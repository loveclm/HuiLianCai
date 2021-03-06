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
                <div class="col-xs-12 col-sm-2 form-inline">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="delete_messages();">删除</a>
                    </div>
                </div>
            </div>

            <div class="row"  style="max-height: 700px; overflow-y: auto;">
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
                        <label id="confirm-deploy-message">您阅读吗？</label><br><br>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_deploy').hide();">不</a>
                        <a href="#" class="btn btn-primary"  style="margin-left: 20px;" onclick="deployItem();">是</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </section>

</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/news.js" charset="utf-8"></script>
