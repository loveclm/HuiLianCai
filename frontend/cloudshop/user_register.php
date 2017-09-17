<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>注册</title>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="../../assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="../../assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="../../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="../../assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/layouts/layout/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="../../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link href="../../css/style.css" rel="stylesheet" type="text/css">
    <!-- END THEME LAYOUT STYLES -->
    <style type="text/css">
        .custom_row input{
            font-size: 12px!important;
            padding-left: 50px!important;
        }
        .custom_row label{
            font-size: 15px!important;
            margin-top: 35px;
        }
    </style>
</head>
<body>
    <div class="col-md-offset-4 col-md-4 col-xs-offset-1 col-xs-10">
        <div class="form-group form-md-line-input custom_row">
            <input type="number" class="form-control" id="phone_number" placeholder="该手机作为登录账号和联系方式">
            <label for="phone_number">手机号</label>
        </div>
        <div class="form-group form-md-line-input custom_row">
            <input type="text" class="form-control" id="auth_code" placeholder="该验证码为您的初始登录密码">
            <label for="auth_code">验证码</label>
            <span id="sms_button" onclick="sendingSMS()">获取验证码</span>
        </div>
        <div class="form-group form-md-line-input custom_row">
            <input type="text" class="form-control" id="Servant_phone" placeholder="请输入推荐人的手机号">
            <label for="Servant_phone">业务员手机号</label>
        </div>
        <div class="form-group form-md-line-input custom_row">
            <input type="password" class="form-control" id="passwd">
            <label for="passwd">密码</label>
        </div>
        <div class="form-group form-md-line-input custom_row">
            <input type="password" class="form-control" id="confirm_passwd" style="padding-left: 65px!important;">
            <label for="confirm_passwd">确认密码</label>
        </div>

        <div class="btn_login" onclick="OnRegister()">完成注册</div>
     </div>

    <div id="auth_question" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body" style="text-align: center">
            <b>注册成功，请您及时进行认证！</b>
        </div>
        <div class="modal-footer">
            <div class="btn_custom" onclick="OnOk()">立即认证</div>
            <div class="btn_custom" id="cancel_button" onclick="OnCancel()">跳过</div>
        </div>
    </div>
    <div  id="message_dialog" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body" style="text-align: center">
            <b>您好，已将验证码发送到您的手机，请注意查收</b>
        </div>
    </div>
    <!-- BEGIN CORE PLUGINS -->
    <script src="../../assets/global/plugins/respond.min.js"></script>
    <script src="../../assets/global/plugins/excanvas.min.js"></script>
    <script src="../../assets/global/plugins/ie8.fix.min.js"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>


    <script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="../../assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
    <script src="../../assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="../../assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../../assets/pages/scripts/ui-extended-modals.min.js" type="text/javascript"></script>
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
        function OnOk() {
            $('#auth_question').modal('hide');
            // jump individual information input page
            window.location.href = 'individual_register.html';
        }
        function  OnCancel() {
            $('#auth_question').modal('hide');
            // jump first page
            window.location.href = '../../index.html';
        }

        function sendingSMS() {
            var phone_num = $('#phone_number').val();
            if( phone_num == "" || phone_num.length != 11){
                alert('手机号不正确。');
                return;
            }
            // show the prompt '您好，已将验证码发送到您的手机，请注意查收'
            showMessage('您好，已将验证码发送到您的手机，请注意查收');
            //send message sending request to server
            sendSMSToServer(phone_num);
        }
    </script>
</body>
</html>
