<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            授权码列表
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-9 col-sm-8 form-inline">
                    <div class="form-group area-search-name-view">
                        <div class="form-group">
                            <select class="form-control" id="searchType">
                                <option value="0" <?php if ($searchType == 0) echo ' selected'?>>商家名称</option>
                                <option value="1" <?php if ($searchType == 1) echo ' selected'?>>名称</option>
                            </select>
                        </div>
                        <input type="text" id="searchName" value="<?php echo $searchName == 'all' ? '': $searchName; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="searchStatus">
                            <option value="0" <?php if ($searchStatus == 0) echo ' selected'?>>付款方式</option>
                            <option value="1" <?php if ($searchStatus == 1) echo ' selected'?>>先付款</option>
                            <option value="2" <?php if ($searchStatus == 2) echo ' selected'?>>后付款</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-3 col-sm-4 form-inline">
                    <div class="form-group area-search-control-view">
                        <button class="btn btn-primary" onclick="searchAuth('<?php echo base_url(); ?>');">查询</button>

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
                        <th width="">名称</th>
                        <th width="100">授权码个数</th>
                        <th width="100">已使用个数</th>
                        <th width="150">新增时间</th>
                        <th width="100">付款方式</th>
                        <th width="100">付款金额(元)</th>
                        <th width="150">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $authCount = count($authList);
                    for($i = 0; $i < $authCount; $i++){
                        $item = $authList[$i];

                        ?>
                        <tr>
                            <td><?php echo $item->shopName;?></td>
                            <td><?php echo $item->tourName; ?></td>
                            <td><?php echo $this->auth_model->getOrderTotal($item->id);?></td>
                            <td><?php echo $this->auth_model->getOrderUsed($item->id);?></td>
                            <td><?php echo $item->created;?></td>
                            <td><?php echo $item->money > 0 ? '先付款': '后付款'; ?></td>
                            <td><?php echo $item->money;?></td>
                            <td>
                                <a href="authDetail/<?php echo $item->id;?>/0">查看 &nbsp;</a>
                                <?php
                                if($item->money == 0){
                                    ?>
                                    <a href="#" onclick="showSelect(<?php echo $item->id;?>);"> 付款方式 &nbsp;</a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <div id="custom-generate-auth-view" style="display:none;">
                        <div class="form-group">
                            <label>选择付款方式？</label>
                        </div>
                        <div class="form-group">
                            <button onclick="showMoney();">先付款</button>
                            <button onclick="$('#custom-generate-auth-view').hide();">后付款</button>
                        </div>
                    </div>

                    <div id="custom-generate-auth-count-view" style="display:none;">
                        <div class="form-group">
                            <label>请输入金额 &nbsp; </label>
                            <input id="auth-count"/> &nbsp; 元
                        </div>

                        <div class="form-group">
                            <button onclick="addMoney('<?php echo base_url(); ?>', );">确认</button>
                            <button onclick="$('#custom-generate-auth-count-view').hide();">取消</button>
                        </div>
                    </div>
                    <div id="savingId" style="display: none;"></div>

                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/auth.js" charset="utf-8"></script>