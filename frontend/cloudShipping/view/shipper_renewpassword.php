<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body style="background: white;">
<div class="col-md-offset-4 col-md-4 col-xs-offset-0 col-xs-12">
    <div class="form-group form-md-line-input custom_row my_pwd">
        <input type="number" class="form-control" id="old_password" placeholder="请输入旧密码">
        <label for="old_password" class="my_pwd">旧密码</label>
    </div>
    <div class="form-group form-md-line-input custom_row my_pwd">
        <input type="text" class="form-control" id="new_password" placeholder="请输入新密码">
        <label for="new_password" class="my_pwd">新密码</label>
    </div>
    <div class="form-group form-md-line-input custom_row my_pwd">
        <input type="text" class="form-control" id="new_cpassword" placeholder="请再次输入新密码">
        <label for="new_cpassword" class="my_pwd">确认密码</label>
    </div>
    <div class="btn_login my_pwd" onclick="OnRegister()">完成</div>
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

    function OnCancel() {
        $('#auth_question').modal('hide');
        // jump first page
        window.location.href = '../index.php';
    }

    function sendingSMS() {
        var phone_num = $('#phone_number').val();
        if (phone_num == "" || phone_num.length != 11) {
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
