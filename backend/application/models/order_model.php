<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class order_model extends CI_Model
{
    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getBuyOrders($searchType, $name, $stDate, $enDate, $status)
    {//id, number, mobile, price, tour_area, tour_point, shop_name, status, ordered_time
        $this->db->select('od.id as id, od.value as number, od.userphone as mobile, od.code as price,' .
            'ta.name as tour_area, ta.point_list as point_list, od.attractionid as tour_point, od.authid as shop_name, ' .
            'od.status as status, ta.type as type, od.ordered_time as ordered_time');
        $this->db->from('tbl_order as od');
        $this->db->join('tourist_area as ta', 'od.areaid = ta.id');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(od.value  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(od.userphone  LIKE '%" . $name . "%')";
                break;
            case '2':
                $likeCriteria = "(ta.name  LIKE '%" . $name . "%')";
                break;
            case '3':
                //$likeCriteria = "(trp.name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if ($stDate != '') $this->db->where("date(od.ordered_time) >= '" . date($stDate) . "'");
        if ($enDate != '') $this->db->where("date(od.ordered_time) <= '" . date($enDate) . "'");

        if ($status != '0') $this->db->where('od.status', $status);
        $this->db->where("(od.ordertype)",'1');
        $this->db->order_by('od.ordered_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrders($searchType, $name, $stDate, $enDate, $status)
    {
        $this->db->select('od.id as id, od.value as number, od.userphone as mobile, od.code as price,' .
            'ta.name as tour_area, ta.point_list as point_list, ta.type as tour_point, ' .
            'au.shopid as shop_name, ta.type as type, od.status as status, od.ordered_time as ordered_time');
        $this->db->from('tbl_order as od');
        $this->db->join('tbl_authcode as au', 'od.authid = au.id');
        $this->db->join('tourist_area as ta', 'au.targetid = ta.id');
        switch ($searchType) {
            case '0':
                $likeCriteria = "(od.value  LIKE '%" . $name . "%')";
                break;
            case '1':
                $likeCriteria = "(od.userphone  LIKE '%" . $name . "%')";
                break;
            case '2':
                $likeCriteria = "(ta.name  LIKE '%" . $name . "%')";
                break;
        }
        $this->db->where($likeCriteria);
        if ($stDate != '') $this->db->where("date(od.ordered_time) >= '" . date($stDate) . "'");
        if ($enDate != '') $this->db->where("date(od.ordered_time) <= '" . date($enDate) . "'");

        if ($status != '0') $this->db->where('od.status', $status);
        $this->db->where("(od.userphone)<>'0'");
        $this->db->where("(od.ordertype)",'2');
        $this->db->order_by('od.ordered_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     * $type==1: scenic area,   $type==2: course
     */
    function getAreaCountByShopId($id, $type)
    {
        $this->db->select('targetid');
        $this->db->from('tbl_authcode');
        $this->db->where('shopid', $id);
        $this->db->where('type', $type);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrdersByUser($phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('userphone', $phone);
        $this->db->order_by('authid');
        $this->db->order_by('areaid');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getOrderCountByUser($phone)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('userphone', $phone);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    public function getMyOrderInfos($mobile)
    {
        $orders = $this->getOrdersByUser($mobile);
        if (count($orders) == 0) {
            return '-1';
        } else {
            $i = 0;
            $total_price = 0;
            $Auths = array();
            foreach ($orders as $item) {
                $i++;
                if ($item->ordertype == '2') { // auth order
                    $areaitem = $this->area_model->getAreaByAuthId($item->authid);
                    $area_info = json_decode($areaitem->info);
                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $areaitem->name,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'pay_method' => 2,
                            'value' => $item->code,
                            'cost' => $areaitem->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'canceled_time' => $item->canceled_time,
                            'state' => $item->status
                        )
                    );
                    $total_price += ($areaitem->price * $areaitem->discount_rate);
                } else if ($item->attractionid == '0') { //buy course or area suoyou
                    $areaitem = $this->area_model->getAreaById($item->areaid);
                    $attritem = json_decode($areaitem->point_list);
                    $area_info = json_decode($areaitem->info);
                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $areaitem->name,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'pay_method' => 1,
                            'value' => $item->code,
                            'cost' => $areaitem->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'canceled_time' => $item->canceled_time,
                            'state' => $item->status
                        )
                    );
                    $total_price += $item->code;
                } else { //buy attraction
                    $areaitem = $this->area_model->getAreaById($item->areaid);
                    $attritem = json_decode($areaitem->point_list);
                    $attr_id = explode('_', $item->attractionid);
                    $attritem = $attritem[$attr_id[1] - 1];
                    array_push(
                        $Auths,
                        array(
                            'id' => $item->value,
                            'name' => $attritem->name,
                            'image' => base_url() . 'uploads/' . $attritem->image,
                            'pay_method' => 1,
                            'value' => $item->code,
                            'cost' => $attritem->price,
                            'discount_rate' => $attritem->discount_rate,
                            'order_time' => $item->ordered_time,
                            'paid_time' => $item->paid_time,
                            'expiration_time' => date_format(date_create($item->ordered_time), "Y.m.d") .
                                ' - ' . date_format(date_create($item->expiration_time), "Y.m.d"),
                            'canceled_time' => $item->canceled_time,
                            'state' => $item->status
                        )
                    );
                    $total_price += $item->code;
                }
            }
            $result['Auths'] = $Auths;
            $result['total_price'] = $total_price;
            return $result;
        }
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function addBuyOrder($OrderInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_order', $OrderInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function addAuthOrder($authInfo)
    {
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('code', $authInfo['code']);
        $this->db->where('ordertype', '2');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return FALSE;
        $OrderInfo = $result['0'];
        $OrderInfo->userphone = $authInfo['userphone'];
        $OrderInfo->status = '2';
        $OrderInfo->paid_time = $authInfo['paid_time'];
        $this->db->select('*');
        $this->db->where('id', $OrderInfo->id);
        $this->db->update('tbl_order', $OrderInfo);
        return TRUE;
    }
}



/* End of file order_model.php */
/* Location: .application/models/order_model.php */
