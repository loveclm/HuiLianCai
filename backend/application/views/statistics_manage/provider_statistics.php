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
                <div class="col-xs-12 col-sm-9 form-inline" style="padding-right: 0px;">
                    <div class="form-group">
                        <select class="form-control" id="searchType">
                            <option value="0">区域总代理账号</option>
                            <option value="1">区域总代理名称</option>
                        </select>
                        <input type="text" id="searchName" style="width: 100px;"
                               value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <select name="start_month" class="form-control" id="start_month" style="margin-left: 10px;">
                            <option value="0" >选择年份</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="start_day" class="form-control" id="start_day">
                            <option value="0">选择月份</option>
                            <?php
                            for($i = 1; $i <=12; $i++){
                                echo '<option value="'. $i .'">'. $i.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <span>至</span>
                    </div>
                    <div class="form-group">
                        <select name="end_month" class="form-control" id="end_month">
                            <option value="0" >选择年份</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="end_day" class="form-control" id="end_day">
                            <option value="0">选择月份</option>
                            <?php
                            for($i = 1; $i <=12; $i++){
                                echo '<option value="'. $i .'">'. $i.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                 </div>
                <div class="col-xs-12 col-sm-2 form-inline" style="padding: 0px;">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="exportTable2xls()">导出Excel</a>
                    </div>
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="provider_statistics();">查询</a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/statistics.js" charset="utf-8"></script>
