<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class area_model extends CI_Model
{
    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getAreas($name = '', $address = 'all', $status = 0)
    {
        $this->db->select('*');
        $this->db->from('tourist_area');
        if ($name != 'all') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        if ($status == 1) {
            $this->db->where('status', 0);
        } elseif ($status == 2) {
            $this->db->where('status', 1);
        }
        if ($address != 'all') {
            $likeCriteria = "(address  LIKE '%" . $address . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('type', 1);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function findAreas($name = '')
    {
        $this->db->select('*');
        $this->db->from('tourist_area');
        if ($name != '') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('type', 1);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function get_all()
    {
        $this->db->select('*');
        $this->db->from('tourist_area');

        $this->db->where('type', 1);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }


    /**
     * This function is used to get all Tourist Area
     * @return array $result : This is result
     */
    function getCourses($name = '', $status = 0)
    {
        $this->db->select('*');
        $this->db->from('tourist_area');
        if ($name != 'all') {
            $likeCriteria = "(name  LIKE '%" . $name . "%')";
            $this->db->where($likeCriteria);
        }
        if ($status == 1) {
            $this->db->where('status', 0);
        } elseif ($status == 2) {
            $this->db->where('status', 1);
        }
        $this->db->where('type', 2);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getAreaById($id)
    {
        $this->db->select('*');
        $this->db->from('tourist_area');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result)) return $result[0];
        return array();
    }

    /**
     * This function is used to get Tourist Area by id
     * @return array $result : This is result
     */
    function getAreaByAuthId($authid)
    {
        $this->db->select('*');
        $this->db->from('tbl_authcode as au');
        $this->db->join('tourist_area as ta', 'au.targetid = ta.id');
        $this->db->where('au.id', $authid);
        $query = $this->db->get();
        $result = $query->result();
        if (count($result)) return $result[0];
        return array();
    }

    /**
     * This function is used to add new area to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewArea($areainfo)
    {
        $this->db->trans_start();
        $this->db->insert('tourist_area', $areainfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function is used to add new area to system
     * @return number $insert_id : This is last inserted id
     */
    function addAttraction($pointInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tourist_area', $pointInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function is used to update the area information
     * @param array $areaInfo : This is users updated information
     * @param number $areaId : This is area id
     */
    function update($areaInfo, $areaId)
    {
        $this->db->where('id', $areaId);
        $this->db->update('tourist_area', $areaInfo);
        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($areaId)
    {
        $this->db->where('id', $areaId);
        $this->db->delete('tourist_area');
        return TRUE;
    }
}


/* End of file area_model.php */
/* Location: .application/models/area_model.php */

