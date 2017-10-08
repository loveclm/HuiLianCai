<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class carousel_model extends CI_Model
{

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getItems($data = '')
    {
        $name = 'all';
        $this->db->select('*');
        $this->db->from('tbl_carousel');
        if ($name != 'all') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('status', 'desc');
        $this->db->order_by('sort', 'asc');
        $this->db->order_by('create_date', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result = NULL;
        return $result;
    }

    /**
     * This function is used to get first 5 carousel images
     * @return array $result : This is result
     */
    function getCarouselItems()
    {
        $this->db->select('image as imgData, activity as linkData, type as linkType');
        $this->db->from('tbl_carousel');
        $this->db->where('status', 1);
        $this->db->order_by('sort', 'asc');
        $this->db->order_by('create_date', 'desc');
        $this->db->limit(5);
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
        $this->db->from('tbl_carousel');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getItemByImage($image)
    {
        $this->db->select('*');
        $this->db->from('tbl_carousel');
        $this->db->where('image', $image);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result) == 0) $result[0] = NULL;
        return $result[0];
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getDeployedCount()
    {
        $this->db->select('*');
        $this->db->from('tbl_carousel');
        $this->db->where('status', 1);
        $qresult = $this->db->count_all_results();
        return $qresult;
    }

    /**
     * This function is used to add new shop to system
     * @return number $insert_id : This is last inserted id
     */
    function add($itemInfo)
    {
        $itemInfo['create_date'] = date("Y-m-d H:i:s");
        if (isset($itemInfo['id'])) {
            $item = $this->getItemById($itemInfo['id']);
            $count = $this->getDeployedCount();
            if($itemInfo['status']==1 && $count>=5) return 0;
        }else {
            if ($itemInfo['sort'] == 0) $itemInfo['sort'] = 1000;
            $item = $this->getItemByImage($itemInfo['image']);
        }
        if (count($item) > 0) {
            $insert_id = $this->update($itemInfo, $item->id);
        } else {
            $this->db->trans_start();
            $this->db->insert('tbl_carousel', $itemInfo);
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
        $result = $this->db->update('tbl_carousel', $itemInfo);
        return $result;
    }

    /**
     * This function is used to delete the shop information
     * @param number $shopId : This is shop id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($itemInfo)
    {

        if (isset($itemInfo['id'])) {
            $item = $this->getItemById($itemInfo['id']);
        }else {
            if ($itemInfo['sort'] == 0) $itemInfo['sort'] = 1000;
            $item = $this->getItemByImage($itemInfo['image']);
        }
        if (count($item) > 0) {
            $this->db->where('id', $itemInfo['id']);
            $result = $this->db->delete('tbl_carousel');
            return $result;
        }
        return 0;
    }

}

/* End of file shop_model.php */
/* Location: ./application/models/shop_model.php */