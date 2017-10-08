<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div>
                <form role="form" id="addShipman" action="<?php echo base_url() ?>user_add" method="post">
                    <input name="id" value="<?php echo isset($id) ? $id : ''; ?>" type="hidden">
                    <div class="row form-inline">
                        <label> *账号 : </label>
                        <div class="input-group margin">
                            <input name="userid" type="text" id="userid" class="form-control"
                                   value="<?php echo isset($model->userid) ? $model->userid : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" <?php echo (isset($id) && $id != '') ? 'disabled' : ''; ?>/>
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

                    <?php
                    if ((isset($id) && $id != '') == FALSE) {
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
                                <input name="cpassword" type="text" id="cpassword" class="form-control"
                                       value="" style="margin: 0 ; padding: 0px 20px;"/>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row form-inline">
                        <label> *用户角色 : </label>

                        <div class="form-group margin">
                            <select name="role" class="form-control" id="role">
                                <?php
                                foreach ($roles as $item) {
                                    ?>
                                    <option value="<?= $item->id; ?>" <?= (isset($model->role) && ($model->role == $item->id)) ? 'selected' : ''; ?>><?= $item->role; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

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
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>