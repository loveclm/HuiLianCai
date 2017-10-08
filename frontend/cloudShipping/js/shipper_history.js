var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '0',       // product kind id
            'name': '线上支付订单',   // product kind name
        },
        {
            'id': '1',       // product kind id
            'name': '货到付款订单',   // product kind name
        },
    ],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};
$(document).ready(function () {
    var start_date = ($('*[name=date10]').val());
    var end_date = ($('*[name=date11]').val());
    //getShippingHistoryItems(start_date,end_date)
    showShippingHistoryList();
});
$(window).resize(function () {
    resize_main_page();
});

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = sessionStorage.getItem('phone_num');
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.cur_bottom_index = parseInt(sessionStorage.getItem('cur_bottom_index'));
    data.bAuthorization = parseInt(sessionStorage.getItem('auth_status'));
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
    if (sessionStorage.getItem('phone_num') === null) {
        sessionStorage.setItem('phone_num', '');
    }
    if (sessionStorage.getItem('auth_status') === null) {
        sessionStorage.setItem('auth_status', 0);
    }

}
// display advertise images on the slider
function display_advertise_images() {}

// display menu items on the horizontal menu bar
function display_menu_infos() {}

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

    var historyItemList = "[{\"id\":\"33333\",\"name\":\"55555555\",\"logo\":\"uploads/ayoubc97251505816073.PNG\",\"status\":\"3\",\"grouping_status\":\"2\",\"group_success\":\"2\",\"pay_type\":\"1\",\"pay_wallet\":\"0.00\",\"pay_coupon\":\"0.00\",\"pay_price\":\"100.00\",\"ordered_time\":\"2017-09-27 10:25:59\",\"paid_time\":\"2017-09-27 10:25:59\",\"closed_time\":null,\"refunded_time\":\"2017-09-27 10:25:59\",\"distributed_time\":null,\"success_time\":\"2017-09-27 14:15:04\",\"completed_time\":\"2017-10-08 15:53:05\",\"amount\":\"11\",\"dist_name\":\"bbb\",\"dist_phone\":\"121212123333\",\"provider_name\":\"provider1\",\"provider_address\":\"山西省,长治市,襄垣县,asdasd\",\"provider_contact_name\":\"adsfas\",\"provider_contact_phone\":\"121212121111\",\"shop_name\":\"aaa\",\"shop_address\":\"山西省,长治市,襄垣县\",\"shop_contact\":\"eyueue\",\"shop_contact_phone\":\"121212121111\",\"products\":[{\"id\":\"1\",\"name\":\"55555555\",\"barcode\":\"7774567890123\",\"image\":\"uploads/ayoubc97251505816073.PNG\",\"old_price\":\"24.50\",\"new_price\":\"10.00\",\"amount\":\"1\"}]}]";
    var product_data = "";
    try{
        historyItemList = JSON.parse(sessionStorage.getItem('historyItemList'));
        for (var i = 0; i < historyItemList.length; i++) {
            product_data += myShipperHistoryItemTemplate(historyItemList[i], index);
        }
        if (product_data == '') product_data = noItemsTemplate(1);

        $("#product_container").html(product_data);
    }catch(e)
    {
        showMessage('服务器错误');
        console.log(e);
    }
}
function showShippingHistoryList() {

    simulate_advertise_images();
    simulat_menu_infos();

    loadDatafromStorage();
    selectMenu(0);
    data.cur_bottom_index = 2;
    selectShipperBottomItem(data.cur_bottom_index, 0);
    resize_main_page();

}
function resize_main_page() {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = parseInt($('.page-footer').css('height'));
    var menu_height = parseInt($('#horizontal_menu_bar').css('height'));
    var carousel_height = 0;//width / 2.4;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height,
        'top': carousel_height + menu_height
    });

    $('.owl-item').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
    $('.commodity_body img').css({'height': $('.commodity_body img').css('width')});

}
