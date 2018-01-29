var app_data = {
    //'timerID': NULL,           // timer indentifier
    'time_counter': 0,   // timer counter that is used when timer runs
    'sms_code': ""       // SMS authorization code

}

function getPhoneVerifiedStatus(genCode, inputCode) {
    var ret = false;

    if (genCode == inputCode)
        ret = true;

    return ret;
}

function disableBackButton() {
    history.forward();
}

function is_weixin() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        return true;
    } else {
        return false;
    }
}

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

function sendingSMS() {
    var phone_num = $('#phone_number').val();
    if (phone_num == "" || phone_num.length != 11) {
        showNotifyAlert('手机号码不正确。');
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

function prepareRealPayment(price, orderId, itemTxt) {
    phone_num = getPhoneNumber()

    // location.href = 'payment.php' +
    //     '?cost=' + price +
    //     '&type=' + orderId +
    //     '&product=' + itemTxt;
}

function increaseCartAmount() {
    var temp = $('#cart_amount').html() == '' ? 0 : parseInt($('#cart_amount').html());
    $('#cart_amount').html(temp++);
}

function decreaseCartAmount() {
    var temp = $('#cart_amount').html();
    temp = parseInt(temp) - 1;
    if (temp == 0) temp = '';
    $('#cart_amount').html(temp);
}

function getPhoneNumber() {
    if (localStorage.getItem('phone_number') == undefined)
        return '';
    else
        return localStorage.getItem('phone_number');
}

function setPhoneNumber(phone_number) {
    if (phone_number == '')
        localStorage.removeItem('phone_number');
    else
        localStorage.setItem('phone_number', phone_number)
}

function getSessionMyInfo() {
    if (localStorage.getItem('myUserInfo') == undefined)
        return '';
    else
        return JSON.parse(localStorage.getItem('myUserInfo'));
}

function setSessionMyInfo(myInfo) {
    if (myInfo == '')
        localStorage.removeItem('myUserInfo');
    else
        localStorage.setItem('myUserInfo', JSON.stringify(myInfo))
}

function getSessionAroundInfo() {
    if (sessionStorage.getItem('myAroundInfo') == undefined)
        return '';
    else
        return JSON.parse(sessionStorage.getItem('myAroundInfo'));
}

function setSessionAroundInfo(myInfo) {
    if (myInfo == '')
        sessionStorage.removeItem('myAroundInfo');
    else
        sessionStorage.setItem('myAroundInfo', JSON.stringify(myInfo))
}

function getSessionPassword() {
    if (localStorage.getItem('user_number') == undefined)
        return '';
    else
        return localStorage.getItem('user_number');
}

function setSessionPassword(user_number) {
    if (user_number == '')
        localStorage.removeItem('user_number');
    else
        localStorage.setItem('user_number', user_number)
}

function getAuthorizationStatus() {
    return ((localStorage.getItem('isAuthorized') == undefined) ? false : true)
}

function setAuthorizationStatus(status) {
    if (status == false)
        localStorage.removeItem('isAuthorized');
    else
        localStorage.setItem('isAuthorized', '1')
}

function getAuthRequestStatus() {
    return ((localStorage.getItem('isAuthRequested') == undefined) ? false : true)
}

function setAuthRequestStatus(status) {
    if (status == false)
        localStorage.removeItem('isAuthRequested');
    else
        localStorage.setItem('isAuthRequested', '1')
}

function getGroupNotifyMsg() {
    return parseInt((sessionStorage.getItem('groupNotifyMsg') == undefined) ? 0 : parseInt(sessionStorage.getItem('groupNotifyMsg')))
}

function setGroupNotifyMsg(data) {
    if (data == '' || data == undefined)
        sessionStorage.removeItem('groupNotifyMsg', 0);
    else
        sessionStorage.setItem('groupNotifyMsg', JSON.stringify(data));
}

function getCouponStatus() {
    return parseInt((localStorage.getItem('coupons') == undefined) ? 0 : parseInt(localStorage.getItem('coupons')))
}

function setCouponStatus(num) {
    if (parseInt(num) == 0)
        localStorage.setItem('coupons', 0);
    else
        localStorage.setItem('coupons', num)
}

function getMySessionWallet() {
    return parseFloat((sessionStorage.getItem('walletData') == undefined) ? 0 : parseFloat(sessionStorage.getItem('walletData')))
}

function setMySessionWallet(wallet) {
    if (parseFloat(wallet) == 0)
        sessionStorage.removeItem('walletData');
    else
        sessionStorage.setItem('walletData', wallet)
}

function getRegisterStatus() {
    return ((localStorage.getItem('isRegistered') == undefined) ? false : true)
}

function setRegisterStatus(status) {
    if (status == false)
        localStorage.removeItem('isRegistered')
    else
        localStorage.setItem('isRegistered', '1')
}

function setFavouriteStatus(index, status, favouriteId) {
    if (status == true)
        localStorage.setItem('favour' + index, ((favouriteId == undefined) ? '1' : favouriteId));
    else
        localStorage.removeItem('favour' + index)
}

function getFavouriteStatus(index) {
    return ((localStorage.getItem('favour' + index) == undefined) ? false : (localStorage.getItem('favour' + index)))
}

function setProviderFavouriteStatus(index, status, favouriteId) {
    if (status == true)
        localStorage.setItem('favourProvider' + index, ((favouriteId == undefined) ? '1' : favouriteId));
    else
        localStorage.removeItem('favourProvider' + index)
}

function getProviderFavouriteStatus(index) {
    return ((localStorage.getItem('favourProvider' + index) == undefined) ? false : (localStorage.getItem('favourProvider' + index)))
}

function setCurProviderDetailInfo(info) {
    sessionStorage.setItem('cur_Provider', JSON.stringify(info))
}

function getCurProviderDetailInfo() {
    return JSON.parse((sessionStorage.getItem('cur_Provider') == undefined) ? '[]' : (sessionStorage.getItem('cur_Provider')))
}

function setCurActivityDetailInfo(info) {
    sessionStorage.setItem('cur_Activity', JSON.stringify(info))
}

function getCurActivityDetailInfo() {
    return JSON.parse((sessionStorage.getItem('cur_Activity') == undefined) ? '[]' : (sessionStorage.getItem('cur_Activity')))
}

function addSessionOnlinePayOrderInfo(id, payInfo, amount, orderId) {
    if (id == undefined) {
        sessionStorage.removeItem('myOnlinePayOrderInfo')
        return;
    }
    var myPayOrderInfo = JSON.parse((sessionStorage.getItem('myOnlinePayOrderInfo') != undefined) ? (sessionStorage.getItem('myOnlinePayOrderInfo')) : '[]')
    if (id == 0) return myPayOrderInfo;
    var isExist = false;
    var isAllOrdered = true;
    for (var i = 0; i < myPayOrderInfo.length; i++) {
        var item = myPayOrderInfo[i]
        if (id == item.id) {
            if (orderId == '') {
                isAllOrdered = false;
                myPayOrderInfo[i].payInfo = payInfo
                myPayOrderInfo[i].amount = amount
            }
            myPayOrderInfo[i].orderId = orderId;
            isExist = true
        }
    }
    if (!isExist) {
        var orderItem = {id: id, payInfo: payInfo, amount: amount, orderId: ''}
        myPayOrderInfo.push(orderItem)
        isAllOrdered = false;
    }
    sessionStorage.setItem('myOnlinePayOrderInfo', JSON.stringify(myPayOrderInfo))
    return isAllOrdered;
}

function addSessionWalletPayOrderInfo(id, payInfo, amount, orderId) {
    if (id == undefined) {
        sessionStorage.removeItem('myWalletPayOrderInfo')
        return;
    }
    var myPayOrderInfo = JSON.parse((sessionStorage.getItem('myWalletPayOrderInfo') != undefined) ? (sessionStorage.getItem('myWalletPayOrderInfo')) : '[]')
    if (id == 0) return myPayOrderInfo;
    var isExist = false;
    var isAllOrdered = true;
    for (var i = 0; i < myPayOrderInfo.length; i++) {
        var item = myPayOrderInfo[i]
        if (id == item.id) {
            if (orderId == '') {
                isAllOrdered = false;
                myPayOrderInfo[i].payInfo = payInfo
                myPayOrderInfo[i].amount = amount
            }
            myPayOrderInfo[i].orderId = orderId
            isExist = true
        }
    }
    if (!isExist) {
        var orderItem = {id: id, payInfo: payInfo, amount: amount, orderId: ''}
        myPayOrderInfo.push(orderItem)
        isAllOrdered = false;
    }
    sessionStorage.setItem('myWalletPayOrderInfo', JSON.stringify(myPayOrderInfo))
    return isAllOrdered;
}

function addToSessionCart(index, amount, max_amount) {
    if (localStorage.getItem('myCart') == '') localStorage.removeItem('myCart');
    var myCart = JSON.parse((localStorage.getItem('myCart') == undefined) ? '[]' : localStorage.getItem('myCart'))
    if (index == 0) {
        return myCart;
    } else if (index == undefined) {
        localStorage.removeItem('myCart');
        return [];
    }
    max_amount = ((max_amount == undefined) ? amount : max_amount)
    //var productDatas = JSON.parse((sessionStorage.getItem('productDatas') == undefined) ? '[]' : sessionStorage.getItem('productDatas'))
    var activityData = JSON.parse((sessionStorage.getItem('cur_Activity') == undefined) ? '[]' : sessionStorage.getItem('cur_Activity'))
    var isExist = false;
    for (var i = 0; i < myCart.length; i++) {
        if (myCart[i].id == index) {
            myCart[i]['amount'] = max_amount;
            myCart[i]['cur_amount'] = amount;
            myCart[i]['cart_include_status'] = 0;
            isExist = true;
            break;
        }
    }
    if (!isExist) {
        activityData['amount'] = max_amount;
        activityData['cur_amount'] = amount;
        activityData['cart_include_status'] = 0;
        myCart.push(activityData)

        // for (var j = 0; j < activityData.length; j++) {
        //    if (activityDatas[j].id == index) {
        //         activityDatas[j]['amount'] = max_amount
        //         activityDatas[j]['cur_amount'] = amount
        //         activityDatas[j]['cart_include_status'] = 0;
        //         myCart.push(activityDatas[j])
        //         break;
        //     }
        // }
    }
    localStorage.setItem('myCart', JSON.stringify(myCart));
    return JSON.parse(localStorage.getItem('myCart'));
}

function removeFromSessionCart(index) {
    var myCart = addToSessionCart(0)
    var isExist = false;
    var newCart = [];
    for (var i = 0; i < myCart.length; i++) {
        if (parseInt(myCart[i].id) == index) {
            isExist = true;
            continue;
        }
        newCart.push(myCart[i])
    }
    localStorage.setItem('myCart', JSON.stringify(newCart))
}

function showCartStatus() {
    var tmp = addToSessionCart(0).length
    tmp = (tmp == 0) ? '' : tmp
    $('#cart_amount').html(tmp)
}

function setCartItemStatus(statusDatas) {
    var myCart = addToSessionCart(0)
    for (var i = 0; i < myCart.length; i++) {
        for (var j = 0; j < statusDatas.length; j++) {
            if (myCart[i].id == statusDatas[j].id) {
                myCart[i].grouping_status = statusDatas[j].status;
                break;
            }
        }
    }
    localStorage.setItem('myCart', JSON.stringify(myCart))
}

function addToSessionOrder(index, amount, typeFrom) {
    var myCurOrder = JSON.parse((sessionStorage.getItem('myCurOrder') == undefined) ? '[]' : sessionStorage.getItem('myCurOrder'))
    if (index == 0) return myCurOrder
    var productDatas = [];
    if (typeFrom == 1)// from product detail
        productDatas = [JSON.parse((sessionStorage.getItem('cur_Activity') == undefined) ? '[]' : sessionStorage.getItem('cur_Activity'))]
    else // from order detail
        productDatas = JSON.parse((sessionStorage.getItem('orderDatas') == undefined) ? '[]' : sessionStorage.getItem('orderDatas'))

    myCurOrder = [];
    for (var j = 0; j < productDatas.length; j++) {
        if (productDatas[j].id == index) {
            productDatas[j].cur_amount = amount;
            myCurOrder.push(productDatas[j])
            break;
        }
    }

    if (typeFrom == 2) {
        var orderData = myCurOrder[0];
        var prods = orderData.products;
        var new_price = 0;
        var old_price = 0;
        for (var i = 0; i < prods.length; i++) {
            new_price += parseFloat(prods[i].new_price) * parseInt(prods[i].amount);
            old_price += parseFloat(prods[i].old_price) * parseInt(prods[i].amount);
        }
        myCurOrder = [];
        myCurOrder.push({
            id: orderData.id,
            product_image: orderData.logo,
            product_name: orderData.name,
            provider_name: orderData.provider_name,
            provider_address: orderData.provider_address,
            provider_contact_name: orderData.provider_contact_name,
            provider_contact_phone: orderData.provider_contact_phone,
            grouping_status: orderData.grouping_status,
            amount: orderData.amount,
            cur_amount: orderData.amount,
            total_info: orderData.products,
            old_price: old_price,
            new_price: new_price,
        });
    }
    sessionStorage.setItem('myCurOrder', JSON.stringify(myCurOrder));
    return JSON.parse(sessionStorage.getItem('myCurOrder'));
}

// display menu items on the horizontal menu bar
function decreaseAmount(id) {
    var curVal = 1;
    var minVal = 1;
    var maxVal = 100000;
    if ($('#product_amount' + id).val() != '')
        curVal = parseInt($('#product_amount' + id).val());
    if ($('#min_amount' + id).val() != '')
        minVal = parseInt($('#min_amount' + id).val());
    if ($('#max_amount' + id).val() != '')
        maxVal = parseInt($('#max_amount' + id).val());

    if (curVal > minVal) $('#product_amount' + id).val(curVal - 1);
    else $('#product_amount' + id).val(curVal);
}

// display menu items on the horizontal menu bar
function increaseAmount(id) {
    var curVal = 1;
    var minVal = 1;
    var maxVal = 100000;
    if ($('#product_amount' + id).val() != '')
        curVal = parseInt($('#product_amount' + id).val());
    if ($('#min_amount' + id).val() != '')
        minVal = parseInt($('#min_amount' + id).val());
    if ($('#max_amount' + id).val() != '')
        maxVal = parseInt($('#max_amount' + id).val());

    if (curVal < maxVal) $('#product_amount' + id).val(curVal + 1);
    else $('#product_amount' + id).val(curVal);
}

// display menu items on the horizontal menu bar
function validateAmount(id) {
    var curVal = 1;
    var minVal = 1;
    var maxVal = 100000;
    if ($('#product_amount' + id).val() != '')
        curVal = parseInt($('#product_amount' + id).val());
    if ($('#min_amount' + id).val() != '')
        minVal = parseInt($('#min_amount' + id).val());
    if ($('#max_amount' + id).val() != '')
        maxVal = parseInt($('#max_amount' + id).val());

    if (curVal < minVal)
        $('#product_amount' + id).val(minVal);
    else if (curVal > maxVal)
        $('#product_amount' + id).val(curVal.toString().substr(0, curVal.toString().length - 1));
    else
        $('#product_amount' + id).val(curVal);
}

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
        sessionStorage.setItem('cur_bottom_index', index);
        data.cur_bottom_index = index;
        switch (index) {
            case 1:
                if (document.title == '惠联彩') break;
                setTimeout(function () {
                    location.href = "home.php";
                }, 5);
                break;
            case 2:
                if (document.title == '购物车') break;
                setTimeout(function () {
                    location.href = "mycart_manage.php";
                }, 5);
                break;
            case 3:
                if (document.title == '消息') break;
                setTimeout(function () {
                    location.href = "main_news.php";
                }, 5);
                break;
            case 4:
                if (document.title == '个人中心') break;
                setTimeout(function () {
                    location.href = "myfunction_manage.php";
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
    $('#message_dialog .modal-body').html('<br><b><center>' + message + '</center></b><br>');
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

function showAuthRequire(message, btn_ok, btn_cancel, isShowbtn) {
    if (!getRegisterStatus()) {
        message = "您还未进行登录!";
        btn_ok = "立即登录";
        btn_cancel = '取消';
        setAuthorizationStatus(false);
    }
    if (getAuthorizationStatus()) {
        return;
    } else if (getAuthRequestStatus()) {
        if (getSessionMyInfo().status != '4') {
            if (document.title == '个人中心' && sessionStorage.getItem('msgShowed') == undefined) {
                message = '您已提交认证申请,<br>请等待审核通过。';
                btn_ok = '确定';
                sessionStorage.setItem('msgShowed', 1);
            } else {
                return;
            }
        } else {
            if (document.title == '个人中心' && sessionStorage.getItem('msgShowed') == undefined) {
                message = '您的便利店认证申请未能通过审核,<br>请返回认证页面重新提报真实、有效资料。';
                btn_ok = '确定';
                sessionStorage.setItem('msgShowed', 1);
            } else {
                if (document.title != '终端便利店认证')
                    return;
            }
        }
    }
    message = (message == undefined) ? '您还未进行认证!' : message;
    btn_ok = (btn_ok == undefined) ? '立即认证' : btn_ok;
    btn_cancel = (btn_cancel == undefined) ? '取消' : btn_cancel;
    isShowbtn = (isShowbtn == undefined) ? true : false;
    var pref = '<br>';
    if (message.indexOf('br') == -1) pref = '<br>';
    $('#auth_question .modal-body').html(pref + '<center><b>' + message + '</b></center>');
    $('#auth_ok').html(btn_ok);
    $('#auth_cancel').html(btn_cancel);
    $('#auth_question').modal();
    if (!isShowbtn) {
        $('#auth_question .modal-footer').hide();
        setTimeout(function () {
            $('#auth_question').modal('hide');
        }, 3000);
    } else {
        $('#auth_question .modal-footer').show();
        // if (getSessionMyInfo().status == '4') {
        //     $('#auth_cancel').hide();
        //     $('#auth_ok').attr('onclick', "OnOk()");
        // } else
        if (getAuthRequestStatus() && sessionStorage.getItem('msgShowed') != undefined) {
            $('#auth_cancel').hide();
            $('#auth_ok').attr('onclick', "$('#auth_question').modal('hide')");

        } else {
            $('#auth_cancel').show();
            $('#auth_ok').attr('onclick', "OnOk();");
        }
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

function showNotification(message, id) {
    $('#notification_bar').hide();
    var msg = '';
    // msg += '<h5 class="">';
    msg += message;
    msg += '&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right" style="float:right"></i>';
    $('#notification_bar').html(msg);
    $('#notification_bar').attr("onclick", "showProductDetailInfo('" + id + "')");
    $('#notification_bar').show();
    clearTimeout(app_data.notifyTimer);
    app_data.notifyTimer = setTimeout(function () {
        $('#notification_bar').hide();
    }, 6900);
}

function showNotifyAlert(message, type, bottom_position) {
    if (message == '') return;
    if (type == undefined) type = 0;
    // if ((DetectIOSDevice() == 'ipad' || DetectIOSDevice() == 'iphone')){
    // var height = document.body.clientHeight
    //        || document.documentElement.clientHeight
    //             || window.innerHeight;
    //      bottom_position = height - 290;
    // }
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
    // $('#notification_alert_bar').css({
    //    'top': bottom_position,
    //     'bottom': bottom_position
    // });
    $('#notification_alert_bar').show();
    clearTimeout(app_data.notifyTimer);
    app_data.notifyTimer = setTimeout(function () {
        $('#notification_alert_bar').hide();
    }, 6900);
    console.log(app_data.notifyTimer);
}

function showNotifyDebug(message, type) {
    if (HLC_DEBUG_MODE == HLC_REAL_MODE) return;
    if (type == undefined) type = 0;
    $('#notification_alert_bar').html(message);
    if (type == 0) {
        $('#notification_alert_bar').css({
            'background-color': 'rgba(255, 255, 255, 0.7)',
            'color': 'red',
            'border-color': 'red'
        })
    } else {
        $('#notification_alert_bar').css({
            'background-color': 'rgba(30, 30, 30, 0.7)',
            'color': 'white',
            'border-color': 'white'
        })
    }
    $('#notification_alert_bar').show();
    setTimeout(function () {
        $('#notification_alert_bar').hide();
    }, 6900);
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
    $('#perform_order').css({'background': '#38abff'});
    $('#perform_order').attr('onclick', 'orderFromDetail();')

}

function scrollEndDetection(divTag) {
    //alert(($(divTag).scrollTop() + $(divTag).innerHeight())+","+$(divTag)[0].scrollHeight);
    if ((parseInt($(divTag).scrollTop()) + parseInt($(divTag).innerHeight())) >= (parseInt($(divTag)[0].scrollHeight) - 2)) {
        return true;
    }
    return false;
};

// generate simulation datas for menu bar
function simulat_menu_infos() {
    display_menu_infos();
}

// generate advertise image list for the advertise part
function simulate_advertise_images() {
    display_advertise_images();
}

// display the product list on the content
function showProductDetailInfo(index, showType) {
    // showType == 1-from fav activity, 2-from fav provider, 5-from main
    showType = ((showType == undefined) ? 5 : showType)
    location.href = 'product_detail.php?iId=\'' + index + '\'&iType=' + showType;
}

function showOrderDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    addSessionOnlinePayOrderInfo();
    //sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "order_detail.php?iId='" + orderId + "'";
}

function showProviderDetailInfo(providerId) {
    data.cur_detail_index = providerId;
    //sessionStorage.setItem('cur_detail_index', providerId);
    location.href = "provider_detail.php?iId='" + providerId + "'";
}

function showGroupingDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    //sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "grouping_detail.php?iId=" + orderId;
}

////////////////////--------- 线上支付 -------------------//////////////////////
function preparePayment() {

    var walletIDlist = [];
    var cntList = [];
    var noteList = [];

    var walletPayList = addSessionWalletPayOrderInfo(0);

    for (var i = 0; i < walletPayList.length; i++) {
        walletIDlist.push(walletPayList[i].id);
        cntList.push(walletPayList[i].amount);
        noteList.push(walletPayList[i].payInfo.order_note);
    }

    walletPayment(walletIDlist, cntList, noteList);
}

function walletPayment(walletIDList, cntList, noteList) {

    if (walletIDList.length == 0) {
        onlinePayment(0);               // wallet paid status ( 0: unpaid, 1 : paid)
        return;
    }

    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/orderRequests', //rest API url
        dataType: 'json',
        data: { // set function name and parameters
            'phone': getPhoneNumber(),
            'activity': JSON.stringify(walletIDList),
            'count': JSON.stringify(cntList),
            'note': noteList[0],
            'pay_method': 1
        },
        success: function (data) {

            var walletPayList = addSessionWalletPayOrderInfo(0);
            if (walletPayList.length != 0) {
                for (var i = 0; i < walletPayList.length; i++) {
                    removeFromSessionCart(walletPayList[i].id);
                }
//                sendRemoveMyCartItemRequest(0);
            }

            if (data.status == true) {
                    walletPayOrder(data.data);

            } else {
                showNotifyAlert('订单失败。')
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error);
        }
    });
}

function walletPayOrder(orderIDlist) {
    var orderList = [];
    var couponList = [];
    var walletList = [];
    var moneyList = [];
    var noteList = [];

    var walletOrderList = addSessionWalletPayOrderInfo(0);
    for (var i = 0; i < orderIDlist.length; i++) {

        if (orderIDlist[i] == '') continue;

        orderList.push(orderIDlist[i]);
        couponList.push(walletOrderList[i].payInfo.coupon);
        walletList.push(walletOrderList[i].payInfo.wallet);
        moneyList.push(walletOrderList[i].payInfo.price);
        noteList.push(walletOrderList[i].payInfo.order_note);
    }

    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/payOrderRequests', //rest API url
        dataType: 'json',
        data: { // set function name and parameters
            'phone': getPhoneNumber(),
            'order': JSON.stringify(orderList),
            'coupon': JSON.stringify(couponList),
            'wallet': JSON.stringify(walletList),
            'money': JSON.stringify(moneyList),
            'note': noteList[0]
        },
        success: function (data) {
            if (data.status == true) {

                var site_pay = {
                    'cost': 0,
                    'product_name': orderList[0],
                    'transaction_time': moment(new Date).format('YYYY-MM-DD HH:mm:ss'),
                    'pay_method': '零钱',
                    'transaction_id': data.data
                };

                for (var i = 0; i < orderList.length; i++) {
                    site_pay['cost'] += parseFloat(walletList[i]);// + parseFloat(couponList[i]);
                }

                sessionStorage.setObject('site_pay', site_pay);
                // delete wallet information

                // -------------------------
                onlinePayment(10);
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error);
        }
    });
}

function onlinePayment(status) {
    // 0-wallet payment is invalid, 10-wallet payment successed
    // 15-weixin payment is invalid, 20-weixin payment successed
    switch (parseInt(status)) {
        case 0: // wallet payment is failed
            onlineOrderRequest();
            break;
        case 10: // wallet payment success.
            var walletPayList = addSessionWalletPayOrderInfo(0);
            if (walletPayList.length == 0) break;
            for (var i = 0; i < walletPayList.length; i++) {
                removeFromSessionCart(walletPayList[i].id);
            }
            addSessionWalletPayOrderInfo();

            window.location.href = 'my_success.php?iType=10';
            break;
        case 15: // weixin payment failed
            showNotifyAlert('微信付款失败。');
            break;
        case 20: // weixin payment success
            var onlinePayList = addSessionOnlinePayOrderInfo(0);
            if (onlinePayList.length == 0) break;
            for (var i = 0; i < onlinePayList.length; i++) {
                removeFromSessionCart(onlinePayList[i].id);
            }
            addSessionOnlinePayOrderInfo();

            sessionStorage.removeItem('cur_detail_index');
            sessionStorage.removeItem('cur_menu_index');
            location.href = "order_manage.php";
            //window.location.href = 'my_success.php?iType=20';
            break;
        case 25: // after payment success
            var onlinePayList = addSessionOnlinePayOrderInfo(0);

            addSessionOnlinePayOrderInfo();
            window.location.href = 'my_success.php?iType=25';
            break;
        case 30: // after payment success
            var walletPayList = addSessionWalletPayOrderInfo(0);
            if (walletPayList.length != 0) {
                for (var i = 0; i < walletPayList.length; i++) {
                    removeFromSessionCart(walletPayList[i].id);
                }
                addSessionWalletPayOrderInfo();
            }
            var onlinePayList = addSessionOnlinePayOrderInfo(0);
            if (onlinePayList.length != 0) {
                for (var i = 0; i < onlinePayList.length; i++) {
                    removeFromSessionCart(onlinePayList[i].id);
                }
                addSessionOnlinePayOrderInfo();
            }
            sessionStorage.removeItem('cartPayIds');
            window.location.href = 'my_success.php?iType=25';
            break;
        default:
            break;
    }
}

function onlineOrderRequest() {
    var onlineIDList = [];
    var cntList = [];
    var noteList = [];
    var onlinePayData = addSessionOnlinePayOrderInfo(0);

    for (var i = 0; i < onlinePayData.length; i++) {
        onlineIDList.push(onlinePayData[i].id);
        cntList.push(onlinePayData[i].amount);
        noteList.push(onlinePayData[i].payInfo.order_note);
    }

    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/orderRequests', //rest API url
        dataType: 'json',
        data: { // set function name and parameters
            'phone': getPhoneNumber(),
            'activity': JSON.stringify(onlineIDList),
            'count': JSON.stringify(cntList),
            'note': noteList[0],
            'pay_method': 1
        },
        success: function (data) {
            var onlinePayList = addSessionOnlinePayOrderInfo(0);
            if (onlinePayList.length != 0) {
                for (var i = 0; i < onlinePayList.length; i++) {
                    removeFromSessionCart(onlinePayList[i].id);
                }
                //sendRemoveMyCartItemRequest(0);
            }
            if (data.status == true) {
                app_data['orderIDList'] = data.data;
                weixin_payment(data.data);
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error);
        }
    });
}

