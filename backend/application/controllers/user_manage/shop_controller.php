<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class shop_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('shop_model');
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->list_view();
    }

    /**
     * This function is used to load the user list
     */
    function list_view()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '终端便利店列表';
            $this->global['pageName'] = 'shop';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['address'] = '';
            $data['searchStatus'] = '0';
            $data['searchShoptype']='0';
            $data['searchAuth'] = '0';

            $this->loadViews("user_manage/shop", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to get TOP10 Lists
     */
    function item_listing()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $ret = array(
                'content' => '',
                'status' => 'fail'
            );
            if (!empty($_POST)) {
                $id = $_POST['id'];
                $searchData = $_POST['searchData'];
                $searchData['provider_id'] = ($this->global['shop_manager_number'] != '') ? $this->global['shop_manager_number'] : '0';

                // get top list data in homepage
                switch ($id) {
                    case 1:
                        if($this->isTicketter() == FALSE){ // provider
                            $header = array("终端便利店账号", "终端便利店", "类型", "地址", "积分",
                                 "禁用状态", "配送员", "操作");
                            $cols = 8;
                        }else {                            // admin
                            $header = array("终端便利店账号", "终端便利店", "类型", "地址", "推荐人手机号", "积分",
                                "认证状态", "禁用状态", "操作");
                            $cols = 9;
                        }
                        $contentList = $this->shop_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有终端便利店." : '';
                        break; // get top1
                }

                // end get
                $ret['header'] = $this->output_header($header);
                $ret['content'] = $this->output_content($contentList, $id);
                $ret['footer'] = $this->output_footer($footer, $cols);
                $ret['status'] = 'success';
            }
            echo json_encode($ret);
        }
    }

    /**
     * This function used to make list view
     */

    function output_content($allLists, $id)
    {
        $output_html = '';
        if (!isset($allLists) || count($allLists) == 0) return '';
        // ????? make list id: 1-main menu
        $i = 0;
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->userid . '</td>';
                    $output_html .= '<td>' . $item->username . '</td>';
                    $output_html .= '<td>';
                    switch ($item->shop_type){
                        case 1:
                            $output_html .= '便利店' . '</td>';
                            break;
                        case 2:
                            $output_html .= '中型超市' . '</td>';
                            break;
                        case 3:
                            $output_html .= '餐饮店' . '</td>';
                            break;
                        case 4:
                            $output_html .= '其他业态' . '</td>';
                            break;
                    }
                    $output_html .= '<td>' . $item->address . '</td>';
                    if($this->isTicketter() == TRUE) {
                        $output_html .= '<td>' . $item->saleman_mobile . '</td>';
                    }
                    $output_html .= '<td>' . (($item->coupon == 0) ? '' : $item->coupon) . '</td>';
                    if($this->isTicketter() == TRUE) {  // admin
                        $output_html .= '<td>';
                        switch ($item->auth) {
                            case 1:
                                $output_html .= '未认证' . '</td>';
                                break;
                            case 2:
                                $output_html .= '待认证' . '</td>';
                                break;
                            case 3:
                                $output_html .= '认证通过' . '</td>';
                                break;
                            case 4:
                                $output_html .= '认证失败' . '</td>';
                                break;
                        }
                    }
                    $output_html .= '<td>' . (($item->status == 1) ? '未禁用' : '已禁用') . '</td>';
                    if($this->isTicketter() == FALSE){      // provider
                        $output_html .= '<td>' . $this->user_model->getUserName($item->ship_man) . '</td>';
                    }
                    $output_html .= '</td>';
                    $output_html .= '<td>';

                    if($item->auth != 1){
                        $output_html .= '<a href="' . base_url() . 'showShopinfo/' . $item->userid . '">查看 &nbsp;&nbsp;</a>';
                    }

                    if($this->isTicketter() == TRUE) {      // admin
                        if ($item->auth == 2 || $item->auth == 4) {
                            $output_html .= '<a href="#" onclick="authConfirm(\'' . $item->userid . '\')">认证审核 &nbsp;&nbsp;</a>';
                        }

                        if ($item->status == 1) {
                            $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->userid . '\',2)">禁用 &nbsp;&nbsp;</a>';
                        } else {
                            $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->userid . '\',1)">解除禁用 &nbsp;&nbsp;</a>';
                        }
                    }else{                                  // provider
                        if ($item->ship_man == 0) {
                            $output_html .= '<a href="#" onclick="userConfirm(\'' . $item->ship_man . '\')">选择配送员 &nbsp;&nbsp;</a>';
                        }
                    }
                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                    $i++;
                }
                break;
            case 2:
                break;
        }

        return $output_html;
    }

    /**
     * This function used to make list view
     */

    function output_header($allLists)
    {
        $output_html = '';
        if (count($allLists) == 0) return '';
        $output_html .= '<tr>';
        foreach ($allLists as $item) {
            $output_html .= '<th>' . $item . '</th>';
        }
        $output_html .= '</tr>';
        return $output_html;
    }

    /**
     * This function used to make list view
     */

    function output_footer($footer, $cols)
    {
        $output_html = '';
        $output_html .= '<tr>';
        $output_html .= '<td colspan="' . $cols . '">' . $footer . '</td>';
        $output_html .= '</tr>';
        return $output_html;
    }

    function shop_validate(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('userid', '供货商账号', 'trim|required|exact_length[11]');
        $this->form_validation->set_rules('password', '初始密码', 'required|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('shop_name', '供货商名称', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('address_district', '所属县区', 'trim|required');
        $this->form_validation->set_rules('address_detail', '详细地址', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('contact_name', '联系人', 'trim|required|min_length[2]|max_length[5]');
        $this->form_validation->set_rules('contact_phone', '联系电话', 'trim|required|exact_length[12]');
        $this->form_validation->set_rules('content', '公司简介', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('cert_no', '营业执照编号', 'trim|required|numeric|exact_length[15]');
        $this->form_validation->set_rules('percent', '平台佣金', 'trim|required|decimal|less_than[5]|greater_than[0]');

        $shop = new stdClass();
        $shop->userid = ucwords(strtolower($this->input->post('userid')));
        $shop->password = $this->input->post('password');
        $shop->name = $this->input->post('shop_name');
        $shop->address = $this->input->post('provinceName');
        $shop->address .= ','.$this->input->post('cityName');
        $shop->address .= ','.$this->input->post('districtName');
        $shop->address .= ','.$this->input->post('address_detail');
        $shop->contact_name = $this->input->post('contact_name');
        $shop->contact_phone = $this->input->post('contact_phone');
        $shop->saleman = $this->input->post('saleman');

        $tmp_data = new stdClass();
        $tmp_data->content = $this->input->post('content');
        $tmp_data->logo = $this->input->post('logo');
        $tmp_data->cert_no = $this->input->post('cert_no');
        $tmp_data->cert = $this->input->post('cert');
        $tmp_data->percent = $this->input->post('percent');

        $brand_count = $this->input->post('brand_count');
        $brand = array();
        for($i = 1 ; $i <= $brand_count; $i++) {
            array_push($brand, json_decode($this->input->post('brand'.$i)));
        }

        $tmp_data->brand = json_encode($brand);

        $shop->more_data = json_encode($tmp_data);

        if ($this->form_validation->run() == FALSE) {
            $this->global['shop'] = $shop;
            // the user list that manages site
            $salemans = array('aaa','bbb');
            $data['salemans'] = $salemans;

            $this->loadViews("user_manage/shop_add", $this->global, $data, NULL);
        } else {

            $user = array(
                'userid' => $shop->userid,
                'password' => $shop->password,
                'name' => $shop->name,
                'role' => 2,    // get shop roleId
                'create_time' => date('Y-m-d H:i:s')
            );

            $userInfo = array(
                'userid' => $shop->userid,
                'type'=> 2,    // get user type
                'address' => $shop->address,
                'contact_name' => $shop->contact_name,
                'contact_phone' => $shop->contact_phone,
                'saleman' => $shop->saleman,
                'more_data' => $shop->more_data
            );

            $this->user_model->addNewUser($user, $userInfo);
            redirect('user_manage/shop');
        }
    }
    /**
     * This function is used to load the user list
     */
    function addItem2DB($data = null)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $result = $_POST['itemInfo'];
            echo $this->shop_model->add($result);
        }
    }

    /**
     * This function is used to load the user list
     */
    function setAuthFromDB()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $result = $_POST['itemInfo'];
            $result['update_time'] = date("Y-m-d");
            echo $this->shop_model->update($result, $result['userid']);
        }
    }

    /**
     * This function is used to edit the user information
     */
    function edititem($userId)
    {
        if ($this->isAdmin() == TRUE ) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑终端便利店';
            $this->global['pageName'] = 'shop_edit';

            $this->global['shop'] = $this->user_model->getshopInfos($userId);
            $data['salemans'] = $this->user_model->getOperatingUsers();

            $this->loadViews("user_manage/shop_add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to show the user information
     */
    function showitem($userId)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '终端便利店详情';
            $this->global['pageName'] = 'show_shopinfo';

            $this->global['shop'] = $this->shop_model->getshopInfos($userId);
            $data['userid'] = $userId;

            $this->loadViews("user_manage/shop_detail", $this->global, $data, NULL);
        }
    }

    function shop_manlist(){
        $ret = array();
        $header = array("序号", "姓名", "联系电话", "头像", "操作");

        $ship_mans = $this->user_model->getShipMans();
        $ret['header'] = $this->output_header($header);
        $ret['content'] = $ship_mans;
        $footer = '';
        if(count($ship_mans) == 0) $footer = '没有数据.';
        $ret['footer'] = $this->output_footer($footer, 5);

        echo json_encode($ret);
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file shop.php */
/* Location: .application/controllers/shop.php */

?>