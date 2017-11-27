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
                        <a href="#" class="btn btn-primary" onclick="exportTable2xls()">导出Excel</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="box main-shadow">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead id="header_tbl">
                            <?= isset($data->header)? $data->header: ''?>
                            </thead>
                            <tbody id="content_tbl">
                            <?= isset($data->content)? $data->content: ''?>
                            </tbody>
                            <tfoot id="footer_tbl">
                            <?= isset($data->footer)? $data->footer: ''?>
                            </tfoot>
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
