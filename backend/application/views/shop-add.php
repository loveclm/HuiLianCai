<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($shop) ? '编辑商家' : '新增商家';?>
        </h1>
    </section>

    <section class="content">
        <div class="container">
            <div class="row custom-info-row">
                <label class="col-sm-2">*商家名称:</label>
                <input type="text" class="col-sm-2" id="shopname" value="<?php echo isset($shop) ? $shop->name : '';?>" />
                <div id="custom-error-shopname" class="custom-error col-sm-4" style="display: none;">不超过10个字符</div>
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2">*商家账号:</label>
                <input type="text" class="col-sm-2" id="shopid" value="<?php echo isset($shop) ? $shop->phonenumber : '';?>" />
                <div id="custom-error-shopid" class="custom-error col-sm-4" style="display: none;">账号使用手机号，只能是11位</div>
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2">*初始密码:</label>
                <input type="text" class="col-sm-2" id="shoppassword" value="<?php echo isset($shop) ? $shop->password : '';?>" />
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2"> *所属地区:</label>
                <input id="cityName" class="col-sm-2" type="text" placeholder="" value="<?php echo isset($shop) ? $shop->address_1 : '';?>"/>
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2"> *商家类型:</label>
                <select class="col-sm-2" id="shoptype">
                    <option value="0">请选择</option>
                    <option value="1" <?php echo isset($shop)&&$shop->type == '1' ? 'selected': '';?>>旅行社</option>
                    <option value="2" <?php echo isset($shop)&&$shop->type == '2' ? 'selected': '';?>>渠道商</option>
                </select>
            </div>

            <div class="row custom-info-row">
                <label class="col-sm-2">*订单分成比率:</label>
                <input style="text-align: right;" type="text" class="col-sm-2" id="shoprate" value="<?php echo isset($shop) ? $shop->discount_rate : '';?>">
                <label> % </label>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 custom-course-control-view">
                    <input type="button" class="btn btn-primary" onclick="cancel('<?php echo base_url(); ?>');" value="取消" />
                    <input type="button" class="btn btn-primary" onclick="processShop('<?php echo base_url(); ?>' , '<?php echo isset($shop)? $shop->id: 0;?>');" value="确认" />
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/shop.js" charset="utf-8"></script>
<script src="https://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/map.js" charset="utf-8"></script>