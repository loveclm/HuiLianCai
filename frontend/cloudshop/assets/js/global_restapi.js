// Main rest api file

var REMOTE_SERVER_ROOT = 'http://www.huiliancai.com';
// var REMOTE_API_URL = REMOTE_SERVER_ROOT + '/backend/';
// var MY_API_URL = 'http://www.huiliancai.com/frontend/';

//var REMOTE_SERVER_ROOT = 'http://192.168.2.15/huiliancai';
var REMOTE_API_URL = REMOTE_SERVER_ROOT + '/backend/';
var MY_API_URL = 'http://192.168.2.18/frontend/';

var HLC_REAL_MODE = 0; //0-real environment
var HLC_SIMUL_MODE = 1; //1-real environment

var HLC_LOGIN_MODE = HLC_REAL_MODE;
var HLC_SMS_MODE = HLC_SIMUL_MODE;
var HLC_PAY_MODE = HLC_SIMUL_MODE;
var HLC_APP_MODE = HLC_REAL_MODE;
var HLC_AUTH_MODE = HLC_REAL_MODE;
var HLC_DEBUG_MODE = HLC_REAL_MODE;
var HLC_AUTH_STATUS = false; //true:force authorized, false: real mode (only applied simul_mode)

var LANG_DATA = {
    'server_error': '网络连接失败，请稍后重试。',
};
var login_token_counter = 0;

//var geoLocation = null;

function replaceHtmlImgURL(txt) {
    // return txt.replace("\"/huiliancai/", "\"" + REMOTE_SERVER_ROOT + "/huiliancai/");
    return txt.replace("\"/backend/", "\"" + REMOTE_SERVER_ROOT + "/backend/");
}

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
                showMessage('您好, 已将验证码发送到您的手机, <br>请注意查收。');
                if (HLC_SMS_MODE == HLC_SIMUL_MODE) {
                    setTimeout(function () {
                        $('#auth_code').val(data['code']);
                    }, 500)
                }
            } else {
                app_data.sms_code = "";
                if(data.error[0]=="验证码超出同模板同号码天发送上限"){
                    showNotifyAlert("同一手机号每天只能获取5次验证码。");
                }else {
                    showNotifyAlert(data.error[0]);
                }
                restoreSMSButton();
                //showNotifyAlert(JSON.stringify(data));
            }
        },
        fail: function () {
            showNotifyAlert(LANG_DATA.server_error)
        }
    });
}

// User Registering APIs
function sendRegisterRequest(phoneNumber, passwd, servantPhone) {
    if (getAuthorizationStatus()) {
        showNotifyAlert('该账号已注册。');
        setTimeout(function () {
            history.back()
        }, 3000)
        return;
    }
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/register', //rest API url
        dataType: 'json',
        data: {'phone': phoneNumber, 'password': passwd, 'saleman': servantPhone}, // set function name and parameters
        success: function (data) {
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                setPhoneNumber(phoneNumber);
                setRegisterStatus(true);
                showAuthRequire('注册成功,<br>请您及时进行认证！', '立即认证', '跳过')
            } else if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。')
                        break;
                    case 2:
                        showNotifyAlert('该账号已存在。')
                        break;
                    default:
                        showNotifyAlert('账号信息错误。');
                        break;
                }
                setRegisterStatus(false)
            } else {
                setPhoneNumber(phoneNumber);
                setSessionPassword(passwd);
                setRegisterStatus(true);
                localStorage.setItem('hlc_token', data.token);
                showAuthRequire('注册成功,<br>请您及时进行认证！', '立即认证', '跳过')
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            setPhoneNumber('');

            setRegisterStatus(false)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Register testing success.')
                //setTimeout(function () {
                setPhoneNumber(phoneNumber);
                setRegisterStatus(true);
                showAuthRequire('注册成功,<br>请您及时进行认证！', '立即认证', '跳过')
                //}, 100)
            }
        }
    });
} //send user register request to server

function sendUploadImageRequest(data, imgCtrl, filename) {
    var timerID = setInterval(function () {
        var message = $('#notification_alert_bar').html();
        // if (message == '图片上传中。。。') message = '图片上传中。';
        // else if (message == '图片上传中。') message = '图片上传中。。';
        // else if (message == '图片上传中。。') message = '图片上传中。。。';
        // else message = '图片上传中...';
        message = '图片上传中...';
        $('#notification_alert_bar').html(message);
        $('#notification_alert_bar').css({
            'background-color': 'rgba(255, 255, 255, 0.7)',
            'color': 'red',
            'border-color': 'red'
        })
        $('#notification_alert_bar').show();
    }, 1000);

    // alert(JSON.stringify(filename));
    $.ajax({
        url: REMOTE_API_URL + "api/ImgProcessor/uploadAnyData",
        type: "POST",
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data) {
            clearInterval(timerID);
            $('#notification_alert_bar').hide();
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                sessionStorage.setItem(imgCtrl, 'assets/images/' + filename);
            }
            else if (data['status'] == true) {
                sessionStorage.setItem(imgCtrl, 'uploads/' + data.file);
                //    if(imgCtrl=='shop_license_img')
                //        $('#shop_license_img_cover').attr('style','opacity:0');
            } else {
                sessionStorage.removeItem(imgCtrl)
                showNotifyAlert(data.status);
            }
            // alert(JSON.stringify(data));
            activateAuthButton(data.file)
        },
        error: function (data) {
            clearInterval();
            $('#notification_alert_bar').hide();
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                sessionStorage.setItem(imgCtrl, 'uploads/' + filename);
                activateAuthButton(filename)
            }
        },
        //  beforeSend: setHeader,
    });
}

function sendUploadLogoImageRequest(data, imgCtrl, filename) {
    var timerID = setInterval(function () {
        var message = $('#notification_alert_bar').html();
        // if (message == '图片上传中。。。') message = '图片上传中。';
        // else if (message == '图片上传中。') message = '图片上传中。。';
        // else if (message == '图片上传中。。') message = '图片上传中。。。';
        // else message = '图片上传中。';
        message = '图片上传中...';
        $('#notification_alert_bar').html(message);
        $('#notification_alert_bar').css({
            'background-color': 'rgba(255, 255, 255, 0.7)',
            'color': 'red',
            'border-color': 'red'
        })
        $('#notification_alert_bar').show();
    }, 1000);
    // alert(JSON.stringify(filename));
    $.ajax({
        url: REMOTE_API_URL + "api/ImgProcessor/uploadLogoData",
        type: "POST",
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data) {
            clearInterval(timerID);
            $('#notification_alert_bar').hide();
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                sessionStorage.setItem(imgCtrl, 'assets/images/' + filename);
            }
            else if (data['status'] == true) {
                sessionStorage.setItem(imgCtrl, 'uploads/' + data.file);
                //    if(imgCtrl=='shop_license_img')
                //        $('#shop_license_img_cover').attr('style','opacity:0');
            } else {
                sessionStorage.removeItem(imgCtrl)
                showNotifyAlert(data.status);
            }
            // alert(JSON.stringify(data));
            activateAuthButton(data.file)
        },
        error: function (data) {
            clearInterval();
            $('#notification_alert_bar').hide();
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                sessionStorage.setItem(imgCtrl, 'uploads/' + filename);
                activateAuthButton(filename)
            }
        },
        //  beforeSend: setHeader,
    });
}

function sendUploadUserInfo(userinfo) {
    // upload two files.

    var sessionUserInfo = {
        'number': userinfo.phone,
        'user_name': userinfo.shop_name,
        'user_addr': userinfo.shop_addr,
        'user_phone': userinfo.contact_person_phone,
        'user_image': userinfo.shop_logo_img,
        'cert_image': userinfo.shop_license_img,
    }
    //alert(JSON.stringify(userinfo));
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/upload_auth', //rest API url
        dataType: 'json',
        data: {
            'phone': userinfo.phone,
            'type': userinfo.shop_type,
            'name': userinfo.shop_name,
            'address': userinfo.shop_addr_gps,
            'filter_addr': userinfo.shop_addr,
            'contact_name': userinfo.contact_person,
            'contact_phone': userinfo.contact_person_phone,
            'logo': userinfo.shop_logo_img,
            'cert_num': userinfo.business_license_num,
            'cert': userinfo.shop_license_img,
            'lat': userinfo.lat,
            'lon': userinfo.lng,
        }, // set function name and parameters
        success: function (data) {
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Authorization Testing Success.')
                setRegisterStatus(true);
                setAuthorizationStatus(true);
                setSessionMyInfo(sessionUserInfo);
                window.location.href = "user_register_success.php";
            } else if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。');
                        break;
                    case 2:
                        showNotifyAlert('该账号已存在。');
                        break;
                    case 3:
                        showMessage('该名称已存在。',2);
                        break;
                    default:
                        showNotifyAlert('账号信息错误。');
                        break;
                }
                setAuthorizationStatus(false);
                setAuthRequestStatus(false);
                setSessionMyInfo('');
            } else {
                setAuthRequestStatus(true);
                setAuthorizationStatus(false);
                setSessionMyInfo(sessionUserInfo);
                sessionStorage.removeItem('msgShowed');
                sessionStorage.removeItem('shop_logo_img')
                sessionStorage.removeItem('shop_license_img')
                location.href = 'user_register_success.php';
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Authorization Testing Success.')
                setTimeout(function () {
                    setRegisterStatus(true)
                    setAuthorizationStatus(true)
                    setSessionMyInfo(userinfo)
                    sessionStorage.removeItem('shop_logo_img')
                    sessionStorage.removeItem('shop_license_img')
                    location.href = "user_register_success.php";
                }, 100)
            }
        }
    });
} // upload user's individual information to server

