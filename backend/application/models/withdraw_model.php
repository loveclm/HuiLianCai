<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class withdraw_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '')
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $status = $data['searchStatus'];
        $provider_id = $data['provider_id'];

        $this->db->select('tbl_provider_withdraw.id as withdraw_id, tbl_provider_withdraw.*, tbl_userinfo.userid as provider_userid, tbl_provider_bankinfo.*');
        $this->db->from('tbl_provider_withdraw');
        $this->db->join('tbl_provider_bankinfo', 'tbl_provider_withdraw.provider_id = tbl_provider_bankinfo.provider_id');
        $this->db->join('tbl_userinfo', 'tbl_provider_withdraw.provider_id = tbl_userinfo.id');

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_userinfo.userid  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(tbl_userinfo.username  LIKE '%" . $name . "%')") : '';
                break;
        }

        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if ($status != '0') $this->db->where('tbl_provider_withdraw.status', $status);

        if($provider_id != '0') $this->db->where('tbl_provider_withdraw.provider_id', $provider_id);

        $this->db->order_by('tbl_provider_withdraw.time', "desc");
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result = NULL;
        return $result;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('tbl_provider_withdraw.id as withdraw_id, tbl_provider_withdraw.*, tbl_userinfo.userid as provider_userid, tbl_provider_bankinfo.*');
        $this->db->from('tbl_provider_withdraw');
        $this->db->join('tbl_provider_bankinfo', 'tbl_provider_withdraw.provider_id = tbl_provider_bankinfo.provider_id');
        $this->db->join('tbl_userinfo', 'tbl_provider_withdraw.provider_id = tbl_userinfo.id');
        $this->db->where('tbl_provider_withdraw.id', $id);

        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        return $result[0];
    }

    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if( !isset($item['id'])){
            $this->db->trans_start();
            $item['time'] = date('Y-m-d H:i:s');
            $this->db->insert('tbl_provider_withdraw', $item);
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
        $this->db->where('id', $id);
        $result = $this->db->update('tbl_provider_withdraw', $item);
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
            $result = $this->db->delete('tbl_order');
            return $result;
        }

        return 0;
    }

}

/* End of file withdraw_model.php */
/* Location: ./application/models/withdraw_model.php */