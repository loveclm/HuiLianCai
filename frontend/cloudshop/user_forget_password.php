<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white;">
    <img class="col-md-offset-5 col-md-2 col-xs-offset-3 col-xs-6" id="reg_logo" src="assets/images/logo.png">
    <div class="col-md-offset-4 col-md-4 col-xs-offset-1 col-xs-10">
        <div class="form-group form-md-line-input custom_row">
            <input type="number" class="form-control" id="phone_number">
            <label for="phone_number">手机号</label>
        </div>
        <div class="form-group form-md-line-input custom_row">
            <input type="text" class="form-control" id="auth_code">
            <label for="auth_code">验证码</label>
            <span id="sms_button"  onclick="sendingSMS()">获取验证码</span>
        </div>
        <div style="height: 25px"></div>
        <div id="confirm_verify" class="btn_login" onclick="">下一步</div>
    </div>

</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>

<script type="text/javascript">
    function OnNext() {
        if( !confirm_verifyPhone()) return;
        var phone_num = $('#phone_number').val();
        setPhoneNumber(phone_num);

        window.location.href = 'user_new_password.php';
    }
</script>

</html>
