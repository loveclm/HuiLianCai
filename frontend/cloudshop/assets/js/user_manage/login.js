// login user
$(document).ready(function () {
    if (getPhoneNumber() != '') {
        if (document.getElementById('phone_number') != null) {
            $('#phone_number').val(getPhoneNumber());
            //document.getElementById('phone_number').disabled = true;
            $('#phone_number').attr({'style': 'font-size:20pt !important;'})
        }
    }

    $('#phone_number').on('input focus change', function () {
        var phone_number = $('#phone_number').val().toString()
        if (phone_number == '')
            $('#phone_number').attr({'style': 'font-size:10pt'})
        else
            $('#phone_number').attr({'style': 'font-size:20pt !important;'})
        if (phone_number.length != 11) {
            $('#phone_number').val(phone_number.substr(0, 11));
        }
    })
    $('#auth_code').on('input focus change', function () {
        var auth_code = $('#auth_code').val().toString()
        if (auth_code == '')
            $('#auth_code').attr({'style': 'font-size:10pt'})
        else
            $('#auth_code').attr({'style': 'font-size:20pt !important;'})
        if (auth_code.length != 6) {
            $('#auth_code').val(auth_code.substr(0, 6));
        }
    })
    $('#servant_phone').on('input focus change', function () {
        var servant = $('#servant_phone').val().toString()
        if (servant == '')
            $('#servant_phone').attr({'style': 'font-size:10pt'})
        else
            $('#servant_phone').attr({'style': 'font-size:20pt !important;'})
        if (servant.length != 11) {
            $('#servant_phone').val(servant.substr(0, 11));
        }
    })
    $('#passwd').on('input focus change', function () {
        var passwd = $('#passwd').val().toString()
        if (passwd == '')
            $('#passwd').attr({'style': 'font-size:10pt'})
        else
            $('#passwd').attr({'style': 'font-size:20pt !important;'})
        if (passwd.length > 20) {
            $('#passwd').val(passwd.substr(0, 20));
        }
    })
    $('#old_passwd').on('input focus change', function () {
        var passwd = $('#old_passwd').val().toString()
        if (passwd == '')
            $('#old_passwd').attr({'style': 'font-size:10pt'})
        else
            $('#old_passwd').attr({'style': 'font-size:20pt !important;'})
        if (passwd.length > 20) {
            $('#old_passwd').val(passwd.substr(0, 20));
        }
    })
    $('#confirm_passwd').on('input focus change', function () {
        var passwd = $('#confirm_passwd').val().toString()
        if (passwd == '')
            $('#confirm_passwd').attr({'style': 'font-size:10pt !important; padding-left:75px !important'})
        else
            $('#confirm_passwd').attr({'style': 'font-size:20pt !important; padding-left:75px !important'})
        if (passwd.length > 20) {
            $('#confirm_passwd').val(passwd.substr(0, 20));
        }
    })
    $('input').on('input focus change', function () {
        var phone = $('#phone_number').val() != undefined ? $('#phone_number').val().toString().length : 0
        var auth = $('#auth_code').val() != undefined ? $('#auth_code').val().toString().length : 0
        var servant = $('#servant_phone').val() != undefined ? $('#servant_phone').val().toString().length : 0
        var old_passwd = ($('#old_passwd').val() != undefined) ? $('#old_passwd').val().toString() : ''
        var passwd = ($('#passwd').val() != undefined) ? $('#passwd').val().toString() : ''
        var cpasswd = $('#confirm_passwd').val() != undefined ? $('#confirm_passwd').val().toString() : ''
        if (phone == 11 && passwd.length >= 6) {
            $('#confirm_login').attr({onclick: 'OnLogin()', style: 'background:#38abff'})
        }
        else {
            $('#confirm_login').attr({onclick: '', style: 'background:darkgrey'})
        }

        if (phone == 11 && auth == 6) {
            $('#confirm_verify').attr({onclick: 'OnNext()', style: 'background:#38abff'})
        } else {
            $('#confirm_verify').attr({onclick: '', style: 'background:darkgrey'})
        }

        if (passwd.length >= 6 && passwd == cpasswd) {
            $('#confirm_reset_pwd').attr({onclick: 'OnResetPassword()', style: 'background:#38abff'})
        } else {
            $('#confirm_reset_pwd').attr({onclick: '', style: 'background:darkgrey'})
        }

        if (old_passwd.length>=6 && passwd.length >= 6 && passwd == cpasswd) {
            $('#my_renew_pwd').attr({onclick: 'OnRenewPassword()', style: 'background:#38abff'})
        } else {
            $('#my_renew_pwd').attr({onclick: '', style: 'background:darkgrey'})
        }

        if (phone == 11 && auth == 6 && passwd.length >= 6 &&
            (servant == 0 || servant == 11) && passwd == cpasswd) {
            $('#confirm_register').attr({onclick: 'OnRegister()', style: 'background:#38abff'})
        } else {
            $('#confirm_register').attr({onclick: '', style: 'background:darkgrey;'})
        }
        console.log(phone)
    })
})

