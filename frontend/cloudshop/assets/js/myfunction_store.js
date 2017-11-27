var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [],
    'advertise_imgs': [
        'assets/images/store_info@2x.jpg',
        'assets/images/store_info_btn.png',
    ],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    document.title = '终端便利店认证';
    if ((!getRegisterStatus()) || (!getAuthorizationStatus())) {
        location.href = 'myfunction_manage.php';
    }

    getMyStoreInfoTemplate(getPhoneNumber())
    if (!getAuthorizationStatus())
        location.href = "user_register_detail.php"

    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();

    //selectBottomItem(data.cur_bottom_index, 0);
    resize_main_page();
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
    var storeItem = getSessionMyInfo()
    var carousel_content_html = "";
    if (data.advertise_imgs != undefined) {
        carousel_content_html += '<div class="owl-item">';
        carousel_content_html += '<img src="';
        carousel_content_html += data.advertise_imgs[0] + '">';

        carousel_content_html += '<h5><span class="shop-detail" style="bottom:32px;font-size:14pt;vertical-align: middle">';
        carousel_content_html += storeItem.user_name;
        carousel_content_html += '<img style="width:45px; height:auto; vertical-align:text-top;" src="';
        carousel_content_html += data.advertise_imgs[1] + '">';
        carousel_content_html += '</span>'
        carousel_content_html += '</h5>';


        carousel_content_html += '<h5><span class="shop-detail">';
        carousel_content_html += storeItem.user_phone;
        carousel_content_html += '</span></h5>';
        carousel_content_html += '</div>';
    }
    $('#advertise_header').html(carousel_content_html);
}

function showImageDetailInfo(img) {
    $("#confirm_dialog").html(imageDetailTemplate(img));
    $("#confirm_dialog").css({'top': '24%', 'left': '5%', 'right': '5%'});
    $("#confirm_dialog").modal();
    showModalToCenter('confirm_dilaog');
}

// display menu items on the horizontal menu bar
function display_menu_infos() {
    var menu_item_html = "";
    var storeInfo = getSessionMyInfo();
    if (storeInfo != undefined) {
        for (var i = 0; i < 1; i++) {
            menu_item_html += myStoreInfoTemplate(storeInfo, 0);
        }
    }

    $('#product_container').html(menu_item_html);
}

function applyStoreAuthrization() {
    showMessage('认证信息提交后则不能修改，<br>是否确认提交？', 1);
    setTimeout(function () {
        location.href = "my_success.php?iId=1";
    }, 2000);
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
    var carousel_height = width / 2.5;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height - 3,
        'top': carousel_height + menu_height + 3
    });

    $('.owl-item').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
}
