<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <?php
            if($shop_manager_number == '') {
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 form-inline">
                        <div class="form-group">
                            <select class="form-control" id="searchType">
                                <option value="0" <?php if ($searchStatus == 0) echo ' selected' ?>>配送员账号</option>
                                <option value="1" <?php if ($searchStatus == 1) echo ' selected' ?>>姓名</option>
                                <option value="2" <?php if ($searchStatus == 2) echo ' selected' ?>>联系电话</option>
                                <option value="3" <?php if ($searchStatus == 3) echo ' selected' ?>>所属区域总代理</option>
                            </select>
                            <input type="text" id="searchName" style="width: 140px;"
                                   value="<?php echo $searchName == 'all' ? '' : $searchName; ?>" class="form-control">
                        </div>
                    </div>

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

                        <!--<div class="form-group">
                            <select class="form-control" id="searchStatus">
                                <option value="0" <?php //if ($searchStatus == 0) echo ' selected' ?>>禁用状态</option>
                                <option value="1" <?php //if ($searchStatus == 1) echo ' selected' ?>>未禁用</option>
                                <option value="2" <?php //if ($searchStatus == 2) echo ' selected' ?>>已禁用</option>
                            </select>
                        </div>-->
                    </div>

                    <div class="col-xs-12 col-sm-1 form-inline">
                        <div class="form-group">
                            <a href="#" class="btn btn-primary" onclick="showLists(1);">查询</a>
                        </div>
                    </div>
                </div>
                <?php
            }else {
                ?>
                <div class="row">
                    <div class="col-xs-6 col-sm-4 form-inline">
                        <div class="form-group area-search-control-view">
                            <a class="btn btn-primary form-control" href="<?php echo base_url(); ?>shipman_add">
                                <span>新增配送员</span>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="row"  style="max-height: 700px; overflow-y: auto;">
                <div class="box main-shadow">
                    <div class="box-body table-responsive no-padding">
                        <table id="contentInfo_tbl" class="table table-hover">
                            <thead id="header_tbl"></thead>
                            <tbody id="content_tbl"></tbody>
                            <tfoot id="footer_tbl"></tfoot>
                        </table>
                        <div id="contentpageNavPosition"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div id="alert_delete" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#alert_delete').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label>该配送员已关联了终端便利店，先去更换终端便利店负责的配送员吧。</label><br><br>
                        <a href="#" class="btn btn-primary" onclick="$('#alert_delete').hide();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>

            <div id="confirm_delete" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_delete').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <label>确定删除？</label><br><br>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_delete').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="deleteItem();">确定</a>
                    </div>
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
            <div id="confirm_password" class="modal-dialog text-center" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                onclick="$('#confirm_password').hide();">
                            <span aria-hidden="true">×</span></button>
                        <span class="modal-title">提示</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group form-inline">
                            <label>&nbsp;&nbsp;*密码 &nbsp;: </label>
                            <input id="passwd" type="password" placeholder="输入新密码">
                        </div>
                        <div class="form-group form-inline">
                            <label>*确认密码 &nbsp;:</label>
                            <input id="cpasswd" type="password" placeholder="再输入密码">
                        </div>
                        <a href="#" class="btn btn-default" onclick="$('#confirm_password').hide();">取消</a>
                        <a href="#" class="btn btn-primary" onclick="passwordItem();">确定</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </section>

</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_manage/shipping.js" charset="utf-8"></script>
<?php
if($shop_manager_number == '') {
    ?>
    <script
            src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool,AMap.DistrictSearch"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/addressSupport.js" charset="utf-8"></script>
    <?php
}
?>