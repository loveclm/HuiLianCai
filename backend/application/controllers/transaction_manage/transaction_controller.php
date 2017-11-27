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
            $data['balance'] = $provider_info->balance;
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
                $searchData['provider_id'] = $this->user_model->getProviderId($this->global['login_id']);
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
                    $output_html .= '<td>' . $this->convertNote($item->note) . '</td>';
                    $output_html .= '<td>' . $item->order_id . '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 2:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $item->time . '</td>';
                    switch ($item->status){
                        case 1: //提现中
                            $output_html .= '<td>- ' . number_format((float)$item->money,2,'.','') . '</td>';
                            $output_html .= '<td>提现中</td>';
                            break;
                        case 2: //提现成功
                            $output_html .= '<td>- ' . number_format((float)$item->money,2,'.','') . '</td>';
                            $output_html .= '<td>提现成功</td>';
                            break;
                        case 3:  //提现失败
                            $output_html .= '<td>+ ' . number_format((float)$item->money,2,'.','') . '</td>';
                            $output_html .= '<td>提现失败</td>';
                            break;
                        case 4:  //提现关闭
                            $output_html .= '<td>- ' . number_format((float)$item->money,2,'.','') . '</td>';
                            $output_html .= '<td>提现关闭</td>';
                            break;
                    }
                    $output_html .= '</tr>';
                }
                break;
            case 3:
                foreach ($allLists as $item) {
                    $output_html .= '<tr>';
                    $output_html .= '<td style="width: 85px;">' . $item->time . '</td>';
                    $output_html .= '<td>' . $item->id . '</td>';
                    $output_html .= '<td style="width: 100px;">' . $item->shop_id . '</td>';
                    $output_html .= '<td>' . $item->shop_name . '</td>';
                    $output_html .= '<td>' . number_format((float)$item->money,2,'.','') . '</td>';
                    $output_html .= '<td>' . $this->convertNote($item->note) . '</td>';
                    $output_html .= '<td>' . $item->order_id . '</td>';
                    $output_html .= '<td><a href="'. base_url().'order_show/' . $item->order_id . '">查看</a></td>';
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
                    $output_html .= '<td><a href="'. base_url().'order_show/' . $item->order_id . '">查看</a></td>';
                    $output_html .= '</tr>';
                }
                break;
        }

        return $output_html;
    }

    function convertNote($note){
        if(substr($note, 0,2) == '拼单') return '订单付款';
        if(substr($note, -6) == '拼单成功返现') return '退还差额（拼团成功）';
        if( $note == '退款到账') return '全额退款（取消订单）';

        return $note;
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
            //配置您申请的appkey
//            $appkey = "1111";
//            //************1.银行卡归属地查询************
//            $url = "http://v.juhe.cn/verifybankcard4/query";
//            $params = array(
//                "key" => $appkey,//应用APPKEY(应用详细页查询)
//                "dtype" => "json",//返回数据的格式,xml或json，默认json
//                "bankcard" => $item->card_no,//银行卡号
//                "realname" => $item->provider_name, //持卡人
//                "idcard" => $item->cert_no, //身份证号
//                "mobile" => $item->reserve_mobile, //手机号
//            );
//
//            $paramstring = http_build_query($params);
//            $content = $this->juhecurl($url,$paramstring);
//            $result = json_decode($content,true);
//            if($result){
//                if($result['error_code']!='0'){
//                    $this->form_validation->set_rules('card_no', "【银行卡确认】 ". $result['error_code'] . " : " . $result['reason'], 'user_error');
//                    $this->form_validation->run();
//
//                    $this->global['model'] = $item;
//                    // the user list that manages site
//                    $data['bankinfo_id'] = '';
//                    $this->loadViews("transaction_manage/bankinfo_add", $this->global, $data, NULL);
//                    return;
//                }
//            }else{
//                $this->form_validation->set_rules('card_no', "【银行卡确认】 请求失败", 'user_error');
//                $this->form_validation->run();
//
//                $this->global['model'] = $item;
//                // the user list that manages site
//                $data['bankinfo_id'] = '';
//                $this->loadViews("transaction_manage/bankinfo_add", $this->global, $data, NULL);
//                return;
//            }

            $item->provider_id = $this->user_model->getProviderId($this->global['login_id']);
            $this->transaction_model->addBankinfo($item);

            redirect('showMyMoney');
        }
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );

        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );

        return $response;
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
            $this->global['model'] = $this->transaction_model->getBankinfoById($Id);

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