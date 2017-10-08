<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected" onclick="test_api()">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-7" style="padding-left: 0px;">
                    <?php
                    if($shop_manager_number == '') {
                        ?>
                        <div class="col-xs-6" style="padding-left: 0px;">
                            <div class="info-box main-shadow">
                                <span class="info-box-icon bg-aqua"><img src="assets/images/user.png"/></span>

                                <div class="info-box-content text-center">
                                    <h1><span class="head-selected info-box-more"><?= isset($basic_data['provider_cnt'])? $basic_data['provider_cnt'] : ''; ?></span></h1>
                                    <span class="info-box-text">供货商数量</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="info-box main-shadow">
                                <span class="info-box-icon bg-aqua"><img src="assets/images/home.png"/></span>

                                <div class="info-box-content text-center">
                                    <h1><span class="head-selected info-box-more"><?= isset($basic_data['shop_cnt'])? $basic_data['shop_cnt'] : ''; ?></span></h1>
                                    <span class="info-box-text">终端便利店数量</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <?php
                    }else {
                        ?>
                        <div class="info-box main-shadow">
                            <span class="info-box-icon" style="background-color: white; margin: 0px 10px;">
                                <img id="" src="<?= isset($basic_data['logo'])?  base_url().$basic_data['logo'] : base_url().'uploads/provider_logo.png'?>" style="max-width: 100px"/>
                            </span>
                            <div style="margin-left: 120px;">
                                <div class="row" style="padding: 20px;">
                                    <div class="col-xs-5">
                                        <h4 class="home_item" style="color:#0090bf;"><?= isset($basic_data['provider_name'])? $basic_data['provider_name'] : ''; ?></h4>
                                        <h4 class="home_item">联系人：<?= isset($basic_data['contact_name'])? $basic_data['contact_name'] : ''; ?></h4>
                                    </div>
                                    <div class="col-xs-7">
                                        <h4 class="home_item">账号：<?= isset($basic_data['provider_id'])? $basic_data['provider_id'] : ''; ?></h4>
                                        <h4 class="home_item">联系电话：<?= isset($basic_data['contact_phone'])? $basic_data['contact_phone'] : ''; ?></h4>
                                    </div>
                                    <div class="col-xs-11">
                                        <h4 class="home_item">公司地址：<?= isset($basic_data['address'])? $basic_data['address'] : ''; ?></h4>
                                    </div>
                                    <div class="col-xs-1">
                                        <h3><a href="<?= base_url(). 'showProvider/' .$userId?>">
                                            <i class="fa fa-fw fa-angle-right" style="padding-right: 20px;"></i></a>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <!-- /.info-box-content -->
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <div class="col-xs-5" style="padding: 0px;">
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
                                    <td><?= isset($sale_data[0]['cnt'])? $sale_data[0]['cnt'] : '--'; ?></td>
                                    <td><?= ($sale_data[0]['cost'] != '--')? '￥'.$sale_data[0]['cost'] : '--'; ?></td>
                                </tr>
                                <tr>
                                    <td>月销量</td>
                                    <td><?= isset($sale_data[1]['cnt'])? $sale_data[1]['cnt'] : '--'; ?></td>
                                    <td><?= ($sale_data[1]['cost'] != '--')? '￥'.$sale_data[1]['cost'] : '--'; ?></td>
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
                        if($shop_manager_number != '' && $i == 1) continue;
                        ?>
                        <li class="<?php echo $i == (($shop_manager_number != '')? '2' : '1') ? 'active' : ''; ?>">
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
<script type="text/javascript">
    var provider_id = <?= ($shop_manager_number == '') ? '0' : $shop_manager_number; ?>;
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/home.js" charset="utf-8"></script>