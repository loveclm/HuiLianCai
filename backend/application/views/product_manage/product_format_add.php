<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/froala_editor.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/froala_style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/code_view.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/colors.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/emoticons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/image_manager.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/image.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/line_breaker.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/table.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/char_counter.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/video.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/fullscreen.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/editor/css/plugins/file.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
<style type="text/css">
    .fr-box{
        width: 600px;
        left:180px;
    }
</style>
<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>
    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div>
                <form role="form" id="addproduct_format" action="<?php echo base_url() ?>product_format_add"
                      method="post">
                    <div class="row form-inline">
                        <label> *商品条码 : </label>
                        <div class="input-group margin">
                            <input name="barcode" type="text" id="barcode" class="form-control"
                                   value="<?php echo isset($product_format) ? $product_format->barcode : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *商品名称 : </label>
                        <div class="input-group margin">
                            <input name="product_name" type="text" id="product_name" class="form-control"
                                   value="<?php echo isset($product_format) ? $product_format->name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *分类 : </label>
                        <select name="type" class="form-control" id="searchKind">
                            <option value="0">请选择</option>
                            <?php
                            foreach ($typelist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($product_format))
                                        if ($product_format->type == $item->id) echo ' selected';
                                    ?>><?= $item->type ?></option>
                                <?php
                            }
                            ?>
                            <?php
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> *规格型号 : </label>
                        <div class="input-group margin">
                            <input name="standard" type="text" id="product_format_name" class="form-control"
                                   value="<?php echo isset($product_format) ? $product_format->standard : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *品牌 : </label>
                        <select name="brand" class="form-control" id="searchBrand">
                            <option value="0">请选择</option>
                            <?php
                            foreach ($brandlist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($product_format))
                                        if ($product_format->brand == $item->id) echo ' selected';
                                    ?>><?= $item->name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> *单位 : </label>

                        <select name="unit" class="form-control" id="searchUnit">
                            <option value="0">请选择</option>
                            <?php
                            foreach ($unitlist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($product_format))
                                        if ($product_format->unit == $item->id) echo ' selected';
                                    ?>><?= $item->name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> *商品封面 : </label>

                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_logo = isset($product_format) ? json_decode($product_format->cover) : ['', 'assets/images/picture.png'];
                                ?>
                                <img id="product_logo_image" src="<?= base_url() . $product_logo[1]; ?>"
                                     alt="user image" class="online"
                                     style="height: 200px; width:300px; padding: 20px; padding-bottom:2px;""><br>
                                <input id="upload_product_logo" type="file" style="display: none"/>
                                <input name="cover" id="product_logo_src" type="text" style="display: none"
                                       value='<?= json_encode($product_logo); ?>'>
                                <span id="product_logo_filename"><?= $product_logo[0] ?></span>
                            </div>
                            <a class="btn btn-primary" href="#" onclick="$('#upload_product_logo').click();">
                                <span>*上传</span>
                            </a>
                    </div>
                    <div class="row form-inline">
                        <label> *商品图片 : </label>

                        <div id="product_imgs_content" class="input-group margin" style="width: 85%; left:80px;">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_img_list = isset($product_format) ? json_decode($product_format->images) : [];
                                $i = 0;
                                foreach ($product_img_list as $product_img) {
                                    $i++;
                                    ?>
                                    <div class="product_imgs" style="float: left;">
                                        <img id="<?= 'product_imgs' . $i . '_image' ?>"
                                             src="<?= base_url() . $product_img[1]; ?>"
                                             onclick="<?= '$(\'#upload_product_imgs' . $i . '\').click();'; ?>"
                                             alt="user image" class="online"
                                             style="height: 130px; width:180px; padding: 20px; padding-bottom:2px;"><br>
                                        <input id="<?= 'upload_product_imgs' . $i ?>" class="upload_product_imgs"
                                               type="file" style="display: none"/>
                                        <input name="<?= 'brand' . $i; ?>" id="<?= 'product_imgs' . $i . '_src' ?>"
                                               type="text" style="display: none"
                                               value='<?= json_encode($product_img); ?>'>
                                        <span id="<?= 'product_imgs' . $i . '_filename' ?>"><?= $product_img[0] ?></span>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <input name="image_count" id="image_count" value="<?= $i; ?>"
                                   style="display: none">
                            <input id="upload_product_imgs" type="file" style="display: none"/>
                            <a class="btn btn-primary" href="#" onclick="$('#upload_product_imgs').click();"
                               style="margin-left: 80px;">
                                <span>*上传</span>
                            </a>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *商品详情 : </label>
                        <textarea name="contents"><?= isset($product_format) ? $product_format->contents : ''; ?></textarea>
                    </div>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>product_format">
                                    <span>返回</span>
                                </a>
                                <input class="btn btn-primary form-control" type="submit" value="保存">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3 col-md-offset-7" style="position: absolute; top: 125px">
            <?php
            $this->load->helper('form');
            $error = $this->session->flashdata('error');
            if ($error) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php } ?>
            <?php
            $success = $this->session->flashdata('success');
            if ($success) {
                ?>
                <div class="alert alert-success alert-dismissable" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <input type="text" value="<?php echo $this->session->flashdata('success'); ?>"
                           id="success_message" style="display:none;">
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Course Management JS-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/froala_editor.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/align.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/code_beautifier.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/code_view.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/colors.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/draggable.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/emoticons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/font_size.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/font_family.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/image.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/file.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/image_manager.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/line_breaker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/link.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/lists.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/paragraph_format.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/paragraph_style.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/video.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/table.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/url.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/entities.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/char_counter.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/inline_style.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/save.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/plugins/fullscreen.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/languages/zh_cn.js"></script>
<script>
    $(function(){
        $('textarea').froalaEditor({
            tabSpaces: 4,
            language:'zh_cn',
            imageUploadURL: 'upload_image.php'
        })
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/product_format.js"
        charset="utf-8"></script>

