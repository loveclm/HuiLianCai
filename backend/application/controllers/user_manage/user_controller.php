<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class user_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->user_list();
    }

    /**
     * This function is used to load the user list
     */
    function user_list()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '运营人员列表';
            $this->global['pageName'] = 'userlist';

            $data['searchType'] = '0';
            $data['searchName'] = '';

            $this->loadViews("user_manage/users", $this->global, $data, NULL);
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
                $searchData['kind'] = 1;
                $searchData['login_user'] = $this->login_id;

                switch ($id) {
                    case 1:
                        $header = array("序号", "账号", "姓名", "角色", "新增时间", "操作");
                        $cols = 6;

                        $contentList = $this->user_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有数据." : '';
                        break;
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

    function output_content($allLists, $id)
    {
        $output_html = '';
        if (!isset($allLists) || count($allLists) == 0) return '';
        // make list id: 1-main menu
        $i = 0;
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $i++;
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $i . '</td>';
                    $output_html .= '<td>' . $item->userid . '</td>';
                    $output_html .= '<td>' . $item->username . '</td>';
                    $output_html .= '<td>' . $this->user_model->getRoleNameById($item->role) . '</td>';
                    $output_html .= '<td>' . $item->create_time . '</td>';

                    $output_html .= '<td>';
                    if($item->id != $this->login_id){
                        $output_html .= '<a href="'. base_url(). 'user_edit/' . $item->id . '">编辑 &nbsp;&nbsp;</a>';
                        $output_html .= '<a href="#" onclick="deleteConfirm(\'' . $item->id . '\')">删除 &nbsp;&nbsp;</a>';
                    }
                    $output_html .= '<a href="#" onclick="passwordConfirm(\'' . $item->id . '\')">重置密码 &nbsp;&nbsp;</a>';
                    $output_html .= '</td>';

                    $output_html .= '</tr>';
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
            $this->global['pageTitle'] = '新增用户';
            $this->global['pageName'] = 'user_add';

            if(empty($_POST)){
                $data['roles'] = $this->user_model->getRoles();

                $this->loadViews("user_manage/user_add", $this->global, $data, NULL);
            }else{
                $this->item_validate();
            }
        }
    }

    function item_validate(){
        $this->load->library('form_validation');

        $id = intval($this->input->post('id'));

        $this->form_validation->set_rules('userid', '账号', 'trim|required|max_length[20]|xss_clean');
        $this->form_validation->set_rules('username', '姓名', 'trim|required|xss_clean|max_length[20]');
        if( $id == 0) {
            $this->form_validation->set_rules('password', '密码', 'required|matches[cpassword]|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('cpassword', '确认密码', 'required|matches[password]|min_length[6]|max_length[20]');
        }
        $this->form_validation->set_rules('role', '用户角色', 'trim|required|numeric|is_natural_no_zero');

        $item = new stdClass();
        $item->username = $this->input->post('username');
        $item->role = $this->input->post('role');
        if($id == 0) {
            $item->userid = $this->input->post('userid');
            $item->password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');
        }

        $data['roles'] = $this->user_model->getRoles();
        if ($this->form_validation->run() == FALSE) {
            if($id == 0) {
                $item->password = '';
                if($this->user_model->isExistUserId($item->userid) )
                    $this->session->set_flashdata('error', '用户创建失败. 此帐户已存在了.');
            }
            $this->global['model'] = $item;

            $this->loadViews("user_manage/user_add", $this->global, $data, NULL);
        } else {
            $userInfo = $item;
            if($id == 0)
                $userInfo->password = getHashedPassword($item->password);

            $userInfo->parent_id = $this->login_id;
            if($id > 0) $userInfo->id = $id;

            $this->user_model->addSystemUser($userInfo);

            redirect('userlist');
        }
    }
    /**
     * This function is used to edit the user information
     */
    function edititem($Id)
    {
        if ($this->isAdmin() == TRUE ) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑用户';
            $this->global['pageName'] = 'user_add';
            $data['roles'] = $this->user_model->getRoles();

            $this->global['model'] =  $this->user_model->getSystemUserById($Id);
            $this->loadViews("user_manage/user_add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to show the user information
     */
    function showitem($Id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '单品活动详情';
            $this->global['pageName'] = 'user_detail';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();

            $data['empty'] = NULL;
            $provider_id = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';
            $item = $this->getItemInfo($Id);
            $userinfo = $this->user_model->getProviderInfos($item->provider_id);
            $item->provider_name = $userinfo->username;
            $item->provider_userid = $userinfo->userid;

            $this->global['brandlist'] = $this->product_util_model->getProductBrandList($item->type);
            $this->global['datalist'] = $this->user_model->getProductList($item->type, $item->brand, $provider_id);

            $this->global['model'] = $item;
            $this->loadViews("user_manage/user_detail", $this->global, $data, NULL);
        }
    }

    function getPorductNamesByBrand(){
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        if(!empty($_POST)){
            $type = $_POST['type'];
            $brand = $_POST['brand'];
            $provider_id = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';

            $ret['content'] = $this->user_model->getProductList($type, $brand, $provider_id);
            if($ret['content'] != NULL) $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function getProductDetailInfo(){
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        if(!empty($_POST)){
            $id = $_POST['id'];
            $item = $this->product_model->getItemById($id);

            $ret['content'] = $item;
            if($ret['content'] != NULL) $ret['status'] = 'success';
        }
        echo json_encode($ret);
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
            echo $this->user_model->add($result);
        }
    }

    /**
     * This function is used to load the user list
     */
    function deleteItemFromDB()
    {
        if ($this->isAdmin()) {
            $this->loadThis();
        } else {
            echo $this->user_model->deleteSystemUser($_POST['id']);
        }
    }

    /**
     * This function is used to change the password of the user
     */
    function updateUserPassword()
    {
        $id = $this->input->post('id');
        $password = $this->input->post('password');
        $usersData = array('password' => getHashedPassword($password));

        $result = $this->user_model->changePassword($id, $usersData);

        if ($result > 0) {
            echo(json_encode(array('status' => TRUE)));
        } else {
            echo(json_encode(array('status' => FALSE)));
        }

        //redirect('userListing');
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
            $this->global['pageName'] = 'user_upload';
            $data['ret_Url'] = 'user_edit';

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