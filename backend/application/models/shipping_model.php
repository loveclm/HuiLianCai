<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class shipping_model extends CI_Model
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
        $address = $data['address'];
        $status = $data['searchStatus'];

        $this->db->select('a.*, b.username as provider, b.address as provider_addr');
        $this->db->from('tbl_userinfo as a');
        $this->db->join('tbl_userinfo as b', 'a.provider_id = b.id');
        $this->db->where('a.type', 4);
        if($provider_id != '0'){
            $this->db->where('a.provider_id', $provider_id);
        }

            switch ($type) {
                case '0': // account
                    $likeCriteria = $name != '' ? ("(a.userid  LIKE '%" . $name . "%')") : '';
                    break;
                case '1': // name
                    $likeCriteria = $name != '' ? ("(a.username  LIKE '%" . $name . "%')") : '';
                    break;
                case '2': // contact
                    $likeCriteria = $name != '' ? ("(a.contact_phone  LIKE '%" . $name . "%')") : '';
                    break;
                case '3': // recommend
                    $likeCriteria = $name != '' ? ("(b.username  LIKE '%" . $name . "%')") : '';
                    break;
            }
            if ($likeCriteria != '') $this->db->where($likeCriteria);

            $likeCriteria = ($address != '') ? ("(b.address  LIKE '%" . $address . "%')") : '';
            if ($address != '') $this->db->where($likeCriteria);

            if ($status != '0') $this->db->where('a.status', $status);

        $this->db->order_by('a.id', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function getShipItems($data = '')
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $status = $data['searchStatus'];

        $start_month = $data['start_month'];
        $start_day = $data['start_day'];
        $end_month = $data['end_month'];
        $end_day = $data['end_day'];
        $provider = $data['provider_id'];

        $start = ($start_month != '0') ? date("Y") . '-' . $start_month . '-' . $start_day : '';
        $end = ($end_month != '0') ? date("Y") . '-' . $end_month . '-' . $end_day : '';
        // get all days between start date and end date
        $this->db->select('tbl_shipping.*, tbl_userinfo.username');
        $this->db->from('tbl_shipping');
        $this->db->join('tbl_userinfo', 'tbl_shipping.ship_id=tbl_userinfo.id');
        $this->db->where('tbl_shipping.provider', $provider);

        if ($start != '')
            $this->db->where('date(tbl_shipping.create_time)>=\'' . $start . '\'');
        if ($end != '')
            $this->db->where('date(tbl_shipping.create_time)<=\'' . $end . '\'');

        switch ($type){
            case '0':
                if($name != '')
                    $this->db->where('(tbl_shipping.id like \'%'.$name.'%\')');
                break;
            case '1':
                if($name != '')
                    $this->db->where('(tbl_userinfo.username like \'%'.$name.'%\')');
                break;
        }

        if($status == '1')
            $this->db->where('tbl_shipping.state', $status);
        else if($status == '2')
            $this->db->where('tbl_shipping.state > 1');

        $this->db->order_by('tbl_shipping.create_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) return NULL;

        return $result;
    }

    function getShippingItems($data){
        $start = $data['start'];
        $end = $data['end'];
        $ship_id = $data['ship_id'];

        // get all days between start date and end date
        $this->db->select('*');
        $this->db->from('tbl_shipping');
        $this->db->where('state', 2);
        $this->db->where('ship_id', $ship_id);

        if ($start != '')
            $this->db->where('tbl_shipping.create_time >= \''. $start .'\'');
        if ($end != '')
            $this->db->where('tbl_shipping.create_time <= \''. $end .'\'');

        //$this->db->group_by('tbl_shipping.create_time');
        $this->db->order_by('tbl_shipping.create_time', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function getShippingHistory($data){
        $start = $data['start'];
        $end = $data['end'];
        $ship_id = $data['ship_id'];

        // get all days between start date and end date
        $this->db->select('*');
        $this->db->from('tbl_shipping');
        $this->db->where('state > 1');
        $this->db->where('ship_id', $ship_id);

        if ($start != '')
            $this->db->where('tbl_shipping.create_time >= \''. $start .'\'');
        if ($end != '')
            $this->db->where('tbl_shipping.create_time <= \''. $end .'\'');

        //$this->db->group_by('tbl_shipping.create_time');
        $this->db->order_by('tbl_shipping.create_time', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function getShippingAllItems(){
        // get all days between start date and end date
        $this->db->select('id, order_id');
        $this->db->from('tbl_shipping');
        $this->db->where('state', 2);
        $this->db->order_by('tbl_shipping.create_time', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function getShippingCount($id){
        $result = $this->db->select('sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt')
            ->from('tbl_order')
            ->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids')
            ->where('tbl_order.status', 4)
            ->where('tbl_order.ship_man', $id)
            ->get()->result();

        if($result[0]->cnt == NULL) return 0;
        return $result[0]->cnt;
    }
    function getShippingCountFromOrders($ids){
        $result = $this->db->select('sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt')
            ->from('tbl_order')
            ->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids')
            //->where('tbl_order.status', 4)
            ->where('tbl_order.id in('. $ids . ')')
            ->get()->result();

        if($result[0]->cnt == NULL) return 0;
        return $result[0]->cnt;
    }

    function getProductInfosByShipping($id){
        $result = $this->db->select('order_id')
            ->from('tbl_shipping')
            ->where('id', $id)
            ->get()->result();
        if(count($result) == 0) return NULL;
        $shipping_order_ids = $result[0]->order_id;

        $this->db->select('tbl_order.activity_ids as activity_id, tbl_order.activity_cnts');
        $this->db->from('tbl_order');
        $this->db->where('tbl_order.id in('.$shipping_order_ids.')');
        //$this->db->where('tbl_order.status', 4);
        $this->db->order_by('tbl_order.create_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        $records = array();
        foreach ($result as $item){
            $activity_id = $item->activity_id;
            $activity_cnt = $item->activity_cnts;
            /// get the information of the activity
            $this->db->select('product_id, group_cnt, buy_cnt');
            $this->db->from('tbl_activity');
            $this->db->where('id in ('.$activity_id.')');
            $query = $this->db->get();
            $result = $query->result();

            if(count($result) == 0) continue;

            $ids = explode(',', $result[0]->product_id);
            $buy_cnt = json_decode($result[0]->buy_cnt);

            for( $i = 0; $i < count($ids); $i++){
                //get product information
                $this->db->select('tbl_product_format.barcode, tbl_product_format.name');
                $this->db->from('tbl_product');
                $this->db->join('tbl_product_format', 'tbl_product.product_id=tbl_product_format.id');
                $this->db->where('tbl_product.id', $ids[$i]);
                $query1 = $this->db->get();
                $result1 = $query1->result();
                if (count($result1) == 0) continue;

                $product = $result1[0];
                $barcode = $product->barcode;

                $exists = 0;
                foreach ($records as  $key =>$record) {
                    if ($record['barcode'] == $barcode) {
                        $records[$key]['cnt'] += intval($activity_cnt) * intval($buy_cnt[$i]->count);
                        $exists = 1;
                        break;
                    }
                }

                if ($exists == 0) {
                    $record = array(
                        'id' => $id,
                        'barcode' => $barcode,
                        'name' => $product->name,
                        'cnt' => intval($activity_cnt) *  intval($buy_cnt[$i]->count)
                    );
                    array_push($records, $record);
                }

            }
        }
        return $records;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_userinfo');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    function getShippingItemByid($id){
        $this->db->select('*');
        $this->db->from('tbl_shipping');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;

        return $result[0];
    }

    // set shipping status of the order table
    function updateShippingStatus($item, $id){
        $this->db->where('id', $id);

        $this->db->update('tbl_order', $item);
    }

    function updateShippingData($item, $id){
        $this->db->where('id', $id);
        $result = $this->db->update('tbl_shipping', $item);

        return $result;
    }

    // add shipping data
    function addShippingItem($data){
        // check whether shipping record exists in shipping table
        $this->db->select('*');
        $this->db->from('tbl_shipping');
        $this->db->where('state', 1);
        $this->db->where('ship_id', $data['ship_id']);
        $this->db->where('create_time', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->result();

        if(count($result) == 0){
            $shipping_info = $data;
            $shipping_info['state'] = 1;
            $shipping_info['create_time'] = date('Y-m-d');
            $shipping_info['id'] = sprintf("%'.09d%'.02d", time()%1e9, mt_rand(10,99));

            $this->db->trans_start();
            $this->db->insert('tbl_shipping', $shipping_info);
            $this->db->insert_id();
            $this->db->trans_complete();
            return $shipping_info['id'];
        }else{
            $shipping_info = array(
                'order_id' => $result[0]->order_id . ',' . $data['order_id'],
                'money' => number_format(floatval($result[0]->money) + floatval($data['money']), 2,'.','')
            );
            $this->db->where('id', $result[0]->id);
            $result = $this->db->update('tbl_shipping', $shipping_info);
        }
        return $result;
    }
    //update shippping data

    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if ( !isset($item['id'])) {
            $this->db->trans_start();
            $item['created_time'] = date("Y-m-d H:i:s");
            $this->db->insert('tbl_userinfo', $item);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        } else {
            $item['update_time'] = date("Y-m-d H:i:s");
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
        $result = $this->db->update('tbl_userinfo', $item);
        return $result;
    }

    /**
     * This function is used to delete the shop information
     * @param number $shopId : This is shop id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($item)
    {
        if (isset($item['userId']) && $item['userId'] != '0') {
            $this->db->where('id', $item['userId']);
            $result = $this->db->delete('tbl_userinfo');
            return $result;
        }

        return 0;
    }

    function isDeletable($id){
        $result = $this->db->select('id')
            ->from('tbl_ship_allocate')
            ->where('shipman', $id)
            ->get()->result();
        if( count($result) == 0) return true;

        return false;
    }
}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */