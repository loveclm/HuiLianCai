<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class shipping_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('order_model');
        $this->load->model('activity_model');
        $this->load->model('news_model');
        $this->load->model('shipping_model');
        $this->load->model('statistics_model');
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
     * in admin page, show ship man list
     */
    function list_view()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '配送员列表';
            $this->global['pageName'] = 'shipman';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['address'] = '';
            $data['searchStatus'] = '0';

            $this->loadViews("user_manage/shipman", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the user list
     * in provider page, accomplish shipping management
     */
    function shipman_manage()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '配送员列表';
            $this->global['pageName'] = 'shipman_manage';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['address'] = '';
            $data['searchStatus'] = '0';

            $this->loadViews("user_manage/shipman", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the user list
     * in provider page, accomplish shipping management
     */
    function shipping()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '配送列表';
            $this->global['pageName'] = 'shipping';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['address'] = '';
            $data['searchStatus'] = '0';

            $this->loadViews("product_manage/shipping", $this->global, $data, NULL);
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
                        $header = array("配送员账号", "姓名", "所属区域总代理", "所属县区", "联系电话", "头像",
                            "配送数量", "禁用状态", "新增时间");
                        $cols = 9;

                        $contentList = $this->shipping_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有数据." : '';

                        // end get
                        $ret['header'] = $this->output_header($header);
                        $ret['content'] = $this->output_content($contentList, $id);
                        $ret['footer'] = $this->output_footer($footer, $cols);
                        $ret['status'] = 'success';
                        break;
                    case 2:
                        $header = array("序号", "账号", "姓名", "联系电话", "新增时间", "操作");
                        $cols = 6;

                        $contentList = $this->shipping_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有数据." : '';
                        // end get

                        $ret['header'] = $this->output_header($header);
                        $ret['content'] = $this->output_content($contentList, $id);
                        $ret['footer'] = $this->output_footer($footer, $cols);
                        $ret['status'] = 'success';
                        break;
                    case 4:
                        $header = array("配送编号", "配送日期", "配送员", "配送数量", "订单金额", "配送状态", "操作");
                        $cols = 7;

                        $contentList = $this->shipping_model->getShipItems($searchData);
                        $footer = (count($contentList) == 0) ? "没有数据." : '';

                        // end get
                        $ret['header'] = $this->output_header($header);
                        $ret['content'] = $this->output_content($contentList, $id);
                        $ret['footer'] = $this->output_footer($footer, $cols);
                        $ret['status'] = 'success';
                        break;
                }
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
        // ????? make list id: 1-main menu
        $i = 0;
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->userid . '</td>';
                    $output_html .= '<td>' . $item->username . '</td>';
                    $output_html .= '<td>' . $item->provider . '</td>';
                    $output_html .= '<td>' . $item->provider_addr . '</td>';
                    $output_html .= '<td>' . $item->contact_phone . '</td>';
                    $output_html .= '<td>';
                    $output_html .=  '<img src="'. base_url() . $item->more_data . '" style="width:50px;height:50px; border-radius:50%; border:1px solid #f0f0f0;">';
                    $output_html .= '</td>';

                    $output_html .= '<td>' . $this->shipping_model->getShippingCount($this->user_model->getIdByUserId($item->userid)) . '</td>';
                    $output_html .= '<td>';
                    $output_html .= '未禁用'; //($item->status == 1) ? '未禁用' : '已禁用';
                    $output_html .= '</td>';
                    $output_html .= '<td>' . $item->created_time . '</td>';
                    $output_html .= '</tr>';
                    $i++;
                }
                break;
            case 2:
                foreach ($allLists as $item) {
                    $i++;
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $i . '</td>';
                    $output_html .= '<td>' . $item->userid . '</td>';
                    $output_html .= '<td>' . $item->username . '</td>';
                    $output_html .= '<td>' . $item->contact_phone . '</td>';
                    $output_html .= '<td>' . $item->created_time . '</td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="' . base_url() . 'shipman_show/' . $item->id . '">查看 &nbsp;&nbsp;</a>';
                    $output_html .= '<a href="' . base_url() . 'shipman_edit/' . $item->id . '">编辑 &nbsp;&nbsp;</a>';
                    if($this->shipping_model->isDeletable($item->id ) == true)
                        $output_html .= '<a href="#" onclick="deleteConfirm(\'' . $item->id . '\')">删除 &nbsp;&nbsp;</a>';
                    else
                        $output_html .= '<a href="#" onclick="$(\'#alert_delete\').show();" style="color: grey;">删除 &nbsp;&nbsp;</a>';

                    $output_html .= '<a href="#" onclick="passwordConfirm(\'' . $item->id . '\')">重置密码 &nbsp;&nbsp;</a>';
                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 4:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->id . '</td>';
                    $output_html .= '<td>' . $item->create_time . '</td>';
                    $output_html .= '<td>' . $item->username . '</td>';
                    $output_html .= '<td>' . $this->shipping_model->getShippingCountFromOrders($item->order_id) . '</td>';
                    $output_html .= '<td>' . number_format((float)$item->money, 2, '.', '') . '</td>';
                    $output_html .= '<td>';
                    $output_html .= ($item->state == 1) ? '未装车' : '已装车';
                    $output_html .= '</td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="' . base_url() . 'shipping_show/' . $item->id . '">明细 &nbsp;&nbsp;</a>';

                    if ($item->state == 1) {
                        $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->id . '\')">装车 &nbsp;&nbsp;</a>';
                    }
                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 5:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item['id'] . '</td>';
                    $output_html .= '<td>' . $item['barcode'] . '</td>';
                    $output_html .= '<td>' . $item['name'] . '</td>';
                    $output_html .= '<td>' . $item['cnt'] . '</td>';
                    $output_html .= '</tr>';
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
     * This function is used to load the user list
     */
    function additem()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '新增配送员';
            $this->global['pageName'] = 'shipman_add';
            if (empty($_POST)) {
                $this->loadViews("user_manage/shipman_add", $this->global, NULL, NULL);
            } else {
                $this->shipping_validate();
            }
        }
    }

    function shipping_validate()
    {
        $this->load->library('form_validation');

        $ship_id = $this->input->post('ship_id');

        $item = new stdClass();

        $this->form_validation->set_rules('username', '名称', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('contact_phone', '联系电话', 'trim|required|exact_length[11]');

        if ($ship_id == '') {
            $this->form_validation->set_rules('userid', '账号', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('password', '密码', 'required|min_length[6]|matches[confirm_password]|max_length[20]');
            $this->form_validation->set_rules('confirm_password', '确认密码 ', 'required|min_length[6]|matches[password]|max_length[20]');

            $confirm_password = $this->input->post('confirm_password');
            $item->userid = $this->input->post('userid');
            $item->password = $this->input->post('password');
            $item->more_data = $this->input->post('cover');
        }

        $item->username = $this->input->post('username');
        $item->contact_phone = $this->input->post('contact_phone');

        $provider_id = ($this->isTicketter() == FALSE) ? $this->global['shop_manager_number'] : '0';

        if ($this->form_validation->run() == FALSE) {

            $item->password = '';
            $this->global['model'] = $item;
            // the user list that manages site
            $data['id'] = $ship_id;

            $this->loadViews("user_manage/shipman_add", $this->global, $data, NULL);
        } else {
            if ($this->user_model->isExistUserName($item->username, 1) ) {
                $this->form_validation->set_rules('userid', '名称已存在。', 'user_error');
                $this->form_validation->run();
                $data['id'] = $ship_id;

                if ($ship_id != '')
                    $this->global['model'] = $this->shipping_model->getItemById($ship_id);
                else
                    $this->global['model'] = $item;

                $this->loadViews("user_manage/shipman_add", $this->global, $data, NULL);
                return;
            }

            if ($ship_id == '') {
                if ($this->user_model->isExistUserId($item->userid, 1) == true) {
                    $this->form_validation->set_rules('userid', '账号已存在。', 'user_error');
                    $this->form_validation->run();

                    $this->global['model'] = $item;
                    // the user list that manages site
                    $data['id'] = $ship_id;

                    $this->loadViews("user_manage/shipman_add", $this->global, $data, NULL);
                    return;
                }

                if ($item->password != $confirm_password) {
                    $this->form_validation->set_rules('userid', '密码和确认密码必须相同。', 'user_error');

                    $this->form_validation->run();
                    $this->global['model'] = $item;
                    // the user list that manages site
                    $data['id'] = $ship_id;

                    $this->loadViews("user_manage/shipman_add", $this->global, $data, NULL);
                    return;
                }
            }

            $iteminfo = array(
                'type' => 4,
                'provider_id' => $provider_id,
                'username' => $item->username,
                'contact_phone' => $item->contact_phone,
            );

            if ($ship_id != '') {
                $iteminfo['id'] = $ship_id;
            } else {
                $iteminfo['userid'] = $item->userid;
                $iteminfo['orderID'] = $item->password;
                $iteminfo['password'] = getHashedPassword($item->password);
                $iteminfo['more_data'] = $item->more_data;
            }

            $this->shipping_model->add($iteminfo);

            redirect('shipman_manage');
        }
    }

    /**
     * This function is used to load the user list
     */
    function addItem2DB($data = null)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $data = $_POST['itemInfo'];
            // if shipping setting then set ship_status and ship_time
            if(isset($data['state'])){
                $shipping_info = $this->shipping_model->getShippingItemByid($data['id']);

                $order_info = array(
                    'ship_status' => 2,
                    'ship_time' => date('Y-m-d H:i:s')
                );

                $ids = explode(',',$shipping_info->order_id);
                foreach ($ids as $id){
                    // set shipping status in order record
                    $this->shipping_model->updateShippingStatus($order_info, $id);

                    $order = $this->order_model->getItemById($id);
                    $activity = $this->activity_model->getItemByid($order->activity_ids);
                    // send news
                    $news_data = array(
                        'sender' => 0,
                        'receiver' => $order->shop,
                        'type' => '拼团活动消息',
                        'message' =>  $activity->activity_name .  '商品拼团结束，供货商将以拼团价/零批价尽快给您发货。'
                    );
                    $this->news_model->add($news_data);
                }
                // change shipping state
                $shipping = array(
                    'state' => 2
                );
                echo $this->shipping_model->updateShippingData($shipping, $shipping_info->id);
            }
            if(isset($data['orderID'])){
                $data['password'] = getHashedPassword($data['orderID']);

                echo $this->shipping_model->add($data);
            }
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
            echo $this->shipping_model->delete($result);
        }
    }

    /**
     * This function is used to edit the user information
     */
    function edititem($userId)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑配送员';
            $this->global['pageName'] = 'shipman_edit';

            $this->global['model'] = $this->shipping_model->getItemById($userId);
            $data['id'] = $userId;

            $this->loadViews("user_manage/shipman_add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to show the user information
     */
    function showitem($userId)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '配送员详情';
            $this->global['pageName'] = 'shipping_show';

            $this->global['model'] = $this->shipping_model->getItemById($userId);
            $data['id'] = $userId;

            $this->loadViews("user_manage/shipman_detail", $this->global, $data, NULL);
        }
    }

    function showShipping($ship_id)
    {
        $this->global['pageTitle'] = '配送明细';
        $this->global['pageName'] = 'shipping_detail';

        $contentlist = $this->shipping_model->getProductInfosByShipping($ship_id);

        $data = new stdClass();
        $header = array("配送编号", "商品条码", "商品名称", "配送数量");
        $data->header = $this->output_header($header);
        $data->content = $this->output_content($contentlist, 5);
        $footer = (count($contentlist) == 0) ? '没有数据.' :'';
        $data->footer = $this->output_footer($footer,4);
        $this->global['data'] = $data;
        $this->loadViews("product_manage/shipping_detail", $this->global, NULL, NULL);
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