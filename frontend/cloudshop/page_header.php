<?php

ini_set('date.timezone', 'Asia/Shanghai');

$pageShopId = 0;
$pageItemType = 0;
$pageItemId = 0;

if (isset($_GET['sId'])) $pageShopId = $_GET['sId'];
if (isset($_GET['iType'])) $pageItemType = $_GET['iType'];
if (isset($_GET['iId'])) $pageItemId = $_GET['iId'];
?>


<head>
    <title>惠联彩</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!--    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css"> -->
    <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--    <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">-->
    <!--    <link rel="stylesheet" href="assets/global/plugins/bootstrap/css/bootstrap.min.css">-->
    <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet"
          type="text/css"/>
    <link href="assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css">
    <!--    <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css">-->
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!--    <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>-->
    <!--    <link href="assets/layouts/layout/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color"/>-->
    <!--    <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css"/>-->

    <link rel="stylesheet" href="assets/global/plugins/jquery-ui/jquery-ui.css">

    <link rel="stylesheet" href="assets/global/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/global/plugins/owl-carousel/owl.theme.css">

    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>

<!--    <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>-->
    <script src="assets/js/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/jquery-ui/jquery-ui.js"></script>

    <!-- END THEME LAYOUT STYLES -->
    <script type="text/javascript">

        var pageShopId = parseInt(<?php echo $pageShopId;?>); //sId - int
        var pageItemType = parseInt(<?php echo $pageItemType;?>); //iType - int
        var pageItemId = parseInt(<?php echo $pageItemId;?>); //iId -int
        var pageType =<?php echo $pageItemId;?>; //iId - string

        var restSessionSize = 1024 * 1024 * 1 - (JSON.stringify(sessionStorage)).length;

        if (pageShopId == 100) {
            document.write('<script type="text/undefined">');
            $('body').html('');
            document.title = '';
            location.href = "home.php";

            //return;
        }

        $(function () {
            clearInterval();
            if (getPhoneNumber()!='' && document.title!='登录')
                sendLoginRequest(getPhoneNumber(), getSessionPassword(), 0);
            setInterval(function () {
                console.log(localStorage.getItem('hlc_token'));
                if (document.title != '终端便利店认证')
                    sendLoginRequest(getPhoneNumber(), getSessionPassword(), 0);
            }, 20000);

        })

    </script>

</head>

