<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white;">
<div class="col-md-12 col-sm-12" style="text-align: center;">
    <img class="reg_logo" id="reg_logo" src="assets/images/logo.png">
</div>
<div class="col-md-offset-4 col-md-4 col-xs-offset-1 col-xs-10">
    <div class="form-group form-md-line-input custom_row">
        <input type="password" class="form-control" id="passwd" title="请输入6-20位密码。">
        <label for="passwd">新密码</label>
    </div>
    <div class="form-group form-md-line-input custom_row">
        <input type="password" class="form-control" id="confirm_passwd" title="两次输入的密码应该一致。">
        <label for="confirm_passwd">确认密码</label>
    </div>
    <div style="height: 25px"></div>
    <div id="confirm_reset_pwd" class="btn_login" onclick="">完成</div>
</div>

</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function () {
        app_data.phone_num = getPhoneNumber();
    });
</script>
</html>
