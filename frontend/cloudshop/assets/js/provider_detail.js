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
    document.title = '区域总代理详情';

    getProviderDetailData(pageItemId)
    weixinConfigure();
});

$(window).resize(function () {
    resize_main_page();
});

function showContents() {
    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();
    showFavouriteStatus()
    resize_main_page();
}

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = getPhoneNumber();
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
    // data.all_products = JSON.parse(sessionStorage.getItem('favoriteDatas'));
    // var isExist = false;
    // for (var i = 0; i < data.all_products.length; i++) {
    //     if (parseInt(data.all_products[i].id) == pageItemId) {
    //         isExist = true;
    //         break;
    //     }
    // }
    // data.cur_product = data.all_products[i];
    data.cur_product = getCurProviderDetailInfo();
    setProviderFavouriteStatus(pageItemId, true, data.cur_product.object_id)
}

// display menu items on the horizontal menu bar
function showSetAmount(index) {
    if ((!getAuthorizationStatus()) || (!getRegisterStatus())) {
        showAuthRequire('您还未进行认证！', '立即认证', '取消');
        return;
    }
    data['all_products'] = getCurProviderDetailInfo().products;
    for (var i = 0; i < data.all_products.length; i++) {
        if (parseInt(data.all_products[i].id) == index)
            break;
    }
    index = i
    setCurActivityDetailInfo(data.all_products[index]);

    data['cur_product'] = data.all_products[index];
    $('#min_amount9999').val(data.cur_product.min_amount)
    $('#max_amount9999').val(data.cur_product.amount)
    $('#product_amount9999').val(data.cur_product.min_amount)
    $('#addToCart_dialog').modal()
    showModalToCenter('addToCart_dialog')
}

function onAddCart() {
    $('#addToCart_dialog').modal('hide')
    data.cur_product.cur_amount = $('#product_amount9999').val();
    sendAddMyCartItemRequest(true, data.cur_product.id,data.cur_product.cur_amount);
//    location.href = "product_detail.php?iId=" + data.cur_product.id
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
    display_product_infos(pageItemId)
}

// shows the detail menu items of any menu
function selectMenu(index) {
}

function setProviderFavourite(index) {
    // if (!getAuthorizationStatus()) {
    //     showAuthRequire('您还未进行认证！', '立即认证', '取消');
    //     return;
    // }
    if (getProviderFavouriteStatus(index) == false)
        sendAddFavouriteRequest(1, index)
    else sendRemoveFavouriteRequest(1, index)
    showFavouriteStatus()
}

function showFavouriteStatus() {
    if (getProviderFavouriteStatus(pageItemId) == false) {
        $("#favourStatus").html('<img src="assets/images/product_tabbar_icon2_n@3x.png"> 收藏')
        $("#favourStatus").attr('style', '');
    } else {
        $("#favourStatus").html('<img src="assets/images/product_tabbar_icon2_d@3x.png"> 已收藏')
        $("#favourStatus").attr('style', 'color: #38abff');
    }
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
    product_html += myProviderDetailTemplate(data.cur_product);

    if (product_html == '') product_html = noItemsTemplate(1);

    $("#product_container").html(product_html);
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
    var progress_width = parseInt(width) - 230;
    $('.product_container').css({'height': content_height, 'top': parseInt(width) / 1.46});

    $('.carousel_item').css({'height': parseInt(width) / 1.46});
    $('.commodity .commodity_body img').css({height: $('.commodity .commodity_body img').css('width')});
    $('.commodity_progress').css({'width': progress_width});
    $('.progress .progress-text').css({'width': progress_width});
    $('#provider_img').css({'height': $('#provider_img').css('width')});
}

function OnOk() {
    if (!getRegisterStatus())
        location.href = "user_login.php"
    else
        window.location.href = 'user_register_detail.php';
}

function OnCancel() {
    $('#auth_question').modal('hide');
}
