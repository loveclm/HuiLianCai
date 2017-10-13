<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class login_model extends CI_Model
{
    
    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($username, $password)
    {
        $this->db->select('tbl_user.id, tbl_user.parent_id, tbl_user.userid, tbl_user.username, tbl_user.password, tbl_user.role, tbl_user.level, tbl_user.role as role_text');
        $this->db->from('tbl_user');
        $this->db->join('tbl_role', 'tbl_role.id=tbl_user.role');
        $this->db->where('tbl_user.userid', $username);
        //$this->db->where('BaseTbl.isDeleted', 0);

        $query = $this->db->get();
        
        $user = $query->row();

        if(!empty($user)){
            if(verifyHashedPassword($password, $user->password)){
                return array( status=> 'success', userinfo=>$user);
            } else {
                return array( status=>'password is wrong');
            }
        } else {
            return array( status=>'user is wrong');
        }
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email)
    {
        $this->db->select('userid');
        $this->db->where('email', $email);
        //$this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_user');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
        $result = $this->db->insert('tbl_reset_password', $data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $this->db->select('id, userid, username');
        $this->db->from('tbl_user');
        //$this->db->where('isDeleted', 0);
        $this->db->where('username', $email);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows;
    }

    // This function used to create new password by reset link
    function createPasswordUser($userid, $password)
    {
        $this->db->where('username', $userid);
        //$this->db->where('isDeleted', 0);
        $this->db->update('tbl_user', array('password'=>getHashedPassword($password)));
        $this->db->delete('tbl_reset_password', array('userid'=>$userid));
    }
}

/* End of file login_model.php */
/* Location: .application/models/login_model.php */


?>