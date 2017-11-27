var app_data = {
//'timerID': NULL,           // timer indentifier
    'time_counter': 0,   // timer counter that is used when timer runs
    'sms_code': ""       // SMS authorization code
};

function sendingSMS() {
    var phone_num = $('#phone_number').val();
    if (phone_num == "" || phone_num.length != 11) {
        showMessage('手机号不正确。');
        return;
    }
// run timer
    app_data.time_counter = 60;
    app_data.sms_code = "";
    app_data['timerID'] = setInterval(function () {
        calculateRemainTime()
    }, 1000);
    $('#sms_button').attr({
        'id': 'sms_button_sending',
//        'onclick': 'restoreSMSButton()',
        'onclick': '',
        'style': 'color:darkgrey;',
    });
//send message sending request to server
    sendSMSToServer(phone_num);
}

function restoreSMSButton() {
    clearTimer();

    $('#sms_button_sending').attr({
        'id': 'sms_button',
        'onclick': 'sendingSMS()',
        'style': 'color:#38abff;'
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

function getPhoneNumber() {
    if (getUserInfo() == '')
        return '';
    else
        return getUserInfo().userid;

//    if (localStorage.getItem('phone_number') == undefined)
//        return '';
//    else
//        return localStorage.getItem('phone_number');
}

function setPhoneNumber(phone_number) {
    if (phone_number == '')
        localStorage.removeItem('phone_number');
    else
        localStorage.setItem('phone_number', phone_number)
}

function getSessionPassword() {
    if (localStorage.getItem('shipper_user_number') == undefined)
        return '';
    else
        return localStorage.getItem('shipper_user_number');
}

function setSessionPassword(user_number) {
    if (user_number == '')
        localStorage.removeItem('shipper_user_number');
    else
        localStorage.setItem('shipper_user_number', user_number)
}

function getUserInfo() {
    if (localStorage.getItem('shipper_data') == undefined)
        return '';
    else
        return JSON.parse(localStorage.getItem('shipper_data'));
}

function setUserInfo(userInfo) {
    if (userInfo == '')
        localStorage.removeItem('shipper_data');
    else
        localStorage.setItem('shipper_data', JSON.stringify(userInfo));
}

function getAuthorizationStatus() {
    return true;
    if (localStorage.getItem('isAuthorized') == undefined)
        return false;
    else
        return JSON.parse(localStorage.getItem('isAuthorized'));
}

function setAuthorizationStatus(status) {
    if (status == false)
        localStorage.removeItem('isAuthorized');
    else
        localStorage.setItem('isAuthorized', JSON.stringify(status))
}

function setRegisterStatus(status) {
    if (status == false)
        sessionStorage.removeItem('isRegistered')
    else
        sessionStorage.setItem('isRegistered', JSON.stringify(status))
}

function getRegisterStatus() {
//return true;
    if (localStorage.getItem('isRegistered') == undefined)
        return false;
    else
        return JSON.parse(localStorage.getItem('isRegistered'));
}

// display menu items on the horizontal menu bar
function decreaseAmount(id) {
    var curVal = parseInt($('#product_amount' + id).val());
    var minVal = parseInt($('#min_amount' + id).val());
    if (curVal > minVal) $('#product_amount' + id).val(curVal - 1);
    else $('#product_amount' + id).val(minVal);
}

// display menu items on the horizontal menu bar
function increaseAmount(id) {
    var curVal = parseInt($('#product_amount' + id).val());
    var maxVal = parseInt($('#max_amount' + id).val());
    if (curVal < maxVal) $('#product_amount' + id).val(curVal + 1);
    else $('#product_amount' + id).val(maxVal);
}

// display menu items on the horizontal menu bar
function validateAmount(id) {
    var curVal = parseInt($('#product_amount' + id).val());
    var minVal = parseInt($('#min_amount' + id).val());
    var maxVal = parseInt($('#max_amount' + id).val());

    if (curVal < minVal)
        $('#product_amount' + id).val(minVal);
    else if (curVal > maxVal)
        $('#product_amount' + id).val(curVal.toString().substr(0, curVal.toString().length - 1));
    else
        $('#product_amount' + id).val(curVal);
};

function selectBottomItem(index, pageindex) {

    if (pageindex == 0) {
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
        if (data.cur_bottom_index == index) return;
        sessionStorage.setItem('cur_bottom_index', index);
        data.cur_bottom_index = index;
        switch (index) {
            case 1:
                setTimeout(function () {
                    location.href = "home.php";
                }, 5);
                break;
            case 2:
                setTimeout(function () {
                    location.href = "mycart_manage.php";
                }, 5);
                break;
            case 3:
                setTimeout(function () {
                    location.href = "main_news.php";
                }, 5);
                break;
            case 4:
                setTimeout(function () {
                    location.href = "myfunction_manage.php";
                }, 5);
                break;
        }
    }
}

/////////////This function is shipper
function selectShipperBottomItem(index, pageindex) {

    if (pageindex == 0) {
        for (var i = 1; i < 4; i++) {
            if (i == index) {
                //if (i == 3) i = 3;
                $("#bottom_item_image" + i).attr('src', 'assets/images/dist_tabbar_icon' + i + '_d@3x.png');
                $("#bottom_item_text" + i).attr('style', 'color: #38abff');
            }
            else {
                //if (i == 3) i = 4;
                $("#bottom_item_image" + i).attr('src', 'assets/images/dist_tabbar_icon' + i + '_n@3x.png');
                $("#bottom_item_text" + i).attr('style', '');
            }
        }
        if (data.cur_bottom_index == index) return;
        sessionStorage.setItem('cur_bottom_index', index);
        data.cur_bottom_index = index;
        switch (index) {
            case 1:
                setTimeout(function () {
                    location.href = "shipper_order.php";
                }, 5);
                break;
            case 2:
                setTimeout(function () {
                    location.href = "shipper_history.php";
                }, 5);
                break;
            case 3:
                setTimeout(function () {
                    location.href = "shipper_manage.php";
                }, 5);
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

    $('.modal-scrollable').css({width: parseInt(width) * 0.7});
    $('.modal-scrollable').css({margin: 'auto', 'margin-top': margin_height});
}

function showMessage(message, isShowbtn) {
    if (isShowbtn == 2) {
        $('#msg_cancel').hide();
        $('#msg_ok').attr('onclick', "$('#message_dialog').modal('hide');");
    } else {
        $('#msg_cancel').show();
        $('#msg_ok').attr('onclick', "onOk();");
    }
    isShowbtn = (isShowbtn == undefined) ? false : true;
    message = (isShowbtn == false) ? message = '<br>' + message : message;
    $('#message_dialog .modal-body').html('<b><center>' + message + '</center></b>');
    $('#message_dialog').modal();
    if (!isShowbtn) {
        $('#message_dialog .modal-footer').css({'display': 'none'});
        clearTimeout(app_data.notifyTimer);
        app_data.notifyTimer = setTimeout(function () {
            $('#message_dialog').modal('hide');
        }, 3000);
    } else {
        $('#message_dialog .modal-footer').css({'display': 'block'});
    }

    showModalToCenter('message_dialog');
}

function showAuthRequire(message, isShowbtn) {
    if (getAuthorizationStatus()) {
        return;
    }
    isShowbtn = (isShowbtn == undefined) ? true : false;
    $('#auth_question .modal-body').html('<b>' + message + '</b>');
    $('#auth_question').modal();
    if (!isShowbtn) {
        $('#auth_question .modal-footer').html('');
        setTimeout(function () {
            $('#auth_question').modal('hide');
        }, 3000);
    }
    showModalToCenter('auth_question');
}

function showLoading(status, message) {
    message = (message == undefined) ? '正在加载中，请等一下。' : message;
    $('#message_loading .modal-body').html('<b>' + message + '</b>');
    if (status == 1)
        $('#message_loading').modal();
    else
        $('#message_loading').modal('hide');
    showModalToCenter('message_loading');
}

function showNotification(message) {
    $('#notification_bar').html(message);

    $('#notification_bar').show();
    setTimeout(function () {
        $('#notification_bar').hide();
    }, 6900);
}

function showNotifyAlert(message, type, bottom_position) {
    if (message == '') return;
    if (type == undefined) type = 0;
    bottom_position = 60;
    if ((DetectIOSDevice() == 'ipad' || DetectIOSDevice() == 'iphone')) {
        var height = document.body.clientHeight
            || document.documentElement.clientHeight
            || window.innerHeight;
        bottom_position = height - 290;
    }
    console.log(bottom_position);

    $('#notification_bar').hide();
    $('#notification_alert_bar').html(message);
    if (type == 0) {
        $('#notification_alert_bar').css({
            'background-color': 'rgba(255, 255, 255, 0.7)',
            'color': 'red',
            'border-color': 'red',
        })
    } else {
        $('#notification_alert_bar').css({
            'background-color': 'rgba(30, 30, 255, 0.7)',
            'color': '#38abff',
            'border-color': '#38abff'
        })
    }
    // alert(bottom_position);
    $('#notification_alert_bar').css({
//        'top': bottom_position,
        'bottom': bottom_position
    });
    $('#notification_alert_bar').show();
    clearTimeout(app_data.notifyTimer);
    app_data.notifyTimer = setTimeout(function () {
        $('#notification_alert_bar').hide();
    }, 6900);
    console.log(app_data.notifyTimer);
}

function validateText() {
    var txt = $('#textarea').val();
    var txtLen = parseInt($('#textLength').html().split('/')[1]);
    if (txt.length > txtLen) {
        txt = txt.substring(0, txtLen);
        $('#textarea').val(txt);
    }
    $('#textLength').html(txt.length + '/' + txtLen);
    if (txt.length < 10) {
        $('.btn_confirm').css({'background': 'darkgrey'});
        $('.btn_confirm').attr('onclick', '');
    } else {
        $('.btn_confirm').css({'background': '#38abff'});
        $('.btn_confirm').attr('onclick', 'sendFeedback();')
    }

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
    location.href = 'product_detail.php?iId=' + index;
}

function showOrderDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "order_detail.php?iId='" + orderId + "'";
}

//PMS-code
function showShipperOrderDetailInfo(orderId) {

    data.cur_detail_index = orderId;
    sessionStorage.setItem('cur_shipper_detail_index', orderId);
    location.href = "shipper_order_detail.php?iId='" + orderId + "'";

}

function showShipperHistoryDetailInfo(orderId) {

    data.cur_detail_index = orderId;
    sessionStorage.setItem('cur_shipper_history_detail_index', orderId);
    location.href = "shipper_history_detail.php?iId='" + orderId + "'";

}

function showGroupingDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "grouping_detail.php?iId='" + orderId + "'";
}

////Below function is to detection div scroll end postion (at the bottom reasearch)--------PMS
var scrollEndDetection = function (divTag) {
    if ($(divTag).scrollTop() + $(divTag).innerHeight() >= $(divTag)[0].scrollHeight) {
        return true;
    }
    return false;
};

function DetectIOSDevice() {
    var uagent = navigator.userAgent.toLowerCase();
    if (uagent.search("iphone") > -1)
        return 'iphone';
    else if (uagent.search("ipad") > -1)
        return 'ipad';
    else if (uagent.search("ipod") > -1)
        return 'ipod';
    else
        return 'android';
}

// This is the part that store and load the object in localStorage

Storage.prototype.setObject = function (key, value) {
    this.setItem(key, JSON.stringify(value));
};
Storage.prototype.getObject = function (key) {
    var val = this.getItem(key);
    if (val == "" || val == null) return null;
    return JSON.parse(val);
}