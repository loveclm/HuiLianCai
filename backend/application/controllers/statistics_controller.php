<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class statistics_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('statistics_model');
        $this->load->model('product_util_model');
        $this->load->model('activity_model');
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
    }

    /**
     * This function is used to load the ship-man statistics list
     */
    function shipman_statistics(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '配送员统计';
            $this->global['pageName'] = 'shipman_statistics';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/shipman_statistics", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the saled product statistics list
     */
    function productsale(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '商品销量统计';
            $this->global['pageName'] = 'productsale';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/product_sale", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the shop paid statistics list
     */
    function shop_statistics(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '便利店统计';
            $this->global['pageName'] = 'shop_statistics';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/shop_statistics", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the provider price statistics list
     */
    function provider_statistics(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '供货商统计';
            $this->global['pageName'] = 'provider_statistics';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/provider_statistics", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the brand using statistics list
     */
    function brand_statistics(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '品牌统计';
            $this->global['pageName'] = 'brand_statistics';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/brand_statistics", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the product type statistics list
     */
    function type_statistics(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '分类统计';
            $this->global['pageName'] = 'type_statistics';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/type_statistics", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the sale-performance statistics list
     */
    function sale_performance(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '销售业绩';
            $this->global['pageName'] = 'sale_performance';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/sale_performance", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the provider's recommender statistics list
     */
    function recommend_provider(){

        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '供货商推荐人列表';
            $this->global['pageName'] = 'recommend_provider';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/recommend_provider", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the shop's recommender statistics list
     */
    function recommend_shop(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '终端便利店推荐人列表';
            $this->global['pageName'] = 'recommend_shop';
            $data['searchName'] = '';

            $this->loadViews("statistics_manage/recommend_shop", $this->global, $data, NULL);
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
                $searchData['provider_id'] = $this->user_model->getProviderId($this->global['login_id']);

                // get top list data in homepage
                switch ($id) {
                    case 1:
                        $header = array("日期", "账号", "配送员", "所属供货商", "供货商账号", "配送数量", "订单金额");
                        $cols = 7;
                        $pay_type = $_POST['pay_type'];
                        $contentList = $this->statistics_model->getShipItems($searchData, $pay_type);

                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;
                            $money = 0;
                            foreach ($contentList as $item){
                                $count += intval($item[5]);
                                $money += floatval($item[6]);
                            }
                            $footer = array("", "", "", "", "", $count, number_format($money, 2,'.',''));
                        }

                        break;
                    case 2:
                        $header = array("排名", "商品条码", "商品名称", "销量", "销售金额");
                        $cols = 5;
                        $contentList = $this->statistics_model->getProductItems($searchData);

                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;
                            $money = 0;
                            foreach ($contentList as $item){
                                $count += intval($item[3]);
                                $money += floatval($item[4]);
                            }
                            $footer = array("", "", "", $count, number_format($money, 2,'.',''));
                        }

                        break;
                    case 3:
                        $header = array("日期", "终端便利店账号", "终端便利店", "订单数量", "订单金额");
                        $cols = 5;
                        $contentList = $this->statistics_model->getShopItems($searchData);

                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;
                            $money = 0;
                            foreach ($contentList as $item){
                                $count += intval($item[3]);
                                $money += floatval($item[4]);
                            }
                            $footer = array("", "", "", $count, number_format($money, 2,'.',''));
                        }

                        break;
                    case 4:
                        $header = array("月份", "供货商账号", "供货商名称", "销量", "销售金额", "平台佣金", "供货商实际收入");
                        $cols = 7;
                        $contentList = $this->statistics_model->getProviderItems($searchData);

                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;  $pintai_cost = 0;
                            $money = 0;  $total_cost = 0;
                            foreach ($contentList as $item){
                                $count += intval($item[3]);
                                $money += floatval($item[4]);
                                $pintai_cost += floatval($item[5]);
                                $total_cost += floatval($item[6]);
                            }
                            $footer = array("", "", "", $count, number_format($money, 2,'.',''),
                                                                number_format($pintai_cost, 2,'.',''),
                                                                number_format($total_cost, 2,'.',''));
                        }

                        break;
                    case 5:
                        $header = array("排名", "LOGO", "分类", "销量", "销售金额");
                        $cols = 5;

                        $contentList = $this->statistics_model->getBrandItems($searchData);
                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;
                            $money = 0;
                            foreach ($contentList as $item){
                                $count += intval($item[3]);
                                $money += floatval($item[4]);
                            }
                            $footer = array("", "", "", $count, number_format($money, 2,'.',''));
                        }

                        break;
                    case 6:
                        $header = array("排列", "分类", "销量", "销售金额");
                        $cols = 4;

                        $contentList = $this->statistics_model->getTypeItems($searchData);
                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;
                            $money = 0;
                            foreach ($contentList as $item){
                                $count += intval($item[2]);
                                $money += floatval($item[3]);
                            }
                            $footer = array("", "", $count, number_format($money, 2,'.',''));
                        }

                        break;
                    case 7:
                        $header = array("月份", "销量", "销售金额", "平台佣金", "实际收入");
                        $cols = 5;
                        $contentList = $this->statistics_model->getSalePerformanceItems($searchData);

                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;  $pintai_cost = 0;
                            $money = 0;  $total_cost = 0;
                            foreach ($contentList as $item){
                                $count += intval($item[1]);
                                $money += floatval($item[2]);
                                $pintai_cost += floatval($item[3]);
                                $total_cost += floatval($item[4]);
                            }
                            $footer = array("", $count, number_format($money, 2,'.',''),
                                number_format($pintai_cost, 2,'.',''),
                                number_format($total_cost, 2,'.',''));
                        }

                        break;
                    case 8:
                        $header = array("业务员账号", "推荐业务员", "推荐人数", "");
                        $cols = 4;
                        $contentList = $this->statistics_model->getRecommendproviderInfos($searchData);

                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;
                            foreach ($contentList as $item){
                                $count += floatval($item[2]);
                            }
                            $footer = array("", "汇总", $count, "");
                        }

                        break;
                    case 9:
                        $header = array("推荐人手机号", "推荐人数", "");
                        $cols = 4;

                        $contentList = $this->statistics_model->getRecommendshopInfos($searchData);

                        if(count($contentList) == 0){
                            $footer = "没有数据.";
                        }else{
                            $count = 0;
                            foreach ($contentList as $item){
                                $count += floatval($item[1]);
                            }
                            $footer = array("汇总", $count, "");
                        }

                        break;
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

    function output_content($allLists, $id)
    {
        $output_html = '';
        if (!isset($allLists) || count($allLists) == 0) return '';
        // make list id: 1-main menu
        for($i = 0; $i < count($allLists); $i++){
            $items = $allLists[$i];

            $output_html .= '<tr>';

            foreach ($items as $item)
                $output_html .= '<td>' . $item . '</td>';

            $output_html .= '</tr>';
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
        if( count($footer) == 1) {
            $output_html .= '<td colspan="' . $cols . '">' . $footer . '</td>';
        }else{
            foreach ($footer as $item)
                $output_html .= '<td>' . $item . '</td>';
        }
        $output_html .= '</tr>';
        return $output_html;
    }

    function showProviders($id){
        $this->global['pageTitle'] = '供货商列表';
        $this->global['pageName'] = 'provider';
        $userinfo = $this->user_model->getUserInfo($id);

        $data['searchType'] = '3';
        $data['searchName'] = $userinfo->username;
        $data['address'] = '';
        $data['searchStatus'] = '0';

        $this->loadViews("user_manage/provider", $this->global, $data, NULL);
    }

    function showShops($id){
        $this->global['pageTitle'] = '终端便利店列表';
        $this->global['pageName'] = 'shop';

        $data['searchType'] = '3';
        $data['searchName'] = ($id == '') ? '' : $id;
        $data['address'] = '';
        $data['searchStatus'] = '0';
        $data['searchShoptype']='0';
        $data['searchAuth'] = '0';

        $this->loadViews("user_manage/shop", $this->global, $data, NULL);
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

            $item = $this->getItemInfo($Id);
            $userinfo = $this->user_model->getProviderInfos($item->provider_id);
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

    function allBrandInfo(){
        $types = $this->product_util_model->getProductBrandList();
        if(count($types) == 0) return NULL;

        echo json_encode($types);
    }

    function allTypeInfo(){
        $brands = $this->product_util_model->getProductTypeList();
        if(count($brands) == 0) return NULL;

        echo json_encode($brands);
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

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file shop.php */
/* Location: .application/controllers/shop.php */

?>