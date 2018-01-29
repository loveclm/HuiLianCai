<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

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
    public function activityInfosByTypeAndBrand_post()
    {
        $param = $this->post();
        $type = $param['type'];
        $brand = $param['brand'];
        $userid = isset($param['phone']) ? $param['phone'] : '';

        $filter_addr = '';
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) $filter_addr = '';
        if ($userid != '') {
            $userinfo = $this->shop_model->getItemById($userid);
            $filter_addr = $userinfo->filter_addr;
        }

        // first, get all activities that type is $type and brand is $brand among the started activities
        $activities = $this->activity_model->getActivityInfosByTypeAndBrand($type, $brand);
        $records = array();
        foreach ($activities as $activity) {
            if ($filter_addr != '') {
                $providerinfo = $this->user_model->getUserInfoByid($activity->provider_id);
                if (strpos($providerinfo->address, $filter_addr) === false) continue;
            }

            $record = $this->getActiviyInfo($activity);
            array_push($records, $record);
        }

        return $this->response(array('status' => true, 'products' => $records), 200);
    }

    function getActiviyInfo($activity)
    {
        // extra information processing
        if ($activity->kind == 2) {
            $more_data = json_decode($activity->more_data);
            $cover = json_decode($more_data->cover);
            $images = json_decode($more_data->images);
            $content = $more_data->contents;
        } else {
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

        if (trim($activity->users) == '')
            $shop_infos = array();
        else
            $shop_infos = $this->shop_model->getShopInfosByIds($activity->users);

        $nums = explode(',', $activity->nums);
        $cur_amount = 0;
        foreach ($nums as $num) {
            $cur_amount += intval($num);
        }
        $progress = (count($shop_infos) * 100) / intval($activity->man_cnt);
        foreach ($shop_infos as $shop) {
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
        $ids = explode(',', $activity->product_id);
        $buy_cnts = json_decode($activity->buy_cnt);
        $product_infos = array();
        $i = 0;
        $max_cnt = 10000;
        foreach ($ids as $id) {
            $product = $this->product_model->getItemById($id);
            $product_info = array(
                'product_name' => $product->name,
                'amount' => $buy_cnts[$i]->count,
                'price' => number_format((float)$buy_cnts[$i]->cost, 2, '.', ''),
                'old_price' => $product->cost,
                'store' => $product->store,
                'unit_name' => $this->product_util_model->getUnitNameById($product->unit),
                'standard' => $product->standard
            );
            $tmp_cnt = intval($product->store) / intval($product_info['amount']);
            if ($tmp_cnt < $max_cnt) $max_cnt = $tmp_cnt;

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
            'product_name' => ((floatval($activity->group_cost) == 0) ? '(赠送) ' : '') . $activity->activity_name,
            'info_size' => $activity->unit_note . ($activity->unit_note == '' ? '' : '/') . $this->product_util_model->getUnitNameById($activity->unit),
            'info_box' => $activity->group_cnt . $this->product_util_model->getUnitNameById($activity->unit) . '起拼',
            'mans' => $activity->man_cnt,
            'amount' => $activity->group_cnt,
            'grouping_status' => $activity->status,
            'cur_amount' => $cur_amount,
            'min_amount' => $activity->group_cnt,
            'max_amount' => $max_cnt,
            'progress' => $progress,
            'old_price' => number_format((float)$activity->origin_cost, 2, '.', ''),
            'new_price' => number_format((float)$activity->group_cost, 2, '.', ''),
            'logos' => $logos,
            'text_html' => $content,
            'man_info' => $mans,
            'provider_id' => $providerinfo->id,
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
            if ($record == NULL) continue;
            array_push($records, $record);
        }
        $this->response(array('status' => true, 'data' => $records), 200);
    }

    // this function is used to get all the information of the order
    function getOrderInfo($order)
    {
        $activity = $this->activity_model->getItemById($order->activity_ids);
        $product_infos = json_decode($order->product_info);

        $buy_cnt = json_decode($activity->buy_cnt);
        $products = array();
        $i = 0;
        $max_cnt = 10000;
        foreach ($product_infos as $product_info) {
            $product_logo = json_decode($product_info->cover);
            $product = array(
                'id' => $product_info->id,
                'name' => $product_info->name,
                'barcode' => $product_info->barcode,
                'store' => $product_info->store,
                'standard' => $product_info->standard,
                'unit_name' => $this->product_util_model->getUnitNameById($product_info->unit),
                'image' => $product_logo[1],
                'old_price' => $product_info->cost,
                'new_price' => $buy_cnt[$i]->cost,
                'amount' => $buy_cnt[$i]->count
            );
            $tmp_cnt = intval($product_info->store) / intval($product['amount']);
            if ($tmp_cnt < $max_cnt) $max_cnt = $tmp_cnt;

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
        if ($activity->users == '')
            $shop_infos = array();
        else
            $shop_infos = $this->shop_model->getShopInfosByIds($activity->users);

        $nums = explode(',', $activity->nums);
        $cur_amount = 0;
        foreach ($nums as $num) {
            $cur_amount += intval($num);
        }
        $progress = (count($shop_infos) * 100) / intval($activity->man_cnt);
        foreach ($shop_infos as $shop) {
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
            'activity_id' => $order->activity_ids,
            'name' => ((floatval($order->group_cost) == 0) ? '(赠送) ' : '') . $activity->activity_name,
            'logo' => $activity_logo[1],
            'cur_amount' => $cur_amount,
            'min_amount' => $activity->group_cnt,
            'max_amount' => intval($max_cnt) - intval($cur_amount),
            'status' => $order->status,
            'note' => $order->note,
            'grouping_status' => $goruping_status,
            'group_success' => $order->isSuccess,
            'old_price' => $order->origin_cost,
            'new_price' => $order->group_cost,
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
            'dist_name' => isset($shipman_info->username) ? $shipman_info->username : '',
            'dist_phone' => isset($shipman_info->contact_phone) ? $shipman_info->contact_phone : '',
            'provider_id' => $providerinfo->id,
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
        $activity_cnt = isset($param['count']) ? $param['count'] : '';
        $pay_method = isset($param['pay_method']) ? $param['pay_method'] : '1';
        $note = isset($param['note']) ? $param['note'] : '';

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

        if ($activity == NULL || $activity->status != 2) {
            $this->response(array('status' => false, 'err_code' => 8, 'error' => 'This activity grouping is ended.'), 200);
            return;
        }

        // get activity information
        $activity->products = $this->activity_model->getProductsFromIds($activity->product_id, $activity->provider_id);

        // if pay method is 'after pay' then jump grouping status
        $orderinfo = array(
            'shop' => $this->user_model->getIdByUserId($userid),
            'provider' => $activity->provider_id,
            'pay_method' => $pay_method,
            'activity_ids' => $activity_id,
            'activity_cnts' => $activity_cnt,
            'origin_cost' => number_format(floatval($activity->origin_cost) * intval($activity_cnt), 2, '.', ''),
            'group_cost' => number_format(floatval($activity->group_cost) * intval($activity_cnt), 2, '.', ''),
            'status' => ($pay_method == 1) ? 1 : 2,
            'note' => $note,
            'product_info' => json_encode($activity->products)
        );

        $order_id = $this->order_model->add($orderinfo);

        // in the case of the after pay, change maninfo and product counter
        if ($pay_method == '2') {
            $userinfo = $this->shop_model->getItemById($userid);

            //add pay status message to message list
            $news_data = array(
                'sender' => $orderinfo['shop'],
                'receiver' => $orderinfo['provider'],
                'type' => '购买话动',
                'message' => '"' . $userinfo->username . '" 参加了话动(' . $activity->activity_name . ')'
            );
            //$this->news_model->add($news_data);

            // add shop id and count to activity record
            $activity_info = array();
            if ($activity->users != '') {
                $users = explode(',', $activity->users);
                $nums = explode(',', $activity->nums);

                $flag = 0;
                for ($i = 0; $i < count($users); $i++) {
                    if ($users[$i] == $orderinfo['shop']) {
                        $nums[$i] = intval($activity_cnt) + intval($nums[$i]);
                        $flag = 1;
                        break;
                    }
                }
                if ($flag == 0) {
                    $activity_info = array(
                        'users' => $activity->users . ',' . $orderinfo['shop'],
                        'nums' => $activity->nums . ',' . $activity_cnt
                    );
                } else {
                    $activity_info = array(
                        'users' => implode(',', $users),
                        'nums' => implode(',', $nums)
                    );
                }
            } else {
                $activity_info = array(
                    'users' => $orderinfo['shop'],
                    'nums' => $activity_cnt
                );
            }

            $this->activity_model->update($activity_info, $activity_id);

            $this->notify_shop_activity($activity_id, $userinfo->id, $activity->activity_name);
            $this->checkActivity($order_id);
        }

        $this->response(array('status' => true, 'data' => $order_id), 200);
    }

    public function shop_order_requests_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        $activity_ids = isset($param['activity']) ? json_decode($param['activity']) : array();
        $activity_cnts = isset($param['count']) ? json_decode($param['count']) : array();
        $pay_method = isset($param['pay_method']) ? $param['pay_method'] : '1';
        $note = isset($param['note']) ? $param['note'] : '';

        //validation authorization
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $orderIDList = array();
        for ($i = 0; $i < count($activity_ids); $i++) {
            $activity_id = $activity_ids[$i];
            $activity_cnt = $activity_cnts[$i];

            // get activity information
            $activity = $this->activity_model->getItemById($activity_id);
            if ($activity == NULL) {
                array_push($orderIDList, '');
                continue;
            }

            if ($activity == NULL || $activity->status != 2) {
                array_push($orderIDList, '');
                continue;
            }

            // get activity information
            $activity->products = $this->activity_model->getProductsFromIds($activity->product_id, $activity->provider_id);

            // if pay method is 'after pay' then jump grouping status
            $orderinfo = array(
                'shop' => $this->user_model->getIdByUserId($userid),
                'provider' => $activity->provider_id,
                'pay_method' => $pay_method,
                'activity_ids' => $activity_id,
                'activity_cnts' => $activity_cnt,
                'origin_cost' => number_format(floatval($activity->origin_cost) * intval($activity_cnt), 2, '.', ''),
                'group_cost' => number_format(floatval($activity->group_cost) * intval($activity_cnt), 2, '.', ''),
                'status' => ($pay_method == '1') ? 1 : 2,
                'note' => $note,
                'product_info' => json_encode($activity->products)
            );
            $order_id = $this->order_model->add($orderinfo);
            array_push($orderIDList, $order_id);
            // in the case of the after pay, change maninfo and product counter
            if ($pay_method == '2') {
                $userinfo = $this->shop_model->getItemById($userid);

                // add shop id and count to activity record
                $activity_info = array();
                if ($activity->users != '') {
                    $users = explode(',', $activity->users);
                    $nums = explode(',', $activity->nums);

                    $flag = 0;
                    for ($k = 0; $k < count($users); $k++) {
                        if ($users[$k] == $orderinfo['shop']) {
                            $nums[$k] = intval($activity_cnt) + intval($nums[$k]);
                            $flag = 1;
                            break;
                        }
                    }
                    if ($flag == 0) {
                        $activity_info = array(
                            'users' => $activity->users . ',' . $orderinfo['shop'],
                            'nums' => $activity->nums . ',' . $activity_cnt
                        );
                    } else {
                        $activity_info = array(
                            'users' => implode(',', $users),
                            'nums' => implode(',', $nums)
                        );
                    }
                } else {
                    $activity_info = array(
                        'users' => $orderinfo['shop'],
                        'nums' => $activity_cnt
                    );
                }
                $this->activity_model->update($activity_info, $activity_id);
                $this->notify_shop_activity($activity_id, $userinfo->id, $activity->activity_name);
                $this->checkActivity($order_id);
            }
        }
        $this->response(array('status' => true, 'data' => $orderIDList), 200);
    }

    // this function is used to check the activity's status
    function checkActivity($order_id)
    {
        $orderinfo = $this->order_model->getItemById($order_id);
        $activity = $this->activity_model->getItemById($orderinfo->activity_ids);
        if ($activity->users == '') return;

        $users = explode(',', $activity->users);

        if (count($users) >= intval($activity->man_cnt)) {
            $status = 3;
            // change activity's status
            $activity_info = array(
                'id' => $activity->activity_id,
                'status' => $status,
                'order' => 1000,
                'recommend_status' => 0
            );
            $this->activity_model->update($activity_info, $activity->activity_id);
            // change order's status
            $orders = $this->order_model->getOrderByActivityId($activity->activity_id);
            if ($orders == NULL) return;

            foreach ($orders as $order) {
                if ($order->status == 1) {
                    $order_info = array(
                        'status' => 5,
                        'cancel_time' => date('Y-m-d H:i:s')
                    );
                    $this->order_model->update($order_info, $order->id);
                    continue;
                }

                $order_info = array(
                    'status' => 3,
                    'isSuccess' => $status - 2,
                    'success_time' => date('Y-m-d H:i:s')
                );
                $provider_info = $this->user_model->getProviderInfos($order->provider);

                $money = number_format(floatval($order->pay_cost) - floatval($order->group_cost), 2, '.', '');
                if ($status == 3) {
                    if ($order->pay_method == 1 && floatval($money) > 0) {
                        // refund extra money
                        $order_info['refund_cost'] = $money;
                        $order_info['refund_time'] = date('Y-m-d H:i:s');
                        $result_id = $this->order_model->update($order_info, $order->id);
                        if(!$result_id) continue;

                        $news_data = array(
                            'sender' => $order->provider,
                            'receiver' => $order->shop,
                            'type' => '拼团活动消息',
                            'message' => '恭喜 ' . $activity->activity_name . '商品拼单成功，本次为您节省' . $money . '元，金额请从我的钱包中查看。'
                        );
                        $this->news_model->add($news_data);

                        //add transaction to message list
                        $transaction_info = array(
                            'order_id' => $order->id,
                            'provider_id' => $order->provider,
                            'shop_id' => $order->shop,
                            'money' => '-' . $money,
                            'pay_type' => 1,
                            'note' => $activity->activity_name . '拼单成功返现'
                        );
                        $this->transaction_model->add($transaction_info);

                        $userinfo = $this->user_model->getUserInfoByid($order->shop);
                        $user_info = array(
                            'balance' => floatval($money) + floatval($userinfo->balance)
                        );
                        $this->shop_model->update($user_info, $userinfo->userid);

                        $user_info = array(
                            'balance' => number_format(floatval($provider_info->balance) - floatval($money), 2, '.', '')
                        );
                        $this->shop_model->update($user_info, $provider_info->userid);

                    }
                } else {
                    $this->order_model->update($order_info, $order->id);
                    $news_data = array(
                        'sender' => $order->provider,
                        'receiver' => $order->shop,
                        'type' => '拼团活动消息',
                        'message' => $activity->activity_name . '商品拼单失败，无法返还拼单成功差额，为降低您的彩购成本，请多多发动朋友参与拼单哦。'
                    );
                    $this->news_model->add($news_data);
                }


            }
            // add activity starting message to message list
            $news_data = array(
                'sender' => '0',
                'receiver' => $order->provider,
                'type' => '拼团活动消息',
                'message' => $activity->activity_name . (($status == 3) ? '拼团成功，按拼团价结算，系统已自动退还终端便利店差价。' : '拼团失败，该拼团活动订单按原价结算。')
            );
            $this->news_model->add($news_data);
        }
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
        $note = isset($param['note']) ? $param['note'] : '';
        $pay_method = isset($param['pay_method']) ? $param['pay_method'] : '1';

        if (floatval($money) == 0 && floatval($wallet) == 0 && floatval($coupon) == 0 && $pay_method == '1') {
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
        if( floatval($order->pay_cost) > 0) {
            $this->response(array('status' => false, 'err_code' => 12, 'error' => 'This order already is paid.'), 200);
            return;
        }
        $activity = $this->activity_model->getItemById($order->activity_ids);
        $result_id = "";
        if ($pay_method == '1') {
            $orderinfo = array(
                'id' => $order->id,
                'coupon' => $coupon,
                'pay_wallet' => $wallet,
                'pay_cost' => number_format(floatval($money) + floatval($wallet), 2, '.', ''),
                'pay_time' => date('Y-m-d H:i:s'),
                'status' => 2,
                'note' => $note
            );

            $result_id = $this->order_model->update($orderinfo, $order_id);
        } else if ($pay_method == '2') {
            $orderinfo = array(
                'id' => $order->id,
                'pay_method' => $pay_method,
                'pay_time' => date('Y-m-d H:i:s'),
                'status' => 2,
                'note' => $note
            );
            $result_id = $this->order_model->update($orderinfo, $order_id);
        }
        if(!$result_id){
            $this->response(array('status' => false, 'err_code' => 11, 'error' => 'db written error'), 200);
            return;
        }
        $userinfo = $this->shop_model->getItemById($userid);

        if ($pay_method == '1') {
            //add transaction to message list
            $transaction_info = array(
                'order_id' => $order->id,
                'provider_id' => $order->provider,
                'shop_id' => $order->shop,
                'money' => number_format(floatval($money) + floatval($wallet), 2, '.', ''),
                'pay_type' => 1,
                'note' => '拼单' . $activity->activity_name
            );
            $transaction_id = $this->transaction_model->add($transaction_info);

            $provider_info = $this->user_model->getProviderInfos($order->provider);
            $user_info = array(
                'balance' => number_format(floatval($money) + floatval($wallet) + floatval($provider_info->balance), 2, '.', '')
            );
            $this->shop_model->update($user_info, $provider_info->userid);
        }

        // add shop id and count to activity record
        $activity_info = array();
        if ($activity->users != '') {
            $users = explode(',', $activity->users);
            $nums = explode(',', $activity->nums);

            $flag = 0;
            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i] == $order->shop) {
                    $nums[$i] = intval($order->activity_cnts) + intval($nums[$i]);
                    $flag = 1;
                    break;
                }
            }
            if ($flag == 0) {
                $activity_info = array(
                    'users' => $activity->users . ',' . $order->shop,
                    'nums' => $activity->nums . ',' . $order->activity_cnts
                );
            } else {
                $activity_info = array(
                    'users' => implode(',', $users),
                    'nums' => implode(',', $nums)
                );
            }
        } else {
            $activity_info = array(
                'users' => $order->shop,
                'nums' => $order->activity_cnts
            );
        }
        $this->activity_model->update($activity_info, $order->activity_ids);

        if ($pay_method == '1') {
            if (intval($money) > 0) {
                $user_info = array(
                    'userid' => $userid,
                    'integral' => number_format(intval($userinfo->integral) + intval($money), 2, '.', '')
                );
                $this->shop_model->update($user_info, $userid);
            }

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
        }
        $this->checkActivity($order_id);
        $this->notify_shop_activity($order->activity_ids, $userinfo->id, $activity->activity_name);
        if ($pay_method == '1')
            $this->response(array('status' => true, 'data' => $transaction_id), 200);
        else
            $this->response(array('status' => true, 'data' => $order_id), 200);
    }

    public function shop_pay_orders_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        $order_ids = isset($param['order']) ? json_decode($param['order']) : array();
        $coupons = isset($param['coupon']) ? json_decode($param['coupon']) : array();
        $wallets = isset($param['wallet']) ? json_decode($param['wallet']) : array();
        $moneys = isset($param['money']) ? json_decode($param['money']) : array();
        $note = isset($param['note']) ? $param['note'] : '';
        $pay_method = isset($param['pay_method']) ? $param['pay_method'] : '1';

        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        for ($i = 0; $i < count($order_ids); $i++) {
            $order_id = $order_ids[$i];
            $coupon = $coupons[$i];
            $wallet = $wallets[$i];
            $money = $moneys[$i];

            // get activity information
            $order = $this->order_model->getItemById($order_id);
            if ($order == NULL) continue;
            if( floatval($order->pay_cost) > 0) continue;

            $activity = $this->activity_model->getItemById($order->activity_ids);
            $result_id = "";
            if ($pay_method == '1') {
                $orderinfo = array(
                    'id' => $order->id,
                    'coupon' => $coupon,
                    'pay_wallet' => $wallet,
                    'pay_cost' => number_format(floatval($money) + floatval($wallet), 2, '.', ''),
                    'pay_time' => date('Y-m-d H:i:s'),
                    'status' => 2,
                    'note' => $note
                );

                $result_id = $this->order_model->update($orderinfo, $order_id);
            } else if ($pay_method == '2') {
                $orderinfo = array(
                    'id' => $order->id,
                    'pay_method' => $pay_method,
                    'pay_time' => date('Y-m-d H:i:s'),
                    'status' => 2,
                    'note' => $note
                );
                $result_id = $this->order_model->update($orderinfo, $order_id);
            }
            if(!$result_id) continue;

            $userinfo = $this->shop_model->getItemById($userid);

            if ($pay_method == '1') {
                //add transaction to message list
                $transaction_info = array(
                    'order_id' => $order->id,
                    'provider_id' => $order->provider,
                    'shop_id' => $order->shop,
                    'money' => number_format(floatval($money) + floatval($wallet), 2, '.', ''),
                    'pay_type' => 1,
                    'note' => '拼单' . $activity->activity_name
                );
                $transaction_id = $this->transaction_model->add($transaction_info);

                $provider_info = $this->user_model->getProviderInfos($order->provider);
                $user_info = array(
                    'balance' => number_format(floatval($money) + floatval($wallet) + floatval($provider_info->balance), 2, '.', '')
                );
                $this->shop_model->update($user_info, $provider_info->userid);
            }

            // add shop id and count to activity record
            $activity_info = array();
            if ($activity->users != '') {
                $users = explode(',', $activity->users);
                $nums = explode(',', $activity->nums);

                $flag = 0;
                for ($k = 0; $k < count($users); $k++) {
                    if ($users[$k] == $order->shop) {
                        $nums[$k] = intval($order->activity_cnts) + intval($nums[$k]);
                        $flag = 1;
                        break;
                    }
                }
                if ($flag == 0) {
                    $activity_info = array(
                        'users' => $activity->users . ',' . $order->shop,
                        'nums' => $activity->nums . ',' . $order->activity_cnts
                    );
                } else {
                    $activity_info = array(
                        'users' => implode(',', $users),
                        'nums' => implode(',', $nums)
                    );
                }
            } else {
                $activity_info = array(
                    'users' => $order->shop,
                    'nums' => $order->activity_cnts
                );
            }
            $this->activity_model->update($activity_info, $order->activity_ids);

            if ($pay_method == '1') {
                if (intval($money) > 0) {
                    $user_info = array(
                        'userid' => $userid,
                        'integral' => number_format(intval($userinfo->integral) + intval($money), 2, '.', '')
                    );
                    $this->shop_model->update($user_info, $userid);
                }

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
            }
            $this->checkActivity($order_id);
            $this->notify_shop_activity($order->activity_ids, $userinfo->id, $activity->activity_name);
        }

        if ($pay_method == '1')
            $this->response(array('status' => true, 'data' => $transaction_id), 200);
        else
            $this->response(array('status' => true, 'data' => $order_id), 200);
    }

    function notify_shop_activity($activity_id, $shop_id, $activity_name)
    {
        $shopinfo = $this->user_model->getUserInfoByid($shop_id);
        if ($shopinfo->location == '') return;

        $pos = explode(',', $shopinfo->location);
        $shops = $this->shop_model->getShopsByLocation($pos[0], $pos[1], $shopinfo->filter_addr);
        foreach ($shops as $shop) {
            //add pay status message to message list
            $news_data = array(
                'sender' => 0,
                'receiver' => $shop->id,
                'type' => '通知消息',
                'message' => $shopinfo->username . '刚刚参与了' . $activity_name . '的拼团',
                'note' => $activity_id
            );
            $this->news_model->add($news_data);
        }
    }

    public function getNotifications_post()
    {
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $messages = $this->news_model->getNotifyMsg($this->user_model->getIdByUserId($userid));

        $this->response(array('status' => true, 'data' => $messages), 200);
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
        if($order->cancel_time != null){
            $this->response(array('status' => false, 'err_code' => 12, 'error' => 'This order is already cancelled.'), 200);
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

        if ($order->status == 1) {
            $this->order_model->update($orderinfo, $order_id);

            $this->response(array('status' => true, 'coupon' => $this->coupon_model->getStatus($this->user_model->getIdByUserId($userid)),
                'wallet' => $user_info['balance'], 'data' => 'order canceling success'), 200);
            return;
        }

        if (intval($order->status) > 2) {
            $this->response(array('status' => false, 'err_code' => 8, 'error' => 'This request is rejected in server.'), 200);
            return;
        }

        if ($order->status == 2) {
            $orderinfo['complete_time'] = date('Y-m-d H:i:s');
            if ($order->pay_method == '1') {
                $orderinfo['refund_cost'] = $order->pay_cost;
                $orderinfo['refund_time'] = date('Y-m-d H:i:s');
                $orderinfo['status'] = 6;

                $result_id = $this->order_model->update($orderinfo, $order_id);
                if(!$result_id) {
                    $this->response(array('status' => false, 'err_code' => 13, 'error' => 'This request is rejected in db.'), 200);
                    return;
                }
                //add transaction to message list
                $transaction_info = array(
                    'order_id' => $order->id,
                    'provider_id' => $order->provider,
                    'shop_id' => $order->shop,
                    'money' => '-' . $orderinfo['refund_cost'],
                    'pay_type' => 1,
                    'note' => '退款到账'
                );
                $this->transaction_model->add($transaction_info);

                // plus refund cost with shop's wallet balance
                $user_info = array(
                    'userid' => $userid,
                    'balance' => number_format(floatval($userinfo->balance) + floatval($orderinfo['refund_cost']), 2, '.', '')
                );
                $this->shop_model->update($user_info, $userid);

                $provider_info = $this->user_model->getProviderInfos($order->provider);
                $user_info = array(
                    'balance' => number_format(floatval($provider_info->balance) - floatval($orderinfo['refund_cost']), 2, '.', '')
                );
                $this->shop_model->update($user_info, $provider_info->userid);

                // return coupon using state
                if (floatval($order->coupon) > 0) {
                    $coupon_info = array(
                        'using' => 1,
                        'use_time' => '',
                    );
                    $this->coupon_model->update($coupon_info, $this->user_model->getIdByUserId($userid));
                }
            }else{
                $result_id = $this->order_model->update($orderinfo, $order_id);
                if(!$result_id) {
                    $this->response(array('status' => false, 'err_code' => 13, 'error' => 'This request is rejected in db.'), 200);
                    return;
                }
            }

            // remove shop id in activity's user list
            $users = explode(',', $activity->users);
            $cnts = explode(',', $activity->nums);
            $n = -1;
            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i] == $order->shop) {
                    $n = $i;
                    break;
                }
            }
            if($n >= 0) {
                if ($cnts[$n] == $order->activity_cnts) $cnts[$n] = intval($cnts[$n]) - intval($order->activity_cnts);

                if ($cnts[$n] == 0) {
                    unset($users[$n]);
                    unset($cnts[$n]);
                }

                $activity_info = array(
                    'id' => $order->activity_ids,
                    'users' => implode(',', $users),
                    'nums' => implode(',', $cnts)
                );
                $this->activity_model->update($activity_info, $order->activity_ids);
            }

        }

        //add pay status message to message list
        $news_data = array(
            'sender' => 0,
            'receiver' => $order->shop,
            'type' => '取消订单',
            'message' => $activity->activity_name . ' 商品的退款已返还我的钱包，请查看，我们持续努力为您服务。'
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

            if ($activity != NULL) {
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
        $data = array();
        $favorites = $this->favorite_model->getItems($userid);
        foreach ($favorites as $favorite) {
            switch (intval($favorite->type)) {
                case 0:   // activity
                    $activity = $this->activity_model->getItemById($favorite->favorite_id);
                    $data = $this->getActiviyInfo($activity);

                    break;
                case 1:   // provider
                    $provider_info = $this->user_model->getProviderInfos($favorite->favorite_id);
                    if ($provider_info == NULL) continue;

                    $activities = $this->activity_model->getProviderProducts($favorite->favorite_id);
                    $records = array();
                    foreach ($activities as $activity) {
                        $record = $this->getActiviyInfo($activity);

                        array_push($records, $record);
                    }

                    $provider_moredata = json_decode($provider_info->more_data);
                    $provider_image = json_decode($provider_moredata->logo);
                    $provider_logos = json_decode($provider_moredata->brand);
                    $p_logos = array();
                    foreach ($provider_logos as $provider_logo) {
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

    // this function is used to manage user's feedback
    function shop_feedback_post()
    {
        $param = $this->post();
        $userid = isset($param['userid']) ? $param['userid'] : '';
        $msg = isset($param['feedback']) ? $param['feedback'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $feedback_info = array(
            'userid' => $userid,
            'feedback' => $msg
        );
        $this->feedback_model->add($feedback_info);

        $this->response(array('status' => true, 'data' => 'request success'), 200);
    }

    // this function is used to finish shipping
    function shipping_complete_post()
    {
        $param = $this->post();
        $userid = isset($param['userid']) ? $param['userid'] : '';
        $order_id = isset($param['order_id']) ? $param['order_id'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        // change order status
        $order = $this->order_model->getItemById($order_id);
        if ($order == NULL) {
            $this->response(array('status' => false, 'err_code' => 8, 'error' => 'Order Number is wrong.'), 200);
            return;
        }

        $activity = $this->activity_model->getItemById($order->activity_ids);
        $money = ($order->isSuccess == 1) ? $order->group_cost : $order->origin_cost;
        $order_info = array(
            'status' => 4,
            'ship_status' => 3,
            'complete_time' => date('Y-m-d H:i:s'),
        );

        if ($order->pay_method == '1') {
            $msg = '订单号' . $order_id . '，线上付款金额' . number_format(floatval($order->pay_cost) - floatval($order->refund_cost), 2, '.', '');
            $msg .= '元，配送员' . $userid . '配送完成。';
        } else {
            $order_info['pay_cost'] = $money;
            $order_info['pay_time'] = date('Y-m-d H:i:s');

            //add transaction to message list
            $transaction_info = array(
                'order_id' => $order->id,
                'provider_id' => $order->provider,
                'shop_id' => $order->shop,
                'money' => $money,
                'pay_type' => 2,
                'note' => '拼单' . $activity->activity_name
            );
            $this->transaction_model->add($transaction_info);

            $msg = '订单号' . $order_id . '，货到付款金额' . $money . '元，配送员' . $userid . '已收款。';
        }
        $this->order_model->update($order_info, $order_id);

        $news_data = array(
            'sender' => 0,
            'receiver' => $order->provider,
            'type' => '订单消息',
            'message' => $msg
        );
        $this->news_model->add($news_data);

        $this->response(array('status' => true, 'data' => 'request success'), 200);
    }

    // this function is used to get shipping items
    function get_shippingItems_post()
    {
        $param = $this->post();
        $userid = $param['userid'];

        $data = array(
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d'),
            'ship_id' => $this->user_model->getIdByUserId($userid)
        );

        $records = array();
        $shippings = $this->shipping_model->getShippingItems($data);
        foreach ($shippings as $shipping_info) {
            $ids = explode(',', $shipping_info->order_id);
            foreach ($ids as $order_id) {
                $order = $this->order_model->getItemById($order_id);
                if ($order == NULL) continue;

                $record = $this->getOrderInfo($order);
                array_push($records, $record);
            }
        }

        $this->response(array('status' => true, 'data' => $records), 200);
    }

    // this function is used to get shipping history items
    function get_shippingHistory_post()
    {
        $param = $this->post();
        $userid = $param['userid'];
        $start = $param['start_date'];
        $end = $param['end_date'];

        $data = array(
            'start' => $start, //date('Y-m-d'),
            'end' => $end, //date('Y-m-d'),
            'ship_id' => $this->user_model->getIdByUserId($userid)
        );

        $records = array();
        $shippings = $this->shipping_model->getShippingHistory($data);
        foreach ($shippings as $shipping_info) {
            $ids = explode(',', $shipping_info->order_id);
            foreach ($ids as $order_id) {
                $order = $this->order_model->getItemById($order_id);
                if ($order == NULL) continue;

                if ($order->status == 4) {
                    $record = $this->getOrderInfo($order);
                    array_push($records, $record);
                }
            }
        }

        $this->response(array('status' => true, 'data' => $records), 200);
    }

    // this function is used to get activity's status
    function get_ActivityStatus_post()
    {
        $param = $this->post();
        $activity_ids = isset($param['ids']) ? $param['ids'] : array();

        $status = $this->activity_model->getActivityStatus(implode(',', $activity_ids));

        $this->response(array('status' => true, 'data' => $status), 200);
    }

    // this function is used to set shop's location
    function setLocation_post()
    {
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';
        $lat = isset($param['lat']) ? $param['lat'] : '';
        $lon = isset($param['lon']) ? $param['lon'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        if (floatval($lat) == 0 || floatval($lon) == 0) {
            $this->response(array('status' => false, 'err_code' => 9, 'error' => 'Location data is wrong.'), 200);
            return;
        }
        $pos = array(
            'lat' => $lat,
            'lon' => $lon
        );
        $userinfo = array(
            'location' => json_encode($pos)
        );
        $this->shop_model->update($userinfo, $userid);

        $this->response(array('status' => true, 'data' => 'request success'), 200);
    }

    // this functioin is used to get activity's detail information
    function activity_detail_post()
    {
        $param = $this->post();
        $activity_id = isset($param['activity']) ? $param['activity'] : '';

        $userid = isset($param['phone']) ? $param['phone'] : '';

        $activity = $this->activity_model->getItemById($activity_id);
        if ($activity == NULL) {
            $this->response(array('status' => false, 'err_code' => 10, 'error' => 'activity is wrong.'), 200);
            return;
        }

        $filter_addr = '';
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) $filter_addr = '';
        if ($userid != '') {
            $userinfo = $this->shop_model->getItemById($userid);
            $filter_addr = $userinfo->filter_addr;
        }

        $record = $this->getActiviyInfo($activity);
        if ($filter_addr != '') {
            $providerinfo = $this->user_model->getUserInfoByid($activity->provider_id);
            if (strpos($providerinfo->address, $filter_addr) === false) $record == null;
        }

        $this->response(array('status' => true, 'data' => $record), 200);
    }

    // this function is used to get provider's detail inforrmation
    function provider_detail_post()
    {
        $param = $this->post();
        $provider_id = isset($param['provider']) ? $param['provider'] : '';
        $userid = isset($param['phone']) ? $param['phone'] : '';

        $provider_info = $this->user_model->getProviderInfos($provider_id);
        if ($provider_info == NULL) {
            $this->response(array('status' => false, 'err_code' => 10, 'error' => 'provider is wrong.'), 200);
            return;
        }

        $filter_addr = '';
        // check user information conflict
        if ($this->user_model->isExistUserId($userid, 1) == false) $filter_addr = '';
        if ($userid != '') {
            $userinfo = $this->shop_model->getItemById($userid);
            $filter_addr = $userinfo->filter_addr;
        }

        $activities = $this->activity_model->getProviderProducts($provider_id);
        $records = array();
        foreach ($activities as $activity) {
            if ($filter_addr != '') {
                $providerinfo = $this->user_model->getUserInfoByid($activity->provider_id);
                if (strpos($providerinfo->address, $filter_addr) === false) continue;
            }
            $record = $this->getActiviyInfo($activity);

            array_push($records, $record);
        }

        $provider_moredata = json_decode($provider_info->more_data);
        $provider_image = json_decode($provider_moredata->logo);
        $provider_logos = json_decode($provider_moredata->brand);
        $p_logos = array();
        foreach ($provider_logos as $provider_logo) {
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
            'provider_content' => $provider_moredata->content,
            'products' => $records
        );

        $this->response(array('status' => true, 'data' => $data), 200);
    }

    function shop_cartInfo_post()
    {
        $param = $this->post();
        $type = isset($param['type']) ? $param['type'] : '';
        $userid = isset($param['phone']) ? $param['phone'] : '';
        $cart_info = isset($param['data']) ? $param['data'] : '';

        if ($this->user_model->isExistUserId($userid, 1) == false) {
            $this->response(array('status' => false, 'err_code' => 6, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $data = '';
        switch ($type) {
            case '1':   // set shopping cart information
                $user_info = array(
                    'userid' => $userid,
                    'cart_info' => $cart_info
                );
                $this->shop_model->update($user_info, $userid);
                $data = 'setting success';
                break;
            case '2':   // get shopping cart information
                $userinfo = $this->shop_model->getItemById($userid);
                $data = $userinfo->cart_info;
                if ($data == null) $data = '';
                break;
        }

        $this->response(array('status' => true, 'data' => $data), 200);
    }

    function test_post()
    {
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