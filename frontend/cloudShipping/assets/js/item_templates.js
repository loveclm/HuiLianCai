// displaying item templates

function getTimeString(time_str) {
    date_str = new Date(time_str);
    var h = date_str.getHours();
    var i = date_str.getMinutes();
    var s = date_str.getSeconds();
    h = h >= 10 ? h : '0' + h;
    i = i >= 10 ? i : '0' + i;
    s = s >= 10 ? s : '0' + s;
    return ' ' + h + ' : ' + i + ' : ' + s;
}

function getPrice(price, isUnit) {
    if (isUnit == undefined) {
        if (price == "") return "¥0.00";
        if (getAuthorizationStatus() == true)
            return "¥" + parseFloat(price).toFixed(2);
        else return "?";
    } else {
        if (price == "") return "0.00";
        if (getAuthorizationStatus() == true) return parseFloat(price).toFixed(2);
        else return "?";
    }
}

function getDateTimeString(date_str) {

    if (date_str == 'undefined') return "";
    if (date_str == 'null') return "";
    if (date_str == '') return "";
    //date_str = new Date().toLocaleString();
    var tmp = date_str.split("-");
    date_str = "";
    for (var i = 0; i < tmp.length; i++) {
        date_str += i == 0 ? tmp[i] : "/" + tmp[i];
    }
    date_str = new Date(date_str);
    var y = date_str.getFullYear();
    var m = date_str.getMonth() + 1;
    var d = date_str.getDate();
    var h = date_str.getHours();
    var i = date_str.getMinutes();
    var s = date_str.getSeconds();
    m = m >= 10 ? m : "0" + m;
    d = d >= 10 ? d : "0" + d;
    h = h >= 10 ? h : "0" + h;
    i = i >= 10 ? i : "0" + i;
    s = s >= 10 ? s : "0" + s;
    return y + "-" + m + "-" + d + " " + h + ":" + i + ":" + s;
}

function getImageURL(url) {
    if (url == '') return 'assets/images/shop_logo.png';
    return (HLC_APP_MODE != HLC_SIMUL_MODE) ? (REMOTE_API_URL + url) : url
}

function mainProductItemTemplate(item) {
    var btn_string = '加入购物车';
    var item_progress = parseInt((item['man_info'].length) / parseInt(item['mans']) * 100);

    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity">';
    tmp_content_html += '<div class="commodity_header">';
    tmp_content_html += '<h5>距拼团結束还有' + getTimeString(item['end_time']) + '</h5>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showProductDetailInfo(' + item['id'] + ')">';
    tmp_content_html += '<img src="' + item['product_image'] + '">';

    tmp_content_html += '<div class="commodity_information">';

    tmp_content_html += '<h5>' + item['product_name'] + '</h5>';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += '<h5>';
    tmp_content_html += '<span class="commodity_attr detail_left">';
    tmp_content_html += item['info_size'] + '</span>';
    tmp_content_html += '<span class="commodity_attr detail_right">';
    tmp_content_html += item['info_box'] + '</span></h5></div>';

    tmp_content_html += '<div class="commodity_progress">';
    tmp_content_html += '<div>';
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html += '<div class="progress-bar progress-bar-success"';
    tmp_content_html += ' role="progressbar" aria-valuenow="' + item_progress;
    tmp_content_html += '" aria-valuemin="0" aria-valuemax="100" style="width: ';
    tmp_content_html += item_progress + '%;">';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="progress-text">' + item_progress + '%</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<span class="detail_left"><strike>' + getPrice(item['old_price']);
    tmp_content_html += '</strike></span>';
    tmp_content_html += '<span class="detail_right">' + getPrice(item['new_price']) + '</span>';
    tmp_content_html += '</div></div>';

    tmp_content_html += '<div class="commodity_button">';
    tmp_content_html += '<div onclick="purchase_again_Order(' + item['id'] + ')">';
    tmp_content_html += btn_string + '</div></div>';

    tmp_content_html += '</div></div>';

    tmp_content_html += '</div>';
    return tmp_content_html;
}

function productDetailTemplate(productItem) {

    var product_desc = '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款';
    var item_progress = parseInt((productItem['man_info'].length) / parseInt(productItem['mans']) * 100);

    var tmp_content_html = "";
    tmp_content_html += '<div class="product_item">';
    tmp_content_html += '<div class="product_header">';
    tmp_content_html += '<h5>' + productItem['product_name'] + '</h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="product_attr">';
    tmp_content_html += '<h5><span class="detail_left">距离拼团結束还有';
    tmp_content_html += getTimeString(productItem['end_time']) + '</span>';
    tmp_content_html += '<span class="detail_right">';
    tmp_content_html += productItem['mans'] + '人团<i class="fa fa-circle"></i>' + productItem['amount'] + '件起团';
    tmp_content_html += '</span></h5></div>';
    tmp_content_html += '<div class="product_progress">';
    tmp_content_html += '<div class="product_price">';
    tmp_content_html += '<h5><span class="detail_left">' + getPrice(productItem['new_price']) + '</span>';
    tmp_content_html += '<span class="detail_right"><strike>' + getPrice(productItem['old_price']);
    tmp_content_html += '</strike></span></h5></div>';

    tmp_content_html += '<div class="commodity_progress"><div>';
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html += '<div class="progress-bar progress-bar-success" role="progressbar"';
    tmp_content_html += ' aria-valuenow="' + item_progress + '" aria-valuemin="0"';
    tmp_content_html += ' aria-valuemax="100" style="width: ' + item_progress + '%;">';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="progress-text">' + item_progress + '%</div>';
    tmp_content_html += '</div></div></div></div></div>';

    ManInfo = productItem['man_info'];
    if (ManInfo.length > 0) {
        tmp_content_html += '<div class="man_info">';

        for (var i = 0; i < ((ManInfo.length > 2) ? 2 : ManInfo.length); i++) {
            var item = ManInfo[i];
            tmp_content_html += '<img src="' + item['image'] + '" ';
            tmp_content_html += " onclick='showManDetailInfo(";
            tmp_content_html += JSON.stringify(ManInfo) + ",\"" + item['id'] + "\");'>";
        }
        tmp_content_html += '<h5>还差 ';
        tmp_content_html += '<span>' + (productItem['mans'] - ManInfo.length) + '</span>';
        tmp_content_html += '件即可拼图成功，';
        tmp_content_html += getTimeString(productItem['end_time']) + ' 后结束</h5></div>';
    }

    tmp_content_html += '<div class="product_info"><div class="product_attr">';
    tmp_content_html += '<h5>拼单须知</h5></div>';
    tmp_content_html += '<h5>' + product_desc + '</h5></div>';

    TotalInfo = productItem['total_info'];
    if (TotalInfo.length > 0) {
        tmp_content_html += '<div class="product_info"><div class="product_attr">';
        tmp_content_html += '<h5>组合明细</h5></div>';
        tmp_content_html += '<table class="product_table"><tbody>';
        tmp_content_html += '<tr><th>商品名称</th><th>数量</th><th>总价</th><tr>';
        for (var i = 0; i < TotalInfo.length; i++) {
            var item = TotalInfo[i];
            tmp_content_html += '<tr>';
            tmp_content_html += '<td>' + item['product_name'] + '</td>';
            tmp_content_html += '<td>' + item['amount'] + '</td>';
            tmp_content_html += '<td>' + getPrice(item['price']) + '</td>';
        }
        tmp_content_html += '</tbody></table>';
        tmp_content_html += '</div>';
    }

    tmp_content_html += '<div class="product_info"><div class="product_attr">';
    tmp_content_html += '<h5>商品详情</h5></div>';
    tmp_content_html += '<div class="product_detail_html">' + productItem['text_html'];
    tmp_content_html += '</div></div>';
    return tmp_content_html;
}

function productAmountSettingTemplate(productItem) {

    var id = productItem['id'];
    var tmp_content_html = '';
    tmp_content_html += '<div class="modal-body">';
    tmp_content_html += '<div class="close_button" ';
    tmp_content_html += 'onclick="hideAmountModal();">';
    tmp_content_html += '<img src="assets/images/close@3x.png"></div>';
    tmp_content_html += '<div class="product_item">';
    tmp_content_html += '<div class="product_price"><h5>';
    tmp_content_html += '<span class="detail_left">' + productItem['new_price'] + '</span>';
    tmp_content_html += '<span class="detail_right">';
    tmp_content_html += '<strike>' + productItem['old_price'] + '</strike></span>';
    tmp_content_html += '</h5></div>';
    tmp_content_html += '<div class="product_price"><h5>库存' + productItem['amount'] + '件';
    tmp_content_html += '</h5></div></div>';
    tmp_content_html += '<div class="product_amount"><h5>选择数量</h5>';
    tmp_content_html += '<button class="btn-left" onclick="decreaseAmount(' + id + ')">-</button>';
    tmp_content_html += '<input id="product_amount' + id + '" value="' + productItem['min_amount'] + '" ';
    tmp_content_html += 'oninput="validateAmount(' + id + ')">';
    tmp_content_html += '<input id="min_amount' + id + '" value="' + productItem['min_amount'] + '" ';
    tmp_content_html += 'style="display: none;">';
    tmp_content_html += '<input id="max_amount' + id + '" value="' + (parseInt(productItem['amount']) - parseInt(productItem['cur_amount'])) + '" ';
    tmp_content_html += 'style="display: none;">';
    tmp_content_html += '<button class="btn-right" onclick="increaseAmount(' + id + ')">+</button>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<button class="btn_confirm" ';
    tmp_content_html += 'onclick="hideAmountModal();">确定</button>';
    tmp_content_html += '<img class="overlay-image" src="' + productItem['product_image'] + '">';

    return tmp_content_html;
}

