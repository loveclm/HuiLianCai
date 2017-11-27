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
<input id="shop_name" type="text" placeholder="请输入便利店名称">
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
<input id="shop_addr" type="text" placeholder="请输入详细地址"
style="text-align: center; width: 100%">
<input style="text-align: center; width: 100%; color: red; font-size: 10pt"
disabled value="地址即为收货地址, 认证通过后不可修改">
</div>
<div class="register_row">
<label>*联系人 :</label>
<input id="contact_person" type="text" placeholder="请输入您的姓名">
</div>
<div class="register_row">
<label>*联系电话 :</label>
<input id="contact_person_phone" type="number" placeholder="请输入您的手机号">
</div>
<div class="register_row">
<label style="width: 35%">*营业执照编号 :</label>
<input id="business_license_number" type="text" style="width:60% ;" placeholder="请输入营业执照编号">
</div>
<div class="register_row" style="border: none;">
<label style="width: auto">*营业执照 :</label>
<div class="shop_license_set">
<img id="shop_license_img" src="assets/images/blank.png" style="">
<img id="shop_license_img_cover" src="assets/images/plus_license@3x.png">
</div>
<input type="file" id="upload_shop_license" style="display: none">
</div>
<div id="my_Map" style="display: none"></div>
<input id="my_LatLng" value="" style="display: none">
</div>
</div>
<div class="page-footer">
<div id="btn_Authorize" onclick="">提交认证</div>
</div>
</div>
</body>
<!--<script src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.Geolocation"></script>-->

<?php include('page_footer.php'); ?>

<?php
ini_set('date.timezone','Asia/Shanghai');
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler = new CLogFileHandler("logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

//①、获取用户openid
$tools = new JsApiPay();
//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();
?>

<script type="text/javascript">

function editAddress() {
WeixinJSBridge.invoke(
'editAddress',
<?php echo $editAddress; ?>,
function (res) {
//var value1 = res.addressProvinceFirstStageName;
//var value2 = res.addressCitySecondStageName;
//var value3 = res.addressCountiesThirdStageName;
//var value4 = res.addressDetailInfo;
//var tel = res.telNumber;
//showMessage(value1 + value2 + value3 + value4 + ":" + tel, 1);
showMessage(JSON.stringify(res), 1);
}
);
}

function weixinGetLocation() {
if (typeof WeixinJSBridge == "undefined") {
if (document.addEventListener) {
document.addEventListener('WeixinJSBridgeReady', editAddress, false);
} else if (document.attachEvent) {
document.attachEvent('WeixinJSBridgeReady', editAddress);
document.attachEvent('onWeixinJSBridgeReady', editAddress);
}
} else {
editAddress();
}
};

</script>

<script src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.DistrictSearch"></script>
<script src="assets/js/addressSupport.js" type="text/javascript"></script>

<script src="assets/js/user_manage/individual_info.js" type="text/javascript"></script>

</html>
