<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class order_model extends CI_Model
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
        $method = $data['searchMethod'];

        $provider_id = $data['provider_id'];
        $this->db->select('id');
        $this->db->from('tbl_userinfo');
        if($provider_id =='0') {
            $this->db->where('type', 2);  // provider
            if($type == '3' && $name != '')
                $this->db->where("username like '%" . $name . "%'");
        }else{
            $this->db->where('type', 3);    //ship man
            if($type == '3' && $name != '')
                $this->db->where("username like '%" . $name . "%'");
        }
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        $userids = '';
        foreach ($result as $item){
            $userids .= ',' . $item->id;
        }
        $userids = substr($userids, 1);

        $this->db->select('tbl_order.*, tbl_userinfo.username as shop_name, tbl_userinfo.contact_name, tbl_userinfo.contact_phone');
        $this->db->from('tbl_order');
        $this->db->join('tbl_userinfo', 'tbl_order.shop = tbl_userinfo.id');
        if( $provider_id != '0')
            $this->db->where('provider', $provider_id);

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_order.id  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(tbl_userinfo.username  LIKE '%" . $name . "%')") : '';
                break;
            case '2': // contact_name
                $likeCriteria = $name != '' ? ("(tbl_userinfo.contact_name  LIKE '%" . $name . "%')") : '';
                break;
            case '3': // provider or ship man name
                if($userids == '') break;

                if($provider_id == '0'){
                    $likeCriteria = $name != '' ? ("tbl_order.provider in (" . $userids . ")") : '';
                }else{
                    $likeCriteria = $name != '' ? ("tbl_order.ship_man in (" . $userids . ")") : '';
                }
                break;
        }

        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if ($status != '0') $this->db->where('tbl_order.status', $status);
        if ($method != '0') $this->db->where('tbl_order.pay_method', $method);

        $this->db->order_by('create_time', 'desc');
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result = NULL;
        return $result;
    }

    // this function is used to get shop's order list
    function getOrdersByShop($user_id){
        $result = $this->db->select('*')
            ->from('tbl_order')
            ->where('shop', $user_id)
            ->order_by('create_time', 'desc')
            ->get()->result();
        return $result;
    }

    function getPrevOrder($user_id, $activity_id, $pay_method){
        $result = $this->db->select('*')
            ->from('tbl_order')
            ->where('shop', $user_id)
            ->where('activity_ids', $activity_id)
            ->where('pay_method', $pay_method)
            ->get()->result();

        if(count($result) == 0) return NULL;
        return $result[0];
    }

    // this function is used to get orders for canceling
    function getOrdersForCanceling()
    {
        $cur_date = date('Y-m-d H:i:s',strtotime("-3 hours"));

        $query = $this->db->select('id, status')
            ->from('tbl_order')
            ->where('status', 1)
            ->where('create_time < "'. $cur_date .'"')
            ->get();

        return $query->result();
    }

    // this function is used to get orders for canceling
    function getOrdersForCompleting()
    {
        $cur_date = date('Y-m-d H:i:s',strtotime("-3 days"));
        //var_dump( $cur_date);
        $query = $this->db->select('id, status')
            ->from('tbl_order')
            ->where('status', 3)
            ->where('success_time < "'. $cur_date .'"')
            ->get();
        return $query->result();
    }

    function getOrderByActivityId($activity_id){
        $query = $this->db->select('*')
            ->from('tbl_order')
            ->where('activity_ids', $activity_id)
            ->where('status <', 3)
            ->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        return $result;
    }

    function getOrderTimeByShop_ActivityId($shop_id, $activity_id){
        $this->db->select('create_time');
        $this->db->from('tbl_order');
        $this->db->where('shop', $shop_id);
        $this->db->where('activity_ids', $activity_id);

        $query = $this->db->get();
        $result = $query->result();

        if(count($result) == 0) return NULL;
        return $result[0]->create_time;
    }

    function getOrderIDByShop_ActivityId($shop_id, $activity_id){
        $this->db->select('id');
        $this->db->from('tbl_order');
        $this->db->where('shop', $shop_id);
        $this->db->where('activity_ids', $activity_id);

        $query = $this->db->get();
        $result = $query->result();

        if(count($result) == 0) return NULL;
        return $result[0]->id;
    }


    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('tbl_order.*, tbl_userinfo.userid as shop_id, tbl_userinfo.username as shop_name, tbl_userinfo.contact_name, tbl_userinfo.contact_phone, tbl_userinfo.address');
        $this->db->from('tbl_order');
        $this->db->join('tbl_userinfo', 'tbl_order.shop = tbl_userinfo.id');
        $this->db->where('tbl_order.id', $id);

        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        return $result[0];
    }

    function getYesterdayOrderStatus($provider_id){
        $cnt = '--';
        $cost = '--';
        $yesterday = date('Y-m-d',strtotime("-1 days"));

        $this->db->select('sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost-tbl_order.refund_cost) as cost');
        $this->db->from('tbl_order');
        $this->db->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids');
        $this->db->where('tbl_order.status', 4);
        $this->db->where('date(tbl_order.pay_time)=\''. $yesterday.'\'');
        if($provider_id != '0')
            $this->db->where('tbl_order.provider', $provider_id);

        $query = $this->db->get();
        $result = $query->result();

        if( count($result) > 0){
            $cnt = intval($result[0]->cnt) == 0 ? '--' : $result[0]->cnt;
            $cost = floatval($result[0]->cost) == 0 ? '--' : number_format((float)$result[0]->cost, 2, '.','');
        }
        return array('cnt' => $cnt, 'cost' => $cost);
    }

    function getMonthOrderStatus($provider_id){
        $cnt = '--';
        $cost = '--';
        $cur_month = date("Y-m");

        $this->db->select('sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost-tbl_order.refund_cost) as cost');
        $this->db->from('tbl_order');
        $this->db->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids');
        $this->db->where('tbl_order.status', 4);
        $this->db->where('left(tbl_order.pay_time, 7)=\''. $cur_month. '\'');
        if($provider_id != '0')
            $this->db->where('tbl_order.provider', $provider_id);

        $query = $this->db->get();
        $result = $query->result();

        if( count($result) > 0){
            $cnt = intval($result[0]->cnt) == 0 ? '--' : $result[0]->cnt;
            $cost = floatval($result[0]->cost) == 0 ? '--' : number_format((float)$result[0]->cost, 2, '.','');
        }
        return array('cnt' => $cnt, 'cost' => $cost);
    }

    function getShippingStatusOfOrderID($id){
        $result = $this->db->select('ship_status')
            ->from('tbl_order')
            ->where('id', $id)
            ->get()->result();

        return count($result) == 0 ? 0 : intval($result[0]->ship_status);
    }

    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if( !isset($item['id'])){
            $item['id'] =  sprintf("%d%'.02d%'.09d%'.05d", $item['pay_method'], $item['shop'], time()%1e9, mt_rand(10000, 99999));
            $this->db->trans_start();
            $item['create_time'] = date("Y-m-d H:i:s");
            $this->db->insert('tbl_order', $item);
            $this->db->insert_id();
            $this->db->trans_complete();
            return $item['id'];
        }else{
            //$item['update_time'] = date("Y-m-d H:i:s");
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
        $result = $this->db->update('tbl_order', $item);
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

/* End of file order_model.php */
/* Location: ./application/models/order_model.php */