<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class product_util_model extends CI_Model
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

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    function getProductTypeList(){
        $this->db->select('*');
        $this->db->from('tbl_product_type');
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) $result = NULL;
        return $result;
    }

    // this functio is used to get type
    function productTypeList(){
        $this->db->select('id, type as name');
        $this->db->from('tbl_product_type');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
    // this function is used to get type list of recommended activities
    function getRecommendedBrandList(){
        $this->db->select('tbl_product_brand.*');
        $this->db->from('tbl_activity');
        $this->db->join('tbl_product', 'tbl_product.id in( tbl_activity.product_id)');
        $this->db->join('tbl_product_format', 'tbl_product.product_id=tbl_product_format.id');
        $this->db->join('tbl_product_brand', 'tbl_product_brand.id=tbl_product_format.brand');
        $this->db->where('tbl_activity.recommend_status', 1);
        $this->db->where('tbl_activity.status', 2);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function getTypeNameById($id){
        $this->db->select('type');
        $this->db->from('tbl_product_type');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return '';
        return $result[0]->type;
    }

    function getProductBrandListByName($name =''){
        $this->db->select('*');
        $this->db->from('tbl_product_brand');
        if($name != "")
            $this->db->where('name like "%'.$name.'%"');
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) $result = NULL;
        return $result;
    }

    function getProductBrandList($type = '0'){
        $this->db->select('*');
        $this->db->from('tbl_product_brand');
        if( $type != '0')
            $this->db->where('type', $type);
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) $result = NULL;
        return $result;
    }

    function getProductUnitList(){
        $this->db->select('*');
        $this->db->from('tbl_product_unit');
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) $result = NULL;
        return $result;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getBrandItemById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_brand');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    function getBrandNameById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product_brand');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return '';
        return $result[0]->name;
    }

    function getUnitNameById($id){
        $this->db->select('*');
        $this->db->from('tbl_product_unit');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) return '';
        return $result[0]->name;
    }
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewBrand($brand)
    {

        if(isset($brand['id'])){
            //$brand['update_time'] = date("Y-m-d H:i:s");
            $this->updateBrand($brand);
            return 0;
        }
        //$brand['create_time'] = date("Y-m-d H:i:s");
        $this->db->trans_start();
        $this->db->insert('tbl_product_brand', $brand);
        $insert_id = $this->db->insert_id();
        $result = $this->db->trans_complete();
        return true;
    }

    function updateBrand($brand){
        $this->db->where('id', $brand['id']);
        $result = $this->db->update('tbl_product_brand', $brand);
        return $result;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function add($itemInfo)
    {
        if($itemInfo['id'] == 0){
            // insert data
            $this->db->trans_start();
            switch ($itemInfo['type']){
                case 1:
                    $item = array('type'=> $itemInfo['name']);
                    $this->db->insert('tbl_product_type', $item);
                    break;
                case 2:
                    $item = array('name'=> $itemInfo['name']);
                    $this->db->insert('tbl_product_unit', $item);
                    break;
            }
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();

            return $insert_id;
        }else{
            //update data
            $this->db->where('id', $itemInfo['id']);
            switch ($itemInfo['type']){
                case 1:
                    $item = array('type'=> $itemInfo['name']);
                    $result = $this->db->update('tbl_product_type', $item);
                    break;
                case 2:
                    $item = array('name'=> $itemInfo['name']);
                    $result = $this->db->update('tbl_product_unit', $item);
                    break;
            }
            return $result;
        }
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
        $this->db->where('id', $itemInfo['id']);
        switch ($itemInfo['type']){
            case 1:
                $result = $this->db->delete('tbl_product_type');
                break;
            case 2:
                $result = $this->db->delete('tbl_product_unit');
                break;
            case 3:
                $result = $this->db->delete('tbl_product_brand');
                break;
            default:
                return 0;
        }
        return $result;
    }

    function isDeletable($id, $type){
        $this->db->select('id')
            ->from('tbl_product_format');

        switch ($type){
            case 1:
                $this->db->where('type', $id);
                break;
            case 3:
                $this->db->where('brand', $id);
                break;
            case 2:
                $this->db->where('unit', $id);
                break;
            default:
                return 0;
        }

        $result = $this->db->limit(1)->get();

        return count($result) ? false : true;
    }
}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */