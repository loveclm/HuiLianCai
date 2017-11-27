<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white">
<img class="col-md-offset-5 col-md-2 col-xs-offset-3 col-xs-6" id="reg_logo" src="assets/images/logo.png">
<div class="col-md-offset-4 col-md-4 col-xs-offset-1 col-xs-10">
    <div class="form-group form-md-line-input custom_row">
        <input type="text" class="form-control" maxlength="20" id="phone_number">
        <label for="phone_number">账号</label>
    </div>
    <div class="form-group form-md-line-input custom_row">
        <input type="password" class="form-control" maxlength="20" id="passwd">
        <label for="passwd">密码</label>
    </div>
    <div id="forget_password" onclick="javascript: window.location.href='user_forget_password.php'">忘记密码？</div>
    <div id="confirm_login" class="btn_login" onclick="">登录</div>
<!--    <div id="register" onclick="location.href='user_register.php'"><u>没有账号？立即注册</u></div>-->

</div>
</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>

</html>
