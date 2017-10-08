var app_data = {
    // timerID,           // timer indentifier
    'time_counter': 0,   // timer counter that is used when timer runs
    'phone_num': "",     // user's phone number
    'auth_status': 0,    // user's authorization stat
    'sms_code': ""       // SMS authorization code
}

var SERVER_URL = 'http://192.168.2.18/hlcfront/';

var HLC_REAL_MODE = 0; //0-real environment
var HLC_SIMUL_MODE = 1; //1-real environment

var HLC_LOGIN_MODE = HLC_SIMUL_MODE;
var HLC_SMS_MODE = HLC_SIMUL_MODE;

// send login request to server
function sendLoginRequest(phonenumber, password) {
    if (HLC_LOGIN_MODE == HLC_REAL_MODE) {
        $.ajax({
            type: 'POST',
            url: SERVER_URL + 'api/Login', //rest API url
            dataType: 'json',
            data: {'phone': phonenumber, 'password': password}, // set function name and parameters
            success: function (data) {
                if (data.status == false) {
                    switch (parseInt(data.err_code)) {
                        case 1:  // user is wrong
                            alert('该用户不存在。');
                            break;
                        case 2: // password is wrong
                            alert('密码错误。');
                            break;
                        case 3: //
                    }
                    sessionStorage.setItem('phone_num', phonenumber);
                } else {
                    sessionStorage.setItem('phone_num', phonenumber);
                    sessionStorage.setItem('auth_status', 1);

                    window.location.href = 'home.php';
                }
            },
            error: function (data) {
                showMessage('服务器错误。')
            }
        });
    } else {
        sessionStorage.setItem('phone_num', phonenumber);
        sessionStorage.setItem('auth_status', 1);
        window.location.href = 'home.php';
    }
}

//send user register request to server
function sendRegisterRequest(phonenumber, password, servant_phone) {
    if (HLC_LOGIN_MODE == HLC_REAL_MODE) {
        $.ajax({
            type: 'POST',
            url: SERVER_URL + 'api/RegisterUser', //rest API url
            dataType: 'json',
            data: {'phone': phonenumber, 'password': password, 'servant': servant_phone}, // set function name and parameters
            success: function (data) {
                if (data.status == false) {
                    showMessage('这个用户已存在。');
                } else {
                    sessionStorage.setItem('phone_num', phonenumber);
                    sessionStorage.setItem('auth_state', 0);

                    $('#auth_question').modal();
                    showModalToCenter('auth_question');
                }
            },
            error: function (data) {
                showMessage('服务器错误。')
            }
        });
    } else {
        // show the dialog
        sessionStorage.setItem('phone_num', phonenumber);
        $('#auth_question').modal();
        showModalToCenter('auth_question');
    }
}

// send password set forget password request
function sendSetforgetPassword() {
    if (HLC_LOGIN_MODE == HLC_REAL_MODE) {
        $.ajax({
            type: 'POST',
            url: SERVER_URL + 'api/forgetPassword', //rest API url
            dataType: 'json',
            data: {'phone': phonenumber, 'password': password}, // set function name and parameters
            success: function (data) {
                if (data.status == false) {
                    showMessage('用户不存在。');
                } else {
                    showMessage('message_dialog', '密码已修改成功');
                    window.location.href = '../index.php';
                }
            },
            error: function (data) {
                showMessage('服务器错误。')
            }
        });
    } else {
        showMessage('message_dialog', '密码已修改成功');
        window.location.href = 'user_login.php';
    }
}

// upload user's individual information to server
function sendUploadUserInfo(userinfo) {
    // upload two files.

    if (HLC_LOGIN_MODE == HLC_REAL_MODE) {
        $.ajax({
            type: 'POST',
            url: SERVER_URL + 'api/uploadUserInfo', //rest API url
            dataType: 'json',
            data: userinfo, // set function name and parameters
            success: function (data) {
                if (data.status == false) {
                    alert('上传失败。');
                } else {
                    app_data.auth_status = 1;
                    sessionStorage.setItem('auth_status', 1);
                    window.location.href = '../../index.html';
                }
            },
            error: function (data) {
                alert('服务器错误。')
            }
        });
    } else {

        window.location.href = "register_success.html";
    }
}

