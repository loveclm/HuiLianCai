<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            订单列表
        </h1>
    </section>
    <section class="container content">
        <ul class="nav nav-tabs" id="myTab1">
            <li class="<?php echo $showList == '1' ? 'active' : ''; ?>">
                <a data-toggle="tab" href="#tab_content1"
                   aria-expanded="<?php echo $showList == '1' ? true : false; ?>"
                   onclick="searchBuyOrder('<?php echo base_url(); ?>');">购买订单</a>
            </li>

            <li class="<?php echo $showList == '1' ? '' : 'active'; ?>">
                <a data-toggle="tab" href="#tab_content2"
                   aria-expanded="<?php echo $showList == '1' ? false : true; ?>"
                   onclick="searchAuthOrder('<?php echo base_url(); ?>');">授权码验证订单</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="tab_content1" class="tab-pane <?php echo $showList == '1' ? 'active' : ''; ?>">
                <div class="content" style="min-height: 800px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 form-inline">
                                <div class="form-group area-search-name-view">
                                    <div class="form-group">
                                        <select class="form-control" id="searchType">
                                            <option value="0" <?php if ($searchType == 0) echo 'selected' ?>>订单编号
                                            </option>
                                            <option value="1" <?php if ($searchType == 1) echo 'selected' ?>>手机号
                                            </option>
                                            <option value="2" <?php if ($searchType == 2) echo 'selected' ?>>景区
                                            </option>
                                            <option value="3" <?php if ($searchType == 3) echo 'selected' ?>>景点
                                            </option>
                                        </select>
                                    </div>
                                    <input type="text" id="searchName"
                                           value="<?php echo $searchName == 'ALL' ? '' : $searchName; ?>"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-xs-4 col-sm-4 form-inline">
                                <div class="form-group">
                                    <label>订单时间 &nbsp;:&nbsp;</label>
                                    <input class="form-control date-picker" id="startDate" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择"
                                           value="<?php echo $startDate; ?>">
                                    <label>&nbsp; 至 &nbsp;</label>
                                    <input class="form-control date-picker" id="endDate" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择"
                                           value="<?php echo $endDate; ?>">
                                </div>
                            </div>


                            <div class="col-xs-2 col-sm-2 form-inline">
                                <div class="form-group">
                                    <select class="form-control" id="searchStatus">
                                        <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>状态</option>
                                        <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>使用中</option>
                                        <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>未付款</option>
                                        <option value="3" <?php if ($searchStatus == 3) echo ' selected' ?>>已取消</option>
                                        <option value="4" <?php if ($searchStatus == 4) echo ' selected' ?>>已过期</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-2 col-sm-2 form-inline">
                                <div class="form-group area-search-control-view">
                                    <button class="btn btn-primary"
                                            onclick="searchBuyOrder('<?php echo base_url(); ?>');">
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
                                    <th width="150">订单编号</th>
                                    <th width="150">手机号</th>
                                    <th width="100">订单金额(元)</th>
                                    <th width="">景区</th>
                                    <th width="">景点</th>
                                    <th width="">所属商家</th>
                                    <th width="100">状态</th>
                                    <th width="150">订单时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $Count = count($buyList);
                                for ($i = 0; $i < $Count; $i++) {
                                    $item = $buyList[$i];
                                    ?>
                                    <tr>
                                        <td><?php echo $item->number; ?></td>
                                        <td><?php echo $item->mobile; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php
                                            $point_listitem = json_decode($item->point_list);
                                            $cs_name='';
                                            if (count($point_listitem) > 0) {
                                                foreach ($point_listitem as $pointitem) {
                                                    if ($cs_name == '') $cs_name = $pointitem->name;
                                                    else $cs_name = $cs_name . ' - ' . $pointitem->name;
                                                }
                                            }
                                            echo ($item->type == 2) ? $cs_name : $item->tour_area;
                                            //echo $item->tour_area;
                                            ?>
                                        </td>
                                        <td><?php
                                            if ($showList == '1') {
                                                if ($item->tour_point != 0) {
                                                    $attr_id = explode('_', $item->tour_point);
                                                    $item->tour_point = $attr_id[1];
                                                    $pointitem = $point_listitem[$item->tour_point - 1];
                                                }
                                                echo ($item->tour_point == 0) ? '所有' : $pointitem->name;
                                            }
                                            ?>
                                        </td>
                                        <td><?php
                                            $shopitem = $this->shop_model->getShopById($item->shop_name);
                                            echo $shopitem->name;
                                            ?>
                                        </td>
                                        <td><?php
                                            echo $item->status == '2' ? '使用中' : ($item->status == '1' ? '未付款' :
                                                ($item->status == '3' ? '已取消' : ($item->status == '4' ? '已过期' : ''))); ?>
                                        </td>
                                        <td><?php echo $item->ordered_time; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="tab_content2" class="tab-pane <?php echo $showList == '1' ? '' : 'active'; ?>">
                <div class="content" style="min-height: 800px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 form-inline">
                                <div class="form-group area-search-name-view">
                                    <div class="form-group">
                                        <select class="form-control" id="searchTypeAuth">
                                            <option value="0" <?php if ($searchTypeAuth == 0) echo ' selected'; ?> >订单编号
                                            </option>
                                            <option value="1" <?php if ($searchTypeAuth == 1) echo ' selected'; ?> >手机号
                                            </option>
                                            <option value="2" <?php if ($searchTypeAuth == 2) echo ' selected'; ?> >景区
                                            </option>
                                        </select>
                                    </div>
                                    <input type="text" id="searchNameAuth"
                                           value="<?php echo $searchNameAuth == 'ALL' ? '' : $searchNameAuth; ?>"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-xs-4 col-sm-4 form-inline">
                                <div class="form-group">
                                    <label>订单时间 &nbsp;:&nbsp;</label>
                                    <input class="form-control date-picker" id="startDateAuth" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择" value="">
                                    <label>&nbsp; 至 &nbsp;</label>
                                    <input class="form-control date-picker" id="endDateAuth" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择" value="">
                                </div>
                            </div>


                            <div class="col-xs-2 col-sm-2 form-inline">
                                <div class="form-group">
                                    <select class="form-control" id="searchStatusAuth">
                                        <option value="0" <?php if ($searchStatusAuth == 0) echo ' selected' ?>>状态
                                        </option>
                                        <option value="2" <?php if ($searchStatusAuth == 2) echo ' selected' ?>>先付款
                                        </option>
                                        <option value="1" <?php if ($searchStatusAuth == 1) echo ' selected' ?>>后付款
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-2 col-sm-2 form-inline">
                                <div class="form-group area-search-control-view">
                                    <button class="btn btn-primary"
                                            onclick="searchAuthOrder('<?php echo base_url(); ?>');">查询
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
                                    <th width="150">订单编号</th>
                                    <th width="150">手机号</th>
                                    <th width="150">授权码</th>
                                    <th width="100">付款方式</th>
                                    <th width="">景区</th>
                                    <th width="">所属商家</th>
                                    <th width="150">订单时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $authCount = count($authList);
                                for ($i = 0; $i < $authCount; $i++) {
                                    $item = $authList[$i];
                                    ?>
                                    <tr>
                                        <td><?php echo $item->number; ?></td>
                                        <td><?php echo $item->mobile; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php
                                            echo $item->status == '2' ? '先付款' : ($item->status == '3' ? '后付款' : '后付款'); ?>
                                        </td>
                                        <td><?php
                                            $point_listitem = json_decode($item->point_list);
                                            $cs_name = '';
                                            if (count($point_listitem) > 0) {
                                                foreach ($point_listitem as $pointitem) {
                                                    if ($cs_name == '') $cs_name = $pointitem->name;
                                                    else $cs_name = $cs_name . ' - ' . $pointitem->name;
                                                }
                                            }
                                            echo ($item->type == 2) ? $cs_name : $item->tour_area;
                                            ?>
                                        </td>
                                        <td><?php
                                            $sh = $this->shop_model->getShopById($item->shop_name);
                                            echo (count($sh) > 0) ? $sh->name : ''; ?>
                                        </td>
                                        <td><?php echo $item->ordered_time; ?></td>
                                    </tr>
                                    <?php
                                } ?>
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
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js" charset="utf-8"></script>