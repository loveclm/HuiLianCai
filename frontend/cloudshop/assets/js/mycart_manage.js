var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [],
    'check_imgs': [
        'assets/images/choose_b_d@3x.png', //selected-0
        'assets/images/choose_b_n@3x.png', //unselected-1
        'assets/images/choose_s_d@3x.png', //selected-2
        'assets/images/choose_s_n@3x.png', //unselected-3
        'assets/images/failed_notice@2x.png', // failed corner-4
        'assets/images/choose_b_no@2x.png', //blank circle-5
    ],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0,
    'anim_status': 0
};

$(document).ready(function () {
    document.title = '购物车';
    if (!getRegisterStatus()) {
        showAuthRequire();
    }
    if (getAuthorizationStatus()) sendAddMyCartItemRequest(false, 0);
    else showContents();
});

$(window).resize(function () {
    resize_main_page();
});

function showContents() {
    loadDatafromStorage();

    simulate_advertise_images();
    simulat_menu_infos();

    resize_main_page();
    data.cur_bottom_index = 2;
    data.anim_status = 0;
    selectBottomItem(data.cur_bottom_index, 0);
    if (getAuthorizationStatus()) showCartStatus();
    calculatePrices();
    $('button').on('click', function () {
        calculatePrices()
    })
    $('input').on('input', function () {
        calculatePrices()
    })

}

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = getPhoneNumber();
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.cur_bottom_index = parseInt(sessionStorage.getItem('cur_bottom_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
}

function initializeData() {
    if (sessionStorage.getItem('cur_menu_index') === null) {
        sessionStorage.setItem('cur_menu_index', 0);
    }
    if (sessionStorage.getItem('cur_bottom_index') === null) {
        sessionStorage.setItem('cur_bottom_index', 0);
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
    var menu_item_html = "";
    console.log(data.menu_info);
    if (getAuthorizationStatus()) {
        data['myCart'] = addToSessionCart(0)
        menu_item_html += myCartItemTemplate(data.myCart, 0);
    } else {
        menu_item_html += noItemsTemplate(4);
    }
    $('#product_container').html(menu_item_html);

    if (data.myCart == undefined) return;
    for (var i = 0; i < data.myCart.length; i++) {
        var item = data.myCart[i];
        var id = item['id'];
        var startX, startY, moveEndX, moveEndY;
        $('#cartitem_' + id).on("touchstart", function (e) {
            //e.preventDefault();
            var id = this.getAttribute('id').split('_')[1];
            startX = e.originalEvent.changedTouches[0].pageX;
            startY = e.originalEvent.changedTouches[0].pageY;
        });
        $('#cartitem_' + id).on("touchmove", function (e) {
            //e.preventDefault();
            var id = this.getAttribute('id').split('_')[1];
            moveEndX = e.originalEvent.changedTouches[0].pageX;
            moveEndY = e.originalEvent.changedTouches[0].pageY;
            var X = moveEndX - startX;
            var Y = moveEndY - startY;
            if (Math.abs(X) > Math.abs(Y) && X < 0) {  //从右侧向左滑动
                showDeleteItem(id);
            } else if (Math.abs(X) > Math.abs(Y) && X > 0) {
                cancelDeleteItem(id, 10);
            }
        });
    }


}

// shows the detail menu items of any menu
function selectMenu(index) {
    if (data.bShow_detal_menu == 1 && data.cur_menu_index == index) return;
    if (data.cur_menu_index != index) data.cur_detail_index = -1;

    if (data.menu_info.length == 0) return;

    // when menu is selected, shows selected status along the design
    data.cur_menu_index = index;
    sessionStorage.setItem('cur_menu_index', 0);
    location.href = data.menu_info[index - 1]['redirect'];
}

function showDeleteItem(id) {
    if (data.anim_status == 0) {
        $('#failedItem' + id).css({'animation-name': 'item_hide'});
        $('#btn_delete' + id).css({'animation-name': 'btn_show'});
        $('#failedItem' + id).css({'margin-left': '-20%'});
        $('#btn_delete' + id).css({'right': '0'});
        $('#status_notice' + id).hide();
        data.anim_status = 1;
    }
}

function performDeleteItem(id) {
    showMessage('是否删除该商品？', 1)
    data['cur_deleting_index'] = id;
}

function cancelDeleteItem(id, status) {
    if (data.anim_status == 1) {
        $('#failedItem' + id).css({'animation-name': 'item_show'});
        $('#btn_delete' + id).css({'animation-name': 'btn_hide'});
        $('#failedItem' + id).css({'margin-left': '0px'});
        $('#btn_delete' + id).css({'right': '-20%'});
        $('#status_notice' + id).show();
        data.anim_status = 0;
    } else {
        switch (parseInt(status)) {
            case 4: // already sold
                showNotifyAlert('该商品已售完。');
                setTimeout(function () {
                    sendRemoveMyCartItemRequest(id);
                }, 2000);
                break;
            case 5: // already undeployed
                showNotifyAlert('该商品已下架。');
                setTimeout(function () {
                    sendRemoveMyCartItemRequest(id);
                }, 2000);
                break;
            case 6: // already deleted
                showNotifyAlert('该商品不存在。');
                setTimeout(function () {
                    sendRemoveMyCartItemRequest(id);
                }, 2000);
                break;
            case 1:
            case 2:
            case 3:
            case 0:
                showProductDetailInfo(id, 4);
                break;
        }
    }
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
        } else {
            $('#itemCheck001').attr('src', data.check_imgs[3])
        }
    } else if ($('#itemCheck' + id).attr('src') == data.check_imgs[0]) {
        $('#itemCheck' + id).attr('src', data.check_imgs[1])
    } else {
        $('#itemCheck' + id).attr('src', data.check_imgs[0])
    }
    calculatePrices()
}

