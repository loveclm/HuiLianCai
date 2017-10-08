<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class coupon_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('coupon_model');
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
            $this->global['pageTitle'] = '优惠券列表';
            $this->global['pageName'] = 'coupon';
            $data['searchStatus'] = '0';

            $this->loadViews("coupon", $this->global, $data, NULL);
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

                        $header = array("优惠券编号", "优惠券金额", "所属终端便利店账号", "终端便利店", "状态", "增送时间", "使用时间");
                        $cols = 7;

                        $contentList = $this->coupon_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有优惠券." : '';
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
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->id . '</td>';
                    $output_html .= '<td>' . number_format((float)$item->coupon, 2, '.', '') . '</td>';
                    $output_html .= '<td>' . $item->userid . '</td>';
                    $output_html .= '<td>' . $item->username . '</td>';

                    switch ($item->using) {
                        case 1:
                            $output_html .= '<td>未使用</td>';
                            break;
                        case 2:
                            $output_html .= '<td>已使用</td>';
                            break;
                    }

                    $output_html .= '<td>' . $item->pass_time . '</td>';
                    $output_html .= '<td>' . $item->use_time . '</td>';

                }
                $output_html .= '</td>';
                $output_html .= '</tr>';
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
            $this->global['pageName'] = 'coupon_add';

            if (empty($_POST)) {
                $data['empty'] = NULL;

                $this->loadViews("transaction_manage/coupon_add", $this->global, $data, NULL);
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
        $item->coupon_id = $this->input->post('coupon_id');
        $item->note = $this->input->post('note');
        $item->status = $this->input->post('status');
        if (intval($item->status) < 2) {
            $this->session->set_flashdata('error', '必选项，单选项。');

            $iteminfo = $this->coupon_model->getItemById($item->coupon_id);
            $iteminfo->note = $item->note;
            $iteminfo->status = $item->status;

            $this->global['model'] = $iteminfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("transaction_manage/coupon_add", $this->global, $data, NULL);
        }

        if ($this->form_validation->run() == FALSE) {
            $iteminfo = $this->coupon_model->getItemById($item->coupon_id);
            $iteminfo->note = $item->note;
            $iteminfo->status = $item->status;

            $this->global['model'] = $iteminfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("transaction_manage/coupon_add", $this->global, $data, NULL);
        } else {
            $iteminfo = array(
                'id' => $item->coupon_id,
                'note' => $item->note,
                'status' => $item->status,
            );
            $this->coupon_model->add($iteminfo);

            redirect('coupon');
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
            $this->global['pageName'] = 'coupon_add';

            $data['empty'] = NULL;
            $this->global['model'] = $this->coupon_model->getItemById($Id);

            $this->loadViews("transaction_manage/coupon_add", $this->global, $data, NULL);
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
            $this->global['pageName'] = 'coupon_detail';

            $data['empty'] = NULL;
            $this->global['model'] = $this->coupon_model->getItemById($Id);

            $this->loadViews("transaction_manage/coupon_detail", $this->global, $data, NULL);
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
            echo $this->coupon_model->add($result);
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
            echo $this->coupon_model->delete($result);
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
            $this->global['pageName'] = 'coupon_upload';
            $data['ret_Url'] = 'coupon_edit';

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