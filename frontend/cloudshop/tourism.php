<?php
    require_once "jssdk.php";
    $jssdk = new JSSDK("wxb042726847dca8d3", "70e43300732636e813e59f8b2199dfc9");
    $signPackage = $jssdk->GetSignPackage();

    $shop_id = 0;
    $target_id = 0;

    if(isset($_GET['shopid']))  $shop_id = $_GET['shopid'];
    if(isset($_GET['targetid'])) $target_id = $_GET['targetid'];
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <title>旅游线路</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/AdminLTE.min.css">
    <link rel="stylesheet" href="css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script type="text/javascript" src="js/plugins/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/global.js"></script>
    <script type="text/javascript" src="js/tourism.js"></script>
</head>

<body>
<div id="content">
    <div id="container"  style="background-image: url('resource/image/bg.png')">
        <div id="tourism-body">
            <!--
                <div class="course_column"><h5>景区 1 : 故宫 (10个景点)</h5></div>
                <div class="course_column"><h5>景区 2 : 圆明园 (10个景点)</h5></div>
                <div class="course_column"><h5>景区 3 : 颐和园 (10个景点)</h5></div>
                <div class="btn-custom btn-course"><h5>支付100元，解锁线路</h5></div>
                <div class="btn-custom btn-course"><h5>输入授权码</h5></div>
            -->
        </div>
    </div>
</div>

<!--phone number verify dialog-->
<div class="modal custom-modal" id="phone_verify">
    <div class="modal-dialog" id="phone_verify_dialog">
        <div class="modal-content" style="height: 100%;border-radius: 12px;">
            <div class="modal-header" id="phone_verify_title" style="padding: 0px; margin: 0px;border: none">
                <button class="close" onclick="phone_verify_dialog_close()">
                    <span aria-hidden="true">×</span>
                </button>
                <img src="resource/image/top.png" style="width: 100%">
                <div style="text-align: center">
                    <img src="resource/image/sign_in.png">
                    <h4>授权登录</h4>
                </div>
            </div>
            <div class="modal-body" id="phone_verify_content">
                <div>
                    <h5 style="float: left">手机号</h5>
                    <input type="text" class="form-control" id="phone_number" placeholder="" style="width: 76%" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div>
                <div>
                    <h5 style="float: left">验证码</h5>
                    <input type="text" class="form-control" id="verify_code" placeholder="验证码" style="width: 35%" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    <div class="btn-custom" id="btn_sendSMS" onclick="sendSMSToPhone()"><h5>获取验证码</h5></div>
                </div>
            </div>
            <div class="btn-custom" id="confirm_verify" onclick="confirm_verify_phone()">
                <i class="fa fa-refresh fa-spin" id="loading"></i>
                <h4 style="margin: 5px 0px">立即认证</h4>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--code authorization dialog-->
<div class="modal custom-modal" id="code_auth">
    <div class="modal-dialog" id="code_auth_dialog">
        <div class="modal-content" style="height: 100%;border-radius: 12px;">
            <div class="modal-header" id="code_auth_title" style="padding: 0px; margin: 0px;border: none">
                <button class="close" onclick="code_auth_dialog_close()">
                    <span aria-hidden="true">×</span>
                </button>
                <img src="resource/image/top.png" style="width: 100%">
                <div style="text-align: center">
                    <img src="resource/image/activation.png">
                    <h4>授权激活</h4>
                </div>
            </div>
            <div class="modal-body" id="code_auth_content">
                <div style="margin: 20px 0px">
                    <h5 style="float: left; margin-left: 5px; margin-right: 15px">授权码</h5>
                    <input type="text" class="form-control" id="auth_code" placeholder="" style="width: 70%" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div>
            </div>
            <div id="auth_error" style="text-align:center; height:20px; display: none">
                <h4>授权码有误，请重新输入！</h4>
            </div>
            <div style="margin: 10px 20px; height: 50px">
                <div class="btn-custom" id="btn_cancel"  onclick="OnCancelauthcodeVerify()" >
                    <h5 style="margin: 5px 0px">取消</h5>
                </div>
                <div class="btn-custom" id="btn_ok" onclick="OnConfirmauthCode()" >
                    <h5 style="margin: 5px 0px">确认</h5>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

</body>
</html>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    var shop_id = <?php echo $shop_id?>;
    var cur_tourism_id = <?php echo $target_id?>;

    function weixinConfigure_tourism(){
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
        wx.config({
            debug: false,
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: <?php echo $signPackage["timestamp"];?>,
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'checkJsApi',
                'onMenuShareTimeline',//
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo'
            ]
        });

        window.share_config = {
            "share": {
                "imgUrl": "http://www.ayoubc.com/tour/plugin/Wxpay/resource/image/logo.png",//分享图，默认当相对路径处理，所以使用绝对路径的的话，“http://”协议前缀必须在。
                "desc" : window.location.href,//摘要,如果分享到朋友圈的话，不显示摘要。
                "title" : 'A游不错(' + document.title + ')',//分享卡片标题
                "link": window.location.href,//分享出去后的链接，这里可以将链接设置为另一个页面。
                "success":function(){//分享成功后的回调函数
                },
                'cancel': function () {
                    // 用户取消分享后执行的回调函数
                }
            }
        };
        wx.ready(function () {
            // 在这里调用 API
            wx.onMenuShareAppMessage(share_config.share);//分享给好友
            wx.onMenuShareTimeline(share_config.share);//分享到朋友圈
            wx.onMenuShareQQ(share_config.share);//分享给手机QQ
        });
    }

    function buy_course_dialog_close(){
        $('#buy_course').hide();
    }

    function code_auth_dialog_close(){
        $('#code_auth').hide();
    }

</script>