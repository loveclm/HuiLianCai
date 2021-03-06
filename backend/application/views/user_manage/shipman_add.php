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
                <form role="form" id="addShipman" action="<?php echo base_url() ?>shipman_add" method="post">
                    <input name="ship_id" value="<?php echo isset($id) ? $id : ''; ?>" type="hidden">
                    <div class="row form-inline">
                        <label> *账号 : </label>
                        <div class="input-group margin">
                            <input name="userid" type="text" id="userid" class="form-control"
                                   value="<?php echo isset($model->userid) ? $model->userid : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" <?php echo (isset($id) && $id !='') ? 'disabled' : ''; ?>/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *名称 : </label>

                        <div class="input-group margin">
                            <input name="username" type="text" id="username" class="form-control"
                                   value="<?php echo isset($model->username) ? $model->username : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <label> *联系电话 : </label>

                        <div class="input-group margin">
                            <input name="contact_phone" type="text" id="contact_phone" class="form-control"
                                   value="<?php echo isset($model->contact_phone) ? $model->contact_phone : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <?php
                    if((isset($id) && $id != '')== FALSE) {
                        ?>
                        <div class="row form-inline">
                            <label> *密码 : </label>

                            <div class="input-group margin">
                                <input name="password" type="text" id="password" class="form-control"
                                       value="" style="margin: 0 ; padding: 0px 20px;"/>
                            </div>
                        </div>
                        <div class="row form-inline">
                            <label> *确认密码 : </label>

                            <div class="input-group margin">
                                <input name="confirm_password" type="text" id="confirm_password" class="form-control"
                                       value="" style="margin: 0 ; padding: 0px 20px;"/>
                            </div>
                        </div>
                        <div class="row form-inline">
                            <label> *头像 : </label>

                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $product_logo = isset($model->more_data) ? $model->more_data : 'assets/images/user_logo.png';
                                ?>
                                <img id="product_logo_image" src="<?= base_url() . $product_logo; ?>"
                                     alt="user image" class="online"
                                     style="height: 50px; width:50px;border-radius: 50%;"><br>
                                <input id="upload_product_logo" type="file" style="display: none"/>
                                <input name="cover" id="product_logo_src" type="text" style="display: none;"
                                       value='<?= $product_logo; ?>'>
                                <span id="product_logo_filename" style="display: none">图片上传中...</span>
                            </div>
                            <a class="btn btn-primary" href="#" onclick="$('#upload_product_logo').click();"
                               style="vertical-align: text-top;">
                                <span>*上传</span>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row" style="padding-left: 200px;">
                        <div class="col-xs-12 col-sm-12 form-inline">
                            <a class="btn btn-default form-control" href="<?php echo base_url(); ?>shipman_manage">
                                <span>取消</span>
                            </a>
                            <input class="btn btn-primary form-control" type="submit" value="提交">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/shipping.js" charset="utf-8"></script>
