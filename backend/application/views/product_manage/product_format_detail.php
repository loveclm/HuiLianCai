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
                                   style="margin: 0 ; padding: 0px 20px;" disabled/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *商品名称 : </label>
                        <div class="input-group margin">
                            <input name="product_name" type="text" id="product_name" class="form-control"
                                   value="<?php echo isset($product_format) ? $product_format->name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" disabled/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *分类 : </label>
                        <select name="type" class="form-control" id="searchKind" style="margin-left: 10px;" disabled>
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
                                   style="margin: 0 ; padding: 0px 20px;" disabled/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *品牌 : </label>
                        <select name="brand" class="form-control" id="searchBrand" style="margin-left: 10px;" disabled>
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

                        <select name="unit" class="form-control" id="searchUnit" style="margin-left: 10px;" disabled>
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
                            </div>
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
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *商品详情 : </label>
                        <div class="form-group" style="vertical-align: text-top; background-color: white; padding: 10px; border: 1px solid lightgrey; width: 400px;">
                            <?= isset($product_format) ? $product_format->contents : ''; ?>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>product_format">
                                    <span>返回</span>
                                </a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/product_format.js"
        charset="utf-8"></script>

