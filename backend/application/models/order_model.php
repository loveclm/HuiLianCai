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
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result = NULL;
        return $result;
    }

    // this function is used to get shop's order list
    function getOrdersByShop($user_id){
        $this->db->select('*');
        $this->db->from('tbl_order');
        $this->db->where('shop', $user_id);
        $query = $this->db->get();

        return $query->result();
    }

    // this function is used to get orders for canceling
    function getOrdersForCanceling()
    {
        $query = $this->db->select('id, start_time, end_time')
            ->from('tbl_order')
            ->where('status', 1)
            ->where('create_time<', date('Y-m-d H:i:s',strtotime("-3 hours")))
            ->get();

        return $query->result();
    }

    // this function is used to get orders for canceling
    function getOrdersForCompleting()
    {
        $query = $this->db->select('id, start_time, end_time')
            ->from('tbl_order')
            ->where('status', 3)
            ->where('success_time<', date('Y-m-d H:i:s',strtotime("-3 days")))
            ->get();

        return $query->result();
    }

    function getOrderByActivityId($activity_id){
        $query = $this->db->select('*')
            ->from('tbl_order')
            ->where('activity_ids', $activity_id)
            ->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        return $result[0];
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

    function getYesterdayOrderStatus(){
        $cnt = '--';
        $cost = '--';
        $yesterday = date('Y-m-d',strtotime("-1 days"));

        $this->db->select('count(id) as cnt, sum(pay_cost-refund_cost) as cost');
        $this->db->from('tbl_order');
        $this->db->where('status in(2,3,4)');
        $this->db->where('date(pay_time)', $yesterday);
        $query = $this->db->get();
        $result = $query->result();

        if( count($result) > 0){
            $cnt = intval($result[0]->cnt) == 0 ? '--' : $result[0]->cnt;
            $cost = floatval($result[0]->cost) == 0 ? '--' : number_format((float)$result[0]->cost, 2, '.','');
        }
        return array('cnt' => $cnt, 'cost' => $cost);
    }

    function getMonthOrderStatus(){
        $cnt = '--';
        $cost = '--';
        $cur_month = date("Y-m");

        $this->db->select('count(id) as cnt, sum(pay_cost-refund_cost) as cost');
        $this->db->from('tbl_order');
        $this->db->where('status in(2,3,4)');
        $this->db->where('left(pay_time, 7)='. $cur_month);
        $query = $this->db->get();
        $result = $query->result();

        if( count($result) > 0){
            $cnt = intval($result[0]->cnt) == 0 ? '--' : $result[0]->cnt;
            $cost = floatval($result[0]->cost) == 0 ? '--' : number_format((float)$result[0]->cost, 2, '.','');
        }
        return array('cnt' => $cnt, 'cost' => $cost);
    }

//    function getProductList($type, $brand, $provider_id){
//        $this->db->select('tbl_product.id, tbl_product_format.name');
//        $this->db->from('tbl_product');
//        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
//        if ($provider_id != '0')
//            $this->db->where('tbl_product.provider_id', $provider_id);
//        $this->db->where('tbl_product_format.type', $type);
//        $this->db->where('tbl_product_format.brand', $brand);
//
//        $query = $this->db->get();
//        $result = $query->result();
//        if(count($result) == 0) $result = NULL;
//
//        return $result;
//    }
//
//    function getProductsFromIds($ids, $provider_id){
//        if($ids == '') return NULL;
//        $this->db->select('tbl_product.id, tbl_product_format.barcode, tbl_product_format.name, tbl_product_format.cover, tbl_product.cost, tbl_product.store');
//        $this->db->from('tbl_product');
//        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
//        $this->db->where('tbl_product.id in('. $ids . ')');
//        if ($provider_id != '0')
//            $this->db->where('tbl_product.provider_id', $provider_id);
//
//        $query = $this->db->get();
//        $result = $query->result();
//        if(count($result) == 0) $result = NULL;
//
//        return $result;
//    }
//
//    function getProducts($provider_id){
//        $this->db->select('tbl_product.id, tbl_product_format.barcode, tbl_product_format.name, tbl_product_format.cover, tbl_product.cost, tbl_product.store');
//        $this->db->from('tbl_product');
//        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
//        if ($provider_id != '0')
//            $this->db->where('tbl_product.provider_id', $provider_id);
//
//        $query = $this->db->get();
//        $result = $query->result();
//        if(count($result) == 0) $result = NULL;
//
//        return $result;
//    }

    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if( !isset($item) || ($item['id'] == '0')){
            $item['id'] =  sprintf("%d%'.02d%'.09d", $item['pay_method'], $item['shop'], time()%1e9);
            $this->db->trans_start();
            $item['create_time'] = date("Y-m-d H:i:s");
            $this->db->insert('tbl_order', $item);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
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