function calculatePrices() {
    var total_price = 0;
    var new_price = 0;
    var total_count = 0;
    var wallet_price = getMySessionWallet();
    var coupon_price = (($('#itemCheck001').attr('src') == data.check_imgs[2]) ? 30 : 0);
    var myCart = addToSessionCart(0);
    var isTotalSelected = true;
    if ($('#itemCheck001').attr('src') == data.check_imgs[2]) {
        sessionStorage.setItem('coupon_select_status', '1');
    }else{
        sessionStorage.removeItem('coupon_select_status');
    }
    for (var i = 0; i < myCart.length; i++) {
        var item = myCart[i]
        var itemStatus = (($('#itemCheck' + item.id).attr('src') == data.check_imgs[0]) ? true : false);
        var itemAmount = parseInt($('#product_amount' + item.id).val());
        $('.added_amount_' + item.id).html(itemAmount);
        if (itemStatus) {
            total_price += parseFloat(item.old_price) * itemAmount;
            new_price += (parseFloat(item.old_price) - parseFloat(item.new_price)) * itemAmount;
            total_count += itemAmount;
        } else {
            isTotalSelected = false;
        }
        myCart[i]['cur_amount'] = itemAmount
        myCart[i]['cart_include_status'] = ((itemStatus) ? 1 : 0);
    }
    if (total_price < 300) {
        $('#coupon_show').hide();
    } else if (getCouponStatus() == 1) {
        $('#coupon_show').show();
    }
    localStorage.setItem('myCart', JSON.stringify(myCart));

    $('#total_price9999').html(getPrice(total_price))
    $('#total_count9999').html('(共' + total_count + '件)')
    $('#rest_price9999').html(getPrice(new_price));

    total_price = total_price - coupon_price;

    if (wallet_price > total_price) wallet_price = total_price;

    $('#wallet_price9999').html(getPrice(wallet_price))
    if (isTotalSelected) $('#itemCheck000').attr('src', data.check_imgs[2])
    else $('#itemCheck000').attr('src', data.check_imgs[3])

    //$('#performCart').attr('onclick', 'orderFromCart();');
}

