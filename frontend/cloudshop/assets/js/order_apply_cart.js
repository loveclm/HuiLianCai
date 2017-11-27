var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0,
    'check_imgs': [
        'assets/images/address@3x.png',
        'assets/images/goods@3x.png',
        'assets/images/choose_s_d@3x.png',
        'assets/images/choose_s_n@3x.png',
    ]
};

$(document).ready(function () {
    document.title = '订单详情';
    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();

    selectMenu(sessionStorage.getItem('cur_menu_index'));
    resize_main_page();
});

$(window).resize(function () {
    simulate_advertise_images();
    simulat_menu_infos();

    resize_main_page();
    loadDatafromStorage();
});

function showContents() {

}

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = sessionStorage.getItem('phone_num');
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
    data.curOrder = addToSessionCart(0);

}

function initializeData() {
    if (sessionStorage.getItem('cur_menu_index') === null) {
        sessionStorage.setItem('cur_menu_index', 0);
    }
    if (sessionStorage.getItem('cur_detail_index') === null) {
        sessionStorage.setItem('cur_detail_index', 0);
    }

}

// display advertise images on the slider
function display_advertise_images() {
}

// display menu items on the horizontal menu bar
function display_menu_infos() {

}

// shows the detail menu items of any menu
function selectMenu(index) {
    if (data.bShow_detal_menu == 1 && data.cur_menu_index == index) return;
    if (data.cur_menu_index != index) data.cur_detail_index = -1;

    var content_html = "";
    if (data.menu_info == undefined) return;

    // when menu is selected, shows selected status along the design
    $('#menuItem' + data.cur_menu_index).css({'border': 'none', 'color': 'black'});
    data.cur_menu_index = index;
    sessionStorage.setItem('cur_menu_index', index);
    $('#menuItem' + data.cur_menu_index).css({'color': '#38abff', 'border-bottom': '3px solid'});

    // shows the detail menu informations with popup format
    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    height -= parseInt($('#horizontal_order_menu_bar').css('height'));

    data.bShow_detal_menu = 1;
    display_product_infos(index);
}

// display the product list on the content
function display_product_infos(index) {
    var cur_product = addToSessionCart(0)
    var product_html = "";
    console.log(cur_product.length)
    var type = 2;
    if (pageItemType == 2) type = 1
    if (cur_product.length != 0) {
        product_html += myCartOrderApplyTemplate(cur_product);
    }
    if (product_html == '') product_html = noItemsTemplate(1);
    $("#product_container").html(product_html);
    if (cur_product.length != 0) {
        $('input[name=paytype]')[0].checked = true;
        selectPayType();
    }
    calculatePrices();
}


function selectDetailMenu(index) {
    if (data.cur_menu_index == index) {
        hideDetailMenu();
        return;
    }

    $('#detail_menu_item' + data.cur_detail_index).css({color: 'black'});
    data.cur_menu_index = index;
    $('#detail_menu_item' + data.cur_detail_index).css({color: '#38abff'});

    hideDetailMenu();

    //loading data from menu index and detail menu index
}

function selectPayType() {
    var status = parseInt($('input[name=paytype]:checked').val());
    $('#paytype' + (status)).removeClass('fa-circle-o');
    $('#paytype' + (status)).addClass('fa-dot-circle-o');

    $('#paytype' + (1 - status)).removeClass('fa-dot-circle-o');
    $('#paytype' + (1 - status)).addClass('fa-circle-o');
    //
    // if (status == 0) $('.order.detail.bottom').show();
    // else $('.order.detail.bottom').hide();
    calculatePrices()
}

function selectItemCheck(id) {
    if (id == '000') { // select all
        if ($('#itemCheck000').attr('src') == data.check_imgs[3]) {
            $('#itemCheck000').attr('src', data.check_imgs[2])
            $('.check_icon').attr('src', data.check_imgs[0])
        } else {
            $('#itemCheck000').attr('src', data.check_imgs[3])
            $('.check_icon').attr('src', data.check_imgs[1])
        }
    } else if (id == '001') { //select coupon
        if ($('#itemCheck001').attr('src') == data.check_imgs[3]) { // coupon selected
            $('#itemCheck001').attr('src', data.check_imgs[2])
            sessionStorage.setItem('coupon_select_status', '1')
        } else {
            $('#itemCheck001').attr('src', data.check_imgs[3])
            sessionStorage.removeItem('coupon_select_status');
        }
    } else if ($('#itemCheck' + id).attr('src') == data.check_imgs[0]) {
        $('#itemCheck' + id).attr('src', data.check_imgs[1])
    } else {
        $('#itemCheck' + id).attr('src', data.check_imgs[0])
    }
    calculatePrices()
}

