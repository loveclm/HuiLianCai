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
                <form role="form" id="addproduct" action="<?php echo base_url() ?>product_add"
                      method="post">
                    <input name="storeId" value="<?= isset($product->storeId) ? $product->storeId :'0';?>" type="hidden"/>
                    <div class="row form-inline">
                        <label> *分类 : </label>
                        <select name="type" class="form-control" id="searchKind">
                            <option value="0">请选择</option>
                            <?php
                            foreach ($typelist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($product->type))
                                        if ($product->type == $item->id) echo ' selected';
                                    ?>><?= $item->type ?></option>
                                <?php
                            }
                            ?>
                        </select>
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
                                    if (isset($product->brand))
                                        if ($product->brand == $item->id) echo ' selected';
                                    ?>><?= $item->name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> *商品名称 : </label>
                        <select name="id" class="form-control" id="product_name">
                            <option value="0">请选择</option>
                            <?php
                            foreach ($productlist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($product->id))
                                        if ($product->id == $item->id) echo ' selected';
                                    ?>><?= $item->name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> 商品条码 : </label>
                        <div class="input-group margin">
                            <span id="barcode"><?php echo isset($product->barcode) ? $product->barcode : ''; ?></span>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <label> 规格型号 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($product->standard) ? $product->standard : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *原价 : </label>
                        <div class="input-group margin">
                            <input name="cost" type="text" id="product_cost" class="form-control"
                                   value="<?php echo isset($product->cost) ? (floatval($product->cost)*100)/100 : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *库存量 : </label>
                        <div class="input-group margin">
                            <input name="store" type="text" id="product_store" class="form-control"
                                   value="<?php echo isset($product->store) ? $product->store : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <label> 单位 :</label>
                        <div class="input-group margin">
                            <span id="unit"><?= isset($product->unit_name)? $product->unit_name : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 商品封面 : </label>
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_logo = isset($product->cover) ? json_decode($product->cover) : ['', 'assets/images/picture.png'];
                                ?>
                                <img id="product_logo_image" src="<?= base_url() . $product_logo[1]; ?>"
                                     alt="user image" class="online"
                                     style="height: 200px; width:300px; padding: 20px; padding-bottom:2px;""><br>
                            </div>
                    </div>
                    <div class="row form-inline">
                        <label> 商品图片 : </label>
                        <div id="product_imgs_content" class="input-group margin" style="width: 85%; left:80px;">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_img_list = isset($product->images) ? json_decode($product->images) : [];
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
                        <label> 商品详情 : </label>
                        <div id="contents" class="form-group" style="vertical-align: text-top; background-color: white; padding: 10px; border: 1px solid lightgrey; width: 400px;">
                            <?= isset($product->contents) ? $product->contents : ''; ?>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>product">
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
        <div id="alert_delete" class="modal-dialog text-center" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="$('#confirm_delete').hide();">
                        <span aria-hidden="true">×</span></button>
                    <span class="modal-title">提示</span>
                </div>
                <div class="modal-body">
                    <label>该商品已经存在，请在商品列表中点击编辑按钮对商品数据进行编辑。</label><br><br>
                    <a href="#" class="btn btn-primary" onclick="$('#alert_delete').hide();">确定</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>

    </section>
</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/product.js"
        charset="utf-8"></script>

