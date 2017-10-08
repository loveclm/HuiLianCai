<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class order_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('activity_model');
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
            $this->global['pageTitle'] = '订单管理';
            $this->global['pageName'] = 'order';

            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchStatus'] = '0';
            $data['searchMethod'] = '0';

            $this->loadViews("order_manage/order", $this->global, $data, NULL);
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
            $ret = array(
                'content' => '',
                'status' => 'fail'
            );
            if (!empty($_POST)) {
                $id = $_POST['id'];
                $searchData = $_POST['searchData'];
                $searchData['provider_id'] = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';

                // get top list data in homepage
                switch ($id) {
                    case 1:
                        if($this->isTicketter() == FALSE){
                            $header = array("序号", "订单号", "终端便利店", "收货人", "联系电话", "付款方式", "订单状态", "配送员", "提交时间", "操作");
                            $cols = 10;
                        }else if($this->isAdmin() == FALSE){
                            $header = array("序号", "订单号", "终端便利店", "收货人", "联系电话", "付款方式", "订单状态", "所属供货商", "提交时间", "操作");
                            $cols = 10;
                        }

                        $contentList = $this->order_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有订单." : '';
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

    function product_list(){
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        $ids = $_POST['ids'];
        $header = array("商品条码", "封面", "活动名称", "原价（元）", "拼团价（元）", "采购数量");
        $cols = 6;

        $provider_id = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';
        $contentList = $this->order_model->getProductsFromIds($ids, $provider_id);

        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有商品." : '';

        $ret['header'] = $this->output_header($header);
        $ret['content'] = $contentList;
        $ret['footer'] = $this->output_footer($footer, $cols);
        $ret['status'] = 'success';

        echo json_encode($ret);
    }

    function all_products(){
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        $header = array("商品条码", "封面", "活动名称", "库存量", "原价", "操作");
        $cols = 6;

        $provider_id = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';
        $contentList = $this->order_model->getProducts( $provider_id);

        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有商品." : '';

        $ret['header'] = $this->output_header($header);
        $ret['content'] = $contentList;
        $ret['footer'] = $this->output_footer($footer, $cols);
        $ret['status'] = 'success';

        echo json_encode($ret);
    }

    function output_content($allLists, $id)
    {
        $output_html = '';
        if (!isset($allLists) || count($allLists) == 0) return '';
        // make list id: 1-main menu
        $i = 0;
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $i++;
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $i . '</td>';
                    $output_html .= '<td>' . $item->id . '</td>';
                    $output_html .= '<td>' . $item->shop_name . '</td>';
                    $output_html .= '<td>' . $item->contact_name . '</td>';
                    $output_html .= '<td>' . $item->contact_phone . '</td>';

                    switch ($item->pay_method){
                        case 1:
                            $output_html .= '<td>线上支付</td>';
                            break;
                        case 2:
                            $output_html .= '<td>货到付款</td>';
                            break;
                    }

                    switch ($item->status) {
                        case 1:
                            $output_html .= '<td>待付款</td>';
                            break;
                        case 2:
                            $output_html .= '<td>待成团</td>';
                            break;
                        case 3:
                            $output_html .= '<td>待发货</td>';
                            break;
                        case 4:
                            $output_html .= '<td>交易成功</td>';
                            break;
                        case 5:
                            $output_html .= '<td>交易关闭</td>';
                            break;
                        case 6:
                            $output_html .= '<td>已退款</td>';
                            break;
                    }

                    if($this->isTicketter() == FALSE ){
                        $output_html .='<td>' . $this->user_model->getUserName($item->ship_man) . '</td>';
                    }else if( $this->isAdmin() == FALSE){
                        $output_html .='<td>' . $this->user_model->getUserName($item->provider) . '</td>';
                    }

                    $output_html .= '<td>' . $item->create_time . '</td>';

                    $output_html .= '<td>';
                    $output_html .= '<a href="' . base_url() . 'order_show/' . $item->id . '">查看 &nbsp;&nbsp;</a>';
                    if($this->isTicketter() == FALSE) {
                        if($item->status == '3' && $item->ship_status == '0')
                            $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->id . '\', 2, 4)">配送 &nbsp;&nbsp;</a>';
                    }

                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                    $i++;
                }
                break;
        }

        return $output_html;
    }

    /**
     * This function used to make list view
     */

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
     * This function is used to show the user information
     */
    function showitem($Id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '订单详情';
            $this->global['pageName'] = 'order_detail';

            $data['empty'] = NULL;
            $provider_id = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';
            $item = $this->getItemInfo($Id);

            $userinfo = $this->user_model->getProviderInfos($this->user_model->getIdByUserId($item->provider_id));
            $item->provider_name = $userinfo->username;
            $item->provider_userid = $userinfo->userid;
            $this->global['model'] = $item;
            $this->loadViews("order_manage/order_detail", $this->global, $data, NULL);
        }
    }

    // this function is used to get all the information of the order
    function getItemInfo($id){
        $item = $this->order_model->getItemById($id);
        if($item == NULL) return $item;

        // get provider information
        $providerinfo = $this->user_model->getUserInfoByid($item->provider);
        $item->provider_id = $providerinfo->userid;
        $item->provider_name = $providerinfo->username;

        // get ship man information
        $shipman_info = $this->user_model->getUserInfoByid($item->ship_man);
        $item->shipman_name = isset($shipman_info->username) ? $shipman_info->username : '';
        $item->shipman_phone = isset($shipman_info->contact_phone) ? $shipman_info->contact_phone : '';

        // get activity information
        $activities = array();
        $ids = explode(",", $item->activity_ids);
        $cnts = explode(",", $item->activity_cnts);
        for( $i = 0; $i < count($ids); $i++){
            $activity = $this->activity_model->getItemById($ids[$i]);
            $activity->products = $this->activity_model->getProductsFromIds($activity->product_id, $activity->provider_id);
            $activity->cnt = $cnts[$i];
            array_push($activities, $activity);
        }
        $item->activities = $activities;
        return $item;
    }

    /**
     * This function is used to load the user list
     */
    function addItem2DB($data = null)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $result = $_POST['itemInfo'];
            echo $this->order_model->add($result);
        }
    }

    function setShipping(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $ret = array(
                'status' => false,
                'data' => ''
            );

            $result = $_POST['itemInfo'];
            $order = $this->order_model->getItemById($result['id']);
            $shop_info = $this->user_model->getUserInfoByid($order->shop_id);
            $ship_id = $shop_info->ship_man;
            if($ship_id == '0'){
                $ret['data'] = '配送员不设定。 请在终端便利店页面选择配送员。';
                echo json_encode($ret);
                return;
            }
            $result['ship_man'] = $ship_id;
            $this->order_model->add($result);

            // add shipping record to shipping table
            $shipping_info = array(
                'ship_id' => $ship_id,
                'provider' => $order->provider,
                'order_id' => $order->id,
                'money' => number_format(floatval($order->pay_cost) - floatval($order->refund_cost),2 , '.', '')
            );
            $this->shipping_model->addShippingItem($shipping_info);

            $ret['status'] = true;
            $ret['data'] = 'shipping was set successfully.';

            echo json_encode($ret);
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