// Login APIs
function sendSetforgetPassword(phoneNumber, passwd, old_passwd) {
    if (old_passwd == undefined) old_passwd = '';
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/reset_password', //rest API url
        dataType: 'json',
        data: {  // set function name and parameters
            'phone': phoneNumber,
            'old': (old_passwd == '' ? ('1' + passwd) : old_passwd),
            'new': passwd,
            'code': ((old_passwd == '') ? 2 : 1),
        },
        success: function (data) {
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('密码已修改成功(testing)');
                setTimeout(function () {
                    sendLoginRequest(getPhoneNumber(), getSessionPassword(), 2);
                }, 100)
            } else if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。')
                        break;
                    case 2:
                        showNotifyAlert('该账号不存在。');
                        break;
                    case 4:
                        showNotifyAlert('旧密码错误。');
                        break;
                    case 4:
                        showNotifyAlert('新密码错误。');
                        break;
                    default:
                        showNotifyAlert(data.error);
                        break;
                }
            } else {
                setSessionPassword(passwd)
                showNotifyAlert('密码修改成功。', 1);
                setTimeout(function () {
                    sendLoginRequest(getPhoneNumber(), getSessionPassword(), 2);
                }, 3000)
            }
            setRegisterStatus(false);
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            setRegisterStatus(false)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('密码已修改成功(testing)');
                setTimeout(function () {
                    sendLoginRequest(getPhoneNumber(), getSessionPassword(), 2);
                }, 100)
            }
        }
    });
} // send password set forget password request

function sendLoginRequest(phoneNumber, passwd, status) {
    var myUserInfo = {
        id: '12341234',
        number: '652458136862456256245624568',
        user_name: '张某某',
        user_phone: '12345678901',
        user_addr: '北京朝阳区芳香园12区5号楼502室',
        user_image: 'assets/images/tmp/i2.png',
        cert_image: 'assets/images/tmp/authcard.png',
        status: HLC_AUTH_STATUS,
        coupon: 1, //0-no coupon, 1-unused, 2-used
    }

    if (phoneNumber == '') {
        if (status != 2) return;
    }
    $.ajax({
            type: 'POST',
            url: REMOTE_API_URL + 'api/login', //rest API url
            dataType: 'json',
            data: {'phone': phoneNumber, 'password': passwd, 'status': status}, // set function name and parameters
            success: function (data) {
                if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                    //showNotifyAlert('Login Testing Success.')
                    setRegisterStatus(true);
                    setAuthorizationStatus(myUserInfo.status)
                    setCouponStatus(myUserInfo.coupon)
                    setSessionMyInfo(myUserInfo)
                    location.href = 'home.php';
                } else if (data.status == false) {
                    if (status == 2) { //logout request sent.
                        var coup = getCouponStatus();
                        var localUserInfo = localStorage.getItem('user_register_detail');
                        var my_hlc_token = localStorage.getItem('hlc_token');
                        var shipper_data = localStorage.getItem('shipper_data');
                        var shipper_user_number = localStorage.getItem('shipper_user_number');
                        console.log('removed from 1');
                        localStorage.clear({});
                        sessionStorage.clear({});
                        localStorage.setItem('isLogout', '1');
                        if (coup != 0) setCouponStatus(coup);
                        if (localUserInfo != undefined) localStorage.setItem('user_register_detail', localUserInfo);
                        if (my_hlc_token != undefined) localStorage.setItem('hlc_token', my_hlc_token);
                        if (shipper_data != undefined) localStorage.setItem('shipper_data', shipper_data);
                        if (shipper_user_number != undefined) localStorage.setItem('shipper_user_number', shipper_user_number);
                        weixinConfigure();
                        wx.closeWindow();

                        // $('body').on('blur', disableBackButton());
                        // $('body').on('unload', disableBackButton());
                        // $('body').on('load', disableBackButton());
                        // location.href = "index.php";
                    } else if (status != 0) {
                        switch (parseInt(data.err_code)) {
                            case 1:
                                showNotifyAlert('信息错误。')
                                break;
                            case 2:
                                showNotifyAlert('该账号不存在。');
                                break;
                            case 3:
                                showNotifyAlert('该账号已被禁用。')
                                break;
                            case 4:
                                showNotifyAlert('密码错误。');
                                break;
                            default:
                                showNotifyAlert('账号信息错误。');
                                break;
                        }
                    }
                    //sessionStorage.clear({});
                    //localStorage.clear({});
                } else {
                    switch (parseInt(data.user_data.status)) {
                        case 1:
                            setAuthRequestStatus(false);
                            setAuthorizationStatus(false);
                            break;
                        case 4:
                        case 2:
                            setAuthRequestStatus(true);
                            setAuthorizationStatus(false);
                            break;
                        case 3:
                            if(getAuthRequestStatus() && !getAuthorizationStatus())
                                localStorage.setItem('isCouponShowed','0');
                            setAuthRequestStatus(true);
                            setAuthorizationStatus(true);
                            break;
                    }

                    if (status == 1) {
                        setSessionPassword(passwd);
                        setRegisterStatus(true);
                        localStorage.setItem('hlc_token', data.user_data.login_status);
                        setPhoneNumber(phoneNumber);
                        var delay_time = 10;
                        if (localStorage.getItem('isCouponShowed')=='0' && data.user_data.coupon == 1) {
                            showMessage('欢迎使用惠联彩,<br>赠送30元代金券, 满300元使用。');
                            localStorage.removeItem('isCouponShowed');
                            delay_time = 3000;
                        }
                        setCouponStatus(data.user_data.coupon)
                        setSessionMyInfo(data.user_data)
                        localStorage.removeItem('isLogout');
                        // $('body').on('blur', disableBackButton());
                        // $('body').on('unload', disableBackButton());
                        // $('body').on('load', disableBackButton());
                        setTimeout(function () {
                            location.href = 'index.php';
                        }, delay_time);
                    } else if (status == 2) { //logout request sent from logout button.
                        var coup = getCouponStatus();
                        var localUserInfo = localStorage.getItem('user_register_detail');
                        var my_hlc_token = localStorage.getItem('hlc_token');
                        var shipper_data = localStorage.getItem('shipper_data');
                        var shipper_user_number = localStorage.getItem('shipper_user_number');
                        console.log('removed from 1');
                        localStorage.clear({});
                        sessionStorage.clear({});
                        localStorage.setItem('isLogout', '1');
                        if (coup != 0) setCouponStatus(coup);
                        if (localUserInfo != undefined) localStorage.setItem('user_register_detail', localUserInfo);
                        if (my_hlc_token != undefined) localStorage.setItem('hlc_token', my_hlc_token);
                        if (shipper_data != undefined) localStorage.setItem('shipper_data', shipper_data);
                        if (shipper_user_number != undefined) localStorage.setItem('shipper_user_number', shipper_user_number);
                        //close();
                        weixinConfigure();
                        wx.closeWindow();
                        // $('body').on('blur', disableBackButton());
                        // $('body').on('unload', disableBackButton());
                        // $('body').on('load', disableBackButton());
                        // location.href = "index.php";
                    } else { // get userInfo from page using.
                        if (localStorage.getItem('hlc_token') != data.user_data.login_status) {
                            login_token_counter++;
                            if (login_token_counter > 1) {
                                var coup = getCouponStatus();
                                var localUserInfo = localStorage.getItem('user_register_detail');
                                var my_hlc_token = localStorage.getItem('hlc_token');
                                var shipper_data = localStorage.getItem('shipper_data');
                                var shipper_user_number = localStorage.getItem('shipper_user_number');
                                console.log('removed from 1');
                                localStorage.clear({});
                                sessionStorage.clear({});
                                localStorage.setItem('isLogout', '1');
                                if (coup != 0) setCouponStatus(coup);
                                if (localUserInfo != undefined) localStorage.setItem('user_register_detail', localUserInfo);
                                if (my_hlc_token != undefined) localStorage.setItem('hlc_token', my_hlc_token);
                                if (shipper_data != undefined) localStorage.setItem('shipper_data', shipper_data);
                                if (shipper_user_number != undefined) localStorage.setItem('shipper_user_number', shipper_user_number);      //close();

                                //$('body').on('blur', disableBackButton());
                                //$('body').on('unload', disableBackButton());
                                //$('body').on('load', disableBackButton());
                                showMessage('该账号已在其他设备登录。', 2);
                                setTimeout(function () {
                                    weixinConfigure();
                                    wx.closeWindow();
                                    //location.href = "index.php";
                                }, 6000);
                            }
                        } else {
                            setRegisterStatus(true);
                            if (data.user_data.status == 4) {
                                setSessionMyInfo(data.user_data);
                            } else {
                                setSessionMyInfo(data.user_data);
                                localStorage.removeItem('isLogout');
                            }
                            setCouponStatus(data.user_data.coupon);

                        }
                    }
                }
            },
            error: function (data) {
                showNotifyAlert(LANG_DATA.server_error)
                if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                    //showNotifyAlert('Login Testing Success.')
                    setTimeout(function () {
                        setRegisterStatus(true);
                        setPhoneNumber(phoneNumber);
                        setAuthorizationStatus(myUserInfo.status)
                        setCouponStatus(myUserInfo.coupon)
                        setSessionMyInfo(myUserInfo)
                        if (status != 0)
                            location.href = 'home.php';
                    }, 100)
                }
            }
        }
    )
    ;
}

