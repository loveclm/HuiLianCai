var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '1',       // product kind id
            'name': '交易明细',
            'redirect': 'mytransaction_detail.php',
        },
    ],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0,
    'wallet_amount': 6666
};

$(document).ready(function () {
    if (parseInt(pageItemType) == 25) document.title = '提交成功';
    else document.title = '支付成功';
    simulate_advertise_images();
    sendAddMyCartItemRequest(true);
    simulat_menu_infos();

    loadDatafromStorage();
    resize_main_page();
});

$(window).resize(function () {
    simulate_advertise_images();
    simulat_menu_infos();

    loadDatafromStorage();
    resize_main_page();
});

function showContents(){

}

// loading setting information
function loadDatafromStorage() {
    initializeData();
}

function initializeData() {
}

function performWeixinPayment() {
    var curPayInfo = addSessionOnlinePayOrderInfo(0)
    var price = 0;
    var wallet = 0;
    for (var i = 0; i < curPayInfo.length; i++) {
        price += parseFloat(curPayInfo[i].payInfo.price);
        wallet += parseFloat(curPayInfo[i].payInfo.wallet);
    }
    if (price > 0) {
        showNotifyAlert('Going to Weixin payment...')
        setTimeout(function () {
            //prepareRealPayment(price, curPayInfo[0].orderId, '购买商品')
        }, 3000)
    } else {
        sendPayRequest();
    }

}

// display advertise images on the slider
function display_advertise_images() {
}

// display menu items on the horizontal menu bar
function display_menu_infos() {
    var menu_item_html ='';

    if(parseInt(pageItemId)==1) menu_item_html = successProcessingTemplate(1);
    else if (parseInt(pageItemType) == 25) menu_item_html = successProcessingTemplate(5);
    else menu_item_html = successProcessingTemplate(6);

    $('#product_container').html(menu_item_html);
    $('.order.detail.menu-item').css({'padding': '17px 10px'});
}

// shows the detail menu items of any menu
function selectMenu(index) {
}

function resize_main_page() {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = 0;//parseInt($('.page-footer').css('height'));
    var menu_height = 0;//parseInt($('#horizontal_menu_bar').css('height'));
    var carousel_height = 0;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': height,
        'top': carousel_height + menu_height
    });

    //$('.carousel_item').css({'height': carousel_height,});
    //$('.commodity_progress').css({'width': progress_width});
}
