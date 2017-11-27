<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class carousel_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('carousel_model');
        $this->load->model('activity_model');
        $this->load->model('provider_model');
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->list_view();
    }

    /**
     * This function is used to load the user list
     */
    function list_view()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $data = '';
            $this->global['pageTitle'] = '轮播图列表';
            $this->global['pageName'] = 'carousel';

            $this->loadViews("carousel", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to get TOP10 Lists
     */
    function item_listing()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            // get top10 datas
            // id: 1-top1, 2-top2, 3-top3, 4-top4, 5-top5
            $ret = array(
                'content' => '',
                'status' => 'fail'
            );
            if (!empty($_POST)) {
                $id = $_POST['id'];
                // get top list data in homepage
                switch ($id) {
                    case 1:
                        $header = array("序号", "轮播图片", "类型", "单品活动/餐装活动/区域总代理", "排序", "新增日期", "操作",);
                        $cols = 7;
                        $contentList = $this->carousel_model->getItems();
                        $footer = (count($contentList) == 0||$contentList=='') ? $footer = "没有轮播图项目." : '';
                        break; // get top1
                }

                // end get
                $ret['header'] = $this->output_header($header);
                $ret['content'] = $this->output_content($contentList, $id);
                $ret['footer'] = $this->output_footer($footer, $cols);
                $ret['status'] = 'success';
            }
            echo json_encode($ret);
        }
    }

    function getActivities(){
        $ret = array(
            'content' => '',
            'status' => 'success'
        );
        if(!empty($_POST)){
            $data = $_POST['data'];
            $activities = $this->activity_model->getItems($data);
            if($activities == NULL) $activities = array();
            $ret['content'] = $activities;
        }else
            $ret['content'] = array();

        echo json_encode($ret);
    }
    function getProviders(){
        $ret = array(
            'content' => '',
            'status' => 'success'
        );
        if(!empty($_POST)){
            $data = $_POST['data'];
            $providers = $this->provider_model->getItems($data);
            if($providers == NULL) $providers = array();
            $ret['content'] = $providers;
        }else
            $ret['content'] = array();

        echo json_encode($ret);
    }
    /**
     * This function used to get activity name( provider or single activity, multiple activity) from carousel item information
     */
    function getActivityNameFromItem($item){
        $activity_name = '';
        switch ($item->type){
            case "2":   //单品活动名称
            case "3":   //餐装活动名称
                $activity = $this->activity_model->getItemById($item->activity);
                $activity_name = $activity->activity_name;
                break;
            case "4":   //区域总代理名称
                $providerinfo = $this->user_model->getUserInfoByid($item->activity);
                $activity_name = $providerinfo->username;
                break;
        }

        return $activity_name;
    }

    /**
     * This function used to make list view
     */
    function output_content($allLists, $id)
    {
        $output_html = '';
        if (!isset($allLists) || count($allLists) == 0) return '';
        // ????? make list id: 1-top1, 2-top2, 3-top3, 4-top4, 5-top5
        $i = 0;
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $imageName = $item->image == '' ? 'assets/image/logo.png' : $item->image;
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . ($i + 1) . '</td>';
                    $output_html .= '<td><img src="' . base_url() . $imageName .
                        '" style="width:150px;height:80px;border:2px solid lightgray;"/></td>';
                    $output_html .= '<td>';
                    $output_html .= (($item->type == 1) ? '广告' : (($item->type == 2) ? '单品活动' :
                        (($item->type == 3) ? '餐装活动' : (($item->type == 4) ? '区域总代理' : ''))));
                    $output_html .= '</td>';
                    $output_html .= '<td>' . $this->getActivityNameFromItem($item) . '</td>';                             // exchange id to activity name
                    $output_html .= '<td>' . ($item->sort != 1000 ? $item->sort : '') . '</td>';
                    $output_html .= '<td>' . $item->create_date . '</td>';
                    $output_html .= '<td>';
                    if ($item->status == '0') {
                        $output_html .= '<a href="#" onclick="deployConfirm(' . $item->id . ',1)">上架 &nbsp;&nbsp;</a>';
                        $output_html .= '<a href="#" onclick="deleteConfirm(' . $item->id . ')">删除 &nbsp;&nbsp;</a>';
                    } else {
                        $output_html .= '<a href="#" onclick="deployConfirm(' . $item->id . ',0)">下架 &nbsp;&nbsp;</a>';
                    }
                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                    $i++;
                }
                break;
            case 2:
                break;
        }

        return $output_html;
    }

     function output_header($allLists)
    {
        $output_html = '';
        if (count($allLists) == 0) return '';
        $output_html .= '<tr>';
        foreach ($allLists as $item) {
            $output_html .= '<th>' . $item . '</th>';
        }
        $output_html .= '</tr>';
        return $output_html;
    }

    /**
     * This function used to make list view
     */

    function output_footer($footer, $cols)
    {
        $output_html = '';
        $output_html .= '<tr>';
        $output_html .= '<td colspan="' . $cols . '">' . $footer . '</td>';
        $output_html .= '</tr>';
        return $output_html;
    }

    /**
     * This function is used to load the user list
     */
    function additem()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '新增轮播图';
            $this->global['pageName'] = 'carousel_add';
            $data = '';

            $this->loadViews("carousel_add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function addItem2DB()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $result = $_POST['itemInfo'];
            echo $this->carousel_model->add($result);
        }
    }

    /**
     * This function is used to load the user list
     */
    function deleteItemFromDB()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $result = $_POST['itemInfo'];
            echo $this->carousel_model->delete($result);
        }
    }

    /**
     * This function is used to load the user list
     */
    function edititem($fname)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑轮播图';
            $this->global['pageName'] = 'carousel_edit';
            $data['imagefile'] = base_url() .  $fname;

            $this->loadViews("carousel_add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the user list
     */
    function upload_img()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '上传图片';
            $this->global['pageName'] = 'carousel_upload';
            $data['ret_Url'] = 'carousel_edit';

            $this->loadViews("uploading_img", $this->global, $data, NULL);
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