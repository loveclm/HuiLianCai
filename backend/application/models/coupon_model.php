<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class coupon_model extends CI_Model
{
    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '')
    {
        $status = $data['searchStatus'];

        $this->db->select('tbl_coupon.*, tbl_userinfo.userid, tbl_userinfo.username');
        $this->db->from('tbl_coupon');
        $this->db->join('tbl_userinfo', 'tbl_coupon.user_id = tbl_userinfo.id');

        if ($status != '0') $this->db->where('tbl_coupon.status', $status);
        $this->db->order_by('tbl_coupon.pass_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result = NULL;
        return $result;
    }
    /*
     * this function is used to get shop's coupon
     *  return value 0: no coupon, 1: unused coupon, 2: used coupon
     */
    function getStatus($user_id){
        $this->db->select('using');
        $this->db->from('tbl_coupon');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->result();

        if(count($result) == 0) return 0;
        return intval($result[0]->using);
    }
    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if( !isset($item['id']) || ($item['id'] == '0')){
            $this->db->trans_start();

            $item['pass_time'] = date('Y-m-d H:i:s');
            $this->db->insert('tbl_coupon', $item);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        }else{
            $insert_id = $this->update($item, $item['id']);
        }

        return $insert_id;
    }

    /**
     * This function is used to update the shop information
     * @param array $shopInfo : This is shops updated information
     * @param number $shopId : This is shop id
     */
    function update($item, $id)
    {
        $this->db->where('userid', $id);
        $result = $this->db->update('tbl_coupon', $item);
        return $result;
    }

    /**
     * This function is used to delete the shop information
     * @param number $shopId : This is shop id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($item)
    {
        if (isset($item['id']) && $item['id'] != '0') {
            $this->db->where('id', $item['id']);
            $result = $this->db->delete('tbl_coupon');
            return $result;
        }

        return 0;
    }

}

/* End of file withdraw_model.php */
/* Location: ./application/models/withdraw_model.php */