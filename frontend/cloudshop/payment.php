<?php
header('Access-Control-Allow-Origin: *');

ini_set('date.timezone', 'Asia/Shanghai');
////error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
//
////初始化日志
$total_fee = floatval($_GET['cost']) * 100;
$title = '惠联彩';//$_GET['title'];
$detail = '商品购买';//$_GET['detail'];
$logHandler = new CLogFileHandler("logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);
//
////①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();
//// get Payment parameter
//
////②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($title);
$input->SetAttach("惠联彩");
$input->SetOut_trade_no(WxPayConfig::MCHID . date("YmdHis"));
$input->SetTotal_fee($total_fee);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($detail);
$input->SetNotify_url('http://www.huiliancai.com/frontend/notify.php');
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//
$jsApiParameters = $tools->GetJsApiParameters($order);
//
?>

<html>
<head lang="en">
    <title><?= $title ?></title>
<!--    <meta charset="utf-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">-->
<!--    <meta name="apple-touch-fullscreen" content="yes">-->
<!--    <meta name="apple-mobile-web-app-capable" content="yes">-->
<!--    <meta name="apple-mobile-web-app-status-bar-style" content="black">-->
<!--    <meta name="format-detection" content="telephone=no">-->

    <?php include('page_header.php'); ?>
</head>

<body>
<div id="content">
    <div id="container">
        <h5 style="font-size: 20pt; margin: 20% 20px;">
            <center>惠联彩</center>
        </h5>
    </div>

    <div class="footer" id="app_footer" style="text-align: center">
        <h5 style="font-size: 20pt; margin: 20% 20px;">支付金额 : ¥<?= floatval($total_fee) / 100; ?></h5>
        <span class="btn_custom" onclick="callpay()">立即支付</span>
    </div>
</div>
</body>
<?php include('page_footer.php'); ?>
<script type="text/javascript">

    $(function () {
        $('#content').html('');
        callpay();
    });
    function callpay() {
        if (typeof WeixinJSBridge == "undefined") {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        } else {
            jsApiCall();
        }
    }
    //调用微信JS api 支付
    function jsApiCall() {
        if (HLC_PAY_MODE == HLC_SIMUL_MODE) {
            onlinePayOrderRequest();
            return;
        }
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function (res) {
                WeixinJSBridge.log(res.err_msg);
                switch (res.err_msg) {
                    case 'get_brand_wcpay_request:ok':
                        onlinePayOrderRequest();
                        break;
                    case 'get_brand_wcpay_request:cancel':
                        showMessage('支付未完成', 2);
                        setTimeout(function () {
                            history.go(-1);
                        },2000);
                        break;
                    case 'get_brand_wcpay_request:fail':
                        showMessage('支付失败', 2);
                        setTimeout(function () {
                            history.go(-1);
                        },2000);
                        break;
                }
                //alert(res.err_code+res.err_desc+res.err_msg);
            }
        );
    }
    function onOk() {
        history.go(-1);
    }

</script>
</html>