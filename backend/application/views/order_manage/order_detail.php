<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>
    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div>
                <form role="form">
                    <div class="row form-inline">
                        <label> 订单号 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->id) ? $model->id : ''; ?></span>
                            <?php
                            if (isset($model->status)) {
                                switch ($model->status) {
                                    case 1:
                                        $Status = '待付款';
                                        break;
                                    case 2:
                                        $Status = '待成团';
                                        break;
                                    case 3:
                                        $Status = '待发货';
                                        break;
                                    case 4:
                                        $Status = '交易成功';
                                        break;
                                    case 5:
                                        $Status = '交易关闭';
                                        break;
                                    case 6:
                                        $Status = '已退款';
                                        break;
                                }
                            } else {
                                $Status = '';
                            }
                            ?>
                            <span style="color: white; background-color: red; margin-left: 50px; padding: 5px;"><?= $Status; ?></span>
                        </div>
                    </div>
                    <?php
                    if ($shop_manager_number == '') {
                        ?>
                        <div class="row form-inline">
                            <label> 所属供货商 : </label>
                            <div class="input-group margin">
                                <span><?php echo isset($model->provider_name) ? $model->provider_name : ''; ?></span>
                            </div>
                        </div>
                        <div class="row form-inline">
                            <label> 供货商账号 : </label>
                            <div class="input-group margin">
                                <span><?php echo isset($model->provider_userid) ? $model->provider_userid : ''; ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row form-inline">
                        <label> 终端便利店账号 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->shop_id) ? $model->shop_id : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 终端便利店 : </label>
                        <div class="input-group margin">
                            <span><?php echo isset($model->shop_name) ? $model->shop_name : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline" style="padding-left: 75px;">
                        <div class="row">
                            <div class="box main-shadow">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead id="header_tbl">
                                        <tr>
                                            <th>活动编号</th>
                                            <th>商品条码</th>
                                            <th>封面</th>
                                            <th>商品名称</th>
                                            <?php
                                            if ($shop_manager_number != '') {
                                                ?>
                                                <th>活动类型</th>
                                                <?php
                                            }
                                            ?>
                                            <th>原价</th>
                                            <th>拼团价</th>
                                            <th>数量</th>
                                            <th>订单金额</th>
                                        </tr>
                                        </thead>
                                        <tbody id="content_tbl">
                                        <?php
                                        if (isset($model->activities)) {
                                            $total_count = 0;
                                            $origin_cost = 0;
                                            $group_cost = 0;
                                            $activity_cnts = explode(",", $model->activity_cnts);
                                            for ($k = 0; $k < count($model->activities); $k++) {
                                                $activity = $model->activities[$k];
                                                $activity_cnt = floatval($activity_cnts[$k]);

                                                $i = 0;
                                                $buy_cnt = json_decode($activity->buy_cnt);
                                                foreach ($activity->products as $product) {
                                                    $cur_count = floatval($buy_cnt[$i]->count);
                                                    $cur_origin_cost = $cur_count * floatval($product->cost);
                                                    $cur_group_cost = $cur_count * floatval($buy_cnt[$i]->cost);

                                                    $total_count += $cur_count * $activity_cnt;
                                                    $origin_cost += $cur_origin_cost * $activity_cnt;
                                                    $group_cost += $cur_group_cost * $activity_cnt;

                                                    $i++;
                                                    ?>
                                                    <tr>
                                                        <td><?= $activity->activity_id . '-' . $i; ?></td>
                                                        <td><?= $product->barcode; ?></td>
                                                        <td>
                                                            <img src="<?= base_url() . json_decode($product->cover)[1] ?>"
                                                                 style="width:100px;height:50px;">
                                                        </td>
                                                        <td><?= $product->name; ?></td>
                                                        <?php
                                                        if ($shop_manager_number != '') {
                                                            ?>
                                                            <th><?= ($activity->kind == 1) ? '单品活动' : '套餐活动'; ?></th>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td><?= number_format((float)$cur_origin_cost, 2, '.',''); ?></td>
                                                        <td><?= number_format((float)$buy_cnt[$i-1]->cost, 2,'.','') ; ?></td>
                                                        <td><?= $cur_count * $activity_cnt; ?></td>
                                                        <td><?= number_format((float)($cur_origin_cost * $activity_cnt),2,'.','' ); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot id="footer_tbl"></tfoot>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 合计数量 : </label>
                        <div class="input-group margin">
                            <span id="total_count"><?= isset($model->activities) ? number_format((float)$total_count,2,'.','') : '0'; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 原价总价 : </label>
                        <div class="input-group margin">
                            <span id="origin_cost"><?= isset($model->activities) ? number_format((float)$origin_cost,2,'.',''): '0.00'; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 拼团价 : </label>
                        <div class="input-group margin">
                            <span id="group_cost"><?= isset($model->activities) ? number_format((float)$group_cost, 2,'.','') : '0.00'; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 付款方式 : </label>
                        <div class="input-group margin">
                            <span id="group_cost"><?= isset($model->pay_method) ? (($model->pay_method == 1) ? '线上支付' : '货到付款') : ''; ?></span>
                        </div>
                    </div>
                    <?php
                    if (isset($model->pay_method) && ($model->pay_method == 1)) {
                        $coupon = floatval($model->coupon);
                        ?>
                        <div class="row form-inline" style="color: red">
                            <label> 优惠券 : </label>
                            <div class="input-group margin">
                                <span id="coupon">-<?= isset($model->coupon) ? number_format((float)$coupon, 2,'.','') : '0.00'; ?></span>
                            </div>
                        </div>
                        <?php
                    } else {
                        $coupon = 0;
                    }
                    ?>
                    <div class="row form-inline">
                        <label> 扣除余额 : </label>
                        <div class="input-group margin">
                            <span id="coupon"><?= number_format((float)$coupon, 2,'.','') ; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> <?= isset($model->pay_method) ? (($model->pay_method == 1) ? '实付金额' : '订单金额') : ''; ?>
                            : </label>
                        <div class="input-group margin">
                            <span id="coupon"><?= isset($model->group_cost) ? intval(floatval($model->group_cost)) : '0.00'; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 退款金额 : </label>
                        <div class="input-group margin">
                            <span><?= number_format((float)(floatval($model->pay_cost) - floatval($model->group_cost)),2,'.',''); ?></span>
                        </div>
                    </div>
                    <div class="row"></div>
                    <div class="row form-inline">
                        <label> 买家留言 : </label>
                        <div class="input-group margin">
                            <span>速度！</span>
                        </div>
                    </div>
                    <div class="row"></div>
                    <div class="row form-inline">
                        <label> 收货人 : </label>
                        <div class="input-group margin">
                            <span><?= isset($model->contact_name) ? $model->contact_name : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 联系电话 : </label>
                        <div class="input-group margin">
                            <span><?= isset($model->contact_phone) ? $model->contact_phone : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 收货地址 : </label>
                        <div class="input-group margin">
                            <span><?= isset($model->address) ? $model->address : ''; ?></span>
                        </div>
                    </div>
                    <div class="row"></div>
                    <div class="row form-inline">
                        <label> 配送员 : </label>
                        <div class="input-group margin">
                            <span><?= isset($model->shipman_name) ? $model->shipman_name : ''; ?></span>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 联系电话 : </label>
                        <div class="input-group margin">
                            <span><?= isset($model->shipman_phone) ? $model->shipman_phone : ''; ?></span>
                        </div>
                    </div>
                    <div class="row"></div>
                    <div class="row form-inline">
                        <label> 提交时间 : </label>
                        <div class="input-group margin">
                            <span><?= isset($model->create_time) ? $model->create_time : ''; ?></span>
                        </div>
                    </div>
                    <?php
                    switch ($model->status) {
                        case 1: //待付款
                            break;
                        case 2: //待成团
                            ?>
                            <div class="row form-inline">
                                <label> 付款时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->pay_time) ? $model->pay_time : ''; ?></span>
                                </div>
                            </div>
                            <?php
                            break;
                        case 3: //待发货
                            ?>
                            <div class="row form-inline">
                                <label> 付款时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->pay_time) ? $model->pay_time : ''; ?></span>
                                </div>
                            </div>
                            <?php
                            if ($model->isSuccess == 1) {
                                ?>
                                <div class="row form-inline">
                                    <label> 拼团成功时间 : </label>
                                    <div class="input-group margin">
                                        <span><?= isset($model->success_time) ? $model->success_time : ''; ?></span>
                                    </div>
                                </div>
                                <?php
                            } else if ($model->isSuccess == 2) {
                                ?>
                                <div class="row form-inline">
                                    <label> 拼团失败时间 : </label>
                                    <div class="input-group margin">
                                        <span><?= isset($model->success_time) ? $model->success_time : ''; ?></span>
                                    </div>
                                </div>
                                <?php
                            }
                            break;
                        case 4: //交易完成
                            ?>
                            <div class="row form-inline">
                                <label> 付款时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->pay_time) ? $model->pay_time : ''; ?></span>
                                </div>
                            </div>
                            <?php
                            if ($model->isSuccess == 1) {
                                ?>
                                <div class="row form-inline">
                                    <label> 拼团成功退款时间 : </label>
                                    <div class="input-group margin">
                                        <span><?= isset($model->refund_time) ? $model->refund_time : ''; ?></span>
                                    </div>
                                </div>
                                <?php
                            } else if ($model->isSuccess == 2) {
                                ?>
                                <div class="row form-inline">
                                    <label> 拼团失败时间 : </label>
                                    <div class="input-group margin">
                                        <span><?= isset($model->success_time) ? $model->success_time : ''; ?></span>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="row form-inline">
                                <label> 发货时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->ship_time) ? $model->ship_time : ''; ?></span>
                                </div>
                            </div>
                            <div class="row form-inline">
                                <label> 完成时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->complete_time) ? $model->complete_time : ''; ?></span>
                                </div>
                            </div>
                            <?php
                            break;
                        case 5: //交易关闭
                            ?>
                            <div class="row form-inline">
                                <label> 取消时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->cancel_time) ? $model->cancel_time : ''; ?></span>
                                </div>
                            </div>
                            <?php
                            break;
                        case 6: //已退款
                            ?>
                            <div class="row form-inline">
                                <label> 付款时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->pay_time) ? $model->pay_time : ''; ?></span>
                                </div>
                            </div>
                            <div class="row form-inline">
                                <label> 取消时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->cancel_time) ? $model->cancel_time : ''; ?></span>
                                </div>
                            </div>
                            <div class="row form-inline">
                                <label> 退款时间 : </label>
                                <div class="input-group margin">
                                    <span><?= isset($model->refund_time) ? $model->refund_time : ''; ?></span>
                                </div>
                            </div>
                            <?php
                            break;
                    }
                    ?>

                    <div class="row form-inline">
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control"
                                   href="<?php echo base_url(); ?>order">
                                    <span>返回</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order_manage/order.js"
        charset="utf-8"></script>

