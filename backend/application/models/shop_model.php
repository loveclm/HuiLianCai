<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class shop_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '')
    {

        $filter_addr = '';
        $provider_id = $data['provider_id'];
        if($provider_id != '0') {
            $result = $this->db->select('address')
                ->from('tbl_userinfo')
                ->where('id', $provider_id)
                ->get()->result();

            if (count($result) > 0) {
                $filter_addr = $result[0]->address;
                $ss = explode(',', $filter_addr);
                unset($ss[count($ss) - 1]);
                $filter_addr = implode(',', $ss);
            }
        }

        $type = $data['searchType'];
        $name = $data['searchName'];
        $status = $data['searchStatus'];
        $shoptype = $data['searchShoptype'];
        if ($provider_id == '0') {
            $address = $data['address'];
            $auth = $data['searchAuth'];
        }
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('type', 3);
        if(isset($data['start_date'])){
            if($data['start_date'] != '')
                $this->db->where('date(created_time)>=\''. $data['start_date'] . '\'');
            if($data['end_date'] != '')
                $this->db->where('date(created_time)<=\''. $data['end_date'] . '\'');
        }
        switch (intval($type)) {
            case 0: // account
                $likeCriteria = $name != '' ? ("(userid  LIKE '%" . $name . "%')") : '';
                break;
            case 1: // name
                $likeCriteria = $name != '' ? ("(username  LIKE '%" . $name . "%')") : '';
                break;
            case 2: // contact
                $likeCriteria = $name != '' ? ("(address  LIKE '%" . $name . "%')") : '';
                break;
            case 3: // recommend
                $likeCriteria = $name != '' ? ("(saleman_mobile  LIKE '%" . $name . "%')") : '';
                break;
        }
        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if ($status != '0') $this->db->where('status', $status);
        if ($shoptype != '0') $this->db->where('shop_type', $shoptype);
        if ($provider_id == '0') {
            $likeCriteria = ($address != '') ? ("(filter_addr  LIKE '%" . $address . "%')") : '';
            if ($address != '') $this->db->where($likeCriteria);

            if ($auth != '0') $this->db->where('auth', $auth);
        }else{
            $this->db->where('status', 1);
            $this->db->where('auth', 3);
            // range limitation
            if($filter_addr != '')
                $this->db->where('filter_addr', $filter_addr);
        }

        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function isExistName($name, $userid){
        $result = $this->db->select('id')
            ->from('tbl_userinfo')
            ->where('username', $name)
            ->where('userid <> \''. $userid . '\'')
            ->get()->result();

        return count($result) == 0 ? false : true;
    }

    function getShipman($shop_id, $provider)
    {
        $this->db->select('shipman')
            ->from('tbl_ship_allocate')
            ->where('shop', $shop_id);
        if ($provider != 0)
            $this->db->where('provider', $provider);

        $result = $this->db->get()->result();

        return count($result) == 0 ? 0 : $result[0]->shipman;
    }

    function setShipman($provider, $shop, $ship)
    {
        $result = $this->db->select('id')
            ->from('tbl_ship_allocate')
            ->where('provider', $provider)
            ->where('shop', $shop)
            ->get()->result();

        $item = array(
            'provider' => $provider,
            'shop' => $shop,
            'shipman' => $ship,
            'set_time' => date('Y-m-d H:i:s')
        );

        if (count($result) == 0) {
            $this->db->trans_start();
            $this->db->insert('tbl_ship_allocate', $item);
            $this->db->insert_id();
            $insert_id = $this->db->trans_complete();
        } else {
            $this->db->where('id', $result[0]);
            $insert_id = $this->db->update('tbl_ship_allocate', $item);
        }

        return $insert_id;
    }

    // get information of the shops from shop ids
    function getShopInfosByIds($ids) {
        if($ids == '') return array();
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('id in(' . $ids . ')');
        $query = $this->db->get();

        return $query->result();
    }

    function getshopInfos($id) {
        $this->db->select('tbl_userinfo.*, tbl_coupon.using as coupon');
        $this->db->from('tbl_userinfo');
        $this->db->join('tbl_coupon', 'tbl_userinfo.id=tbl_coupon.user_id', 'left');
        $this->db->where('tbl_userinfo.userid', $id);
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    function getShopsByLocation($lat, $lon, $filter_addr){
        $result = $this->db->select('*')
            ->from('tbl_userinfo')
            ->where('filter_addr', $filter_addr)
            ->get()->result();

        $records = array();
        foreach ($result as $shop){
            if($shop->location == '') continue;
            $pos = explode( ',', $shop->location);
            $dis = $this->distance($lat, $lon, $pos[0], $pos[1]);
            if($dis < 3000)
                array_push($records, $shop);
        }

        return $records;
    }

    function distance($lat2, $lon2, $lat1, $lon1, $unit = 'K') {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
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

    function getUserCount($type)
    {
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
//        if (isset($itemInfo['userid'])) {
//            $item = $this->getItemById($itemInfo['userid']);
//        } else {
//            $item = $this->getItemByNumber($itemInfo['email']);
//        }
//        if (count($item) > 0) {
//            $itemInfo['update_time'] = date("Y-m-d");
//            $insert_id = $this->update($itemInfo, $item->userid);
//        } else {
//            $itemInfo['created_time'] = date("Y-m-d");
//            $this->db->trans_start();
//            $this->db->insert('tbl_userinfo', $itemInfo);
//            $insert_id = $this->db->insert_id();
//            $this->db->trans_complete();
//        }

        $this->db->where('id', $itemInfo['id']);
        $insert_id = $this->db->update('tbl_userinfo', $itemInfo);

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