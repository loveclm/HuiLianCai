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
                        <label>供货商账号 : </label>
                        <div class="input-group margin">
                            <span id="barcode"><?php echo isset($model->provider_userid) ? $model->provider_userid : ''; ?></span>
                            <?php
                            if (isset($model->status)) {
                                switch ($model->status) {
                                    case 1:
                                        $Status = '提现中';
                                        break;
                                    case 2:
                                        $Status = '提现成功';
                                        break;
                                    case 3:
                                        $Status = '提现失败';
                                        break;
                                }
                            } else {
                                $Status = '';
                            }
                            ?>
                            <span style="color: white; background-color: red; margin-left: 50px; padding: 5px;"><?= $Status; ?></span>
                        </div>

                    </div>

                    <div class="row form-inline">
                        <label> 银行开户行 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->bank_name) ? $model->bank_name : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 持卡人 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->provider_name) ? $model->provider_name : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 身份证号 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->cert_no) ? $model->cert_no : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 银行卡号 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->card_no) ? $model->card_no : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 银行预留手机号 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->reserve_mobile) ? $model->reserve_mobile : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 申请金额 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->money) ? number_format((float)$model->money,2,'.','') : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 申请时间 : </label>
                        <div class="input-group margin">
                            <span id="standard"><?php echo isset($model->time) ? $model->time : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 备注 : </label>
                        <div class="input-group margin">
                            <span ><?php echo isset($model->note) ? $model->note : ''; ?></span>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/transaction_manage/withdraw.js"
        charset="utf-8"></script>

