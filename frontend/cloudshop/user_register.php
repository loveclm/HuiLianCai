<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white;">
<div class="col-md-offset-4 col-md-4 col-xs-offset-0 col-xs-12 user_register">
    <div class="form-group form-md-line-input custom_row">
        <input type="number" class="form-control" id="phone_number" placeholder="该手机作为登录账号和联系方式"
    	    title="请输入便利店手机号。">
        <label for="phone_number">手机号</label>
    </div>
    <div class="form-group form-md-line-input custom_row">
        <input type="number" class="form-control" id="auth_code" placeholder="请输入验证码"
               style="font-size:10pt !important;" title="请输入6位验证码。">
        <label for="auth_code">验证码</label>
        <span id="sms_button" onclick="sendingSMS()">获取验证码</span>
    </div>
    <div class="form-group form-md-line-input custom_row">
        <input type="password" class="form-control" id="passwd" placeholder="请输入您的密码"
    	    title="请输入6-20位密码。">
        <label for="passwd">密码</label>
    </div>
    <div class="form-group form-md-line-input custom_row">
        <input type="password" class="form-control" id="confirm_passwd" placeholder="请再次输入您的密码"
               style="padding-left: 75px!important;" title="两次输入的密码应该一致。">
        <label for="confirm_passwd">确认密码</label>
    </div>
    <div class="form-group form-md-line-input custom_row">
        <input type="number" class="form-control" id="servant_phone" placeholder="请输入推荐人的手机号"
    	    title="业务员手机号不能与便利店手机号一样。">
        <label for="servant_phone">业务员手机号</label>
    </div>

    <div id="confirm_register" class="btn_login" onclick="">完成注册</div>
</div>

</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
        document.title = '注册';
    })

    function OnOk() {
        $('#auth_question').modal('hide');
        // jump individual information input page
        window.location.href = 'user_register_detail.php';
    }

    function OnCancel() {
        $('#auth_question').modal('hide');
        // jump first page
        window.location.href = 'home.php';
    }
</script>
</html>
