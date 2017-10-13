<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class news_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_news');
        $this->db->where('tbl_news.receiver', $id);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function getNewItems($id){
        $this->db->select('*');
        $this->db->from('tbl_news');
        $this->db->where('receiver', $id);
        $this->db->where('status', 0);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_news');
        $this->db->where('tbl_news.id', $id);

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
            $this->db->trans_start();
            $item['create_time'] = date("Y-m-d H:i:s");
            $this->db->insert('tbl_news', $item);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
        }else{
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
        $result = $this->db->update('tbl_news', $item);
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
            $result = $this->db->delete('tbl_news');
            return $result;
        }

        return 0;
    }

    function delete_messages($ids){
        if (isset($ids) && $ids != '0') {
            $this->db->where('id in('. $ids. ')');
            $result = $this->db->delete('tbl_news');
            return $result;
        }
        return 0;
    }
}

/* End of file activity_model.php */
/* Location: ./application/models/activity_model.php */