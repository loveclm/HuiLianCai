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
                            <span><?php echo isset($model->activity_name) ? $model->activity_name : ''; ?></span>
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
                        <label> 拼单人数 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->man_cnt) ? $model->man_cnt : ''; ?> 人</span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 起团数量 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->group_cnt) ? $model->group_cnt : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline" style="padding-left: 75px;">
                        <input name="product_ids" id="product_list" value="<?php echo isset($model->product_id) ? $model->product_id : ''; ?>" type="hidden"/>
                        <input name="buy_cnt" id="buy_cnt" value='<?php echo isset($model->buy_cnt) ? $model->buy_cnt : "[]"; ?>' type="hidden"/>
                        <div class="row">
                            <div class="box main-shadow">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead id="header_tbl"></thead>
                                        <tbody id="content_tbl"></tbody>
                                        <tfoot id="footer_tbl"></tfoot>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 活动原价 : </label>
                        <div class="input-group margin">
                            <span><?= isset($model) ? $model->origin_cost : '0.00'; ?>元</span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 拼团价 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model) ? $model->group_cost: '0.00'; ?>元</span>
                        </div>
                    </div>
                    <?php
                    $more_data = json_decode(isset($model->more_data) ? $model->more_data : null);
                    ?>
                    <div class="row form-inline">
                        <label> 套餐封面 : </label>

                        <div class="form-group text-center" style="padding: 0px 20px;">
                            <?php
                            $product_logo = isset($more_data->cover) ? json_decode($more_data->cover) : ['', 'assets/images/picture.png'];
                            ?>
                            <img id="product_logo_image" src="<?= base_url() . $product_logo[1]; ?>"
                                 alt="user image" class="online"
                                 style="height: 200px; width:300px; padding: 20px; padding-bottom:2px;""><br>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 套餐图片 : </label>

                        <div id="product_imgs_content" class="input-group margin" style="width: 85%; left:80px;">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_img_list = isset($more_data->images) ? json_decode($more_data->images) : [];
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
                        <label> 套餐详情 : </label>
                        <div id="contents" class="form-group"
                             style="vertical-align: text-top; background-color: white; padding: 20px; border: 1px solid lightgrey">
                            <?= isset($more_data->contents) ? $more_data->contents : ''; ?>
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
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>multiple_activity">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/activity_manage/multiple_activity.js"
        charset="utf-8"></script>

