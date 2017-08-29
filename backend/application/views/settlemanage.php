<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            结算列表
        </h1>
    </section>
    <section class="container content">
        <ul class="nav nav-tabs" id="myTab1">
            <li class="<?php echo $showList == '1' ? 'active' : ''; ?>">
                <a data-toggle="tab" href="#tab_content1"
                   aria-expanded="<?php echo $showList == '1' ? true : false; ?>"
                   onclick="searchBuyOrder('<?php echo base_url(); ?>');">线上购买订单结算</a>
            </li>

            <li class="<?php echo $showList == '1' ? '' : 'active'; ?>">
                <a data-toggle="tab" href="#tab_content2"
                   aria-expanded="<?php echo $showList == '1' ? false : true; ?>"
                   onclick="searchAuthOrder('<?php echo base_url(); ?>');">后付款授权码结算</a>
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
                                            <option value="0" <?php if ($searchType == 0) echo 'selected' ?>>商家账号
                                            </option>
                                            <option value="1" <?php if ($searchType == 1) echo 'selected' ?>>商家名称
                                            </option>
                                        </select>
                                    </div>
                                    <input type="text" id="searchName"
                                           value="<?php echo $searchName == 'ALL' ? '' : $searchName; ?>"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-6 form-inline">
                                <div class="form-group">
                                    <label>年月份 &nbsp;:&nbsp;</label>
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
                                    <th width="150">年月份</th>
                                    <th width="150">商家账号</th>
                                    <th width="">商家名称</th>
                                    <th width="150">结算金额(元)</th>
                                    <th width="150">平台提成(元)</th>
                                    <th width="150">实际结算金额(元)</th>
                                    <th width="100">状态</th>
                                    <th width="150">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $Count = count($buyList);
                                $sumTotal = 0;
                                $sumSettled = 0;
                                for ($i = 0; $i < $Count; $i++) {
                                    $item = $buyList[$i];
                                    $sumTotal+=$item->price;
                                    $sumSettled+=$item->price;
                                    ?>
                                    <tr>
                                        <td><?php echo $item->settle_date; ?></td>
                                        <td><?php echo $item->shopcode; ?></td>
                                        <td><?php echo $item->shopname; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php echo $item->price*$ratio; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php
                                            echo $item->status == '1' ? '已结算' : $item->status == '2' ? '未结算' : ''; ?>
                                        </td>
                                        <td>
                                            <a href="settleBuyDetail/<?php echo $item->id;?>/0">查看订单 &nbsp;</a>
                                            <?php
                                            if($item->price == 0){
                                                ?>
                                                <a href="#" onclick="showSelect(<?php echo $item->id;?>);"> 结算 &nbsp;</a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr style="background: #fd8f23;">
                                    <td>合计</td>
                                    <td>---</td>
                                    <td>---</td>
                                    <td><?php echo $sumTotal; ?></td>
                                    <td><?php echo $sumTotal*$ratio; ?></td>
                                    <td><?php echo $sumSettled; ?></td>
                                    <td>---</td>
                                    <td>---</td>
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
            </div>

            <div id="tab_content2" class="tab-pane <?php echo $showList == '1' ? '' : 'active'; ?>">
                <div class="content" style="min-height: 800px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 form-inline">
                                <div class="form-group area-search-name-view">
                                    <div class="form-group">
                                        <select class="form-control" id="searchTypeAuth">
                                            <option value="0" <?php if ($searchTypeAuth == 0) echo ' selected'; ?> >商家账号
                                            </option>
                                            <option value="1" <?php if ($searchTypeAuth == 1) echo ' selected'; ?> >商家名称
                                            </option>
                                        </select>
                                    </div>
                                    <input type="text" id="searchNameAuth"
                                           value="<?php echo $searchNameAuth == 'ALL' ? '' : $searchNameAuth; ?>"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-6 form-inline">
                                <div class="form-group">
                                    <label>年月份 &nbsp;:&nbsp;</label>
                                    <input class="form-control date-picker" id="startDateAuth" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择" value="">
                                    <label>&nbsp; 至 &nbsp;</label>
                                    <input class="form-control date-picker" id="endDateAuth" type="text"
                                           data-date-format="yyyy-mm-dd" placeholder="请选择" value="">
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
                                    <th width="150">年月份</th>
                                    <th width="">商家账号</th>
                                    <th width="">商家名称</th>
                                    <th width="150">使用授权码</th>
                                    <th width="">结算金额</th>
                                    <th width="100">状态</th>
                                    <th width="150">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $authCount = count($authList);
                                $sumTotal=0;
                                $sumSettled=0;
                                for ($i = 0; $i < $authCount; $i++) {
                                    $item = $authList[$i];
                                    $sumTotal+=$item->price;
                                    $sumSettled+=$item->price;
                                    ?>
                                    <tr>
                                        <td><?php echo $item->settle_date; ?></td>
                                        <td><?php echo $item->shopcode; ?></td>
                                        <td><?php echo $item->shopname; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php echo $item->status == '1' ? '已结算' : $item->status == '2' ? '未结算' : ''; ?></td>
                                        <td>
                                            <a href="settleBuyDetail/<?php echo $item->id;?>/0">查看订单 &nbsp;</a>
                                            <?php
                                            if($item->price == 0){
                                                ?>
                                                <a href="#" onclick="showSelect(<?php echo $item->id;?>);"> 结算 &nbsp;</a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                                <tr style="background: #fd8f23;">
                                    <td>合计</td>
                                    <td>---</td>
                                    <td>---</td>
                                    <td><?php echo $sumTotal; ?></td>
                                    <td><?php echo $sumSettled; ?></td>
                                    <td>---</td>
                                    <td>---</td>
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
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/settle.js" charset="utf-8"></script>