function calculatePrices() {
    if (sessionStorage.getItem('coupon_select_status') == undefined)
        $('#itemCheck001').attr('src', data.check_imgs[3]);
    else
        $('#itemCheck001').attr('src', data.check_imgs[2]);
    addSessionWalletPayOrderInfo();
    addSessionOnlinePayOrderInfo();
    var wallet = getMySessionWallet();
    var cartPayIds = sessionStorage.getObject('cartPayIds');
    var myCurOrder = [];
    var pay_method = (parseInt($('input[name=paytype]:checked').val())) + 1;
    var total_pay_price = 0;
    for (var i = 0; i < cartPayIds.length; i++) {
        for (var j = 0; j < addToSessionCart(0).length; j++) {
            if (addToSessionCart(0)[j].id == cartPayIds[i]) {
                myCurOrder = addToSessionCart(0)[j];
                break;
            }
        }
        total_pay_price += parseFloat(myCurOrder.old_price) * parseInt(myCurOrder.cur_amount);
    }
    var coupon = (($('#itemCheck001').attr('src') == data.check_imgs[2]) ? 30 : 0);
    console.log('total:' + total_pay_price);
    console.log('coup:' + coupon);
    if (getCouponStatus() == 1 && pay_method == 1 && total_pay_price >= 300) {
        $('.order.detail.bottom').css({'display': 'block'});
    } else {
        coupon = 0;
        $('.order.detail.bottom').css({'display': 'none'});
    }
    console.log(coupon);

    var total_wallet_pay_price = 0;
    var total_diff_pay_price = 0;

    for (var i = 0; i < cartPayIds.length; i++) {
        for (var j = 0; j < addToSessionCart(0).length; j++) {
            if (addToSessionCart(0)[j].id == cartPayIds[i]) {
                myCurOrder = addToSessionCart(0)[j];
                break;
            }
        }
        var price = parseFloat(myCurOrder.old_price) * parseInt(myCurOrder.cur_amount);
        //addSessionOnlinePayOrderInfo();
        var pay_coupon = 0;
        var pay_wallet = 0;
        if (price >= coupon && coupon > 0) {
            price -= coupon;
            pay_coupon = coupon;
            coupon = 0;
        }
        if (price >= wallet && wallet > 0) {
            price -= wallet;
            pay_wallet = wallet;
            wallet = 0;
        } else if (price < wallet && wallet > 0) {
            pay_wallet = price;
            wallet -= price;
            price = 0;
        }
        var payInfo = {price: price, coupon: pay_coupon, wallet: pay_wallet, pay_method: pay_method};

        payInfo['order_note'] = $('#textarea').val();
        if (payInfo.price > 0) {
            addSessionOnlinePayOrderInfo(myCurOrder.id, payInfo, myCurOrder.cur_amount, '')
        } else {
            addSessionWalletPayOrderInfo(myCurOrder.id, payInfo, myCurOrder.cur_amount, '')
        }

    }
    wallet = getMySessionWallet();
    coupon = (($('#itemCheck001').attr('src') == data.check_imgs[2]) ? 30 : 0);
    if (getCouponStatus() == 1 && pay_method == 1 && total_pay_price >= 300) {
        $('.order.detail.bottom').css({'display': 'block'});
    } else {
        coupon = 0;
        $('.order.detail.bottom').css({'display': 'none'});
    }
    total_pay_price = total_pay_price - coupon;
    if (wallet >= total_pay_price) wallet = total_pay_price;
    if (pay_method == 1) { // online pay
        $('#wallet_price').html(getPrice(wallet));
        $('#pay_price').html(getPrice(total_pay_price - wallet));
    } else { // after pay
        $('#wallet_price').html(getPrice(0));
        $('#pay_price').html(getPrice(total_pay_price));
    }
}

