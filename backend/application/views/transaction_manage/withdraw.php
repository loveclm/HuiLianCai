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
                <div class="col-xs-12 col-sm-10 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchType">
                            <option value="0" <?php if ($searchType== 0) echo ' selected' ?>>区域总代理账号</option>
                            <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>持卡人</option>
                        </select>
                        <input type="text" id="searchName" style="width: 140px;"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <select name="searchStatus" class="form-control" id="searchStatus" style="margin-left: 40px;">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>状态</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>提现中</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>提现成功</option>
                            <option value="3" <?php if ($searchStatus == 3) echo ' selected' ?>>提现失败</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-1 form-inline">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="showLists(1);">查询</a>
                    </div>
                </div>
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
    </section>

</div>
<script type="text/javascript">
    var data = '<?php echo isset($data)? json_encode($data): '';?>';
    var sms_error = '';
    if(data != '') sms_error = JSON.parse(data);
</script>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/transaction_manage/withdraw.js" charset="utf-8"></script>