function myOrderItemTemplate(productItem, status) {

    productItem = {
        'id': '15',
        'number': '652458136868586',
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
        'status': Math.floor(Math.random() * 5) + 1,//1-waiting pay, 2-waiting groupping, 3-waiting sending,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'amount': 3,

    };

    if (status != 0 && productItem['status'] != status) return "";

    var status_class_List = ['waiting', 'waiting', 'waiting', 'completed', 'closed', 'closed'];
    var status_string_List = ['待付款', '待成团', '待发货', '交易完成', '已退款', '交易关闭'];

    var tmp_content_html = "";
    tmp_content_html += '<div class="order ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += '<h5>订单编号 : ' + productItem['number'] + '</h5>';
    tmp_content_html += '<h5 class="order_state">' + status_string_List[productItem['status'] - 1] + '</h5>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showOrderDetailInfo(' + productItem['status'] + ')">';
    tmp_content_html += '<img src="' + productItem['image'] + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += '<h5>' + productItem['name'] + '</h5>';
    tmp_content_html += '<div class="product_price">';
    tmp_content_html += '<h5><span class="detail_left">' + productItem['new_price'] + '</span>';
    tmp_content_html += '<span class="detail_right"><strike>' + productItem['old_price'];
    tmp_content_html += '</strike></span></h5></div>';
    tmp_content_html += '<h5>数量 :  <b>' + productItem['amount'] + '</b></h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += 'style="height:' + (parseInt(productItem['status']) >= 3 ? '25' : '55') + 'px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + productItem['amount'] + '件商品, 总价 : &nbsp;';
    tmp_content_html += '<span class="detail_right">' + productItem['new_price'] + '</span>';
    tmp_content_html += '</h5>';

    if (parseInt(productItem['status']) < 3) {
        switch (parseInt(productItem['status'])) {
            case 1:
                tmp_content_html += '<div onclick="pay_for_Order(' + productItem['id'] + ')"><h5>&nbsp;&nbsp;&nbsp;付款&nbsp;&nbsp;&nbsp;&nbsp;</h5></div>';
                tmp_content_html += '<div class="disabled" onclick="cancelOrder(' + productItem['id'] + ')"><h5>取消订单</h5></div>';
                break;
            case 2:
                tmp_content_html += '<div onclick="cancelOrder(' + productItem['id'] + ')"><h5>取消订单</h5></div>';
                break;
        }
    }
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';


    return tmp_content_html;
}

function myOrderDetailTemplate(productItem, status) {

    productItem = {
        'id': '15',
        'number': '652458136868586',
        'user_name': '张某某',
        'user_phone': '18234857557',
        'user_addr': '北京朝阳区芳香园12区5号楼502室',
        'products': [
            {
                'id': '151',
                'name': '态有机原生态有',
                'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                'old_price': Math.floor(Math.random() * 100).toFixed(2),
                'new_price': Math.floor(Math.random() * 100).toFixed(2),
                'status': 1,// 1-paid, 2-unpaid
                'amount': 2
            },
            {
                'id': '171',
                'name': '态有机原生态有',
                'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
                'old_price': Math.floor(Math.random() * 100).toFixed(2),
                'new_price': Math.floor(Math.random() * 100).toFixed(2),
                'status': 2,// 1-paid, 2-unpaid
                'amount': 3
            }
        ],
        'status': 3,//Math.floor(Math.random() * 5)+1,
        // 1-waiting payment, 2-waiting groupping, 3-waiting distribution,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
        'pay_type': 1,
        'pay_wallet': '25.00',
        'pay_price': '20.00',
        'ordered_time': getDateTimeString(new Date()),//("Y-m-d H:i:s"),
        'closed_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'paid_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'refunded_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'distributed_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'dist_name': '王小二',
        'dist_phone': '18452132614',

    };
    var iconImage = ['assets/images/address@3x.png', 'assets/images/goods@3x.png'];
    var payType = ['线上支付', '货到付款'];
    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    //if(status!=0 && productItem['status']!=status) return "";

    var status_class_List = ['waiting', 'waiting', 'waiting', 'completed', 'closed', 'closed'];
    var status_string_List = ['待付款', '待成团', '待发货', '交易完成', '已退款', '交易关闭'];

    var tmp_content_html = '';
    if (productItem['status'] == 1) {
        tmp_content_html += '<div class="counting">';
        tmp_content_html += '<h5>剩余 ' + hrs + '时 ' + mins + '分 ' + secs + '秒，该交易自动关闭</h5>';
        tmp_content_html += '</div>';
    }

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += '<h5>订单编号 : ' + productItem['number'] + '</h5>';
    tmp_content_html += '<h5 class="order_state">' + status_string_List[productItem['status'] - 1] + '</h5>';
    tmp_content_html += '</div></div>';

    tmp_content_html += '<div class="order title ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img src="' + iconImage[0] + '">';
    tmp_content_html += '<div class="detail">';
    tmp_content_html += '<h5><span class="">' + productItem['user_name'] + '</span>';
    tmp_content_html += '<span class="">' + productItem['user_phone'];
    tmp_content_html += '</span></h5>';
    tmp_content_html += '<h5><span>' + productItem['user_addr'] + '</span></h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情</span></h5>';
    var amount = 0;
    var new_price = 0;
    for (var i = 0; i < productItem['products'].length; i++) {
        var item = productItem['products'][i];
        amount += parseInt(item['amount']);
        tmp_content_html += '<div class="commodity_body">';
        tmp_content_html += '<img src="' + item['image'] + '"';
        tmp_content_html += ' onclick="showProductDetailInfo(' + item['id'] + '0)">';
        tmp_content_html += '<div class="commodity_detail">';
        tmp_content_html += '<h5>' + item['name'] + '</h5>';
        tmp_content_html += '<div class="product_price">';
        if (item['status'] == 2) { // 2-unpaid
            item['new_price'] = '0.00';
            item['old_price'] = '';
        } else { // 1-paid
            item['new_price'] = item['new_price'];
            item['old_price'] = '¥' + item['old_price'];
        }
        new_price += parseFloat(item['new_price']) * parseInt(item['amount']);
        tmp_content_html += '<h5><span class="detail_left">¥' + item['new_price'] + '</span>';
        tmp_content_html += '<span class="detail_right">';

        if (item['status'] == 2) // 2-unpaid
            tmp_content_html += '<a href="#" onclick="location.href=\'order_apply.php\';">赠品</a>';
        else // 1-paid
            tmp_content_html += '<strike>' + item['old_price'] + '</strike>';


        tmp_content_html += '</span>';
        tmp_content_html += '</h5></div>';
        tmp_content_html += '<h5>数量 :  <b>' + item['amount'] + '</b></h5>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
    }

    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += 'style="height:40px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + amount + '件商品, 总价 : &nbsp;';
    tmp_content_html += '<span class="detail_right">¥' + new_price.toFixed(2) + '</span>';
    tmp_content_html += '</h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><th>付款方式 : </th>';
    tmp_content_html += '<td>' + payType[productItem['pay_type'] - 1] + '</td></tr>';
    tmp_content_html += '<tr><th>买家留言 : </th>';
    tmp_content_html += '<td>希望可以尽快发货哦!' + '</td></tr>';

    st = parseInt(productItem['status']);
    productItem['pay_wallet'] = (new_price - parseFloat(productItem['pay_price'])).toFixed(2);
    console.log((st));
    switch (st) {
        case 6: // transaction closed
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            tmp_content_html += '<tr><th>交易关闭时间 : </th>';
            tmp_content_html += '<td>' + productItem['closed_time'] + '</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            break;
        case 4: // transaction completed
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_wallet'] + '元</td></tr>';
            tmp_content_html += '<tr><th>微信支付 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_price'] + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '<tr><th>送达时间 : </th>';
            tmp_content_html += '<td>' + productItem['distributed_time'] + '</td></tr>';
            tmp_content_html += '<tr><th>配送员 : </th>';
            tmp_content_html += '<td>' + productItem['dist_name'] + '';
            tmp_content_html += '<span>' + productItem['dist_phone'] + '</span></td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            break;
        case 5: // transaction refunded
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_wallet'] + '元</td></tr>';
            tmp_content_html += '<tr><th>微信支付 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_price'] + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '<tr><th>申请退款时间 : </th>';
            tmp_content_html += '<td>' + productItem['distributed_time'] + '</td></tr>';
            tmp_content_html += '<tr><th>退款时间 : </th>';
            tmp_content_html += '<td>' + productItem['refunded_time'] + '</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            break;
        case 1: // payment waiting
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            tmp_content_html += '<div><h5><br><br></h5></div>';
            if (productItem['pay_type'] == '1') {
                tmp_content_html += '<button class="btn_confirm btn-grey" ';
                tmp_content_html += 'onclick="$(\'#menu_dialog\').modal(\'hide\');">取消订单</button>';
                tmp_content_html += '<button class="btn_confirm btn-cyan" ';
                tmp_content_html += 'onclick="$(\'#menu_dialog\').modal(\'hide\');">付款</button>';
            } else {
                tmp_content_html += '<button class="btn_confirm btn-grey" ';
                tmp_content_html += 'onclick="$(\'#menu_dialog\').modal(\'hide\');">取消订单</button>';
                tmp_content_html += '<button class="btn_confirm btn-cyan" ';
                tmp_content_html += 'onclick="location.href=\'my_success.php?pageid=2\'">付款</button>';
            }

            break;
        case 3: // payment waiting
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_wallet'] + '元</td></tr>';
            tmp_content_html += '<tr><th>微信支付 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_price'] + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            tmp_content_html += '<div><h5><br><br></h5></div>';

            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<div class="pay-button">';
                tmp_content_html += '<div class="disabled" onclick="cancelOrder(';
                tmp_content_html += productItem['id'] + ')"><h5>退款</h5></div></div>';
            } else {
                tmp_content_html += '<div class="pay-button">';
                tmp_content_html += '<div class="disabled" onclick="cancelOrder(';
                tmp_content_html += productItem['id'] + ')"><h5>取消订单</h5></div></div>';
            }

            break;
        case 2: // payment waiting
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_wallet'] + '元</td></tr>';
            tmp_content_html += '<tr><th>微信支付 : </th>';
            tmp_content_html += '<td class="price">' + productItem['pay_price'] + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            tmp_content_html += '<div><h5><br><br></h5></div>';


            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<div class="pay-button">';
                tmp_content_html += '<div class="disabled" onclick="cancelOrder(';
                tmp_content_html += productItem['id'] + ')"><h5>退款</h5></div></div>';
            } else {
                tmp_content_html += '<div class="pay-button">';
                tmp_content_html += '<div class="disabled" onclick="cancelOrder(';
                tmp_content_html += productItem['id'] + ')"><h5>取消订单</h5></div></div>';
            }

            break;
    }

    return tmp_content_html;
}

