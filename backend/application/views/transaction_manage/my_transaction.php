<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <label>我的余额</label><br>
                <label id="balance" style="color: red; font-size: 20px; padding-left: 100px;">
                    ￥<span style="font-size: 40px;"><?= isset($balance) ? number_format((float)$balance, 2, '.', '') : '0.00' ?></span>
                </label>
                <?php
                if ($bankinfo_id == '') {
                    ?>
                    <a href="<?= base_url() ?>bankinfo_add" style="margin: 60px;vertical-align: super">申请提现</a>
                    <a href="<?= base_url() ?>bankinfo_add" style="margin: 30px;vertical-align: super; float: right">绑定银行卡</a>
                    <?php
                } else {
                    ?>
                    <a href="#" onclick="deployConfirm('<?= $login_id;?>', '<?= $balance;?>')"
                       style="margin: 60px;vertical-align: super">申请提现</a>
                    <a href="<?= base_url() ?>bankinfo_show/<?= $bankinfo_id; ?>"
                       style="margin: 30px;vertical-align: super; float: right">查看银行卡</a>
                    <?php
                }
                ?>
            </div>
            <input name="bankinfo_id" value="<?= $bankinfo_id; ?>" type="hidden"/>
            <div id="donate" class="row" style="margin-left: 20px;">
                <label><input type="radio" id="1" name="toggleButton" checked><span>提现记录</span></label>
                <label><input type="radio" id="2" name="toggleButton"><span>线上付款记录</span></label>
                <label><input type="radio" id="3" name="toggleButton"><span>货到付款记录</span></label>
                <input id="btnIndex" value="2" type="hidden">
            </div><br>
            <div id="searchTool" class="row">
            </div>
            <div class="row" style="max-height: 700px; overflow-y: auto;">
                <div class="box main-shadow">
                    <div class="box-body table-responsive no-padding">
                        <table id="contentInfo_tbl" class="table table-hover">
                            <thead id="header_tbl"></thead>
                            <tbody id="content_tbl"></tbody>
                            <tfoot id="footer_tbl"></tfoot>
                        </table>
                        <div id="contentpageNavPosition"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div id="confirm_delete" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_delete').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提现申请</span>
                    </div>
                    <div class="modal-body">
                        <img src="<?= base_url() ?>assets/images/success.png"><br><br>
                        <label>申请成功</label><br><br>
                        <label>1-7个工作日给您银行卡转账，请耐心等待</label><br><br>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

            <input id="item_id" value="" style="display: none;"/>
            <input id="item_status" value="" style="display: none;"/>

            <div id="confirm_deploy" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title">提现申请</span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label style="float: left; margin: 7px; margin-left: 20px;"> *银行卡号 : </label>
                            <input type="text" id="card_no" class="form-control"
                                   value="<?php echo isset($card_info) ? $card_info : ''; ?>"
                                   style="margin: 0px 10px; width: 250px;" disabled />
                        </div>
                        <div class="row">
                            <label style="float: left; margin: 7px; margin-left: 20px;"> *提现金额 : </label>
                            <input name="req_money" type="text" id="req_money" class="form-control"
                                   value=""  style="margin: 5px 10px; width: 250px;"/>
                            <label id="confirm-deploy-message">当前全部余额 <span style="color: red;"> ￥<?= $balance; ?></span></label><br><br>
                        </div>

                        <a href="#" class="btn btn-default" onclick="$('#confirm_deploy').hide()">取消</a>
                        <a href="#" class="btn btn-primary" onclick="deployItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

    </section>

</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/transaction_manage/my_transaction.js"
        charset="utf-8"></script>
