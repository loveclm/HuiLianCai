<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white;">
    <img class="col-md-offset-5 col-md-2 col-xs-offset-3 col-xs-6" id="reg_logo" src="assets/images/logo.png">
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

</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        app_data.phone_num = sessionStorage.getItem('phone_num');
    });
</script>
</html>
