
// displaying item templates

function myOrderItemTemplate(productItem) {
    var status_class_List = ['order_using', 'order_expired'];
    var status_string_List = ['使用中', '已过期'];

    tmp_content_html = "";
    tmp_content_html += '<div class="order ' + status_class_List[productItem['status'] - 1] + '">';
    tmp_content_html += '<div class="order_body" onclick="showScenicArea(' + productItem['id'] + ')">';
    tmp_content_html += '<img src="' + productItem['image'] + '">';
    tmp_content_html += '<div class="scenic_content" style="position: relative">';
    if (productItem['status'] == 1) {
        tmp_content_html += '<h5 style="position: absolute; color: red; right: 0px">' +
            status_string_List[productItem['status'] - 1] + '</h5>';
    } else {

        tmp_content_html += '<h5 style="position: absolute; right: 0px">' +
            status_string_List[productItem['status'] - 1] + '</h5>';
    }

    tmp_content_html += '<h5>&nbsp</h5>';
    tmp_content_html += '<h5>' + productItem['name'] + '</h5>';
    tmp_content_html += '<h5>' + productItem['order_time'] + '</h5>';
    tmp_content_html += '</div></div>';

    if (productItem['status'] == 2) {
        tmp_content_html += '<div class="order_footer">';
        tmp_content_html += '    <div onclick="purchase_again_Order(' + productItem['id'] + ')"><h5>重新购买</h5></div>';
        tmp_content_html += '</div>'
    }
    tmp_content_html += '</div>';
}

function mainProductItemTemplate(productItem) {
    productItem = {
        'id': '15',
        'end_time': '15 : 23 : 30',
        'product_image': 'assets/images/logo.png',
        'product_name': '可跳转到收货地址',
        'info_size': '1g*12/箱',
        'info_amount': '500箱起拼',
        'progress': Math.floor(Math.random() * 85 + 16),
        'old_price': 'XXXX',//'¥' + Math.floor(Math.random() * 100).toFixed(2),
        'new_price': 'XXXX',//'¥' + Math.floor(Math.random() * 100).toFixed(2),
        'button': '加入购物车'
    };

    tmp_content_html = "";
    tmp_content_html += '<div class="commodity">';
    tmp_content_html += '<div class="commodity_header">';
    tmp_content_html += '<h5>距离拼团結束还有' + productItem['end_time'] + '</h5>';
    tmp_content_html += '</div>';
    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += ' onclick="showOrderDetailInfo(' + productItem['id'] + '0)">';
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