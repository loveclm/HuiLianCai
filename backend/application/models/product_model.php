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

        if ($kind!= '0') $this->db->where('tbl_product_format.type', $kind);
        if ($brand!= '0') $this->db->where('tbl_product_format.brand', $brand);

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

        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) $result = NULL;
        return $result;
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
            $product['update_time'] = date("Y-m-d");

            $this->updateProduct($product);
            return TRUE;
        }
        $product['create_time'] = date("Y-m-d");
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
            $this->db->where('id', $itemInfo['id']);
            $result = $this->db->delete('tbl_product');
            return $result;
        }
        return 0;
    }

}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */