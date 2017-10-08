<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
        <input id="pageTitle" value="<?php echo $pageTitle ?>" type="hidden">
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-9 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchType">
                            <option value="0">配送编号</option>
                            <option value="1">配送员</option>
                        </select>
                        <input type="text" id="searchName"
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
                        <a href="#" class="btn btn-primary" onclick="shipman_list();">查询</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-2 form-inline">
                <div class="form-group">
                    <select class="form-control" id="searchStatus">
                        <option value="0">配送状态</option>
                        <option value="1">未装车</option>
                        <option value="2">已装车</option>
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
            <input id="item_id" value="" style="display: none;"/>
            <div id="confirm_deploy" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_deploy').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label id="confirm-deploy-message">确定要装车吗？</label><br><br>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_deploy').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="deployItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </section>

</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/shipping.js"
        charset="utf-8"></script>