function orderFromCart() {
    addSessionWalletPayOrderInfo();
    addSessionOnlinePayOrderInfo();
    var wallet = getMySessionWallet();
    var coupon = (($('#itemCheck001').attr('src') == data.check_imgs[2]) ? 30 : 0);
    var price = 0;
    var myCart = addToSessionCart(0);

    var payInfo = {price: 0, coupon: coupon, wallet: wallet, pay_method: 1};
    var isExist = 0;
    var idList = [];
    for (var i = 0; i < myCart.length; i++) {
        var item = myCart[i]
        var itemStatus = (($('#itemCheck' + item.id).attr('src') == data.check_imgs[0]) ? true : false);
        if (itemStatus) {
            var itemAmount = parseInt($('#product_amount' + item.id).val());
            price = parseFloat(item.old_price) * itemAmount;
            if (price < wallet) {
                payInfo = {price: 0, coupon: 0, wallet: price, pay_method: 1}
                wallet -= price;
            } else if (price < wallet + coupon) {
                payInfo = {price: 0, coupon: coupon, wallet: price - coupon, pay_method: 1}
                wallet -= (price - coupon);
                coupon = 0;
            } else {
                payInfo = {price: price - wallet - coupon, coupon: coupon, wallet: wallet, pay_method: 1}
                wallet = 0;
                coupon = 0;
            }
//            setMySessionWallet(wallet);
            payInfo['order_note'] = '';
            idList.push(item.id);
            if (payInfo.price > 0) {
                addSessionOnlinePayOrderInfo(item.id, payInfo, itemAmount, '')
            } else {
                addSessionWalletPayOrderInfo(item.id, payInfo, itemAmount, '')
            }
            isExist = 1;
            //sendOrderRequest(item.id, itemAmount, 1);
        }
    }
    //$('#performCart').attr('onclick', "showNotifyAlert('You have sent payment request already.');");
    if (isExist == 0) {
        showNotifyAlert('还没有选择商品。');
        // $('#performCart').attr('onclick', 'orderFromCart();');

        return;
    }
    sessionStorage.setItem('cartPayIds', JSON.stringify(idList));
    location.href = "order_apply_cart.php";
    //preparePayment();
}

function payPrepareFromCart() {
    var curPayInfo = addSessionOnlinePayOrderInfo(0)
    var price = 0;
    var wallet = 0;
    for (var i = 0; i < curPayInfo.length; i++) {
        price += parseFloat(curPayInfo[i].payInfo.price);
        wallet += parseFloat(curPayInfo[i].payInfo.wallet);
    }
    if (price > 0) {
        //going to confirm page.
        location.href = 'my_success.php?iId=6';
    } else if (wallet > 0) {
        location.href = 'my_success.php?iId=6';
    } else {
        showNotifyAlert('支付信息错误。')
    }
}

function onlinePayOrder() {
    var orderIDlist = app_data['orderIDList'];

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
            'note': noteList[0],
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

                for (var i = 0; i < orderList.length; i++) {
                    site_pay['cost'] += parseFloat(moneyList[i]);// + parseFloat(walletList[i]);// + parseFloat(couponList[i]);
                }

                sessionStorage.setObject('site_pay', site_pay);
                // delete wallet information

                // -------------------------

                sessionStorage.removeItem('cur_detail_index');
                sessionStorage.removeItem('cur_menu_index');
                location.href = "order_manage.php";
                // window.location.href = 'my_success.php?pay_method = "weixin"';
            }
        },
        error: function (data) {

        }
    });

}

function resize_main_page() {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = parseInt($('.page-footer').css('height'));
    var menu_height = 0;//parseInt($('#horizontal_menu_bar').css('height'));
    var carousel_height = 0;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height,
        'top': carousel_height + menu_height
    });

    $('.carousel_item').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
    $('.order.cart .commodity_body .body_img').css({'height': $('.order.cart .commodity_body .body_img').css('width')});
}

function onOk() {
    var delId = data.cur_deleting_index;
    sendRemoveMyCartItemRequest(delId);
}

function OnOk() {
    if (!getRegisterStatus()) {
        location.href = 'user_login.php'
        return;
    }
}

function OnCancel() {
    $('#auth_question').modal('hide');
}