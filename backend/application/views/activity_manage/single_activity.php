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
                            <option value="0" <?php if ($searchType== 0) echo ' selected' ?>>拼团编号</option>
                            <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>活动名称</option>
                            <?php
                            if($shop_manager_number == '') {
                                ?>
                                <option value="2" <?php if ($searchType == 1) echo ' selected' ?>>所属区域总代理</option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="text" id="searchName" style="width: 140px;"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <select name="searchStatus" class="form-control" id="searchStatus" style="margin-left: 40px;">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>活动状态</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>未开始</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>拼团中</option>
                            <option value="3" <?php if ($searchStatus == 3) echo ' selected' ?>>拼团成功</option>
                            <option value="4" <?php if ($searchStatus == 4) echo ' selected' ?>>拼团失败</option>

                        </select>
                    </div>
                    <?php
                        if($shop_manager_number == ''){
                            ?>
                            <div class="form-group">
                                <select class="form-control" id="searchOrder">
                                    <option value="0" <?php if ($searchOrder == 0) echo ' selected' ?>>置顶</option>
                                    <option value="1" <?php if ($searchOrder == 1) echo ' selected' ?>>是</option>
                                    <option value="2" <?php if ($searchOrder == 2) echo ' selected' ?>>否</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="searchRecommend">
                                    <option value="0" <?php if ($searchRecommend== 0) echo ' selected' ?>>推荐首页</option>
                                    <option value="1" <?php if ($searchRecommend == 1) echo ' selected' ?>>是</option>
                                    <option value="2" <?php if ($searchRecommend == 2) echo ' selected' ?>>否</option>
                                </select>
                            </div>
                            <?php
                        }
                    ?>
                </div>
                <div class="col-xs-12 col-sm-1 form-inline">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="showLists(1);">查询</a>
                    </div>
                </div>
            </div>
            <?php
                if( $shop_manager_number != '') {
                    ?>
                    <div class="row">
                        <div class="col-xs-6 col-sm-4 form-inline">
                            <div class="form-group area-search-control-view">
                                <a class="btn btn-primary form-control" href="<?php echo base_url(); ?>single_activity_add">
                                    <span>新增活动</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
            <div class="row" style="max-height: 670px; overflow-y: auto;">
                <div class="box main-shadow">
                    <div class="box-body table-responsive no-padding">
                        <table id="contentInfo_tbl"  class="table table-hover">
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
            <input id="item_type" value="" style="display: none;"/>
            <!-- /.modal-dialog -->
            <div id="confirm_deploy" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_deploy').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label id="confirm-deploy-message">您确定拼团成功吗？</label><br><br>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_deploy').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="deployItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            <div id="confirm_order" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-inline">
                            <label id="confirm-deploy-message">置顶顺序 :</label>
                            <input id="order_value">
                        </div>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_order').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="orderItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </section>

</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/activity_manage/single_activity.js" charset="utf-8"></script>
