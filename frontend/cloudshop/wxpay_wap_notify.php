// 本脚本的业务逻辑只是个例子，仅供参考
<?php
// 拿到原始数据并通过xml类解析为对象
$postObj = simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA );
// 你可以通过下面这种方式来看一下微信究竟返回了那些参数，请保证log.php存在并且有写权限
// file_put_contents(dirname(__file__)."/log.php",file_get_contents("php://input"));
$arr = array();
foreach ($postObj as $key => $value) {
    $arr[$key] = $value;
} 
// 订单状态
$status = $arr['result_code'];

if(trim($status) == "SUCCESS") {
    // 微信订单
    $out_trade_no = $arr['transaction_id'];

        // 价格
        $money = $arr['total_fee']/100;
        // 在商户订单号中提取用户id,上一个脚本中我说了这个商户订单号可以灵活使用
        $uid = explode("__", $arr['out_trade_no'])[1];

        // 在数据库中检查这个订单号是否已经处理过了　以免重复处理，因为很多原因微信可能多次触发本脚本
        // checkrepeat(orderid);

        /////////////////////////////////////////////////////////////
        ////                                                    /////
        ////    这里处理业务逻辑．．．．                            /////    
        ////                                                    /////            
        /////////////////////////////////////////////////////////////

        // 处理完逻辑　返回这个xml数据，告诉微信服务器，这个订单号已经处理完了　不要在来骚扰我了
        $xml = "
                <xml>
                  <return_code><![CDATA[".$status."]]></return_code>
                  <return_msg><![CDATA[OK]]></return_msg>
                </xml>";
        echo $xml;
        
}
?>