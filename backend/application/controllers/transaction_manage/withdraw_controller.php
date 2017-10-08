<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class withdraw_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('withdraw_model');
        $this->load->model('product_model');
        $this->load->model('product_util_model');
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
            $this->global['pageTitle'] = '提现列表';
            $this->global['pageName'] = 'withdraw';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchStatus'] = '0';

            $this->loadViews("transaction_manage/withdraw", $this->global, $data, NULL);
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

                        $header = array("供货商账号", "持卡人", "银行卡号", "银行预留手机号", "申请金额", "状态", "申请时间", "操作");
                        $cols = 8;

                        $contentList = $this->withdraw_model->getItems($searchData);
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
                    $output_html .= '<td>' . $item->provider_userid . '</td>';
                    $output_html .= '<td>' . $item->provider_name . '</td>';
                    $output_html .= '<td>' . $item->card_no . '</td>';
                    $output_html .= '<td>' . $item->reserve_mobile . '</td>';
                    $output_html .= '<td>' . number_format((float)$item->money,2,'.','') . '</td>';

                    switch ($item->status) {
                        case 1:
                            $output_html .= '<td>提现中</td>';
                            break;
                        case 2:
                            $output_html .= '<td>提现成功</td>';
                            break;
                        case 3:
                            $output_html .= '<td>提现失败</td>';
                            break;
                    }

                    $output_html .= '<td>' . $item->time . '</td>';

                    $output_html .= '<td>';
                    switch ($item->status) {
                        case '1':
                            $output_html .= '<a href="' . base_url() . 'withdraw_edit/' . $item->withdraw_id . '">打款 &nbsp;&nbsp;</a>';
                            break;
                        default:
                            $output_html .= '<a href="' . base_url() . 'withdraw_show/' . $item->withdraw_id . '">查看 &nbsp;&nbsp;</a>';
                            break;
                    }
                    $output_html .= '</td>';
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
            $this->global['pageName'] = 'withdraw_add';

            if (empty($_POST)) {
                $data['empty'] = NULL;

                $this->loadViews("transaction_manage/withdraw_add", $this->global, $data, NULL);
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
        $item->withdraw_id = $this->input->post('withdraw_id');
        $item->note = $this->input->post('note');
        $item->status = $this->input->post('status');
        if(intval($item->status) < 2) {
            $this->session->set_flashdata('error', '必选项，单选项。');

            $iteminfo = $this->withdraw_model->getItemById($item->withdraw_id);
            $iteminfo->note = $item->note;
            $iteminfo->status = $item->status;

            $this->global['model'] = $iteminfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("transaction_manage/withdraw_add", $this->global, $data, NULL);
        }

        if ($this->form_validation->run() == FALSE) {
            $iteminfo = $this->withdraw_model->getItemById($item->withdraw_id);
            $iteminfo->note = $item->note;
            $iteminfo->status = $item->status;

            $this->global['model'] = $iteminfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("transaction_manage/withdraw_add", $this->global, $data, NULL);
        } else {
            $iteminfo = array(
                'id' => $item->withdraw_id,
                'note' => $item->note,
                'status' => $item->status,
            );
            $this->withdraw_model->add($iteminfo);

            redirect('withdraw');
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
            $this->global['pageName'] = 'withdraw_add';

            $data['empty'] = NULL;
            $this->global['model'] = $this->withdraw_model->getItemById($Id);

            $this->loadViews("transaction_manage/withdraw_add", $this->global, $data, NULL);
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
            $this->global['pageName'] = 'withdraw_detail';

            $data['empty'] = NULL;
            $this->global['model'] = $this->withdraw_model->getItemById($Id);

            $this->loadViews("transaction_manage/withdraw_detail", $this->global, $data, NULL);
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
            echo $this->withdraw_model->add($result);
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
            echo $this->withdraw_model->delete($result);
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
            $this->global['pageName'] = 'withdraw_upload';
            $data['ret_Url'] = 'withdraw_edit';

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