<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class product_format_model extends CI_Model
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
        $this->db->select('tbl_product_format.*, brand.name as brand_name, kind.type as type_name, unit.name as unit_name');
        $this->db->from('tbl_product_format');
        $this->db->join('tbl_product_type as kind', 'tbl_product_format.type=kind.id');
        $this->db->join('tbl_product_brand as brand', 'tbl_product_format.brand=brand.id');
        $this->db->join('tbl_product_unit as unit', 'tbl_product_format.unit=unit.id');

        switch ($type) {
            case '0': // account
                $likeCriteria = $name != '' ? ("(tbl_product_format.barcode  LIKE '%" . $name . "%')") : '';
                break;
            case '1': // name
                $likeCriteria = $name != '' ? ("(tbl_product_format.name  LIKE '%" . $name . "%')") : '';
                break;
        }
        if ($likeCriteria != '') $this->db->where($likeCriteria);

        if ($kind!= '0') $this->db->where('tbl_product_format.type', $kind);
        if ($brand!= '0') $this->db->where('tbl_product_format.brand', $brand);

        $this->db->order_by('create_time', 'desc');

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function isDeletable($id){
        $result = $this->db->select('id')
            ->from('tbl_product')
            ->where('product_id', $id)
            ->get()->result();

        return count($result) == 0 ? true : false;
    }

    function isValidBarcode($barcode, $id){
        $result = $this->db->select('id')
            ->from('tbl_product_format')
            ->where('barcode', $barcode)
            ->where('id <> ', $id)
            ->get()->result();

        return count($result) == 0 ? true : false;
    }

    function isValidProductName($name, $id){
        $result = $this->db->select('id')
            ->from('tbl_product_format')
            ->where('name', $name)
            ->where('id <> ', $id)
            ->get()->result();

        return count($result) == 0 ? true : false;
    }
    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_format');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewProduct($product)
    {
        $result = $this->getItemByBarcode($product['barcode']);
        if(count($result)>0){
            $product['update_time'] = date("Y-m-d H:i:s");

            $this->updateProduct($product);
            return 0;
        }
        $product['create_time'] = date("Y-m-d H:i:s");
        $this->db->trans_start();
        $this->db->insert('tbl_product_format', $product);
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
        $this->db->where('barcode', $product['barcode']);
        $result = $this->db->update('tbl_product_format', $product);
        return $result;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function add($itemInfo)
    {
        if (isset($itemInfo['userId'])) {
            $item = $this->getItemById($itemInfo['userId']);
        } else {
            $item = $this->getItemByNumber($itemInfo['email']);
        }
        if (count($item) > 0) {
            $itemInfo['update_time'] = date("Y-m-d H:i:s");
            $insert_id = $this->update($itemInfo, $item->userId);
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
            $result = $this->db->delete('tbl_product_format');
            return $result;
        }
        return 0;
    }

}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */