// displaying item templates


function mainProductItemTemplate(productItem) {
    productItem = {
        'id': '15',
        'end_time': '15 : 23 : 30',
        'product_image': 'assets/images/logo.png',
        'product_name': '可跳转到收货地址',
        'info_size': '1g*12/箱',
        'info_amount': '500箱起拼',
        'progress': Math.floor(Math.random() * 85 + 16),
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'button': '加入购物车'
    };

    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity">';
    tmp_content_html += '<div class="commodity_header">';
    tmp_content_html += '<h5>距离拼团結束还有' + productItem['end_time'] + '</h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showProductDetailInfo(' + productItem['id'] + '0)">';
    tmp_content_html += '<img src="' + productItem['product_image'] + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += '<h5>' + productItem['product_name'] + '</h5>';
    tmp_content_html += '<h5>';
    tmp_content_html += '<span class="commodity_attr detail_left">';
    tmp_content_html += productItem['info_size'] + '</span>';
    tmp_content_html += '<span class="commodity_attr detail_right">';
    tmp_content_html += productItem['info_amount'] + '</span></h5></div>';
    tmp_content_html += '<div class="commodity_progress">';
    tmp_content_html += '<div>';
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html += '<div class="progress-bar progress-bar-success"';
    tmp_content_html += ' role="progressbar" aria-valuenow="' + productItem['progress'];
    tmp_content_html += '" aria-valuemin="0" aria-valuemax="100" style="width: ';
    tmp_content_html += productItem['progress'] + '%;">';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="progress-text">' + productItem['progress'] + '%</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<span class="detail_left"><strike>' + productItem['old_price'];
    tmp_content_html += '</strike></span>';
    tmp_content_html += '<span class="detail_right">' + productItem['new_price'] + '</span>';
    tmp_content_html += '</div></div>';
    tmp_content_html += '<div class="commodity_button">';
    tmp_content_html += '<div onclick="purchase_again_Order(' + productItem['id'] + ')">';
    tmp_content_html += productItem['button'] + '</div></div>';
    tmp_content_html += '</div></div>';
    return tmp_content_html;
}

