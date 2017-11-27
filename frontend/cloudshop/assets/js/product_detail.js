var data = {
    'menu_info': [],
    'advertise_imgs': [],
    'cur_menu_index': 0,
    'cur_detail_index': 0,
    'cur_bottom_index': 0,
    'bShow_detal_menu': 0,
    'all_products': [],
    'cur_product': [],
    'cur_product_index': 0,
};

$(document).ready(function () {
    document.title = '商品详情';
    if (!getAuthorizationStatus()) showAuthRequire('您还未进行认证！', '立即认证', '我知道了');
    getActivityDetailData(pageItemId)
    getLocationGroupRequest(getPhoneNumber());
//    weixinConfigure();
});

$(window).resize(function () {
    resize_main_page();
});

function showContents() {
    loadDatafromStorage();
    simulate_advertise_images();
    simulat_menu_infos();
    if (getAuthorizationStatus()) showCartStatus()
    showFavouriteStatus();

    resize_main_page();

}

// loading setting information
function loadDatafromStorage() {
    initializeData();

    data.phone_num = getPhoneNumber();
    data.cur_detail_index = parseInt(sessionStorage.getItem('cur_detail_index'));
    data.cur_menu_index = parseInt(sessionStorage.getItem('cur_menu_index'));
    data.bAuthorization = getAuthorizationStatus();

    data.cur_product = getCurActivityDetailInfo();
    var item = data.cur_product;
    var isParticipant = 0;
    var curPrice = 0;
    for (var j = 0; j < item.man_info.length; j++) {
        if (item.man_info[j].id == getSessionMyInfo().id) {
            isParticipant = 1;
            curPrice += ((parseFloat(item.old_price)-parseFloat(item.new_price))*parseInt(item.amount))
            break;
        }
    }

    if (isParticipant == 1) {
        console.log('cur_rest_price:'+curPrice);
        var desc_txt = '我参与了{惠联彩拼货平台}';
        desc_txt += item.name + '拼团采购, 节省了'
        desc_txt += getPrice(curPrice,1)+ '元钱';
        //desc_txt += item.product_name + '为活动名称)';
        weixinConfigure(desc_txt);
    } else {
        weixinConfigure('惠联彩,每天都是订货会');
    }
    data.advertise_imgs = data.cur_product.logos;

}

function initializeData() {
    if (sessionStorage.getItem('cur_menu_index') === null) {
        sessionStorage.setItem('cur_menu_index', 0);
    }
    if (sessionStorage.getItem('cur_detail_index') === null) {
        sessionStorage.setItem('cur_detail_index', 0);
    }
}

function showManDetailInfo(manInfo, id) {
    if (!getAuthorizationStatus()) {
        showAuthRequire();
        return;
    }
    console.log(manInfo)
    $("#confirm_dialog").html(manDetailTemplate(manInfo, id));
    $("#confirm_dialog").css({'top': '35%', 'left': '10%', 'right': '10%'});
    $("#confirm_dialog").modal();
    // showModalToCenter('confirm_dialog');
}

function hideModal() {
    $("#confirm_dialog").modal('hide');
}

// display advertise images on the slider
function display_advertise_images() {
    var carousel_content_html = "";
    if (data.advertise_imgs != undefined) {
        for (var i = 0; i < data.advertise_imgs.length; i++) {
            carousel_content_html += '<div class="owl-item">'
                + '<img src="' + getImageURL(data.advertise_imgs[i]) + '"></div>';
        }
    }
    $('#advertise_header').html(carousel_content_html);

    $('#advertise_header').owlCarousel({
        autoPlay: false
    });
    display_product_infos(pageItemId);
}

// display menu items on the horizontal menu bar
function display_menu_infos() {
}

function showAmountDlg() {
    $('#top_dialog').html(activityAmountSettingTemplate(data.cur_product));
    $('#top_dialog').css({'bottom': 0, 'top': 'auto'});
    $('#top_dialog').modal();
    //var imgData = '<div class="overlay-image"><img src="' + 'assets/images/logo.png' + '"></div>';
    setTimeout(function () {
        $('body').removeClass('modal-open');
    }, 100);
}

function hideAmountModal() {
    $('#top_dialog').modal('hide');
}

function showFavouriteStatus() {
    if (getFavouriteStatus(pageItemId) == false) {
        $("#bottom_item_text2").html('收藏')
        $("#bottom_item_image2").attr('src', 'assets/images/product_tabbar_icon2_n@3x.png');
        $("#bottom_item_text2").attr('style', '');
    } else {
        $("#bottom_item_text2").html('已收藏')
        $("#bottom_item_image2").attr('src', 'assets/images/product_tabbar_icon2_d@3x.png');
        $("#bottom_item_text2").attr('style', 'color: #38abff');
    }
}

function selectBottomItem(index) {
    if (index != 1) {
        if ((!getRegisterStatus()) || (!getAuthorizationStatus())) {
            showAuthRequire();
            return;
        }
    }
    for (var i = 1; i < 6; i++) {
        if (i == index) {
            $("#bottom_item_image" + i).attr('src', 'assets/images/product_tabbar_icon' + i + '_d@3x.png');
            $("#bottom_item_text" + i).attr('style', 'color: #38abff');
        }
        else {
            $("#bottom_item_image" + i).attr('src', 'assets/images/product_tabbar_icon' + i + '_n@3x.png');
            $("#bottom_item_text" + i).attr('style', '');
        }
    }
    switch (index) {
        case 1:
            location.href = "provider_detail.php?iId=" + data.cur_product.provider_id;
            break;
        case 2:
            if (getFavouriteStatus(pageItemId) == false)
                sendAddFavouriteRequest(0, pageItemId)
            else
                sendRemoveFavouriteRequest(0, pageItemId)
            showFavouriteStatus();
            break;
        case 3:
            location.href = "mycart_manage.php";
            break;
        case 4:
            sessionStorage.setItem('addType', 1)
            showAmountDlg();
            break;
        case 5:
            sessionStorage.setItem('addType', 2)
            showAmountDlg();
            break;
    }
}

function addProductOrderCart(id) {
    var addType = parseInt(sessionStorage.getItem('addType'))
    var new_amount = parseInt($('#product_amount' + pageItemId).val());
    var max_amount = parseInt($('#max_amount' + pageItemId).val());
    if (addType == 1) {//add cart
        sendAddMyCartItemRequest(true,id,new_amount, max_amount);
        hideAmountModal();
    }
    else { // add order
        addToSessionOrder(id, new_amount, 1);
        hideAmountModal();
        location.href = 'order_apply.php?iId=\'' + pageItemId + '\'&iType=2';
    }
}

// display the product list on the content
function display_product_infos(index) {

    var product_content = activityDetailTemplate(data.cur_product);
    $("#product_container").html(product_content);
}

function resize_main_page() {
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = parseInt($('.page-footer').css('height'));
    var carousel_height = width / 1.55;
    var content_height = height - footer_height - carousel_height;
    var progress_width = width - 200;
    $('.product_container').css({'height': height - footer_height, 'top': 0});
    // $('.product_container').css({'height': content_height, 'top': carousel_height});

    //$('#advertise_header').css({'height': carousel_height});
    $('.owl-item img').css({'height': carousel_height});
    $('.commodity_progress').css({'width': progress_width});
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
        location.href = "user_login.php"
    else
        location.href = "user_register_detail.php"
}

function OnCancel() {
    $('#auth_question').modal('hide');
}