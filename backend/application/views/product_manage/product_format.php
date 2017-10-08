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
                <div class="col-xs-12 col-sm-6 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchType">
                            <option value="0" <?php if ($searchType== 0) echo ' selected' ?>>商品条码</option>
                            <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>商品名称</option>
                        </select>
                        <input type="text" id="searchName"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchKind">
                            <option value="0" <?php if ($searchKind == 0) echo ' selected' ?>>分类</option>
                            <?php
                                foreach ( $typelist as $item){
                                    ?>
                                    <option value="<?= $item->id ?>" <?php if ($searchKind == $item->id) echo ' selected' ?>><?= $item->type ?></option>
                                    <?php
                                }
                            ?>
                        <?php
                        ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="searchBrand">
                            <option value="0" <?php if ($searchBrand == 0) echo ' selected' ?>>品牌</option>
                            <?php
                                foreach ($brandlist as $item){
                                    ?>
                                    <option value="<?= $item->id ?>" <?php if ($searchBrand == $item->id) echo ' selected' ?>><?= $item->name ?></option>
                                    <?php
                                }
                            ?>
                        </select>
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
                        <a class="btn btn-primary form-control" href="<?php echo base_url(); ?>product_format_add">
                            <span>新增商品</span>
                        </a>
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
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label id="confirm-deploy-message">确定要上架吗？</label><br><br>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/product_format.js" charset="utf-8"></script>
