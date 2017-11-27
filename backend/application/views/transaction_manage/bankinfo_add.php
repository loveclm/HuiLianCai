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
                <form role="form" id="addBankinfo" action="<?php echo base_url() ?>bankinfo_add" method="post">
                    <input name="ship_id" value="<?php echo isset($id) ? $id : ''; ?>" type="hidden">
                    <div class="row form-inline">
                        <label> *银行卡开户行 : </label>
                        <div class="input-group margin">
                            <input name="bank_name" type="text" id="bank_name" class="form-control"
                                   value="<?php echo isset($model->bank_name) ? $model->bank_name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;" <?php echo (isset($id) && $id !='') ? 'disabled' : ''; ?>/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *持卡人 : </label>

                        <div class="input-group margin">
                            <input name="provider_name" type="text" id="provider_name" class="form-control"
                                   value="<?php echo isset($model->provider_name) ? $model->provider_name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>

                    <div class="row form-inline">
                        <label> *身份证号 : </label>

                        <div class="input-group margin">
                            <input name="cert_no" type="text" id="cert_no" class="form-control"
                                   value="<?php echo isset($model->cert_no) ? $model->cert_no : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *银行卡号 : </label>

                        <div class="input-group margin">
                            <input name="card_no" type="text" id="card_no" class="form-control"
                                   value="<?php echo isset($model->card_no) ? $model->card_no : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *预留手机号 : </label>

                        <div class="input-group margin">
                            <input name="reserve_mobile" type="text" id="reserve_mobile" class="form-control" placeholder="银行预留手机号"
                                   value="<?php echo isset($model->reserve_mobile) ? $model->reserve_mobile : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/>
                            <label style="width: 240px;">信息加密处理，仅用于银行验证</label>
                        </div>
                    </div>
                    <div class="row" style="padding-left: 200px;">
                        <div class="col-xs-12 col-sm-12 form-inline">
                            <a class="btn btn-default form-control" href="<?php echo base_url(); ?>showMyMoney">
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
