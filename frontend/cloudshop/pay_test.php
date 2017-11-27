<?php

	$money= 1;//充值金额 
        
      
      
      
        //$userip = $this->getIP(); //获得用户设备IP 自己网上百度去
        $appid = "wx76b2bf6b8c1dae12";//微信给的
        $mch_id = "1486887662";//微信官方的
        $key = "t2soso349kxlkeklxmxles3sldsfmxlk";//自己设置的微信商家key
      
        $rand = rand(00000,99999);
        $out_trade_no = '20170804'.$rand;//平台内部订单号
        $nonce_str=MD5($out_trade_no);//随机字符串
        $body = "H5充值";//内容
        $total_fee = $money; //金额
        //var_dump($total_fee);
        $spbill_create_ip = '120.27.8.152'; //IP
        $notify_url = "http://www.baidu.com/login/pay/payinfo"; //回调地址
        $trade_type = 'MWEB';//交易类型 具体看API 里面有详细介绍
        $scene_info ='{"h5_info":{"type":"Wap","wap_url":"http://www.baidu.com","wap_name":"支付"}}';//场景信息 必要参数
        $signA ="appid=$appid&body=$body&mch_id=$mch_id&nonce_str=$nonce_str¬ify_url=$notify_url&out_trade_no=$out_trade_no&scene_info=$scene_info&spbill_create_ip=$spbill_create_ip&total_fee=$total_fee&trade_type=$trade_type";
        $strSignTmp = $signA."&key=$key"; //拼接字符串  注意顺序微信有个测试网址 顺序按照他的来 直接点下面的校正测试 包括下面XML  是否正确
        $sign = strtoupper(MD5($strSignTmp)); // MD5 后转换成大写
        $post_data = "<xml>
                        <appid>$appid</appid>
                        <body>$body</body>
                        <mch_id>$mch_id</mch_id>
                        <nonce_str>$nonce_str</nonce_str>
                        <notify_url>$notify_url</notify_url>
                        <out_trade_no>$out_trade_no</out_trade_no>
                        <scene_info>$scene_info</scene_info>
                        <spbill_create_ip>$spbill_create_ip</spbill_create_ip>
                        <total_fee>$total_fee</total_fee>
                        <trade_type>$trade_type</trade_type>
                        <sign>$sign</sign>
                    </xml>";//拼接成XML 格式
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";//微信传参地址
        //var_dump($this);
        $test = new test();
        echo $test->index();

class test{

public function __construct(){

}
public function index(){
        $dataxml = $this->http_post($url,$post_data); //后台POST微信传参地址  同时取得微信返回的参数    POST 方法我写下面了
	//var_dump($dataxml);
        $objectxml = (array)simplexml_load_string($dataxml, 'SimpleXMLElement', LIBXML_NOCDATA); //将微信返回的XML 转换成数组
	echo $objectxml;
}

  function http_post($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
};
    ?>
    
    <html>
    <head>
    <title>test</title>
    <head>
    <body>
    <div id="test"></div>
    </body>
    <script type="text/javascript">
	var data = '<?php echo $dataxml;?>';
	document.getElementById('test').innerHTML = data;
	
    </script>
    </html>