<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class product_format_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_format_model');
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
            $this->global['pageTitle'] = '商品列表';
            $this->global['pageName'] = 'product_format';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();
            $this->global['brandlist'] = $this->product_util_model->getProductBrandList();
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchKind'] = '0';
            $data['searchBrand'] = '0';

            $this->loadViews("product_manage/product_format", $this->global, $data, NULL);
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
                        $header = array("序号", "商品条码", "封面", "商品名称", "分类",
                            "品牌", "单位", "操作");
                        $cols = 8;
                        $contentList = $this->product_format_model->getItems($searchData);
                        $footer = (count($contentList) == 0 || !isset($contentList)) ? $footer = "没有商品." : '';
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
                    $output_html .= '<td>' . $item->barcode . '</td>';
                    $output_html .= '<td><img src="' . base_url() . json_decode($item->cover)[1] .
                        '" style="width:150px;height:80px;border:2px solid lightgray;"/></td>';
                    $output_html .= '<td>' . $item->name . '</td>';
                    $output_html .= '<td>' . $item->type_name . '</td>';
                    $output_html .= '<td>' . $item->brand_name . '</td>';
                    $output_html .= '<td>' . $item->unit_name . '</td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="'. base_url(). 'product_format_show/' . $item->id . '">查看 &nbsp;&nbsp;</a>';
                    $output_html .= '<a href="'. base_url(). 'product_format_edit/' . $item->id . '">编辑 &nbsp;&nbsp;</a>';

                    if($this->product_format_model->isDeletable($item->id))
                        $output_html .= '<a href="#" onclick="deleteConfirm(\'' . $item->id . '\')">删除 &nbsp;&nbsp;</a>';
                    else
                        $output_html .= '<a href="#" onclick="$(\'#alert_delete\').show();" style="color: grey;">删除 &nbsp;&nbsp;</a>';

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
            $this->global['pageTitle'] = '新增商品';
            $this->global['pageName'] = 'product_format_add';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();
            $this->global['brandlist'] = $this->product_util_model->getProductBrandList();
            $this->global['unitlist'] = $this->product_util_model->getProductUnitList();

            if(empty($_POST)){
                $data['empty'] = NULL;
                $this->loadViews("product_manage/product_format_add", $this->global, $data, NULL);

            }else{
                $this->product_format_validate();
            }
        }
    }

    function product_format_validate(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('barcode', '商品条码', 'trim|required|exact_length[13]');
        $this->form_validation->set_rules('product_name', '商品名称', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('type', '分类', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('standard', '规格型号', 'trim|required|max_length[15]');
        $this->form_validation->set_rules('brand', '品牌', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('unit', '单位', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('contents', '商品详情', 'trim|required');

        $id = $this->input->post('id');
        $product_format = new stdClass();
        $product_format->barcode = $this->input->post('barcode');
        $product_format->name = $this->input->post('product_name');
        $product_format->type = $this->input->post('type');
        $product_format->standard = $this->input->post('standard');
        $product_format->brand = $this->input->post('brand');
        $product_format->unit = $this->input->post('unit');
        $product_format->cover = $this->input->post('cover');
        $product_format->contents = $this->input->post('contents');

        $brand_count = $this->input->post('image_count');
        $brand = array();
        for($i = 1 ; $i <= $brand_count; $i++) {
            array_push($brand, json_decode($this->input->post('brand'.$i)));
        }
        $product_format->images = json_encode($brand);
        if ($this->form_validation->run() == FALSE) {
            $this->global['product_format'] = $product_format;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("product_manage/product_format_add", $this->global, $data, NULL);
        } else {

            $tmp = json_decode($product_format->cover);
            if( $tmp[0] == '' || $brand_count == 0){
                $this->form_validation->set_rules('image_count', '请上传图片', 'user_error');
                $this->form_validation->run();

                $this->global['product_format'] = $product_format;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("product_manage/product_format_add", $this->global, $data, NULL);
                return;
            }
            if($this->product_format_model->isValidBarcode($product_format->barcode, $id) == false){
                $this->form_validation->set_rules('barcode', '该商品条码已存在', 'user_error');
                $this->form_validation->run();

                $this->global['product_format'] = $product_format;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("product_manage/product_format_add", $this->global, $data, NULL);
                return;
            }
            if($this->product_format_model->isValidProductName($product_format->name, $id) == false){
                $this->form_validation->set_rules('barcode', '该商品名称已存在', 'user_error');
                $this->form_validation->run();

                $this->global['product_format'] = $product_format;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("product_manage/product_format_add", $this->global, $data, NULL);
                return;
            }

            if(strpos($product_format->cover, 'hlc') === false || $brand_count == 0) {
                $this->form_validation->set_rules('barcode', '请上传图片', 'user_error');
                $this->form_validation->run();

                $this->global['product_format'] = $product_format;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("product_manage/product_format_add", $this->global, $data, NULL);
                return;
            }

            $product = array(
                'barcode' => $product_format->barcode,
                'name' => $product_format->name,
                'type' => $product_format->type,
                'standard' => $product_format->standard,
                'brand' => $product_format->brand,
                'unit' => $product_format->unit,
                'cover' => $product_format->cover,
                'images' => $product_format->images,
                'contents' => $product_format->contents,
            );
            $this->product_format_model->addNewProduct($product);
            redirect('product_format');
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
            echo $this->product_format_model->add($result);
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
            echo $this->product_format_model->delete($result);
        }
    }

    /**
     * This function is used to edit the user information
     */
    function edititem($Id)
    {
        if ($this->isAdmin() == TRUE ) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = '编辑商品';
            $this->global['pageName'] = 'product_format_add';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();
            $this->global['unitlist'] = $this->product_util_model->getProductUnitList();
            $data['empty'] = NULL;

            $this->global['product_format'] = $this->product_format_model->getItemById($Id);
            $this->global['brandlist'] =$this->product_util_model->getProductBrandList($this->global['product_format']->type);
            $this->loadViews("product_manage/product_format_add", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = '编辑商品';
            $this->global['pageName'] = 'product_format_detail';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();
            $this->global['unitlist'] = $this->product_util_model->getProductUnitList();
            $data['empty'] = NULL;

            $this->global['product_format'] = $this->product_format_model->getItemById($Id);
            $this->global['brandlist'] =$this->product_util_model->getProductBrandList($this->global['product_format']->type);

            $this->loadViews("product_manage/product_format_detail", $this->global, $data, NULL);
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