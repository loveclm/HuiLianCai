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

            $provider_info = $this->user_model->getProviderInfoFromTblUser($this->global['login_id']);
            $bankinfo = $this->transaction_model->getBankinfo($provider_info->id);
            $data['bankinfo_id'] = ($bankinfo == NULL) ? '' : $bankinfo->id;
            $data['card_info'] = ($bankinfo == NULL) ? '' : $bankinfo->bank_name . '储蓄卡(' . substr($bankinfo->card_no, -4) . ')';
            $data['balance'] = 200; //$provider_info->balance;
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
            $this->global['pageTitle'] = '绑定银行卡';
            $this->global['pageName'] = 'bankinfo_add';

            if (empty($_POST)) {
                $data['empty'] = NULL;

                $this->loadViews("transaction_manage/bankinfo_add", $this->global, $data, NULL);
            } else {
                $this->item_validate();
            }
        }
    }

    function item_validate()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('bank_name', '银行卡开户行', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('provider_name', '持卡人', 'trim|required|min_length[2]|max_length[5]');
        $this->form_validation->set_rules('cert_no', '身份证号', 'trim|required|exact_length[18]');
        $this->form_validation->set_rules('card_no', '银行卡号', 'trim|required||numeric|max_length[25]');
        $this->form_validation->set_rules('reserve_mobile', '手机号', 'trim|required|exact_length[11]');

        $item = new stdClass();
        $item->bank_name = $this->input->post('bank_name');
        $item->provider_name = $this->input->post('provider_name');
        $item->cert_no = $this->input->post('cert_no');
        $item->card_no = $this->input->post('card_no');
        $item->reserve_mobile = $this->input->post('reserve_mobile');

        if ($this->form_validation->run() == FALSE) {
            $this->global['model'] = $item;
            // the user list that manages site
            $data['bankinfo_id'] = '';

            $this->loadViews("transaction_manage/bankinfo_add", $this->global, $data, NULL);
        } else {
            $this->transaction_model->addBankinfo($item);

            redirect('showMyMoney');
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
            $this->global['pageTitle'] = '查看银行卡';
            $this->global['pageName'] = 'transaction_detail';

            $data['empty'] = NULL;
            $this->global['model'] = $this->transaction_model->getBankinfo($Id);

            $this->loadViews("transaction_manage/bankinfo_detail", $this->global, $data, NULL);
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