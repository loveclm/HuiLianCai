<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class shop_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '')
    {
        $provider_id = $data['provider_id'];
        $type = $data['searchType'];
        $name = $data['searchName'];
        $status = $data['searchStatus'];
        if($provider_id == '0') {
            $address = $data['address'];
            $shoptype = $data['searchShoptype'];
            $auth = $data['searchAuth'];
        }
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('type', 3);

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(userid  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(username  LIKE '%" . $name . "%')") : '';
                break;
            case '2': // contact
                $likeCriteria = $name != '' ? ("(address  LIKE '%" . $name . "%')") : '';
                break;
            case '3': // recommend
                $likeCriteria = $name != '' ? ("(saleman_mobile  LIKE '%" . $name . "%')") : '';
                break;
        }
        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if ($status!= '0') $this->db->where('status', $status);
        if($provider_id == '0') {
            $likeCriteria = ($address != '') ? ("(address  LIKE '%" . $address . "%')") : '';
            if ($address != '') $this->db->where($likeCriteria);

            if ($shoptype != '0') $this->db->where('shop_type', $shoptype);
            if ($auth != '0') $this->db->where('auth', $auth);
        }

        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result = NULL;
        return $result;
    }

    // get information of the shops from shop ids
    function getShopInfosByIds($ids){
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('id in(' . $ids . ')');
        $query = $this->db->get();

        return $query->result();
    }

    function getshopInfos($id){
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('userid', $id);
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('userid', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }
    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemByNumber($num)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('userid', $num);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getDeployedCount()
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('status', 1);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    function getUserCount($type){
        $this->db->select('id');
        $this->db->from('tbl_userinfo');
        $this->db->where('type', $type);
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function add($itemInfo)
    {
        if (isset($itemInfo['userid'])) {
            $item = $this->getItemById($itemInfo['userid']);
        } else {
            $item = $this->getItemByNumber($itemInfo['email']);
        }
        if (count($item) > 0) {
            $itemInfo['update_time'] = date("Y-m-d");
            $insert_id = $this->update($itemInfo, $item->userid);
        } else {
            $itemInfo['created_time'] = date("Y-m-d");
            $this->db->trans_start();
            $this->db->insert('tbl_userinfo', $itemInfo);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        }

        return $insert_id;
    }

    /**
     * This function is used to update the shop information
     * @param array $shopInfo : This is shops updated information
     * @param number $shopId : This is shop id
     */
    function update($itemInfo, $id)
    {
        $this->db->where('userid', $id);
        $result = $this->db->update('tbl_userinfo', $itemInfo);
        return $result;
    }
}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */