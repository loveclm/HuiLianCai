<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white;">
    <div class="col-md-offset-4 col-md-4 col-xs-offset-0 col-xs-12">
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
<!--        <div class="form-group form-md-line-input custom_row">-->
<!--            <input type="password" class="form-control" id="passwd">-->
<!--            <label for="passwd">密码</label>-->
<!--        </div>-->
<!--        <div class="form-group form-md-line-input custom_row">-->
<!--            <input type="password" class="form-control" id="confirm_passwd" style="padding-left: 65px!important;">-->
<!--            <label for="confirm_passwd">确认密码</label>-->
<!--        </div>-->

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

</body>

    <?php include('page_footer.php'); ?>

    <script src="assets/js/user_manage/login.js" type="text/javascript"></script>

    <script type="text/javascript">
        function OnOk() {
            $('#auth_question').modal('hide');
            // jump individual information input page
            window.location.href = 'user_register_detail.php';
        }
        function  OnCancel() {
            $('#auth_question').modal('hide');
            // jump first page
            window.location.href = '../index.php';
        }

        function sendingSMS() {
            var phone_num = $('#phone_number').val();
            if( phone_num == "" || phone_num.length != 11){
                showMessage('手机号不正确。');
                return;
            }
            // show the prompt '您好，已将验证码发送到您的手机，请注意查收'
            showMessage('您好，已将验证码发送到您的手机，请注意查收。');
            //send message sending request to server
            sendSMSToServer(phone_num);
        }
    </script>
</html>
