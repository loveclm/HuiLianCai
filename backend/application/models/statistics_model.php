<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class statistics_model extends CI_Model
{
    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getShipItems($data = '', $pay_type = 0)
    {
        //$type = $data['searchType'];
        $name = $data['searchName'];
        $start_month = $data['start_month'];
        $start_day = $data['start_day'];
        $end_month = $data['end_month'];
        $end_day = $data['end_day'];
        $provider_id = $data['provider_id'];

        $start = ($start_month != '0') ? date("Y") . '-' . $start_month . '-' . $start_day : '';
        $end = ($end_month != '0') ? date("Y") . '-' . $end_month . '-' . $end_day : '';

        // get all days between start date and end date
        $days = $this->getAllDayInfos($start, $end, 'create_time');
        if ($days == NULL) return array();

        $records = array();
        // get distinct ship man record along with each date
        foreach ($days as $day) {
            $this->db->select('tbl_order.ship_man');
            $this->db->from('tbl_order');
            $this->db->where('date(tbl_order.create_time)', $day->filter_date);
            $this->db->group_by('tbl_order.ship_man');
            $query = $this->db->get();
            $result = $query->result();

            if (count($result) == 0) continue;
            $ship_mans = $result;

            foreach ($ship_mans as $ship_man) {
                $ship_man_info = $this->getUserInfoById($ship_man->ship_man);
                if ($ship_man_info == NULL) continue;
                if ($name != '') {
                    if (strpos($ship_man_info->username, $name) === false) continue;
                }

                // get shipping total count and total money along with date and ship man
                $this->db->select('tbl_order.provider, sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost)-sum(tbl_order.refund_cost) as cost');
                $this->db->from('tbl_order');
                $this->db->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids');
                $this->db->where('date(tbl_order.create_time)', $day->filter_date);
                $this->db->where('tbl_order.ship_man', $ship_man->ship_man);
                $this->db->where('tbl_order.status', 4);
                if ($pay_type > 0)
                    $this->db->where('tbl_order.pay_method', $pay_type);
                if ($provider_id != '0')
                    $this->db->where('tbl_order.provider', $provider_id);

                $query = $this->db->get();
                $result = $query->result();
                if (count($result) == 0) continue;
                $total_info = $result[0];

                $provider_info = $this->getUserInfoById($total_info->provider);
                if ($provider_info == NULL) continue;

                $record = [
                    $day->filter_date,
                    $ship_man_info->userid,
                    $ship_man_info->username,
                    $provider_info->userid,
                    $provider_info->username,
                    $total_info->cnt,
                    number_format((float)$total_info->cost, 2, '.', '')
                ];

                array_push($records, $record);
            }
        }
        return $records;
    }

    function getProductItems($data)
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $provider_id = $data['provider_id'];

        // get activities of completed orders
        $records = array();
        $idlist = array();
        $activities = $this->getAllActivities();

        foreach ($activities as $activity) {
            // get all product list of a activity
            $this->db->select('product_id, buy_cnt, name');
            $this->db->from('tbl_activity');
            $this->db->where('id', $activity->id);
            if ($provider_id != '0')
                $this->db->where('provider_id', $provider_id);

            $this->db->order_by('start_time', 'desc');

            $query = $this->db->get();
            $result = $query->result();
            if (count($result) == 0) continue;

            foreach ($result as $item) {
                if ($type == 1 && name != '') {
                    if (strpos($item->name, $name) === false) continue;
                }

                $ids = explode(',', $item->product_id);
                $buy_cnt = json_decode($item->buy_cnt);

                for ($i = 0; $i < count($ids); $i++) {
                    $id = $ids[$i];
                    $cost = floatval($buy_cnt[$i]->cost);
                    $cnt = intval($buy_cnt[$i]->count);

                    $this->db->select('tbl_product_format.barcode, tbl_product.cost, tbl_product_format.name');
                    $this->db->from('tbl_product');
                    $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
                    $this->db->where('tbl_product.id', $id);
                    $query1 = $this->db->get();
                    $result1 = $query1->result();
                    if (count($result1) == 0) continue;

                    if ($type == 0 && $name != '') {
                        if (strpos($result1[0]->barcode, $name) === false) continue;
                    }
                    $exists = 0;
                    $k = 0;
                    foreach ($records as $key => $record) {
                        if ($idlist[$k] == $id) {
                            $records[$key][3] += ($activity->cnt + $activity->fcnt) * intval($cnt);
                            $records[$key][4] += $cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost);
                            $records[$key][4] = number_format($records[$key][4], 2, '.', '');
                            $exists = 1;
                            break;
                        }

                        $k++;
                    }

                    if ($exists == 0) {
                        $new_record = [
                            count($records) + 1,
                            $result1[0]->barcode,
                            $result1[0]->name,
                            ($activity->cnt + $activity->fcnt) * intval($cnt),
                            number_format($cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost), 2, '.', ''),
                        ];
                        array_push($idlist, $id);
                        array_push($records, $new_record);
                    }
                }
            }
        }
        return $this->sortRecords($records);
    }

    function getTopProductItems($provider_id)
    {
        $records = array();
        $idlist = array();
        $activities = $this->getTopAllActivities();
        foreach ($activities as $activity) {
            // get all product list of a activity
            $this->db->select('product_id, buy_cnt, name');
            $this->db->from('tbl_activity');
            $this->db->where('id', $activity->id);
            if ($provider_id != '0')
                $this->db->where('provider_id', $provider_id);

            $query = $this->db->get();
            $result = $query->result();
            if (count($result) == 0) continue;

            foreach ($result as $item) {

                $ids = explode(',', $item->product_id);
                $buy_cnt = json_decode($item->buy_cnt);

                for ($i = 0; $i < count($ids); $i++) {
                    $id = $ids[$i];

                    $cost = floatval($buy_cnt[$i]->cost);
                    $cnt = intval($buy_cnt[$i]->count);

                    $this->db->select('tbl_product_format.barcode, tbl_product_format.cover, tbl_product.cost, tbl_product_format.name');
                    $this->db->from('tbl_product');
                    $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
                    $this->db->where('tbl_product.id', $id);
                    $query1 = $this->db->get();
                    $result1 = $query1->result();
                    if (count($result1) == 0) continue;

                    $exists = 0;
                    $k = 0;
                    foreach ($records as $key => $record) {
                        if ($idlist[$k] == $id) {
                            $records[$key][4] += ($activity->cnt + $activity->fcnt) * intval($cnt);
                            $records[$key][5] += $cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost);
                            $records[$key][5] = number_format($records[$key][5], 2, '.', '');
                            $exists = 1;
                            break;
                        }

                        $k++;
                    }

                    if ($exists == 0) {
                        $cover = json_decode($result1[0]->cover);
                        $new_record = [
                            count($records) + 1,
                            $result1[0]->barcode,
                            '<img src="' . base_url() . $cover[1] . '" style="width:150px;height:80px;"/>',
                            $result1[0]->name,
                            ($activity->cnt + $activity->fcnt) * intval($cnt),
                            number_format($cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost), 2, '.', ''),
                        ];
                        array_push($idlist, $id);
                        array_push($records, $new_record);
                    }
                }
            }
        }

        return $this->getTopRecords($records);
    }

    function getShopItems($data)
    {
        $provider_id = $data['provider_id'];

        $type = $data['searchType'];
        $name = $data['searchName'];
        $start_month = $data['start_month'];
        $start_day = $data['start_day'];
        $end_month = $data['end_month'];
        $end_day = $data['end_day'];

        $start = ($start_month != '0') ? date("Y") . '-' . $start_month . '-' . $start_day : '';
        $end = ($end_month != '0') ? date("Y") . '-' . $end_month . '-' . $end_day : '';

        // get all days between start date and end date
        $days = $this->getAllDayInfos($start, $end, 'create_time');
        if ($days == NULL) return NULL;

        $datas = array();
        // get distinct shop record along with each date
        foreach ($days as $day) {
            $records = array();

            $this->db->select('tbl_order.shop');
            $this->db->from('tbl_order');
            $this->db->where('date(tbl_order.create_time)', $day->filter_date);
            $this->db->where('status', 4);
            $this->db->group_by('tbl_order.shop');
            $query = $this->db->get();
            $result = $query->result();

            if (count($result) == 0) continue;
            $shops = $result;

            foreach ($shops as $shop) {
                $shop_info = $this->getUserInfoById($shop->shop);
                if ($shop_info == NULL) continue;
                if ($name != '') {
                    if ($type == 0)
                        if (strpos($shop_info->userid, $name) === false) continue;
                        else if ($type == 1)
                            if (strpos($shop_info->username, $name) === false) continue;
                }

                // get shipping total count and total money along with date and ship man
                $this->db->select('sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost)-sum(tbl_order.refund_cost) as cost, pay_method');
                $this->db->from('tbl_order');
                $this->db->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids');
                $this->db->where('date(tbl_order.create_time)', $day->filter_date);
                $this->db->where('tbl_order.shop', $shop->shop);
                $this->db->where('tbl_order.pay_cost != ', 0);
                $this->db->where('tbl_order.status', 4);
                if ($provider_id != '0')
                    $this->db->where('tbl_order.provider', $provider_id);

                $this->db->group_by('tbl_order.pay_method');

                $query = $this->db->get();
                $result = $query->result();
                if (count($result) == 0) continue;
                if ($result[0]->cnt == 0) continue;

                foreach ($result as $total_info ) {

                    $record = [
                        $day->filter_date,
                        $shop_info->userid,
                        $shop_info->username,
                        ($total_info->pay_method == '1') ? '线上支付' : '货到付款',
                        $total_info->cnt,
                        number_format((float)$total_info->cost, 2, '.', '')
                    ];

                    array_push($records, $record);
                }
            }

            $records = $this->sortRecords($records);
            foreach ($records as $record) {
                array_push($datas, $record);
            }
        }

        return $datas;
    }

    function getTopShopItems($provider_id)
    {
        $start = date('Y-m-d', strtotime("-30 days"));
        $end = date('Y-m-d');

        $records = array();
        $this->db->select('tbl_order.shop, sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost)-sum(tbl_order.refund_cost) as cost');
        $this->db->from('tbl_order');
        $this->db->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids');
        $this->db->where('date(tbl_order.pay_time)>=', $start);
        $this->db->where('date(tbl_order.pay_time)<=', $end);
        $this->db->where('tbl_order.status', 4);
        if ($provider_id != '0')
            $this->db->where('tbl_order.provider', $provider_id);

        $this->db->group_by('tbl_order.shop');
        $this->db->order_by('sum(tbl_order.pay_cost)-sum(tbl_order.refund_cost)', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        $result = $query->result();

        $i = 0;
        foreach ($result as $item) {
            if(floatval($item->cost) == 0) continue;
            $shop_info = $this->getUserInfoById($item->shop);
            $i++;
            $record = [
                $i,
                $shop_info->userid,
                $shop_info->username,
                $item->cnt,
                number_format((float)$item->cost, 2, '.', '')
            ];
            array_push($records, $record);

        }
        return $records;
    }

    function getProviderItems($data)
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $start_month = $data['start_month'];
        $start_year = $data['start_year'];
        $end_month = $data['end_month'];
        $end_year = $data['end_year'];

        $start = ($start_month != '0') ? $start_year . '-' . $start_month . '-1' : '';
        $end = ($end_month != '0') ? $end_year . '-' . $end_month . '-31' : '';

        $result = $this->db->select('id')
            ->from('tbl_order')->limit(1)->get()->result();
        if(count($result) == 0) return $result;

        // get all days between start date and end date
        $sql = "select left(create_time, 7) as filter_date from tbl_order where status =4";
        if ($start != '')
            $sql .= " and date(create_time)>=" . $start;
        if ($end != '')
            $sql .= " and date(create_time)<=" . $end;
        $sql .= " GROUP BY left(create_time, 7) ORDER BY create_time desc";

        $query = $this->db->query($sql);
        $result = $query->result();
        if (count($result) == 0) return;
        $days = $result;

        $datas = array();
        // get distinct shop record along with each date
        foreach ($days as $day) {
            $records = array();

            $sql = "select id from tbl_userinfo where type = 2";

            $query = $this->db->query($sql);
            $result = $query->result();

            if (count($result) == 0) continue;
            $providers = $result;
            foreach ($providers as $provider) {
                $provider_info = $this->getUserInfoById($provider->id);
                if ($provider_info == NULL) continue;
                if ($name != '') {
                    if ($type == 0)
                        if (strpos($provider_info->userid, $name) === false) continue;
                        else if ($type == 1)
                            if (strpos($provider_info->username, $name) === false) continue;
                }
                // get shipping total count and total money along with date and ship man
                $sql = "select sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost - tbl_order.refund_cost) as cost from tbl_order";
                $sql .= " join tbl_activity on tbl_activity.id=tbl_order.activity_ids";
                $sql .= " where tbl_order.status=4 and left(tbl_order.create_time,7)='" . $day->filter_date;
                $sql .= "' and tbl_order.provider=" . $provider->id;

                $query = $this->db->query($sql);
                $result = $query->result();

                $total_info = $result[0];
                if($total_info->cost == null){
                    $total_info->cost = 0;
                    $total_info->cnt = 0;
                }

                $cur_cost = floatval($total_info->cost);
                $cur_platform_cost = $cur_cost * floatval($provider_info->ratio);

                $record = [
                    substr($day->filter_date, 0, 7),
                    $provider_info->userid,
                    $provider_info->username,
                    $total_info->cnt,
                    number_format((float)$total_info->cost, 2, '.', ''),
                    number_format((float)$cur_platform_cost, 2, '.', ''),
                    number_format((float)($cur_cost - $cur_platform_cost), 2, '.', '')
                ];
                array_push($records, $record);
            }
            $records = $this->sortRecords($records);
            foreach ($records as $record) {
                array_push($datas, $record);
            }
        }

        return $datas;
    }

    function getTopProviderItems()
    {
        $start = date('Y-m-d', strtotime("-30 days"));
        $end = date('Y-m-d');

        $records = array();
         // get shipping total count and total money along with date and ship man
        $this->db->select('provider, sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost)-sum(tbl_order.refund_cost) as cost');
        $this->db->from('tbl_order');
        $this->db->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids');
        $this->db->where('date(tbl_order.pay_time)>=', $start);
        $this->db->where('date(tbl_order.pay_time)<=', $end);
        $this->db->where('tbl_order.status', 4);

        $this->db->group_by('tbl_order.provider');
        $this->db->order_by('sum(tbl_order.pay_cost)-sum(tbl_order.refund_cost)', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        $result = $query->result();

        $i = 0;
        foreach ($result as $item) {
            $provider_info = $this->getUserInfoById($item->provider);

            $i++;
            $record = [
                $i,
                $provider_info->userid,
                $provider_info->username,
                $item->cnt,
                number_format((float)$item->cost, 2, '.', ''),
            ];

            array_push($records, $record);
        }

        return $records;
    }

    function getBrandItems($data)
    {
        $provider_id = $data['provider_id'];
        $type = $data['searchType'];

        // get activities of completed orders
        $records = array();
        $idlist = array();
        $activities = $this->getAllActivities();
        foreach ($activities as $activity) {
            // get all product list of a activity
            $this->db->select('product_id, buy_cnt, name');
            $this->db->from('tbl_activity');
            $this->db->where('id', $activity->id);
            if ($provider_id != '0')
                $this->db->where('provider_id', $provider_id);

            $this->db->order_by('start_time', 'desc');

            $query = $this->db->get();
            $result = $query->result();
            if (count($result) == 0) continue;

            foreach ($result as $item) {
                $ids = explode(',', $item->product_id);
                $buy_cnt = json_decode($item->buy_cnt);

                for ($i = 0; $i < count($ids); $i++) {
                    $id = $ids[$i];
                    $cost = floatval($buy_cnt[$i]->cost);
                    $cnt = intval($buy_cnt[$i]->count);

                    $this->db->select('tbl_product.cost, tbl_product_brand.image, tbl_product_brand.type, tbl_product_brand.id');
                    $this->db->from('tbl_product');
                    $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
                    $this->db->join('tbl_product_brand', 'tbl_product_format.brand = tbl_product_brand.id');
                    $this->db->where('tbl_product.id', $id);

                    $query1 = $this->db->get();
                    $result1 = $query1->result();
                    if (count($result1) == 0) continue;


                    if ($type != '0') {
                        if ($result1[0]->id != $type) continue;
                    }

                    $exists = 0;
                    $k = 0;
                    foreach ($records as $key => $record) {
                        if ($idlist[$k] == $result1[0]->id) {
                            $records[$key][3] += ($activity->cnt + $activity->fcnt) * intval($cnt);
                            $records[$key][4] += $cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost);
                            $records[$key][4] = number_format($records[$key][4], 2, '.', '');
                            $exists = 1;
                            break;
                        }

                        $k++;
                    }

                    if ($exists == 0) {
                        $new_record = [
                            count($records) + 1,
                            '<img src="' . base_url() . $result1[0]->image . '" style="width:150px;height:80px;"/>',
                            $this->product_util_model->getTypeNameById($result1[0]->type),
                            ($activity->cnt + $activity->fcnt) * intval($cnt),
                            number_format($cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost), 2, '.', ''),
                        ];

                        array_push($idlist, $result1[0]->id);
                        array_push($records, $new_record);
                    }
                }
            }
        }


        return $this->sortRecords($records);
    }

    function getTopBrandItems($provider_id)
    {
        // get activities of completed orders
        $records = array();
        $idlist = array();
        $activities = $this->getTopAllActivities();
        foreach ($activities as $activity) {
            // get all product list of a activity
            $this->db->select('product_id, buy_cnt, name');
            $this->db->from('tbl_activity');
            $this->db->where('id', $activity->id);
            if ($provider_id != '0')
                $this->db->where('provider_id', $provider_id);

            $this->db->order_by('start_time', 'desc');

            $query = $this->db->get();
            $result = $query->result();
            if (count($result) == 0) continue;

            foreach ($result as $item) {
                $ids = explode(',', $item->product_id);
                $buy_cnt = json_decode($item->buy_cnt);

                for ($i = 0; $i < count($ids); $i++) {
                    $id = $ids[$i];
                    $cost = floatval($buy_cnt[$i]->cost);
                    $cnt = intval($buy_cnt[$i]->count);

                    $this->db->select('tbl_product.cost, tbl_product_brand.image, tbl_product_brand.type, tbl_product_brand.id');
                    $this->db->from('tbl_product');
                    $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
                    $this->db->join('tbl_product_brand', 'tbl_product_format.brand = tbl_product_brand.id');
                    $this->db->where('tbl_product.id', $id);

                    $query1 = $this->db->get();
                    $result1 = $query1->result();
                    if (count($result1) == 0) continue;

                    $exists = 0;
                    $k = 0;
                    foreach ($records as $key => $record) {
                        if ($idlist[$k] == $result1[0]->id) {
                            $records[$key][3] += ($activity->cnt + $activity->fcnt) * intval($cnt);
                            $records[$key][4] += $cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost);
                            $records[$key][4] = number_format($records[$key][4], 2, '.', '');
                            $exists = 1;
                            break;
                        }

                        $k++;
                    }

                    if ($exists == 0) {
                        $new_record = [
                            count($records) + 1,
                            '<img src="' . base_url() . $result1[0]->image . '" style="width:150px;height:80px;"/>',
                            $this->product_util_model->getTypeNameById($result1[0]->type),
                            ($activity->cnt + $activity->fcnt) * intval($cnt),
                            number_format($cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost), 2, '.', ''),
                        ];

                        array_push($idlist, $result1[0]->id);
                        array_push($records, $new_record);
                    }
                }
            }
        }
        return $this->getTopRecords($records);
    }

    function getTypeItems($data)
    {
        $provider_id = $data['provider_id'];
        $type = $data['searchType'];

        // get activities of completed orders
        $records = array();
        $idlist = array();
        $activities = $this->getAllActivities();
        foreach ($activities as $activity) {
            // get all product list of a activity
            $this->db->select('product_id, buy_cnt, name');
            $this->db->from('tbl_activity');
            $this->db->where('id', $activity->id);
            if ($provider_id != '0')
                $this->db->where('provider_id', $provider_id);

            $this->db->order_by('start_time', 'desc');

            $query = $this->db->get();
            $result = $query->result();
            if (count($result) == 0) continue;

            foreach ($result as $item) {
                $ids = explode(',', $item->product_id);
                $buy_cnt = json_decode($item->buy_cnt);

                for ($i = 0; $i < count($ids); $i++) {
                    $id = $ids[$i];
                    $cost = floatval($buy_cnt[$i]->cost);
                    $cnt = intval($buy_cnt[$i]->count);

                    $this->db->select('tbl_product.cost, tbl_product_format.type');
                    $this->db->from('tbl_product');
                    $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
                    $this->db->where('tbl_product.id', $id);

                    $query1 = $this->db->get();
                    $result1 = $query1->result();
                    if (count($result1) == 0) continue;

                    if ($type != '0') {
                        if ($result1[0]->type != $type) continue;
                    }

                    $exists = 0;
                    $k = 0;
                    foreach ($records as $key => $record) {
                        if ($idlist[$k] == $result1[0]->type) {
                            $records[$key][2] += ($activity->cnt + $activity->fcnt) * intval($cnt);
                            $records[$key][3] += $cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost);
                            $records[$key][3] = number_format($records[$key][3], 2, '.', '');
                            $exists = 1;
                            break;
                        }

                        $k++;
                    }

                    if ($exists == 0) {
                        $new_record = [
                            count($records) + 1,
                            $this->product_util_model->getTypeNameById($result1[0]->type),
                            ($activity->cnt + $activity->fcnt) * intval($cnt),
                            number_format($cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost), 2, '.', ''),
                        ];

                        array_push($idlist, $result1[0]->type);
                        array_push($records, $new_record);
                    }
                }
            }
        }
        return $this->sortRecords($records);
    }

    function getTopTypeItems($provider_id)
    {
        $records = array();
        $idlist = array();
        $activities = $this->getTopAllActivities();
        foreach ($activities as $activity) {
            // get all product list of a activity
            $this->db->select('product_id, buy_cnt, name');
            $this->db->from('tbl_activity');
            $this->db->where('id', $activity->id);
            if ($provider_id != '0')
                $this->db->where('provider_id', $provider_id);

            $query = $this->db->get();
            $result = $query->result();
            if (count($result) == 0) continue;

            foreach ($result as $item) {
                $ids = explode(',', $item->product_id);
                $buy_cnt = json_decode($item->buy_cnt);

                for ($i = 0; $i < count($ids); $i++) {
                    $id = $ids[$i];
                    $cost = floatval($buy_cnt[$i]->cost);
                    $cnt = intval($buy_cnt[$i]->count);

                    $this->db->select('tbl_product.cost, tbl_product_format.type');
                    $this->db->from('tbl_product');
                    $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
                    $this->db->where('tbl_product.id', $id);

                    $query1 = $this->db->get();
                    $result1 = $query1->result();
                    if (count($result1) == 0) continue;

                    $exists = 0;
                    $k = 0;
                    foreach ($records as $key => $record) {
                        if ($idlist[$k] == $result1[0]->type) {
                            $records[$key][2] += ($activity->cnt + $activity->fcnt) * intval($cnt);
                            $records[$key][3] += $cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost);
                            $records[$key][3] = number_format($records[$key][3], 2, '.', '');
                            $exists = 1;
                            break;
                        }

                        $k++;
                    }

                    if ($exists == 0) {
                        $new_record = [
                            count($records) + 1,
                            $this->product_util_model->getTypeNameById($result1[0]->type),
                            ($activity->cnt + $activity->fcnt) * intval($cnt),
                            number_format($cnt * ($activity->cnt * $cost + $activity->fcnt * $result1[0]->cost), 2, '.', ''),
                        ];

                        array_push($idlist, $result1[0]->type);
                        array_push($records, $new_record);
                    }
                }
            }
        }
        return $this->getTopRecords($records);
    }

    function getSalePerformanceItems($data)
    {
        $provider_id = $data['provider_id'];
        $start_month = $data['start_month'];
        $start_year = $data['start_year'];
        $end_month = $data['end_month'];
        $end_year = $data['end_year'];

        $start = ($start_month != '0') ? $start_year . '-' . $start_month . '-1' : '';
        $end = ($end_month != '0') ? $end_year . '-' . $end_month . '-31' : '';

        $result = $this->db->select('id')
            ->from('tbl_order')->limit(1)->get()->result();
        if(count($result) == 0) return $result;

        // get all days between start date and end date
        $sql = "select left(create_time, 7) as date_filter from tbl_order where status =4";
        if ($start != '')
            $sql .= " and date(create_time)>=" . $start;
        if ($end != '')
            $sql .= " and date(create_time)<=" . $end;
        $sql .= " GROUP BY left(create_time, 7) ORDER BY create_time desc";

        $query = $this->db->query($sql);
        $result = $query->result();

        if (count($result) == 0) return;
        $days = $result;
        $records = array();
        // get distinct shop record along with each date
        foreach ($days as $day) {
            $this->db->select('sum(tbl_activity.product_cnt * tbl_order.activity_cnts) as cnt, sum(tbl_order.pay_cost - tbl_order.refund_cost) as cost, tbl_order.provider');
            $this->db->from('tbl_order');
            $this->db->join('tbl_activity', 'tbl_activity.id=tbl_order.activity_ids');
            $this->db->where('left(tbl_order.create_time,7)', $day->date_filter);
            $this->db->where('tbl_order.status', 4);
            if ($provider_id != '0')
                $this->db->where('tbl_order.provider', $provider_id);

            $this->db->group_by('tbl_order.provider');
            $query = $this->db->get();
            $result = $query->result();

            if (count($result) == 0) continue;

            $cur_cnt = 0;
            $cur_cost = 0;
            $cur_platform_cost = 0;
            // get platform cost ratio
            foreach ($result as $item) {
                $ratio = $this->getRatioOfProvider($item->provider);
                $cur_cnt += intval($item->cnt);
                $cur_cost += floatval($item->cost);
                $cur_platform_cost += floatval($cur_cost) * $ratio;
            }
            $record = [
                $day->date_filter,
                $cur_cnt,
                number_format((float)$cur_cost, 2, '.', ''),
                number_format((float)$cur_platform_cost, 2, '.', ''),
                number_format((float)($cur_cost - $cur_platform_cost), 2, '.', '')
            ];

            array_push($records, $record);
        }
        return $records;
    }

    function getRecommendproviderInfos($data = '')
    {
        $name = $data['searchName'];
        $start_month = $data['start_month'];
        $start_day = $data['start_day'];
        $end_month = $data['end_month'];
        $end_day = $data['end_day'];

        $start = ($start_month != '0') ? date("Y") . '-' . $start_month . '-' . $start_day : '';
        $end = ($end_month != '0') ? date("Y") . '-' . $end_month . '-' . $end_day : '';
        // get all users between start date and end date
        $records = array();

        // get recommended number of the user
        $this->db->select('count(tbl_userinfo.saleman)  as cnt, tbl_user.username, tbl_user.userid, tbl_user.id');
        $this->db->from('tbl_userinfo');
        $this->db->join('tbl_user', 'tbl_userinfo.saleman = tbl_user.id');
        $this->db->where('tbl_userinfo.type', 2);

        if ($start != '')
            $this->db->where('date(tbl_userinfo.created_time)>=\'' . $start . '\'');
        if ($end != '')
            $this->db->where('date(tbl_userinfo.created_time)<=\'' . $end . '\'');

        $this->db->group_by('tbl_userinfo.saleman');
        $query = $this->db->get();
        $result = $query->result();

        foreach ($result as $item) {
            if ($name != '') {
                if (strpos($item->name, $name) === false) continue;
            }

            $record = [
                $item->userid,
                $item->username,
                $item->cnt,
                '<a href="' . base_url() . 'showProviders/' . $item->id . '">推荐明细</a>'
            ];

            array_push($records, $record);
        }

        return $records;
    }

    function getRecommendshopInfos($data = '')
    {
        $name = $data['searchName'];
        $start_month = $data['start_month'];
        $start_day = $data['start_day'];
        $end_month = $data['end_month'];
        $end_day = $data['end_day'];

        $start = ($start_month != '0') ? date("Y") . '-' . $start_month . '-' . $start_day : '';
        $end = ($end_month != '0') ? date("Y") . '-' . $end_month . '-' . $end_day : '';

        // get all users between start date and end date
        $this->db->select('count(saleman_mobile)  as cnt, saleman_mobile');
        $this->db->from('tbl_userinfo');
        $this->db->where('type', 3);
        $this->db->where('saleman_mobile <> \'\'');
        if ($start != '')
            $this->db->where('date(created_time)>=\'' . $start . '\'');
        if ($end != '')
            $this->db->where('date(created_time)<=\'' . $end . '\'');

        $this->db->group_by('saleman_mobile');
        $query = $this->db->get();
        $result = $query->result();

        $records = array();
        // get recommended number of the user
        foreach ($result as $user) {
            if ($name != '') {
                if (strpos($user->saleman_mobile, $name) === false) continue;
            }

            $record = [
                $user->saleman_mobile,
                $user->cnt,
                '<a href="' . base_url() . 'showShops/' . $user->saleman_mobile . '/' . $start . '/' . $end . '">推荐明细</a>'
            ];

            array_push($records, $record);
        }

        return $records;
    }

    function getAllActivities()
    {
        $this->db->select('activity_ids, activity_cnts,isSuccess');
        $this->db->from('tbl_order');
        $this->db->where('status', 4);
        $this->db->order_by('complete_time', "desc");

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return $result;

        $activities = array();
        foreach ($result as $item) {
            $ids = explode(',', $item->activity_ids);
            $cnts = explode(',', $item->activity_cnts);

            for ($i = 0; $i < count($ids); $i++) {
                $id = $ids[$i];
                $cnt = $cnts[$i];
                $exists = 0;
                foreach ($activities as $activity) {
                    if ($activity->id == $id) {
                        if ($item->isSuccess == 1)
                            $activity->cnt += $cnt;
                        else
                            $activity->fcnt += $cnt;

                        $exists = 1;
                        break;
                    }
                }
                if ($exists == 0) {
                    $activity = new stdClass();
                    $activity->id = $id;
                    if ($item->isSuccess == 1) {
                        $activity->cnt = $cnt;
                        $activity->fcnt = 0;
                    } else {
                        $activity->cnt = 0;
                        $activity->fcnt = $cnt;
                    }
                    array_push($activities, $activity);
                }
            }
        }

        return $activities;
    }

    function getTopAllActivities()
    {
        $start = date('Y-m-d', strtotime("-30 days"));
        $end = date('Y-m-d');

        $this->db->select('activity_ids, activity_cnts,isSuccess');
        $this->db->from('tbl_order');
        $this->db->where('status', 4);
        $this->db->where('date(pay_time)>=', $start);
        $this->db->where('date(pay_time)<=', $end);
        $this->db->order_by('pay_time', "desc");

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return NULL;
        $activities = array();
        foreach ($result as $item) {
            $ids = explode(',', $item->activity_ids);
            $cnts = explode(',', $item->activity_cnts);

            for ($i = 0; $i < count($ids); $i++) {
                $id = $ids[$i];
                $cnt = $cnts[$i];
                $exists = 0;
                foreach ($activities as $activity) {
                    if ($activity->id == $id) {
                        if ($item->isSuccess == 1)
                            $activity->cnt += $cnt;
                        else
                            $activity->fcnt += $cnt;

                        $exists = 1;
                        break;
                    }
                }
                if ($exists == 0) {
                    $activity = new stdClass();
                    $activity->id = $id;
                    if ($item->isSuccess == 1) {
                        $activity->cnt = $cnt;
                        $activity->fcnt = 0;
                    } else {
                        $activity->cnt = 0;
                        $activity->fcnt = $cnt;
                    }
                    array_push($activities, $activity);
                }
            }
        }
        return $activities;
    }

    function getTopRecords($datas){
        $records = array();
        if(count($datas) == 0) return $records;
        $n = count($datas[0])-1;
	    $k = count($datas);
	
        $cnt = 0;
        while(1){
            if(count($datas) == 0) break;
            if($cnt == 10) break;

            $cost = -1; $j = -1;
            for( $i = 0; $i < $k; $i++){
                if(!isset($datas[$i])) continue;
                if( $cost < $datas[$i][$n]) {
                    $j = $i; $cost = $datas[$i][$n];
                }
            }

            if($j == -1) break;
            $record = $datas[$j];
            array_push($records, $record);
            unset($datas[$j]);
            $cnt++;
        }
        return $records;
    }

    function getUserInfoById($id)
    {
        $this->db->select('userid, username, ratio');
        $this->db->from('tbl_userinfo');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) return NULL;
        return $result[0];
    }

    function getAllDayInfos($start, $end, $time_field)
    {
        $result = $this->db->select('id')
            ->from('tbl_order')->limit(1)->get()->result();
        if(count($result) == 0) return NULL;

        $this->db->select('date(' . $time_field . ') as filter_date');
        $this->db->from('tbl_order');
        if ($start != '')
            $this->db->where('date(' . $time_field . ')>=' . $start);
        if ($end != '')
            $this->db->where('date(' . $time_field . ')<=' . $end);

        $this->db->group_by('date(' . $time_field . ')');
        $this->db->order_by($time_field, 'desc');

        $query = $this->db->get();
        $result = $query->result();

        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function getRatioOfProvider($id)
    {
        $this->db->select('ratio');
        $this->db->from('tbl_userinfo');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return 1;

        return floatval($result[0]->ratio);
    }

    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if (!isset($item) || ($item['id'] == '0')) {
            $this->db->trans_start();

            $this->db->insert('tbl_coupon', $item);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        } else {
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
        $result = $this->db->update('tbl_coupon', $item);
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
            $result = $this->db->delete('tbl_coupon');
            return $result;
        }

        return 0;
    }

    function sortRecords($datas){
        $records = array();
        if(count($datas) == 0) return $records;
        $n = count($datas[0])-2;
        $k = count($datas);
        $cnt = 0;
        while(1){
            if(count($datas) == 0) break;

            $cost = -1; $j = -1;
            for( $i = 0; $i < $k; $i++){
                if(!isset($datas[$i])) continue;
                if( $cost < $datas[$i][$n]) {
                    $j = $i; $cost = $datas[$i][$n];
                }
            }

            if($j == -1) break;

            $cnt++;
            $record = $datas[$j];
            if(strlen($record[0]) < 6) $record[0] = $cnt;
            array_push($records, $record);
            unset($datas[$j]);
        }
        return $records;
    }
}

/* End of file withdraw_model.php */
/* Location: ./application/models/withdraw_model.php */