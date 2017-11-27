///  main api file.

var REMOTE_API_URL = 'http://www.huiliancai.com/backend/';
//var MY_API_URL = 'http://www.huiliancai.com/shipping/';

//var REMOTE_API_URL = 'http://192.168.2.15/huiliancai/backend/';
var MY_API_URL = 'http://192.168.2.18/shipping/';

var HLC_REAL_MODE = 0; //0-real environment
var HLC_SIMUL_MODE = 1; //1-real environment

var HLC_LOGIN_MODE = HLC_REAL_MODE;
var HLC_SMS_MODE = HLC_SIMUL_MODE;
var HLC_APP_MODE = HLC_REAL_MODE;

var LANG_DATA = {
    'server_error': '网络连接失败，请稍后重试。',
};

function sendSMSToServer(phoneNumber) {

//send SMS sending request in backend server.
    $('#loading').css({display: 'block'});
    $.ajax({
        type: 'POST',
        url: MY_API_URL + 'sms/SendTemplateSMS.php', //rest API url
        dataType: 'json',
        data: {'phoneNumber': phoneNumber}, // set function name and parameters
        success: function (data) {
// get SMS code from received data
            $('#loading').css({display: 'none'});
            if (data['result'] == "success") {
                app_data.sms_code = data['code'];
// show the prompt '您好，已将验证码发送到您的手机，请注意查收'
                showMessage('您好，已将验证码发送到您的手机，请注意查收。');
                if (HLC_SMS_MODE == HLC_SIMUL_MODE) {
                    showMessage('SMS Testing success.')
                    setTimeout(function () {
                        $('#auth_code').val(data['code']);
                    }, 1500)
                }
            } else {
                app_data.sms_code = "";
                showMessage(data.error['0']);
            }
        },
        fail: function () {
            showMessage(LANG_DATA.server_error);
        }
    });
}

//send user register request to server
function sendRegisterRequest(phoneNumber, passwd, servantPhone) {
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/register', //rest API url
        dataType: 'json',
        data: {'phone': phoneNumber, 'password': passwd, 'saleman': servantPhone}, // set function name and parameters
        success: function (data) {
            if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showMessage('信息错误。')
                        break;
                    case 2:
                        showMessage('这个用户已存在。')
                        break;
                    default:
                        showMessage('账号信息错误。');
                        break;
                }
                setRegisterStatus(false)
            } else {
                // setPhoneNumber(phoneNumber)
                setRegisterStatus(true)
                showAuthRequire('注册成功，<br>请您及时进行认证！')
            }
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                showMessage('Register testing success.')
                setTimeout(function () {
                    // setPhoneNumber(phoneNumber);
                    setRegisterStatus(true);
                    showAuthRequire('注册成功，<br>请您及时进行认证！')
                }, 1500)
            }
        },
        error: function (data) {
            showMessage(LANG_DATA.server_error);
            // setPhoneNumber('')
            setRegisterStatus(false)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                showMessage('Register testing success.')
                setTimeout(function () {
                    // setPhoneNumber(phoneNumber);
                    setRegisterStatus(true);
                    showAuthRequire('注册成功，<br>请您及时进行认证！')
                }, 1500)
            }
        }
    });
}

// upload user's individual information to server
function sendUploadUserInfo(userinfo) {
// upload two files.
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/upload_auth', //rest API url
        dataType: 'json',
        data: {
            'phone': userinfo.phone,
            'type': userinfo.shop_type,
            'name': userinfo.shop_name,
            'address': userinfo.shop_addr,
            'contact_name': userinfo.contact_person,
            'contact_phone': userinfo.contact_person_phone,
            'logo': userinfo.shop_logo_img,
            'cert_num': userinfo.business_license_num,
            'cert': userinfo.shop_license_img,
        }, // set function name and parameters
        success: function (data) {
            if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showMessage('信息错误。')
                        break;
                    case 2:
                        showMessage('这个用户已存在。')
                        break;
                    default:
                        showMessage('账号信息错误。');
                        break;
                }
                setAuthorizationStatus(false);
                setTimeout(function () {
                    window.location.href = 'user_register_success.php';
                }, 3000)
            } else {
                setAuthorizationStatus(true)
                window.location.href = 'user_register_success.php';
            }
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                showMessage('Authorization Testing Success.')
                setTimeout(function () {
                    setAuthorizationStatus(true)
                    window.location.href = "user_register_success.php";
                })
            }
        },
        error: function (data) {
            setAuthorizationStatus(false)
            showMessage(LANG_DATA.server_error);
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                showMessage('Authorization Testing Success.')
                setTimeout(function () {
                    setAuthorizationStatus(true)
                    window.location.href = "user_register_success.php";
                })
            }
        }
    });
}

