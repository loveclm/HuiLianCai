<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            商家列表
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-search-name-view">
                        <label>商家名称</label>
                        <input type="text" id="searchName" value="<?php echo $searchName == 'all' ? '': $searchName; ?>" class="form-control">
                    </div>

                </div>

                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-search-area-view">
                        <label>所属地区</label>
                        <select class="form-control" id="searchAddress">
                            <option value="all">请选择</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="searchStatus">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected'?>>状态</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected'?>>未禁用</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected'?>>已禁用</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4 form-inline">
                    <div class="form-group area-search-control-view">
                        <button class="btn btn-primary" onclick="searchShop('<?php echo base_url(); ?>');">查询</button>

                        <a class="btn btn-primary" href="<?php echo base_url(); ?>addshop">
                            <span>新增</span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <table class="table table-bordered area-result-view">
                    <thead>
                    <tr style="background-color: lightslategrey;">
                        <th>商家名称</th>
                        <th width="100">商家类型</th>
                        <th width="100">景区数</th>
                        <th width="100">旅游线路数</th>
                        <th width="100">授权码数</th>
                        <th>所属地区</th>
                        <th width="100">状态</th>
                        <th width="300">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $shopCount = count($shopList);
                    for($i = 0; $i < $shopCount; $i++){
                        $shop = $shopList[$i];

                        ?>
                        <tr>
                            <td><?php echo $shop->name;?></td>
                            <td><?php echo $shop->type == '1' ? '旅行社': ($shop->type == '2'?'渠道商':''); ?></td>
                            <td><?php echo $this->order_model->getAreaCountByShopId($shop->id,1);?></td>
                            <td><?php echo $this->order_model->getAreaCountByShopId($shop->id,2);?></td>
                            <td><?php echo $this->auth_model->getAuthCountByShopId($shop->id);?></td>
                            <td><?php echo $shop->address_1;?></td>
                            <td><?php echo $shop->status == 1 ? '已禁用': '未禁用'; ?></td>
                            <td>
                                <a href="editshop/<?php echo $shop->id;?>">查看</a>
                                <?php
                                if($shop->status == 0){
                                    ?>
                                    <a href="#" onclick="deleteAreaConfirm(<?php echo $shop->id;?>);">删除</a>
                                    <?php
                                }
                                if($shop->status == 0){
                                    ?>
                                    <a href="#" onclick="deployAreaConfirm(<?php echo $shop->id;?>);">禁用</a>
                                    <?php
                                }else {
                                    ?>
                                    <a href="#" onclick="undeployAreaConfirm(<?php echo $shop->id;?>);">取消禁用</a>
                                    <?php
                                }
                                ?>
                                <a href="#" onclick="showGenerateQR(<?php echo $shop->id;?>);">生成二维码</a>
                                <a href="#" onclick="showGenerateAuth(<?php echo $shop->id;?>);">发放授权码</a>
                            </td>
                        </tr>
                    <?php } ?>
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
                            <input id="current-codetype" style="display: none;"/>
                            <input id="current-targetid" style="display: none;"/>
                        </div>
                    </div>

                    <div id="custom-generate-auth-view" style="display:none;">
                        <div class="form-group">
                            <label>选择类型  </label>
                            <select id="auth-select"  onchange="changeAuthType();">
                                <option value="0">请选择</option>
                                <option value="1">景区</option>
                                <option value="2">旅游线路</option>
                            </select>
                        </div>
                        <div class="form-group" id="custom-auth-area-view" style="display:none;">
                            <label>景区名称  </label>
                            <select id="auth-select-area">
                                <option value="0">请选择</option>
                                <?php
                                $areaCount = count($areaList);
                                for($i=0; $i<$areaCount; $i++){
                                    $areaInfo = $areaList[$i];
                                    ?>
                                    <option value="<?php echo $areaInfo->id;?>"><?php echo $areaInfo->name;?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group" id="custom-auth-course-view" style="display:none;">
                            <label>旅游线路名称  </label>
                            <select id="auth-select-course">
                                <option value="0">请选择</option>
                                <?php
                                $courseCount = count($courseList);
                                for($i=0; $i<$courseCount; $i++){
                                    $courseInfo = $courseList[$i];
                                    ?>
                                    <option value="<?php echo $courseInfo->id;?>"><?php echo $courseInfo->name;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button onclick="cancel('<?php echo base_url(); ?>');">取消</button>
                            <button onclick="generateAuth('<?php echo base_url(); ?>');">确定</button>
                        </div>
                    </div>

                    <div id="custom-generate-auth-count-view" style="display:none;">
                        <div class="form-group">
                            <label>发放数量  </label>
                            <input id="auth-count" style="width: 30%;"/>个
                        </div>

                        <div class="form-group">
                            <button onclick="cancel('<?php echo base_url(); ?>');">取消</button>
                            <button onclick="generateAuthFinal('<?php echo base_url(); ?>');">确定</button>
                        </div>
                    </div>
                    <div id="custom-generate-qr-view" style="display:none;">
                        <div class="form-group">
                            <div id="qr-view"></div>
                            <button style="position: absolute; top: 3px; right: 3px;border: none;background: none;" onclick="cancel('<?php echo base_url(); ?>');">
                                <img src="<?php echo base_url(); ?>assets/images/close.png" style="width: 30px;"></button>
                        </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/shop.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/qrcode.js" charset="utf-8"></script>