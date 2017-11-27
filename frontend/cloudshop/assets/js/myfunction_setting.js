var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '1',       // product kind id
            'name': '修改密码',
            'redirect': 'myfunction_renewpassword.php',
        },
        {
            'id': '2',
            'name': '关于我们',
            'redirect': 'about_us.php',
        },
        {
            'id': '3',
            'name': '意见反馈',
            'redirect': 'myfunction_feedback.php?page=1',
        },
        {
            'id': '4',
            'name': '退出登录',
            'redirect': 'user_login.php',
        },
    ],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    document.title = '设置';
    simulate_advertise_images();
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

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = getPhoneNumber();
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
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
    if (index == 4) {
        sendLoginRequest(getPhoneNumber(), getSessionPassword(), 2);
    } else if (index == 1) {
        if (getRegisterStatus()) location.href = data.menu_info[index - 1]['redirect'];
        else showAuthRequire();
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
    var carousel_height = 0;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height - 3,
        'top': carousel_height + menu_height + 3
    });

    $('.carousel_item').css({'height': carousel_height});
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
