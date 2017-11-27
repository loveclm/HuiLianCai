<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Shop (ShopController)
 * Area Class to control all shop related operations.
 * @author : The jin hu
 * @version : 1.0
 * @since : 12 August 2017
 */
class product_controller extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
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
            $this->global['pageTitle'] = '区域总代理商品列表';
            $this->global['pageName'] = 'product';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();
            $this->global['brandlist'] = $this->product_util_model->getProductBrandList();
            $data['searchType'] = '0';
            $data['searchName'] = '';
            $data['searchKind'] = '0';
            $data['searchBrand'] = '0';

            $this->loadViews("product_manage/product", $this->global, $data, NULL);
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
                        $header = array();
                        $cols = 0;
                        if ($this->isTicketter() == TRUE) {
                            $header = array("序号", "商品条码", "商品名称", "分类", "品牌", "所属区域总代理", "库存量", "原价", "操作");
                            $cols = 9;
                        }else{
                            $header = array("序号", "商品条码", "封面", "商品名称", "分类", "品牌", "库存量", "原价", "操作");
                            $cols = 9;
                        }
                        $contentList = $this->product_model->getItems($searchData);
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
     * This function used to get brand list belong to product type
     */
    function getBrandListByTypeID(){
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );
        if(!empty($_POST)){
            $type = $_POST['type'];

            $ret['content'] = $this->product_util_model->getProductBrandList($type);
            if($ret['content'] != NULL) $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    /**
     * This function used to get brand list belong to product type
     */
    function getPorductNamesByBrand(){
        $ret = array(
            'content' => '',
            'status' => 'fail'
        );

        if(!empty($_POST)){
            $type = $_POST['type'];
            $brand = $_POST['brand'];

            $ret['content'] = $this->product_model->getProductList($type, $brand);
            if($ret['content'] != NULL) $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function getProductDetailInfo(){
        $ret = array(
            'content' => '',
            'status' => 'fail',
        );

        if(!empty($_POST)){
            $id = $_POST['id'];
            $product_format = $this->product_format_model->getItemById($id);

            if(!empty($product_format)) {
                $product_format->type_name = $this->product_util_model->getTypeNameById($product_format->type);
                $product_format->brand_name = $this->product_util_model->getBrandNameById($product_format->brand);
                $product_format->unit_name = $this->product_util_model->getUnitNameById($product_format->unit);
            }
            $ret['content'] = $product_format;
            if($ret['content'] != NULL) $ret['status'] = 'success';
            if($this->product_model->isExist($id, $this->user_model->getProviderId($this->global['login_id'])) == true) {
                $ret['status'] = 'fail';
                $ret['content'] = '';
                $ret['error'] = 1;
            }
        }
        echo json_encode($ret);
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
                    if($this->isTicketter() == FALSE){
                        $output_html .= '<td><img src="' . base_url() . json_decode($item->cover)[1] .
                            '" style="width:150px;height:80px;border:2px solid lightgray;"/></td>';
                    }
                    $output_html .= '<td>' . $item->name . '</td>';
                    $output_html .= '<td>' . $this->product_util_model->getTypeNameById($item->type) . '</td>';
                    $output_html .= '<td>' . $this->product_util_model->getBrandNameById($item->brand) . '</td>';
                    if($this->isTicketter() == TRUE) {
                        $output_html .= '<td>' . $item->provider_name . '</td>';
                    }
                    $output_html .= '<td>' . $item->store . '</td>';
                    $output_html .= '<td>' . $item->cost . '</td>';
                    $output_html .= '<td>';
                    $output_html .= '<a href="'. base_url(). 'product_show/' . $item->storeId . '">查看 &nbsp;&nbsp;</a>';
                    if($this->isTicketter() == FALSE) {
                        $output_html .= '<a href="' . base_url() . 'product_edit/' . $item->storeId . '">编辑 &nbsp;&nbsp;</a>';
                        if($this->product_model->isDeletable($item->storeId) == true)
                            $output_html .= '<a href="#" onclick="deleteConfirm(\'' . $item->storeId . '\')">删除 &nbsp;&nbsp;</a>';
                        else
                            $output_html .= '<a href="#" onclick="$(\'#alert_delete\').show();" style="color: grey;">删除 &nbsp;&nbsp;</a>';
                    }
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
            $this->global['pageName'] = 'product_add';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();

            if(empty($_POST)){
                $data['empty'] = NULL;

                $this->loadViews("product_manage/product_add", $this->global, $data, NULL);
            }else{
                $this->product_validate();
            }
        }
    }

    function product_validate(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', '商品名称', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('type', '分类', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('brand', '品牌', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('cost', '原价', 'trim|required|numeric|greater_than[0]|max_length[8]');
        $this->form_validation->set_rules('store', '库存量', 'trim|required|numeric|max_length[8]');

        $product = new stdClass();
        $product->storeId = $this->input->post('storeId');
        $product->type = $this->input->post('type');
        $product->brand = $this->input->post('brand');
        $product->id = $this->input->post('id');
        $product->cost = $this->input->post('cost');
        $product->store = $this->input->post('store');

        if ($this->form_validation->run() == FALSE) {
            if($product->type != 0){
                $this->global['brandlist'] = $this->product_util_model->getProductBrandList($product->type);
            }
            if($product->brand != 0){
                $this->global['productlist'] = $this->product_model->getProductList($product->type, $product->brand);
            }
            if($product->id != '0'){
                $productinfo = $this->product_format_model->getItemById($product->id);
                $productinfo->unit_name = $this->product_util_model->getUnitNameById($productinfo->unit);
                $productinfo->cost = $product->cost;
                $productinfo->store = $product->store;
            }else {
                $productinfo = $product;

            }

            $this->global['product'] = $productinfo;
            // the user list that manages site
            $data['empty'] = NULL;

            $this->loadViews("product_manage/product_add", $this->global, $data, NULL);
        }else {

            if($this->product_model->isExist($product->id) == true){
                $this->form_validation->set_rules('id', '该商品已经存在', 'user_error');
                $this->form_validation->run();

                if($product->type != 0){
                    $this->global['brandlist'] = $this->product_util_model->getProductBrandList($product->type);
                }
                if($product->brand != 0){
                    $this->global['productlist'] = $this->product_model->getProductList($product->type, $product->brand);
                }
                if($product->id != '0'){
                    $productinfo = $this->product_format_model->getItemById($product->id);
                    $productinfo->unit_name = $this->product_util_model->getUnitNameById($productinfo->unit);
                    $productinfo->cost = $product->cost;
                    $productinfo->store = $product->store;
                }else {
                    $productinfo = $product;

                }

                $this->global['product'] = $productinfo;
                // the user list that manages site
                $data['empty'] = NULL;

                $this->loadViews("product_manage/product_add", $this->global, $data, NULL);
            }

            $product = array(
                'id' => $product->storeId,
                'product_id' => $product->id,
                'cost' => $product->cost,
                'store' => $product->store,
                'provider_id' => $this->global['shop_manager_number']
            );
            $this->product_model->addNewProduct($product);
            redirect('product');
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
            echo $this->product_model->add($result);
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
            echo $this->product_model->delete($result);
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
            $this->global['pageName'] = 'product_add';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();
            $data['empty'] = NULL;

            $product = $this->getProductInfo($Id);
            $this->global['brandlist'] = $this->product_util_model->getProductBrandList($product->type);
            $this->global['productlist'] = $this->product_model->getProductList($product->type, $product->brand);
            $this->global['product'] = $product;
            $this->loadViews("product_manage/product_add", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = '商品详情';
            $this->global['pageName'] = 'product_detail';
            $this->global['typelist'] = $this->product_util_model->getProductTypeList();
            $data['empty'] = NULL;

            $product = $this->getProductInfo($Id);

            $this->global['product'] = $product;
            $this->loadViews("product_manage/product_detail", $this->global, $data, NULL);
        }
    }

    function getProductInfo($Id){
        $productinfo = $this->product_model->getItemById($Id);
        if($productinfo == NULL) return $productinfo;

        $productinfo->type_name = $this->product_util_model->getTypeNameById($productinfo->type);
        $productinfo->brand_name = $this->product_util_model->getBrandNameById($productinfo->brand);
        $productinfo->unit_name = $this->product_util_model->getUnitNameById($productinfo->unit);

        return $productinfo;
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
            $this->global['pageName'] = 'product_upload';
            $data['ret_Url'] = 'product_edit';

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