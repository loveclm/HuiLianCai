<?php
// 文档中说的，价格　单位是分
$money = $_POST['money']*100; //商品价格
// 前台请求的参数
$title = $_POST['title'];//商品名称
$userid = $_POST['userid'];//用户id
$time = time();

$nonce_str = "hcuasduvihasdiovjerjgvujsaru"; //随机字符串
$appid = "wx76b2bf6b8c1dae12"; //在微信开放平台中的　appid(先要创建一个移动应用)
$mch_id = "1486887662";  //商户号，在商户平台中查看
$key = "t2soso349kxlkeklxmxles3sldsfmxlk"; //在微信开放平台中的　
$notify_url = "http://www.huiliancai.com/frontend/wxpay_wap_notify.php"; //用户支付完后微信会来触发这个脚本，是处理业务逻辑的地方
                                                                         //订单号可以灵活使用，比如我这个地方把userid加进去，在异步回调的时候方便直接操作用户
$out_trade_no = $time."__".$userid; 

// 下面的参数含义直接看文档
    
$tmpArr = array(
    'appid' => $appid,//不要填成了 公众号原始id
    'attach' => $title,
    'body' => $title,
    'mch_id' => $mch_id,
    'nonce_str' => $nonce_str,
    'notify_url' => $notify_url,
    'out_trade_no' => $out_trad_no,
    'spbill_create_ip' =>'120.27.8.152',//$_SERVER['REMOTE_ADDR'],
    'total_fee' => $money,
    'trade_type' => 'MWEB'
);
// 签名逻辑官网有说明，签名步骤就不解释了
ksort($tmpArr);  

$buff = "";
foreach ($tmpArr as $k => $v)
{
   $buff .= $k . "=" . $v . "&";
}
$buff = trim($buff, "&");
$stringSignTemp=$buff."&key=51b3363e91fe317fc346526f5933f15e";
$sign= strtoupper(md5($stringSignTemp)); //签名

$xml = "<xml>
           <appid>".$appid."</appid>
           <attach>".$title."</attach>
           <body>".$title."</body>
           <mch_id>".$mch_id."</mch_id>
           <nonce_str>".$nonce_str."</nonce_str>
           <notify_url>".$notify_url."</notify_url>
           <out_trade_no>".$out_trade_no."</out_trade_no>
           <spbill_create_ip>".$_SERVER['REMOTE_ADDR']."</spbill_create_ip>
           <total_fee>".$money."</total_fee>
           <trade_type>MWEB</trade_type>
           <sign>".$sign."</sign>
        </xml> ";

$posturl = "https://api.mch.weixin.qq.com/pay/unifiedorder";

$ch = curl_init($posturl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
$response = curl_exec($ch);  
curl_close($ch);

echo $response;

//$xmlobj = json_decode(json_encode(simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA ))); 
//echo $xmlobj;
//exit($xmlobj->mweb_url);

?>