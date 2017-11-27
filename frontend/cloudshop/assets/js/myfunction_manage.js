var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '1',       // product kind id
            'name': '我的订单',
            'icon': 'assets/images/personal_icon1@3x.png',
            'redirect': 'order_manage.php',
        },
        {
            'id': '2',
            'name': '我的拼团',
            'icon': 'assets/images/personal_icon2@3x.png',
            'redirect': 'grouping_manage.php',
        },
        {
            'id': '3',
            'name': '店铺认证',
            'icon': 'assets/images/personal_icon3@3x.png',
            'redirect': 'myfunction_store.php',
        },
        {
            'id': '4',
            'name': '我的收藏',
            'icon': 'assets/images/personal_icon4@3x.png',
            'redirect': 'mycollection_home.php',
        },
        {
            'id': '5',
            'name': '我的钱包',
            'icon': 'assets/images/personal_icon5@3x.png',
            'redirect': 'myfunction_wallet.php',
        },
        {
            'id': '6',
            'name': '我的优惠券',
            'icon': 'assets/images/personal_icon6@3x.png',
            'redirect': 'myfunction_coupon.php',
        },
        {
            'id': '7',
            'name': '我的积分',
            'icon': 'assets/images/personal_icon7@3x.png',
            'redirect': 'my_integral.php',
        },
        {
            'id': '8',
            'name': '设置',
            'icon': 'assets/images/personal_icon8@3x.png',
            'redirect': 'myfunction_setting.php',
        },
    ],
    'advertise_imgs': [
        'assets/images/main_setting@2x.jpg',
        'assets/images/main_setting_d@2x.jpg',
        'assets/images/main_setting_n@2x.jpg',
        'assets/images/main_setting_f@2x.jpg',
    ],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    document.title = '个人中心';
    if ((!getRegisterStatus()) || (!getAuthorizationStatus())) showAuthRequire();

    //sendLoginRequest(getPhoneNumber(), getSessionPassword(), 0);

    simulate_advertise_images();
    simulat_menu_infos();

    loadDatafromStorage();
    resize_main_page();
    data.cur_bottom_index = 4;
    selectBottomItem(data.cur_bottom_index, 0);
    if (getAuthorizationStatus()) showCartStatus()
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
    if (data.advertise_imgs != undefined) {
        carousel_content_html += '<div class="owl-item">';
        carousel_content_html += '<img src="';
        if (getAuthorizationStatus() == true) {
            carousel_content_html += data.advertise_imgs[0] + '">';
            carousel_content_html += '<h5><span class="shop-detail" style="bottom:32px;font-size:14pt;">';
            carousel_content_html += getSessionMyInfo().user_name ;
            carousel_content_html += '</span></h5>';
        } else if (!getAuthRequestStatus()) {
            carousel_content_html += data.advertise_imgs[1] + '">';
        } else if (getSessionMyInfo().status == '4') {
            carousel_content_html += data.advertise_imgs[3] + '">';
        } else {
            carousel_content_html += data.advertise_imgs[2] + '">';
        }
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
}

// shows the detail menu items of any menu
function selectMenu(index) {
    if (data.bShow_detal_menu == 1 && data.cur_menu_index == index) return;
    if (data.cur_menu_index != index) data.cur_detail_index = -1;

    if (data.menu_info == undefined) return;

    // when menu is selected, shows selected status along the design
    data.cur_menu_index = index;
    sessionStorage.setItem('cur_menu_index', 0);
    if (index == 3 && !getAuthorizationStatus()) {

        if (false) {//(getAuthRequestStatus()) {
            sendLoginRequest(getPhoneNumber(), getSessionPassword(), 0);
            showAuthRequire();
        } else if (!getRegisterStatus()) {
            showAuthRequire();
        } else {
            location.href = 'user_register_detail.php';
        }
    } else {
        location.href = data.menu_info[index - 1]['redirect'];
    }
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
    var carousel_height = width / 2.4;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height - 3,
        'top': carousel_height + menu_height + 3
    });

    $('.owl-item').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
}

function OnOk() {
    if (!getRegisterStatus())
        location.href = "user_login.php";
    else
        location.href = "user_register_detail.php";
}

function OnCancel() {
    $('#auth_question').modal('hide');
}
