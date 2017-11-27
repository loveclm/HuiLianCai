var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    document.title = '拼团详情';

    getMyOrderItemTemplate();
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

    data.phone_num = getPhoneNumber();
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
    data['orders'] = JSON.parse(
        (sessionStorage.getItem('orderDatas') == undefined) ? '[]' : sessionStorage.getItem('orderDatas')
    );

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
    height -= parseInt($('#horizontal_order_menu_bar').css('height'));

    data.bShow_detal_menu = 1;
    display_product_infos(index);
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

// display the product list on the content
function display_product_infos(index) {

    var product_html = "";

    var isParticipant = 0;
    var curItem = '';
    var curPrice = 0;

    for (var i = 0; i < data.orders.length; i++) {
        if (data.orders[i].id == pageType) {
            product_html += myGroupingDetailTemplate(data.orders[i], index);

            var item = data.orders[i];
            curItem = item.name;
            curPrice = 0;
            for (var j = 0; j < item.man_info.length; j++) {
                if (item.man_info[j].id == getSessionMyInfo().id) {
                    isParticipant = 1;
                    curPrice += ((parseFloat(item.old_price)-parseFloat(item.new_price))*parseInt(item.amount))
                    break;
                }
            }

            break;
        }
    }

    if (isParticipant == 1) {
//            if (item.grouping_status == 3 && getAuthorizationStatus()) {
        var price = 0;
        var desc_txt = '我参与了{惠联彩拼货平台}';
        desc_txt += curItem + '拼团采购, 节省了'
        desc_txt += curPrice + '元钱';
//desc_txt += item.product_name + '为活动名称)';
        weixinConfigure(desc_txt);
    } else {
        weixinConfigure('惠联彩,每天都是订货会');
    }

    if (product_html == '') product_html = noItemsTemplate(1);
    if (!getAuthorizationStatus()) product_html = noItemsTemplate(1);

    $("#product_container").html(product_html);
}

function onOk() {
    var payInfo = addSessionOnlinePayOrderInfo(0)[0];
    sendCancelOrderRequest(payInfo.id, '');
    $('#message_dialog').modal('hide');
}

function showCancelOrderConfirm(id) {

    addSessionOnlinePayOrderInfo();
    addSessionOnlinePayOrderInfo(id, 1, 1, id);

    showMessage('确认取消订单？', 1)
}

function applyCancelFeedback(id) {
    console.log(id)
    var myCurOrder = [];
    for (var i = 0; i < data.orders.length; i++) {
        if (data.orders[i].id == id) {
            myCurOrder = data.orders[i];

            break;
        }
    }
    addSessionOnlinePayOrderInfo();
    addSessionOnlinePayOrderInfo(id, 1, 1, id);

    location.href = 'myfunction_feedback.php?page=2'
}

function showManDetailInfo(manInfo, id) {
    $("#confirm_dialog").html(manDetailTemplate(manInfo, id));
    $("#confirm_dialog").css({'top': '30%', 'left': '10%', 'right': '10%'});
    $("#confirm_dialog").modal();
}

function hideModal() {
    $("#confirm_dialog").modal('hide');
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
    $('.order .commodity_detail').css({'width': width - 135, 'margin': 0, 'padding': 0});
}