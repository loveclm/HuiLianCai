<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class favorite_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_shop_favorite');
        $this->db->where('tbl_shop_favorite.shop', $id);

        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_shop_favorite');
        $this->db->where('tbl_shop_favorite.id', $id);

        $query = $this->db->get();
        $result = $query->result();
        if(count($result) == 0) return NULL;

        return $result[0];
    }

    /**
     * This function is used to add new item to system
     * @return number $insert_id : This is last inserted id
     */
    function add($item)
    {
        if( !isset($item['id'])){
            $this->db->select('id');
            $this->db->from('tbl_shop_favorite');
            $this->db->where('shop', $item['shop']);
            $this->db->where('type', $item['type']);
            $this->db->where('favorite_id', $item['favorite_id']);
            $query = $this->db->get();
            $result = $query->result();

            if(count($result) == 0) {
                $this->db->trans_start();
                $item['add_time'] = date("Y-m-d H:i:s");
                $this->db->insert('tbl_shop_favorite', $item);
                $insert_id = $this->db->insert_id();
                $this->db->trans_complete();
            }else{
                return $result[0]->id;
            }
        }else{
            $insert_id = $this->db->update($item, $item['id']);
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
        $result = $this->db->update('tbl_shop_favorite', $item);
        return $result;
    }

    /**
     * This function is used to delete the shop information
     * @param number $shopId : This is shop id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($id)
    {
        if (isset($id)) {
            $this->db->where('id', $id);
            $result = $this->db->delete('tbl_shop_favorite');
            return $result;
        }

        return 0;
    }

}

/* End of file activity_model.php */
/* Location: ./application/models/activity_model.php */