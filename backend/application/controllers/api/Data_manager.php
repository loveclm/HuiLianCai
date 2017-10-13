<?php defined('BASEPATH') OR exit('No direct script access allowed');
//header('Access-Control-Allow-Origin: *');

require_once('./application/libraries/REST_Controller.php');

/**
 * Tourist Area API controller
 *
 * Validation is missign
 */
class Data_manager extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_util_model');
        $this->load->model('product_model');
        $this->load->model('order_model');
        $this->load->model('activity_model');
        $this->load->model('shop_model');
        $this->load->model('user_model');
        $this->load->model('news_model');
        $this->load->model('coupon_model');
        $this->load->model('transaction_model');
        $this->load->model('feedback_model');
        $this->load->model('favorite_model');
        $this->load->model('shipping_model');
    }
    //////////////--------the rest API related with shop information and login----------------////////////////////

    /**
     * this function is used to get activity information along with product type and product brand
     * here, product type includes product original type and recommended type
     * brand is the same with original information
     * @param type : 0 => recommend,  1+ => type
     * @param brand : 1+ => brand index
     **/
    public function activityInfosByTypeAndBrand_post(){
        $param = $this->post();
        $type = $param['type'];
        $brand = $param['brand'];

        // first, get all activities that type is $type and brand is $brand among the started activities
        $activities = $this->activity_model->getActivityInfosByTypeAndBrand($type, $brand);

        $records = array();
        foreach ($activities as $activity){
            $record = $this->getActiviyInfo($activity);
            array_push($records, $record);
        }

        return $this->response(array('status' => true, 'products'=> $records), 200);
    }

    function getActiviyInfo($activity){
        // extra information processing
        if($activity->kind == 2) {
            $more_data = json_decode($activity->more_data);
            $cover = json_decode($more_data->cover);
            $images = json_decode($more_data->images);
            $content = $more_data->contents;
        }else{
            $product = $this->product_model->getItemById($activity->product_id);
            $cover = json_decode($product->cover);
            $images = json_decode($product->images);
            $content = $product->contents;
        }

        $logos = array();
        foreach ($images as $image) {
            array_push($logos, $image[1]);
        }
        // calculate progress status and the information of the shops that ordered this activity
        $mans = array();
        if($activity->users == '')
            $shop_infos = array();
        else
            $shop_infos = $this->shop_model->getShopInfosByIds($activity->users);

        $nums = explode(',', $activity->nums);
        $cur_amount = 0;
        foreach ($nums as $num){
            $cur_amount += intval($num);
        }
        $progress = (count($shop_infos)*100)/intval($activity->man_cnt);
        foreach ($shop_infos as $shop){
            $shop_more_data = json_decode($shop->more_data);
            $man = array(
                'id' => $shop->id,
                'name' => $shop->username,
                'image' => isset($shop_more_data->logo) ? $shop_more_data->logo : '',
                'ordered_time' => $this->order_model->getOrderTimeByShop_ActivityId($shop->id, $activity->activity_id)
            );

            array_push($mans, $man);
        }

        // get the information of the products with this activity
        $ids = explode(',',$activity->product_id);
        $buy_cnts = json_decode($activity->buy_cnt);
        $product_infos = array();
        $i = 0;
        foreach ($ids as $id){
            $product = $this->product_model->getItemById($id);
            $product_info = array(
                'product_name' => $product->name,
                'amount' => $buy_cnts[$i]->count,
                'price' => number_format((float)$buy_cnts[$i]->cost, 2,'.','')
            );
            array_push($product_infos, $product_info);
            $i++;
        }

        $providerinfo = $this->user_model->getUserInfoByid($activity->provider_id);
        $more_data = json_decode($providerinfo->more_data);

        // record configuration
        $record = array(
            'id' => $activity->activity_id,
            'end_time' => $activity->end_time,
            'product_image' => $cover[1],
            'product_name' => $activity->activity_name,
            'info_size' => $activity->unit_note . ($activity->unit_note == '' ? '' : '/'). $this->product_util_model->getUnitNameById($activity->unit),
            'info_box' => $activity->group_cnt . $this->product_util_model->getUnitNameById($activity->unit) . '起拼',
            'mans' => $activity->man_cnt,
            'amount'=> $activity->group_cnt,
            'grouping_status' =>$activity->status,
            'cur_amount' => $cur_amount,
            'min_amount' => 1,
            'progress' => $progress,
            'old_price' => number_format((float)$activity->origin_cost,2, '.',''),
            'new_price' => number_format((float)$activity->group_cost,2, '.',''),
            'logos' => $logos,
            'text_html' => $content,
            'man_info' => $mans,
            'provider_name' => $providerinfo->username,
            'provider_address' => $providerinfo->address,
            'provider_contact_name' => $providerinfo->contact_name,
            'provider_contact_phone' => $providerinfo->contact_phone,
            'provider_content' => $more_data->content,
            'total_info' => $product_infos
        );

        return $record;
    }

    /*
     *  This function is used to process the shop's transaction information request
     *  shop's mobile phone number,
     */
    public function shop_transactions_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        //validation authorization
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        // get transaction information
        $transactions = $this->transaction_model->getShopTransactionInfos($this->user_model->getIdByUserId($userid));
        $userinfo = $this->shop_model->getItemById($userid);
        $wallet = $userinfo->balance;
        $this->response(array('status' => true, 'data' => $transactions, 'wallet' => $wallet), 200);
    }

    /*
     *  This function is used to send the shop's news information
     *  shop's mobile phone number,
     */
    public function shop_news_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        //validation authorization
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        // get transaction information
        $news = $this->news_model->getItems($this->user_model->getIdByUserId($userid));
        $records = array();
        $ids = '';

        foreach ($news as $message) {
            if ($ids != '') $ids .= ',';
            $record = array(
                'content' => $message->message,
                'sent_time' => $message->create_time
            );
            array_push($records, $record);
        }
        if($ids != '')
            $this->news_model->delete_messages($ids);

        $this->response(array('status' => true, 'data' => $records), 200);
    }

    /*
     *  This function is used to send the shop's order information
     *  shop's mobile phone number,
     */
    public function shop_orders_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        //validation authorization
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        $records = array();
        // get transaction information
        $orders = $this->order_model->getOrdersByShop($this->user_model->getIdByUserId($userid));
        foreach ($orders as $order) {
            $record = $this->getOrderInfo($order);
            array_push($records, $record);
        }

        $this->response(array('status' => true, 'data' => $records), 200);
    }

    // this function is used to get all the information of the order
    function getOrderInfo($order){
        $activity = $this->activity_model->getItemById($order->activity_ids);
        $product_infos = $this->activity_model->getProductsFromIds($activity->product_id, $activity->provider_id);

        $buy_cnt = json_decode($activity->buy_cnt);
        $products = array();
        $i = 0;
        foreach ($product_infos as $product_info) {
            $product_logo = json_decode($product_info->cover);
            $product = array(
                'id' => $product_info->id,
                'name' => $product_info->name,
                'barcode' => $product_info->barcode,
                'image' => $product_logo[1],
                'old_price' => $product_info->cost,
                'new_price' => $buy_cnt[$i]->cost,
                'amount' => $buy_cnt[$i]->count
            );
            $i++;
            array_push($products, $product);
        }

        // get activity logo
        if ($activity->kind == 2) {
            $more_data = json_decode($activity->more_data);
            $activity_logo = json_decode($more_data->cover);
        } else if ($activity->kind = 1) {
            $activity_logo = json_decode($product_infos[0]->cover);
        }
        // grouping status
        $goruping_status = $order->isSuccess;
        if ($order->status == 2) {
            $goruping_status = 3;
        }

        $providerinfo = $this->user_model->getUserInfoByid($order->provider);
        $provider_moreData = json_decode($providerinfo->more_data);
        $shipman_info = $this->user_model->getUserInfoByid($order->ship_man);
        $shop_info = $this->user_model->getUserInfoByid($order->shop);

        // calculate progress status and the information of the shops that ordered this activity
        $mans = array();
        if($activity->users == '')
            $shop_infos = array();
        else
            $shop_infos = $this->shop_model->getShopInfosByIds($activity->users);

        $nums = explode(',', $activity->nums);
        $cur_amount = 0;
        foreach ($nums as $num){
            $cur_amount += intval($num);
        }
        $progress = (count($shop_infos)*100)/intval($activity->man_cnt);
        foreach ($shop_infos as $shop){
            $shop_more_data = json_decode($shop->more_data);
            $man = array(
                'id' => $shop->id,
                'name' => $shop->username,
                'image' => isset($shop_more_data->logo) ? $shop_more_data->logo : '',
                'ordered_time' => $this->order_model->getOrderTimeByShop_ActivityId($shop->id, $activity->activity_id)
            );

            array_push($mans, $man);
        }

        $record = array(
            'id' => $order->id,
            'name' => $activity->name,
            'logo' => $activity_logo[1],
            'status' => $order->status,
            'grouping_status' => $goruping_status,
            'group_success' => $order->isSuccess,
            'pay_type' => $order->pay_method,
            'pay_wallet' => $order->pay_wallet,
            'pay_coupon' => $order->coupon,
            'pay_price' => number_format(floatval($order->pay_cost) - floatval($order->pay_wallet), 2, '.', ''),
            'ordered_time' => $order->create_time,
            'paid_time' => $order->create_time,
            'closed_time' => $order->cancel_time,
            'refunded_time' => $order->refund_time,
            'distributed_time' => $order->ship_time,
            'success_time' => $order->success_time,
            'completed_time' => $order->complete_time,
            'amount' => $order->activity_cnts,
            'dist_name' => isset($shipman_info->username) ? $shipman_info->username :'' ,
            'dist_phone' => isset($shipman_info->contact_phone) ? $shipman_info->contact_phone :'',
            'provider_name' => $providerinfo->username,
            'provider_address' => $providerinfo->address,
            'provider_contact_name' => $providerinfo->contact_name,
            'provider_contact_phone' => $providerinfo->contact_phone,
            'provider_content' => $provider_moreData->content,
            'shop_name' => $shop_info->username,
            'shop_address' => $shop_info->address,
            'shop_contact' => $shop_info->contact_name,
            'shop_contact_phone' => $shop_info->contact_phone,
            'products' => $products,
            'man_info' => $mans,
            'man_cnt' => $activity->man_cnt
        );
        return $record;
    }

    /*
     *  This function is used to send the shop's order request
     *  shop's mobile phone number,
     */
    public function shop_order_request_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        $activity_id = isset($param['activity']) ? $param['activity'] : '';
        $actvity_cnt = isset($param['count']) ? $param['count'] : '';
        $pay_method = isset($param['pay_method']) ? $param['pay_method'] : '';


        //validation authorization
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        // get activity information
        $activity = $this->activity_model->getItemById($activity_id);
        if ($activity == NULL) {
            $this->response(array('status' => false, 'err_code' => 7, 'error' => 'This activity id is wrong.'), 200);
            return;
        }

        // if pay method is 'after pay' then jump grouping status
        $orderinfo = array(
            'shop' => $this->user_model->getIdByUserId($userid),
            'provider' => $activity->provider_id,
            'pay_method' => $pay_method,
            'activity_ids' => $activity_id,
            'activity_cnts' => $actvity_cnt,
            'origin_cost' => number_format(floatval($activity->origin_cost) * intval($actvity_cnt), 2, '.', ''),
            'group_cost' => number_format(floatval($activity->group_cost) * intval($actvity_cnt), 2, '.', ''),
            'status' => ($pay_method == 1) ? 1 : 2,
        );

        $order_id = $this->order_model->add($orderinfo);
        $this->response(array('status' => true, 'data' => $order_id), 200);
    }

    /*
     *  This function is used to send the shop's order request
     *  shop's mobile phone number,
     */
    public function shop_pay_order_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        $order_id = isset($param['order']) ? $param['order'] : '';
        $coupon = isset($param['coupon']) ? $param['coupon'] : '';
        $wallet = isset($param['wallet']) ? $param['wallet'] : '';
        $money = isset($param['money']) ? $param['money'] : '';

        if( floatval($money) == 0 && floatval($wallet) == 0 && floatval($coupon) == 0){
            $this->response(array('status' => false, 'err_code' => 11, 'error' => 'This order is rejected in server.'), 200);
            return;
        }
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        // get activity information
        $order = $this->order_model->getItemById($order_id);
        if ($order == NULL) {
            $this->response(array('status' => false, 'err_code' => 7, 'error' => 'This order id is wrong.'), 200);
            return;
        }

        $orderinfo = array(
            'id' => $order->id,
            'coupon' => $coupon,
            'pay_wallet' => $wallet,
            'pay_cost' => number_format(floatval($money) + floatval($wallet), 2, '.', ''),
            'pay_time' => date('Y-m-d H:i:s'),
            'status' => 2
        );

        $this->order_model->update($orderinfo, $order_id);

        $activity = $this->activity_model->getItemById($order->activity_ids);
        $userinfo = $this->shop_model->getItemById($userid);

        //add pay status message to message list
        $news_data = array(
            'sender' => $order->shop,
            'receiver' => $order->provider,
            'type' => '购买话动',
            'message' => '"' . $userinfo->username . '" 参加了话动(' . $activity->name . ')'
        );
        $this->news_model->add($news_data);

        //add transaction to message list
        $transaction_info = array(
            'order_id' => $order->id,
            'provider_id' => $order->provider,
            'shop_id' => $order->shop,
            'money' => number_format(floatval($money) + floatval($wallet), 2, '.', ''),
            'pay_type' => 1,
            'note' => '订单付款'
        );
        $transaction_id = $this->transaction_model->add($transaction_info);

        // add shop id and count to activity record
        $activity_info = array(
            'users' => $activity->users . (($activity->users == '') ? '' : ',') . $order->shop,
            'nums' => $activity->nums . (($activity->nums == '') ? '' : ',') . $order->activity_cnts
        );
        $this->activity_model->update($activity_info, $order->activity_ids);

        // minus money from shop's wallet
        if (floatval($wallet) > 0) {
            $user_info = array(
                'userid' => $userid,
                'balance' => number_format(floatval($userinfo->balance) - floatval($wallet), 2, '.', '')
            );
            $this->shop_model->update($user_info, $userid);
        }
        //  reset coupon using state
        if (floatval($coupon) > 0) {
            $coupon_info = array(
                'using' => 2,
                'use_time' => date('Y-m-d H:i:s'),
                'coupon' => 30
            );
            $this->coupon_model->update($coupon_info, $this->user_model->getIdByUserId($userid));
        }
        $this->response(array('status' => true, 'data' => $transaction_id), 200);
    }

    /*
     *  This function is used to send the shop's order request
     *  shop's mobile phone number,
     */
    public function shop_order_cancel_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        $order_id = isset($param['order']) ? $param['order'] : '';
        $reason = isset($param['note']) ? $param['note'] : '';

        //validation authorization
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        // get activity information
        $order = $this->order_model->getItemById($order_id);
        if ($order == NULL) {
            $this->response(array('status' => false, 'err_code' => 7, 'error' => 'This order id is wrong.'), 200);
            return;
        }

        $userinfo = $this->shop_model->getItemById($userid);
        $activity = $this->activity_model->getItemById($order->activity_ids);

        // processing cancel order
        $orderinfo = array(
            'id' => $order->id,
            'cancel_time' => date('Y-m-d H:i:s'),
            'cancel_reason' => $reason,
            'status' => 5
        );

        $user_info = array('balance' => $userinfo->balance);

        if($order->status > 2){
            $this->response(array('status' => false, 'err_code' => 11, 'error' => 'This request is rejected in server.'), 200);
            return;
        }

        if ($order->status == 2) {
            $orderinfo['status'] = 6;
            $orderinfo['refund_cost'] = number_format(floatval($order->pay_cost)-floatval($order->coupon), 2,'.','');
            $orderinfo['refund_time'] = date('Y-m-d H:i:s');
            $orderinfo['complete_time'] = date('Y-m-d H:i:s');
            //$orderinfo['pay_cost'] = '0.00';
            //$orderinfo['pay_wallet'] ='0.00';
            //$orderinfo['coupon'] = '0.00';

            //add transaction to message list
            $transaction_info = array(
                'order_id' => $order->id,
                'provider_id' => $order->provider,
                'shop_id' => $order->shop,
                'money' => '-' . $orderinfo['refund_cost'],
                'pay_type' => 1,
                'note' => '全额退款（取消订单）'
            );
            $this->transaction_model->add($transaction_info);

            // plus refund cost with shop's wallet balance
            $user_info = array(
                'userid' => $userid,
                'balance' => number_format(floatval($userinfo->balance) + floatval($order->pay_cost), 2, '.', '')
            );
            $this->shop_model->update($user_info, $userid);
            // return coupon using state
            if (floatval($order->coupon) > 0) {
                $coupon_info = array(
                    'using' => 1,
                    'use_time' => '',
                );
                $this->coupon_model->update($coupon_info, $this->user_model->getIdByUserId($userid));
            }
            // remove shop id in activity's user list
            $users = explode(',', $activity->users);
            $cnts = explode(',', $activity->nums);
            $n = 0;
            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i] == $order->shop) {
                    $n = $i;
                    break;
                }
            }
            unset($users[$n]);
            unset($cnts[$n]);

            $activity_info = array(
                'id' => $order->activity_ids,
                'users' => implode(',', $users),
                'nums' => implode(',', $cnts)
            );
            $this->activity_model->update($activity_info, $order->activity_ids);

        }

        $this->order_model->update($orderinfo, $order_id);

        //add pay status message to message list
        $news_data = array(
            'sender' => $order->shop,
            'receiver' => $order->provider,
            'type' => '取消订单',
            'message' => '"' . $userinfo->username . '" 取消了订单 ：' . $activity->name
        );
        $this->news_model->add($news_data);

        $this->response(array('status' => true, 'coupon' => $this->coupon_model->getStatus($this->user_model->getIdByUserId($userid)),
            'wallet' => $user_info['balance'], 'data' => 'order canceling success'), 200);
    }

    // this function is used to get shop's wallet
    public function get_shop_wallet_post()
    {
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $userinfo = $this->shop_model->getItemById($userid);

        $this->response(array('status' => true, 'wallet' => $userinfo->balance,
                              'coupon' => $this->coupon_model->getStatus($this->user_model->getIdByUserId($userid))), 200);
    }

    /**
     *  this function is used to set favorite activity or provider for shop
     * @param(type)   0 : favorite activity, 1: favorite provider
     */
    function shop_set_favorite_post()
    {
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';
        $type = isset($param['type']) ? $param['type'] : '';
        $favorite_id = isset($param['object_id']) ? $param['object_id'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $favorite_info = array(
            'shop' => $userid,
            'type' => $type,
            'favorite_id' => $favorite_id
        );
        $insert_id = $this->favorite_model->add($favorite_info);

        // if favorite thing is activity then,  favorite provider together
        if ($type == 0) {
            $activity = $this->activity_model->getItemById($favorite_id);

            if($activity != NULL) {
                $favorite_info = array(
                    'shop' => $userid,
                    'type' => 1,
                    'favorite_id' => $activity->provider_id
                );

                $this->favorite_model->add($favorite_info);
            }
        }
        $this->response(array('status' => true, 'favorite_id' => $insert_id, 'data' => 'favorite add success'), 200);
    }

    // this function is used to free favorite
    function shop_free_favorite_post()
    {
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';
        $favorite_id = isset($param['favorite_id']) ? $param['favorite_id'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        // remove favorite item
        $this->favorite_model->delete($favorite_id);

        $this->response(array('status' => true, 'data' => 'request success'), 200);
    }

    /**
     *  this function is used to get favorite information
     */
    function shop_favoriteItems_post()
    {
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $datas = array();
        $favorites = $this->favorite_model->getItems($userid);
        foreach ($favorites as $favorite) {
            switch ($favorite->type) {
                case '0':   // activity
                    $activity = $this->activity_model->getItemById($favorite->favorite_id);
                    $data = $this->getActiviyInfo($activity);

                    break;
                case '1':   // provider
                    $provider_info = $this->user_model->getProviderInfos($favorite->favorite_id);
                    if($provider_info == NULL) continue;

                    $activities = $this->activity_model->getProviderProducts($favorite->favorite_id);
                    $records = array();
                    foreach ($activities as $activity){
                        $record = $this->getActiviyInfo($activity);

                        array_push($records, $record);
                    }

                    $provider_moredata = json_decode($provider_info->more_data);
                    $provider_image = json_decode($provider_moredata->logo);
                    $provider_logos = json_decode($provider_moredata->brand);
                    $p_logos = array();
                    foreach ($provider_logos as $provider_logo){
                        array_push($p_logos, $provider_logo[1]);
                    }
                    $data = array(
                        'id' => $provider_info->id,
                        'name' => $provider_info->username,
                        'image' => $provider_image[1],
                        'logos' => $p_logos,
                        'address' => $provider_info->address,
                        'contact_name' => $provider_info->contact_name,
                        'contact_phone' => $provider_info->contact_phone,
                        'content' => $provider_moredata->content,
                        'products' => $records
                    );
                    break;
            }

            $data_record = array(
                'id' => $favorite->id,
                'type' => $favorite->type,
                'object_id' => $favorite->favorite_id,
                'detail' => $data
            );

            array_push($datas, $data_record);
        }
        $this->response(array('status' => true, 'data' => $datas), 200);
    }

//    // this function is used to manage user's feedback
//    function shop_feedback_post(){
//        $param = $this->post();
//        $userid = isset($param['userid']) ? $param['userid'] : '';
//        $msg = isset($param['feedback']) ? $param['feedback'] : '';
//
//        if ($this->user_model->isExistUserId($userid, 1) == false) {
//            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
//            return;
//        }
//
//        $feedback_info = array(
//            'userid' => $userid,
//            'feedback' => $msg
//        );
//        $this->feedback_model->add($feedback_info);
//
//        $this->response(array('status' => true, 'data' => 'request success'), 200);
//    }
//
//    // this function is used to finish shipping
//    function shipping_complete_post(){
//        $param = $this->post();
//        $userid = isset($param['userid']) ? $param['userid'] : '';
//        $order_id = isset($param['order_id']) ? $param['order_id'] : '';
//
//        if ($this->user_model->isExistUserId($userid, 1) == false) {
//            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
//            return;
//        }
//        // change order status
//        $order = $this->order_model->getItemById($order_id);
//        if($order== NULL) {
//            $this->response(array('status' => false, 'err_code' => 8, 'error' => 'Order Number is wrong.'), 200);
//            return;
//        }
//
//        $money = ($order->isSuccess == 1) ? $order->group_cost : $order->origin_cost;
//        $order_info = array(
//            'status' => 4,
//            'complete_time' => date('Y-m-d H:i:s'),
//        );
//
//        if($order->pay_method == 1){
//            $msg = '订单号'. $order_id . '，线上付款金额'. number_format(floatval($order->pay_cost) - floatval($order->refund_cost),2,'.','') ;
//            $msg .= '元，配送完成。';
//        }else{
//            $order_info['pay_cost'] = $money;
//            $order_info['pay_time']  = date('Y-m-d H:i:s');
//
//            //add transaction to message list
//            $transaction_info = array(
//                'order_id' => $order->id,
//                'provider_id' => $order->provider,
//                'shop_id' => $order->shop,
//                'money' => $money,
//                'pay_type' => 2,
//                'note' => '订单付款(货到付款)'
//            );
//            $this->transaction_model->add($transaction_info);
//
//            $msg = '订单号'. $order_id . '，货到付款金额'. $money .'元，配送员'. $money .'已收款。';
//        }
//        $this->order_model->update($order_info, $order_id);
//
//        $news_data = array(
//            'sender' => $order->shop,
//            'receiver' => $order->provider,
//            'type' => '订单消息',
//            'message' => $msg
//        );
//        $this->news_model->add($news_data);
//
//        $this->response(array('status' => true, 'data' => 'request success'), 200);
//    }
//
//    // this function is used to get shipping items
//    function get_shippingItems_post(){
//        $param = $this->post();
//        $userid = $param['userid'];
//
//        $data = array(
//            'start' => date('Y-m-d'),
//            'end' => date('Y-m-d'),
//            'ship_id' => $this->user_model->getIdByUserId($userid)
//        );
//
//        $records = array();
//        $shippings  = $this->shipping_model->getShippingItems( $data);
//        foreach ($shippings as $shipping_info){
//            $ids = explode(',', $shipping_info->order_id);
//            foreach ($ids as $order_id){
//                $order = $this->order_model->getItemById($order_id);
//                $record = $this->getOrderInfo($order);
//
//                array_push($records, $record);
//            }
//        }
//
//        $this->response(array('status' => true, 'data' => $records), 200);
//    }
//
//    // this function is used to get shipping history items
//    function get_shippingHistory_post(){
//        $param = $this->post();
//        $userid = $param['userid'];
//        $start = $param['start_date'];
//        $end = $param['end_date'];
//
//        $data = array(
//            'start' => $start, //date('Y-m-d'),
//            'end' => $end, //date('Y-m-d'),
//            'ship_id' => $this->user_model->getIdByUserId($userid)
//        );
//
//        $records = array();
//        $shippings  = $this->shipping_model->getShippingItems( $data);
//        foreach ($shippings as $shipping_info){
//            $ids = explode(',', $shipping_info->order_id);
//            foreach ($ids as $order_id){
//                $order = $this->order_model->getItemById($order_id);
//                $record = $this->getOrderInfo($order);
//
//                array_push($records, $record);
//            }
//        }
//
//        $this->response(array('status' => true, 'data' => $records), 200);
//    }
//
//    // this function is used to get activity's status
//    function get_ActivityStatus_post()
//    {
//        $param = $this->post();
//        $activity_ids = isset($param['ids']) ? $param['ids'] : array();
//
//        $status = $this->activity_model->getActivityStatus(implode(',',$activity_ids));
//
//        $this->response(array('status' => true, 'data' => $status), 200);
//    }
//
//    // this function is used to set shop's location
//    function setLocation_post()
//    {
//        $param = $this->post();
//        $userid = isset($param['phone']) ? $param['phone'] : '';
//        $lat = isset($param['lat']) ? $param['lat'] : '';
//        $lon = isset($param['lon']) ? $param['lon'] : '';
//
//        if ($this->user_model->isExistUserId($userid, 1) == false) {
//            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
//            return;
//        }
//
//        if(floatval($lat) == 0 || floatval($lon) == 0){
//            $this->response(array('status' => false, 'err_code' => 9, 'error' => 'Location data is wrong.'), 200);
//            return;
//        }
//        $pos = array(
//            'lat' => $lat,
//            'lon' => $lon
//        );
//        $userinfo = array(
//            'location' => json_encode($pos)
//        );
//        $this->shop_model->update($userinfo, $userid);
//        $this->response(array('status' => true, 'data' => 'request success'), 200);
//    }
//
//    // this functioin is used to get activity's detail information
//    function activity_detail_post()
//    {
//        $param = $this->post();
//        $activity_id = isset($param['activity']) ? $param['activity'] : '';
//
//        $activity = $this->activity_model->getItemById($activity_id);
//        if($activity == NULL) {
//            $this->response(array('status' => false, 'err_code'=> 10, 'error' => 'activity is wrong.'), 200);
//            return;
//        }
//        $record = $this->getActiviyInfo($activity);
//        $this->response(array('status' => true, 'data' => $record), 200);
//    }
//
//    // this function is used to get provider's detail inforrmation
//    function provider_detail_post()
//    {
//        $param = $this->post();
//        $provider_id = isset($param['provider']) ? $param['provider'] : '';
//
//        $provider_info = $this->user_model->getProviderInfos($provider_id);
//        if($provider_info == NULL) {
//            $this->response(array('status' => false, 'err_code'=> 10, 'error' => 'provider is wrong.'), 200);
//            return;
//        }
//
//        $activities = $this->activity_model->getProviderProducts($provider_id);
//        $records = array();
//        foreach ($activities as $activity){
//            $record = $this->getActiviyInfo($activity);
//
//            array_push($records, $record);
//        }
//
//        $provider_moredata = json_decode($provider_info->more_data);
//        $provider_image = json_decode($provider_moredata->logo);
//        $provider_logos = json_decode($provider_moredata->brand);
//        $p_logos = array();
//        foreach ($provider_logos as $provider_logo){
//            array_push($p_logos, $provider_logo[1]);
//        }
//        $data = array(
//            'id' => $provider_info->id,
//            'name' => $provider_info->username,
//            'image' => $provider_image[1],
//            'logos' => $p_logos,
//            'address' => $provider_info->address,
//            'contact_name' => $provider_info->contact_name,
//            'contact_phone' => $provider_info->contact_phone,
//            'provider_content' => (json_decode($provider_info->more_data))->content,
//            'products' => $records
//        );
//
//        $this->response(array('status' => true, 'data' => $data), 200);
//    }

    function test_post(){
        $param = $this->post();
        $this->response(array('data' => $param), 200);
    }

    function template_post()
    {
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $userinfo = $this->shop_model->getItemById($userid);
        $this->response(array('status' => true, 'data' => 'request success'), 200);
    }

}

/* End of file Areas.php */
/* Location: ./application/controllers/api/Areas.php */