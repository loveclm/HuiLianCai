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
    getShippingItems();

});
function showShippingOrderList()
{
    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();

    selectMenu(0);
    data.cur_bottom_index = 1;
    selectShipperBottomItem(data.cur_bottom_index, 0);

    resize_main_page();
}
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
function display_menu_infos() {
    var menu_item_html = "";
    if (data.menu_info != undefined) {
        for (var i = 0; i < data.menu_info.length; i++) {
            menu_item_html += '<ul id="menuItem' + i;
            menu_item_html += '" onclick="selectMenu(' + i + ')">';
            menu_item_html += data.menu_info[i]['name'] + '</ul>';
        }
    }
    $('#horizontal_menu_bar').html(menu_item_html);
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
/*
         0 => online payment 线上支付订单
index =
         1 => cash payment 货到付款订单
 */
function display_product_infos(index) {
    try {
        var product_html = '';
        var product_data = JSON.parse(sessionStorage.getItem('orderList'));
        if(product_data===undefined) return;
        for (var i = 0; i < product_data.length; i++) {
            var tmp = product_data[i];
            console.log(tmp);
            product_html += myShipperOrderItemTemplate(tmp, index+1);
        }
        if (product_html == '') product_html = noItemsTemplate(1);
        $("#product_container").html(product_html);
    }
    catch(e)
    {
        alert('json parse error');
    }
}
function shipService(orderId) {

    $('.custom-data-confirm-class').attr('orderId',orderId);
    showMessage('<br>是否将商品送到终端便利店？<br>',1);

}
function  OnConfirmMsg() {

    var orderId = $('.custom-data-confirm-class').attr('orderId');
    confirmShipperProductInfo(orderId);
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
        'height': content_height ,
        'top': carousel_height + menu_height
    });

    $('.owl-item').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
    $('.commodity_body img').css({'height': $('.commodity_body img').css('width')});
}
