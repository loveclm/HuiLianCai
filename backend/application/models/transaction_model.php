<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class transaction_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '', $pay_type = 0)
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];

        $this->db->select('tbl_transaction.*, tbl_userinfo.userid as shop_id, tbl_userinfo.username as shop_name');
        $this->db->from('tbl_transaction');
        $this->db->join('tbl_userinfo', 'tbl_transaction.shop_id = tbl_userinfo.id');

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_userinfo.userid  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                if($pay_type == 0)
                    $likeCriteria = $name != '' ? ("(tbl_transaction.order_id  LIKE '%" . $name . "%')") : '';
                else
                    $likeCriteria = $name != '' ? ("(tbl_userinfo.username  LIKE '%" . $name . "%')") : '';
                break;
        }

        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if ($start_time != '') $this->db->where('tbl_transaction.time >', $start_time);
        if ($end_time != '') $this->db->where('tbl_transaction.time <', $end_time);
        if($pay_type != 0)
            $this->db->where('tbl_transaction.pay_type', $pay_type);

        $this->db->order_by('tbl_transaction.time', "desc");
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
        $this->db->select('tbl_transaction.id as transaction_id, tbl_transaction.*, tbl_userinfo.userid as provider_userid, tbl_provider_bankinfo.*');
        $this->db->from('tbl_transaction');
        $this->db->join('tbl_provider_bankinfo', 'tbl_transaction.provider_id = tbl_provider_bankinfo.provider_id');
        $this->db->join('tbl_userinfo', 'tbl_transaction.provider_id = tbl_userinfo.id');
        $this->db->where('tbl_transaction.id', $id);

        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        return $result[0];
    }

    function getShopTransactionInfos($user_id){
        $this->db->select('money as price, pay_type as type, time as trans_time, note as content');
        $this->db->from('tbl_transaction');
        $this->db->where('shop_id', $user_id);
        $query = $this->db->get();

        return $query->result();
    }

    function getBankinfo($id){
        $result = $this->db->select('*')
            ->from('tbl_provider_bankinfo')
            ->where('provider_id', $id)
            ->get()->result();
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
            $item['id'] = sprintf("%'.02d%'05d", $item['shop_id'], mt_rand(10000,99999)) . $item['order_id'];
            $item['time'] = date("Y-m-d H:i:s");
            $this->db->insert('tbl_transaction', $item);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();

            return $item['id'];
        }else{
            $insert_id = $this->update($item, $item['id']);
        }

        return $insert_id;
    }

    function addBankinfo($item)
    {
        $this->db->trans_start();
        $item['create_time'] = date("Y-m-d H:i:s");
        $this->db->insert('tbl_provider_bankinfo', $item);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

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
        $result = $this->db->update('tbl_transaction', $item);
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

/* End of file transaction_model.php */
/* Location: ./application/models/transaction_model.php */