function onlinePayOrderRequest() {   //// return from weixin payment.php
    var orderIDlist = sessionStorage.getObject('orderIDList');

    var orderList = [];
    var couponList = [];
    var walletList = [];
    var moneyList = [];
    var noteList = [];

    var onlineOrderList = addSessionOnlinePayOrderInfo(0);
    for (var i = 0; i < orderIDlist.length; i++) {

        if (orderIDlist[i] == '') continue;

        orderList.push(orderIDlist[i]);
        couponList.push(onlineOrderList[i].payInfo.coupon);
        walletList.push(onlineOrderList[i].payInfo.wallet);
        moneyList.push(onlineOrderList[i].payInfo.price);
        noteList.push(onlineOrderList[i].payInfo.order_note);
    }

    if (orderList.length == 0) { // if (listcount successed) is 0 - online payment is rejected
        onlinePayment(15);
        return;
    }
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/payOrderRequests', //rest API url
        dataType: 'json',
        data: { // set function name and parameters
            'phone': getPhoneNumber(),
            'order': JSON.stringify(orderList),
            'coupon': JSON.stringify(couponList),
            'wallet': JSON.stringify(walletList),
            'money': JSON.stringify(moneyList),
            'note': noteList[0]
        },
        success: function (data) {
            if (data.status == true) {

                var site_pay = {
                    'cost': 0,
                    'product_name': orderList[0],
                    'transaction_time': moment(new Date).format('YYYY-MM-DD HH:mm:ss'),
                    'pay_method': '线上',
                    'transaction_id': data.data
                };

                for (var i = 0; i < orderList.length; i++) {
                    site_pay['cost'] += parseFloat(moneyList[i]);
                }
                sessionStorage.setObject('site_pay', site_pay);
                onlinePayment(20);
            }
        },
        error: function (data) {
            showNotifyAlert(LANG_DATA.server_error);
        }
    });
}

function weixin_payment(orderIDList) {
    var cost = 0;
    var onlinePayList = addSessionOnlinePayOrderInfo(0);
    for (var i = 0; i < onlinePayList.length; i++) {
        if (orderIDList[i] == '') continue;

        cost += parseFloat(onlinePayList[i].payInfo.price);
    }

    if (cost == 0) {
        showNotifyAlert('订单失败。');
        return;
    }
    sessionStorage.setObject('orderIDList', orderIDList);
    if (HLC_PAY_MODE == HLC_SIMUL_MODE) {
        onlinePayOrderRequest();
    } else {
        setTimeout(function () {
            sessionStorage.setItem('isPaying','1');
            window.location.href = MY_API_URL + 'payment.php?cost=' + cost + "&";
        }, 1000);
    }
}


// This is the part that store and load the object in localStorage
Storage.prototype.setObject = function (key, value) {
    this.setItem(key, JSON.stringify(value));
}
Storage.prototype.getObject = function (key) {
    var val = this.getItem(key);
    if (val == "" || val == undefined) return [];
    return JSON.parse(val);
}


