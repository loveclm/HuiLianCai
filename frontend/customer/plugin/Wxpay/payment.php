<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 8/25/2017
 * Time: 1:02 AM
 */
    ini_set('date.timezone','Asia/Shanghai');
    require_once "lib/WxPay.Api.php";
    require_once "WxPay.JsApiPay.php";
    require_once "log.php";

    //error_reporting(E_ERROR);
    //初始化日志
    $logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
    $log = Log::Init($logHandler, 15);

    //①、获取用户openid
    $tools = new JsApiPay();
    $openId = $tools->GetOpenid();

    $data = array();
    if(!isset($_GET)){
        $data['result'] = 'fail';
        echo json_encode($data);
        exit();
    }

    // get Payment parameter
    $total_fee = floatval($_GET['cost'])*100;
    $buy_type = $_GET['type'];
    $product_name = $_GET['product'];

    //②、统一下单
    $input = new WxPayUnifiedOrder();

    $input->SetBody("A游不错");
    $input->SetAttach($buy_type);
    $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
    $input->SetTotal_fee($total_fee);
    $input->SetFee_type("1");
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag($product_name);
    $input->SetNotify_url(dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."/notify.php");
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);

    $order = WxPayApi::unifiedOrder($input);
    $jsApiParameters = $tools->GetJsApiParameters($order);

    $data['result'] = 'success';
    $data['parameters'] = $jsApiParameters;
    echo json_encode($data);
?>