function myOrderApplyTemplate(productItem, status) {

    var products = [
        {
            'id': '151',
            'name': '态有机原生态有',
            'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'status': 1,// 1-paid, 2-unpaid
            'amount': 2
        },
    ];
    productItem = {
        'id': '15',
        'number': '652458136868586',
        'user_name': '张某某',
        'user_phone': '18234857557',
        'user_addr': '北京朝阳区芳香园12区5号楼502室',
        'products': products,
        'status': status,
        // 1-waiting payment, 2-waiting groupping, 3-waiting distribution,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
        'pay_type': 2,
        'pay_wallet': '25.00',
        'pay_price': '20.00',
        'pay_wallet_rest': '5.00',
        'ordered_time': getDateTimeString(new Date()),//("Y-m-d H:i:s"),
        'closed_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'paid_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'refunded_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'distributed_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'dist_name': '王小二',
        'dist_phone': '18452132614',

    };
    var iconImage = [
        'assets/images/address@3x.png',
        'assets/images/goods@3x.png',
        'assets/images/choose_s_d@3x.png',
        'assets/images/choose_s_n@3x.png',
    ];
    var payType = ['线上支付', '货到付款'];
    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    //if(status!=0 && productItem['status']!=status) return "";

    var status_class_List = ['waiting', 'waiting', 'waiting', 'completed', 'closed', 'closed'];
    var status_string_List = ['待付款', '待成团', '待发货', '交易完成', '已退款', '交易关闭'];

    var tmp_content_html = '';

    tmp_content_html += '<div class="order title ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img src="' + iconImage[0] + '">';
    tmp_content_html += '<div class="detail">';
    tmp_content_html += '<h5><span class="">' + productItem['user_name'] + '</span>';
    tmp_content_html += '<span class="">' + productItem['user_phone'];
    tmp_content_html += '</span></h5>';
    tmp_content_html += '<h5><span>' + productItem['user_addr'] + '<span></h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情<span></h5>';
    var amount = 0;
    var new_price = 0;
    for (var i = 0; i < productItem['products'].length; i++) {
        var item = productItem['products'][i];
        amount += parseInt(item['amount']);
        tmp_content_html += '<div class="commodity_body">';
        tmp_content_html += '<img src="' + item['image'] + '"';
        tmp_content_html += ' onclick="showProductDetailInfo(' + item['id'] + '0)">';
        tmp_content_html += '<div class="commodity_detail">';
        tmp_content_html += '<h5>' + item['name'] + '</h5>';
        tmp_content_html += '<div class="product_price">';
        if (item['status'] == 2) { // 2-unpaid
            item['new_price'] = '0.00';
            item['old_price'] = '';
        } else { // 1-paid
            item['new_price'] = item['new_price'];
            item['old_price'] = '¥' + item['old_price'];
        }
        new_price += parseFloat(item['new_price']) * parseInt(item['amount']);
        tmp_content_html += '<h5><span class="detail_left">¥' + item['new_price'] + '</span>';
        tmp_content_html += '<span class="detail_right">';

        if (item['status'] == 2) // 2-unpaid
            tmp_content_html += '<a href="#" onclick="showOrderingProduct();">赠品</a>';
        else // 1-paid
            tmp_content_html += '<strike>' + item['old_price'] + '</strike>';

        tmp_content_html += '</span>';
        tmp_content_html += '</h5></div>';
        tmp_content_html += '<h5>数量 :  <b>' + item['amount'] + '</b></h5>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
    }

    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += 'style="height:40px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + amount + '件商品, 总价 : &nbsp;';
    tmp_content_html += '<span class="detail_right">¥' + new_price.toFixed(2) + '</span>';
    tmp_content_html += '</h5>';
    tmp_content_html += '</div>';

    tmp_content_html += '</div>';

    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><td>付款方式 : </td>';
    tmp_content_html += '<td class="paytype" onclick="selectPayType();">';

    tmp_content_html += '<label><input type="radio" name="paytype" value="0">';
    tmp_content_html += '<i  id="paytype0" class="fa fa-circle-o"></i>' + payType[0] + '</label>';
    tmp_content_html += '<label><input type="radio" name="paytype" value="1">';
    tmp_content_html += '<i  id="paytype1" class="fa fa-circle-o"></i>' + payType[1] + '</label>';

    tmp_content_html += '</td></tr>';
    tmp_content_html += '</table>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="order detail bottom ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<div>优惠券</div>';
    tmp_content_html += '<img class="title_img" src="' + iconImage[2] + '">';
    tmp_content_html += '<h5 class="title_text"><span>满300减30<span></h5>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="my-feedback order">';
    tmp_content_html += '<textarea id="textarea" class="form-control" ';
    tmp_content_html += 'placeholder="买家留言" oninput="validateText();"></textarea>';
    tmp_content_html += '<div class="detail_right">';
    tmp_content_html += '<h5 id="textLength">0/100</h5></div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div><h5><br><br><br><br><br><br></h5></div>';

    productItem['pay_wallet_rest'] = (parseFloat(productItem['pay_wallet']) - parseFloat(new_price)).toFixed(2);
    tmp_content_html += '<div class="order apply">';

    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><td>已扣除钱包余额 : <span>¥' + productItem['pay_wallet'] + '</span>';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '<tr><td>&nbsp;';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '<tr><td>应付 : <span>¥' + new_price.toFixed(2) + '</span>';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '<tr><td>拼单成功后节省 : <span>¥' + productItem['pay_wallet_rest'] + '</span>';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '</table>';
    tmp_content_html += '</div>';

    tmp_content_html += '<button class="btn_confirm btn-cyan" ';
    tmp_content_html += 'onclick="$(\'#menu_dialog\').modal(\'hide\');">提交订单</button>';

    tmp_content_html += '</div>';

    return tmp_content_html;
}

