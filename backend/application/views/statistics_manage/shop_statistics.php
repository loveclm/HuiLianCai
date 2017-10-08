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
                <div class="col-xs-12 col-sm-9 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchType">
                            <option value="0">终端便利店账号</option>
                            <option value="1">终端便利店</option>
                        </select>
                        <input type="text" id="searchName" style="width: 150px;"
                               value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <select name="start_month" class="form-control" id="start_month">
                            <option value="0">选择月份</option>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="start_day" class="form-control" id="start_day">
                            <option value="0">选择日</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span>至</span>
                    </div>
                    <div class="form-group">
                        <select name="end_month" class="form-control" id="end_month">
                            <option value="0">选择月份</option>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="end_day" class="form-control" id="end_day">
                            <option value="0">选择日</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-2 form-inline">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="exportTable2xls()">导出xcel</a>
                    </div>
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="shop_statistics();">查询</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="pay_type">
                            <option value="0">付款方式</option>
                            <option value="1">线上支付</option>
                            <option value="2">货到付款</option>
                        </select>
                    </div>
                </div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/statistics.js" charset="utf-8"></script>
