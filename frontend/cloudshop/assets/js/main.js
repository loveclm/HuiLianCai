var data = {
    'phone_num': '',
    'menu_info': [],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0,
    'paginationCnt': 5,////for Pagination variable-PMS-CODE
    'productPageCnt': 0////for Pagination variable-PMS-CODE
};

$(document).ready(function () {
    document.title = '惠联彩';
    getCarouselDatas();
    setTimeout(function () {
        getLocationGroupRequest(getPhoneNumber());
    }, 2000);
    weixinConfigure();

//    $('body').on('pageshow', onFocus);
});

function onFocus() {
//    if (localStorage.getItem('isLogout') == '1') {
//        location.href = 'index.php';
//    }
}

function showContents() {
    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();
    selectBottomItem(1, 0);
    if (!getAuthorizationStatus()) {
        if (getRegisterStatus()) {
            if (pageItemId != 0) {
                showAuthRequire('' +
                    '由于您还未进行认证,<br>无法看到商品价格！',
                    '立即认证',
                    '我知道了'
                );
            }
        }
    } else {
        sendAddMyCartItemRequest();
    }
    if (data.cur_menu_index == 0 && data.cur_detail_index == 0) {
        data.cur_menu_index = 100;
        data.cur_detail_index = 100;
    }
    selectCurMenuItem();
    setTimeout(function () {
        resize_main_page();
    }, 100);
}

// display menu items on the horizontal menu bar
function showSetAmount(index) {
    if ((!getAuthorizationStatus()) || (!getRegisterStatus())) {
        showAuthRequire('您还未进行认证！', '立即认证', '取消');
        return;
    }
    data['all_products'] = JSON.parse(sessionStorage.getItem('productDatas'));
    for (var i = 0; i < data.all_products.length; i++) {
        if (parseInt(data.all_products[i].id) == index)
            break;
    }
    index = i;
    setCurActivityDetailInfo(data.all_products[index]);
    data['cur_product'] = data.all_products[index];
    console.log(data.cur_product);
    $('#min_amount9999').val(data.cur_product.min_amount);
    $('#max_amount9999').val(data.cur_product.max_amount);
    $('#product_amount9999').val(data.cur_product.min_amount);
    $('#addToCart_dialog').modal();
    showModalToCenter('addToCart_dialog');
}

function onAddCart() {
    $('#addToCart_dialog').modal('hide');
    data.cur_product.cur_amount = $('#product_amount9999').val();
    sendAddMyCartItemRequest(true, data.cur_product.id, data.cur_product.cur_amount);
    //location.href="mycart_manage.php";
    //location.href = "product_detail.php?iId=" + data.cur_product.id
}

$(window).resize(function () {
    resize_main_page();
});

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = getPhoneNumber();
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.cur_bottom_index = parseInt(sessionStorage.getItem('cur_bottom_index'));
    data.bAuthorization = parseInt(getAuthorizationStatus());
    data.advertise_imgs = JSON.parse(sessionStorage.getItem('carouselDatas'));
    data.menu_info = JSON.parse(sessionStorage.getItem('menuDatas'));
}

function initializeData() {
    if (sessionStorage.getItem('cur_menu_index') === null) {
        sessionStorage.setItem('cur_menu_index', 0);
    }
    if (sessionStorage.getItem('cur_bottom_index') === null) {
        sessionStorage.setItem('cur_bottom_index', 1);
    }
    if (sessionStorage.getItem('cur_detail_index') === null) {
        sessionStorage.setItem('cur_detail_index', 0);
    }
}

// display advertise images on the slider
function display_advertise_images() {
    var carousel_content_html = "";
    if (data.advertise_imgs != undefined) {

        for (var i = 0; i < data.advertise_imgs.length; i++) {
            var item = data.advertise_imgs[i];
            carousel_content_html += '<div class="owl-item">';
            carousel_content_html += '<img src="' + getImageURL(item.imgData) + '"'
            carousel_content_html += ' alt="' + getImageURL(item.imgData) + '"'
            if (parseInt(item.linkData) == 0) item.linkData = 0;
            switch (parseInt(item.linkType)) {
                case 1:
                    carousel_content_html += ' >';
                    break;
                case 2:
                    carousel_content_html += ' onclick="showProductDetailInfo(\'' + item.linkData + '\')">';
                    break;
                case 3:
                    carousel_content_html += ' onclick="showProductDetailInfo(\'' + item.linkData + '\')">';
                    break;
                case 4:
                    carousel_content_html += ' onclick="showProviderDetailInfo(\'' + item.linkData + '\')">';
                    break;
                default:
                    carousel_content_html += ' >';
                    break;
            }
            carousel_content_html += '</div>';
        }
        if (carousel_content_html == '') {
            var img = "assets/images/store_info@2x.png"
            carousel_content_html += '<div class="owl-item">';
            carousel_content_html += '<img src="' + (img) + '"'
            carousel_content_html += ' alt="' + (img) + '">'
            carousel_content_html += '</div>';
        }
        $('#advertise_header').html(carousel_content_html);

        // $('#advertise_header').owlCarousel({
        //     autoPlay: 3000,
        //     stopOnHover: true,
        // });
    } else {
        var img = "assets/images/store_info@2x.png"
        carousel_content_html += '<div class="owl-item">';
        carousel_content_html += '<img src="' + (img) + '"'
        carousel_content_html += ' alt="' + (img) + '">'
        carousel_content_html += '</div>';

        $('#advertise_header').html(carousel_content_html);

    }
    resize_main_page();
}

