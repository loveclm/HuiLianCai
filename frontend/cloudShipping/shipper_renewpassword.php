<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white;">
<div class="col-md-offset-4 col-md-4 col-xs-offset-0 col-xs-12">
<div class="form-group form-md-line-input custom_row my_pwd">
<input type="password" class="form-control"
id="old_passwd" placeholder="请输入旧密码"
title="请输入6-20位旧密码。">
<label for="old_passwd" class="my_pwd">旧密码</label>
</div>
<div class="form-group form-md-line-input custom_row my_pwd">
<input type="password" class="form-control" id="passwd"
placeholder="请输入新密码"
title="请输入6-20位新密码。">
<label for="passwd" class="my_pwd">新密码</label>
</div>
<div class="form-group form-md-line-input custom_row my_pwd">
<input type="password" class="form-control" id="confirm_passwd"
placeholder="请再次输入新密码"
title="两次输入的新密码应该一致。">
<label for="confirm_passwd" class="my_pwd">确认密码</label>
</div>

<div id="my_renew_pwd" class="btn_login my_pwd" onclick="OnRegister()">完成</div>
</div>

</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function () {
document.title = '修改密码';
})

</script>
</html>
