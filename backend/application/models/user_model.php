<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model
{
    /***###############################################********
     *  Following functions are the users that manage SYSTEM
     */
    function getItems($data = '')
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $login_user = $data['login_user'];

        $this->db->select('id, userid, username, role, create_time');
        $this->db->from('tbl_user');
        $this->db->where('(id=' . $login_user . ' or parent_id=' . $login_user . ')');

        $criteria = '';
        if ($name != '') {
            switch ($type) {
                case 0: //account
                    $criteria = '(userid like \'%' . $name . '%\')';
                    break;
                case 1: //name
                    $criteria = '(username like \'%' . $name . '%\')';
                    break;
            }
            $this->db->where($criteria);
        }

        $query = $this->db->get();
        $result = $query->result();

        return count($result) == 0 ? NULL : $result;
    }

    // this function is used to get all roles within the scope of the current user
    function getRoles($login_id)
    {

//        $this->db->select('tbl_role.id, tbl_role.role');
//        $this->db->from('tbl_user');
//        $this->db->join('tbl_role', 'tbl_role.id = tbl_user.role');
//        $this->db->where('(tbl_user.id='. $login_user . ' or tbl_user.parent_id='. $login_user .')');
//        if($login_user == '1') // less provider
//            $this->db->where('tbl_user.level != 2');

        $this->db->select('id, role');
        $this->db->from('tbl_role');
        $this->db->where('parent_id', $login_id);
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    // the function is used for getting role name from role id
    function getRoleNameById($id)
    {
        $this->db->select('role');
        $this->db->from('tbl_role');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();

        return count($result) == 0 ? '' : $result[0]->role;
    }

    // function is used to delete with user id from tbl_user table
    function deleteSystemUser($id)
    {
        if (!$this->isDeletable($id)) return false;

        $this->db->where('id', $id);
        $this->db->delete('tbl_user');

        return true;
    }


    function isDeletable($id)
    {
        $query = $this->db->select('id')
            ->from('tbl_userinfo')
            ->where('saleman', $id)
            ->limit(1)
            ->get();

        return count($query->result()) > 0 ? false : true;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addSystemUser($userInfo)
    {
        if (isset($userInfo->id)) {
            $this->updateSystemUser($userInfo);
        }
        $userInfo->create_time = date("Y-m-d H:i:s");
        $this->db->trans_start();
        $this->db->insert('tbl_user', $userInfo);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function updateSystemUser($userinfo)
    {
        $this->db->where('id', $userinfo->id);
        $this->db->update('tbl_user', $userinfo);
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getSystemUserById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->result()[0];
    }

    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($id, $oldPassword)
    {
        $this->db->select('id, password');
        $this->db->where('userid', $id);
        //$this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_user');

        $user = $query->result();

        if (!empty($user)) {
            if (verifyHashedPassword($oldPassword, $user[0]->password)) {
                return $user;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($id, $userInfo)
    {
        $this->db->where('userid', $id);
        $this->db->update('tbl_user', $userInfo);

        return $this->db->affected_rows();
    }

    function addNewShop($userid, $password, $saleman)
    {
        $userinfo = array(
            'userid' => $userid,
            'password' => $password,
            'saleman_mobile' => $saleman,
            'type' => 3,
            'created_time' => date('Y-m-d H:i:s')
        );

        $this->db->trans_start();
        $this->db->insert('tbl_userinfo', $userinfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($user, $userInfo)
    {
        $users = $this->findAllUsersById($user['userid']);
        if (count($users) > 0) return 0;

        $user['create_time'] = date('Y-m-d H:i:s');

        $this->db->trans_start();
        $this->db->insert('tbl_user', $user);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        $userinfo['created_time'] = date('Y-m-d H:i:s');
        $this->db->trans_start();
        $this->db->insert('tbl_userinfo', $userInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    /***###############################################********
     *  Following functions are the users that manage provider
     */


    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($level, $searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.userid, BaseTbl.username, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_user as BaseTbl');
        $this->db->join('tbl_role as Role', 'Role.id = BaseTbl.role', 'left');
        $this->db->where('BaseTbl.level', $level);
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.userid  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.username  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->where('BaseTbl.role !=', 1);
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userListing($level, $searchText = '', $searchStatus = '0', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userid, BaseTbl.username, BaseTbl.mobile, Role.role, BaseTbl.create_time');
        $this->db->from('tbl_user as BaseTbl');
        $this->db->join('tbl_role as Role', 'Role.id = BaseTbl.role', 'left');
        $this->db->where('BaseTbl.level', $level);
        if (!empty($searchText)) {
            if ($searchStatus == '0') {
                $likeCriteria = "(BaseTbl.userid  LIKE '%" . $searchText . "%')";
            } else {
                $likeCriteria = "(BaseTbl.name  LIKE '%" . $searchText . "%')";
            }
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->where('BaseTbl.roleId <> 2');
        //$this->db->where('BaseTbl.roleId !=', 1);
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function roleListing($type = 1)
    {

        $this->db->select('*');
        $this->db->from('tbl_role');
        $this->db->where('parent_id', $type);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function getAllUsers($role = 1)
    {
        $this->db->select('userid as userid, email as number, name as name');
        $this->db->from('tbl_user');
        if ($role != 1)
            $this->db->where('role', $role);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('*');
        $this->db->from('tbl_role');
        $this->db->where('id !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_user");
        $this->db->where("email", $email);
        //$this->db->where("isDeleted", 0);
        if ($userId != 0) {
            $this->db->where("userid !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function findAllUsersById($userId)
    {
        $this->db->select("*");
        $this->db->from("tbl_user");
        $this->db->where("userid", $userId);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewOrderUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_user', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function getOrderUserByPhone($phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('mobile', $phone);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('userid, username, level, role');
        $this->db->from('tbl_user');
        //$this->db->where('isDeleted', 0);
        $this->db->where('role !=', 1);
        $this->db->where('id', $userId);
        $query = $this->db->get();
        $result = $query->result();

        return count($result) == 0 ? NULL : $result[0];
    }


    function getUserInfoByid($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    function getUserName($id)
    {
        $this->db->select('username');
        $this->db->from('tbl_userinfo');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return '';

        return $result[0]->username;
    }

    function getShipMans($provider_id = '')
    {
        $this->db->select('id, username, contact_phone, more_data');
        $this->db->from('tbl_userinfo');
        $this->db->where('type', 4);
        if ($provider_id != '')
            $this->db->where('provider_id', $provider_id);

        $query = $this->db->get();

        return $query->result();
    }

    function getRoleinfos($type, $id = 0)
    {
        $this->db->select('id, menu_id, menu');
        $this->db->from('tbl_menu');
        $this->db->where('( type = 0 or type =' . $type . ')');
        $this->db->where('parent_id', $id);
        $this->db->order_by('order', 'asc');
        $query = $this->db->get();
        $top_menus = $query->result();

        $data = array();
        if (count($top_menus) == 0) return $data;
        $i = 0;
        foreach ($top_menus as $key => $top_menu) {
            $data_item = array(
                'id' => $top_menu->menu_id,
                'text' => $top_menu->menu,
                'data' => $this->getRoleinfos($type, $top_menu->id)
            );
            array_push($data, $data_item);
            $i++;
        }

        return $data;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewRole($data)
    {
        $result = count($this->roleListing($data['parent_id']));
        if ($result > 10) {
            return '0';
        } else {
            $this->db->trans_start();
            $this->db->insert('tbl_role', $data);

            $insert_id = $this->db->insert_id();

            $this->db->trans_complete();
        }
        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function findRole($name)
    {
        $this->db->select('*');
        $this->db->from('tbl_role');
        $this->db->where('role', $name);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getRoleById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_role');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateRole($roleInfo, $roleId)
    {
        $this->db->where('id', $roleId);
        $this->db->update('tbl_role', $roleInfo);

        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRole($roleId)
    {
        if (intval($roleId) <= 2) return false;
        $this->db->where('id', $roleId);
        $this->db->delete('tbl_role');
        return true;
    }


    /**
     * This function is used to delete the other user information
     */
    function getOperatingUsers()
    {
        $this->db->select('id, username');
        $this->db->from('tbl_user');
        $this->db->where('parent_id', '1');
        $this->db->where('role !=', 2);

        $query = $this->db->get();

        return $query->result();
    }

    function getProviderInfos($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->join('tbl_userinfo', 'tbl_user.userid = tbl_userinfo.userid', 'left');
        $this->db->where('tbl_user.role', 2);
        $this->db->where('tbl_userinfo.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    function getProviderInfoFromTblUser($id)
    {
        $result = $this->db->select('tbl_userinfo.*')
            ->from('tbl_user')
            ->join('tbl_userinfo', 'tbl_user.userid = tbl_userinfo.userid')
            ->where('tbl_user.id', $id)
            ->get()->result();

        if (count($result) == 0) return NULL;
        return $result[0];
    }

    function getProviderId($user_id)
    {
        $userinfo = $this->getProviderInfoFromTblUser($user_id);
        return ($userinfo == NULL) ? '0' : $userinfo->id;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userid', $userId);
        $this->db->update('tbl_user', $userInfo);

        return TRUE;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateUser($user, $userInfo)
    {
        $this->db->where('userid', $user['userid']);
        $this->db->update('tbl_user', $user);

        $this->db->where('userid', $userInfo['userid']);
        $this->db->update('tbl_userinfo', $userInfo);

        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userid', $userId);
        $this->db->update('tbl_user', $userInfo);

        return $this->db->affected_rows();
    }

    /**
     * This function is used to get the ip list related to user
     * @param number $userId : This is user id
     */
    function getPublicNumberByUserId($userId)
    {
        $this->db->select('id, provider_id');
        $this->db->from('tbl_userinfo');
        $this->db->where('userid', $userId);

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return 0;

        $id = ($result[0]->provider_id == '0') ? $result[0]->id : $result[0]->provider_id;

        return $id;
    }

    function getIdByUserId($userid)
    {
        $this->db->select('id');
        $this->db->from('tbl_userinfo');
        $this->db->where('userid', $userid);
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) return 0;
        return $result[0]->id;
    }

    /***
     *  This function is used when add user. check whether userid exists
     */
    function isExistUserId($userid, $type = 0)
    {
        if ($type == 0) {
            $this->db->select('userid');
            $this->db->from('tbl_user');
            $this->db->where('userid', $userid);
        } else {
            $this->db->select('userid');
            $this->db->from('tbl_userinfo');
            $this->db->where('userid', $userid);
        }

        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) return FALSE;

        return TRUE;
    }

    /***
     *  This function is used when add user. check whether username exists
     */
    function isExistUserName($username, $type = 0)
    {
        if ($type == 0) {
            $this->db->select('username');
            $this->db->from('tbl_user');
            $this->db->where('username', $username);
        } else {
            $this->db->select('username');
            $this->db->from('tbl_userinfo');
            $this->db->where('username', $username);
        }

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return FALSE;

        return TRUE;
    }
}

/* End of file user_model.php */
/* Location: .application/models/user_model.php */
