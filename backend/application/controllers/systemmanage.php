<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 8 August 2017
 */
class systemmanage extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('shop_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = '系统管理';

        $this->userListing();
    }

    /**
     * This function is used to load the user list
     */
    function userListing()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $searchText = $this->input->post('searchText');
            $searchStatus = $this->input->post('searchStatus');

            $this->load->library('pagination');

            $count = $this->user_model->userListingCount($this->global['level'], $searchText);

            $returns = $this->paginationCompress("userListing/", $count, 5);

            $data['userRecords'] = $this->user_model
                ->userListing($this->global['level'], $searchText, $searchStatus, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = '人员管理';
            $data['searchText'] = $searchText;
            $data['searchStatus'] = $searchStatus;

            $this->loadViews("systemusermanage", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function roleListing()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $type = $this->login_id;
            $data['userRecords'] = $this->user_model->roleListing($type);

            $this->global['pageTitle'] = '角色管理';

            $this->loadViews("systemrolemanage", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $data['roles'] = $this->user_model->getUserRoles();

            $this->global['pageTitle'] = '添加人员';

            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userid");
        $email = $this->input->post("email");

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if (empty($result)) {
            echo("true");
        } else {
            echo("false");
        }
    }

    /**
     * This function is used to add new user to the system
     */
    function addNewUser()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('fname', '账号', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('email', '姓名', 'trim|required|xss_clean|max_length[20]');
            $this->form_validation->set_rules('password', '密码', 'required|matches[cpassword]|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('cpassword', '确认密码', 'required|matches[password]|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('role', '用户角色', 'trim|required|numeric|is_natural_no_zero');
//            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]|xss_clean');

            $name = ucwords(strtolower($this->input->post('fname')));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');
            $roleId = $this->input->post('role');
            $mobile = $this->input->post('mobile');

            if ($this->form_validation->run() == FALSE) {
                $this->global['fname'] = $name;
                $this->global['email'] = $email;
                $this->global['password'] = $password;
                $this->global['cpassword'] = $cpassword;
                $this->global['roleId'] = $roleId;
                $this->addNew();
            } else {
                $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleId' => $roleId + 1, 'name' => $name,
                    'mobile' => $mobile, 'createdBy' => $this->vendorId, 'createdDtm' => date('Y-m-d H:i:s'));

                $result = $this->user_model->addNewUser($userInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', '新用户创建成功.');
                } else {
                    $this->session->set_flashdata('error', '用户创建失败. 此帐户已存在了.');
                }
                redirect('addNew');
            }
        }
    }

    /**
     * This function is used to make tree view to manage user's role
     */
    function roleInfos(){
        $type = ($this->global['shop_manager_number'] == '') ? 1 : 2;
        $data = $this->user_model->getRoleInfos($type);

        echo  json_encode($data);
    }

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

                $type = $this->login_id;

                switch ($id) {
                    case 1:
                        $header = array("序号", "用户角色", "功能设置", "操作");
                        $cols = 4;

                        $contentList = $this->user_model->roleListing($type);
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
                    $output_html .= '<td>' . $item->role . '</td>';
                    $output_html .= '<td><a href="#" onclick="showRoleEdit(' . $item->id .');">操作设置</a>';
                    $output_html .= '<div id="permission'.$item->id.'" style="display: none">'. $item->permission .'</div></td>';

                    $output_html .= '<td><a href="#" data-userid="'. $item->id.'"';
                    if($this->user_model->isRoleDeletable($item->id) == true)
                         $output_html .= 'onclick="confirmDelete('. $item->id .')"> 删除';
                    else
                        $output_html .= 'onclick="$(\'#alert_delete\').show()" style="color:grey;"> 删除';

                    $output_html .= '</a></td>';

                    $output_html .= '</tr>';
                }
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
     * This function is used to add new user to the system
     */
    function addRole()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $roleName = $this->input->post('roleName');
            $result = $this->user_model->findRole($roleName);
            if (count($result) > 0) {
                $this->session->set_flashdata('error', '角色创建失败.');
                echo(json_encode(array('status' => FALSE)));
                return;
            }
            $data = array(
                'role' => $roleName,
                'parent_id' => $this->login_id,
                'permission' => '[]'
            );

            $result = $this->user_model->addNewRole($data);
            if ($result > 0) {
                $this->session->set_flashdata('success', '新角色创建成功.');
                echo  json_encode(array('status' => TRUE));
            } else {
                $this->session->set_flashdata('error', '角色创建失败.');
                echo  json_encode(array('status' => FALSE));
            }
        }
    }

    /**
     * This function is used to edit the user information
     */
    function updateRole()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $roleId = $this->input->post('id');
            $permission = $this->input->post('permission');

            $result = $this->user_model->getRoleById($roleId);
            $roleInfo=$result;
            $roleInfo->permission = $permission;
            $result = $this->user_model->updateRole($roleInfo, $roleId);

            if ($result == true) {
                $this->session->set_flashdata('success', '用户更新成功.');
                echo(json_encode(array('status' => TRUE)));
            } else {
                $this->session->set_flashdata('error', '用户更新失败.');
                echo(json_encode(array('status' => FALSE)));
            }

            //redirect('roleListing');

        }
    }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if ($this->isAdmin() == TRUE || $userId == 1) {
            $this->loadThis();
        } else {
            if ($userId == null) {
                redirect('userListing');
            }

            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);

            $this->global['pageTitle'] = '编辑人员';

            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userid');

            $this->form_validation->set_rules('userid', '账号', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('username', '姓名', 'trim|required|xss_clean|max_length[20]');
            $this->form_validation->set_rules('password', '密码', 'required|matches[cpassword]|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('cpassword', '确认密码', 'required|matches[password]|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('role', '用户角色', 'trim|required|numeric|is_natural_no_zero');

            $name = ucwords(strtolower($this->input->post('userid')));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');
            $roleId = $this->input->post('role');
            $mobile = $this->input->post('mobile');
            if ($this->form_validation->run() == FALSE) {
                $this->global['fname'] = $name;
                $this->global['email'] = $email;
                $this->global['password'] = $password;
                $this->global['cpassword'] = $cpassword;
                $this->global['roleId'] = $roleId;
                $this->editOld($userId);
            } else {
                $userInfo = array();

                if (empty($password)) {
                    $userInfo = array('email' => $email, 'roleId' => $roleId + 1, 'name' => $name,
                        'mobile' => $mobile, 'updatedBy' => $this->vendorId, 'update_time' => date('Y-m-d H:i:s'));
                } else {
                    $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleId' => $roleId + 1,
                        'name' => ucwords($name), 'mobile' => $mobile, 'updatedBy' => $this->vendorId,
                        'update_time' => date('Y-m-d H:i:s'));
                }

                $result = $this->user_model->editUser($userInfo, $userId);

                if ($result == true) {
                    $this->session->set_flashdata('success', '用户更新成功.');
                    echo(json_encode(array('status' => TRUE)));
                } else {
                    $this->session->set_flashdata('error', '用户更新失败.');
                    echo(json_encode(array('status' => FALSE)));
                }

                redirect('userListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($id)
    {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $userId = $id;
            $userInfo = array(/*'isDeleted' => 1,*/ 'updatedBy' => $this->vendorId, 'update_time' => date('Y-m-d H:i:s'));

            $result = $this->user_model->deleteUser($userId, $userInfo);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
            redirect('userListing');

        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRole($id)
    {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $roleId = $id;
            $result = $this->user_model->deleteRole($roleId);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
            redirect('roleListing');

        }
    }

    /**
     * This function is used to load the change password screen
     */
    function loadChangePass()
    {
        $this->global['pageTitle'] = '修改密码';

        $this->loadViews("changePassword", $this->global, NULL, NULL);
    }

    /**
     * This function is used to change the password of the user
     */
    function changePassword()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('oldPassword', '旧密码', 'required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('newPassword', '新密码', 'required|matches[cNewPassword]|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('cNewPassword', '确认密码', 'required|matches[newPassword]|min_length[6]|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $this->loadChangePass();
        } else {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            $cnewPassword = $this->input->post('cNewPassword');

            $this->global['oldpwd'] = $oldPassword;
            $this->global['newpwd'] = $newPassword;
            $this->global['cnewpwd'] = $cnewPassword;

            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);

            if (empty($resultPas)) {
                $this->session->set_flashdata('nomatch', '您的旧密码不正确.');
                redirect('loadChangePass');
            } else {
                $usersData = array('password' => getHashedPassword($newPassword),
                    'update_time' => date('Y-m-d H:i:s'));

                $result = $this->user_model->changePassword($this->vendorId, $usersData);

                if ($result > 0) {
                    $this->session->set_flashdata('success', '密码更新成功.');
                    echo json_encode(array('status' => TRUE));
                    redirect('logout');
                } else {
                    $this->session->set_flashdata('error', '密码更新失败.');
                    echo json_encode(array('status' => FALSE));
                }

                redirect('loadChangePass');
            }
        }
    }

    /**
     * This function is used to change the password of the user
     */
    function updateUserPassword()
    {
        $id = $this->input->post('id');
        $password = $this->input->post('password');

        //$resultPas = $this->user_model->matchOldPassword($id, $password);

        $usersData = array('password' => getHashedPassword($password), 'updatedBy' => $this->vendorId,
            'update_time' => date('Y-m-d H:i:s'));

        $result = $this->user_model->changePassword($id, $usersData);

        if ($result > 0) {
            $this->session->set_flashdata('success', '密码更新成功.');
            echo(json_encode(array('status' => TRUE)));
        } else {
            $this->session->set_flashdata('error', '密码更新失败.');
            echo(json_encode(array('status' => FALSE)));
        }

        //redirect('userListing');
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file systemmanage.php */
/* Location: .application/controllers/systemmanage.php */


?>