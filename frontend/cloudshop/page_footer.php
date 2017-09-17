
<div class="page-footer">
    <div class="bottom_item" onclick="selectBottomItem(1)">
        <img src="assets/images/tabbar_icon1_d@3x.png" class="bottom_item_image" id="bottom_item_image1">
        <h5 id="bottom_item_text1" style="color: #38abff">拼货</h5>
    </div>
    <div class="bottom_item"  onclick="selectBottomItem(2)">
        <img src="assets/images/tabbar_icon2_n@3x.png" class="bottom_item_image" id="bottom_item_image2">
        <h5 id="bottom_item_text2" >购物车</h5>
    </div>
    <div class="bottom_item"  onclick="selectBottomItem(3)">
        <img src="assets/images/tabbar_icon3_n@3x.png" class="bottom_item_image" id="bottom_item_image3">
        <h5 id="bottom_item_text3" >消息</h5>
    </div>
    <div class="bottom_item"  onclick="selectBottomItem(4)">
        <img src="assets/images/tabbar_icon4_n@3x.png" class="bottom_item_image" id="bottom_item_image4">
        <h5 id="bottom_item_text4" >我的</h5>
    </div>
</div>
<div id="menu_dialog" class="modal" tabindex="-1" data-backdrop="basic" data-keyboard="false">
    <div class="modal-body">
    </div>
</div>
<div id="message_dialog" class="modal" tabindex="-1" data-backdrop="basic" data-keyboard="false">
    <div class="modal-body">
    </div>
    <div class="modal-footer" style="border: none;">
        <button type="button" class="btn btn-primary" onclick="">确认</button>
        <button type="button" class="btn btn-default" onclick="$('#message_dialog').hide();">取消</button>
    </div>
</div>
<div id="notification_bar"></div>
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/pages/scripts/ui-extended-modals.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

<script type="text/javascript" src="assets/js/global.js"></script>

<script type="text/javascript">

    var shop_id = <?=$shop_id?>;
    var target_type = <?=$type?>;
    var targetid = <?=$target_id?>;

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