<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

require_once('./application/libraries/REST_Controller.php');

/**
 * Tourist Area API controller
 *
 * Validation is missign
 */
class Shop_Authorization extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_util_model');
        $this->load->model('product_model');
        $this->load->model('order_model');
        $this->load->model('activity_model');
        $this->load->model('shop_model');
        $this->load->model('carousel_model');
        $this->load->model('user_model');
        $this->load->model('news_model');
        $this->load->model('coupon_model');
        $this->load->model('shipping_model');
    }
    //////////////--------the rest API related with shop information and login----------------////////////////////
    /*
     *  This function is used to process the shop's register request
     *  shop's mobile phone number, business phone number, password
     */
    public function register_shop_post(){
        $param = $this->post();

        $userid = isset($param['phone']) ? $param['phone'] : '';
        $saleman_mobile = isset($param['saleman']) ? $param['saleman'] : '';
        $passwd = isset($param['password']) ? $param['password'] : '';

        //validation authorization
        if( strlen($userid)!= 11  ||  strlen($passwd) < 6 || strlen($passwd) >20 ){
            $this->response(array('status' => false, 'err_code'=> 1 , 'error' => 'This user information format is wrong.'), 200);
            return;
        }
        // check user information conflict
        if( $this->user_model->isExistUserId($userid, 1)){
            $this->response(array('status' => false, 'err_code'=> 2, 'error' => 'This user already is registered in server.'), 200);
            return;
        }
        // register user information
        $result = $this->user_model->addNewShop($userid, getHashedPassword($passwd), $saleman_mobile);
        if($result > 0) {
            $this->response(array('status' => true, 'err_code' => 0, 'error' => 'register successful.'), 200);
            // add shop register message to message list
            $news_data = array(
                'sender' => $this->user_model->getIdByUserId($userid),
                'receiver' => 0,
                'type' => '便利店注册',
                'message' => '手机号 ：' . $userid
            );
            $this->news_model->add($news_data);

            // coupon add
            $coupon_info = array(
                'user_id' => $userid,
                'coupon' => 30,
                'using' => 0
            );
            $this->coupon_model->add($coupon_info);
        }else
            $this->response(array('status' => false, 'err_code'=> 3, 'error' => 'server is busy.'), 200);
    }

    // This function is used to process the shop's authoiraregister request
    public function authorization_info_post(){
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';
        $shop_type = isset($param['type']) ? $param['type'] : '';
        $username = isset($param['name']) ? $param['name'] : '';
        $address = isset($param['address']) ? $param['address'] : '';
        $contact_name = isset($param['contact_name']) ? $param['contact_name'] : '';
        $contact_phone = isset($param['contact_phone']) ? $param['contact_phone'] : '';
        $logo = isset($param['logo']) ? $param['logo'] : '';
        $cert_number = isset($param['cert_num']) ? $param['cert_num'] : '';
        $cert = isset($param['cert']) ? $param['cert'] : '';

        // validation user authorization information
        if(strlen($userid) == 0 || strlen($shop_type) == 0 || strlen($username) == 0 || strlen($address) == 0 ||
            strlen($contact_name) == 0 || strlen($contact_phone) == 0 || strlen($logo) == 0 || strlen($cert) == 0 || strlen($cert_number) == 0){
            $this->response(array('status' => false, 'err_code'=> 1 , 'error' => 'This user information is wrong.'), 200);
        }
        // check user
        if( $this->user_model->isExistUserId($userid, 1) == false){
            $this->response(array('status' => false, 'err_code'=> 2, 'error' => 'This user does not exist in server.'), 200);
        }
        // register user authorization information
        $more_data = new stdClass();
        $more_data->logo = $logo;
        $more_data->cert = $cert;
        $more_data->cert_num = $cert_number;
        $userinfo = array(
            'userid' => $userid,
            'auth' => 2,
            'shop_type' => $shop_type,
            'username' => $username,
            'address' => $address,
            'contact_name' => $contact_name,
            'contact_phone' => $contact_phone,
            'update_time' =>  date("Y-m-d"),
            'more_data' => json_encode($more_data)
        );

        $insert_id = $this->shop_model->update($userinfo, $userid);
        if( $insert_id > 0) {
            $this->response(array('status' => true, 'err_code' => 0, 'error' => 'successful.'), 200);
            // add authorization request message to message list

            $news_data = array(
                'sender' => $this->user_model->getIdByUserId($userid),
                'receiver' => 0,
                'type' => '企业认证申请',
                'message' => '您有个用户申请企业认证，请及时审核。'
            );
            $this->news_model->add($news_data);
            // coupon set
            $coupon_info = array(
                'id' => $userid,
                'userid' => $userid,
                'use_time' => date('Y-m-d H:i:s')
            );
            $this->coupon_model->update($coupon_info,$this->user_model->getIdByUserId($userid));

        }else
            $this->response(array('status' => false, 'err_code'=> 3, 'error' => 'server is busy.'), 200);
    }

    public function check_auth_post(){
        $param = $this->post();
        $userid = $param['phone'];
        // check user
        if( $this->user_model->isExistUserId($userid, 1) == false){
            $this->response(array('status' => false, 'err_code'=> 2, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        $user = $this->shop_model->getItemById($userid);

        $this->response(array('status' => true, 'data'=> (($user->auth == 3) ? true : false)), 200);
    }
    /**
     *  This function is used to process the shop's password reset request
     * @reset password : code = 1, phone, old password, new password
     * @forgot password : code = 2, phone, '1' + new password , new password
     */
    public function reset_password_post(){
        $param = $this->post();
        $req_code = intval(isset($param['code']) ? $param['code'] : '');
        $userid = isset($param['phone']) ? $param['phone'] : '';
        $old_password = isset($param['old']) ? $param['old'] : '';
        $new_password = isset($param['new']) ? $param['new'] : '';

        // validation check
        if( strlen($new_password) < 6 || strlen($new_password)> 20) {
            $this->response(array('status' => false, 'err_code' => 1, 'error' => 'This new password formaat is wrong.'), 200);
            return;
        }
        // check user
        if( $this->user_model->isExistUserId($userid, 1) == false){
            $this->response(array('status' => false, 'err_code'=> 2, 'error' => 'This user does not exist in server.'), 200);
            return;
        }

        switch ($req_code){
            case 1:
                $user = $this->shop_model->getItemById($userid);
                if($user == NULL)
                    $this->response(array('status' => false, 'err_code'=> 2, 'error' => 'This user does not exist in server.'), 200);
                if( !verifyHashedPassword($old_password, $user->password))
                    $this->response(array('status' => false, 'err_code'=> 4, 'error' => 'This old password is wrong.'), 200);
                break;
            case 2:
                if(substr($old_password,1) != $new_password )
                    $this->response(array('status' => false, 'err_code'=> 5, 'error' => 'forgot password request format is wrong.'), 200);
                break;
        }

        $userinfo = array(
            'userid' => $userid,
            'update_time' => date("Y-m-d"),
            'password' => getHashedPassword($new_password)
        );

        $insert_id = $this->shop_model->update($userinfo, $userid);
        if( $insert_id > 0) {
            $this->response(array('status' => true, 'err_code' => 0, 'error' => 'successful.'), 200);
            // add shop password reset message to message list
            //$news_data = array(
            //    'sender' => $this->user_model->getIdByUserId($userid),
            //    'receiver' => 0,
            //    'type' => '密码修改申请',
            //    'message' => $userid . '修改了密码'
            //);
            //$this->news_model->add($news_data);
        }else
            $this->response(array('status' => false, 'err_code'=> 3, 'error' => 'server is busy.'), 200);
    }

    // This function is used to process the shop's login request
    public function login_post(){
        $param = $this->post();
        $userid = isset($param['phone']) ? $param['phone'] : '';
        $password = isset($param['password']) ? $param['password'] : '';
        if(strlen($userid) != 11 || strlen($password) < 5 || strlen($password)>20)
            $this->response(array('status' => false, 'err_code'=> 1, 'error' => 'This password format is wrong.'), 200);

        $user = $this->shop_model->getItemById($userid);
        if($user == NULL) {
            $this->response(array('status' => false, 'err_code' => 2, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        $coupon = $this->coupon_model->getStatus($this->user_model->getIdByUserId($userid));
        if( verifyHashedPassword($password, $user->password)) {
            $more_data = json_decode($user->more_data);

            if( $user->auth == 3) {
                $userinfo = array(
                    'id' => $user->id,
                    'number' => $more_data->cert_num,
                    'cert_image' => $more_data->cert,
                    'user_name' => $user->username,
                    'user_phone' => $user->userid,
                    'user_addr' => $user->address,
                    'user_image' => $more_data->logo,
                    'status' => ($user->auth == '3') ? true : false,
                    'wallet' => $user->balance,
                    'coupon' => $coupon
                );
            }else {
                $userinfo = array(
                    'id' => $user->id,
                    'user_phone' => $user->userid,
                    'status' => false,
                );
            }
            $this->response(array('status' => true, 'user_data' => $userinfo, 'err_code' => 0, 'error' => 'login successful.'), 200);
        }else
            $this->response(array('status' => false, 'err_code'=> 4, 'error' => 'This password is wrong.'), 200);
    }

    // This function is used to process the shipping user's login request
    public function shipping_login_post(){
        $param = $this->post();
        $userid = isset($param['userid']) ? $param['userid'] : '';
        $password = isset($param['password']) ? $param['password'] : '';

        $user = $this->shop_model->getItemById($userid);
        if($user == NULL) {
            $this->response(array('status' => false, 'err_code' => 2, 'error' => 'This user does not exist in server.'), 200);
            return;
        }
        $provider_info = $this->user_model->getProviderInfos($user->provider_id);

        if( verifyHashedPassword($password, $user->password)) {
                $userinfo = array(
                    'id' => $user->id,
                    'userid' => $user->userid,
                    'user_name' => $user->username,
                    'user_phone' => $user->contact_phone,
                    'user_image' => $user->more_data,
                    'provider_name' => $provider_info->username,
                    'provider_address' => $provider_info->address,
                    'provider_contact_phone' => $provider_info->contact_phone,
                    'provider_contact_name' => $provider_info->contact_name,
                    'ship_amount' => $this->shipping_model->getShippingCount($user->id)
                );
            $this->response(array('status' => true, 'user_data' => $userinfo, 'err_code' => 0, 'error' => 'login successful.'), 200);
        }else
            $this->response(array('status' => false, 'err_code'=> 4, 'error' => 'This password is wrong.'), 200);
    }

    //////////////--------the rest API related with shop information and login----------------////////////////////
    // this function is used to process menu information request
    public function menuAndCarouselInfos_post(){
        $carouselData = $this->carousel_model->getCarouselItems();
        $menuData = $this->getMenuInfos();

        $this->response(array('status' => true, 'carouselData' => $carouselData, 'menuData' => $menuData), 200);
    }
    // this function is used to get menu information
    function getMenuInfos(){
        // get all product type list
        $records = $this->product_util_model->productTypeList();
        foreach ($records as $key => $record){
            $brands = $this->product_util_model->getProductBrandList($record->id);
            if( $brands == NULL ) {
                unset($records[$key]);
            }else{
                $records[$key]->brand = $brands;
            }
        }
        // get recommended brand list
        $record = new stdClass();
        $record->id = 0;
        $record->name = '推荐';
        $record->brand = $this->product_util_model->getRecommendedBrandList();
        array_unshift($records, $record);

        return $records;
    }

}

/* End of file Areas.php */
/* Location: ./application/controllers/api/Areas.php */