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
    'wallet_amount': 0
};

$(document).ready(function () {
    document.title = '我的钱包';
    if (getAuthorizationStatus()) getMyTransactionItemsTemplate();
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
}

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = sessionStorage.getItem('phone_num');
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
    data.wallet_amount = getMySessionWallet()
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
    var carousel_content_html = "";
    if (data.advertise_imgs[0] == undefined) {
        carousel_content_html += '<div class="carousel_item wallet_top">';
        carousel_content_html += '<h5>我的余额</h5>';
        carousel_content_html += '<h5><span>' + getPrice(data.wallet_amount) + '</span></h5>';
        carousel_content_html += '</div>';
    }
    $('#advertise_header').html(carousel_content_html);
}

// display menu items on the horizontal menu bar
function display_menu_infos() {
    var menu_item_html = "";
    if (data.menu_info != undefined) {
        for (var i = 0; i < data.menu_info.length; i++) {
            menu_item_html += myMenuItemsTemplate(data.menu_info[i]);
        }
    }

    $('#product_container').html(menu_item_html);
    $('.order.detail.menu-item').css({'padding': '17px 10px'});
}

// shows the detail menu items of any menu
function selectMenu(index) {
    if (!getAuthorizationStatus()) {
        showAuthRequire('您还未认证,<br>请先进行店铺认证！', '立即认证');
        return
    }
    if (data.bShow_detal_menu == 1 && data.cur_menu_index == index) return;
    if (data.cur_menu_index != index) data.cur_detail_index = -1;

    if (data.menu_info == undefined) return;

    // when menu is selected, shows selected status along the design
    data.cur_menu_index = index;
    sessionStorage.setItem('cur_menu_index', 0);
    location.href = data.menu_info[index - 1]['redirect'];
}

function OnOk() {
    window.location.href = 'user_register_detail.php';
}

function OnCancel() {
    $('#auth_question').modal('hide');
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
    var carousel_height = width / 1.9;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height - 4,
        'top': carousel_height + menu_height + 4
    });

    $('.carousel_item').css({'height': carousel_height,});
    $('.commodity_progress').css({'width': progress_width});
}
