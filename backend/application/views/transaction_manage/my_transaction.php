
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
                <label id="balance" style="color: red; font-size: 20px;">
                    ￥<span style="font-size: 40px;"><?= isset($balance) ? number_format((float)$balance, 2,'.',''): '0.00'?></span>
                </label>
                <a href="#" onclick="" style="margin: 60px;vertical-align: super">申请提现</a>
            </div>

            <div id="donate">
                <label><input type="radio" id="1" name="toggleButton" checked><span>提现记录</span></label>
                <label><input type="radio" id="2" name="toggleButton"><span>线上付款记录</span></label>
                <label><input type="radio" id="3" name="toggleButton"><span>货到付款记录</span></label>
                <input id="btnIndex" value="2" type="hidden">
            </div>
            <div id="searchTool" class="row">

            </div>
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
    </section>

</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/transaction_manage/my_transaction.js" charset="utf-8"></script>
