<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white">
<div class="col-md-12 col-sm-12" style="text-align: center;">
    <img class="reg_logo" id="reg_logo" src="assets/images/logo.png">
</div>
<div id="main_body_panel" style="width:calc(100vw);height:calc(100vh);position:absolute;top:0;left:0;opacity:0;"></div>
<div class="col-md-offset-4 col-md-4 col-xs-offset-1 col-xs-10">
    <div class="form-group form-md-line-input custom_row">
        <input type="number" class="form-control" maxlength="11" min="0" id="phone_number"
    		title="请输入便利店手机号。"
               style="font-size:28px !important;">
        <label for="phone_number">手机号</label>
    </div>
    <div class="form-group form-md-line-input custom_row">
        <input type="password" class="form-control" maxlength="20" id="passwd"
    		title="请输入6-20位密码。"
               style="font-size: 28px;">
        <label for="passwd">密码</label>
    </div>
    <div id="forget_password" onclick="location.href='user_forget_password.php'">忘记密码？</div>
    <div id="confirm_login" class="btn_login" onclick="">登录</div>
    <div id="register" onclick="location.href='user_register.php'"><u>没有账号？立即注册</u></div>

</div>

<?php include('page_footer.php'); ?>
</body>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        document.title = '登录';
    })
</script>
</html>
