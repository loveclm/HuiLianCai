var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '1',       // product kind id
            'name': '个人信息',
            'icon': 'assets/images/dist_personal_icon1@2x.png',
            'redirect': 'shipper_info.php',
        },
        {
            'id': '2',
            'name': '修改密码',
            'icon': 'assets/images/dist_personal_icon2@2x.png',
            'redirect': 'shipper_renewpassword.php',
        },
        {
            'id': '3',
            'name': '关于我们',
            'icon': 'assets/images/dist_personal_icon3@2x.png',
            'redirect': 'about_us.php',
        },
        {
            'id': '4',
            'name': '意见反馈',
            'icon': 'assets/images/dist_personal_icon4@2x.png',
            'redirect': 'shipper_feedback.php',
        },
    ],
    'advertise_imgs': [
        'assets/images/main_setting@2x.png',
        'assets/images/main_setting_d@2x.png',
    ],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    simulate_advertise_images();
    simulat_menu_infos();

    loadDatafromStorage();
    resize_main_page();
    data.cur_bottom_index = 3;
    selectShipperBottomItem(data.cur_bottom_index, 0);
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
function display_advertise_images() {
    var carousel_content_html = "";
    if (data.advertise_imgs != undefined) {
        carousel_content_html += '<div class="owl-item">';
        carousel_content_html += '<img src="';
        if (getAuthorizationStatus() == true)
            carousel_content_html += data.advertise_imgs[0] + '">';
        else
            carousel_content_html += data.advertise_imgs[1] + '">';
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
    location.href = data.menu_info[index - 1]['redirect'];
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
