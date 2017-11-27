<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body class="page-footer-fixed">
<div class="page-wrapper">
    <div class="page-content-wrapper">
        <div class="page-content" style="overflow-y: scroll;background: white;">
            <div class="register_row">
                <label>*上传店招 :</label>
                <img id="shop_logo_img" src="assets/images/plus@3x.png">
                <input type="file" id="upload_shop_logo" style="display: none">
            </div>
            <div class="register_row">
                <label>*类型 :</label>
                <select id="shop_type" class="form-control input-small input-inline">
                    <option value="0">请选择</option>
                    <option value="1">便利店</option>
                    <option value="2">中型超市</option>
                    <option value="3">餐饮店</option>
                    <option value="4">其他业态</option>
                </select>
            </div>
            <div class="register_row">
                <label>*账号 :</label>
                <input id="userID" type="text" value="" disabled placeholder="请输入便利店账号(手机号)">
            </div>
            <div class="register_row">
                <label>*名称 :</label>
                <input id="shop_name" type="text" placeholder="请输入便利店名称" title="便利店名称不应超过10个字。">
            </div>
            <div id="tip" class="register_row">
                <span>*地址 :</span>
                <?php
                $address = '';
                $addrs = explode(',', $address);
                ?>
                <select id='province' onchange='search(this)' style="width: 25%;"></select>
                <select id='city' onchange='search(this)' style="width: 25%"></select>
                <select id='district' onchange='search(this)' style="width: 25%"></select>
                <select id='street' onchange='setCenter(this)' style="display: none;"></select>

                <input name="provinceName" id="provinceName" style="display: none;"
                       value="<?php echo $address != '' ? ($addrs[0]) : ''; ?>">
                <input name="cityName" id="cityName" style="display: none;"
                       value="<?php echo $address != '' ? ($addrs[1]) : ''; ?>">
                <input name="districtName" id="districtName" style="display: none;"
                       value="<?php echo $address != '' ? ($addrs[2]) : ''; ?>">
            </div>
            <div class="register_row" style="text-align: center;">
                <input id="shop_addr" type="text" placeholder="请输入详细地址" title="便利店地址不应超过30个字。"
                       style="text-align: center; width: 100%">
                <input style="text-align: center; width: 100%; color: red; font-size: 10pt"
                       disabled value="地址即为收货地址, 认证通过后不可修改">
            </div>
            <div class="register_row">
                <label>*联系人 :</label>
                <input id="contact_person" type="text" placeholder="请输入您的姓名" title="请输入姓名(2-5个字)。">
            </div>
            <div class="register_row">
                <label>*联系电话 :</label>
                <input id="contact_person_phone" type="number" placeholder="请输入您的手机号" title="请输入11位有效手机号码。">
            </div>
            <div class="register_row">
                <label style="width: 35%">*营业执照编号 :</label>
                <input id="business_license_number" type="text" style="width:60% ;" placeholder="请输入营业执照编号"
                       title="营业执照编号不应超过30个字。">
            </div>
            <div class="register_row" style="border: none;">
                <label style="width: auto">*营业执照 :</label>
                <div class="shop_license_set">
                    <img id="shop_license_img" src="assets/images/blank.png" style="">
                    <img id="shop_license_img_cover" src="assets/images/plus_license@3x.png">
                </div>
                <input type="file" id="upload_shop_license" style="display: none">
            </div>
            <div id="my_Map" style="display: none;"></div>
            <input id="my_LatLng" value="" style="display:none;">
        </div>
    </div>
    <div class="page-footer">
        <div id="btn_Authorize" onclick="">提交认证</div>
    </div>
</div>
</body>
<!--<script src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.Geolocation"></script>-->


<script src="https://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.DistrictSearch"></script>
<script src="assets/js/addressSupport.js" type="text/javascript"></script>

<?php include('page_footer.php'); ?>
<script type="text/javascript">
    function getWeixinLocation() {
        /*
        * 注意：
        * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
        * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
        * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
        *
        * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
        * 邮箱地址：weixin-open@qq.com
        * 邮件主题：【微信JS-SDK反馈】具体问题
        * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
        */

        if(!is_weixin()) return;
        wx.config({
            debug: false,
            appId: '<?= $signPackage["appId"];?>',
            timestamp: <?= $signPackage["timestamp"];?>,
            nonceStr: '<?= $signPackage["nonceStr"];?>',
            signature: '<?= $signPackage["signature"];?>',
            jsApiList: ['checkJsApi', 'getLocation']
        });

        wx.checkJsApi({
            jsApiList: [
                'getLocation'
            ],
            success: function (res) {
// alert(JSON.stringify(res));
// alert(JSON.stringify(res.checkResult.getLocation));
                if (res.checkResult.getLocation == false) {
                    alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                    return;
                }
            }
        });
        wx.ready(function () {
            wx.getLocation({
                success: function (res) {
                    var lat_lng = {};
                    lat_lng.lat = res.latitude;
                    lat_lng.lng = res.longitude;
//showMessage(JSON.stringify(lat_lng), 1);
                    $('#my_LatLng').val(JSON.stringify(lat_lng));
                    getLocationFromLatLng();
//alert(JSON.stringify(res));
                },
                cancel: function (res) {
                    showNotifyAlert('获取地理位置授权失败');
                }
            });
        });
    }
</script>

<script src="assets/js/user_manage/individual_info.js" type="text/javascript"></script>

</html>
