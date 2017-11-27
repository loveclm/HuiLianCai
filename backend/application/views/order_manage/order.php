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
                            <option value="0" <?php if ($searchType== 0) echo ' selected' ?>>订单号</option>
                            <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>终端便利店</option>
                            <option value="2" <?php if ($searchType == 2) echo ' selected' ?>>收货人</option>
                            <?php
                            if($shop_manager_number == '') {
                                ?>
                                <option value="3" <?php if ($searchType == 3) echo ' selected' ?>>所属区域总代理</option>
                                <?php
                            }else {
                                ?>
                                <option value="3" <?php if ($searchType == 3) echo ' selected' ?>>配送员</option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="text" id="searchName" style="width: 140px;"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <select name="searchMethod" class="form-control" id="searchMethod" style="margin-left: 40px;">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>付款方式</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>线上支付</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>货到付款</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="searchStatus" class="form-control" id="searchStatus">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>订单状态</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>待付款</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>待成团</option>
                            <option value="3" <?php if ($searchStatus == 3) echo ' selected' ?>>待发货</option>
                            <option value="4" <?php if ($searchStatus == 4) echo ' selected' ?>>交易成功</option>
                            <option value="5" <?php if ($searchStatus == 5) echo ' selected' ?>>交易关闭</option>
                            <option value="6" <?php if ($searchStatus == 6) echo ' selected' ?>>已退款</option>

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
                        <label id="confirm-deploy-message">您确定配送吗？</label><br><br>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_deploy').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="deployItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

    </section>

</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order_manage/order.js" charset="utf-8"></script>
