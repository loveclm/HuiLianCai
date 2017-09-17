<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.userId  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
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
    function userListing($searchText = '', $searchStatus = '0', $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role, BaseTbl.create_time');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId', 'left');
        if (!empty($searchText)) {
            if ($searchStatus == '0') {
                $likeCriteria = "(BaseTbl.userId  LIKE '%" . $searchText . "%')";
            } else {
                $likeCriteria = "(BaseTbl.name  LIKE '%" . $searchText . "%')";
            }
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId <> 2');
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
    function roleListing()
    {
        $this->db->select('*');
        $this->db->from('tbl_roles');
//        $this->db->where("roleId <> 2");

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
    function getAllUsers($roleId = 1)
    {
        $this->db->select('userId as userid, email as number, name as name');
        $this->db->from('tbl_users');
        if ($roleId != 1)
            $this->db->where('roleId', $roleId);
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
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
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
        $this->db->from("tbl_users");
        $this->db->where("email", $email);
        $this->db->where("isDeleted", 0);
        if ($userId != 0) {
            $this->db->where("userId !=", $userId);
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
        $this->db->from("tbl_users");
        $this->db->where("userId", $userId);
        $this->db->where("isDeleted", 0);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($user, $userInfo)
    {
        var_dump($user);
        var_dump($userInfo);
        $users = $this->findAllUsersById($user['userId']);
        if(count($users)>0){
            $this->updateUser($user, $userInfo);
            return 0;
        }
        $this->db->trans_start();
        $this->db->insert('tbl_users', $user);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        $this->db->trans_start();
        $this->db->insert('tbl_userinfo', $userInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return true;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewOrderUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('user', $userInfo);

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
        $this->db->from('user');
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
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewRole($name)
    {
        $result = count($this->roleListing());
        if ($result >= 11) {
            return '0';
        } else {
            $item = [
                'role' => $name
            ];
            $this->db->trans_start();
            $this->db->insert('tbl_roles', $item);

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
        $this->db->from('tbl_roles');
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
        $this->db->from('tbl_roles');
        $this->db->where('roleId', $id);
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
        $this->db->where('roleId', $roleId);
        $this->db->update('tbl_roles', $roleInfo);

        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRole($roleId)
    {
        if ($roleId == '1') return false;
        $this->db->where('roleId', $roleId);
        $this->db->delete('tbl_roles');
        return true;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return TRUE;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateUser($user, $userInfo)
    {
        $this->db->where('userId', $user['userId']);
        $this->db->update('tbl_users', $user);

        $this->db->where('userId', $userInfo['userId']);
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
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }

    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');

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
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }
}

/* End of file user_model.php */
/* Location: .application/models/user_model.php */
