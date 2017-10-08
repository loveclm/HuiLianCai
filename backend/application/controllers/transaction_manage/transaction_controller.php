<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class transaction_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaction_model');
        $this->load->model('withdraw_model');
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

    function showMyMoney(){
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '交易列表';
            $this->global['pageName'] = 'transaction';
            $data['balance'] = '10';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchStatus'] = '0';

            $this->loadViews("transaction_manage/my_transaction", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the user list
     */
    function list_view()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '交易列表';
            $this->global['pageName'] = 'transaction';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchStatus'] = '0';

            $this->loadViews("transaction_manage/transaction", $this->global, $data, NULL);
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
                // get top list data in homepage
                switch ($id) {
                    case 1:

                        $header = array("交易时间", "交易号", "终端便利店账号", "交易终端便利店", "交易金额", "交易说明", "订单号");
                        $cols = 7;

                        $contentList = $this->transaction_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有提现." : '';
                        break; // get top1
                    case 2:
                        $searchData['searchStatus'] = '0';
                        $header = array("提现申请时间", "交易金额", "交易说明");
                        $cols = 3;

                        $contentList = $this->withdraw_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有提现." : '';
                        break; // get top1
                    case 3:

                        $header = array("交易时间", "交易号", "终端便利店账号", "交易终端便利店", "交易金额", "交易说明", "订单号", "操作");
                        $cols = 8;

                        $contentList = $this->transaction_model->getItems($searchData, 1);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有提现." : '';
                        break; // get top1
                    case 4:

                        $header = array("交易时间", "终端便利店账号", "终端便利店", "订单金额", "订单编号", "操作");
                        $cols = 6;

                        $contentList = $this->transaction_model->getItems($searchData, 2);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有提现." : '';
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

    function output_content($allLists, $id)
    {
        $output_html = '';
        if (!isset($allLists) || count($allLists) == 0) return '';
        // make list id: 1-main menu
        $i = 0;
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->time . '</td>';
                    $output_html .= '<td>' . $item->id . '</td>';
                    $output_html .= '<td>' . $item->shop_id . '</td>';
                    $output_html .= '<td>' . $item->shop_name . '</td>';
                    $output_html .= '<td>' . number_format((float)$item->money,2,'.','') . '</td>';
                    $output_html .= '<td>' . $item->note . '</td>';
                    $output_html .= '<td>' . $item->order_id . '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 2:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->time . '</td>';
                    switch ($item->status){
                        case 1: //提现成功
                            $output_html .= '<td>- ' . number_format((float)$item->money,2,'.','') . '</td>';
                            break;
                        case 2: //提现失败
                            $output_html .= '<td>+ ' . number_format((float)$item->money,2,'.','') . '</td>';
                            break;
                        case 3:  //提现中
                            $output_html .= '<td>- ' . number_format((float)$item->money,2,'.','') . '</td>';
                            break;
                    }
                    $output_html .= '<td>' . $item->note . '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 3:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->time . '</td>';
                    $output_html .= '<td>' . $item->id . '</td>';
                    $output_html .= '<td>' . $item->shop_id . '</td>';
                    $output_html .= '<td>' . $item->shop_name . '</td>';
                    $output_html .= '<td>' . number_format((float)$item->money,2,'.','') . '</td>';
                    $output_html .= '<td>' . $item->note . '</td>';
                    $output_html .= '<td>' . $item->order_id . '</td>';
                    $output_html .= '<td><a href="'. base_url().'showitem/' . $item->id . '">查看</a></td>';
                    $output_html .= '</tr>';
                }
                break;
            case 4:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->time . '</td>';
                    $output_html .= '<td>' . $item->shop_id . '</td>';
                    $output_html .= '<td>' . $item->shop_name . '</td>';
                    $output_html .= '<td>' . number_format((float)$item->money,2,'.','') . '</td>';
                    $output_html .= '<td>' . $item->order_id . '</td>';
                    $output_html .= '<td><a href="'. base_url().'showitem/' . $item->id . '">查看</a></td>';
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
            $this->global['pageTitle'] = '新增单品活动';
            $this->global['pageName'] = 'transaction_add';

            if (empty($_POST)) {
                $data['empty'] = NULL;

                $this->loadViews("transaction_manage/transaction_add", $this->global, $data, NULL);
            } else {
                $this->item_validate();
            }
        }
    }

    function item_validate()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('note', '备注', 'trim|required|max_length[100]');

        $item = new stdClass();
        $item->transaction_id = $this->input->post('transaction_id');
        $item->note = $this->input->post('note');
        $item->status = $this->input->post('status');
        if(intval($item->status) < 2) {
            $this->session->set_flashdata('error', '必选项，单选项。');

            $iteminfo = $this->transaction_model->getItemById($item->transaction_id);
            $iteminfo->note = $item->note;
            $iteminfo->status = $item->status;

            $this->global['model'] = $iteminfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("transaction_manage/transaction_add", $this->global, $data, NULL);
        }

        if ($this->form_validation->run() == FALSE) {
            $iteminfo = $this->transaction_model->getItemById($item->transaction_id);
            $iteminfo->note = $item->note;
            $iteminfo->status = $item->status;

            $this->global['model'] = $iteminfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("transaction_manage/transaction_add", $this->global, $data, NULL);
        } else {
            $iteminfo = array(
                'id' => $item->transaction_id,
                'note' => $item->note,
                'status' => $item->status,
            );
            $this->transaction_model->add($iteminfo);

            redirect('transaction');
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
            $this->global['pageTitle'] = '打款';
            $this->global['pageName'] = 'transaction_add';

            $data['empty'] = NULL;
            $this->global['model'] = $this->transaction_model->getItemById($Id);

            $this->loadViews("transaction_manage/transaction_add", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = '提现详情';
            $this->global['pageName'] = 'transaction_detail';

            $data['empty'] = NULL;
            $this->global['model'] = $this->transaction_model->getItemById($Id);

            $this->loadViews("transaction_manage/transaction_detail", $this->global, $data, NULL);
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
            $result = $_POST['itemInfo'];
            echo $this->transaction_model->add($result);
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
            echo $this->transaction_model->delete($result);
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
            $this->global['pageName'] = 'transaction_upload';
            $data['ret_Url'] = 'transaction_edit';

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