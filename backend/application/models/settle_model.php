<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class settle_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getBuyOrders($searchType, $name, $stDate, $enDate)
    {
        $this->db->select('bu.id as id, bu.settlement_date as settle_date, sh.phonenumber as shopcode, ' .
            'sh.name as shopname, bu.sum as price, sh.status as status');
        $this->db->from('buy_settlement as bu');
        $this->db->join('shop as sh', 'bu.shopid = sh.id');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(sh.phonenumber  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(sh.name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if ($stDate != '') $this->db->where("date(bu.settlement_date) >= '" . date($stDate) . "'");
        if ($enDate != '') $this->db->where("date(bu.settlement_date) <= '" . date($enDate) . "'");

        $this->db->order_by('bu.settlement_date', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrders($searchType, $name, $stDate, $enDate)
    {
        $this->db->select('au.id as id, au.settlement_date as settle_date, ' .
            'sh.phonenumber as shopcode, sh.name as shopname, ' .
            'au.money as price, au.status as status');
        $this->db->from('auth_settlement as au');
        $this->db->join('shop as sh', 'au.shopid = sh.id');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(sh.phonenumber  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(sh.name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if ($stDate != '') $this->db->where("date(au.settlement_date) >= '" . date($stDate) . "'");
        if ($enDate != '') $this->db->where("date(au.settlement_date) <= '" . date($enDate) . "'");

        $this->db->order_by('au.settlement_date', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getTotalPriceByPhone($phone)
    {
        $this->db->select('au.id as id, sh.phonenumber as shopcode, sh.name as shopname, ' .
            'au.money as price, au.status as status');
        $this->db->from('auth_settlement as au');
        $this->db->join('shop as sh', 'au.shopid = sh.id');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(sh.phonenumber  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(sh.name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if ($stDate != '') $this->db->where("date(au.settlement_date) >= '" . date($stDate) . "'");
        if ($enDate != '') $this->db->where("date(au.settlement_date) <= '" . date($enDate) . "'");

        $this->db->order_by('au.settlement_date', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
}

/* End of file settle_model.php */
/* Location: .application/models/settle_model.php */
?>