function payPrepareFromCart() {
    var pay_method = (parseInt($('input[name=paytype]:checked').val())) + 1;
    if (pay_method == 1) {
        preparePayment();
    } else {

        var IDList = [];
        var cntList = [];
        var noteList = [];

        var walletPayList = addSessionWalletPayOrderInfo(0);
        var onlinePayList = addSessionOnlinePayOrderInfo(0);

        for (var i = 0; i < walletPayList.length; i++) {
            IDList.push(walletPayList[i].id);
            cntList.push(walletPayList[i].amount);
            noteList.push(walletPayList[i].payInfo.order_note);
        }

        for (var i = 0; i < onlinePayList.length; i++) {
            IDList.push(onlinePayList[i].id);
            cntList.push(onlinePayList[i].amount);
            noteList.push(onlinePayList[i].payInfo.order_note);
        }

        if (IDList.length > 0) {
            $.ajax({
                type: 'POST',
                url: REMOTE_API_URL + 'api/orderRequests', //rest API url
                dataType: 'json',
                data: { // set function name and parameters
                    'phone': getPhoneNumber(),
                    'activity': JSON.stringify(IDList),
                    'count': JSON.stringify(cntList),
                    'note': noteList[0],
                    'pay_method': 2
                },
                success: function (data) {
                    if (data.status == true) {

                        onlinePayment(30);

                    } else {
                        showNotifyAlert('订单失败。')
                    }
                },
                error: function (data) {
                    showNotifyAlert(LANG_DATA.server_error);
                }
            });
        }

    }
}

function onlinePayOrder() {

    var orderList = [];
    var couponList = [];
    var walletList = [];
    var moneyList = [];
    var noteList = [];

    var onlineOrderList = addSessionOnlinePayOrderInfo(0);
    for (var i = 0; i < onlineOrderList.length; i++) {

        if (onlineOrderList[i].orderId == '') continue;

        orderList.push(onlineOrderList[i].orderId);
        couponList.push(onlineOrderList[i].payInfo.coupon);
        walletList.push(onlineOrderList[i].payInfo.wallet);
        moneyList.push(onlineOrderList[i].payInfo.price);
        noteList.push(onlineOrderList[i].payInfo.order_note);
    }
    $.ajax({
        type: 'POST',
        url: REMOTE_API_URL + 'api/payOrderRequests', //rest API url
        dataType: 'json',
        data: { // set function name and parameters
            'phone': getPhoneNumber(),
            'order': orderList,
            'coupon': couponList,
            'wallet': walletList,
            'money': moneyList,
            'note': onlineOrderList[0].payInfo.order_note
        },
        success: function (data) {
            if (data.status == true) {

                var site_pay = {
                    'cost': 0,
                    'product_name': orderList[0],
                    'transaction_time': moment(new Date).format('YYYY-MM-DD HH:mm:ss'),
                    'pay_method': '线上支付',
                    'transaction_id': data.data
                };

                site_pay['cost'] += parseFloat(curPayInfo.payInfo.price);// + parseFloat(curPayInfo.payInfo.wallet);// + parseFloat(curPayInfo.payInfo.coupon);

                sessionStorage.setObject('site_pay', site_pay);
                // delete wallet information

                // -------------------------

                sessionStorage.removeItem('cur_detail_index');
                sessionStorage.removeItem('cur_menu_index');
                location.href = "order_manage.php";
                // window.location.href = 'my_success.php?pay_method="weixin"';
            }
        },
        error: function (data) {

        }
    });

}

function orderFromDetail() {
    calculatePrices();
    payPrepareFromCart();
}

function resize_main_page() {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = $('.page-footer').css('height');
    var menu_height = $('#horizontal_menu_bar').css('height');
    var content_height = parseInt(height) - parseInt(footer_height) - parseInt(width) / 1.46;
    var progress_width = parseInt(width) - 200;
    $('.product_container').css({'height': content_height, 'top': parseInt(width) / 1.46});

    $('.carousel_item').css({'height': parseInt(width) / 1.46});
    $('.commodity_progress').css({'width': progress_width});
    $('.commodity_body img').css({'height': $('.commodity_body img').css('width')});
    // $('.order .commodity_detail').css({'width': width - 140, 'margin': 0, 'padding': 0});
}