function getAuthorizationStatusRequest(phoneNumber, type) {
    return;
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/check_auth', //rest API url
        dataType: 'json',
        data: {'phone': phoneNumber}, // set function name and parameters
        success: function (data) {
            if (HLC_AUTH_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setAuthorizationStatus(HLC_AUTH_STATUS)
            } else if (data.status == false) {
                if (type == undefined) {
                    switch (parseInt(data.err_code)) {
                        case 1:
                            showNotifyAlert('信息错误。')
                            break;
                        case 2:
                            showNotifyAlert('该账号不存在。');
                            break;
                        case 4:
                            showNotifyAlert('密码错误。');
                            break;
                        default:
                            showNotifyAlert(data.error);
                            break;
                    }
                }
                setAuthorizationStatus(false)
            } else {
                switch (parseInt(data.data)) {
                    case 1:
                    case 4:
                        setAuthRequestStatus(false);
                        setAuthorizationStatus(false);
                        break;
                    case 2:
                        setAuthRequestStatus(true);
                        setAuthorizationStatus(false);
                        break;
                    case 3:
                        setAuthRequestStatus(true);
                        setAuthorizationStatus(true);
                        break;
                }
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_AUTH_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setTimeout(function () {
                    setAuthorizationStatus(HLC_AUTH_STATUS)
                }, 100)
            }
        }
    });
}

// HomePage APIs
function getCarouselDatas() {

    var carouselData = [
        {
            imgData: 'assets/images/tmp/u4.png',
            linkData: '3',//id of link
            linkType: '2',//0-advertise, 1-single Activity, 2-set Activity, 3-Provider
        },
        {
            imgData: 'assets/images/tmp/u5.png',
            linkData: '3',//id of link
            linkType: '2',//0-advertise, 1-single Activity, 2-set Activity, 3-Provider
        },
        {
            imgData: 'assets/images/tmp/u1.png',
            linkData: '0',//id of link
            linkType: '0',//0-advertise, 1-single Activity, 2-set Activity, 3-Provider
        },
        {
            imgData: 'assets/images/tmp/u3.png',
            linkData: '3',//id of link
            linkType: '2',//0-advertise, 1-single Activity, 2-set Activity, 3-Provider
        },
        {
            imgData: 'assets/images/tmp/u2.png',
            linkData: '3',//id of link
            linkType: '2',//0-advertise, 1-single Activity, 2-set Activity, 3-Provider
        }
    ]
    var menuData = [
        {
            'id': '1',       // product kind id
            'name': '推荐',   // product kind name
            'brand': [
                {
                    'id': '11',      // brand id
                    'name': '康师傅'  // brand name
                },
                {'id': '12', 'name': '伊利'},
                {'id': '13', 'name': '蒙牛'},
                {'id': '14', 'name': '今麦郎'},
                {'id': '15', 'name': '统一'},
                {'id': '16', 'name': '白象'},
                {'id': '17', 'name': '华丰'},
                {'id': '18', 'name': '五谷道场'},
                {'id': '19', 'name': '农心'}
            ]
        },
        {
            'id': '2',
            'name': '食品',
            'brand': [
                {'id': '20', 'name': '伊利'},
                {'id': '21', 'name': '蒙牛'},
                {'id': '22', 'name': '统一'},
                {'id': '23', 'name': '华丰'},
                {'id': '24', 'name': '五谷道场'},
            ]
        },
        {
            'id': '3',
            'name': '方便面',
            'brand': [
                {'id': '31', 'name': '康师傅'},
                {'id': '32', 'name': '蒙牛'},
                {'id': '33', 'name': '今麦郎'},
                {'id': '34', 'name': '白象'},
                {'id': '35', 'name': '华丰'},
                {'id': '36', 'name': '农心'}]
        },
        {
            'id': '4',
            'name': '乳制品',
            'brand': [
                {'id': '41', 'name': '康师傅'},
                {'id': '42', 'name': '伊利'},
                {'id': '43', 'name': '蒙牛'},
                {'id': '44', 'name': '今麦郎'},
                {'id': '45', 'name': '白象'}]
        },
        {
            'id': '5',
            'name': '冰淇淋',
            'brand': [
                {'id': '51', 'name': '伊利'},
                {'id': '52', 'name': '蒙牛'},
                {'id': '53', 'name': '统一'},
                {'id': '54', 'name': '华丰'},
                {'id': '55', 'name': '五谷道场'},
                {'id': '56', 'name': '农心'}]
        },
        {
            'id': '6',
            'name': '面包',
            'brand': [
                {'id': '61', 'name': '康师傅'},
                {'id': '62', 'name': '蒙牛'},
                {'id': '63', 'name': '白象'},
                {'id': '64', 'name': '华丰'},
                {'id': '65', 'name': '五谷道场'},
                {'id': '66', 'name': '农心'}]
        },
        {
            'id': '7',
            'name': '火腿肠',
            'brand': [
                {'id': '71', 'name': '白象'},
                {'id': '72', 'name': '华丰'},
                {'id': '73', 'name': '五谷道场'},
                {'id': '74', 'name': '农心'}]
        },
        {
            'id': '8',
            'name': '饮料',
            'brand': [
                {'id': '81', 'name': '康师傅'},
                {'id': '82', 'name': '伊利'},
                {'id': '83', 'name': '白象'},
                {'id': '84', 'name': '华丰'},
                {'id': '85', 'name': '五谷道场'},
                {'id': '86', 'name': '农心'}]
        },
        {
            'id': '9',
            'name': '生活用品',
            'brand': [
                {'id': '91', 'name': '今麦郎'},
                {'id': '92', 'name': '统一'},
                {'id': '93', 'name': '白象'},
                {'id': '94', 'name': '华丰'}]
        }
    ]

    sessionStorage.removeItem('carouselDatas');
    sessionStorage.removeItem('menuDatas');
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/menuAndCarouselInfos', //rest API url
        dataType: 'json',
        data: {'data': '1', 'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('MenuCarousel Testing Success.')
                sessionStorage.setItem('carouselDatas', JSON.stringify(carouselData));
                sessionStorage.setItem('menuDatas', JSON.stringify(menuData));
                getAuthorizationStatusRequest(getPhoneNumber(), 0);
                showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
            } else {
                sessionStorage.setItem('carouselDatas', JSON.stringify(data.carouselData));
                sessionStorage.setItem('menuDatas', JSON.stringify(data.menuData));
                getAuthorizationStatusRequest(getPhoneNumber(), 0);
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error);
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('MenuCarousel Testing Success.')
                setTimeout(function () {
                    sessionStorage.setItem('carouselDatas', JSON.stringify(carouselData));
                    sessionStorage.setItem('menuDatas', JSON.stringify(menuData));
                    getAuthorizationStatusRequest(getPhoneNumber(), 0);
                    showContents()
                }, 500)
            }
        }
    });
} //carousel and menu data

function getMainActivityItemTemplate(menu_id, brand_id) {
    var productItems = [
        {
            'id': '151',
            'end_time': '2001/1-1 3:5:3',
            'provider_id': '45872',
            'provider_name': '有机原',
            'provider_address': '特级 优质 原生态有原生态有机原',
            'provider_contact_phone': '579373629384',
            'product_image': 'assets/images/tmp/u4.png',
            'product_name': '可跳转到收货地址',
            'info_size': '1g*12/箱',
            'info_box': '500箱起拼',
            'mans': 3,
            'amount': 200,
            'min_amount': 10,
            'cur_amount': 15,
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'logos': [
                'assets/images/tmp/u4.png',
                'assets/images/tmp/u5.png',
            ],
            'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
            'man_info': [],
            'total_info': [],
        },
        {
            'id': '171',
            'end_time': '2001-1-1 16:45:8',
            'product_image': 'assets/images/tmp/u5.png',
            'product_name': '特级 优质 原生态有机米 真空包装',
            'info_size': '3g*12/箱',
            'info_box': '200箱起拼',
            'mans': 10,
            'amount': 50,
            'min_amount': 5,
            'cur_amount': 30,
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'logos': [
                'assets/images/tmp/u5.png',
                'assets/images/tmp/u3.png',
                'assets/images/tmp/u4.png',
                'assets/images/tmp/u1.png',
            ],
            'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
            'man_info': [
                {
                    'id': '101',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': '2017-1-6 13:30:25',
                },
                {
                    'id': '102',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': '2017/1/6 13:30:25',
                },
                {
                    'id': '103',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '王红',
                    'ordered_time': '2017/1/6 13:30:25',
                }
            ],
            'total_info': [
                {
                    'product_name': '伊利牛奶',
                    'amount': 10,
                    'price': Math.floor(Math.random() * 100).toFixed(2),
                },
                {
                    'product_name': '方便面',
                    'amount': 6,
                    'price': Math.floor(Math.random() * 100).toFixed(2),
                },
                {
                    'product_name': '面包',
                    'amount': 3,
                    'price': Math.floor(Math.random() * 100).toFixed(2),
                }
            ],
        },
        {
            'id': '360',
            'end_time': '2001/1/1 10:23:47',
            'product_image': 'assets/images/tmp/u3.png',
            'product_name': '特级 优质 原生态有机米 真空包装',
            'info_size': '500g*3/箱',
            'info_box': '50箱起拼',
            'mans': 10,
            'amount': 30,
            'min_amount': 5,
            'cur_amount': 20,
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'logos': [
                'assets/images/tmp/u3.png',
                'assets/images/tmp/u4.png',
                'assets/images/tmp/u2.png',
            ],
            'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
            'man_info': [
                {
                    'id': '101',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': '2017/1/6 13:30:25',
                },
                {
                    'id': '102',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': '2017/1/6 13:30:25',
                },
                {
                    'id': '103',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': '2017/1/6 13:30:25',
                },
                {
                    'id': '104',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': '2017/1/6 13:30:25',
                },
                {
                    'id': '105',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': '2017/1/6 13:30:25',
                },
                {
                    'id': '106',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': '2017/1/6 13:30:25',
                },
            ],
            'total_info': [],
        },
        {
            'id': '251',
            'end_time': '2001/1/1 7:8:23',
            'product_image': 'assets/images/tmp/u2.png',
            'product_name': '货地址',
            'info_size': '15g*5/箱',
            'info_box': '300箱起拼',
            'mans': 5,
            'amount': 100,
            'min_amount': 6,
            'cur_amount': 60,
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'logos': [
                'assets/images/tmp/u2.png',
                'assets/images/tmp/u4.png',
                'assets/images/tmp/u3.png',
                'assets/images/tmp/u1.png',
                'assets/images/tmp/u5.png',
            ],
            'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
            'man_info': [
                {
                    'id': '101',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': '2017/1/6 13:30:25',
                },
            ],
            'total_info': [
                {
                    'product_name': '伊利牛奶',
                    'amount': 2,
                    'price': Math.floor(Math.random() * 100).toFixed(2),
                },
                {
                    'product_name': '方便面',
                    'amount': 8,
                    'price': Math.floor(Math.random() * 100).toFixed(2),
                },
                {
                    'product_name': '面包',
                    'amount': 4,
                    'price': Math.floor(Math.random() * 100).toFixed(2),
                }
            ],
        },
    ];
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/productInfos', //rest API url
        dataType: 'json',
        data: {'type': menu_id, 'brand': brand_id, 'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            sessionStorage.removeItem('productDatas')
            data.productPageCnt=0;
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                sessionStorage.setItem('productDatas', JSON.stringify(productItems));
                data.productPageCnt =0;
                display_product_infos()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
            } else {
                sessionStorage.setItem('productDatas', JSON.stringify(data.products));
                data.productPageCnt=0;
                setTimeout(function(){
                    display_product_infos()
                },100);
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    sessionStorage.setItem('productDatas', JSON.stringify(productItems));
                    display_product_infos()
                }, 500)
            }
        }
    });
    getMyWalletRequest();
} // main activity

function getActivityDetailData(id) {
    var activityItem = {
        'id': id,
        'end_time': '2001-1-1 16:45:8',
        'provider_id': '45872',
        'provider_name': '有机原',
        'provider_address': '特级 优质 原生态有原生态有机原',
        'provider_contact_phone': '579373629384',
        'product_image': 'assets/images/tmp/u5.png',
        'product_name': '特级 优质 原生',
        'info_size': '3g*12/箱',
        'info_box': '200箱起拼',
        'mans': 10,
        'amount': 50,
        'min_amount': 5,
        'cur_amount': 30,
        'old_price': Math.floor(Math.random() * 100).toFixed(2),
        'new_price': Math.floor(Math.random() * 100).toFixed(2),
        'logos': [
            'assets/images/tmp/u5.png',
            'assets/images/tmp/u3.png',
            'assets/images/tmp/u4.png',
            'assets/images/tmp/u1.png',
        ],
        'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
        'man_info': [
            {
                'id': '543267',
                'image': 'assets/images/tmp/i2.png',
                'name': '张二明',
                'ordered_time': '2017-1-6 13:30:25',
            },
            {
                'id': '4567245',
                'image': 'assets/images/tmp/i1.png',
                'name': '王万红',
                'ordered_time': '2017/1/6 13:30:25',
            },
            {
                'id': '2543273',
                'image': 'assets/images/tmp/i2.png',
                'name': '王红',
                'ordered_time': '2017/1/6 13:30:25',
            }
        ],
        'total_info': [
            {
                'product_name': '伊利牛奶',
                'amount': 10,
                'price': Math.floor(Math.random() * 100).toFixed(2),
            },
            {
                'product_name': '方便面',
                'amount': 6,
                'price': Math.floor(Math.random() * 100).toFixed(2),
            },
            {
                'product_name': '面包',
                'amount': 3,
                'price': Math.floor(Math.random() * 100).toFixed(2),
            }
        ],
    };
    sessionStorage.removeItem('cur_Activity')
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/activity_detail', //rest API url
        dataType: 'json',
        data: {'activity': id, 'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setCurActivityDetailInfo(activityItem);
                showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
                history.back();
            } else {
                if (data.data.length == 0) history.back();
                setCurActivityDetailInfo(data.data);
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            history.back();
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    setCurActivityDetailInfo(activityItem);
                    showContents()
                }, 500)
            }
        }
    });
} // main activity

function getProviderDetailData(id) {
    var providerItem = {
        'id': '509865',
        'type': 1,//0-activity, 1-provider
        'object_id': id,
        'detail': {
            'id': '123568457',
            'name': '供业商11',
            'image': 'assets/images/tmp/u5.png',
            'content': '特级 优质 原生态有机米 真空包装',
            'address': '态有机米 真空包装',
            'contact_name': '3g*12/箱',
            'contact_phone': '3312564987',
            'products': [
                {
                    'id': '876543',
                    'end_time': '2001/1-1 3:5:3',
                    'product_image': 'assets/images/tmp/u4.png',
                    'product_name': '可跳转到收货地址',
                    'info_size': '1g*12/箱',
                    'info_box': '500箱起拼',
                    'mans': 3,
                    'amount': 200,
                    'min_amount': 10,
                    'cur_amount': 15,
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'logos': [
                        'assets/images/tmp/u4.png',
                        'assets/images/tmp/u5.png',
                    ],
                    'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                    'man_info': [],
                    'total_info': [],
                },
                {
                    'id': '654654',
                    'end_time': '2001-1-1 16:45:8',
                    'product_image': 'assets/images/tmp/u5.png',
                    'product_name': '特级 优质 原生态有机米 真空包装',
                    'info_size': '3g*12/箱',
                    'info_box': '200箱起拼',
                    'mans': 10,
                    'amount': 50,
                    'min_amount': 5,
                    'cur_amount': 30,
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'logos': [
                        'assets/images/tmp/u5.png',
                        'assets/images/tmp/u3.png',
                        'assets/images/tmp/u4.png',
                        'assets/images/tmp/u1.png',
                    ],
                    'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                    'man_info': [
                        {
                            'id': '101',
                            'image': 'assets/images/tmp/i2.png',
                            'name': '张二明',
                            'ordered_time': '2017-1-6 13:30:25',
                        },
                        {
                            'id': '102',
                            'image': 'assets/images/tmp/i1.png',
                            'name': '王万红',
                            'ordered_time': '2017/1/6 13:30:25',
                        },
                        {
                            'id': '103',
                            'image': 'assets/images/tmp/i2.png',
                            'name': '王红',
                            'ordered_time': '2017/1/6 13:30:25',
                        }
                    ],
                    'total_info': [
                        {
                            'product_name': '伊利牛奶',
                            'amount': 10,
                            'price': Math.floor(Math.random() * 100).toFixed(2),
                        },
                        {
                            'product_name': '方便面',
                            'amount': 6,
                            'price': Math.floor(Math.random() * 100).toFixed(2),
                        },
                        {
                            'product_name': '面包',
                            'amount': 3,
                            'price': Math.floor(Math.random() * 100).toFixed(2),
                        }
                    ],
                },
                {
                    'id': '8743425',
                    'end_time': '2001/1/1 10:23:47',
                    'product_image': 'assets/images/tmp/u3.png',
                    'product_name': '特级 优质 原生态有机米 真空包装',
                    'info_size': '500g*3/箱',
                    'info_box': '50箱起拼',
                    'mans': 10,
                    'amount': 30,
                    'min_amount': 5,
                    'cur_amount': 20,
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'logos': [
                        'assets/images/tmp/u3.png',
                        'assets/images/tmp/u4.png',
                        'assets/images/tmp/u2.png',
                    ],
                    'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                    'man_info': [
                        {
                            'id': '101',
                            'image': 'assets/images/tmp/i2.png',
                            'name': '张二明',
                            'ordered_time': '2017/1/6 13:30:25',
                        },
                        {
                            'id': '102',
                            'image': 'assets/images/tmp/i1.png',
                            'name': '王万红',
                            'ordered_time': '2017/1/6 13:30:25',
                        },
                        {
                            'id': '103',
                            'image': 'assets/images/tmp/i2.png',
                            'name': '张二明',
                            'ordered_time': '2017/1/6 13:30:25',
                        },
                        {
                            'id': '104',
                            'image': 'assets/images/tmp/i1.png',
                            'name': '王万红',
                            'ordered_time': '2017/1/6 13:30:25',
                        },
                        {
                            'id': '105',
                            'image': 'assets/images/tmp/i2.png',
                            'name': '张二明',
                            'ordered_time': '2017/1/6 13:30:25',
                        },
                        {
                            'id': '106',
                            'image': 'assets/images/tmp/i1.png',
                            'name': '王万红',
                            'ordered_time': '2017/1/6 13:30:25',
                        },
                    ],
                    'total_info': [],
                },
            ],
            'logos': [
                'assets/images/tmp/u5.png',
                'assets/images/tmp/u3.png',
                'assets/images/tmp/u4.png',
                'assets/images/tmp/u1.png',
            ],
        },

    };
    sessionStorage.removeItem('cur_Provider')
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/provider_detail', //rest API url
        dataType: 'json',
        data: {'provider': id, 'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setCurProviderDetailInfo(providerItem.detail);
                showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
            } else {
                setCurProviderDetailInfo(data.data);
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    setCurProviderDetailInfo(providerItem.detail);
                    showContents()
                }, 500)
            }
        }
    });
} // main activity

