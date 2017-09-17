<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>忘记密码</title>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css">
    <link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
    <link href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="../../assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css">
    <link href="../../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="../../assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="../../assets/layouts/layout/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color">
    <link href="../../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/style.css" rel="stylesheet" type="text/css">
    <!-- END THEME LAYOUT STYLES -->
</head>
<body>
    <img class="col-md-offset-5 col-md-2 col-xs-offset-3 col-xs-6" id="reg_logo" src="../../images/logo.png">
    <div class="col-md-offset-4 col-md-4 col-xs-offset-1 col-xs-10">
        <div class="form-group form-md-line-input custom_row">
            <input type="password" class="form-control" id="passwd">
            <label for="passwd">新密码</label>
        </div>
        <div class="form-group form-md-line-input custom_row">
            <input type="password" class="form-control" id="confirm_passwd">
            <label for="confirm_passwd">确认密码</label>
        </div>
        <div style="height: 25px"></div>
        <div class="btn_login" onclick="OnResetpassword()">完成</div>
    </div>
    <div  id="message_dialog" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body" style="text-align: center">
            <b>您好，已将验证码发送到您的手机，请注意查收</b>
        </div>
    </div>

</body>
<!-- BEGIN CORE PLUGINS -->
<script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="../../assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="../../assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="../../assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="../../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="../../assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script src="../../js/global.js" type="text/javascript"></script>
<script src="../../js/user_manage/login.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        app_data.phone_num = sessionStorage.getItem('phone_num');
    });
</script>
</html>