function productDetailTemplate(productItem) {
    var manInfos = [
        {
            'image': 'assets/images/logo.png',
            'desc': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，'
        },
        {
            'image': 'assets/images/dist_personal_icon1@3x.png',
            'desc': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，'
        }
    ];

    var totalInfos = [
        {
            'product_name': '伊利牛奶',
            'amount': 3,
            'price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        },
        {
            'product_name': '方便面',
            'amount': 2,
            'price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        },
        {
            'product_name': '面包',
            'amount': 1,
            'price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        }
    ];
    productItem = {
        'id': '15',
        'end_time': '15 : 23 : 30',
        'product_name': '特级 优质 原生态有机米   真空包装',
        'mans': 3,
        'amount': 200,
        'progress': Math.floor(Math.random() * 85 + 16),
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'desc': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
        'text_html': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款',
        'man_info': manInfos,
        'total_info': totalInfos,
    };

    var tmp_content_html = "";
    tmp_content_html += '<div class="product_item">';
    tmp_content_html += '<div class="product_header">';
    tmp_content_html += '<h5>' + productItem['product_name'] + '</h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="product_attr">';
    tmp_content_html += '<h5><span class="detail_left">距离拼团結束还有';
    tmp_content_html += productItem['end_time'] + '</span>';
    tmp_content_html += '<span class="detail_right">';
    tmp_content_html += productItem['mans'] + '人团▪' + productItem['amount'] + '件起团';
    tmp_content_html += '</span></h5></div>';
    tmp_content_html += '<div class="product_progress">';
    tmp_content_html += '<div class="product_price">';
    tmp_content_html += '<h5><span class="detail_left">' + productItem['new_price'] + '</span>';
    tmp_content_html += '<span class="detail_right"><strike>' + productItem['old_price'];
    tmp_content_html += '</strike></span></h5></div>';

    tmp_content_html += '<div class="commodity_progress"><div>';
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html += '<div class="progress-bar progress-bar-success" role="progressbar"';
    tmp_content_html += ' aria-valuenow="' + productItem['progress'] + '" aria-valuemin="0"';
    tmp_content_html += ' aria-valuemax="100" style="width: ' + productItem['progress'] + '%;">';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="progress-text">' + productItem['progress'] + '%</div>';
    tmp_content_html += '</div></div></div></div></div>';

    ManInfo = productItem['man_info'];
    if (ManInfo.length > 0) {
        tmp_content_html += '<div class="man_info">';
        for (var i = 0; i < ManInfo.length; i++) {
            var item = ManInfo[i];
            tmp_content_html += '<img src="' + item['image'] + '">';
        }
        tmp_content_html += '<h5>还差 ';
        tmp_content_html += '<span>' + (productItem['mans'] - ManInfo.length) + '</span>';
        tmp_content_html += '件即可拼图成功，';
        tmp_content_html += productItem['end_time'] + ' 后结束</h5></div>';
    }

    tmp_content_html += '<div class="product_info"><div class="product_attr">';
    tmp_content_html += '<h5>拼单须知</h5></div>';
    tmp_content_html += '<h5>' + productItem['desc'] + '</h5></div>';

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
            tmp_content_html += '<td>' + item['price'] + '</td>';
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

    productItem = {
        'id': '15',
        'image': 'assets/images/logo.png',
        'amount': 200,
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),

    };

    var tmp_content_html = '';
    tmp_content_html += '<div class="modal-body">';
    tmp_content_html += '<div class="close_button" ';
    tmp_content_html += 'onclick="$(\'#top_dialog\').modal(\'hide\');">';
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
    tmp_content_html += '<button class="btn-left" onclick="decreaseAmount()">-</button>';
    tmp_content_html += '<input id="product_amount" value="0" oninput="validateAmount()">';
    tmp_content_html += '<button class="btn-right" onclick="increaseAmount()">+</button>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<button class="btn_confirm" ';
    tmp_content_html += 'onclick="$(\'#top_dialog\').modal(\'hide\');">确定</button>';
    tmp_content_html += '<div class="overlay"><img src="' + productItem['image'] + '"></div>';

    return tmp_content_html;
}

function myOrderItemTemplate(productItem, status) {

    productItem = {
        'id': '15',
        'number': '652458136868586',
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/logo.png',
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

    var statusList = [0, 1, 2, 3, 4];
    productItem = {
        'id': '15',
        'number': '652458136868586',
        'user_name': '张某某',
        'user_phone': '18234857557',
        'user_addr': '北京朝阳区芳香园12区5号楼502室',
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/logo.png',
        'status': status,//Math.floor(Math.random() * 5)+1,
        // 1-waiting payment, 2-waiting groupping, 3-waiting distribution,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'amount': 3,
        'pay_type': 1,
        'pay_wallet': '25.00',
        'pay_price': '25.00',
        'ordered_time': new Date().toLocaleString(),//("Y-m-d H:i:s"),
        'closed_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
        'paid_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
        'refunded_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
        'distributed_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
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
    tmp_content_html += '<h5><span>' + productItem['user_addr'] + '<span></h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="order detail ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情<span></h5>';

    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showOrderDetailInfo(' + productItem['id'] + '0)">';
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
    tmp_content_html += 'style="height:40px;">';
    tmp_content_html += '<h5>共计 &nbsp;' + productItem['amount'] + '件商品, 总价 : &nbsp;';
    tmp_content_html += '<span class="detail_right">' + productItem['new_price'] + '</span>';
    tmp_content_html += '</h5>';

    tmp_content_html += '</div>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><th>付款方式 : </th>';
    tmp_content_html += '<td>' + payType[productItem['pay_type'] - 1] + '</td></tr>';
    tmp_content_html += '<tr><th>买家留言 : </th>';
    tmp_content_html += '<td>希望可以尽快发货哦!' + '</td></tr>';

    st = productItem['status'];
    console.log((st));
    switch (st) {
        case '6': // transaction closed
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
        case '4': // transaction completed
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
        case '5': // transaction refunded
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
        case '1': // payment waiting
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += '<table>';
            tmp_content_html += '<tr><th>提交订单时间 : </th>';
            tmp_content_html += '<td>' + productItem['ordered_time'] + '</td></tr>';
            tmp_content_html += '</table>';
            tmp_content_html += '</div>';
            tmp_content_html += '<div><h5><br><br></h5></div>';

            tmp_content_html += '<button class="btn_confirm btn-grey" ';
            tmp_content_html += 'onclick="$(\'#menu_dialog\').modal(\'hide\');">取消订单</button>';
            tmp_content_html += '<button class="btn_confirm btn-cyan" ';
            tmp_content_html += 'onclick="$(\'#menu_dialog\').modal(\'hide\');">付款</button>';

            break;
        case '3': // payment waiting
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
        case '2': // payment waiting
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

function myGroupingItemTemplate(productItem, status) {


    productItem = {
        'id': '15',
        'number': '652458136868586',
        'name': '特级 优质 原生态有机原生态有机原生态有机米   真空包装',
        'image': 'assets/images/logo.png',
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
            'image': 'assets/images/logo.png',
            'desc': '需要满足拼单人数和拼单数量的'
        },
        {
            'image': 'assets/images/dist_personal_icon1@3x.png',
            'desc': '需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，'
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
        'ordered_time': new Date().toLocaleString(),//("Y-m-d H:i:s"),
        'closed_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
        'paid_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
        'refunded_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
        'distributed_time': new Date().toLocaleString(),//time("Y-m-d H:i:s"),
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
            tmp_content_html += '<img src="' + item['image'] + '">';
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

function noItemsTemplate(status) {

    var tmp_content_html = '';
    tmp_content_html += '<div class="order_no_items">';
    tmp_content_html += '<img src="assets/images/face@3x.png">';
    tmp_content_html += '<h5>';
    switch (status) {
        case 1:
            tmp_content_html += '暂无订单消息';
            break;
        case 2:
            tmp_content_html += '暂无拼团消息';
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
        'trans_time': (new Date()).toLocaleString(),
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
        'product_image': 'assets/images/logo.png',
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

function myProviderItemTemplate(productItem) {
    productItem = {
        'id': '15',
        'end_time': '15 : 23 : 30',
        'product_image': 'assets/images/logo.png',
        'product_name': '可跳转到收货地址',
        'info_size': '1g*12/箱',
        'info_amount': '500箱起拼',
        'progress': Math.floor(Math.random() * 85 + 16),
        'old_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': '¥' + Math.floor(Math.random() * 100).toFixed(2),
        'button': '加入购物车'
    };

    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity my-provider">';
    //tmp_content_html += ' onclick="showProductDetailInfo(' + productItem['id'] + '0)">';
    tmp_content_html += '<img src="' + productItem['product_image'] + '">';
    tmp_content_html += '<h5>' + productItem['product_name'] + '</h5>';
    tmp_content_html += '</div>';
    return tmp_content_html;
}

function myStoreInfoTemplate(productItem, status) {
    productItem = {
        'id': '15',
        'number': '652458136862456256245624568',
        'user_name': '张某某',
        'user_phone': '18234857557',
        'user_addr': '北京朝阳区芳香园12区',
        'user_image': 'assets/images/tmp/authcard.png',
        'status': status,//Math.floor(Math.random() * 5)+1,
        // 1-waiting payment, 2-waiting groupping, 3-waiting distribution,
        // 4-transaction completed, 5-transaction refunded, 6-transaction closed,
        // 7-success groupping, 8-failed groupping, 9-waiting receiving
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
    tmp_content_html += '<span>' + productItem['user_phone'] + '</span>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="store_info">';
    tmp_content_html += '<h5><img src="' + iconImage[1] + '"><h5>';
    tmp_content_html += '<span>' + productItem['user_name'] + '</span>';
    tmp_content_html += '</div>';
    tmp_content_html += '</div>';

    tmp_content_html += '<div class="store pay-info">';
    tmp_content_html += '<table>';
    tmp_content_html += '<tr><th>地址 : </th>';
    tmp_content_html += '<td>' + productItem['user_addr'] + '</td></tr>';
    tmp_content_html += '<tr><th>营业执照编号 : </th>';
    tmp_content_html += '<td>' + productItem['number'] + '</td></tr>';
    tmp_content_html += '</table>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="store auth_img">';
    tmp_content_html += '<h5><img src="' + productItem['user_image'] + '"><h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '<button class="store btn_confirm" ';
    tmp_content_html += 'onclick="applyStoreAuthrization();">提交认证</button>';



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
            tmp_content_html += '<div class="btn_confirm" onclick="location.href=\'myfunction_manage.php\'">返回</div>';
            break;
        case '2':
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