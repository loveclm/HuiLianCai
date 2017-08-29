<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            查看详情
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-9 col-sm-8 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchStatus">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>状态</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>已使用</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>未使用</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-3 col-sm-4 form-inline">
                    <div class="form-group area-search-control-view">
                        <button class="btn btn-primary"
                                onclick="searchAuthOrder('<?php echo base_url(); ?>',<?php echo $authid; ?> );">查询
                        </button>
                        <button class="btn btn-primary" onclick="cancel('<?php echo base_url(); ?>');">返回
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
                        <th sidth="100">序号</th>
                        <th>授权码</th>
                        <th>状态</th>
                        <th>订单时间</th>
                        <th width="200">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $authCount = count($authList);
                    for ($i = 0; $i < $authCount; $i++) {
                        $item = $authList[$i];

                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $item->code; ?></td>
                            <td><?php echo $item->status == 0 ? '未使用' : '已使用'; ?></td>
                            <td><?php echo $item->ordered_time; ?></td>
                            <td>
                                <?php
                                if ($item->status != 0) {
                                    ?>
                                    <a href="#" onclick="authOrderItem('<?php echo base_url(); ?>',<?php echo $item->id; ?>);"> 订单详情 &nbsp;</a>
                                    <?php
                                }
                                ?>
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
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/auth.js" charset="utf-8"></script>