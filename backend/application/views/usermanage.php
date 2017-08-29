<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            用户列表
        </h1>
    </section>
    <section class="container content">
        <div class="content" style="min-height: 800px;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-8 col-sm-8 form-inline">
                        <div class="form-group area-search-name-view">
                            <div class="form-group">
                                <select class="form-control" id="searchType">
                                    <option value="0" <?php if ($searchType == 0) echo 'selected' ?>>手机号
                                    </option>
                                </select>
                            </div>
                            <input type="text" id="searchName"
                                   value="<?php echo $searchName == 'ALL' ? '' : $searchName; ?>"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 form-inline">
                        <div class="form-group area-search-control-view">
                            <button class="btn btn-primary"
                                    onclick="searchUserList('<?php echo base_url(); ?>');">
                                查询
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <table class="table table-bordered area-result-view">
                        <thead>
                        <tr style="background-color: lightslategrey;">
                            <th width="">序号</th>
                            <th width="">手机号</th>
                            <th width="">购买订单</th>
                            <th width="">验证订单</th>
                            <th width="">总消费金额(元)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $Count = count($userList);
                        $sumPaid = $sumBuy = $sumAuth = $Auth = $Buy = 0;
                        if($userList!='') {
                            for ($i = 0; $i < $Count; $i++) {
                                $item = $userList[$i];
                                $mobile = $item->mobile;
                                $Buy = $this->collection_model->getBuyOrderPaid($mobile);
                                $sumBuy += $Buy;
                                $Auth = $this->collection_model->getAuthOrderPaid($mobile);
                                $sumAuth += $Auth;
                                $Paid = $this->order_model->getMyOrderInfos($mobile);
                                $Paid = $Paid == '-1' ? '0' : $Paid['total_price'];
                                $sumPaid += $Paid;
                                ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo $item->mobile; ?></td>
                                    <td><?php echo $Buy; ?></td>
                                    <td><?php echo $Auth; ?></td>
                                    <td><?php echo $Paid; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr style="background: #fd8f23;">
                            <td>合计</td>
                            <td>---</td>
                            <td><?php echo $sumBuy; ?></td>
                            <td><?php echo $sumAuth; ?></td>
                            <td><?php echo $sumPaid; ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div id="custom-confirm-delete-view" style="display:none;">
                            <p>
                                是否要删除此商家？
                            </p>

                            <div class="form-group">
                                <button onclick="deleteArea('<?php echo base_url(); ?>', 0);">取消</button>
                                <button onclick="deleteArea('<?php echo base_url(); ?>', 1);">确定</button>
                            </div>

                        </div>
                        <div id="custom-confirm-deploy-view" style="display:none;">
                            <p>
                                是否要上架此景区？
                            </p>

                            <div class="form-group">
                                <button onclick="deployArea('<?php echo base_url(); ?>', 0);">取消</button>
                                <button onclick="deployArea('<?php echo base_url(); ?>', 1);">确定</button>
                                <input id="current-areaid" style="display: none;"/>
                                <input id="current-areastatus" style="display: none;"/>
                                <input id="current-type" style="display: none;"/>
                            </div>
                        </div>

                        <div id="custom-generate-auth-view" style="display:none;">
                            <div class="form-group">
                                <label>选择类型 </label>
                                <select id="auth-select" onchange="changeAuthType();">
                                    <option value="0">请选择</option>
                                    <option value="1">景区</option>
                                    <option value="2">旅游线路</option>
                                </select>
                            </div>
                            <div class="form-group" id="custom-auth-area-view" style="display:none;">
                                <label>景区名称 </label>
                                <select id="auth-select-area">
                                    <option value="0">请选择</option>
                                    <option value="1">区</option>
                                    <option value="2">旅</option>
                                </select>
                            </div>
                            <div class="form-group" id="custom-auth-course-view" style="display:none;">
                                <label>旅游线路名称 </label>
                                <select id="auth-select-course">
                                    <option value="0">请选择</option>
                                    <option value="1">区</option>
                                    <option value="2">旅</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <button onclick="cancel('<?php echo base_url(); ?>');">取消</button>
                                <button onclick="generateAuth('<?php echo base_url(); ?>');">确定</button>
                            </div>
                        </div>

                        <div id="custom-generate-auth-count-view" style="display:none;">
                            <div class="form-group">
                                <label>发放数量 </label>
                                <input id="auth-count"/>个
                            </div>

                            <div class="form-group">
                                <button onclick="cancel('<?php echo base_url(); ?>');">取消</button>
                                <button onclick="generateAuthFinal('<?php echo base_url(); ?>');">确定</button>
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/usercollection.js" charset="utf-8"></script>