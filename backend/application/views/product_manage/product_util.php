<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <?php
                if(isset($brand)) {
                    ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-10 form-inline">
                            <div class="form-group">
                                <select class="form-control" id="searchType">
                                    <option value="0">品牌名称</option>
                                </select>
                                <input type="text" id="searchName" class="form-control" style="width: 140px;"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-1 form-inline">
                            <div class="form-group">
                                <a href="#" class="btn btn-primary" onclick="showLists(3);">查询</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
            <div class="row">
                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-search-control-view">
                        <?php
                            if(isset($brand)){
                                ?>
                                <a class="btn btn-primary form-control" href="<?php echo base_url(); ?>product_brand_add">
                                    <span>新增</span>
                                </a>
                                <?php
                        }else {
                                ?>
                                <a class="btn btn-primary form-control" href="#" onclick="deployConfirm(0,<?= ($pageName == 'product_type')? '1' : '2'; ?>)">
                                    <span>新增</span>
                                </a>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row" style="max-height: 670px; overflow-y: auto;">
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
            <div id="alert_delete" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#alert_delete').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label id="alert_message">该名称已关联商品，不能删除。</label><br><br>
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
            <!-- /.modal-dialog -->
            <div id="confirm_deploy" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_deploy').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span id="confirm_title" class="modal-title">新增分类名称</span>
                    </div>
                    <div class="modal-body">
                        <label id="confirm-deploy-message">*分类名称</label>
                        <input id="name" type="text" />
                        <div id="add_alert_message" style="color: red;"></div>
                        <br><br>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/product_util.js" charset="utf-8"></script>
