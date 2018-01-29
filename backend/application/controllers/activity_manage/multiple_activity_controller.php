<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class multiple_activity_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('activity_model');
        $this->load->model('product_model');
        $this->load->model('product_util_model');
        $this->load->model('shop_model');
        $this->load->model('user_model');
        $this->load->model('transaction_model');
        $this->load->model('order_model');
        $this->load->model('news_model');
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
            $this->global['pageTitle'] = '套装活动列表';
            $this->global['pageName'] = 'multiple_activity';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();

            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchStatus'] = '0';
            $data['searchOrder'] = '0';
            $data['searchRecommend'] = '0';

            $this->loadViews("activity_manage/multiple_activity", $this->global, $data, NULL);
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
                $searchData['kind'] = 2;
                $searchData['provider_id'] = $this->user_model->getProviderId($this->global['login_id']);

                // get top list data in homepage
                switch ($id) {
                    case 1:
                        if ($this->isTicketter() == FALSE) {
                            $header = array("序号", "活动编号", "活动名称", "活动状态", "拼团开始时间", "操作");
                            $cols = 6;
                        } else if ($this->isAdmin() == FALSE) {
                            $header = array("序号", "活动编号", "活动名称", "所属区域总代理", "活动状态", "拼团开始时间", "置顶顺序", "操作");
                            $cols = 8;
                        }

                        $contentList = $this->activity_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有活动." : '';
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

    function product_list()
    {
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        $ids = $_POST['ids'];
        $header = array("商品条码", "封面", "商品名称", "原价（元）", "拼团价（元）", "采购数量");
        $cols = 6;

        $provider_id = ($this->isTicketter() == FALSE) ? $this->global['shop_manager_number'] : '0';
        $contentList = $this->activity_model->getProductsFromIds($ids, $provider_id);

        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有商品." : '';

        $ret['header'] = $this->output_header($header);
        $ret['content'] = $contentList;
        $ret['footer'] = $this->output_footer($footer, $cols);
        $ret['status'] = 'success';

        echo json_encode($ret);
    }

    function all_products()
    {
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        $header = array("商品条码", "封面", "商品名称", "库存量", "原价", "操作");
        $cols = 6;

        $provider_id = ($this->isTicketter() == FALSE) ? $this->global['shop_manager_number'] : '0';
        $contentList = $this->activity_model->getProducts($provider_id);

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
                    $output_html .= '<td>' . $item->name . '</td>';

                    if ($this->isTicketter() == TRUE && $this->isAdmin() == FALSE) {
                        $output_html .= '<td>' . $item->username . '</td>';
                    }

                    switch ($item->status) {
                        case 1:
                            $output_html .= '<td>未开始</td>';
                            break;
                        case 2:
                            $output_html .= '<td>拼团中</td>';
                            break;
                        case 3:
                            $output_html .= '<td>拼团成功</td>';
                            break;
                        case 4:
                            $output_html .= '<td>拼团失败</td>';
                            break;
                    }

                    $output_html .= '<td>' . $item->start_time . '</td>';
                    if ($this->isTicketter() == TRUE && $this->isAdmin() == FALSE) {
                        $output_html .= '<td>' . (($item->order == '1000') ? '' : $item->order) . '</td>';
                    }

                    $output_html .= '<td>';
                    $output_html .= '<a href="' . base_url() . 'multiple_activity_show/' . $item->id . '">查看 &nbsp;&nbsp;</a>';
                    if ($this->isTicketter() == FALSE) {
                        switch ($item->status) {
                            case '1':
                                $output_html .= '<a href="' . base_url() . 'multiple_activity_edit/' . $item->id . '">编辑 &nbsp;&nbsp;</a>';
                                $output_html .= '<a href="#" onclick="deleteConfirm(\'' . $item->id . '\')">删除 &nbsp;&nbsp;</a>';
                                break;
                            case '2':
                                $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->id . '\', 2, 3)">一键成团 &nbsp;&nbsp;</a>';
                                break;
                            case '3':
                            case '4':
                                $output_html .= '<a href="' . base_url() . 'multiple_activity_copy/' . $item->id . '">再拼团 &nbsp;&nbsp;</a>';
                                break;
                        }
                    } else if ($this->isAdmin() == FALSE) {
                        if ($item->status == '3' || $item->status == '4') continue;

                        if ($item->recommend_status != '0')
                            $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->id . '\', 1, 0)">取消推荐 &nbsp;&nbsp;</a>';
                        else
                            $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->id . '\', 1, 1)">推荐 &nbsp;&nbsp;</a>';

                        if ($item->order != '1000')
                            $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->id . '\', 3, 0)">取消置顶 &nbsp;&nbsp;</a>';
                        else
                            $output_html .= '<a href="#" onclick="orderConfirm(\'' . $item->id . '\')">置顶 &nbsp;&nbsp;</a>';
                    }

                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 2:
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
     * This function is used to load the user list
     */
    function additem()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '新增套装活动';
            $this->global['pageName'] = 'multiple_activity_add';

            if (empty($_POST)) {
                $data['empty'] = NULL;
                $data['start_time'] = date('Y-m-d H:i', strtotime("5 minutes"));
                $data['end_time'] = date('Y-m-d H:i', strtotime("2 days"));

                $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
            } else {
                $this->item_validate();
            }
        }
    }

    function item_validate()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('activity_name', '活动名称', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('start_time', '拼团开始时间', 'trim|required');
        $this->form_validation->set_rules('end_time', '拼团结束时间', 'trim|required');
        $this->form_validation->set_rules('man_cnt', '拼单人数', 'trim|required|numeric|greater_than[1]|max_length[3]');
        $this->form_validation->set_rules('group_cnt', '起团数量', 'trim|required|numeric|greater_than[1]|max_length[3]');
        $this->form_validation->set_rules('product_ids', '商品', 'trim|required');
        $this->form_validation->set_rules('contents', '商品详情', 'trim|required');
        $this->form_validation->set_rules('group_cost', '拼团价', 'trim|required|numeric|greater_than[0]');

        $item = new stdClass();
        $item->activity_id = $this->input->post('activity_id');
        $item->activity_name = $this->input->post('activity_name');
        $item->start_time = $this->input->post('start_time');
        $item->end_time = $this->input->post('end_time');
        $item->man_cnt = $this->input->post('man_cnt');
        $item->group_cnt = $this->input->post('group_cnt');
        $item->group_cost = $this->input->post('group_cost');
        $item->origin_cost = $this->input->post('origin_cost');
        $item->product_id = $this->input->post('product_ids');
        $item->buy_cnt = $this->input->post('buy_cnt');

        // start storing extra data
        $more_data = new stdClass();
        $more_data->cover = $this->input->post('cover');
        $more_data->contents = $this->input->post('contents');

        $brand_count = $this->input->post('image_count');
        $brand = array();
        for ($i = 1; $i <= $brand_count; $i++) {
            array_push($brand, json_decode($this->input->post('brand' . $i)));
        }
        $more_data->images = json_encode($brand);
        // end storing extra data
        $item->more_data = json_encode($more_data);

        $provider_id = ($this->isTicketter() == FALSE) ? $this->global['shop_manager_number'] : '0';

        if ($this->form_validation->run() == FALSE) {
            $this->global['model'] = $item;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
        } else {
            if (date($item->start_time) < date('Y-m-d H:i')) {
                $this->form_validation->set_rules('start_time', '拼团开始时间必须大于当前时间。', 'user_error');
                $this->form_validation->run();
                $this->global['model'] = $item;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
                return;
            }

            if (date($item->start_time) > date($item->end_time)) {
                $this->form_validation->set_rules('end_time', '拼团结束时间必须大于拼团开始时间。', 'user_error');
                $this->form_validation->run();

                $this->global['model'] = $item;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
                return;
            }

            if (floatval($item->group_cost) > floatval($item->origin_cost)) {
                $this->form_validation->set_rules('group_cost', '拼团价必须短于原价。', 'user_error');
                $this->form_validation->run();

                $this->global['model'] = $item;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
                return;
            }

            $tmp = json_decode($more_data->cover);
            if ($tmp[0] == '' || $brand_count == 0) {
                $this->form_validation->set_rules('end_time', '请上传套餐图片', 'user_error');
                $this->form_validation->run();

                $this->global['model'] = $item;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
                return;
            }

            // calculate product count
            $cnt  = 0;
            $cnts = json_decode($item->buy_cnt);
            foreach ($cnts as $cnt_item){
                $cnt += intval($cnt_item->count);
            }

            $iteminfo = array(
                'id' => $item->activity_id,
                'provider_id' => $provider_id,
                'product_id' => $item->product_id,
                'product_cnt' => $cnt,
                'name' => $item->activity_name,
                'kind' => 2,
                'order' => 1000,
                'man_cnt' => $item->man_cnt,
                'group_cnt' => $item->group_cnt,
                'group_cost' => $item->group_cost,
                'origin_cost' => $item->origin_cost,
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
                'buy_cnt' => $item->buy_cnt,
                'more_data' => $item->more_data
            );
            $this->activity_model->add($iteminfo);
            redirect('multiple_activity');
        }
    }

    /**
     * This function is used to edit the user information
     */
    function edititem($Id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑套装活动';
            $this->global['pageName'] = 'multiple_activity_add';

            $data['empty'] = NULL;
            $item = $this->getItemInfo($Id);

            $this->global['model'] = $item;
            $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to edit the user information
     */
    function copyitem($Id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑套装活动';
            $this->global['pageName'] = 'multiple_activity_add';

            $data['empty'] = NULL;
            $item = $this->getItemInfo($Id);
            $item->activity_id = '0';

            $this->global['model'] = $item;
            $this->loadViews("activity_manage/multiple_activity_add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to show the user information
     */
    function showitem($Id)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '套装活动详情';
            $this->global['pageName'] = 'multiple_activity_detail';

            $data['empty'] = NULL;

            $item = $this->getItemInfo($Id);
            $userinfo = $this->user_model->getProviderInfos($item->provider_id);
            $item->provider_name = $userinfo->username;
            $item->provider_userid = $userinfo->userid;
            $this->global['model'] = $item;
            $this->loadViews("activity_manage/multiple_activity_detail", $this->global, $data, NULL);
        }
    }

    function getPorductNamesByBrand()
    {
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        if (!empty($_POST)) {
            $type = $_POST['type'];
            $brand = $_POST['brand'];
            $provider_id = ($this->isTicketter() == FALSE) ? $this->global['shop_manager_number'] : '0';

            $ret['content'] = $this->activity_model->getProductList($type, $brand, $provider_id);
            if ($ret['content'] != NULL) $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function getProductDetailInfo()
    {
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        if (!empty($_POST)) {
            $id = $_POST['id'];
            $item = $this->getItemInfo($id);

            $ret['content'] = $item;
            if ($ret['content'] != NULL) $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function getItemInfo($id)
    {
        $item = $this->activity_model->getItemById($id);
        if ($item == NULL) return $item;
        $item->type_name = $this->product_util_model->getTypeNameById($item->type);
        $item->brand_name = $this->product_util_model->getBrandNameById($item->brand);
        $item->unit_name = $this->product_util_model->getUnitNameById($item->unit);

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
            echo $this->activity_model->add($result);
            if (!isset($result['status'])) return;

            $activity = $this->activity_model->getItemById($result['id']);
            if ($activity->users == '') return;

            $status = $result['status'];
            $orders = $this->order_model->getOrderByActivityId($result['id']);
            if ($orders == NULL) return;

            foreach ($orders as $order) {
                if($order->status == 1){
                    $order_info = array(
                        'status' => 5,
                        'cancel_time' => date('Y-m-d H:i:s')
                    );
                    $this->order_model->update($order_info, $order->id);
                    continue;
                }

                $order_info = array(
                    'status' => 3,
                    'isSuccess' => $status - 2,
                    'success_time' => date('Y-m-d H:i:s')
                );

                $money = number_format(floatval($order->pay_cost) - floatval($order->group_cost), 2, '.', '');
                if ($status == 3) {
                    if ($order->pay_method == 1 && floatval($money)>0) {
                        // refund extra money
                        $order_info['refund_cost'] = $money;
                        $order_info['refund_time'] = date('Y-m-d H:i:s');
                        $result_id = $this->order_model->update($order_info, $order->id);
                        if(!$result_id) continue;

                        $news_data = array(
                            'sender' => $order->provider,
                            'receiver' => $order->shop,
                            'type' => '拼团活动消息',
                            'message' => '恭喜 ' . $activity->activity_name . '商品拼单成功，本次为您节省' . $money . '元，金额请从我的钱包中查看。'
                        );
                        $this->news_model->add($news_data);

                        //add transaction to message list
                        $transaction_info = array(
                            'order_id' => $order->id,
                            'provider_id' => $order->provider,
                            'shop_id' => $order->shop,
                            'money' => '-' . $money,
                            'pay_type' => 1,
                            'note' => $activity->activity_name . '拼单成功返现'
                        );
                        $this->transaction_model->add($transaction_info);

                        $userinfo = $this->user_model->getUserInfoByid($order->shop);
                        $user_info = array(
                            'balance' => floatval($money) + floatval($userinfo->balance)
                        );
                        $this->shop_model->update($user_info, $userinfo->userid);

                        $provider_info = $this->user_model->getProviderInfos($order->provider);
                        $user_info = array(
                            'balance' => number_format( floatval($provider_info->balance) - floatval($money), 2, '.','')
                        );
                        $this->shop_model->update($user_info, $provider_info->userid);
                    }
                } else {
                    $result_id = $this->order_model->update($order_info, $order->id);
                    if(!$result_id) continue;

                    $news_data = array(
                        'sender' => $order->provider,
                        'receiver' => $order->shop,
                        'type' => '拼团活动消息',
                        'message' => $activity->activity_name . '商品拼单失败，无法返还拼单成功差额，为降低您的彩购成本，请多多发动朋友参与拼单哦。'
                    );
                    $this->news_model->add($news_data);
                }
            }

            $news_data = array(
                'sender' => '0',
                'receiver' => $order->provider,
                'type' => '拼团活动消息',
                'message' => $activity->activity_name . (($status == 3) ? '拼团成功，按拼团价结算，系统已自动退还终端便利店差价。' : '拼团失败，该拼团活动订单按原价结算。')
            );
            $this->news_model->add($news_data);
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
        echo $this->activity_model->delete($result);
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
        $this->global['pageName'] = 'multiple_activity_upload';
        $data['ret_Url'] = 'multiple_activity_edit';

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