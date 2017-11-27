<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/CCPRestSmsSDK.php';

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
        $this->load->model('shop_model');
        $this->load->model('news_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $msg = '';
        if(isset($_GET['msg'])) $msg = $_GET['msg'];

        $this->list_view($msg);
    }

    /**
     * This function is used to load the user list
     */
    function list_view($msg)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '提现列表';
            $this->global['pageName'] = 'withdraw';
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchStatus'] = '0';
            if($msg != '') $this->global['data'] = json_decode(urldecode($msg));

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
                $searchData['provider_id'] = $this->user_model->getProviderId($this->global['login_id']);
                // get top list data in homepage
                switch ($id) {
                    case 1:

                        $header = array("区域总代理账号", "持卡人", "银行卡号", "银行预留手机号", "申请金额", "状态", "申请时间", "操作");
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
                    if($item->status == 4) continue;
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
                        case 4:
                            $output_html .= '<td>提现关闭</td>';
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

        if ($this->form_validation->run() == FALSE) {
            $iteminfo = $this->withdraw_model->getItemById($item->withdraw_id);
            $iteminfo->note = $item->note;
            $iteminfo->status = $item->status;

            $this->global['model'] = $iteminfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("transaction_manage/withdraw_add", $this->global, $data, NULL);
        } else {

            if(intval($item->status) < 2) {
                $this->form_validation->set_rules('userid', '必选项，单选项。', 'user_error');
                $this->form_validation->run();

                $iteminfo = $this->withdraw_model->getItemById($item->withdraw_id);
                $iteminfo->note = $item->note;
                $iteminfo->status = $item->status;

                $this->global['model'] = $iteminfo;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("transaction_manage/withdraw_add", $this->global, $data, NULL);
            }

            $withdrowItem = $this->withdraw_model->getItemById($item->withdraw_id);
            $providerinfo = $this->user_model->getUserInfoByid( $withdrowItem->provider_id);

            if($item->status == 3){
                $iteminfo = array(
                    'provider_id' => $withdrowItem->provider_id,
                    'note' => $item->note,
                    'money' => $withdrowItem->money,
                    'status' => 4,
                );
                $this->withdraw_model->add($iteminfo);
            }

            $iteminfo = array(
                'id' => $item->withdraw_id,
                'note' => $item->note,
                'status' => $item->status,
            );
            $this->withdraw_model->add($iteminfo);

            // add message list
            if($item->status == 2){
                //add pay status message to message list
                $news_data = array(
                    'sender' => 0,
                    'receiver' => $withdrowItem->provider_id,
                    'type' => '提现申请',
                    'message' => '恭喜！您提现申请'. $withdrowItem->money. '元，已提现成功。'
                );
            }else{
                //add pay status message to message list
                $news_data = array(
                    'sender' => 0,
                    'receiver' => $withdrowItem->provider_id,
                    'type' => '提现申请',
                    'message' => '抱歉！您提现申请'. $withdrowItem->money. '元，已提现失败。'
                );
                // plus balance
                $userinfo = array(
                    'balance' => number_format(floatval($providerinfo->balance) + floatval($withdrowItem->money), 2, '.','')
                );
                $this->shop_model->update($userinfo, $providerinfo->userid);

            }
            $this->news_model->add($news_data);

            $result = $this->sendMessageToPhone($providerinfo->contact_phone, intval($item->status)-1);

            //$this->loadViews("transaction_manage/withdraw", $this->global, $data, NULL);
            redirect('withdraw?msg='.urlencode(json_encode($result)));
        }
    }

    function sendMessageToPhone($phone, $type){
        $message = array();

        //手机号码，替换内容数组，模板ID
        if($type == 1)
            return $this->sendTemplateSMS($phone, $message, 217997);
        else
            return $this->sendTemplateSMS($phone, $message, 217996);
    }

    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
     */
    function sendTemplateSMS($to,$datas,$tempId)
    {
        // 初始化REST SDK
        global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;

        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        $accountSid= '8aaf07085df473bd015e02d1e23b033a';

        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        $accountToken= '5ffb76facf5f405b8013c294de9945af';

        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID // app token = 324e315f686eb507c9235c8ff39be0bc
        $appId='8aaf07085df473bd015e02d49e230340';

        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        $serverIP='app.cloopen.com';

        //请求端口，生产环境和沙盒环境一致
        $serverPort='8883';

        //REST版本号，在官网文档REST介绍中获得。
        $softVersion='2013-12-26';

        $rest = new CCPRestSmsSDK($serverIP,$serverPort,$softVersion);
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);

        // 发送模板短信
        $data = array();
        $result = $rest->sendTemplateSMS($to,$datas,$tempId);
        if($result == NULL ) {
            return array("result" => "result error!");
        }
        if($result->statusCode!=0) {
            //TODO 添加错误处理逻辑
            $data['result'] = 'fail';
            $data['error'] = $result->statusMsg[0];
        }else{
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
            //TODO 添加成功处理逻辑
            $data['result'] = 'success';
            $data['code'] = $smsmessage;
        }
        return $data;
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

            $providerinfo = $this->user_model->getProviderInfoFromTblUser( $this->global['login_id']);
            //add pay status message to message list
            $news_data = array(
                'sender' => $providerinfo->id,
                'receiver' => 0,
                'type' => '提现申请消息',
                'message' => '您有个提现申请，请及时处理。'
            );
            $this->news_model->add($news_data);
            // plus balance
            $userinfo = array(
                'balance' => number_format(floatval($providerinfo->balance) - floatval($result['money']), 2, '.','')
            );
            $this->shop_model->update($userinfo, $providerinfo->userid);

            $result['provider_id'] = $providerinfo->id;
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