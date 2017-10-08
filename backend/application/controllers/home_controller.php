<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class home_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('shop_model');
        $this->load->model('order_model');
        $this->load->model('statistics_model');
        $this->load->model('product_util_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->home_view();
    }

    /**
     * This function is used to load the user list
     */
    function home_view()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '首页';

            $data['tab_headers'] = array(
                "月供货商销售排行",
                "月终端商便利店消费排行",
                "月分类销售排行",
                "月品牌销售排行",
                "单品销售排名",
            );

            if($this->global['shop_manager_number'] == '') {   // or $this->isTicketter()
                // calculate the number of provider
                // calculate the number of shop
                $this->global['basic_data'] = array(
                    'provider_cnt' => $this->shop_model->getUserCount(2),
                    'shop_cnt' => $this->shop_model->getUserCount(3)
                );
            }else{
                // provider's information
                $provider_info = $this->user_model->getProviderInfos($this->user_model->getIdByUserId($this->userId));

                $more_data = json_decode($provider_info->more_data);
                $logo = json_decode($more_data->logo);

                $this->global['basic_data'] = array(
                    'logo' => $logo[1],
                    'provider_id' => $provider_info->userid,
                    'provider_name' => $provider_info->username,
                    'contact_name' => $provider_info->contact_name,
                    'contact_phone' => $provider_info->contact_phone,
                    'address' => $provider_info->address
                );
            }

            // yesterday's the number and money of sales
            $sale_data = array();
            $yesterday_sale = $this->order_model->getYesterdayOrderStatus();
            array_push($sale_data, $yesterday_sale);
            // current month's the number and money of sales
            $month_sale = $this->order_model->getMonthOrderStatus();
            array_push($sale_data, $month_sale);
            $this->global['sale_data'] = $sale_data;

            $this->loadViews("home", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to get TOP10 Lists
     */
    function home_listing()
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
                $provider_id = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';
                // ????? get top list data in homepage
                switch ($id) {
                    case 1:
                        $header = array("排名", "供货商账号", "供货商名称", "销量", "销售金额",);
                        $cols = 5;
                        $contentList = $this->statistics_model->getTopProviderItems();
                        $footer = (count($contentList) == 0) ? '没有数据' : "掌握30日内销售最多的供货商";
                        break; // get top1
                    case 2:
                        $header = array("排名", "终端便利店账号", "终端便利店", "销量", "销售金额",);
                        $cols = 5;
                        $contentList = $this->statistics_model->getTopShopItems($provider_id);
                        $footer = (count($contentList) == 0) ? '没有数据' : "掌握30日内消费最多的终端便利店";
                        break; // get top2
                    case 3:
                        $header = array("排列", "分类", "销量", "销售金额",);
                        $cols = 4;
                        $contentList = $this->statistics_model->getTopTypeItems($provider_id);
                        $footer = (count($contentList) == 0) ? '没有数据' : "掌握30日内最热销售的商品";
                        break; // get top3
                    case 4:
                        $header = array("排名", "LOGO", "品牌", "销量", "销售金额",);
                        $cols = 5;
                        $contentList = $this->statistics_model->getTopBrandItems($provider_id);
                        $footer = (count($contentList) == 0) ? '没有数据' : "掌握30日内最热销售的商品";
                        break; // get top4
                    case 5:
                        $header = array("排名", "商品条码", "封面", "商品名称", "销量", "销售金额",);
                        $cols = 6;
                        $contentList = $this->statistics_model->getTopProductItems($provider_id);
                        $footer = (count($contentList) == 0) ? '没有数据' : "掌握30日内最热销售的商品";
                        break; // get top5
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

    /**
     * This function used to make list view
     */

    function output_content($allLists, $id)
    {
        $output_html = '';
        if (!isset($allLists) || count($allLists) == 0) return '';
        // ????? make list id: 1-top1, 2-top2, 3-top3, 4-top4, 5-top5
        $i = 0;
        foreach ($allLists as $item) {
            if (count($item) == 0) continue;
            $output_html .= '<tr>';
            $j = 0;
            foreach ($item as $subitem) {
                $badge = ($i < 3 ? '<span style="color:darkgoldenrod;"><i class="fa fa-gift"></i></span>' : '');
                $output_html .= '<td>' . ($j == 0 ? $badge."&nbsp;&nbsp;".($i + 1) : $subitem) . '</td>';
//            $output_html .= '<td>' . ($item->type == '1' ? '旅行社' : ($item->type == '2' ? '渠道商' : '')) . '</td>';
//            $output_html .= '<td>' . $this->activity_model->getAreaCountByShopId($item->id, 1) . '</td>';
//            $output_html .= '<td>' . $this->activity_model->getAreaCountByShopId($item->id, 2) . '</td>';
//            $output_html .= '<td>' . $this->auth_model->getAuthCountByShopId($item->id) . '</td>';
//            $output_html .= '<td>' . $item->address_1 . '</td>';
//            $output_html .= '<td>' . ($item->status == 1 ? '已禁用' : '未禁用') . '</td>';
//            $output_html .= '<td>';
//            $output_html .= '<a href="' . base_url() . 'showshop/' . $item->id . '">查看 &nbsp;&nbsp;</a>';
//            $output_html .= '<a href="' . base_url() . 'editshop/' . $item->id . '">编辑 &nbsp;&nbsp;</a>';
//            if ($item->status == '0') {
//                $output_html .= '<a href="#" onclick="deleteShopConfirm(' . $item->id . ')">删除 &nbsp;&nbsp;</a>';
//            }
//            if ($item->status == '0') {
//                $output_html .= '<a href="#" onclick="deployShopConfirm(' . $item->id . ')">禁用 &nbsp;&nbsp;</a>';
//            } else {
//                $output_html .= '<a href="#" onclick="undeployShopConfirm(' . $item->id . ')">取消禁用 &nbsp;&nbsp;</a>';
//            }
//            $output_html .= '<a href="#" onclick="showGenerateQR(' . $item->id . ')">生成二维码 &nbsp;&nbsp;</a>';
//            $output_html .= '<a href="#" onclick="showGenerateAuth(' . $item->id . ')">发放授权码 &nbsp;&nbsp;</a>';
//
//            $output_html .= '</td>';
                $j++;
            }
            $output_html .= '</tr>';
            $i++;
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

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

/* End of file shop.php */
/* Location: .application/controllers/shop.php */

?>