function sendAddMyCartItemRequest(isUpload, addId, addAmount, addMax_amount) {
    if (isUpload == undefined) isUpload = false;
    isUpload = (isUpload == false) ? 2 : 1;
    var originCart = JSON.stringify(addToSessionCart(0));
    if (isUpload && addId != undefined) addToSessionCart(addId, addAmount, addMax_amount);
    var myCart = JSON.stringify(addToSessionCart(0));
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/cartInfo', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber(), 'data': myCart, 'type': isUpload}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                if (addId != undefined) showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。');
                localStorage.setItem('myCart', originCart);
                if (addId != undefined) showContents();
            } else {
                if (isUpload != 1) { // get
                    localStorage.setItem('myCart', data.data);
                    if (addId != undefined) getActivityStatus();
                } else { // set
                    if (addId != undefined) {
                        showNotifyAlert('已加入购物车。', 1);
                    }
                }
                showCartStatus();
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    if (addId != undefined) showContents()
                }, 100)
            }
        }
    });
}

function sendRemoveMyCartItemRequest(delId, isHidden) {
    if (isHidden == undefined) isHidden = true;
    var originCart = JSON.stringify(addToSessionCart(0));
    removeFromSessionCart(delId);
    var currentCart = JSON.stringify(addToSessionCart(0));
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/cartInfo', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber(), 'data': currentCart, 'type': 1}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                showContents();
            } else if (data.status == false) {
                showNotifyAlert('信息错误。');
                localStorage.setItem('myCart', originCart);
                $('#message_dialog').modal('hide');
                showContents();
            } else {
                showCartStatus();
                $('#message_dialog').modal('hide');
                if (!isHidden)
                    showNotifyAlert('已删除购物车项目。', 1);
                showContents();
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    showContents()
                }, 100)
            }
        }
    });
}

// Cart Manage APIs
function getActivityStatus() {
    var myCart = addToSessionCart(0);
    var ids = [];
    for (var i = 0; i < myCart.length; i++) {
        ids.push(myCart[i].id)
    }
    var statusDatas = [{id: 151, status: 1}, {id: 171, status: 3}, {id: 360, status: 4}, {id: 251, status: 4},]
    sessionStorage.removeItem('productDatas')
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/activityStatus', //rest API url
        dataType: 'json',
        data: {'ids': ids}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setCartItemStatus(statusDatas)
                showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
                showContents()
            } else {
                setCartItemStatus(data.data)
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    setCartItemStatus(statusDatas)
                    showContents()
                }, 500)
            }
        }
    });
    getMyWalletRequest();
} // main activity

// Order Manage APIs
function getMyOrderItemTemplate() {

    var orderItems = [
        {
            'id': '12345234525',
            'logo': 'assets/images/tmp/u4.png',
            'name': '特级 优质 原生态',
            'provider_id': '45872',
            'provider_name': '有机原',
            'provider_address': '特级 优质 原生态有原生态有机原',
            'provider_contact_phone': '579373629384',
            'shop_contact': '有机原',
            'shop_address': '特级 优质 原生态有原生态有机原',
            'shop_contact_phone': '579373629384',
            'amount': 2,
            'products': [
                {
                    'id': '151',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'status': 1,// 1-paid, 2-unpaid
                    'amount': 2
                },
                {
                    'id': '171',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'status': 2,// 1-paid, 2-unpaid
                    'amount': 3
                }
            ],
            'man_cnt': 5,
            'man_info': [
                {
                    'id': '101',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                },
                {
                    'id': '102',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                }
            ],
            'status': 2, // (1- 待付款, 2-待成团, 3-待发货, 4-交易成功, 5-交易关闭, 6-已退款 )
            'grouping_status': 2, // 1-已拼团, 2-拼团失败, 3-待成单
            'group_success': 0, // 0-none, 1-success, 2-fail
            'pay_type': 2,//1-online, 2-cash
            'pay_wallet': '25.00',
            'pay_price': '20.00',
            'pay_coupon': '30.00',
            'ordered_time': (new Date()).toLocaleString(),//("Y-m-d H:i:s"),
            'closed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'paid_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'refunded_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'distributed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'success_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'dist_name': '王小二',
            'dist_phone': '18452132614',

        },
        {
            'id': '45673456345615',
            'logo': 'assets/images/tmp/u5.png',
            'provider_name': '有机原',
            'provider_address': '特级 优质 原生态有机原',
            'provider_contact_phone': '579373629384',
            'shop_contact': '有机原',
            'shop_address': '特级 优质 原生态有机原',
            'shop_contact_phone': '579373629384',
            'name': '特级 优质 原生态有机米 真空包装',
            'amount': 6,
            'products': [
                {
                    'id': '151',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'amount': 2
                },
                {
                    'id': '171',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'amount': 3
                }
            ],
            'man_cnt': 5,
            'man_info': [
                {
                    'id': '101',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                },
                {
                    'id': '102',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                }
            ],
            'status': 3, // (1- 待付款, 2-待成团, 3-待发货, 4-交易成功, 5-交易关闭, 6-已退款 )
            'grouping_status': 3, // 1-已拼团, 2-拼团失败, 3-待成单
            'group_success': 0, // 0-none, 1-success, 2-fail
            'pay_type': 1,//1-online, 2-cash
            'pay_wallet': '25.00',
            'pay_price': '20.00',
            'pay_coupon': '30.00',
            'ordered_time': (new Date()).toLocaleString(),//("Y-m-d H:i:s"),
            'closed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'paid_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'refunded_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'distributed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'success_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'dist_name': '王小二',
            'dist_phone': '18452132614',

        },
        {
            'id': '1238765234525',
            'logo': 'assets/images/tmp/u4.png',
            'provider_name': '有机原',
            'provider_address': '特级 优质 原生态有机原',
            'provider_contact_phone': '579373629384',
            'shop_contact': '有机原',
            'shop_address': '特级 优质 原生态有机原',
            'shop_contact_phone': '579373629384',
            'name': '特级 优质 原生态有机米 真空包装',
            'amount': 2,
            'products': [
                {
                    'id': '151',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'status': 1,// 1-paid, 2-unpaid
                    'amount': 2
                },
                {
                    'id': '171',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'status': 2,// 1-paid, 2-unpaid
                    'amount': 0
                }
            ],
            'man_cnt': 5,
            'man_info': [
                {
                    'id': '101',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                },
                {
                    'id': '102',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                }
            ],
            'status': 1, // (1- 待付款, 2-待成团, 3-待发货, 4-交易成功, 5-交易关闭, 6-已退款 )
            'grouping_status': 2, // 1-已拼团, 2-拼团失败, 3-待成单
            'group_success': 0, // 0-none, 1-success, 2-fail
            'pay_type': 2,//1-online, 2-cash
            'pay_wallet': '25.00',
            'pay_price': '20.00',
            'pay_coupon': '30.00',
            'ordered_time': (new Date()).toLocaleString(),//("Y-m-d H:i:s"),
            'closed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'paid_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'refunded_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'distributed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'success_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'dist_name': '王小二',
            'dist_phone': '18452132614',

        },
        {
            'id': '45235345615',
            'logo': 'assets/images/tmp/u5.png',
            'provider_name': '有机原',
            'provider_address': '特级 优质 原生态有机原',
            'provider_contact_phone': '579373629384',
            'shop_contact': '有机原',
            'shop_address': '特级 优质 原生态有机原',
            'shop_contact_phone': '579373629384',
            'name': '特级 优质 原生态有机米 真空包装',
            'amount': 6,
            'products': [
                {
                    'id': '151',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'amount': 2
                },
                {
                    'id': '171',
                    'name': '态有机原生态有',
                    'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                    'old_price': Math.floor(Math.random() * 100).toFixed(2),
                    'new_price': Math.floor(Math.random() * 100).toFixed(2),
                    'barcode': Math.floor(Math.random() * 100).toFixed(2),
                    'amount': 3
                }
            ],
            'man_cnt': 5,
            'man_info': [
                {
                    'id': '101',
                    'image': 'assets/images/tmp/i2.png',
                    'name': '张二明',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                },
                {
                    'id': '102',
                    'image': 'assets/images/tmp/i1.png',
                    'name': '王万红',
                    'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
                }
            ],
            'status': 4, // (1- 待付款, 2-待成团, 3-待发货, 4-交易成功, 5-交易关闭, 6-已退款 )
            'grouping_status': 1, // 1-已拼团, 2-拼团失败, 3-待成单
            'group_success': 0, // 0-none, 1-success, 2-fail
            'pay_type': 1,//1-online, 2-cash
            'pay_wallet': '25.00',
            'pay_price': '20.00',
            'pay_coupon': '30.00',
            'ordered_time': (new Date()).toLocaleString(),//("Y-m-d H:i:s"),
            'closed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'paid_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'refunded_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'distributed_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'success_time': (new Date()).toLocaleString(),//time("Y-m-d H:i:s"),
            'dist_name': '王小二',
            'dist_phone': '18452132614',

        },
    ];

    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/orderInfos', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                sessionStorage.setItem('orderDatas', JSON.stringify(orderItems));
                showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
            } else {
                sessionStorage.setItem('orderDatas', JSON.stringify(data.data));
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            //history.back();
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    sessionStorage.setItem('orderDatas', JSON.stringify(orderItems));
                    showContents()
                }, 100)
            }
        }
    });
} // My order items

