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
                <div class="col-xs-12 col-sm-4 form-inline">
                    <div class="form-group">
                        <select class="form-control" id="searchType">
                            <option value="0" <?php if ($searchType == 0) echo ' selected' ?>>终端便利店账号</option>
                            <option value="1" <?php if ($searchType == 1) echo ' selected' ?>>终端便利店</option>
                            <option value="2" <?php if ($searchType == 2) echo ' selected' ?>>地址</option>
                            <?php
                            if ($shop_manager_number == '') {
                                ?>
                                <option value="3" <?php if ($searchType == 3) echo ' selected' ?>>推荐人手机号</option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="text" id="searchName"
                               value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control"
                               style="width: 170px;">
                    </div>
                </div>
                <?php
                if ($shop_manager_number == '') {
                    ?>
                    <div class="col-xs-12 col-sm-6 form-inline">

                        <!-- ////////////////////Address Part  -->
                        <div id="tip" class="form-group">
                            <span>所属县区 : </span>
                            <?php
                            $address = '';
                            $addrs = explode(',', $address);
                            ?>
                            <select id='province' onchange='search(this)'></select>
                            <select id='city' onchange='search(this)'></select>
                            <select id='district' onchange='search(this)'></select>
                            <select id='street' onchange='setCenter(this)' style="display: none;"></select>

                            <input name="provinceName" id="provinceName" style="display: none;"
                                   value="<?php echo $address != '' ? ($addrs[0]) : ''; ?>">
                            <input name="cityName" id="cityName" style="display: none;"
                                   value="<?php echo $address != '' ? ($addrs[1]) : ''; ?>">
                            <input name="districtName" id="districtName" style="display: none;"
                                   value="<?php echo $address != '' ? ($addrs[2]) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <select class="form-control" id="searchStatus">
                                <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>禁用状态</option>
                                <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>未禁用</option>
                                <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>已禁用</option>
                            </select>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class=" col-xs-12 col-sm-6 form-inline">
                        <div class="form-group">
                            <select class="form-control" id="searchShoptype">
                                <option value="0" <?php if ($searchShoptype == 0) echo ' selected' ?>>类型</option>
                                <option value="1" <?php if ($searchShoptype == 1) echo ' selected' ?>>便利店</option>
                                <option value="2" <?php if ($searchShoptype == 2) echo ' selected' ?>>中型超市</option>
                                <option value="2" <?php if ($searchShoptype == 3) echo ' selected' ?>>餐饮店</option>
                                <option value="2" <?php if ($searchShoptype == 4) echo ' selected' ?>>其他业态</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="searchStatus">
                                <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>禁用状态</option>
                                <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>未禁用</option>
                                <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>已禁用</option>
                            </select>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="col-xs-12 col-sm-1 form-inline">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary" onclick="showLists(1);">查询</a>
                    </div>
                </div>
            </div>
            <?php
            if ($shop_manager_number == '') {
                ?>
                <div class="row">
                    <div class=" col-xs-12 col-sm-6 form-inline">
                        <div class="form-group">
                            <select class="form-control" id="searchShoptype">
                                <option value="0" <?php if ($searchShoptype == 0) echo ' selected' ?>>类型</option>
                                <option value="1" <?php if ($searchShoptype == 1) echo ' selected' ?>>便利店</option>
                                <option value="2" <?php if ($searchShoptype == 2) echo ' selected' ?>>中型超市</option>
                                <option value="2" <?php if ($searchShoptype == 3) echo ' selected' ?>>餐饮店</option>
                                <option value="2" <?php if ($searchShoptype == 4) echo ' selected' ?>>其他业态</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="searchAuth">
                                <option value="0" <?php if ($searchAuth == 0) echo ' selected' ?>>认证状态</option>
                                <option value="1" <?php if ($searchAuth == 1) echo ' selected' ?>>未认证</option>
                                <option value="2" <?php if ($searchAuth == 2) echo ' selected' ?>>待认证</option>
                                <option value="2" <?php if ($searchAuth == 3) echo ' selected' ?>>认证通过</option>
                                <option value="2" <?php if ($searchAuth == 4) echo ' selected' ?>>认证失败</option>
                            </select>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
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
            <div id="confirm_auth" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row form-inline">
                            <label class="col-sm-2 col-xs-12" style="width: 102px;">审核结果：</label>
                            <div class="form-group">
                                <div class="radio col-sm-4 col-xs-12">
                                    <label>
                                        <input type="radio" name="radio_auth_type" value="1" checked>
                                        认证通过
                                    </label>
                                </div>
                                <div class="radio col-sm-4 col-xs-12">
                                    <label>
                                        <input type="radio" name="radio_auth_type" value="2">
                                        认证失败
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="auth_reason" style="display: none;">
                        <label style="text-align: left;margin-left: -40%;">*失败原因：</label>
                        <div>
                        <textarea name="reason" id="reason" rows="3" cols="40"></textarea>
                        </div>
                    </div>
                    <br>
                    <a href="#" class="btn btn-default" onclick="$('#confirm_auth').hide();">取消</a>
                    <a href="#" class="btn btn-primary" onclick="authItem();">确定</a>
                    <div style="height: 20px;"></div>
                </div>
                <!-- /.modal-content -->
            </div>
            <input id="item_id" value="" style="display: none;"/>
            <input id="item_status" value="" style="display: none;"/>
            <!-- /.modal-dialog -->
            <div id="confirm_deploy" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_deploy').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label id="confirm-deploy-message">确定要上架吗？</label><br><br>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_deploy').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="deployItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            <div id="confirm_user" class="modal-dialog text-center" style="display: none;width: 400px; margin: 5px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title">选择配送员</span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="box main-shadow">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead id="ship_header_tbl"></thead>
                                        <tbody id="ship_content_tbl"></tbody>
                                        <tfoot id="ship_footer_tbl"></tfoot>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </section>

</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/user_manage/shop.js" charset="utf-8"></script>
<?php
if ($shop_manager_number == '') {
    ?>
    <script
            src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool,AMap.DistrictSearch"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/addressSupport.js" charset="utf-8"></script>
    <?php
}
?>