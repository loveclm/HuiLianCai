<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class activity_model extends CI_Model
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
        $provider_id = $data['provider_id'];

        $this->db->select('tbl_activity.*, tbl_userinfo.username');
        $this->db->from('tbl_activity');
        $this->db->join('tbl_userinfo', 'tbl_activity.provider_id = tbl_userinfo.id');
        $this->db->where('tbl_activity.kind', $data['kind']);
        if ($provider_id != '0')
            $this->db->where('tbl_activity.provider_id', $provider_id);

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_activity.id  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(tbl_activity.name  LIKE '%" . $name . "%')") : '';
                break;
        }
        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if ($status != '0') $this->db->where('tbl_activity.status', $status);
        if (isset($data['searchOrder'])) {
            switch ($data['searchOrder']) {
                case '1':
                    $this->db->where('tbl_activity.order !=', '1000');
                    break;
                case '2':
                    $this->db->where('tbl_activity.order', '1000');
                    break;
            }

            switch ($data['searchRecommend']) {
                case '1':
                    $this->db->where('tbl_activity.recommend_status !=', '0');
                    break;
                case '2':
                    $this->db->where('tbl_activity.recommend_status', '0');
                    break;
            }
        }
        $this->db->order_by('tbl_activity.order', 'asc');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function getProviderProducts($provider_id)
    {
        $this->db->select('tbl_activity.id as activity_id, tbl_activity.name as activity_name, tbl_activity.create_time as added_time, tbl_activity.*, tbl_product.id as storeId, tbl_product.store, tbl_product.cost, tbl_product_format.*');
        $this->db->from('tbl_activity');
        $this->db->join('tbl_product', 'tbl_activity.product_id = tbl_product.id');
        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
        $this->db->where('tbl_activity.status', 2);
        $this->db->where('tbl_activity.provider_id', $provider_id);

        $this->db->order_by('tbl_activity.recommend_status', "desc");
        $this->db->order_by('tbl_activity.order', 'asc');
        $this->db->order_by('tbl_activity.start_time', "desc");

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function getActivityInfosByTypeAndBrand($type, $brand)
    {
        $this->db->select('tbl_activity.id as activity_id, tbl_activity.name as activity_name, tbl_activity.create_time as added_time, tbl_activity.*, tbl_product.id as storeId, tbl_product.store, tbl_product.cost, tbl_product_format.*');
        $this->db->from('tbl_activity');
        $this->db->join('tbl_product', 'tbl_activity.product_id = tbl_product.id');
        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
        $this->db->where('tbl_activity.status', 2);
        $this->db->where('tbl_product_format.brand', $brand);

        if ($type == 0)
            $this->db->where('tbl_activity.recommend_status', 1);
        else
            $this->db->where('tbl_product_format.type', $type);

        $this->db->order_by('tbl_activity.recommend_status', "desc");
        $this->db->order_by('tbl_activity.order', 'asc');
        $this->db->order_by('tbl_activity.start_time', "desc");

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    // this function is used to get activities for starting
    function getActivitiesForStarting()
    {
        $query = $this->db->select('id, start_time, end_time')
            ->from('tbl_activity')
            ->where('status', 1)
            ->where('start_time < \'' . date('Y-m-d H:i:s') . '\'')
            ->get();

        return $query->result();
    }

    // this function is used to get activities for ending
    function getActivitiesForEnding()
    {
        $query = $this->db->select('id, end_time, man_cnt, group_cnt, users, nums, provider_id, name')
            ->from('tbl_activity')
            ->where('status', 2)
            ->where('end_time < \'' . date('Y-m-d H:i:s') . '\'')
            ->get();

        return $query->result();
    }

    function getActivityStatus($ids){
        if($ids == '') return array();

        $result = $this->db->select('id, status')
            ->from('tbl_activity')
            ->where('id in(' . $ids . ')')
            ->get()->result();
        return $result;
    }
    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('tbl_activity.id as activity_id, tbl_activity.name as activity_name, tbl_activity.create_time as added_time, tbl_activity.*, tbl_product.id as storeId, tbl_product.store, tbl_product.cost, tbl_product_format.*');
        $this->db->from('tbl_activity');
        $this->db->join('tbl_product', 'tbl_activity.product_id = tbl_product.id');
        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
        $this->db->where('tbl_activity.id', $id);

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return NULL;

        return $result[0];
    }

    function getProductList($type, $brand, $provider_id)
    {
        $this->db->select('tbl_product.id, tbl_product_format.name');
        $this->db->from('tbl_product');
        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
        if ($provider_id != '0')
            $this->db->where('tbl_product.provider_id', $provider_id);
        $this->db->where('tbl_product_format.type', $type);
        $this->db->where('tbl_product_format.brand', $brand);

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;

        return $result;
    }

    function getProductsFromIds($ids, $provider_id)
    {
        if ($ids == '') return NULL;
        $this->db->select('tbl_product.id, tbl_product_format.barcode, tbl_product_format.name, tbl_product_format.cover, tbl_product.cost, tbl_product.store');
        $this->db->from('tbl_product');
        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
        $this->db->where('tbl_product.id in(' . $ids . ')');
        if ($provider_id != '0')
            $this->db->where('tbl_product.provider_id', $provider_id);

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;

        return $result;
    }

    function getProducts($provider_id)
    {
        $this->db->select('tbl_product.id, tbl_product_format.barcode, tbl_product_format.name, tbl_product_format.cover, tbl_product.cost, tbl_product.store');
        $this->db->from('tbl_product');
        $this->db->join('tbl_product_format', 'tbl_product.product_id = tbl_product_format.id');
        if ($provider_id != '0')
            $this->db->where('tbl_product.provider_id', $provider_id);

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;

        return $result;
    }

    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if (!isset($item) || ($item['id'] == '0')) {
            $item['id'] = sprintf("%d%'.02d%'.09d%'04d", $item['kind'], $item['provider_id'], time() % 1e9, mt_rand(1000,9999));
            $this->db->trans_start();
            $item['create_time'] = date("Y-m-d H:i:s");
            $this->db->insert('tbl_activity', $item);
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
        $result = $this->db->update('tbl_activity', $item);

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
            $result = $this->db->delete('tbl_activity');
            return $result;
        }

        return 0;
    }

}

/* End of file activity_model.php */
/* Location: ./application/models/activity_model.php */