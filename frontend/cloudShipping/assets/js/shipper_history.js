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
    document.title = '配送订单';
    var start_date = ($('*[name=date10]').val());
    var end_date = ($('*[name=date11]').val());
    getShippingHistoryItems(start_date, end_date)
    //showShippingHistoryList();
});
$(window).resize(function () {
    resize_main_page();
});

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = getPhoneNumber();
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
    if (sessionStorage.getItem('auth_status') === null) {
        sessionStorage.setItem('auth_status', 0);
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
//    height -= parseInt($('#advertise_header').css('height'));
//    height -= parseInt($('#horizontal_order_menu_bar').css('height'));
//    $('#detail_menu_mask').css({'height': height});

//    data.bShow_detal_menu = 1;
    display_product_infos(index);
}

function searchShippingHistoryItems() {
    var start_date = ($('*[name=date10]').val());
    var end_date = ($('*[name=date11]').val());
    getShippingHistoryItems(start_date, end_date)
}

// display the product list on the content
function display_product_infos(index) {

    var historyItemList = "";
    var product_data = "";
    try {
    if(sessionStorage.getItem('historyItemList')!=undefined){
        historyItemList = JSON.parse(sessionStorage.getItem('historyItemList'));
        console.log(historyItemList);
        for (var i = 0; i < historyItemList.length; i++) {
            if (historyItemList[i].status == '4')
                product_data += myShipperHistoryItemTemplate(historyItemList[i], index);
        }
    }
        if (product_data == '') product_data = noItemsTemplate(1);

        $("#product_container").html(product_data);
    } catch (e) {
        showMessage(LANG_DATA.server_error);
        console.log(e);
    }
}

function showShippingHistoryList() {
    console.log('received');
//    simulate_advertise_images();
//    simulat_menu_infos();

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
