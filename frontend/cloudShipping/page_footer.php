<div id="auth_question" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-body" style="text-align: center">
        <b>注册成功，请您及时进行认证！</b>
    </div>
    <div class="modal-footer">
        <div class="btn_custom" onclick="OnOk()">立即认证</div>
        <div class="btn_custom btn-default" onclick="OnCancel()">跳过</div>
    </div>
    <br>
</div>

<div id="message_dialog" class="modal fade" tabindex="-1" data-backdrop="basic"
     data-keyboard="false" style="background-color: rgba(255,255,255,0.85);">
    <div class="modal-body">
    </div>
    <div class="modal-footer" style="border: none;">
        <button id="msg_cancel" type="button" class="btn_custom btn-default"
                onclick="$('#message_dialog').modal('hide');">取消
        </button>
        <button id="msg_ok" type="button" class="btn_custom"
                onclick="onOk()">确定
        </button>
    </div>
    <br>
</div>

<div id="message_loading" class="modal fade" tabindex="-1"
     data-backdrop="basic" data-keyboard="false">
    <div class="modal-body">
    </div>
    <br>
</div>
<div id="notification_bar"></div>
<div id="notification_alert"
     style="width: 100%; text-align: center; bottom:60px; position:absolute;">
    <span id="notification_alert_bar"></span>
</div>
<div class="custom-data-confirm-class" style="display:none;"></div>

<script src="assets/global/plugins/owl-carousel/owl.carousel.js"></script>
<script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!--<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>-->
<!--<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>-->
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!--<script src="assets/pages/scripts/ui-extended-modals.min.js" type="text/javascript"></script>-->
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<!--<script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>-->
<!--<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>-->
<!--<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>-->

<script type="text/javascript" src="assets/js/global_restapi.js"></script>
<script type="text/javascript" src="assets/js/global.js"></script>

<script type="text/javascript">

    var pageShopId = parseInt(<?=$pageShopId?>);
    var pageItemType = parseInt(<?=$pageItemType?>);
    var pageItemId = parseInt(<?=$pageItemId?>);
    var pageItemIdTxt = <?=$pageItemId?>;
    
    var restSessionSize = 1024 * 1024 * 1 - (JSON.stringify(sessionStorage)).length;

    function weixinConfigure() {
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
//        wx.config({
//            debug: false,
//            appId: '<?//= $signPackage["appId"];?>//',
//            timestamp: <?//= $signPackage["timestamp"];?>//,
//            nonceStr: '<?//= $signPackage["nonceStr"];?>//',
//            signature: '<?//= $signPackage["signature"];?>//',
//            jsApiList: [
//                // 所有要调用的 API 都要加到这个列表中
//                'checkJsApi',
//                'onMenuShareTimeline',//
//                'onMenuShareAppMessage',
//                'onMenuShareQQ',
//                'onMenuShareWeibo'
//            ]
//        });
    }

</script>