function OnLogin() {
    // first, send login request to server
    // second, when receive response, if successful then SUCCESS else FAIL
    // third, store login result to sessionStorage
    var phone_number = $('#phone_number').val();
    var password = $('#passwd').val();

    if ((phone_number == "") || (password == "")) {
        showMessage('手机号/密码不能为空。');
        return;
    }
    if (phone_number.length != 11) {
        showMessage('手机号不正确。');
        return;
    }
    if (password.length < 6 || password.length > 20) {
        showMessage('密码不正确。');
        return;
    }
    sendLoginRequest(phone_number, password);
}

// register user
function OnRegister() {
    if (!confirm_verifyPhone()) return;

    var passwd = $('#passwd').val();
    var confirm_passwd = $('#confirm_passwd').val();
    if (passwd == "") {
        showMessage('密码不能为空。');
        return;
    }

    if (passwd.length < 6 || passwd.length > 20) {
        showMessage('密码格式错误！');
        return;
    }

    if (passwd != confirm_passwd) {
        showMessage('确认密码错误。');
        return;
    }

    // check the availability of the servant's mobile number
    var servant_phone = $('#servant_phone').val();

    var phone_number = $('#phone_number').val();

    sendRegisterRequest(phone_number, passwd, servant_phone);
}

// check phone verify code
function confirm_verifyPhone() {
    // if (app_data.timerID == undefined) {
    //     showMessage('你没有发短信。');
    //     return false;
    // }

    restoreSMSButton();

    var auth_code = $('#auth_code').val();
    if (auth_code != app_data.sms_code || app_data.sms_code == "") {
        showMessage('验证码错误。');
        return false;
    }
    return true;
}

// reset password
function OnResetPassword() {
    app_data.phone_num = getPhoneNumber();
    if (app_data.phone_num == undefined || app_data.phone_num.length != 11) {
        showMessage('手机号错误！');
        return;
    }

    var password = $('#passwd').val();
    var confirm_password = $('#confirm_passwd').val();

    if (password == "") {
        showMessage('密码不能为空！');
        return;
    }

    if (password.length < 6 || password.length > 20) {
        showMessage('密码格式错误！');
        return;
    }

    if (password != confirm_password) {
        showMessage('确认密码错误！');
        return;
    }

    sendSetforgetPassword(app_data.phone_num, password);
}

// reset password
function OnRenewPassword() {
    app_data.phone_num = getPhoneNumber();
    if (app_data.phone_num == undefined || app_data.phone_num.length != 11) {
        showMessage('手机号错误！');
        return;
    }

    var password = $('#passwd').val();
    var confirm_password = $('#confirm_passwd').val();
    var old_password = $('#old_passwd').val();

    if (old_password == "") {
        showMessage('旧密码不能为空！');
        return;
    }

    if (old_password.length < 6 || old_password.length > 20) {
        showMessage('旧密码格式错误！');
        return;
    }

    if (password == "") {
        showMessage('密码不能为空！');
        return;
    }

    if (password.length < 6 || password.length > 20) {
        showMessage('密码格式错误！');
        return;
    }

    if (password != confirm_password) {
        showMessage('确认密码错误！');
        return;
    }

    sendSetforgetPassword(app_data.phone_num, password, old_password);
}
