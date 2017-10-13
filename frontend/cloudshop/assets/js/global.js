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

function prepareRealPayment(price, orderId, itemTxt) {
    phone_num = getPhoneNumber()

    location.href = 'payment.php' +
        '?cost=' + price +
        '&type=' + orderId +
        '&product=' + itemTxt;
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

function getAuthorizationStatus() {
    return ((localStorage.getItem('isAuthorized') == undefined) ? false : true)
}

function setAuthorizationStatus(status) {
    if (status == false)
        localStorage.removeItem('isAuthorized');
    else
        localStorage.setItem('isAuthorized', '1')
}

function getCouponStatus() {
    return parseInt((sessionStorage.getItem('coupons') == undefined) ? 0 : parseInt(sessionStorage.getItem('coupons')))
}

function setCouponStatus(num) {
    if (parseInt(num) == 0)
        sessionStorage.setItem('coupons', 0);
    else
        sessionStorage.setItem('coupons', num)
}

function getMySessionWallet() {
    return parseFloat((sessionStorage.getItem('walletData') == undefined) ? 0 : parseInt(sessionStorage.getItem('walletData')))
}

function setMySessionWallet(wallet) {
    if (parseFloat(wallet) == 0)
        sessionStorage.removeItem('walletData');
    else
        sessionStorage.setItem('walletData', wallet)
}

function getRegisterStatus() {
    return ((sessionStorage.getItem('isRegistered') == undefined) ? false : true)
}

function setRegisterStatus(status) {
    if (status == false)
        sessionStorage.removeItem('isRegistered')
    else
        sessionStorage.setItem('isRegistered', '1')
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

function addSessionPayOrderInfo(id, payInfo, amount, orderId) {
    if (id == undefined) {
        sessionStorage.removeItem('myPayOrderInfo')
        return;
    }
    var myPayOrderInfo = JSON.parse((sessionStorage.getItem('myPayOrderInfo') != undefined) ? (sessionStorage.getItem('myPayOrderInfo')) : '[]')
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
    sessionStorage.setItem('myPayOrderInfo', JSON.stringify(myPayOrderInfo))
    return isAllOrdered;
}

function addToSessionCart(index, amount, max_amount) {
    var myCart = JSON.parse((localStorage.getItem('myCart') == undefined) ? '[]' : localStorage.getItem('myCart'))
    if (index == 0) return myCart
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
        activityData['amount'] = max_amount
        activityData['cur_amount'] = amount
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
            continue
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

function addToSessionOrder(index, amount) {
    var myCurOrder = JSON.parse((sessionStorage.getItem('myCurOrder') == undefined) ? '[]' : sessionStorage.getItem('myCurOrder'))
    if (index == 0) return myCurOrder

    var productDatas = JSON.parse((sessionStorage.getItem('productDatas') == undefined) ? '[]' : sessionStorage.getItem('productDatas'))
    myCurOrder = [];
    for (var j = 0; j < productDatas.length; j++) {
        if (parseInt(productDatas[j].id) == index) {
            productDatas[j].cur_amount = amount;
            myCurOrder.push(productDatas[j])
            break;
        }
    }
    sessionStorage.setItem('myCurOrder', JSON.stringify(myCurOrder));
    return JSON.parse(sessionStorage.getItem('myCurOrder'));
}

// display menu items on the horizontal menu bar
function decreaseAmount(id) {
    var curVal = parseInt($('#product_amount' + id).val());
    var minVal = parseInt($('#min_amount' + id).val());
    if ($('#min_amount' + id).val() == undefined) minVal = 1;
    if (curVal > minVal) $('#product_amount' + id).val(curVal - 1);
    else $('#product_amount' + id).val(curVal);
}

// display menu items on the horizontal menu bar
function increaseAmount(id) {
    var curVal = parseInt($('#product_amount' + id).val());
    var maxVal = parseInt($('#max_amount' + id).val());
    if ($('#max_amount' + id).val() == undefined) maxVal = 100000;
    if (curVal < maxVal) $('#product_amount' + id).val(curVal + 1);
    else $('#product_amount' + id).val(curVal);
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
    isShowbtn = (isShowbtn == undefined) ? false : true;
    message = (isShowbtn == false) ? message = '<br>' + message : message;
    $('#message_dialog .modal-body').html('<br><b><center>' + message + '</center></b><br>');
    $('#message_dialog').modal();
    if (!isShowbtn) {
        $('#message_dialog .modal-footer').css({'display': 'none'});
        setTimeout(function () {
            $('#message_dialog').modal('hide');
        }, 3000);
    } else {
        $('#message_dialog .modal-footer').css({'display': 'block'});
    }

    showModalToCenter('message_dialog');
}

function showAuthRequire(message, btn_ok, btn_cancel, isShowbtn) {
    if (getAuthorizationStatus()) {
        return;
    }
    btn_ok = (btn_ok == undefined) ? '确认' : btn_ok;
    btn_cancel = (btn_cancel == undefined) ? '取消' : btn_cancel;
    isShowbtn = (isShowbtn == undefined) ? true : false;
    $('#auth_question .modal-body').html('<br><center><b>' + message + '</b></center>');
    $('#auth_ok').html(btn_ok);
    $('#auth_cancel').html(btn_cancel);
    $('#auth_question').modal();
    if (!isShowbtn) {
        $('#auth_question .modal-footer').css({'display': 'none'});
        setTimeout(function () {
            $('#auth_question').modal('hide');
        }, 3000);
    } else {
        $('#auth_question .modal-footer').css({'display': 'block'});
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

function validateText() {
    var txt = $('#textarea').val();
    var txtLen = parseInt($('#textLength').html().split('/')[1]);
    if (txt.length > txtLen) {
        txt = txt.substring(0, txtLen);
        $('#textarea').val(txt);
    }
    $('#textLength').html(txt.length + '/' + txtLen);

}

var scrollEndDetection = function (divTag) {
    if ($(divTag).scrollTop() + $(divTag).innerHeight() >= $(divTag)[0].scrollHeight) {
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
    location.href = 'product_detail.php?iId=' + index + '&iType=' + showType;
}

function showOrderDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    //sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "order_detail.php?iId='" + orderId + "'";
}

function showProviderDetailInfo(providerId) {
    data.cur_detail_index = providerId;
    //sessionStorage.setItem('cur_detail_index', providerId);
    location.href = "provider_detail.php?iId=" + providerId;
}

function showGroupingDetailInfo(orderId) {
    data.cur_detail_index = orderId;
    //sessionStorage.setItem('cur_detail_index', orderId);
    location.href = "grouping_detail.php?iId=" + orderId;
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