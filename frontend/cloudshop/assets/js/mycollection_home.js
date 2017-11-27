var data = {
    'bAuthorization': 0,
    'phone_num': '',
    'menu_info': [
        {
            'id': '0',       // product kind id
            'name': '商品',   // product kind name
        },
        {
            'id': '1',       // product kind id
            'name': '区域总代理',   // product kind name
        },
    ],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0
};

$(document).ready(function () {
    document.title = '我的收藏';
    //if (getAuthorizationStatus())
    getMyFavouriteItemTemplate()
    //else
    //    showContents()
});

$(window).resize(function () {
    resize_main_page();
});

function showContents() {
    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();

    selectMenu(sessionStorage.getItem('cur_menu_index'));
    resize_main_page();
}

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = sessionStorage.getItem('phone_num');
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
    data['favors'] = [];
    if (sessionStorage.getItem('favoriteDatas') == undefined) return
    data['favors'] = JSON.parse(sessionStorage.getItem('favoriteDatas'));
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
            menu_item_html += '<ul id="menuItem' + i;
            menu_item_html += '" onclick="selectMenu(' + i + ')">';
            menu_item_html += data.menu_info[i]['name'] + '</ul>';
        }
    }
    menu_item_html += '<div class="search-item">';
    menu_item_html += '<div class="search-area">';
    menu_item_html += '<input id="collection_search" type="text"';
    menu_item_html += ' class="form-control input-sm"';
    menu_item_html += ' placeholder="搜索您想要的商品">';
    menu_item_html += '<img class="search_icon" src="assets/images/search_icon.png"'
    menu_item_html += ' onclick="searchList()">';
    menu_item_html += '</div>';
    menu_item_html += '</div>';


    $('#horizontal_collection_menu_bar').html(menu_item_html);
}

function searchList() {
    var index = data.cur_menu_index;
    var keywrd = $('#collection_search').val();
    if (sessionStorage.getItem('favoriteDatas') == undefined) {
        display_product_infos(data.cur_menu_index)
        return
    }
    data['favors'] = JSON.parse(sessionStorage.getItem('favoriteDatas'));
    var newList = [];
    for (var i = 0; i < data.favors.length; i++) {
        if (index == 0)
            var name = data.favors[i].detail.product_name;
        else
            var name = data.favors[i].detail.name;
        if (name == undefined) continue;
        if (keywrd == '' || name.indexOf(keywrd) != -1) {
            newList.push(data.favors[i]);
        }
    }
    data.favors = newList;
    console.log(newList)
    display_product_infos(data.cur_menu_index)
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
    if (index == 0)
        $('#collection_search').attr('placeholder', '搜索您想要的商品');
    else
        $('#collection_search').attr('placeholder', '搜索您想要的区域总代理');
    sessionStorage.setItem('cur_menu_index', index);
    $('#menuItem' + data.cur_menu_index).css({'color': '#38abff', 'border-bottom': '3px solid'});

    // shows the detail menu informations with popup format
    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    height -= parseInt($('#horizontal_grouping_menu_bar').css('height'));
    $('#detail_menu_mask').css({'height': height});

    data.bShow_detal_menu = 1;
    searchList();
}

// display the product list on the content
function display_product_infos(index) {

    var product_html = "";
    if (data.favors != undefined) {
        for (var i = 0; i < data.favors.length; i++) {
            var id = data.favors[i].object_id;
            if (index == 0) {
                product_html += myFavoriteItemTemplate(data.favors[i]);
                setFavouriteStatus(id, true, id)
            } else {
                product_html += myProviderItemTemplate(data.favors[i]);
                setProviderFavouriteStatus(id, true, id)
            }
        }
    }
    if (product_html == '') product_html = noItemsTemplate(5);

    $("#product_container").html(product_html);
    $('#msg_txt').html((index == 0 ? '暂无收藏商品' : '暂无收藏区域总代理'));
    resize_main_page()
}

function resize_main_page() {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = parseInt($('.page-footer').css('height'));
    var menu_height = parseInt($('#horizontal_collection_menu_bar').css('height')) + 5;
    var carousel_height = 0;//parseInt(width)/1.5;
    var content_height = height - footer_height - menu_height - carousel_height;
    var progress_width = width - 200;
    $('#product_container').css({'height': content_height, 'margin-top': menu_height});
    console.log(menu_height);
    $('.carousel_item').css({'height': carousel_height});
//    $('.commodity_progress').css({'width': progress_width});
//    $('.order .commodity_detail').css({'width': width - 135, 'margin': 0, 'padding': 0});
    $('.commodity_body img').css({height: $('.commodity_body img').css('width')});
}

function OnCancel() {
    $('#auth_question').modal('hide');
}