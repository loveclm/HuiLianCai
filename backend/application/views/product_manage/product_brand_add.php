<style type="text/css">
    .fr-box {
        width: 600px;
        left: 180px;
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
                <form role="form" id="add_brand" action="<?php echo base_url() ?>product_brand_add"
                      method="post">
                    <input name="id" value="<?= isset($product_format) ? $product_format->id : '0'; ?>" type="hidden">
                    <div class="row form-inline">
                        <label> *商品分类 : </label>
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
                        <label> *品牌名称 : </label>
                        <div class="input-group margin">
                            <input name="product_name" type="text" id="product_name" class="form-control"
                                   value="<?php echo isset($product_format) ? $product_format->name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 上传LOGO : </label>

                        <div class="form-group text-center" style="padding: 0px 20px;">
                            <?php
                            $product_logo = isset($product_format) ? $product_format->image : 'assets/images/logo.png';
                            ?>
                            <img id="product_logo_image" src="<?= base_url() . $product_logo; ?>"
                                 alt="user image" class="online"
                                 style="height: 130px; width:180px; padding: 20px; padding-bottom:2px;"
                                 onclick="$('#upload_product_logo').click();"><br>
                            <input id="upload_product_logo" type="file" style="display: none"/>
                            <input name="image" id="product_logo_src" type="text" style="display: none"
                                   value='<?= $product_logo; ?>'>
                            <span id="product_logo_filename" style="display: none;"><?= $product_logo ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>product_brand">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/product_util.js"
        charset="utf-8"></script>

