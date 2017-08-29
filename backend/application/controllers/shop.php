<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class shop extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
        $this->load->model('shop_model');
        $this->load->model('order_model');
        $this->load->model('auth_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->shop();
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

            $this->loadViews("area-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function used to load the first screen of the shop
     */
    public function shop()
    {
        $this->global['pageTitle'] = '商家管理';
        $this->global['shopList'] =$this->shop_model->getShops();
        $this->global['areaList'] =$this->area_model->getAreas();
        $this->global['courseList'] = $this->area_model->getCourses();
        $this->global['searchName'] = '';
        $this->global['searchAddress'] = '';
        $this->global['searchStatus'] = 0;
        $this->loadViews("shop", $this->global, NULL , NULL);
    }


    /**
     * This function used to load the first screen of the shop
     */
    public function qr()
    {
        $this->global['pageTitle'] = '二维码管理';

        $qrList = array();
        $qrDBList = $this->shop_model->getQR();

        foreach ($qrDBList as $list){
            $areaid = $list->targetid;
            $areaInfo = $this->area_model->getAreaById($areaid);

            $info = array('target'=>$areaInfo->name,
                'shop'=>$list->name, 'type' => $list->type == '1' ? '景区':'旅游线路',
                'time'=>$list->created_time,
                'id'=>$list->id);
            array_push($qrList, $info);
        }

        $this->global['qrList'] = $qrList;

        $this->global['searchName'] = '';
        $this->global['searchStatus'] = 0;
        $this->loadViews("qrmanage", $this->global, NULL , NULL);
    }


    /**
     * This function is used to load the add Course form
     */
    function addShop()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = '新增商家';
            $this->global['areaList'] =$this->area_model->getAreas();
            $this->loadViews("shop-add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function shoplisting($name, $status)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = '商家管理';
            $this->global['shopList'] =$this->shop_model->getShops($name, '', $status);
            $this->global['areaList'] =$this->area_model->getAreas();
            $this->global['courseList'] = $this->area_model->getCourses();
            $this->global['searchName'] = $name;
            $this->global['searchAddress'] = '';
            $this->global['searchStatus'] = $status;
            $this->loadViews("shop", $this->global, NULL , NULL);
        }
    }


    /**
     * This function is used to load the user list
     */
    function qrlisting($name, $status)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = '二维码管理';

            $qrList = array();
            $qrDBList = $this->shop_model->getQR($name, $status);

            foreach ($qrDBList as $list){
                $areaid = $list->targetid;
                $areaInfo = $this->area_model->getAreaById($areaid);

                $info = array('target'=>$areaInfo->name,
                    'shop'=>$list->name, 'type' => $list->type == '1' ? '景区':'旅游线路',
                    'time'=>$list->created_time,
                    'id'=>$list->id);
                array_push($qrList, $info);
            }

            $this->global['qrList'] = $qrList;

            $this->global['searchName'] = $name;
            $this->global['searchStatus'] = $status;
            $this->loadViews("qrmanage", $this->global, NULL , NULL);
        }
    }

    /**
     * This function is used to load the edit form
     */
    function editshop($id)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = '编辑商家';
            $this->global['shop'] = $this->shop_model->getShopById($id);

            $this->loadViews("shop-add", $this->global, NULL, NULL);
        }
    }


    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file shop.php */
/* Location: .application/controllers/shop.php */

?>