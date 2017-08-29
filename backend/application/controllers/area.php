<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Area (AreaController)
 * Area Class to control all tourist area and course related operations.
 * @author : thejinhu
 * @version : 1.0
 * @since : 8 August 2017
 */
class area extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = '景区管理';
        $this->global['areaList'] =$this->area_model->getAreas();
        $this->global['searchName'] = '';
        $this->global['searchAddress'] = '';
        $this->global['searchStatus'] = 0;
        $this->loadViews("area", $this->global, NULL , NULL);
    }

    /**
     * This function is used to load the user list
     */
    function listing($name, $address, $status)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = '景区管理';
            $this->global['areaList'] =$this->area_model->getAreas($name, $address, $status);
            $this->global['searchName'] = $name;
            $this->global['searchAddress'] = $address;
            $this->global['searchStatus'] = $status;

            $this->loadViews("area", $this->global, NULL , NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = '新增景区';
            $this->global['isEdit']='0';
            $this->loadViews("area-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function edit($id)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {

            $this->global['pageTitle'] = '编辑景区';
            $this->global['area'] = $this->area_model->getAreaById($id);
            $this->global['isEdit']='1';

            $this->loadViews("area-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function used to load the first screen of the course
     */
    public function course()
    {
        $this->global['pageTitle'] = '旅游线路管理';
        $this->global['courseList'] = $this->area_model->getCourses();
        $this->global['searchName'] = '';
        $this->global['searchStatus'] = 0;
        $this->loadViews("course", $this->global, NULL , NULL);
    }

    /**
     * This function is used to load the add Course form
     */
    function addCourse()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {

            $this->global['pageTitle'] = '新增旅游线路';
            $this->global['areaList'] =$this->area_model->getAreas();
            $this->loadViews("course-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function courselisting($name, $status)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = '旅游线路管理';
            $this->global['courseList'] =$this->area_model->getCourses($name, $status);
            $this->global['searchName'] = $name;
            $this->global['searchStatus'] = $status;

            $this->loadViews("course", $this->global, NULL , NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function editcourse($id)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {

            $this->global['pageTitle'] = '编辑旅游线路';
            $this->global['areaList'] =$this->area_model->getAreas();
            $this->global['course'] = $this->area_model->getAreaById($id);

            $this->loadViews("course-add", $this->global, NULL, NULL);
        }
    }


    function pageNotFound()
    {
        $this->global['pageTitle'] = '404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file area.php */
/* Location: .application/controllers/area.php */


?>