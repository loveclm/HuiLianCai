<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class news_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
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
            $this->global['pageTitle'] = '消息列表';
            $this->global['pageName'] = 'news';
            $data['id'] = '0';

            $this->loadViews("news", $this->global, $data, NULL);
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
                $userid = ($this->isTicketter()== FALSE) ? $this->global['shop_manager_number'] : '0';

                // get top list data in homepage
                switch ($id) {
                    case 1:
                        $header = array("序号", "消息类型", "消息内容", "状态", "发送日期", "操作");
                        $cols = 7;

                        $contentList = $this->news_model->getItems($userid);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有数据." : '';
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
                    $i++;
                    $output_html .= '<tr>';
                    $output_html .= '<td><input class="news_check" id="chk_' . $item->id . '" type="checkbox"></td>';
                    $output_html .= '<td>' . $i . '</td>';
                    $output_html .= '<td>' . $item->type . '</td>';
                    $output_html .= '<td>' . $item->message . '</td>';

                    switch ($item->status) {
                        case 0:
                            $output_html .= '<td>未读</td>';
                            break;
                        case 1:
                            $output_html .= '<td>已读</td>';
                            break;
                    }

                    $output_html .= '<td>' . $item->create_time . '</td>';

                    $output_html .= '<td>';
                    if($item->status == 0)
                        $output_html .= '<a href="#" onclick="deployConfirm(\'' . $item->id . '\')">阅读 &nbsp;&nbsp;</a>';

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
        $output_html .= '<th><input id="chk_all" type="checkbox" value="全选" onclick="if(this.checked){allchecked(1)}else{allchecked(0)} "></th>';
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

    function getItemInfo($id){
        $item = $this->news_model->getItemById($id);
        if($item == NULL) return $item;
        $item->type_name = $this->product_util_model->getTypeNameById($item->type);
        $item->brand_name = $this->product_util_model->getBrandNameById($item->brand);

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
            echo $this->news_model->add($result);
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
            echo $this->news_model->delete($result);
        }
    }

    function delete_messages(){
        if(empty($_POST['ids'] )) return;

        $result = $this->news_model->delete_messages($_POST['ids']);
        echo  $result;
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
            $this->global['pageName'] = 'news_upload';
            $data['ret_Url'] = 'news_edit';

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