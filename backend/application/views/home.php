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
                <div class="col-xs-7">
                    <div class="col-xs-6">
                        <div class="info-box main-shadow">
                            <span class="info-box-icon bg-aqua"><img src="assets/images/user.png"/></span>

                            <div class="info-box-content text-center">
                                <h1><span class="head-selected info-box-more">1200</span></h1>
                                <span class="info-box-text">供货商数量</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="info-box main-shadow">
                            <span class="info-box-icon bg-aqua"><img src="assets/images/home.png"/></span>

                            <div class="info-box-content text-center">
                                <h1><span class="head-selected info-box-more">1400</span></h1>
                                <span class="info-box-text">终端便利店数量</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                </div>

                <div class="col-xs-5">
                    <div class="info-box main-shadow">
                        <div class="box-body table-responsive ">
                            <span class="h4 head-selected"> 销售情况統计 </span> 按周期统计订单量和订单金额
                            <table class="table table-hover text-center">
                                <tbody>
                                <tr>
                                    <th>项目</th>
                                    <th>销量</th>
                                    <th>销售金额</th>
                                </tr>
                                <tr>
                                    <td>昨日销量</td>
                                    <td>---</td>
                                    <td>---</td>
                                </tr>
                                <tr>
                                    <td>月销量</td>
                                    <td>261</td>
                                    <td>&yen; 345345</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <ul class="nav nav-tabs center-block" id="myTab1">
                    <?php
                    $i = 0;
                    foreach ($tab_headers as $item) {
                        $i++;
                        ?>
                        <li class="<?php echo $i == '1' ? 'active' : ''; ?>">
                            <a data-toggle="tab" href="#"
                               aria-expanded="<?php echo $i == '1' ? true : false; ?>"
                               onclick="showTopLists(<?php echo $i; ?>);">
                                <?php echo $item; ?>TOP10
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/home.js" charset="utf-8"></script>