<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

include_once("./CCPRestSmsSDK.php");

//主帐号,对应开官网发者主账号下的 ACCOUNT SID
$accountSid = '8aaf07085df473bd015e02d1e23b033a';

//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
$accountToken = '5ffb76facf5f405b8013c294de9945af';

//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID // app token = 324e315f686eb507c9235c8ff39be0bc
$appId = '8aaf07085df473bd015e02d49e230340';

//请求地址
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
$serverIP = 'app.cloopen.com';


//请求端口，生产环境和沙盒环境一致
$serverPort = '8883';

//REST版本号，在官网文档REST介绍中获得。
$softVersion = '2013-12-26';

$phoneNumber = $_POST['phoneNumber'];
$sms_code = mt_rand(100000, 999999);
$message = array($sms_code, '验证码部分');
//手机号码，替换内容数组，模板ID
sendTemplateSMS($phoneNumber, $message, '199724');

/**
 * 发送模板短信
 * @param to 手机号码集合,用英文逗号分开
 * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
 * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
 */
function sendTemplateSMS($to, $datas, $tempId)
{
    // 初始化REST SDK
    global $accountSid, $accountToken, $appId, $serverIP, $serverPort, $softVersion, $sms_code;

    $rest = new REST($serverIP, $serverPort, $softVersion);
    $rest->setAccount($accountSid, $accountToken);
    $rest->setAppId($appId);

    // 发送模板短信
    $data = array();
    $result = NULL;
    $result->statusCode = 0;
    //$result = $rest->sendTemplateSMS($to, $datas, $tempId);
    if ($result == NULL) {
        $data = array(result => "error!", error => "Server no response!");
    } else if ($result->statusCode != 0) {
        //TODO 添加错误处理逻辑
        $data['result'] = 'fail';
        $data['error'] = $result->statusMsg;
    } else {
        // 获取返回信息
        $smsmessage = $result->TemplateSMS;
        //TODO 添加成功处理逻辑
        $data['result'] = 'success';
        $data['code'] = $sms_code;
    }
    echo json_encode($data);
}

?>