function sendOrderRequest(activityId, productAmount, payMethod, order_note) {
    //var orderId = '123412341234'
    if (order_note == undefined) order_note = '';
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/orderRequest', //rest API url
        dataType: 'json',
        data: {// set function name and parameters
            'phone': getPhoneNumber(),
            'activity': activityId,
            'count': productAmount,
            'note': order_note,
            'pay_method': payMethod,
        },
        success: function (data) {
            if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。')
                        break;
                    default:
                        showNotifyAlert('订单失败。');
                        break;
                }
            } else {
                console.log(data.data)
                if (addSessionOnlinePayOrderInfo(activityId, 0, 0, data.data)) {
                    payPrepareFromCart();
                }
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setTimeout(function () {
                    if (addSessionOnlinePayOrderInfo(activityId, 0, 0, orderId)) {
                        payPrepareFromCart();
                    }
                }, 100)
            }
        }
    });
} //order request

function sendCancelOrderRequest(orderId, sendTxt) {
    var coupon = 2;
    var myWallet = '90';
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/cancelOrderRequest', //rest API url
        dataType: 'json',
        data: {// set function name and parameters
            'phone': getPhoneNumber(),
            'order': orderId,
            'note': sendTxt,
        },
        success: function (data) {
            if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。');
                        break;
                    case 8:
                        showNotifyAlert('拼团结束以后订单不能取消。');
                        break;
                    default:
                        showNotifyAlert('该订单不能取消。');
                        break;
                }
            } else {
                setCouponStatus(data.coupon);
                setMySessionWallet(data.wallet);
                addSessionOnlinePayOrderInfo();
                if (sendTxt != '') {
                    showMessage('您的退款申请已提交,<br>我们将会尽快处理！');
                    setTimeout(function () {
                        history.go(-1);
                        clearTimeout();
                        //location.href = 'javascript:history.go(-2)'
                    }, 3000);
                } else {
                    location.reload();
                }
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error);
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setTimeout(function () {
                    setCouponStatus(coupon);
                    setMySessionWallet(myWallet);
                    addSessionOnlinePayOrderInfo();
                    history.back();
                    //location.href = 'javascript:history.back()'
                }, 100)
            }
        }
    });
} // order cancelling request

function sendPayRequest() {
    var orderId = '123412341234';
    var myPayOrderInfo = addSessionOnlinePayOrderInfo(0);
    console.log(myPayOrderInfo);
    for (var i = 0; i < myPayOrderInfo.length; i++) {
        var item = myPayOrderInfo[i];
        if (item.payInfo.pay_method != 1) continue;
        $.ajax({
            type: 'POST',
            url: REMOTE_API_URL + 'api/payOrderRequest', //rest API url
            dataType: 'json',
            data: {// set function name and parameters
                'phone': getPhoneNumber(),
                'order': item.orderId,
                'coupon': item.payInfo.coupon,
                'wallet': item.payInfo.wallet,
                'money': item.payInfo.price,
                'note': item.payInfo.order_note,
            },
            success: function (data) {
                if (data.status == false) {
                    switch (parseInt(data.err_code)) {
                        case 1:
                            showNotifyAlert('信息错误。');
                            break;
                        default:
                            showNotifyAlert('支付失败。');
                            break;
                    }
                } else {
                    console.log(data.data);
                    removeFromSessionCart(item.id);
                    if (parseInt(item.payInfo.coupon) != 0)
                        setCouponStatus(2)
                    if (parseInt(item.payInfo.wallet) != 0)
                        setMySessionWallet(getMySessionWallet() - parseInt(item.payInfo.wallet))
                    getMyWalletRequest()
                    setTimeout(function () {
                        location.reload()
                    }, 1000)
                }
            },
            error: function (data) {
                showNotifyAlert(LANG_DATA.server_error)
                if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                    //showNotifyAlert('Login Testing Success.')
                    setTimeout(function () {
                        if (parseInt(item.payInfo.coupon) != 0)
                            setCouponStatus(2)
                        if (parseInt(item.payInfo.wallet) != 0)
                            setMySessionWallet(getMySessionWallet() - parseInt(item.payInfo.wallet))
                        removeFromSessionCart(item.id)
                        location.reload()
                        getMyWalletRequest()
                    }, 1000)
                }
            }
        });
    }
} //payment request

function getLocationGroupRequest(phoneNumber) {
    if (phoneNumber == '') return;
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/getNotifications', //rest API url
        dataType: 'json',
        data: { // set function name and parameters
            'phone': phoneNumber,
        },
        success: function (data) {
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setAuthorizationStatus(HLC_AUTH_STATUS)
            } else if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。')
                        break;
                    default:
                        showNotifyAlert(data.error);
                        break;
                }
                setSessionAroundInfo('');
            } else {
                setSessionAroundInfo(data.data)
            }
            showAroundGroupingNotification();
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            setSessionAroundInfo('');
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setTimeout(function () {
                    setAuthorizationStatus(HLC_AUTH_STATUS)
                }, 100)
            }
        }
    });
}

function getMyTransactionItemsTemplate() {
    var transInfos = [
        {
            'price': (Math.floor(Math.random() * 100) + 1).toFixed(2),
            'time': (new Date()).toLocaleString(),
            'pay_type': Math.floor(Math.random() * 2), // 1-paid(-),   2-refunded(+)
            'content': '拼单康师傅方便面',
        },
        {
            'title': '购买商品',
            'price': -(Math.floor(Math.random() * 100) + 1).toFixed(2),
            'time': (new Date()).toLocaleString(),
            'pay_type': Math.floor(Math.random() * 2), // 1-online,   2-refunded(+)
            'content': '拼单康师傅方便面',
        },
    ];
    var wallet = 6666;

    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/transactionInfos', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                sessionStorage.setItem('transactionDatas', JSON.stringify(transInfos));
                setMySessionWallet(wallet);
                showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
                setMySessionWallet(0);
                showContents()
            } else {
                sessionStorage.setItem('transactionDatas', JSON.stringify(data.data));
                setMySessionWallet(data.wallet);
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            history.back();
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    sessionStorage.setItem('transactionDatas', JSON.stringify(transInfos));
                    setMySessionWallet(wallet);
                    showContents()
                }, 100)
            }
        }
    });

}

function getMyWalletRequest(isShowing) {

    isShowing = (isShowing == undefined ? false : true)
    var wallet = 6666;
    var coupon = 2;

    if (getPhoneNumber() == '') {
        if (isShowing) showContents();
        return;
    }

    setMySessionWallet(0);
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/getMyWallet', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (data.status == false) {
                if (isShowing)
                    showNotifyAlert('信息错误。')
                setMySessionWallet(0);
                setCouponStatus(0);
            } else {
                console.log(data)
                setMySessionWallet(data.wallet);
                setCouponStatus(data.coupon)
                if (isShowing)
                    showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            history.back();
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    setMySessionWallet(wallet);
                    setCouponStatus(coupon)
                    if (isShowing)
                        showContents()
                }, 100)
            }
        }
    });
}

function sendMyFeedbackRequest(msg_data) {

    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/addFeedback', //rest API url
        dataType: 'json',
        data: {'userid': getPhoneNumber(), 'feedback': msg_data}, // set function name and parameters
        success: function (data) {
            if (data.status == false) {
                showNotifyAlert('信息错误。')
            } else {
                showConfirm();
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    showConfirm();
                }, 100)
            }
        }
    });
}

