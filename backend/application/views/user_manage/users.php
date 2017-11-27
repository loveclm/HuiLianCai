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
                                <option value="0" <?php if ($searchType == 0) echo ' selected' ?>>账号</option>
                                <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>姓名</option>
                            </select>
                            <input type="text" id="searchName" style="width: 140px;"
                                   value="<?php echo $searchName == '' ? '' : $searchName; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-1 form-inline">
                        <div class="form-group">
                            <a href="#" class="btn btn-primary" onclick="showLists(1);">查询</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-4 form-inline">
                        <div class="form-group area-search-control-view">
                            <a class="btn btn-primary form-control" href="<?php echo base_url(); ?>user_add">
                                <span>新增运营人员</span>
                            </a>
                        </div>
                    </div>
                </div>
            <div class="row" style="max-height: 670px; overflow-y: auto;">
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
            <div id="alert_delete" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#alert_delete').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label>该业务员已关联了区域总代理，先去更换区域总代理的业务员吧。</label><br><br>
                        <a href="#" class="btn btn-primary" onclick="$('#alert_delete').hide();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

            <div id="confirm_delete" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_delete').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label>确定删除？</label><br><br>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_delete').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="deleteItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

            <input id="item_id" value="" style="display: none;"/>
            <input id="item_status" value="" style="display: none;"/>

            <div id="confirm_password" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_password').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group form-inline">
                            <label>&nbsp;&nbsp;*密码 &nbsp;: </label>
                            <input id="passwd" type="password" placeholder="输入新密码">
                        </div>
                        <div class="form-group form-inline">
                            <label>*确认密码 &nbsp;:</label>
                            <input id="cpasswd" type="password" placeholder="再输入密码">
                        </div>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_password').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="passwordItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </section>

</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/user_manage/user_manage.js" charset="utf-8"></script>
