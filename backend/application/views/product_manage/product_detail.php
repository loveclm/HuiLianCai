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
                    <div class="row form-inline">
                        <label> 商品条码 : </label>
                        <div class="input-group margin">
                            <span><?= $product->barcode; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 商品名称 : </label>
                        <div class="input-group margin">
                            <span><?= $product->name; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 分类 : </label>
                        <div class="input-group margin">
                            <span><?= $product->type_name; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 规格型号 : </label>
                        <div class="input-group margin">
                            <span><?= $product->standard; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 品牌 : </label>
                        <div class="input-group margin">
                            <span><?= $product->brand_name; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 原价 : </label>
                        <div class="input-group margin">
                            <span><?= $product->cost; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 库存量 : </label>
                        <div class="input-group margin">
                            <span><?= $product->store; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 单位 :</label>
                        <div class="input-group margin">
                            <span><?= $product->unit_name; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 商品封面 : </label>
                        <div class="form-group text-center" style="padding: 0px 20px;">
                            <?php
                            $product_logo = isset($product) ? json_decode($product->cover) : ['', 'assets/images/logo.png'];
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
                                $product_img_list = isset($product) ? json_decode($product->images) : [];
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
                        <div class="form-group" style="vertical-align: text-top; background-color: white; padding: 20px; border: 1px solid lightgrey">
                            <?= isset($product) ? $product->contents : ''; ?>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>product">
                                    <span>返回</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/product.js"
        charset="utf-8"></script>