// display menu items on the horizontal menu bar
function display_menu_infos() {
    var menu_item_html = "";
    if (data.menu_info != undefined) {
        for (var i = 0; i < data.menu_info.length; i++) {
            item = data.menu_info[i];
            menu_item_html += '<ul id="menuItem' + i;
            menu_item_html += '" onclick="selectMenu(' + i + ')">' + item.name;
            menu_item_html += '</ul>';
        }
    }

    $('#horizontal_menu_bar').css({'text-align': 'left'});
    $('#horizontal_menu_bar').html(menu_item_html);
}

// shows the detail menu items of any menu
function selectMenu(index) {
    //if (data.bShow_detal_menu == 1 && data.cur_menu_index == index) return;

    var content_html = "";
    if (data.menu_info == undefined) {
        display_no_product();
        return;
    }
    if (index > (data.menu_info.length - 1)) index = 0;

    // when menu is selected, shows selected status along the design
    $('#menuItem' + data.cur_menu_index).css({'border': 'none', 'color': 'black'});
    data.cur_menu_index = index;
    $('#menuItem' + index).css({'color': '#38abff', 'border-bottom': '2px solid'});

    if (index == 0) {
        data.bShow_detal_menu = 1;
        data.productPageCnt = 0;
        selectDetailMenu(0);
        return;
    }

    // show detail menu information
    for (var i = 0; i < data.menu_info[index].brand.length; i++) {
        content_html += '<ul id="detail_menu_item' + i + '" onclick="selectDetailMenu(' + i + ')">';
        content_html += data.menu_info[index].brand[i]['name'] + '</ul>'
    }
    $('#detail_menu_content').html(content_html);
    if (parseInt(sessionStorage.getItem('cur_menu_index')) == index)
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
    data.productPageCnt = 0;
    if (index > (data.menu_info[data.cur_menu_index].brand.length - 1)) index = 0;
    if (data.cur_menu_index == sessionStorage.getItem('cur_menu_index') && data.cur_detail_index == index) {
        hideDetailMenu();
        return;
    }
    if (data.menu_info[data.cur_menu_index].brand.length == 0) {
        display_no_product();
        //alert("brand0")
        return;
    }

    $('#detail_menu_item' + data.cur_detail_index).css({color: 'black'});
    data.cur_detail_index = index;
    sessionStorage.setItem('cur_detail_index', data.cur_detail_index);
    sessionStorage.setItem('cur_menu_index', data.cur_menu_index);
    $('#detail_menu_item' + data.cur_detail_index).css({color: '#38abff'});
    $("#product_container").html('');

    hideDetailMenu();

    if (data.menu_info[data.cur_menu_index].brand.length != 0) {
        getMainActivityItemTemplate(
            data.menu_info[data.cur_menu_index].id,
            data.menu_info[data.cur_menu_index].brand[index].id
        );
        //alert("brand1")

    } else {
        display_no_product();
        data.productPageCnt = 0;
        // return;
        //alert("barand3")
    }
    //loading data from menu index and detail menu index
}

// hide detail menu
function hideDetailMenu() {
    $('#detail_menu').css({display: 'none'});
    data.bShow_detal_menu = 0;
}

function selectCurMenuItem() {
    data.cur_detail_index = 100;
    selectMenu(parseInt(sessionStorage.getItem('cur_menu_index')));
    selectDetailMenu(parseInt(sessionStorage.getItem('cur_detail_index')));
}

function display_no_product() {
    hideDetailMenu();
    $('#product_container').html(noItemsTemplate(8));
    $('.order_no_items img').css({'width': '25%'})
    $('.order_no_items').css({'padding': '20% 0'})
}

function display_product_infos() {
    hideDetailMenu();
    if (sessionStorage.getItem('productDatas') == undefined) {
        display_no_product()
    } else if (sessionStorage.getItem('productDatas') == '[]') {
        display_no_product()
    } else {
        var product_datas = JSON.parse(sessionStorage.getItem('productDatas'));
        console.log('sdf');
        if (data.productPageCnt * data.paginationCnt > product_datas.length) return;
        var pageOffset = Math.min((data.productPageCnt + 1) * data.paginationCnt, product_datas.length);
        //alert("page added:" + data.productPageCnt + "," + pageOffset + "," + product_datas.length);
        if (data.productPageCnt == 0 && $("#product_container").html() != "") {
            $("#product_container").html('');
            return;
        }
        for (var i = data.productPageCnt * data.paginationCnt; i < pageOffset; i++) {
            $("#product_container").append(mainActivityItemTemplate(product_datas[i]));
        }
        data.productPageCnt++;
    }

    resize_main_page()
}

//////////////////////////////////////////////PMS-Code/////////////////
$('#product_container').on('scroll', function () {
    if($('#product_container').html()=="") return;

    if (scrollEndDetection(this)) {
        display_product_infos();
    }
});


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
    var progress_width = width * .6 - 70;
    $('.product_container').css({
        'height': content_height - 2,
        'top': carousel_height + menu_height
    });
    $('#advertise_header').owlCarousel({
        autoPlay: 3000,
        stopOnHover: true,
        //navigation:true,
        //navigationText:['<','>'],
        //pagination:false
    });
    $('#advertise_header').css({'height': carousel_height});
    $('.owl-item img').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
    $('.commodity_body img').css({height: $('.commodity_body img').css('width')});
}

function showAroundGroupingNotification() {
    var msgs = getSessionAroundInfo();
    if (msgs == '') return;
    showNotification(msgs[0].message, msgs[0].activity);
    msgs.slice(0);
    if (msgs.length == 0) {
        setSessionAroundInfo('');
        return;
    }
    setSessionAroundInfo(msgs);
    setTimeout(function () {
        showAroundGroupingNotification();
    }, 10000);
}

function OnOk() {
    if (!getRegisterStatus())
        location.href = "user_login.php";
    else
        location.href = 'user_register_detail.php';
}

function OnCancel() {
    $('#auth_question').modal('hide');
}
