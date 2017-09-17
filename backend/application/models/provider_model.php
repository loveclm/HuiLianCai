<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class provider_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '')
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $address = $data['address'];
        $status = $data['searchStatus'];
        $this->db->select('tbl_users.*, tbl_userinfo.*');
        $this->db->from('tbl_users');
        $this->db->join('tbl_userinfo', 'tbl_users.userId=tbl_userinfo.userId');

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_users.userId  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(tbl_users.name  LIKE '%" . $name . "%')") : '';
                break;
            case '2': // contact
                $likeCriteria = $name != '' ? ("(tbl_userinfo.contact_name  LIKE '%" . $name . "%')") : '';
                break;
            case '3': // recommend
                $likeCriteria = $name != '' ? ("(tbl_userinfo.contact_phone  LIKE '%" . $name . "%')") : '';
                break;
        }
        if ($likeCriteria != '') $this->db->where($likeCriteria);

        $likeCriteria = ($address != '') ? ("(address  LIKE '%" . $address . "%')") : '';
        if ($address!= '') $this->db->where($likeCriteria);

        if ($status!= '0') $this->db->where('status', $status);

        $this->db->where('roleId', '2'); // 2-providers
        $this->db->order_by('isDeleted', '0'); // 0-using, 1-deleted
        $this->db->order_by('create_time', 'desc');
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
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('userId', $id);
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
        $this->db->from('tbl_users');
        $this->db->where('userId', $num);
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
        $this->db->from('tbl_users');
        $this->db->where('status', 1);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function add($itemInfo)
    {
        if (isset($itemInfo['userId'])) {
            $item = $this->getItemById($itemInfo['userId']);
        } else {
            $item = $this->getItemByNumber($itemInfo['email']);
        }
        if (count($item) > 0) {
            $itemInfo['update_time'] = date("Y-m-d");
            $insert_id = $this->update($itemInfo, $item->userId);
        } else {
            $itemInfo['create_time'] = date("Y-m-d");
            $this->db->trans_start();
            $this->db->insert('tbl_users', $itemInfo);
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
        $this->db->where('userId', $id);
        $result = $this->db->update('tbl_users', $itemInfo);
        return $result;
    }

    /**
     * This function is used to delete the shop information
     * @param number $shopId : This is shop id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($itemInfo)
    {

        if (isset($itemInfo['userId'])) {
            $item = $this->getItemById($itemInfo['userId']);
        } else {
            $item = $this->getItemByNumber($itemInfo['email']);
        }
        if (count($item) > 0) {
            $this->db->where('userId', $itemInfo['userId']);
            $result = $this->db->delete('tbl_users');
            return $result;
        }
        return 0;
    }

}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */