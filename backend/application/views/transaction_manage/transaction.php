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
                <div class="col-xs-12 col-sm-4 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchType">
                            <option value="0" <?php if ($searchType == 0) echo ' selected' ?>>终端便利店账号</option>
                            <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>订单号</option>
                        </select>
                        <input type="text" id="searchName" style="width: 140px"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 form-inline" style="margin-top: 0px;">
                    <div class="form-group">
                        <span> 交易时间 </span>
                        <input id="start_time" class="datepicker-inline" size="16" type="text" value="" readonly=""
                               style="padding: 0px 20px;margin: 10px;width: 150px; height: 35px;">

                        <span> 至 </span>
                        <input id="end_time" class="datepicker-inline" size="16" type="text" value="" readonly=""
                               style="padding: 0px 20px;margin: 10px; width: 150px; height: 35px;">
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


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/transaction_manage/transaction.js"
        charset="utf-8"></script>
<script type="text/javascript">
    $(function () {
        $(".datepicker-inline").datepicker({
            format: 'yyyy-mm-dd'
        });
    });
</script>