function myGroupingItemTemplate(productItem, status) {


    productItem = {
        'id': '15',
        'number': '652458136868586',
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
        'status': Math.floor(Math.random() * 3) + 1,//1-waiting pay, 2-waiting groupping, 3-waiting sending,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'amount': 3,

    };

    if (status != 0 && productItem['status'] != status) return "";

    var status_class_List = ['waiting', 'completed', 'failed'];
    var status_string_List = ['待成单', '已拼团', '拼团失败'];

    var tmp_content_html = "";
    tmp_content_html += '<div class="order ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += '<h5>订单编号 : ' + productItem['number'] + '</h5>';
    tmp_content_html += '<h5 class="order_state">' + status_string_List[productItem['status'] - 1] + '</h5>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showGroupingDetailInfo(' + productItem['id'] + '0)">';
    tmp_content_html += '<img src="' + productItem['image'] + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += '<h5>' + productItem['name'] + '</h5>';
    tmp_content_html += '<div class="product_price">';
    tmp_content_html += '<h5><span class="detail_left">' + productItem['new_price'] + '</span>';
    tmp_content_html += '<span class="detail_right"><strike>' + productItem['old_price'];
    tmp_content_html += '</strike></span></h5></div>';
    tmp_content_html += '<h5>数量 :  <b>' + productItem['amount'] + '</b></h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += 'style="height:' + (parseInt(productItem['status']) > 3 ? '25' : '55') + 'px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + productItem['amount'] + '件商品, 总价 : &nbsp;';
    tmp_content_html += '<span class="detail_right">' + productItem['new_price'] + '</span>';
    tmp_content_html += '</h5>';

    if (parseInt(productItem['status']) <= 3) {
        switch (parseInt(productItem['status'])) {
            case 1:
                tmp_content_html += '<div onclick="cancelOrder(' + productItem['id'] + ')"><h5>查看订单详情</h5></div>';
                tmp_content_html += '<div onclick="pay_for_Order(' + productItem['id'] + ')"><h5>查看拼团详情</h5></div>';
                break;
            case 2:
                tmp_content_html += '<div onclick="cancelOrder(' + productItem['id'] + ')"><h5>查看订单详情</h5></div>';
                tmp_content_html += '<div onclick="pay_for_Order(' + productItem['id'] + ')"><h5>查看拼团详情</h5></div>';
                break;
            case 3:
                tmp_content_html += '<div onclick="cancelOrder(' + productItem['id'] + ')"><h5>查看订单详情</h5></div>';
                tmp_content_html += '<div onclick="pay_for_Order(' + productItem['id'] + ')"><h5>查看拼团详情</h5></div>';
                break;
        }
    }
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';


    return tmp_content_html;
}

