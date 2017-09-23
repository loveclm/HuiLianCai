// login user
function OnLogin() {
    // first, send login request to server
    // second, when receive response, if successful then SUCCESS else FAIL
    // third, store login result to sessionStorage
    var phone_number = $('#phone_number').val();
    var password = $('#passwd').val();

    if( (phone_number == "") || (password == "")){
        showMessage('手机号/密码不能为空。');
        return;
    }
    if(phone_number.length != 11) {
        showMessage('手机号不正确。');
        return;
    }
    if(password.length < 6 || password.length > 20){
        showMessage('密码不正确。');
        return;
    }
    sendLoginRequest(phone_number, password);
}

// register user
function OnRegister() {
    if( !confirm_verifyPhone()) return;

    var password = $('#passwd').val();
    var confirm_password = $('#confirm_passwd').val();
    if( password == "") {
        showMessage('密码不能为空。');
        return;
    }

    if(password.length < 6 || password.length > 20){
        showMessage('密码格式错误!');
        return;
    }

    if(password != confirm_password){
        showMessage('确认密码错误。');
        return false;
    }

    // check the availability of the servant's mobile number
    var servant_phone = $('#Servant_phone').val();
    if( servant_phone == "" || servant_phone.length != 11){
        showMessage('需要业务员手机号码。')
        return;
    }

    var phone_number = $('#phone_number').val();
    var password = $('#passwd').val();

    sendRegisterRequest(phone_number, password, servant_phone);
}

// check phone verify code
function confirm_verifyPhone() {
    if(app_data.timerID == undefined){
        showMessage('你没有发短信。');
        return false;
    }

    restoreSMSButton();

    var auth_code = $('#auth_code').val();
    if( auth_code != app_data.sms_code || app_data.sms_code == ""){
        showMessage('验证码错误。');
        return false;
    }

    return true;
}

// reset password
function OnResetpassword(){
    app_data.phone_num = sessionStorage.getItem('phone_num');
    if( app_data.phone_num == undefined || app_data.phone_num.length != 11){
        showMessage('手机号错误。');
        return;
    }

    var password = $('#passwd').val();
    var confirm_password = $('#confirm_passwd').val();

    if( password == "") {
        showMessage('密码不能为空。');
        return;
    }

    if(password.length < 6 || password.length > 20){
        showMessage('密码格式错误!');
        return;
    }

    if( password != confirm_password){
        showMessage('确认密码错误。');
        return;
    }

    sendSetforgetPassword(app_data.phone_num, password);
}
