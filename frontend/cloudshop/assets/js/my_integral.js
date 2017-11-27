var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [],
    'advertise_imgs': [
        'assets/images/integral@2x.jpg',
        'assets/images/integral@3x.jpg',
    ],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    document.title = '我的积分';
    simulate_advertise_images();
    simulat_menu_infos();

    loadDatafromStorage();
    resize_main_page();
    selectBottomItem(data.cur_bottom_index);
});

$(window).resize(function () {
    simulate_advertise_images();
    simulat_menu_infos();

    loadDatafromStorage();
    resize_main_page();
});

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = sessionStorage.getItem('phone_num');
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
    var carousel_content_html = "";
    var my_integral = getSessionMyInfo().integral;
    if(my_integral==undefined) my_integral=0;
    if (data.advertise_imgs != undefined) {
        carousel_content_html += '<div class="owl-item integral_top">';
        carousel_content_html += '<img src="';
        carousel_content_html += data.advertise_imgs[0] + '">';
        carousel_content_html += '<h5><span>' + my_integral + '</span></h5>';
        carousel_content_html += '</div>';
    }
    $('#advertise_header').html(carousel_content_html);
}

// display menu items on the horizontal menu bar
function display_menu_infos() {
    var menu_item_html = "";

    //if (data.menu_info != undefined) {
    for (var i = 0; i < 1; i++) {
        menu_item_html += myIntegralTemplate(0);
    }
    //}

    $('#product_container').html(menu_item_html);
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
    var carousel_height = width / 1.85;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height - 3,
        'top': carousel_height + menu_height + 3
    });

    $('.owl-item').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
}