function myGroupingDetailTemplate(productItem, status) {

    var manInfos = [
        {
            'id': '101',
            'image': 'assets/images/tmp/i2.png',
            'name': '张二明',
            'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
        },
        {
            'id': '102',
            'image': 'assets/images/tmp/i1.png',
            'name': '王万红',
            'ordered_time': getDateTimeString('2017/1/6 13:30:25'),
        }
    ];
    productItem = {
        'id': '15',
        'number': '652458136868586',
        'user_name': '张某某',
        'user_phone': '18234857557',
        'user_addr': '北京朝阳区芳香园12区5号楼502室',
        'name': '特级 优质 原生态有',
        'man_info': manInfos,
        'status': status,//Math.floor(Math.random() * 5)+1,
        // 1-waiting payment, 2-waiting groupping, 3-waiting distribution,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
        'pay_type': 1,
        'ordered_time': getDateTimeString(new Date()),//("Y-m-d H:i:s"),
        'closed_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'paid_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'refunded_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'distributed_time': getDateTimeString(new Date()),//time("Y-m-d H:i:s"),
        'recom_name': '王小二',
        'recom_phone': '18452132614',
        'progress': '100',

    };
    var statusList = ['2', '2', '7', '8'];
    var iconImage = {
        '2': 'assets/images/pd_loading@3x.png',
        '7': 'assets/images/pd_success@3x.png',
        '8': 'assets/images/pd_fail@3x.png',
    };
    var payType = ['线上支付', '货到付款'];
    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    //if(status!=0 && productItem['status']!=status) return "";

    var status_message_List = [
        '还未满足成单条件，请您耐心等候！',
        '还未满足成单条件，请您耐心等候！',
        '恭喜您，拼团成功，系统将尽快退还多余金额！',
        '很遗憾，拼单失败，卖家将按照原价给您发货！'];
    var status_string_List = ['拼单中', '拼单中', '拼单成功', '拼单失败'];

    var tmp_content_html = '';

    tmp_content_html += '<div class="grouping">';
    tmp_content_html += '<div class="grouping_header">';
    tmp_content_html += '<h5><img src="' + iconImage[statusList[status]] + '">';
    tmp_content_html += '<span>' + status_string_List[status] + '</span></h5>';
    tmp_content_html += '<h5><span>' + status_message_List[status] + '</span></h5>';

    tmp_content_html += '<div class="commodity_progress">';
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html += '<div class="progress-bar progress-bar-success" role="progressbar"';
    tmp_content_html += ' aria-valuenow="' + productItem['progress'] + '" aria-valuemin="0"';
    tmp_content_html += ' aria-valuemax="100" style="width: ' + productItem['progress'] + '%;">';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="progress-text">' + productItem['progress'] + '%</div>';
    tmp_content_html += '</div></div>';

    ManInfo = productItem['man_info'];
    if (ManInfo.length > 0) {
        tmp_content_html += '<div class="man_info">';
        var item = '';
        for (var i = 0; i < ManInfo.length; i++) {
            item = ManInfo[i];
            tmp_content_html += '<img src="' + item['image'] + '" ';
            tmp_content_html += " onclick='showManDetailInfo(" + JSON.stringify(ManInfo) + ",\"" + item['id'] + "\");'>";
        }
        tmp_content_html += '</div>';
    }

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="grouping pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr onclick="showProductDetailInfo(\'' + productItem['id'] + '\')">';
    tmp_content_html += '<th>商品名称 : </th>';
    tmp_content_html += '<td>' + productItem['name'];
    tmp_content_html += '<span class="title_arrow"><i class="fa fa-angle-right"></i></span>';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '<tr><th>收货人 : </th>';
    tmp_content_html += '<td><span>' + productItem['user_name'] + '</span>';
    tmp_content_html += '<span>' + productItem['user_phone'] + '</span></td></tr>';
    tmp_content_html += '<tr><th>收货地址 : </th>';
    tmp_content_html += '<td>' + productItem['user_addr'] + '</td></tr>';
    tmp_content_html += '<tr><th>发起拼单时间 : </th>';
    tmp_content_html += '<td>' + productItem['distributed_time'] + '</td></tr>';
    tmp_content_html += '</div>';
    if (productItem['progress'] == '100') {
        setTimeout(function () {
            $('.progress-bar.active, .progress.active .progress-bar').css({
                'background': '#6bbdff'
            });
        }, 10);
    }

    return tmp_content_html;
}

function manDetailTemplate(manInfos, id) {

    var iconImage = ['assets/images/close@3x.png'];

    var tmp_content_html = '';
    tmp_content_html += '<div class="grouping mans">';
    tmp_content_html += '<div class="close_button" ';
    tmp_content_html += 'onclick="hideModal();">';
    tmp_content_html += '<img src="assets/images/close@3x.png"></div>';
    tmp_content_html += '<div class="grouping_header">';
    for (var i = 0; i < manInfos.length; i++) {
        item = manInfos[i];
        if (item.id != id) continue;
        tmp_content_html += '<h5><img src="' + item.image + '"></h5>';
        tmp_content_html += '<h5>' + item.name + '</h5>';
        tmp_content_html += '<h5><span>' + getDateTimeString(item.ordered_time) + '参与了拼单</span></h5>';
        break;
    }
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="order detail">';
    for (var i = 0; i < manInfos.length; i++) {
        item = manInfos[i];
        if (item.id == id) continue;
        tmp_content_html += '<img class="title_img" src="' + item.image + '">';
        tmp_content_html += '<h5 class="title_text">' + item.name;
        tmp_content_html += '<span>' + getDateTimeString(item.ordered_time) + '加入了拼单</span></h5><br>';
    }
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';


    return tmp_content_html;
}

function noItemsTemplate(status) {

    var tmp_content_html = '';
    tmp_content_html += '<div class="order_no_items">';
    tmp_content_html += '<img src="assets/images/face@3x.png">';
    tmp_content_html += '<h5>';
    switch (status) {
        case 1:
            tmp_content_html += '暂无订单信息';
            break;
        case 2:
            tmp_content_html += '暂无拼团信息';
            break;
        case 3:
            tmp_content_html += '暂无消息';
            break;
        case 4:
            tmp_content_html += '购物车空空如也, 快去逛逛吧!';
            break;

    }
    tmp_content_html += '</h5>';
    tmp_content_html += '</div>';
    return tmp_content_html;
}

function myIntegralTemplate(status) {

    var tmp_content_html = '';
    tmp_content_html += '<div class="integral-item">';
    tmp_content_html += '<div class="border">';
    tmp_content_html += '<h5 class="title"><span>积分规则</span></h5>';
    tmp_content_html += '<h5>1、每实际消费1元积1分</h5>';
    tmp_content_html += '<h5>2、根据积分等级定期可以参与优惠活动</h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';
    return tmp_content_html;
}

function myMenuItemsTemplate(menuInfo, status) {

    var tmp_content_html = '';
    if (menuInfo['name'] == '退出登录')
        tmp_content_html += '<div class="divider-line"></div>';
    tmp_content_html += '<div class="order detail menu-item" onclick="selectMenu(';
    tmp_content_html += menuInfo['id'] + ')">';
    if (menuInfo['icon'] != undefined)
        tmp_content_html += '<img class="title_img" src="' + menuInfo['icon'] + '">';
    tmp_content_html += '<h5 class="title_text"><span>' + menuInfo['name'] + '<span></h5>';
    tmp_content_html += '<h5 class="title_arrow"><i class="fa fa-angle-right"></i></h5>';
    tmp_content_html += '</div>';
    return tmp_content_html;
}

function myCouponItemsTemplate(couponInfo, status) {
    couponInfo = {
        'image': 'assets/images/discountCoupon@3x.png'
    };
    var tmp_content_html = '';
    tmp_content_html += '<img class="" src="' + couponInfo['image'] + '">';
    return tmp_content_html;
}

function myTransactionItemsTemplate(transInfo) {
    transInfo = {
        'title': '购买商品',
        'price': (Math.floor(Math.random() * 100) + 1).toFixed(2),
        'trans_time': getDateTimeString(new Date()),
        'type': Math.floor(Math.random() * 2), // 1-paid(-),   2-refunded(+)
        'content': '拼单康师傅方便面',
    };
    var type = parseInt(transInfo['type']);
    var tmp_content_html = '';
    tmp_content_html += '<div class="trans-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><th style="color: black;">' + transInfo['content'] + '</th>';
    tmp_content_html += '<td ' + (type == 1 ? 'style="color:#38abff">-' : 'style="color:#ff6000">+')
        + transInfo['price'] + '</td></tr>';
    tmp_content_html += '<tr><th>' + transInfo['trans_time'] + '</th>';
    tmp_content_html += '<td>' + transInfo['title'] + '</td></tr>';
    tmp_content_html += '</table>';
    tmp_content_html += '</div>';

    return tmp_content_html;
}

function myCollectionItemTemplate(productItem) {
    productItem = {
        'id': '15',
        'end_time': '15 : 23 : 30',
        'product_image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
        'product_name': '可跳转到收货地址',
        'info_size': '1g*12/箱',
        'info_amount': '500箱起拼',
        'progress': Math.floor(Math.random() * 85 + 16),
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'button': '加入购物车'
    };

    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity my-collection">';
    tmp_content_html += '<div class="commodity_header">';
    tmp_content_html += '<h5>距离拼团結束还有' + productItem['end_time'] + '</h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showProductDetailInfo(' + productItem['id'] + '0)">';
    tmp_content_html += '<img src="' + productItem['product_image'] + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += '<h5>' + productItem['product_name'] + '</h5></div>';
    tmp_content_html += '<div class="commodity_progress">';
    tmp_content_html += '<span class="detail_left">' + productItem['new_price'] + '</span>';
    tmp_content_html += '<span class="detail_right"><strike>' + productItem['old_price'];
    tmp_content_html += '</strike></span>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div></div>';
    return tmp_content_html;
}

function myProviderItemTemplate(providerItem) {
    providerItem = {
        'id': '15',
        'provider_image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
        'provider_name': '可跳转到收货地址',
    };

    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity my-provider">';
    //tmp_content_html += ' onclick="showProductDetailInfo(' + providerItem['id'] + '0)">';
    tmp_content_html += '<img src="' + providerItem['provider_image'] + '">';
    tmp_content_html += '<h5>' + providerItem['provider_name'] + '</h5>';
    tmp_content_html += '</div>';
    return tmp_content_html;
}

function myStoreInfoTemplate(storeItem, status) {
    storeItem = {
        'id': '15',
        'number': '652458136862456256245624568',
        'user_name': '张某某',
        'user_phone': '18234857557',
        'user_addr': '北京朝阳区芳香园12区',
        'auth_image': 'assets/images/tmp/authcard.png'
    };
    var iconImage = [
        'assets/images/phone@3x.png',
        'assets/images/name@3x.png',
    ];

    //if(status!=0 && productItem['status']!=status) return "";

    var tmp_content_html = '';

    tmp_content_html += '<div class="store">';
    tmp_content_html += '<div class="store_info left">';
    tmp_content_html += '<h5><img src="' + iconImage[0] + '"><h5>';
    tmp_content_html += '<span>' + storeItem['user_phone'] + '</span>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="store_info">';
    tmp_content_html += '<h5><img src="' + iconImage[1] + '" style="width: 30px;"><h5>';
    tmp_content_html += '<span>' + storeItem['user_name'] + '</span>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="store pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><th>地址 : </th>';
    tmp_content_html += '<td>' + storeItem['user_addr'] + '</td></tr>';
    tmp_content_html += '<tr><th>营业执照编号 : </th>';
    tmp_content_html += '<td>' + storeItem['number'] + '</td></tr>';
    tmp_content_html += '</table>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="store auth_img">';
    tmp_content_html += '<h5><img src="' + storeItem['auth_image'] + '"><h5>';
    tmp_content_html += '</div>';
//    tmp_content_html += '<button class="store btn_confirm" ';
//    tmp_content_html += 'onclick="applyStoreAuthrization();">提交认证</button>';


    return tmp_content_html;
}

function successProcessingTemplate(status) {

    var tmp_content_html = '';
    tmp_content_html += '<div class="success_top">';
    tmp_content_html += '<img src="assets/images/pd_success@3x.png">';
    switch (status) {// 1-store activated, 2-
        case '1':
            tmp_content_html += '<h5 class="msg-body">';
            tmp_content_html += '提交成功，请耐心等待后台审核！';
            tmp_content_html += '</h5>';
            tmp_content_html += '<div class="btn_confirm" onclick="back();">返回</div>';
            break;
        case '2':
            tmp_content_html += '<h5 class="msg-body">';
            tmp_content_html += '提交成功，请耐心等待后台审核！';
            tmp_content_html += '</h5>';
            tmp_content_html += '<div class="btn_confirm" onclick="history.back();">返回</div>';
            tmp_content_html += '暂无拼团消息';
            break;
        case '3':
            tmp_content_html += '暂无消息';
            break;
        case '4':
            tmp_content_html += '购物车空空如也, 快去逛逛吧!';
            break;

    }
    tmp_content_html += '</div>';

    return tmp_content_html;
}

function mainNewsItemTemplate(newsInfo) {

    var newsInfo = {
        'content': 'Proin gravida dolor sit amet lacus accumsan Cum sociis natoque penatibus et magnis dis parturien',
        'sent_time': '2017/09/25 8:00:00'
    };
    var cur = new Date();
    var sent_time = new Date(newsInfo['sent_time']);
    var cy = cur.getFullYear();
    var cm = cur.getMonth() + 1;
    var cd = cur.getDate();
    var y = sent_time.getFullYear();
    var m = sent_time.getMonth() + 1;
    var d = sent_time.getDate();
    var h = sent_time.getHours();
    var i = sent_time.getMinutes();

    sent_time = Math.floor((cur.valueOf() - sent_time.valueOf()) / 1000 / 60);
    console.log(cy + '-' + cm + '-' + cd + ',' + y + '-' + m + '-' + d + ',' + sent_time);
    if (sent_time >= 0 && sent_time < 2) sent_time = '刚刚';
    else if (sent_time > 0 && sent_time < 20) sent_time = sent_time + '分钟前';
    else {
        sent_time = Math.floor(((new Date(cy, cm, cd)).valueOf() - (new Date(y, m, d)).valueOf()) / 1000);
        if (sent_time == 0) sent_time = '今天 ' + h + ':' + i;
        else if (sent_time == 3600 * 24) sent_time = '昨天 ' + h + ':' + i;
        else sent_time = y + '-' + m + '-' + d;
    }
    var tmp_content_html = '';
    tmp_content_html += '<div class="news-item" onclick="showMessage(\'';
    tmp_content_html += newsInfo['content'] + '\')">';
    tmp_content_html += '<h5>' + newsInfo['content'] + '</h5>';
    tmp_content_html += '<h5 class="time_text">' + sent_time + '</h5>';
    tmp_content_html += '</div>';
    return tmp_content_html;
}

function myCartItemTemplate(productItem, status) {

    var success_products = [
        {
            'id': '151',
            'name': '态有机原生态有态有生态有态有机原生态有',
            'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'status': 1,// 1-paid, 2-unpaid
            'amount': 2
        },
        {
            'id': '171',
            'name': '态有机原生态有',
            'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'status': 2,// 1-paid, 2-unpaid
            'amount': 3
        },
    ];
    var failed_products = [
        {
            'id': '16',
            'name': '态有机原生态有态有生',
            'image': 'assets/images/tmp/u2.png',
            'old_price': Math.floor(Math.random() * 100).toFixed(2),
            'new_price': Math.floor(Math.random() * 100).toFixed(2),
            'status': 1,// 1-paid, 2-unpaid
            'amount': 2
        },
    ];
    productItem = {
        'id': '15',
        'success_products': success_products,
        'failed_products': failed_products,
        'status': status,//Math.floor(Math.random() * 5)+1,
        // 1-waiting payment, 2-waiting groupping, 3-waiting distribution,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
        'pay_type': 2,
        'pay_wallet': '25.00',
        'pay_price': '20.00',
        'pay_wallet_rest': '5.00',
    };
    var iconImage = [
        'assets/images/choose_b_d@3x.png',
        'assets/images/choose_b_n@3x.png',
        'assets/images/choose_s_d@3x.png',
        'assets/images/choose_s_n@3x.png',
        'assets/images/failed_notice@2x.png',
    ];
    var payType = ['线上支付', '货到付款'];

    //if(status!=0 && productItem['status']!=status) return "";

    var status_class_List = ['waiting', 'waiting', 'waiting', 'completed', 'closed', 'closed'];
    var status_string_List = ['待付款', '待成团', '待发货', '交易完成', '已退款', '交易关闭'];

    var tmp_content_html = '';

    tmp_content_html += '<div class="order cart ' + status_class_List[productItem['status'] - 1] + '">';
    var amount = 0;
    var new_price = 0;
    for (var i = 0; i < productItem['success_products'].length; i++) {
        var item = productItem['success_products'][i];
        amount += parseInt(item['amount']);
        tmp_content_html += '<div class="commodity_body">';
        if (i == 0)
            tmp_content_html += '<img class="check_icon" src="' + iconImage[0] + '">';
        else
            tmp_content_html += '<div class="check_icon"></div>';
        tmp_content_html += '<img class="body_img" src="' + item['image'] + '"';
        tmp_content_html += ' onclick="showProductDetailInfo(' + item['id'] + '0)">';
        tmp_content_html += '<div class="commodity_detail">';
        tmp_content_html += '<h5>' + item['name'] + '</h5>';
        tmp_content_html += '<div class="product_price">';
        if (item['status'] == 2) { // 2-unpaid
            item['new_price'] = '0.00';
            item['old_price'] = '';
        } else { // 1-paid
            item['new_price'] = item['new_price'];
            item['old_price'] = '¥' + item['old_price'];
        }
        new_price += parseFloat(item['new_price']) * parseInt(item['amount']);
        tmp_content_html += '<h5><span class="detail_left">¥' + item['new_price'] + '</span>';
        tmp_content_html += '<span class="detail_right">';

        if (item['status'] == 2) // 2-unpaid
            tmp_content_html += '<a href="#" onclick="showOrderingProduct();">赠品</a>';
        else // 1-paid
            tmp_content_html += '<strike>' + item['old_price'] + '</strike>';

        tmp_content_html += '</span>';
        tmp_content_html += '</h5></div>';
        tmp_content_html += '<div class="product_amount">';
        tmp_content_html += '<button class="btn-right" onclick="increaseAmount(' + item['id'] + ')">+</button>';
        tmp_content_html += '<input id="product_amount' + item['id'] + '" value="' + item['amount'] + '" oninput="validateAmount(' + item['id'] + ')">';
        tmp_content_html += '<button class="btn-left" onclick="decreaseAmount(' + item['id'] + ')">-</button>';
        tmp_content_html += '<h5>数量 : </h5>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
    }
    tmp_content_html += '</div>';


    tmp_content_html += '<div class="order cart failed">';
    for (var i = 0; i < productItem['failed_products'].length; i++) {
        var item = productItem['failed_products'][i];

        // tmp_content_html += '<div class="status_notice">';
        // tmp_content_html += '已失败';
        // tmp_content_html += '</div>';

        tmp_content_html += '<img class="status_notice" src="' + iconImage[4] + '" ';
        tmp_content_html += ' onclick="showDeleteItem(' + item['id'] + ')">';
        tmp_content_html += '<div class="btn_delete" id="btn_delete' + item['id'] + '" ';
        tmp_content_html += ' onclick="DeleteItem(' + item['id'] + ')">';
        tmp_content_html += '<span>删除</span>';
        tmp_content_html += '</div>';

        tmp_content_html += '<div class="commodity_body" id="failedItem' + item['id'] + '" ';
        tmp_content_html += ' onclick="cancelDeleteItem(' + item['id'] + ')">';
        if (i == 0)
            tmp_content_html += '<img class="check_icon" src="' + iconImage[1] + '">';
        else
            tmp_content_html += '<div class="check_icon"></div>';
        tmp_content_html += '<img class="body_img" src="' + item['image'] + '"';
        tmp_content_html += ' onclick="showProductDetailInfo(' + item['id'] + '0)">';
        tmp_content_html += '<div class="commodity_detail">';
        tmp_content_html += '<h5>' + item['name'] + '</h5>';
        tmp_content_html += '<div class="product_price">';
        if (item['status'] == 2) { // 2-unpaid
            item['new_price'] = '0.00';
            item['old_price'] = '';
        } else { // 1-paid
            item['new_price'] = item['new_price'];
            item['old_price'] = '¥' + item['old_price'];
        }
        tmp_content_html += '<h5><span class="detail_left">¥' + item['new_price'] + '</span>';
        tmp_content_html += '<span class="detail_right">';

        if (item['status'] == 2) // 2-unpaid
            tmp_content_html += '<a href="#" onclick="showOrderingProduct();">赠品</a>';
        else // 1-paid
            tmp_content_html += '<strike>' + item['old_price'] + '</strike>';

        tmp_content_html += '</span>';
        tmp_content_html += '</h5></div>';
        tmp_content_html += '<div class="product_amount">';
        tmp_content_html += '<button class="btn-right" onclick="increaseAmount(' + item['id'] + ')">+</button>';
        tmp_content_html += '<input id="product_amount' + item['id'] + '" value="' + item['amount'] + '" oninput="validateAmount(' + item['id'] + ')">';
        tmp_content_html += '<button class="btn-left" onclick="decreaseAmount(' + item['id'] + ')">-</button>';
        tmp_content_html += '<h5>数量 : </h5>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
    }


    tmp_content_html += '</div>';


    tmp_content_html += '<div><h5><br><br><br><br><br><br></h5></div>';

    productItem['pay_wallet_rest'] = (parseFloat(productItem['pay_wallet']) - parseFloat(new_price)).toFixed(2);
    tmp_content_html += '<div class="order apply cart">';

    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr id="coupon_show"><td colspan="2">优惠券';
    tmp_content_html += '<img class="title_img" src="' + iconImage[3] + '">';
    tmp_content_html += '满300减30';

    tmp_content_html += '</td></tr>';
    tmp_content_html += '<tr><td colspan="2">已扣除钱包余额 :'
    tmp_content_html += '<span>¥' + productItem['pay_wallet'] + '</span>';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '<tr>';
    tmp_content_html += '<td rowspan="2">';
    tmp_content_html += '<img class="bottom_img" src="' + iconImage[3] + '">';
    tmp_content_html += '全选</td>';
    tmp_content_html += '<td style="padding: 0;">';
    tmp_content_html += '合计 :<span>¥' + new_price.toFixed(2) + '</span>';
    tmp_content_html += '<span class="subtext">(共0件)</span>';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '<tr><td style="padding: 0;">拼单成功后节省 :<span>¥'
    tmp_content_html += productItem['pay_wallet_rest'] + '</span>';
    tmp_content_html += '</td></tr>';
    tmp_content_html += '</table>';
    tmp_content_html += '</div>';

    tmp_content_html += '<button class="btn_confirm btn-cyan" ';
    tmp_content_html += 'onclick="$(\'#menu_dialog\').modal(\'hide\');">结算</button>';

    tmp_content_html += '</div>';

    return tmp_content_html;
}

function mainBottomItemTemplate(userType) {

    var menuItem = [
        {
            'id': '1',
            'name': '拼货',
            'image': 'assets/images/tabbar_icon1_d@3x.png',
            'image_n': 'assets/images/tabbar_icon1_n@3x.png'
        },
        {
            'id': '2',
            'name': '购物车',
            'image': 'assets/images/tabbar_icon2_d@3x.png',
            'image_n': 'assets/images/tabbar_icon2_n@3x.png'
        },
        {
            'id': '3',
            'name': '消息',
            'image': 'assets/images/tabbar_icon3_d@3x.png',
            'image_n': 'assets/images/tabbar_icon3_n@3x.png'
        },
        {
            'id': '4',
            'name': '我的',
            'image': 'assets/images/tabbar_icon4_d@3x.png',
            'image_n': 'assets/images/tabbar_icon4_n@3x.png'
        },
    ];
    var item;
    for (var i = 0; i < 4; i++) {
        item = menuItem[i];
        tmp_content_html += '<div class="bottom_item" ';
        tmp_content_html += ' onclick="selectBottomItem(' + item.id + ',0)">';
        tmp_content_html += '<img src="' + ((i == 0) ? item.image : item.image_n);
        tmp_content_html += '" id="bottom_item_image' + item.id + '">';
        tmp_content_html += '<h5 id="bottom_item_text' + item.id;
        tmp_content_html += '" style="color: ' + ((i == 0) ? '#38abff' : 'black') + '">';
        tmp_content_html += item.name + '</h5>';
        tmp_content_html += '</div>';
    }

    return tmp_content_html;
}

/*
Shipper-PMS
 */
function myShipperOrderItemTemplate(productItem, paymentType) {
    /*
    productItem = {
        'id': '15',
        'number': '652458136868586',
        'user_name': '张某某',
        'user_phone': '18234857557',
        'user_addr': '北京朝阳区芳香园12区5号楼502室',
        'payment_type':'',///0- online payment, 1 then offline payment
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'amount': 3,
    };
    */

    if (parseInt(productItem['status']) != 3) return '';
    if (parseInt(productItem['pay_type']) != paymentType) return '';

    var iconImage = ['assets/images/address@3x.png', 'assets/images/goods@3x.png'];

    var tmp_content_html = "";

    tmp_content_html += '<div class="order waiting">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += '<h5>订单编号 : ' + productItem['id'] + '</h5>';
    tmp_content_html += '<h5 class="order_state">待发货</h5>';
    tmp_content_html += '</div>';


    tmp_content_html += '<div class="order title waiting shipper-detail-title">';
    tmp_content_html += '<img src="' + iconImage[0] + '">';
    tmp_content_html += '<div class="detail">';
    tmp_content_html += '<h5><span class="">' + productItem['shop_contact'] + '</span>';
    tmp_content_html += '<span class="">' + productItem['shop_contact_phone'];
    tmp_content_html += '</span></h5>';
    tmp_content_html += '<h5><span>' + productItem['shop_address'] + '</span></h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    var totalPrice = 0;
    var amount = 0;

    for (var i = 0; i < productItem['products'].length; i++) {
        if (parseInt(productItem.group_success) != 2)
            totalPrice += parseFloat(productItem['products'][i]['new_price']) * parseInt(productItem['products'][i]['amount']);
        else
            totalPrice += parseFloat(productItem['products'][i]['old_price']) * parseInt(productItem['products'][i]['amount']);
        amount += (parseInt(productItem.products[i].amount) * parseInt(productItem.amount));
    }

    tmp_content_html += '<div class="commodity_body shipper-order"';
    tmp_content_html += ' onclick="showShipperOrderDetailInfo(\'' + productItem['id'] + '\')">';
    tmp_content_html += '<img src="' + REMOTE_API_URL + productItem['logo'] + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += '<h5>' + productItem['name'] + '</h5>';
    tmp_content_html += '<h5>共计 ' + amount + '件商品, 总价: ';

    tmp_content_html += '<span class="detail_right" style="float:none">' + getPrice(totalPrice * parseInt(productItem['amount'])) + '</span>';
    tmp_content_html += '</h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += 'style="height:' + '45px;">';

    tmp_content_html += '<div class="shipper-order-service-btn" onclick="shipService(\'' + productItem['id'] + '\')"><h5>送达</h5></div>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    return tmp_content_html;
}

function myShipperOrderDetailTemplate(productItem, orderId) {

    console.log(productItem);

    var iconImage = ['assets/images/address@3x.png', 'assets/images/goods@3x.png'];
    var payType = ['线上支付', '货到付款'];

    var payType_item = ["微信支付", "微信支付"];

    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    //if(status!=0 && productItem['status']!=status) return "";

    var status_class_List = ['waiting', 'waiting', 'waiting', 'completed', 'closed', 'closed'];
    var status_string_List = ['待付款', '待成团', '待发货', '交易完成', '已退款', '交易关闭'];

    var tmp_content_html = '';
    if (productItem['status'] == 1) {
        tmp_content_html += '<div class="counting">';
        tmp_content_html += '<h5>剩余 ' + hrs + '时 ' + mins + '分 ' + secs + '秒，该交易自动关闭</h5>';
        tmp_content_html += '</div>';
    }

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += '<h5>订单编号 : ' + productItem['id'] + '</h5>';
    tmp_content_html += '<h5 class="order_state">' + status_string_List[productItem['status'] - 1] + '</h5>';
    tmp_content_html += '</div></div>';

    tmp_content_html += '<div class="order title ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img src="' + iconImage[0] + '">';
    tmp_content_html += '<div class="detail">';
    tmp_content_html += '<h5><span class="">' + productItem['shop_contact'] + '</span>';
    tmp_content_html += '<span class="">' + productItem['shop_contact_phone'];
    tmp_content_html += '</span></h5>';
    tmp_content_html += '<h5><span>' + productItem['shop_address'] + '</span></h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情</span></h5>';
    var amount = 0;
    var new_price = 0;
    var rest_price = 0;
    for (var i = 0; i < productItem['products'].length; i++) {
        var item = productItem['products'][i];
        amount += (parseInt(item['amount']) * parseInt(productItem.amount));
        tmp_content_html += '<div class="commodity_body">';
        tmp_content_html += '<img src="' + REMOTE_API_URL + item['image'] + '">';
        //tmp_content_html += ' onclick="showProductDetailInfo(' + item['id'] + '0)">';
        tmp_content_html += '<div class="commodity_detail">';
        tmp_content_html += '<h5>' + item['name'] + '</h5>';
        tmp_content_html += '<div class="product_price">';
        if (item['status'] == 2) { // 2-unpaid
            item['new_price'] = '0.00';
            item['old_price'] = '';
        } else { // 1-paid
            item['new_price'] = item['new_price'];
            item['old_price'] = item['old_price'];
        }
        if (parseInt(productItem.group_success) != 2) {
            new_price += parseFloat(item['new_price']) * parseInt(item['amount']) * parseInt(productItem['amount']);
            rest_price += (parseFloat(item.old_price) - parseFloat(item['new_price'])) * parseInt(item['amount']) * parseInt(productItem['amount']);
            tmp_content_html += '<h5><span class="detail_left">' + getPrice(item['new_price']) + '</span>';
            tmp_content_html += '<span class="detail_right"><strike>' + getPrice(item['old_price']) + '</strike>';
        } else {
            new_price += parseFloat(item['old_price']) * parseInt(item['amount']) * parseInt(productItem['amount']);
            rest_price += (parseFloat(item.old_price) - parseFloat(item['new_price'])) * parseInt(item['amount']) * parseInt(productItem['amount']);
            tmp_content_html += '<h5><span class="detail_left">' + getPrice(item['new_price']) + '</span>';
            tmp_content_html += '<span class="detail_right"><strike>' + getPrice(item['old_price']) + '</strike>';
//            tmp_content_html += '<h5><span class="detail_left"><strike>' + getPrice(item['new_price']) + '</strike></span>';
//            tmp_content_html += '<span class="detail_right">' + getPrice(item['old_price']);
        }

        tmp_content_html += '</span>';
        tmp_content_html += '</h5></div>';
        tmp_content_html += '<h5>数量 :  <b>' + (parseInt(item['amount']) * parseInt(productItem.amount)) + '</b></h5>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
    }

    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += 'style="height:40px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + amount + '件商品, 总价 : &nbsp;';
    tmp_content_html += '<span class="detail_right">' + getPrice(new_price) + '</span>';
    tmp_content_html += '</h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><th>付款方式 : </th>';
    tmp_content_html += '<td>' + payType[productItem['pay_type'] - 1] + '</td></tr>';
    tmp_content_html += '<tr><th>买家留言 : </th>';
    tmp_content_html += '<td style="line-height:1.2;">' + productItem.note + '</td></tr>';

    st = parseInt(productItem['status']);
    if(parseInt(productItem.pay_type) == 2) {
        productItem.pay_wallet = 0;
        productItem.pay_price=0;
    }


    console.log((st));
    switch (st) {
        case 6: // transaction closed
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            tmp_content_html += '<tr><th>交易关闭时间 : </th>';
            tmp_content_html += '<td>' + productItem['closed_time'] + '</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            break;
        case 4: // transaction completed
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem.pay_wallet, 1) + '元</td></tr>';
            tmp_content_html += '<tr><th>' + payType_item[productItem["pay_type"] - 1] + ' : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem.pay_price, 1) + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '<tr><th>送达时间 : </th>';
            tmp_content_html += '<td>' + productItem['distributed_time'] + '</td></tr>';
            tmp_content_html += '<tr><td colspan="2">配送员 : ';
            tmp_content_html += '&nbsp;&nbsp;' + productItem['dist_name'] + '';
            tmp_content_html += '<span>' + productItem['dist_phone'] + '</span></td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            break;


        case 3: // payment waiting
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem.pay_wallet, 1) + '元</td></tr>';
            tmp_content_html += '<tr><th>' + payType_item[productItem["pay_type"] - 1] + ' : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem.pay_price, 1) + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            tmp_content_html += '<div><h5><br><br></h5></div>';

            tmp_content_html += '<button class="btn_confirm shipper-pay-service" ';
            tmp_content_html += 'onclick="confirmServiceModal(\'' + productItem['id'] + '\');">送达</button>';
            tmp_content_html += '</div>';

            break;

    }

    return tmp_content_html;
}

