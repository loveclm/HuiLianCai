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
        $this->db->select('tbl_userinfo.*, tbl_user.status, tbl_user.create_time, sale.username as salename');
        $this->db->from('tbl_user');
        $this->db->join('tbl_userinfo', 'tbl_user.userid=tbl_userinfo.userid');
        $this->db->join('tbl_user as sale', 'tbl_userinfo.saleman=sale.id');


        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_user.userid  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(tbl_user.username  LIKE '%" . $name . "%')") : '';
                break;
            case '2': // contact
                $likeCriteria = $name != '' ? ("(tbl_userinfo.contact_name  LIKE '%" . $name . "%')") : '';
                break;
            case '3': // recommend
                $likeCriteria = $name != '' ? ("(sale.username  LIKE '%" . $name . "%')") : '';
                break;
        }
        if ($likeCriteria != '') $this->db->where($likeCriteria);

        $likeCriteria = ($address != '') ? ("(tbl_userinfo.address  LIKE '%" . $address . "%')") : '';
        if ($address!= '') $this->db->where($likeCriteria);

        if ($status!= '0') $this->db->where('tbl_user.status', $status);

        $this->db->where('tbl_user.role', '2'); // 2-providers
        //$this->db->order_by('isDeleted', '0'); // 0-using, 1-deleted
        $this->db->order_by('tbl_user.create_time', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function getOrderCount($userid){
        $this->db->select('tbl_order.id');
        $this->db->from('tbl_order');
        $this->db->join( 'tbl_userinfo', 'tbl_order.provider = tbl_userinfo.id');
        $this->db->where('tbl_userinfo.userid', $userid);
        $query = $this->db->get();

        return count($query->result());
    }

    function getFullInfosById($provider_id){
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('id', $provider_id);
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        return $result[0];
    }

    function isDeletable($id)
    {
        $query = $this->db->select('id')
            ->from('tbl_userinfo')
            ->where('provider', $id)
            ->limit(1)
            ->get();

        if(count($query->result()) > 0) return false;

        $result = $this->db->select('id')
            ->ftom('tbl_product')
            ->where('provider_id', $id)
            ->limit(1)->get()->result();

        return count($result) == 0 ? true : false;
    }
    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
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
            $itemInfo['update_time'] = date("Y-m-d H:i:s");
            $insert_id = $this->update($itemInfo, $item->userid);
        } else {
            $itemInfo['create_time'] = date("Y-m-d H:i:s");
            $this->db->trans_start();
            $this->db->insert('tbl_user', $itemInfo);
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
        $result = $this->db->update('tbl_user', $itemInfo);
        return $result;
    }

    /**
     * This function is used to delete the shop information
     * @param number $shopId : This is shop id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($itemInfo)
    {

        if (isset($itemInfo['userid'])) {
            $item = $this->getItemById($itemInfo['userid']);
        } else {
            $item = $this->getItemByNumber($itemInfo['email']);
        }
        if (count($item) > 0) {
            $this->db->where('userid', $itemInfo['userid']);
            $result = $this->db->delete('tbl_user');

            $this->db->where('userid', $itemInfo['userid']);
            $result = $this->db->delete('tbl_userinfo');
            return $result;
        }
        return 0;
    }

}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */