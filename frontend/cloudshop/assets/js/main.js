var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '0',       // product kind id
            'name': '推荐',   // product kind name
            'brand': [
                {
                    'id': '11',          // brand id
                    'name': '康师傅'
                },    // brand name
                {'id': '12', 'name': '伊利'},
                {'id': '13', 'name': '蒙牛'},
                {'id': '14', 'name': '今麦郎'},
                {'id': '15', 'name': '统一'},
                {'id': '16', 'name': '白象'},
                {'id': '17', 'name': '华丰'},
                {'id': '18', 'name': '五谷道场'},
                {'id': '19', 'name': '农心'}
            ]
        },
        {
            'id': '1',
            'name': '食品',
            'brand': [
                {'id': '12', 'name': '伊利'},
                {'id': '13', 'name': '蒙牛'},
                {'id': '15', 'name': '统一'},
                {'id': '17', 'name': '华丰'},
                {'id': '18', 'name': '五谷道场'},
            ]
        },
        {
            'id': '2',
            'name': '方便面',
            'brand': [
                {'id': '11', 'name': '康师傅'},
                {'id': '13', 'name': '蒙牛'},
                {'id': '14', 'name': '今麦郎'},
                {'id': '16', 'name': '白象'},
                {'id': '17', 'name': '华丰'},
                {'id': '19', 'name': '农心'}]
        },
        {
            'id': '3',
            'name': '乳制品',
            'brand': [
                {'id': '11', 'name': '康师傅'},
                {'id': '12', 'name': '伊利'},
                {'id': '13', 'name': '蒙牛'},
                {'id': '14', 'name': '今麦郎'},
                {'id': '16', 'name': '白象'}]
        },
        {
            'id': '4',
            'name': '冰淇淋',
            'brand': [
                {'id': '12', 'name': '伊利'},
                {'id': '13', 'name': '蒙牛'},
                {'id': '15', 'name': '统一'},
                {'id': '17', 'name': '华丰'},
                {'id': '18', 'name': '五谷道场'},
                {'id': '19', 'name': '农心'}]
        },
        {
            'id': '5',
            'name': '面包',
            'brand': [
                {'id': '11', 'name': '康师傅'},
                {'id': '13', 'name': '蒙牛'},
                {'id': '16', 'name': '白象'},
                {'id': '17', 'name': '华丰'},
                {'id': '18', 'name': '五谷道场'},
                {'id': '19', 'name': '农心'}]
        },
        {
            'id': '6',
            'name': '火腿肠',
            'brand': [
                {'id': '16', 'name': '白象'},
                {'id': '17', 'name': '华丰'},
                {'id': '18', 'name': '五谷道场'},
                {'id': '19', 'name': '农心'}]
        },
        {
            'id': '7',
            'name': '饮料',
            'brand': [
                {'id': '11', 'name': '康师傅'},
                {'id': '12', 'name': '伊利'},
                {'id': '16', 'name': '白象'},
                {'id': '17', 'name': '华丰'},
                {'id': '18', 'name': '五谷道场'},
                {'id': '19', 'name': '农心'}]
        },
        {
            'id': '8',
            'name': '生活用品',
            'brand': [
                {'id': '14', 'name': '今麦郎'},
                {'id': '15', 'name': '统一'},
                {'id': '16', 'name': '白象'},
                {'id': '17', 'name': '华丰'}]
        }
    ],
    'advertise_imgs': [
        'assets/images/tmp/u1.png',
        'assets/images/tmp/u2.png',
        'assets/images/tmp/u3.jpg',
        'assets/images/tmp/u1.png',
        'assets/images/tmp/u2.png',
    ],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    simulate_advertise_images();
    simulat_menu_infos();

    resize_main_page();
    loadDatafromStorage();
});

$(window).resize(function () {
    simulate_advertise_images();
    simulat_menu_infos();

    resize_main_page();
    loadDatafromStorage();
});

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = sessionStorage.getItem('phone_num');
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(sessionStorage.getItem('auth_status'));
}

function initializeData() {
    if (sessionStorage.getItem('cur_menu_index') === null) {
        sessionStorage.setItem('cur_menu_index', 0);
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
    var indicator_content_html = "";
    var carousel_content_html = "";
    if (data.advertise_imgs != undefined) {

        for (var i = 0; i < data.advertise_imgs.length; i++) {
            indicator_content_html += '<li data-target="#advertise_header" data-slide-to="' + i + '"'
                + (i == 0 ? ' class="active"' : '') + '></li>';
            carousel_content_html += '<div ' + (i == 0 ? 'class="item active"' : 'class="item"') + '>'
                + '<div class="carousel_item">'
                + '<img src="' + data.advertise_imgs[i] + '"></div></div>';
        }
    }

    $('.carousel-indicators').html(indicator_content_html);
    $('.carousel-inner').html(carousel_content_html);

    $('#advertise_header').carousel({
        interval: 3000,
        pause: "hover"
    });
    resize_main_page();

}


// display menu items on the horizontal menu bar
function display_menu_infos() {
    var menu_item_html = "";
    if (data.menu_info != undefined) {
        for (var i = 0; i < data.menu_info.length; i++) {
            menu_item_html += '<ul id="menuItem' + i + '" onclick="selectMenu(' + i + ')">' + data.menu_info[i]['name'] + '</ul>';
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
    $('#menuItem' + data.cur_menu_index).css({'color': '#38abff', 'border-bottom': '2px solid'});

    // show detail menu information
    for (var i = 0; i < data.menu_info[index].brand.length; i++) {
        content_html += '<ul id="detail_menu_item' + i + '" onclick="selectDetailMenu(' + i + ')">';
        content_html += data.menu_info[index].brand[i]['name'] + '</ul>'
    }
    $('#detail_menu_content').html(content_html);
    $('#detail_menu_item' + data.cur_detail_index).css({color: '#38abff'});


    // shows the detail menu informations with popup format
    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    height -= parseInt($('#advertise_header').css('height'));
    height -= parseInt($('#horizontal_menu_bar').css('height'));
    $('#detail_menu').css({display: 'block'});
    height -= parseInt($('#detail_menu_content').height()) + 2;
    $('#detail_menu_mask').css({'height': height});

    data.bShow_detal_menu = 1;
}

function selectDetailMenu(index) {
    if (data.cur_detail_index == index) {
        hideDetailMenu();
        return;
    }

    $('#detail_menu_item' + data.cur_detail_index).css({color: 'black'});
    data.cur_detail_index = index;
    $('#detail_menu_item' + data.cur_detail_index).css({color: '#38abff'});

    display_product_infos(index);
    hideDetailMenu();

    //loading data from menu index and detail menu index
}

// hide detail menu
function hideDetailMenu() {
    $('#detail_menu').css({display: 'none'});
    data.bShow_detal_menu = 0;
}

// display the product list on the content
function display_product_infos(index) {

    var product_data = '';
    for (var i = 0; i < index + 1; i++) {
        product_data += mainProductItemTemplate('');
    }
    $("#product_container").html(product_data);
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
    var carousel_height = width / 2.2;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({
        'height': content_height - 3,
        'top': carousel_height+ menu_height + 3
    });

    $('.carousel_item').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
}