function myShipperHistoryItemTemplate(productItem, status) {

    /*
    productItem = {
        'id': '15',
        'number': '652458136868586',
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
        'status': 3,
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'amount': 3,

    };
    */

    var iconImage = ['assets/images/address@3x.png', 'assets/images/goods@3x.png'];

    var tmp_content_html = "";
    tmp_content_html += '<div class="order completed">';

    tmp_content_html += '<div class="order detail completed">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情</span></h5></div>';

    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showShipperHistoryDetailInfo(\'' + productItem['id'] + '\')">';
    tmp_content_html += '<img src="' + REMOTE_API_URL + productItem['logo'] + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += '<h5>' + productItem['name'] + '</h5>';

    var totalPrice = 0, totalOldPrice = 0;
    var amounts = 0;
    for (var i = 0; i < productItem['products'].length; i++) {
        totalPrice += parseFloat(productItem['products'][i]['new_price']) * parseInt(productItem['products'][i]['amount']);
        totalOldPrice += parseFloat(productItem['products'][i]['old_price']) * parseInt(productItem['products'][i]['amount']);
        amounts += parseInt(productItem['products'][i]['amount']);
    }

    tmp_content_html += '<div class="product_price">';

    if (true){ //(parseInt(productItem.group_success) != 2) {
        tmp_content_html += '<h5><span class="detail_left">' + getPrice(totalPrice) + '</span>';
        tmp_content_html += '<span class="detail_right"><strike>' + getPrice(totalOldPrice) + '</strike>';
    } else {
        tmp_content_html += '<h5><span class="detail_left"><strike>' + getPrice(totalPrice) + '</strike></span>';
        tmp_content_html += '<span class="detail_right">' + getPrice(totalOldPrice);
    }

    tmp_content_html += '</span></h5></div>';
    tmp_content_html += '<h5>数量 :  <b>' + productItem.amount + '</b></h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += ' style="padding:13px 0 0; height: 44px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + (parseInt(productItem['amount']) * amounts) + '件商品, 总价 : &nbsp;';

    if (parseInt(productItem.group_success) != 1) totalPrice = totalOldPrice;
    tmp_content_html += '<span class="detail_right">' + getPrice(totalPrice * parseInt(productItem['amount'])) + '</span>';
    tmp_content_html += '</h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';


    return tmp_content_html;
}

function myShipperHistoryItemDetailTemplate(productItem, status) {

    /*
    productItem = {
        'id': '15',
        'number': '652458136868586',
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/tmp/u' + Math.floor(Math.random() * 5 + 1) + '.png',
        'status': 3,
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'amount': 3,

    };
    */

    console.log(productItem);

    var iconImage = ['assets/images/address@3x.png', 'assets/images/goods@3x.png'];
    var payType = ['线上支付', '货到付款'];
    var payType_item = ["微信支付", "微信支付"];
    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    //if(status!=0 && productItem['status']!=status) return "";

    var status_class_List = ['waiting', 'waiting', 'waiting', 'completed', 'closed', 'closed'];
    var status_string_List = ['待付款', '待成团', '待发货', '交易完成', '已退款', '交易关闭'];

    var tmp_content_html = '';
    if (productItem['status'] == 1) {
        tmp_content_html += '<div class="counting">';
        tmp_content_html += '<h5>剩余 ' + hrs + '时 ' + mins + '分 ' + secs + '秒，该交易自动关闭</h5>';
        tmp_content_html += '</div>';
    }

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += '<h5>订单编号 : ' + productItem['id'] + '</h5>';
    tmp_content_html += '<h5 class="order_state">' + status_string_List[productItem['status'] - 1] + '</h5>';
    tmp_content_html += '</div></div>';

    tmp_content_html += '<div class="order title ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img src="' + iconImage[0] + '">';
    tmp_content_html += '<div class="detail">';
    tmp_content_html += '<h5><span class="">' + productItem['shop_contact'] + '</span>';
    tmp_content_html += '<span class="">' + productItem['shop_contact_phone'];
    tmp_content_html += '</span></h5>';
    tmp_content_html += '<h5><span>' + productItem['shop_address'] + '</span></h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情</span></h5>';
    var amount = 0;
    var new_price = 0;
    var old_price = 0;
    for (var i = 0; i < productItem['products'].length; i++) {
        var item = productItem['products'][i];
        amount += (parseInt(item['amount']) * parseInt(productItem.amount));
        tmp_content_html += '<div class="commodity_body">';
        tmp_content_html += '<img src="' + REMOTE_API_URL + item['image'] + '">';
//        tmp_content_html += ' onclick="showProductDetailInfo(' + item['id'] + '0)">';
        tmp_content_html += '<div class="commodity_detail">';
        tmp_content_html += '<h5>' + item['name'] + '</h5>';
        tmp_content_html += '<div class="product_price">';
        if (item['status'] == 2) { // 2-unpaid
            item['new_price'] = '0.00';
            item['old_price'] = '';
        } else { // 1-paid
            item['new_price'] = item['new_price'];
            item['old_price'] = item['old_price'];
        }
        new_price += parseFloat(item['new_price']) * parseInt(item['amount']);
        old_price += parseFloat(item['old_price']) * parseInt(item['amount']);

        tmp_content_html += '<h5>';

        if (false){ //(parseInt(productItem.group_success) == 2) {
            tmp_content_html += '<span class="detail_left"><strike>' + getPrice(item['new_price']) + '</strike></span>';
            tmp_content_html += '<span class="detail_right">' + getPrice(item['old_price']);
        } else {
            tmp_content_html += '<span class="detail_left">' + getPrice(item['new_price']) + '</strike></span>';
            tmp_content_html += '<span class="detail_right"><strike>' + getPrice(item['old_price']) + '</strike>';
        }

        tmp_content_html += '</span>';

        tmp_content_html += '</h5></div>';
        tmp_content_html += '<h5>数量 :  <b>' + (parseInt(item['amount']) * parseInt(productItem.amount)) + '</b></h5>';
        tmp_content_html += '</div>';
        tmp_content_html += '</div>';
    }
    if (parseInt(productItem.group_success) != 1) new_price = old_price;
    tmp_content_html += '<div id="order_footer' + productItem['id'] + '" class="order_footer" ';
    tmp_content_html += 'style="height:40px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + amount + '件商品, 总价 : &nbsp;';
    tmp_content_html += '<span class="detail_right">' + getPrice(new_price * parseInt(productItem['amount'])) + '</span>';
    tmp_content_html += '</h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><th>付款方式 : </th>';
    tmp_content_html += '<td>' + payType[productItem['pay_type'] - 1] + '</td></tr>';
    tmp_content_html += '<tr><th style="vertical-align: top;">买家留言 : </th>';
    tmp_content_html += '<td style="line-height:1.2;padding-top: 8px;">' + productItem.note + '</td></tr>';

    if (productItem.pay_type == 2){
        productItem.pay_price = 0;
        productItem.pay_wallet = 0;
    }

    st = parseInt(productItem['status']);
    var rest_price = (old_price - new_price) * parseInt(productItem.amount);
    console.log((st));
    switch (st) {
        case 6: // transaction closed
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            tmp_content_html += '<tr><th>交易关闭时间 : </th>';
            tmp_content_html += '<td>' + productItem['closed_time'] + '</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            break;
        case 4: // transaction completed
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem.pay_wallet, 1) + '元</td></tr>';
            tmp_content_html += '<tr><th>' + payType_item[productItem["pay_type"] - 1] + ' : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem['pay_price'],1) + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '<tr><th>送达时间 : </th>';
            tmp_content_html += '<td>' + productItem['distributed_time'] + '</td></tr>';

            tmp_content_html += '<tr><td colspan="2">配送员 : ';
            tmp_content_html += '&nbsp;&nbsp;' + productItem['dist_name'] + '';
            tmp_content_html += '<span>' + productItem['dist_phone'] + '</span></td></tr>';

            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            break;

        case 3: // payment waiting
            tmp_content_html += '<tr><th>扣除余额 : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem.pay_wallet, 1) + '元</td></tr>';
            tmp_content_html += '<tr><th>' + payType_item[productItem["pay_type"] - 1] + ' : </th>';
            tmp_content_html += '<td class="price">' + getPrice(productItem['pay_price'],1) + '元</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            if (productItem['pay_type'] == 1) {
                tmp_content_html += '<tr><th>付款时间 : </th>';
                tmp_content_html += '<td>' + productItem['paid_time'] + '</td></tr>';
            }
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            tmp_content_html += '<div><h5><br><br></h5></div>';


            tmp_content_html += '</div>';

            break;

    }

    return tmp_content_html;
}

function myShipperInfoTemplate(shipperItem, status) {

    var iconImage = [
        'assets/images/phone@3x.png',
        'assets/images/orders@3x.png',
    ];
    var tmp_content_html = '';

    tmp_content_html += '<div class="store">';
    tmp_content_html += '<div class="store_info left">';
    tmp_content_html += '<h5><img src="' + iconImage[0] + '"><h5>';
    tmp_content_html += '<span>' + shipperItem['user_phone'] + '</span>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="store_info">';
    tmp_content_html += '<h5><img src="' + iconImage[1] + '" '
        + ' style="">'
        + '<h5>';
    tmp_content_html += '<span>' + shipperItem['ship_amount'] + '</span>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="store pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<div class="td_row"><div class="td_left" style="margin: 7px 0;">所属区域总代理</div>';
    tmp_content_html += '<div class="td_right">' + shipperItem['provider_name'] + '</div><br></div>';
    tmp_content_html += '<div class="td_row"><div class="td_left" style="margin: 7px 0;">所属县区</div>';
    tmp_content_html += '<div class="td_right">' + shipperItem['provider_address'] + '</div></div>';
    tmp_content_html += '</table>';
    tmp_content_html += '</div>';

    return tmp_content_html;
}