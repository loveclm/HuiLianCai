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
    document.title = '订单详情';
//    simulate_advertise_images();
//    simulat_menu_infos();

//    loadDatafromStorage();
    selectMenu(0);
    resize_main_page();
});

$(window).resize(function () {
    simulate_advertise_images();
    simulat_menu_infos();

    resize_main_page();
    loadDatafromStorage();
});

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = getPhoneNumber();
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(sessionStorage.getItem('auth_status'));
}

function initializeData() {
    if (sessionStorage.getItem('cur_menu_index') === null) {
        sessionStorage.setItem('cur_menu_index', 0);
    }
    if (sessionStorage.getItem('cur_detail_index') === null) {
        sessionStorage.setItem('cur_detail_index', 0);
    }
    if (sessionStorage.getItem('auth_status') === null) {
        sessionStorage.setItem('auth_status', 0);
    }

}

// display advertise images on the slider
function display_advertise_images() {
}

// display menu items on the horizontal menu bar
function display_menu_infos() {
//    var menu_item_html = "";
//    if (data.menu_info != undefined) {
//        for (var i = 0; i < data.menu_info.length; i++) {
//            menu_item_html += '<ul id="menuItem' + i;
//            menu_item_html += '" onclick="selectMenu(' + i + ')">';
//            menu_item_html += data.menu_info[i]['name'] + '</ul>';
//        }
//    }

//    $('#horizontal_order_menu_bar').html(menu_item_html);
}

// shows the detail menu items of any menu
function selectMenu(index) {
//    if (data.bShow_detal_menu == 1 && data.cur_menu_index == index) return;
//    if (data.cur_menu_index != index) data.cur_detail_index = -1;

//    var content_html = "";
//    if (data.menu_info == undefined) return;

    // when menu is selected, shows selected status along the design
//    $('#menuItem' + data.cur_menu_index).css({'border': 'none', 'color': 'black'});
//    data.cur_menu_index = index;
//    sessionStorage.setItem('cur_menu_index', index);
//    $('#menuItem' + data.cur_menu_index).css({'color': '#38abff', 'border-bottom': '3px solid'});

    // shows the detail menu informations with popup format
//    var height = document.body.clientHeight
//        || document.documentElement.clientHeight
//        || window.innerHeight;
//    height -= parseInt($('#horizontal_order_menu_bar').css('height'));

//    data.bShow_detal_menu = 1;
    display_product_infos(index);
}

function selectDetailMenu(index) {
//    if (data.cur_menu_index == index) {
//
//        return;
//    }
    $('#detail_menu_item' + data.cur_detail_index).css({color: 'black'});
    data.cur_menu_index = index;
    $('#detail_menu_item' + data.cur_detail_index).css({color: '#38abff'});
    //loading data from menu index and detail menu index
}

// display the product list on the content
function display_product_infos(index) {/////index mean order for shipper

    var product_data = "";
    var orderList = JSON.parse(sessionStorage.getItem('historyItemList'));
    console.log(orderList);
    var cur_shipper_detail_index = pageItemIdTxt;
    console.log(cur_shipper_detail_index);
    for (var i = 0; i < orderList.length; i++) {
        if (orderList[i].id == cur_shipper_detail_index) {
            product_data += myShipperHistoryItemDetailTemplate(orderList[i], index);
            break;
        }
    }
    if (product_data == '') product_data = noItemsTemplate(1);

    $("#product_container").html(product_data);
}

function confirmServiceModal(orderId) {
    //confirm_dialog
    $('.custom-data-confirm-class').attr('orderId', orderId);
    showMessage('<br>是否将商品送到终端便利店？<br>', 1);
}

function OnConfirmMsg() {

    var orderId = $('.custom-data-confirm-class').attr('orderId');
    confirmShipperProductInfo(pageItemIdTxt);
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
    // $('.order .commodity_detail').css({'width': width - 135, 'margin': 0, 'padding': 0});
}