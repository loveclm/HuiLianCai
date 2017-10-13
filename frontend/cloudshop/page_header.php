
<?php
//require_once "jssdk.php";
//$jssdk = new JSSDK("wxb042726847dca8d3", "70e43300732636e813e59f8b2199dfc9");
//$signPackage = $jssdk->GetSignPackage();

$pageShopId = 0;
$pageItemType = 0;
$pageItemId = 0;

if (isset($_GET['sId'])) $pageShopId = $_GET['sId'];
if (isset($_GET['iType'])) $pageItemType = $_GET['iType'];
if (isset($_GET['iId'])) $pageItemId = $_GET['iId'];
?>


<head>
    <title>惠联采</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon"/>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
<!--    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css"> -->
    <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!--    <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">-->
<!--    <link rel="stylesheet" href="assets/global/plugins/bootstrap/css/bootstrap.min.css">-->
    <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" />
    <link href="assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css">
<!--    <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css">-->
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
<!--    <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>-->
<!--    <link href="assets/layouts/layout/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color"/>-->
<!--    <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css"/>-->

    <link rel="stylesheet" href="assets/global/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/global/plugins/owl-carousel/owl.theme.css">

    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>

    <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- END THEME LAYOUT STYLES -->

</head>

