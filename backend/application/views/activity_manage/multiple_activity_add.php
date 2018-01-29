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
    .fr-box {
        width: 400px;
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
                <form role="form" action="<?php echo base_url() ?>multiple_activity_add"
                      method="post">
                    <input id="activity_id" name="activity_id" value="<?= isset($model->activity_id) ? $model->activity_id : '0'; ?>"
                           type="hidden"/>
                    <div class="row form-inline">
                        <label> *活动名称 : </label>
                        <div class="input-group margin">
                            <input name="activity_name" type="text" id="activity_name" class="form-control"
                                   value="<?php echo isset($model->activity_name) ? $model->activity_name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px; width: 400px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *拼团开始时间 : </label>
                        <div class="input-group date form_datetime margin"
                             data-date="<?php echo isset($model->start_time) ? $model->start_time : $start_time; ?>"
                             data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input1">
                            <input name="start_time" class="form-control" size="16" type="text"
                                   value="<?php echo isset($model->start_time) ? $model->start_time : $start_time; ?>"
                                   readonly="" style="padding: 0px 20px;margin: 0px; width: 360px;"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>

                    </div>
                    <div class="row form-inline">
                        <label> *拼团结束时间 : </label>

                        <div class="input-group date form_datetime margin"
                             data-date="<?php echo isset($model->end_time) ? $model->end_time : $end_time; ?>"
                             data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input1">
                            <input name="end_time" class="form-control" size="16" type="text"
                                   value="<?php echo isset($model->end_time) ? $model->end_time : $end_time; ?>" readonly=""
                                   style="padding: 0px 20px;margin: 0px; width: 360px;"/>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>

                    </div>
                    <div class="row form-inline">
                        <label> *拼单人数 : </label>
                        <div class="input-group margin">
                            <input name="man_cnt" type="text" id="man_cnt" class="form-control"
                                   value="<?php echo isset($model->man_cnt) ? $model->man_cnt : '2'; ?>"
                                   style="margin: 0 ; padding: 0px 20px; width: 380px;"/><h5 style="margin-top: 10px;">人</h5>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *起团数量 : </label>
                        <div class="input-group margin">
                            <input name="group_cnt" type="text" id="group_cnt" class="form-control"
                                   value="<?php echo isset($model->group_cnt) ? $model->group_cnt : '2'; ?>"
                                   style="margin: 0 ; padding: 0px 20px; width: 380px;"/>
                        </div>
                    </div>
                    <div class="row form-inline" style="padding-left: 75px;">
                        <input onclick="selectProduct();" type="button" value="选择单品"/><br>
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
                            <span id="origin_cost"><?= isset($model->origin_cost) ? number_format((float)$model->origin_cost, 2,'.','') : '0.00'; ?>
                                </span>元
                            <input name="origin_cost" id="origin_cost_input" value="<?php echo isset($model->origin_cost) ? $model->origin_cost : '0.00'; ?>" type="hidden">
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 拼团价 : </label>
                        <div class="input-group margin">
                            <span name="group_cost" id="group_cost"><?php echo isset($model->group_cost) ? number_format((float)$model->group_cost,2,'.','') : '0.00'; ?>
                                </span>元
                            <input name="group_cost" id="group_cost_input" value="<?php echo isset($model->group_cost) ? $model->group_cost : '0.00'; ?>" type="hidden">
                        </div>
                    </div>
                    <?php
                    $more_data = json_decode(isset($model->more_data) ? $model->more_data : null);
                    ?>
                    <div class="row form-inline">
                        <label> *套餐封面 : </label>

                        <div class="form-group text-center" style="padding: 0px 20px;">
                            <?php
                            $product_logo = isset($more_data->cover) ? json_decode($more_data->cover) : ['', 'assets/images/picture.png'];
                            ?>
                            <img id="product_logo_image" src="<?= base_url() . $product_logo[1]; ?>"
                                 alt="user image" class="online"
                                 style="height: 200px; width:300px; padding: 20px; padding-bottom:2px;""><br>
                            <input id="upload_product_logo" type="file" style="display: none"/>
                            <input name="cover" id="product_logo_src" type="text" style="display: none"
                                   value='<?=  json_encode($product_logo); ?>'>
                            <span id="product_logo_filename" style="display: none;"><?= $product_logo[0] ?></span>
                        </div>
                        <a class="btn btn-primary" href="#" onclick="$('#upload_product_logo').click();">
                            <span>*上传</span>
                        </a>
                    </div>
                    <div class="row form-inline">
                        <label> *套餐图片 : </label>

                        <div id="product_imgs_content" class="input-group margin" style="width: 85%; left:80px;">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_img_list = isset($more_data->images) ? json_decode($more_data->images) : [];
                                $i = 0;
                                foreach ($product_img_list as $product_img) {
                                    $i++;
                                    ?>
                                    <div class="product_imgs" id="product_imgs<?= $i; ?>" style="float: left;position: relative;">
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
                                        <span id="<?= 'product_imgs' . $i . '_filename' ?>" style="display: none;"><?= $product_img[0] ?></span>
                                        <div class="item_group">
                                            <div class="close_item"  onclick="delete_image(<?= $i; ?>)">
                                                <i class="fa fa-fw fa-close"></i></div>
                                            <span class="modify_item" onclick="ModifyImage(<?= $i; ?>)">修改</span>
                                        </div>
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
                        <label> *套餐详情 : </label>
                        <textarea name="contents"><?= isset($more_data->contents) ? $more_data->contents : ''; ?></textarea>
                    </div>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>multiple_activity">
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
        <!-- /.modal-dialog -->
        <div id="confirm_deploy" class="modal-dialog text-center" style="display: none; width: 800px;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0090bf;">
                    <span class="modal-title">选择商品</span>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 10px">
                        <div class="box main-shadow">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <thead id="dlg_header_tbl"></thead>
                                    <tbody id="dlg_content_tbl"></tbody>
                                    <tfoot id="dlg_footer_tbl"></tfoot>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div><br>
                    <a href="#" class="btn btn-default" onclick="$('#confirm_deploy').hide();">取消</a>
                    <a href="#" class="btn btn-primary" onclick="setProduts();">确定</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </section>
</div>


<!-- Course Management JS-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/editor/js/froala_editor.min.js"></script>
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
    $(function () {
        $('textarea').froalaEditor({
            tabSpaces: 4,
            language: 'zh_cn',
            imageUploadURL: '<?php echo base_url()?>upload_image.php'
        })
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/activity_manage/multiple_activity.js"
        charset="utf-8"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "userListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>