function sendSMSToServer(phone_num) {
    // run timer
    app_data.time_counter = 60;
    app_data.sms_code = "";
    app_data['timerID'] = setInterval(function () {
        calculateRemainTime()
    }, 1000);
    $('#sms_button').attr({
        'id': 'sms_button_sending',
        'onclick': 'restoreSMSButton()'
    });

    if (HLC_SMS_MODE == HLC_REAL_MODE) {
        //send SMS sending request in backend server.
        $('#loading').css({display: 'block'});
        $.ajax({
            type: 'POST',
            url: 'http://www.ayoubc.com/tour/plugin/SMS/SendTemplateSMS.php', //rest API url
            dataType: 'json',
            data: {'phone_num': phone_num}, // set function name and parameters
            success: function (data) {
                // get SMS code from received data
                $('#loading').css({display: 'none'});
                if (data['result'] == "success") {
                    app_data.sms_code = data['code'];
                } else {
                    app_data.sms_code = "";
                    alert(data.error['0']);
                }
            },
            fail: function () {
                return;
            }
        });
    } else {
        app_data.sms_code = "1234";
    }
}

function restoreSMSButton() {
    clearTimer();

    $('#sms_button_sending').attr({
        'id': 'sms_button',
        'onclick': 'sendingSMS()'
    });
    $('#sms_button').html('重新获取');
}

function calculateRemainTime() {
    app_data.time_counter--;
    if (app_data.time_counter == 0) {
        clearTimer();
        restoreSMSButton();
        return;
    }
    $('#sms_button_sending').html(app_data.time_counter + '秒可重发');
}

function clearTimer() {
    clearInterval(app_data.timerID);
    app_data.timerID = undefined;
}

function selectBottomItem(index, pageindex) {

    if(pageindex==0) {
        for (var i = 1; i < 5; i++) {
            if (i == index) {
                $("#bottom_item_image" + i).attr('src', 'assets/images/tabbar_icon' + i + '_d@3x.png');
                $("#bottom_item_text" + i).attr('style', 'color: #38abff');
            }
            else {
                $("#bottom_item_image" + i).attr('src', 'assets/images/tabbar_icon' + i + '_n@3x.png');
                $("#bottom_item_text" + i).attr('style', '');
            }
        }
        if(data.cur_bottom_index == index) return;
        sessionStorage.setItem('cur_bottom_index',index);
        data.cur_bottom_index = index;
        switch (index) {
            case 1:
                showNotification("李老师 - 参加这个顶买第一菜单。>");
                break;
            case 2:
                showNotification("这是第二菜单。>");
                break;
            case 3:
                showMessage("这是第三菜单。");
                break;
            case 4:
                setTimeout(function () {
                    location.href = "myfunction_manage.php";
                }, 500)
                break;
        }
    }
}

function showModalToCenter(id) {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;
    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var dialog_height = $('#' + id).css('height');
    var margin_height = (parseInt(height) - parseInt(dialog_height)) / 2;
    $('.modal-scrollable').css({width: parseInt(width) * 0.7, margin: 'auto', 'margin-top': margin_height});
}

function showMessage(message) {
    $('#message_dialog .modal-body').html('<b>' + message + '</b>');
    $('#message_dialog').modal();
    setTimeout(function () {
        //$('#message_dialog').modal('hide');
    }, 3000);
    showModalToCenter('message_dialog');
}

function showNotification(message) {
    $('#notification_bar').html(message);

    $('#notification_bar').show();
    setTimeout(function () {
        $('#notification_bar').hide();
    }, 6900);
}

// generate simulation datas for menu bar
function simulat_menu_infos() {
    display_menu_infos();
}

// generate advertise image list for the advertise part
function simulate_advertise_images() {
    display_advertise_images();
}

// display the product list on the content
function showProductDetailInfo(index) {
    location.href = 'product_detail.php?productid=' + index;
}

function showOrderDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "order_detail.php"
}

function showGroupingDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "grouping_detail.php"
}

// This is the part that store and load the object in localStorage
Storage.prototype.setObject = function (key, value) {
    this.setItem(key, JSON.stringify(value));
}
Storage.prototype.getObject = function (key) {
    var val = this.getItem(key);
    if (val == "" || val == null) return null;
    return JSON.parse(val);
}