// send password set forget password request
function sendSetforgetPassword(phoneNumber, passwd, old_passwd) {
    if (old_passwd == undefined) old_passwd = '1' + passwd;
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/reset_password', //rest API url
        dataType: 'json',
        data: {  // set function name and parameters
            'phone': phoneNumber,
            'old': old_passwd,
            'new': passwd,
            'code': 1
        },
        success: function (data) {
            if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showMessage('信息错误。')
                        break;
                    case 2:
                        showMessage('该账号不存在。');
                        break;
                    case 4:
                        showMessage('旧密码错误。');
                        break;
                    case 4:
                        showMessage('新密码错误。');
                        break;
                    default:
                        showMessage('账号信息错误。');
                        break;
                }
            } else {
                showMessage('密码修改成功。');
                setTimeout(function () {
                    sessionStorage.clear({});
                    localStorage.clear({});
                    location.href = "index.php";
//                    sendLoginRequest(getPhoneNumber(), passwd,2);
                }, 2000)
            }
            setRegisterStatus(false);
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                showMessage('密码修改成功(testing)');
                setTimeout(function () {
                    sendLoginRequest(getPhoneNumber(), passwd, 2);
                }, 3000)
            }
        },
        error: function (data) {
            showMessage(LANG_DATA.server_error);
            setRegisterStatus(false)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                showMessage('密码修改成功(testing)');
                setTimeout(function () {
                    sendLoginRequest(getPhoneNumber(), passwd, 2);
                }, 3000)
            }
        }
    });
}

function sendLoginRequest(phoneNumber, passwd, isShowResult) {////////PMS-Code
    if (isShowResult == undefined) isShowResult = true;
    else isShowResult=false;
    if(phoneNumber=='') return;
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/shipping_login', //rest API url
        dataType: 'json',
        data: {'userid': phoneNumber, 'password': passwd}, // set function name and parameters
        success: function (res) {
            if (res.status == false) {
                switch (parseInt(res.err_code)) {
                    case 1:
                        showMessage('信息错误。')
                        break;
                    case 2:
                        showMessage('该账号不存在。');
                        break;
                    case 4:
                        showMessage('密码错误。');
                        break;
                    default:
                        showMessage('账号信息错误。');
                        break;
                }
                setRegisterStatus(false);
                setUserInfo('');
                setSessionPassword('');
                if(isShowResult)
                    window.location.href = 'user_login.php'
            } else {
                setRegisterStatus(true);
                setUserInfo(res.user_data);
                setSessionPassword(passwd);
                if (isShowResult)
                    window.location.href = 'shipper_order.php';
                else
                    showContents();
            }
        },
        error: function (data) {
            showMessage(LANG_DATA.server_error);
            setRegisterStatus(false);
        }
    });
}

/******************************PMS-CODE*********************************************/
function getShippingItems() {

    var shipperInfo = getUserInfo();
    var userid = shipperInfo['userid'];
    var orderList = [];
    sessionStorage.removeItem('orderList');
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/shippingItems', //rest API url
        dataType: 'json',
        data: {'userid': userid}, // set function name and parameters
        success: function (res) {
            if (res.status == false) {
                showMessage('信息错误。')
            } else {
                sessionStorage.setItem('orderList', JSON.stringify(res.data));
                showShippingOrderList();
            }
        },
        error: function (res) {
            showMessage(LANG_DATA.server_error);
        }
    });
}

function sendShipperFeedbackRequest(msg_data, userid) {
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/addFeedback', //rest API url
        dataType: 'json',
        data: {'userid': userid, 'feedback': msg_data}, // set function name and parameters
        success: function (data) {
            if (data.status == false) {
                showMessage('信息错误。')
            } else {
                showConfirm();
            }
        },
        error: function (data) {
            showMessage(LANG_DATA.server_error);
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
// showMessage('Product Testing Success.')
                setTimeout(function () {
                    showConfirm();
                }, 100)
            }
        }
    });
}

function confirmShipperProductInfo(orderId) {
    var shipperInfo = getUserInfo();
    var userid = getPhoneNumber();
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/shippingComplete', //rest API url
        dataType: 'json',
        data: {userid: userid, order_id: orderId}, // set function name and parameters
        success: function (res) {
            if (res.status == false) {
                showMessage('信息错误。');
                console.log('Operation failed.' + res.data);
            } else {
                $('#message_dialog').modal('hide');
//                {///update session storage

                var orderList = sessionStorage.getItem('orderList');
///update status field
                orderList = orderList.replace('"status":"3"', '"status":"4"');
///update distributed_time to current time
                var date = new Date();
                var curTime = getDateTimeString(date.toLocaleString());
                orderList = orderList.replace('"distributed_time":null', '"distributed_time":"' + curTime + '"');
                sessionStorage.setItem('orderList', orderList);
//                }
                window.location.reload();
                console.log('保存完成 return data is:' + res.data);
//////////////////////////Tomorrow task
            }
        },
        error: function (res) {
            showMessage(LANG_DATA.server_error);
            console.log(res);
        }
    });


}

function getShippingHistoryItems(start_date, end_date) {
    var historyItems = [];
    var shipperInfo = getUserInfo();
    var userid = getPhoneNumber();
    sessionStorage.removeItem('shippingHistoryList');
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/shippingHistory', //rest API url
        dataType: 'json',
        data: {'userid': userid, start_date: start_date, end_date: end_date}, // set function name and parameters
        success: function (res) {
            if (res.status == false) {
                showMessage('信息错误。')
            } else {
                sessionStorage.setItem('historyItemList', JSON.stringify(res.data));
                showShippingHistoryList();
            }
        },
        error: function (res) {
            showMessage(LANG_DATA.server_error);
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
// showMessage('MenuCarousel Testing Success.')
                setTimeout(function () {
                    sessionStorage.setItem('historyItemList', JSON.stringify(historyItems));
                    showShippingHistoryList();
                }, 100)
            }
        }
    });

}

