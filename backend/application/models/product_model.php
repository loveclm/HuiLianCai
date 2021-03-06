<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class product_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '')
    {
        $type = $data['searchType'];
        $name = $data['searchName'];
        $kind = $data['searchKind'];
        $brand = $data['searchBrand'];
        $provider_id = $data['provider_id'];

        $this->db->select('tbl_product_format.*, products.id as storeId, tbl_userinfo.username as provider_name, products.store, products.cost');
        $this->db->from('tbl_product as products');
        $this->db->join('tbl_userinfo', 'products.provider_id=tbl_userinfo.id');
        $this->db->join('tbl_product_format', 'products.product_id=tbl_product_format.id');

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_product_format.barcode  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(tbl_product_format.name  LIKE '%" . $name . "%')") : '';
                break;
            case '2': // provider_name
                $likeCriteria = $name != '' ? ("(tbl_userinfo.username  LIKE '%" . $name . "%')") : '';
                break;
        }
        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if($provider_id != '0') $this->db->where('products.provider_id', $provider_id);

        if ($kind!= '0') $this->db->where('tbl_product_format.type', $kind);
        if ($brand!= '0') $this->db->where('tbl_product_format.brand', $brand);

        $this->db->order_by('products.create_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function getProductList($type, $brand){
        $this->db->select('id, name');
        $this->db->from('tbl_product_format');
        $this->db->where('type', $type);
        $this->db->where('brand', $brand);

        $this->db->order_by('create_time', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) $result = NULL;
        return $result;
    }

    function isDeletable($id){
        $activities = $this->db->select('product_id')
            ->from('tbl_activity')
            ->get()->result();

        if(count($activities) == 0) return true;

        foreach ($activities as $activity){
            $ids = explode(',', $activity->product_id);
            foreach ($ids as $cur_id){
                if($cur_id == $id ) return false;
            }
        }
        return true;
    }
    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('tbl_product_format.*, products.id as storeId, tbl_userinfo.username as provider_name, products.store, products.cost');
        $this->db->from('tbl_product as products');
        $this->db->join('tbl_userinfo', 'products.provider_id=tbl_userinfo.id');
        $this->db->join('tbl_product_format', 'products.product_id=tbl_product_format.id');
        $this->db->where('products.id', $id);

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return NULL;
        return $result[0];
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewProduct($product)
    {

        if($product['id'] != '0'){
            $product['update_time'] = date("Y-m-d H:i:s");

            $this->updateProduct($product);
            return TRUE;
        }
        $product['create_time'] = date("Y-m-d H:i:s");
        $this->db->trans_start();
        $this->db->insert('tbl_product', $product);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return true;
    }

    function getItemByBarcode($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_format');
        $this->db->where('barcode', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    function updateProduct($product){
        $this->db->where('id', $product['id']);
        $result = $this->db->update('tbl_product', $product);
        return $result;
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
        $this->db->where('id', $id);
        $result = $this->db->update('tbl_product_format', $itemInfo);
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
            $this->db->where('id', $itemInfo['userId']);
            $result = $this->db->delete('tbl_product');
            return $result;
        }
        return 0;
    }

    function isExist($id, $provider){
        $result = $this->db->select('id')
            ->from('tbl_product')
            ->where('product_id', $id)
            ->where('provider_id', $provider)
            ->get()->result();

        if(count($result) == 0) return false;
        return true;
    }
}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */