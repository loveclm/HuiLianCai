<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class provider_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('provider_model');
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
            $this->global['pageTitle'] = '供货商列表';
            $this->global['pageName'] = 'provider';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['address'] = '';
            $data['searchStatus'] = '0';

            $this->loadViews("user_manage/provider", $this->global, $data, NULL);
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
                // get top list data in homepage
                switch ($id) {
                    case 1:
                        $header = array("供货商账号", "供货商名称", "所属县区", "联系人", "联系电话",
                            "订单数量", "推荐业务员", "禁用状态", "新增日期", "操作");
                        $cols = 10;
                        $contentList = $this->provider_model->getItems($searchData);

                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有供货商." : '';
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
                    $saleman_info = $this->user_model->getUserInfo($item->saleman);
                    $cnt = $this->provider_model->getOrderCount($item->userid);

                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->userid . '</td>';
                    $output_html .= '<td>' . $item->username . '</td>';
                    $output_html .= '<td>' . $item->address . '</td>';
                    $output_html .= '<td>' . $item->contact_name . '</td>';
                    $output_html .= '<td>' . $item->contact_phone . '</td>';
                    $output_html .= '<td>' . $cnt . '</td>';
                    $output_html .= '<td>' . $saleman_info->username . '</td>';
                    $output_html .= '<td>';
                    $output_html .= ($item->status == 1) ? '未禁用' : '已禁用';

                    $output_html .= '</td>';
                    $output_html .= '<td>' . $item->create_time . '</td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="'. base_url(). 'showProvider/' . $item->userid . '">查看 &nbsp;&nbsp;</a>';
                    $output_html .= '<a href="'. base_url(). 'editProvider/' . $item->userid . '">编辑 &nbsp;&nbsp;</a>';
                    if ($item->status == 1) {
                        $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->userid . '\',2)">禁用 &nbsp;&nbsp;</a>';
                    } else {
                        $output_html .= '<a href="#" onclick="deleteConfirm(\'' . $item->userid . '\')">删除 &nbsp;&nbsp;</a>';
                        $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->userid . '\',1)">解除禁用 &nbsp;&nbsp;</a>';
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

    /**
     * This function is used to load the user list
     */
    function additem()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '新增供货商';
            $this->global['pageName'] = 'provider_add';
            if(empty($_POST)){
                $data['salemans'] = $this->user_model->getOperatingUsers();
                $this->loadViews("user_manage/provider_add", $this->global, $data, NULL);

            }else{
                $this->provider_validate();
            }
        }
    }

    function provider_validate(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('userid', '供货商账号', 'trim|required|exact_length[11]');
        $this->form_validation->set_rules('orderID', '初始密码', 'required|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('provider_name', '供货商名称', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('address_district', '所属县区', 'trim|required');
        $this->form_validation->set_rules('address_detail', '详细地址', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('contact_name', '联系人', 'trim|required|min_length[2]|max_length[5]');
        $this->form_validation->set_rules('contact_phone', '联系电话', 'trim|required|exact_length[12]');
        $this->form_validation->set_rules('content', '公司简介', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('cert_no', '营业执照编号', 'trim|required|numeric|exact_length[15]');
        $this->form_validation->set_rules('ratio', '平台佣金', 'trim|required|decimal|less_than[5]|greater_than[0]');

        $id = $this->input->post('provider_id');
        $provider = new stdClass();
        $provider->userid = ucwords(strtolower($this->input->post('userid')));
        $provider->orderID = $this->input->post('orderID');
        $provider->username = $this->input->post('provider_name');
        $provider->address = $this->input->post('provinceName');
        $provider->address .= ','.$this->input->post('cityName');
        $provider->address .= ','.$this->input->post('districtName');
        $provider->address .= ','.$this->input->post('address_detail');
        $provider->contact_name = $this->input->post('contact_name');
        $provider->contact_phone = $this->input->post('contact_phone');
        $provider->saleman = $this->input->post('saleman');
        $provider->ratio = floatval($this->input->post('ratio'))/100;

        $tmp_data = new stdClass();
        $tmp_data->content = $this->input->post('content');
        $tmp_data->logo = $this->input->post('logo');
        $tmp_data->cert_no = $this->input->post('cert_no');
        $tmp_data->cert = $this->input->post('cert');

        $brand_count = $this->input->post('brand_count');
        $brand = array();
        for($i = 1 ; $i <= $brand_count; $i++) {
            array_push($brand, json_decode($this->input->post('brand'.$i)));
        }

        $tmp_data->brand = json_encode($brand);

        $provider->more_data = json_encode($tmp_data);

        if ($this->form_validation->run() == FALSE) {
            $this->global['id'] = $id;
            $this->global['provider'] = $provider;
            // the user list that manages site

            $data['salemans'] = $this->user_model->getOperatingUsers();

            $this->loadViews("user_manage/provider_add", $this->global, $data, NULL);
        } else {

            $user = array(
                'userid' => $provider->userid,
                'password' => getHashedPassword($provider->orderID),
                'username' => $provider->username,
                'role' => 2,    // get provider roleId
                'level' => 2    // provider ? system ?
            );

            $userInfo = array(
                'userid' => $provider->userid,
                'type'=> 2,    // get user type
                'username' => $provider->username,
                'orderID' => $provider->orderID,
                'address' => $provider->address,
                'contact_name' => $provider->contact_name,
                'contact_phone' => $provider->contact_phone,
                'saleman' => $provider->saleman,
                'ratio' => $provider->ratio,
                'more_data' => $provider->more_data
            );
            if($id == ''){ // new user
                $result = $this->user_model->addNewUser($user, $userInfo);
                if ($result == 0 ) {
                    $this->global['id'] = $id;
                    $this->global['provider'] = $provider;
                    $data['salemans'] = $this->user_model->getOperatingUsers();

                    $this->session->set_flashdata('error', '该账号不存在。');
                    $this->loadViews("user_manage/provider_add", $this->global, $data, NULL);
                }
            }else {    //update user
                $result = $this->user_model->updateUser($user, $userInfo);
            }
            redirect('provider');
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
            echo $this->provider_model->add($result);
        }
    }

    /**
     * This function is used to load the user list
     */
    function deleteItemFromDB()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $result = $_POST['itemInfo'];
            echo $this->provider_model->delete($result);
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
            $this->global['pageTitle'] = '编辑供货商';
            $this->global['pageName'] = 'provider_edit';
            $this->global['id'] = 1;
            $this->global['provider'] = $this->user_model->getProviderInfos($this->user_model->getIdByUserId($userId));
            $data['salemans'] = $this->user_model->getOperatingUsers();

            $this->loadViews("user_manage/provider_add", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = '供货商详情';
            $this->global['pageName'] = 'provider_show';

            $this->global['provider'] = $this->user_model->getProviderInfos($this->user_model->getIdByUserId($userId));
            $data['salemans'] = $this->user_model->getOperatingUsers();

            $this->loadViews("user_manage/provider_detail", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function upload_img()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '上传图片';
            $this->global['pageName'] = 'provider_upload';
            $data['ret_Url'] = 'provider_edit';

            $this->loadViews("uploading_img", $this->global, $data, NULL);
        }
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