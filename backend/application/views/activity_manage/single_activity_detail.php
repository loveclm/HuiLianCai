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
                <form role="form">
                    <div class="row form-inline">
                        <label> 活动编号 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->activity_id) ? $model->activity_id : ''; ?></span>
                            <?php
                            if(isset($model->status)){
                                switch ($model->status) {
                                    case 1:
                                        $Status = '未开始';
                                        break;
                                    case 2:
                                        $Status = '拼单中';
                                        break;
                                    case 3:
                                        $Status = '拼单成功';
                                        break;
                                    case 4:
                                        $Status = '拼单失败';
                                        break;
                                }
                            }else{
                                $Status = '';
                            }
                            ?>
                            <span style="color: dodgerblue">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?= $Status; ?></span>
                        </div>
                    </div>
                    <?php
                    if($shop_manager_number == '') {
                        ?>
                        <div class="row form-inline">
                            <label> 所属供货商 : </label>
                            <div class="input-group margin">
                                <span><?php echo isset($model->provider_name) ? $model->provider_name : ''; ?></span>
                            </div>
                        </div>
                        <div class="row form-inline">
                            <label> 供货商账号 : </label>
                            <div class="input-group margin">
                                <span><?php echo isset($model->provider_userid) ? $model->provider_userid : ''; ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row form-inline">
                        <label> *活动名称 : </label>
                        <div class="input-group margin">
                            <input name="activity_name" type="text" id="activity_name" class="form-control"
                                   value="<?php echo isset($model->activity_name) ? $model->activity_name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" disabled/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 拼团开始时间 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->start_time) ? $model->start_time : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 拼团结束时间 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->end_time) ? $model->end_time : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *拼单人数 : </label>
                        <div class="input-group margin">
                            <input name="man_cnt" type="text" id="man_cnt" class="form-control"
                                   value="<?php echo isset($model->man_cnt) ? $model->man_cnt : '2'; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" disabled/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *起团数量 : </label>
                        <div class="input-group margin">
                            <input name="group_cnt" type="text" id="group_cnt" class="form-control"
                                   value="<?php echo isset($model->group_cnt) ? $model->group_cnt : '2'; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" disabled/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *拼团价 : </label>
                        <div class="input-group margin">
                            <input name="group_cost" type="text" id="group_cost" class="form-control"
                                   value="<?php echo isset($model->group_cost) ? number_format((float)$model->group_cost, 2,'.','') : '0.00'; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" disabled/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 商品条码 : </label>
                        <div class="input-group margin">
                            <span id="barcode"><?php echo isset($model->barcode) ? $model->barcode : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *商品名称 : </label>
                        <select name="id" class="form-control" id="product_name" disabled>
                            <option value="0">请选择</option>
                            <?php
                            foreach ($datalist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($model) && isset($model->storeId))
                                        if ($model->storeId == $item->id) echo ' selected';
                                    ?>><?= $item->name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> *分类 : </label>
                        <select name="type" class="form-control" id="searchKind" disabled>
                            <option value="0">请选择</option>
                            <?php
                            foreach ($typelist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($model->type))
                                        if ($model->type == $item->id) echo ' selected';
                                    ?>><?= $item->type ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> 规格型号 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->standard) ? $model->standard : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *品牌 : </label>
                        <select name="brand" class="form-control" id="searchBrand" disabled>
                            <option value="0">请选择</option>
                            <?php
                            foreach ($brandlist as $item) {
                                ?>
                                <option value="<?= $item->id ?>"
                                    <?php
                                    if (isset($model->brand))
                                        if ($model->brand == $item->id) echo ' selected';
                                    ?>><?= $item->name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row form-inline">
                        <label> 原价 : </label>
                        <div class="input-group margin">
                            <span id="cost"><?= isset($model->cost) ? number_format((float)$model->cost, 2,'.','') : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 库存量 : </label>
                        <div class="input-group margin">
                            <span id="store"><?= isset($model->store) ? $model->store : ''; ?></span>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <label> 单位 :</label>
                        <div class="input-group margin">
                            <span id="unit"><?= isset($model->unit_name) ? $model->unit_name : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 商品封面 : </label>
                        <div class="form-group text-center" style="padding: 0px 20px;">
                            <?php
                            $model_logo = isset($model->cover) ? json_decode($model->cover) : ['', 'assets/images/logo.png'];
                            ?>
                            <img id="product_logo_image" src="<?= base_url() . $model_logo[1]; ?>"
                                 alt="user image" class="online"
                                 style="height: 200px; width:300px; padding: 20px; padding-bottom:2px;""><br>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 商品图片 : </label>
                        <div id="product_imgs_content" class="input-group margin" style="width: 85%; left:80px;">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_img_list = isset($model->images) ? json_decode($model->images) : [];
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
                        <div id="contents" class="form-group"
                             style="vertical-align: text-top; background-color: white; padding: 20px; border: 1px solid lightgrey">
                            <?= isset($model->contents) ? $model->contents : ''; ?>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 新增活动时间 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->added_time) ? $model->added_time : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 实际结束时间 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->end_time) ? $model->end_time : ''; ?></span>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>single_activity">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/activity_manage/single_activity.js"
        charset="utf-8"></script>

