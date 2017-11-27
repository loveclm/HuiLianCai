var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '0',       // product kind id
            'name': '全部',   // product kind name
        },
        {
            'id': '1',       // product kind id
            'name': '待付款',   // product kind name
        },
        {
            'id': '2',       // product kind id
            'name': '待成团',   // product kind name
        },
        {
            'id': '3',       // product kind id
            'name': '待发货',   // product kind name
        },
        {
            'id': '4',       // product kind id
            'name': '交易完成',   // product kind name
        },
    ],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    document.title = '订单列表';
    if((!getRegisterStatus()) || (!getAuthorizationStatus())) showAuthRequire();
    if (getAuthorizationStatus()) getMyOrderItemTemplate();
    else showContents()
});

$(window).resize(function () {
    resize_main_page();
});

function showContents() {
    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();

    selectMenu(sessionStorage.getItem('cur_menu_index'));
    resize_main_page();
}

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = sessionStorage.getItem('phone_num');
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
    if (sessionStorage.getItem('orderDatas') == undefined) return
    data['orders'] = JSON.parse(sessionStorage.getItem('orderDatas'));
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
    var menu_item_html = "";
    if (data.menu_info != undefined) {
        for (var i = 0; i < data.menu_info.length; i++) {
            menu_item_html += '<ul id="menuItem' + i;
            menu_item_html += '" onclick="selectMenu(' + i + ')">';
            menu_item_html += data.menu_info[i]['name'] + '</ul>';
        }
    }

    $('#horizontal_order_menu_bar').html(menu_item_html);
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
    height -= parseInt($('#advertise_header').css('height'));
    height -= parseInt($('#horizontal_order_menu_bar').css('height'));
    $('#detail_menu_mask').css({'height': height});

    data.bShow_detal_menu = 1;
    display_product_infos(index);
}

// display the product list on the content
function display_product_infos(index) {

    var product_html = "";
    if (data.orders != undefined) {
        for (var i = 0; i < data.orders.length; i++) {
            product_html += myOrderItemTemplate(data.orders[i], index);
        }
    }
    if (product_html == '') product_html = noItemsTemplate(1);

    if (!getAuthorizationStatus()) product_html = noItemsTemplate(1);

    $("#product_container").html(product_html);
    resize_main_page();
}

function showPaymentPageFromOrderList(id) {
    if (sessionStorage.getItem('orderDatas') == undefined) {
        showNotifyAlert('订单信息不存在。');
        return;
    }
    var all_orders = JSON.parse(sessionStorage.getItem('orderDatas'));
    var cur_order = [];
    var isExist = 0;
    for (var i = 0; i < all_orders.length; i++) {
        if (all_orders[i].id == id) {
            cur_order = all_orders[i];
            isExist = 1;
            break;
        }
    }
    if (isExist == 0) {
        showNotifyAlert('该订单已删除。');
        return;
    }
    addToSessionOrder(cur_order.id, 0, 2);

    location.href = "order_apply.php?iId=\'" + cur_order.id + "\'"
}

function showCancelOrderConfirm(id) {
    console.log(id)
    var myCurOrder = [];
    for (var i = 0; i < data.orders.length; i++) {
        if (data.orders[i].id == id) {
            myCurOrder = data.orders[i]
            break;
        }
    }
    addSessionOnlinePayOrderInfo();
    addSessionOnlinePayOrderInfo(id, 1, 1, id);

    showMessage('确认取消订单？', 1)
}

function applyCancelFeedback(id) {
    console.log(id)
    var myCurOrder = [];
    for (var i = 0; i < data.orders.length; i++) {
        if (data.orders[i].id == id) {
            myCurOrder = data.orders[i]
            break;
        }
    }
    addSessionOnlinePayOrderInfo();
    addSessionOnlinePayOrderInfo(id, 1, 1, id);

    location.href = 'myfunction_feedback.php?page=2'
}

function resize_main_page() {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = parseInt($('.page-footer').css('height'));
    var menu_height = parseInt($('#horizontal_order_menu_bar').css('height'));
    console.log(menu_height);
    var carousel_height = 0;//width/1.46;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = parseInt(width) - 200;
    $('#product_container').css({'height': content_height, 'margin-top': menu_height - 1});

    $('.carousel_item').css({'height': carousel_height});
    //$('.commodity_progress').css({'width': progress_width});
    // $('.order .commodity_detail').css({'width': width - 135, 'margin': 0, 'padding': 0});
    $('.commodity_body img').css({height: $('.commodity_body img').css('width')});
}

function onOk() {
    var payInfo = addSessionOnlinePayOrderInfo(0)[0];
    sendCancelOrderRequest(payInfo.id, '');
    $('#message_dialog').modal('hide');
}

function OnOk(){
    if(!getRegisterStatus())
        location.href="user_login.php";
    else
        location.href="user_register_detail.php";
}

function OnCancel(){
    $('#auth_question').modal('hide');
}
