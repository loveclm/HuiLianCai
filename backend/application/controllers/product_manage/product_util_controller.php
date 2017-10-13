<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class product_util_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
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

    function productUtil($type)
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            switch ($type) {
                case 1:
                    $this->global['pageTitle'] = '分类列表';
                    $this->global['pageName'] = 'product_type';

                    $this->loadViews("product_manage/product_util", $this->global, NULL, NULL);
                    break;
                case 2:
                    $this->global['pageTitle'] = '单位列表';
                    $this->global['pageName'] = 'product_unit';

                    $this->loadViews("product_manage/product_util", $this->global, NULL, NULL);
                    break;
                case 3:
                    $this->global['pageTitle'] = '品牌管理';
                    $this->global['pageName'] = 'product_brand';
                    $data['brand'] = 1;
                    $this->loadViews("product_manage/product_util", $this->global, $data, NULL);
                    break;
            }
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
                        $header = array("序号", "分类名称", "操作");
                        $cols = 3;
                        $contentList = $this->product_util_model->getProductTypeList();
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "暂无数据。" : '';
                        break; // get top1
                    case 2:
                        $header = array("序号", "单位名称", "操作");
                        $cols = 3;
                        $contentList = $this->product_util_model->getProductUnitList();
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "暂无数据。" : '';
                        break;
                    case 3:
                        $header = array("商品分类", "品牌名称", "LOGO", "操作");
                        $cols = 4;
                        $contentList = $this->product_util_model->getProductBrandListByName($searchData['searchName']);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "暂无数据。" : '';
                        break;
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
        // ????? make list id: 1-main menu
        $i = 0;
        switch ($id) {
            case 1:
                foreach ($allLists as $item) {
                    $i++;
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $i . '</td>';
                    $output_html .= '<td>' . $item->type . '</td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="#" onclick="deployConfirm(' . $item->id . ',' . $id . ')">编辑 &nbsp;&nbsp;</a>';
                    if($this->product_util_model->isDeletable($item->id, 1))
                        $output_html .= '<a href="#" onclick="deleteConfirm(' . $item->id . ',' . $id . ')">删除 &nbsp;&nbsp;</a>';
                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 2:
                foreach ($allLists as $item) {
                    $i++;
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $i . '</td>';
                    $output_html .= '<td>' . $item->name . '</td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="#" onclick="deployConfirm(' . $item->id . ',' . $id . ')">编辑 &nbsp;&nbsp;</a>';
                    if($this->product_util_model->isDeletable($item->id, 2))
                        $output_html .= '<a href="#" onclick="deleteConfirm(' . $item->id . ',' . $id . ')">删除 &nbsp;&nbsp;</a>';
                    $output_html .= '</td>';
                    $output_html .= '</tr>';
                }
                break;
            case 3:
                foreach ($allLists as $item) {
                    $i++;
                    $output_html .= '<tr>';
                    $output_html .= '<td>' . $this->product_util_model->getTypeNameById($item->type) . '</td>';
                    $output_html .= '<td>' . $item->name . '</td>';
                    $output_html .= '<td><img src="' . base_url() . $item->image .
                        '" style="width:150px;height:80px;"/></td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="' . base_url() . 'product_brand_edit/' . $item->id . '">编辑 &nbsp;&nbsp;</a>';
                    if($this->product_util_model->isDeletable($item->id, 3))
                        $output_html .= '<a href="#" onclick="deleteConfirm(' . $item->id . ',' . $id . ')">删除 &nbsp;&nbsp;</a>';
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
            $this->global['pageTitle'] = '新增品牌';
            $this->global['pageName'] = 'product_brand_add';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();

            if (empty($_POST)) {
                $this->loadViews("product_manage/product_brand_add", $this->global, NULL, NULL);
            } else {
                $this->product_format_validate();
            }
        }
    }

    function product_format_validate()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('type', '商品分类', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('product_name', '品牌名称', 'trim|required|max_length[10]');

        $product_format = new stdClass();
        $product_format->id = $this->input->post('id');
        $product_format->name = $this->input->post('product_name');
        $product_format->type = $this->input->post('type');
        $product_format->image = $this->input->post('image');

        if ($this->form_validation->run() == FALSE) {
            $this->global['product_format'] = $product_format;
            // the user list that manages site

            $this->loadViews("product_manage/product_brand_add", $this->global, NULL, NULL);
        } else {
            if($product_format->id == '0'){
                $product = array(
                    'name' => $product_format->name,
                    'type' => $product_format->type,
                    'image' => $product_format->image
                );
            }else{
                $product = array(
                    'id' => $product_format->id,
                    'name' => $product_format->name,
                    'type' => $product_format->type,
                    'image' => $product_format->image
                );
            }

            $this->product_util_model->addNewBrand($product);
            redirect('product_brand');
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
            echo $this->product_util_model->add($result);
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
            echo $this->product_util_model->delete($result);
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
            $this->global['pageTitle'] = '新增品牌';
            $this->global['pageName'] = 'product_brand_add';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();

            $this->global['product_format'] = $this->product_util_model->getBrandItemById($Id);

            $this->loadViews("product_manage/product_brand_add", $this->global, NULL, NULL);
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
            $this->global['pageName'] = 'product_format_upload';
            $data['ret_Url'] = 'product_format_edit';

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