function getMyFavouriteItemTemplate() {
    var favItems = [
        {
            'id': '134565',
            'type': 0,//0-activity, 1-provider
            'object_id': '23452345',
            'detail': {
                'id': '93457456734',
                'end_time': '2001-1-1 16:45:8',
                'product_image': 'assets/images/tmp/u5.png',
                'product_name': '特级 优质 原生',
                'info_size': '3g*12/箱',
                'info_box': '200箱起拼',
                'mans': 10,
                'amount': 50,
                'min_amount': 5,
                'cur_amount': 30,
                'old_price': Math.floor(Math.random() * 100).toFixed(2),
                'new_price': Math.floor(Math.random() * 100).toFixed(2),
                'logos': [
                    'assets/images/tmp/u5.png',
                    'assets/images/tmp/u3.png',
                    'assets/images/tmp/u4.png',
                    'assets/images/tmp/u1.png',
                ],
                'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                'man_info': [
                    {
                        'id': '543267',
                        'image': 'assets/images/tmp/i2.png',
                        'name': '张二明',
                        'ordered_time': '2017-1-6 13:30:25',
                    },
                    {
                        'id': '4567245',
                        'image': 'assets/images/tmp/i1.png',
                        'name': '王万红',
                        'ordered_time': '2017/1/6 13:30:25',
                    },
                    {
                        'id': '2543273',
                        'image': 'assets/images/tmp/i2.png',
                        'name': '王红',
                        'ordered_time': '2017/1/6 13:30:25',
                    }
                ],
                'total_info': [
                    {
                        'product_name': '伊利牛奶',
                        'amount': 10,
                        'price': Math.floor(Math.random() * 100).toFixed(2),
                    },
                    {
                        'product_name': '方便面',
                        'amount': 6,
                        'price': Math.floor(Math.random() * 100).toFixed(2),
                    },
                    {
                        'product_name': '面包',
                        'amount': 3,
                        'price': Math.floor(Math.random() * 100).toFixed(2),
                    }
                ],
            },

        },
        {
            'id': '509865',
            'type': 1,//0-activity, 1-provider
            'object_id': '436585',
            'detail': {
                'id': '123568457',
                'name': '供业商11',
                'image': 'assets/images/tmp/u5.png',
                'address': '特级 优质 原生态有机米 真空包装',
                'contact_name': '3g*12/箱',
                'contact_phone': '3312564987',
                'products': [
                    {
                        'id': '876543',
                        'end_time': '2001/1-1 3:5:3',
                        'product_image': 'assets/images/tmp/u4.png',
                        'product_name': '可跳转到收货地址',
                        'info_size': '1g*12/箱',
                        'info_box': '500箱起拼',
                        'mans': 3,
                        'amount': 200,
                        'min_amount': 10,
                        'cur_amount': 15,
                        'old_price': Math.floor(Math.random() * 100).toFixed(2),
                        'new_price': Math.floor(Math.random() * 100).toFixed(2),
                        'logos': [
                            'assets/images/tmp/u4.png',
                            'assets/images/tmp/u5.png',
                        ],
                        'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                        'man_info': [],
                        'total_info': [],
                    },
                    {
                        'id': '654654',
                        'end_time': '2001-1-1 16:45:8',
                        'product_image': 'assets/images/tmp/u5.png',
                        'product_name': '特级 优质 原生态有机米 真空包装',
                        'info_size': '3g*12/箱',
                        'info_box': '200箱起拼',
                        'mans': 10,
                        'amount': 50,
                        'min_amount': 5,
                        'cur_amount': 30,
                        'old_price': Math.floor(Math.random() * 100).toFixed(2),
                        'new_price': Math.floor(Math.random() * 100).toFixed(2),
                        'logos': [
                            'assets/images/tmp/u5.png',
                            'assets/images/tmp/u3.png',
                            'assets/images/tmp/u4.png',
                            'assets/images/tmp/u1.png',
                        ],
                        'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                        'man_info': [
                            {
                                'id': '101',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017-1-6 13:30:25',
                            },
                            {
                                'id': '102',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '103',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '王红',
                                'ordered_time': '2017/1/6 13:30:25',
                            }
                        ],
                        'total_info': [
                            {
                                'product_name': '伊利牛奶',
                                'amount': 10,
                                'price': Math.floor(Math.random() * 100).toFixed(2),
                            },
                            {
                                'product_name': '方便面',
                                'amount': 6,
                                'price': Math.floor(Math.random() * 100).toFixed(2),
                            },
                            {
                                'product_name': '面包',
                                'amount': 3,
                                'price': Math.floor(Math.random() * 100).toFixed(2),
                            }
                        ],
                    },
                    {
                        'id': '8743425',
                        'end_time': '2001/1/1 10:23:47',
                        'product_image': 'assets/images/tmp/u3.png',
                        'product_name': '特级 优质 原生态有机米 真空包装',
                        'info_size': '500g*3/箱',
                        'info_box': '50箱起拼',
                        'mans': 10,
                        'amount': 30,
                        'min_amount': 5,
                        'cur_amount': 20,
                        'old_price': Math.floor(Math.random() * 100).toFixed(2),
                        'new_price': Math.floor(Math.random() * 100).toFixed(2),
                        'logos': [
                            'assets/images/tmp/u3.png',
                            'assets/images/tmp/u4.png',
                            'assets/images/tmp/u2.png',
                        ],
                        'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                        'man_info': [
                            {
                                'id': '101',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '102',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '103',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '104',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '105',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '106',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                        ],
                        'total_info': [],
                    },
                ],
                'logos': [
                    'assets/images/tmp/u5.png',
                    'assets/images/tmp/u3.png',
                    'assets/images/tmp/u4.png',
                    'assets/images/tmp/u1.png',
                ],
            },

        },
        {
            'id': '183565',
            'type': 0,//0-activity, 1-provider
            'object_id': '8764546',
            'detail': {
                'id': '3246757',
                'end_time': '2001-1-1 16:45:8',
                'product_image': 'assets/images/tmp/u5.png',
                'product_name': '态有机米 真空包装',
                'info_size': '3g*12/箱',
                'info_box': '200箱起拼',
                'mans': 10,
                'amount': 50,
                'min_amount': 5,
                'cur_amount': 30,
                'old_price': Math.floor(Math.random() * 100).toFixed(2),
                'new_price': Math.floor(Math.random() * 100).toFixed(2),
                'logos': [
                    'assets/images/tmp/u5.png',
                    'assets/images/tmp/u3.png',
                    'assets/images/tmp/u4.png',
                    'assets/images/tmp/u1.png',
                ],
                'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                'man_info': [
                    {
                        'id': '101',
                        'image': 'assets/images/tmp/i2.png',
                        'name': '张二明',
                        'ordered_time': '2017-1-6 13:30:25',
                    },
                    {
                        'id': '102',
                        'image': 'assets/images/tmp/i1.png',
                        'name': '王万红',
                        'ordered_time': '2017/1/6 13:30:25',
                    },
                    {
                        'id': '103',
                        'image': 'assets/images/tmp/i2.png',
                        'name': '王红',
                        'ordered_time': '2017/1/6 13:30:25',
                    }
                ],
                'total_info': [
                    {
                        'product_name': '伊利牛奶',
                        'amount': 10,
                        'price': Math.floor(Math.random() * 100).toFixed(2),
                    },
                    {
                        'product_name': '方便面',
                        'amount': 6,
                        'price': Math.floor(Math.random() * 100).toFixed(2),
                    },
                    {
                        'product_name': '面包',
                        'amount': 3,
                        'price': Math.floor(Math.random() * 100).toFixed(2),
                    }
                ],
            },
        },
        {
            'id': '809865',
            'type': 1,//0-activity, 1-provider
            'object_id': '4364546585',
            'detail': {
                'id': '19871',
                'name': '商22',
                'image': 'assets/images/tmp/u5.png',
                'address': '特级 态有机米 真空包装',
                'contact_name': '3g*12/箱',
                'contact_phone': '3312564987',
                'products': [
                    {
                        'id': '9055346',
                        'end_time': '2001-1-1 16:45:8',
                        'product_image': 'assets/images/tmp/u5.png',
                        'product_name': '特级 优质 原生态有机米 真空包装',
                        'info_size': '3g*12/箱',
                        'info_box': '200箱起拼',
                        'mans': 10,
                        'amount': 50,
                        'min_amount': 5,
                        'cur_amount': 30,
                        'old_price': Math.floor(Math.random() * 100).toFixed(2),
                        'new_price': Math.floor(Math.random() * 100).toFixed(2),
                        'logos': [
                            'assets/images/tmp/u5.png',
                            'assets/images/tmp/u3.png',
                            'assets/images/tmp/u4.png',
                            'assets/images/tmp/u1.png',
                        ],
                        'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                        'man_info': [
                            {
                                'id': '101',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017-1-6 13:30:25',
                            },
                            {
                                'id': '102',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '103',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '王红',
                                'ordered_time': '2017/1/6 13:30:25',
                            }
                        ],
                        'total_info': [
                            {
                                'product_name': '伊利牛奶',
                                'amount': 10,
                                'price': Math.floor(Math.random() * 100).toFixed(2),
                            },
                            {
                                'product_name': '方便面',
                                'amount': 6,
                                'price': Math.floor(Math.random() * 100).toFixed(2),
                            },
                            {
                                'product_name': '面包',
                                'amount': 3,
                                'price': Math.floor(Math.random() * 100).toFixed(2),
                            }
                        ],
                    },
                    {
                        'id': '32462684',
                        'end_time': '2001/1/1 10:23:47',
                        'product_image': 'assets/images/tmp/u3.png',
                        'product_name': '特级 优质 原生态有机米 真空包装',
                        'info_size': '500g*3/箱',
                        'info_box': '50箱起拼',
                        'mans': 10,
                        'amount': 30,
                        'min_amount': 5,
                        'cur_amount': 20,
                        'old_price': Math.floor(Math.random() * 100).toFixed(2),
                        'new_price': Math.floor(Math.random() * 100).toFixed(2),
                        'logos': [
                            'assets/images/tmp/u3.png',
                            'assets/images/tmp/u4.png',
                            'assets/images/tmp/u2.png',
                        ],
                        'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
                        'man_info': [
                            {
                                'id': '101',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '102',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '103',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '104',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '105',
                                'image': 'assets/images/tmp/i2.png',
                                'name': '张二明',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                            {
                                'id': '106',
                                'image': 'assets/images/tmp/i1.png',
                                'name': '王万红',
                                'ordered_time': '2017/1/6 13:30:25',
                            },
                        ],
                        'total_info': [],
                    },
                ],
                'logos': [
                    'assets/images/tmp/u5.png',
                    'assets/images/tmp/u3.png',
                    'assets/images/tmp/u4.png',
                    'assets/images/tmp/u1.png',
                ],
            },
        },
    ];
    if (getPhoneNumber() == '') {
        showContents();
        return;
    }
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/myFavorite', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                sessionStorage.setItem('favoriteDatas', JSON.stringify(favItems));
                showContents()
            } else if (data.status == false) {
                showNotifyAlert('信息错误。')
                sessionStorage.removeItem('favoriteDatas');
                showContents()
            } else {
                sessionStorage.setItem('favoriteDatas', JSON.stringify(data.data));
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            sessionStorage.removeItem('favoriteDatas');
            showContents()
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    sessionStorage.setItem('favoriteDatas', JSON.stringify(favItems));
                    showContents()
                }, 100)
            }
        }
    });
} // my fav activities, my fav providers

