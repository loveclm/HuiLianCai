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
                <form role="form" action="<?php echo base_url() ?>withdraw_add"
                      method="post">
                    <input name="withdraw_id" value="<?= isset($model->withdraw_id) ? $model->withdraw_id : '0'; ?>" type="hidden"/>
                    <div class="row form-inline">
                        <label>供货商账号 : </label>
                        <div class="input-group margin">
                            <span id="barcode"><?php echo isset($model->provider_userid) ? $model->provider_userid : ''; ?></span>
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
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="radio_caro_type" value="2" <?= (isset($model->status) && ($model->status == 2))? 'checked' : '';?>>
                                    打款成功
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="radio_caro_type" value="3" <?= (isset($model->status) && ($model->status == 3))? 'checked' : '';?>>
                                    打款失败
                                </label>
                            </div>
                        </div>
                        <input name="status" id="option_status" value="<?= isset($model->status) ? $model->status : 0; ?>" type="hidden">
                    </div>
                    <div class="row form-inline">
                        <label> *备注 : </label>
                    </div>
                    <div class="row form-inline">
                            <textarea name="note" class="form-control" rows="3" placeholder=""
                                      style="margin-left: 100px; min-width: 400px; max-height: 150px; max-width:400px; "><?= isset($model->note) ? $model->note : ''; ?></textarea>
                    </div>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?>withdraw">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/transaction_manage/withdraw.js"
        charset="utf-8"></script>