function sendAddFavouriteRequest(type, objectId) {
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/addFavorite', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber(), 'type': type, 'object_id': objectId}, // set function name and parameters
        success: function (data) {
            if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。')
                        break;
                    default:
                        showNotifyAlert('账号信息错误。');
                        break;
                }
            } else {
                if (type == 0)
                    setFavouriteStatus(objectId, true, data.favorite_id);
                else
                    setProviderFavouriteStatus(objectId, true, data.favorite_id)
                showFavouriteStatus();
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setTimeout(function () {
                    if (type == 0)
                        setFavouriteStatus(objectId, true, 1);
                    else
                        setProviderFavouriteStatus(objectId, true, 1)
                    showFavouriteStatus();
                }, 100)
            }
        }
    });
}

function sendRemoveFavouriteRequest(type, objectId) {
    var favouriteId = (type == 0) ? getFavouriteStatus(objectId) : getProviderFavouriteStatus(objectId)
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/removeFavorite', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber(), 'favorite_id': favouriteId}, // set function name and parameters
        success: function (data) {
            if (data.status == false) {
                switch (parseInt(data.err_code)) {
                    case 1:
                        showNotifyAlert('信息错误。')
                        break;
                    case 2:
                        showNotifyAlert('该账号不存在。');
                        break;
                    case 4:
                        showNotifyAlert('密码错误。');
                        break;
                    default:
                        showNotifyAlert('账号信息错误。');
                        break;
                }
            } else {
                if (type == 0)
                    setFavouriteStatus(objectId, false);
                else
                    setProviderFavouriteStatus(objectId, false)
                showFavouriteStatus();
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            setRegisterStatus(false);
            if (HLC_LOGIN_MODE == HLC_SIMUL_MODE) {
                //showNotifyAlert('Login Testing Success.')
                setTimeout(function () {
                    if (type == 0)
                        setFavouriteStatus(objectId, false);
                    else
                        setProviderFavouriteStatus(objectId, false)
                    showFavouriteStatus();
                }, 100)
            }
        }
    });
}

function getMyStoreInfoTemplate() {
    if (HLC_APP_MODE == HLC_SIMUL_MODE) {
        var myUserInfo = {
            id: '12341234',
            number: '652458136862456256245624568',
            user_name: '张某某',
            user_phone: '12345678901',
            user_addr: '北京朝阳区芳香园12区5号楼502室',
            user_image: 'assets/images/tmp/i2.png',
            cert_image: 'assets/images/tmp/authcard.png',
            contact_phone: '18734268273',
            status: HLC_AUTH_STATUS,
            coupon: 2, //0-no coupon, 1-unused, 2-used
        }
        setAuthorizationStatus(HLC_AUTH_STATUS)
        if (HLC_AUTH_STATUS)
            setSessionMyInfo(myUserInfo);
        else
            setSessionMyInfo('')
    }
}

function getMainNewsItemTemplate() {

    var newsInfos = [
        {
            'content': 'testing news 1',
            'sent_time': '2017-10-6 21:00:00'
        },
        {
            'content': 'testing news 1',
            'sent_time': '2017-10-6 20:00:00'
        },
        {
            'content': 'Proin gravida dolor sit amet lacus accumsan Cum sociis natoque penatibus et magnis dis parturien',
            'sent_time': '2017/10/6 19:00:00'
        },
        {
            'content': 'Proin gravida dolor sit amet lacus accumsan Cum sociis natoque penatibus et magnis dis parturien',
            'sent_time': '2017/10/6 18:00:00'
        },
        {
            'content': 'testing news 2',
            'sent_time': '2017/10/5 18:00:00'
        },
        {
            'content': 'testing news 2',
            'sent_time': '2017/10/5 8:00:00'
        },
        {
            'content': 'Testing News 123',
            'sent_time': '2017/10/4 8:30:00'
        },
        {
            'content': 'Testing News 123',
            'sent_time': '2017/10/4 8:00:00'
        },
        {
            'content': 'Notification 123',
            'sent_time': '2017/10/3 20:00:00'
        },
        {
            'content': 'Notification 123',
            'sent_time': '2017/10/3 8:00:00'
        },
    ]
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/newsInfos', //rest API url
        dataType: 'json',
        data: {'phone': getPhoneNumber()}, // set function name and parameters
        success: function (data) {
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                sessionStorage.setItem('newsDatas', JSON.stringify(newsInfos));
                showContents()
            } else if (data.status == false) {
                //showNotifyAlert('信息错误。');
                sessionStorage.setItem('newsDatas', '[]');
                showContents()
            } else {
                sessionStorage.setItem('newsDatas', JSON.stringify(data.data));
                showContents()
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error)
            history.back();
            if (HLC_APP_MODE == HLC_SIMUL_MODE) {
                // showNotifyAlert('Product Testing Success.')
                setTimeout(function () {
                    sessionStorage.setItem('newsDatas', JSON.stringify(newsInfos));
                    showContents()
                }, 100)
            }
        }
    });
}

//获取地理位置信息start
//封装成一个函数
function getMyPosition() {

    //alert('start');
    var myLocation = null;
    var map = new AMap.Map('my_Map', {
        resizeEnable: true,
        zoom: 15,
        scrollWheel: true
    });

    // add plugin to get current GPS location
    map.plugin('AMap.Geolocation', function () {
        myLocation = new AMap.Geolocation({
            enableHighAccuracy: true,//是否使用高精度定位，默认:true
            //timeout: 0,          //超过10秒后停止定位，默认：无穷大
            buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
            zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            GeoLocationFirst: true
        });
        myLocation.getCurrentPosition();
        //alert('called');
        AMap.event.addListener(myLocation, 'complete', getLocationCompleted);//返回定位信息
        AMap.event.addListener(myLocation, 'error', onErrorLocation);
    });
    //alert('object created.');

    //解析定位结果
    function getLocationCompleted(data) {
        //    alert(JSON.stringify(data));

        $('#my_LatLng').val(JSON.stringify(data));
        var myLocationStr = '';
        myLocationStr += data.addressComponent.province + ' ';
        myLocationStr += data.addressComponent.city + ' ' + data.addressComponent.district + ' ';
        myLocationStr += data.addressComponent.township + ' ' + data.addressComponent.street + ' ';
        myLocationStr += data.addressComponent.streetNumber;
        $('#shop_addr').val(myLocationStr);
    }

    function onErrorLocation(data) {
        alert('这手机不能接收地理定位服务。\n 请再次检查网络设置。\n error:' + data.message);
    }
}

function getLocationFromLatLng() {  //POI搜索，关键字查询

    var lat_lng = $('#my_LatLng').val();
    if (lat_lng == "") {
        return;
    }

    var lnglatXY = new AMap.LngLat(
        JSON.parse(lat_lng).lng,
        JSON.parse(lat_lng).lat
    );
    //加载地理编码插件

    var mapObj = new AMap.Map('my_Map', {
        resizeEnable: true,
        zoom: 15,
        scrollWheel: true
    });
    mapObj.plugin(["AMap.Geocoder"], function () {
        MGeocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: "all"
        });
        //返回地理编码结果
        AMap.event.addListener(MGeocoder, "complete", getLocationCompleted);
        AMap.event.addListener(MGeocoder, "error", onErrorLocation);
        //逆地理编码
        MGeocoder.getAddress(lnglatXY);
    });

    function getLocationCompleted(data) {
        //alert(JSON.stringify(data));
        //var myLocation = {lat: data.position.getLat(), lng: data.position.getLng()};

        data = data.regeocode;
        var myLocationStr = '';
        myLocationStr += data.addressComponent.province + ' ';
        myLocationStr += data.addressComponent.city + ' ' + data.addressComponent.district + ' ';
        myLocationStr += data.addressComponent.township + ' ' + data.addressComponent.street + ' ';
        myLocationStr += data.addressComponent.streetNumber;
        $('#shop_addr').val(myLocationStr);
    };//返回定位信息
    function onErrorLocation(data) {
        alert('这手机不能接收地理定位服务。\n 请再次检查网络设置。\n error:' + data.message);
    }
}