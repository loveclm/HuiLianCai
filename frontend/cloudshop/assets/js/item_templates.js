// displaying item templates

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

function getImageURL(url) {
    if (url == "") return "assets/images/plus@3x.png";
    return HLC_APP_MODE != HLC_SIMUL_MODE ? REMOTE_API_URL + url : url;
}

function getTimeString(time_str) {
    var tmp = time_str.split("-");
    time_str = "";

    for (var i = 0; i < tmp.length; i++) {
        time_str += i == 0 ? tmp[i] : "/" + tmp[i];
    }
    var cur_time = new Date();
    if (cur_time > new Date(time_str)) return " 00 : 00 : 00";

    cur_time = moment(cur_time.getTime()).format("YYYY/MM/DD HH:mm:ss");
    var ms = moment(time_str, "YYYY/MM/DD HH:mm:ss").diff(
        moment(cur_time, "YYYY/MM/DD HH:mm:ss")
    );
    var d = moment.duration(ms);
    var s = Math.floor(d.asHours()) + moment.utc(ms).format(" : mm : ss");

    return " " + s;
}

function calculateGroupingTime(item, id) {
    clearInterval();
    if (id == undefined) id = "";
    app_data["timerID"] = setInterval(function () {
        $("#remain_time" + id).html(getTimeString(item.end_time));
        $("#remain_time").html(getTimeString(item.end_time));
    }, 1000);
    console.log(app_data.timerID);
}

function getCancelTimeString(time_str) {
    var tmp = time_str.split("-");
    time_str = "";

    for (var i = 0; i < tmp.length; i++) {
        time_str += i == 0 ? tmp[i] : "/" + tmp[i];
    }
    var cur_time = new Date();
    var end_time = new Date(time_str);
    end_time = end_time.setHours(end_time.getHours() + 3);

    if (cur_time > new Date(end_time)) return " 0时 0分 0秒";

    cur_time = moment(cur_time.getTime()).format("YYYY/MM/DD HH:mm:ss");
    end_time = moment(end_time).format("YYYY/MM/DD HH:mm:ss");

    console.log();
    var ms = moment(end_time, "YYYY/MM/DD HH:mm:ss").diff(
        moment(cur_time, "YYYY/MM/DD HH:mm:ss")
    );
    var d = moment.duration(ms);
    var s = Math.floor(d.asHours()) + moment.utc(ms).format("时 m分 s秒");

    return " " + s;
}

function calculateCancelTime(item, id) {
    clearInterval();
    if (id == undefined) id = "";
    app_data["timerID"] = setInterval(function () {
        $("#remain_time" + id).html(getCancelTimeString(item.ordered_time));
    }, 1000);
    console.log(app_data.timerID);
}

function getDateTimeString(date_str) {
    if (date_str == 'undefined') return "";
    if (date_str == 'null') return "";
    if (date_str == '') return "";
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

function mainActivityItemTemplate(item) {
    var btn_string = "加入购物车";
    var item_progress = parseInt(
        item["man_info"].length / parseInt(item["mans"]) * 100
    );

    if (parseInt(item.grouping_status) == 3 || item_progress == 100) {
        item_progress = 100;
        //item.end_time = "2000-01-01 00:00:00";
    }

    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity">';
    tmp_content_html += '<div class="commodity_header">';
    tmp_content_html += "<h5>距拼团結束还有";
    tmp_content_html +=
        '<span id="remain_time' +
        item["id"] +
        '">' +
        getTimeString(item["end_time"]) +
        "</span>";
    tmp_content_html += "</h5>";
    tmp_content_html += "</div>";

    calculateGroupingTime(item, item.id);

    var standard = "";
    if (item.total_info.length == 1) standard = item.total_info[0].standard;
    else standard = item.total_info.length + "种/组";

    var unit = "组";
    if (item.total_info.length == 1) unit = item.total_info[0].unit_name;

    var added = "";
    //        if(parseFloat(item.new_price)==0) added='(赠送) ';

    tmp_content_html += '<div class="commodity_body">';
    tmp_content_html += '<img src="' + getImageURL(item["product_image"]) + '" ';
    tmp_content_html +=
        " onclick=\"showProductDetailInfo('" + item["id"] + "',5)\">";

    tmp_content_html += '<div class="commodity_information">';

    tmp_content_html += "<h5>" + added + item["product_name"] + "</h5>";
    tmp_content_html += '<div class="commodity_detail"';
    tmp_content_html +=
        " onclick=\"showProductDetailInfo('" + item["id"] + "',5)\">";
    tmp_content_html += "<h5>";
    tmp_content_html += '<span class="commodity_attr detail_left">';
    // tmp_content_html += item['info_size'] + '</span>';
    tmp_content_html += standard + "</span>";
    tmp_content_html += '<span class="commodity_attr detail_right">';
    // tmp_content_html += item['info_box'] + '</span></h5></div>';
    tmp_content_html += item.min_amount + unit + "起拼</span></h5></div>";

    tmp_content_html += '<div class="commodity_progress"';
    tmp_content_html +=
        " onclick=\"showProductDetailInfo('" + item["id"] + "',5)\">";
    tmp_content_html += "<div>";
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html += '<div class="progress-bar progress-bar-success"';
    tmp_content_html += ' role="progressbar" aria-valuenow="' + item_progress;
    tmp_content_html += '" aria-valuemin="0" aria-valuemax="100" style="width: ';
    tmp_content_html += item_progress + '%;">';
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="progress-text">' + item_progress + "%</div>";
    tmp_content_html += "</div>";
    tmp_content_html +=
        '<span class="detail_left"><strike>' + getPrice(item["old_price"]);
    tmp_content_html += "</strike></span>";
    tmp_content_html +=
        '<span class="detail_right">' + getPrice(item["new_price"]) + "</span>";
    tmp_content_html += "</div></div>";

    tmp_content_html +=
        '<div id="btn-add-to-cart' + item.id + '" class="commodity_button" onclick="showSetAmount(' + item.id + ')">';
    tmp_content_html += '<div>';
    tmp_content_html += btn_string + "</div></div>";

    tmp_content_html += "</div></div>";

    tmp_content_html += "</div>";

    if (item_progress == 100) {
        setTimeout(function () {
            //$(".progress-bar.active, .progress.active .progress-bar").css({background: "#6bbdff"});
            $('.progress-text').css({'color': 'white', 'mix-blend-mode': 'normal'});
            $('#btn-add-to-cart' + item.id + " div").css({'background': 'lightgrey', 'border': 'none'});
            $('#btn-add-to-cart' + item.id + " div:active").css({'color': 'white'});
            $('#btn-add-to-cart' + item.id).attr('onclick', '');
        }, 5);
    } else {
        setTimeout(function () {
            $('.progress-text').css({'color': 'white', 'mix-blend-mode': 'normal'});
        }, 5);
    }
    return tmp_content_html;
}

function activityDetailTemplate(productItem) {
    var product_desc = "需要满足拼单人数和拼单数量的条件才能拼单成功原价付款，拼单成功后退还多余钱款";
    var item_progress = parseInt(
        productItem["man_info"].length / parseInt(productItem["mans"]) * 100
    );
    if (parseInt(productItem.grouping_status) == 3 || item_progress == 100) {
        item_progress = 100;
//        productItem.end_time = "2000-01-01 00:00:00";
    }

    var added = "";
    //    if(parseFloat(productItem.new_price)==0) added='(赠送) ';
    var tmp_content_html = "";
    tmp_content_html += '<div class="product_item">';
    tmp_content_html += '<div class="product_header">';
    tmp_content_html += "<h5>" + added + productItem["product_name"] + "</h5>";
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="product_attr">';
    tmp_content_html += '<h5><span class="detail_left">距拼团結束还有';
    tmp_content_html +=
        '<span id="remain_time' +
        productItem.id +
        '">' +
        getTimeString(productItem["end_time"]) +
        "</span></span>";

    calculateGroupingTime(productItem, productItem.id);

    var unit = "组";
    if (productItem.total_info.length == 1)
        unit = productItem.total_info[0].unit_name;

    tmp_content_html += '<span class="detail_right">';
    tmp_content_html +=
        productItem["mans"] +
        '人团<i class="fa fa-circle"></i>' +
        productItem["amount"] +
        unit +
        "起团";
    tmp_content_html += "</span></h5></div>";
    tmp_content_html += '<div class="product_progress">';
    tmp_content_html += '<div class="product_price">';
    tmp_content_html +=
        '<h5><span class="detail_left">' +
        getPrice(productItem["new_price"]) +
        "</span>";
    tmp_content_html +=
        '<span class="detail_right"><strike>' + getPrice(productItem["old_price"]);
    tmp_content_html += "</strike></span></h5></div>";

    tmp_content_html += '<div class="commodity_progress"><div>';
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html +=
        '<div class="progress-bar progress-bar-success" role="progressbar"';
    tmp_content_html +=
        ' aria-valuenow="' + item_progress + '" aria-valuemin="0"';
    tmp_content_html +=
        ' aria-valuemax="100" style="width: ' + item_progress + '%;">';
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="progress-text">' + item_progress + "%</div>";
    tmp_content_html += "</div></div></div></div></div>";

    ManInfo = productItem["man_info"];
    if (ManInfo.length > 0) {
        tmp_content_html += '<div class="man_info">';

        ManInfo.sort(function (a, b) {
            if (a.ordered_time > b.ordered_time) return 1;
            else if (a.ordered_time < b.ordered_time) return -1;
            else return 0;
        })

        for (var i = 0; i < (ManInfo.length > 2 ? 2 : ManInfo.length); i++) {
            var item = ManInfo[i];
            tmp_content_html += '<img src="' + getImageURL(item["image"]) + '" ';
            tmp_content_html += " onclick='showManDetailInfo(";
            tmp_content_html +=
                JSON.stringify(ManInfo) + ',"' + item["id"] + "\");'>";
        }
        if (ManInfo.length < 2) {
            tmp_content_html += '<img src="assets/images/man_b_n@3x.png">';
        }

        if (item_progress == 100) {
            tmp_content_html += "<h5>拼团成功。</h5>";
        } else {
            tmp_content_html += "<h5>还差 ";
            tmp_content_html +=
                "<span>" + (productItem["mans"] - ManInfo.length) + "</span>";
            tmp_content_html += '件即可拼团成功，<span id="remain_time">';
            tmp_content_html += getTimeString(productItem["end_time"]) + "</span> 后结束</h5>";
        }
        tmp_content_html += "</div>";

        calculateGroupingTime(productItem, productItem.id);
    }

    tmp_content_html += '<div class="product_info"><div class="product_attr">';
    tmp_content_html += "<h5>拼单须知</h5></div>";
    tmp_content_html += "<h5>" + product_desc + "</h5></div>";

    TotalInfo = productItem["total_info"];
    if (TotalInfo.length > 1) {
        tmp_content_html += '<div class="product_info"><div class="product_attr">';
        tmp_content_html += "<h5>组合明细</h5></div>";
        tmp_content_html += '<table class="product_table"><tbody>';
        tmp_content_html += "<tr><th>商品名称</th><th>数量</th><th>总价</th><tr>";
        for (var i = 0; i < TotalInfo.length; i++) {
            var item = TotalInfo[i];
            tmp_content_html += "<tr>";
            tmp_content_html += "<td>" + item["product_name"] + "</td>";
            tmp_content_html += "<td>" + item["amount"] + "</td>";
            tmp_content_html += "<td>" + getPrice(parseFloat(item["old_price"])*parseInt(item.amount)) + "</td>";
        }
        tmp_content_html += "</tbody></table>";
        tmp_content_html += "</div>";
    }

    tmp_content_html += '<div class="product_info"><div class="product_attr">';
    tmp_content_html += "<h5>商品详情</h5></div>";
    tmp_content_html +=
        '<div class="product_detail_html">' +
        replaceHtmlImgURL(productItem["text_html"]);
    tmp_content_html += "</div></div>";

    if (item_progress == 100) {
        setTimeout(function () {
            $('#bottom-btn-detail1').css({background: 'lightgrey', 'border-right': '1px solid #f0f0f0'});
            $('#bottom-btn-detail2').css({background: 'lightgrey'});
            $('#bottom-btn-detail1').attr('onclick', '');
            $('#bottom-btn-detail2').attr('onclick', '');

            $(".progress-bar.active, .progress.active .progress-bar").css({background: "#6bbdff"});
            $('.progress-text').css({'color': 'white', 'mix-blend-mode': 'normal'});
        }, 5);
    } else {
        setTimeout(function () {
            $('.progress-text').css({'color': 'white', 'mix-blend-mode': 'normal'});
        }, 5);
    }

    return tmp_content_html;
}

function activityAmountSettingTemplate(productItem) {
    var id = productItem["id"];
    var tmp_content_html = "";
    tmp_content_html += '<div class="modal-body">';
    tmp_content_html += '<div class="close_button" ';
    tmp_content_html += 'onclick="hideAmountModal();">';
    tmp_content_html += '<img src="assets/images/close@3x.png"></div>';
    tmp_content_html += '<div class="product_item">';
    tmp_content_html += '<div class="product_price"><h5>';
    tmp_content_html +=
        '<span class="detail_left">' +
        getPrice(productItem["new_price"]) +
        "</span>";
    tmp_content_html += '<span class="detail_right">';
    tmp_content_html +=
        "<strike>" + getPrice(productItem["old_price"]) + "</strike></span>";
    tmp_content_html += "</h5></div>";
    tmp_content_html +=
        '<div class="product_price"><h5>库存' + parseInt(productItem["max_amount"]) + "件";
    tmp_content_html += "</h5></div></div>";
    tmp_content_html += '<div class="product_amount"><h5>选择数量</h5>';
    tmp_content_html +=
        '<button class="btn-left" onclick="decreaseAmount(' + id + ')">-</button>';
    tmp_content_html +=
        '<input id="product_amount' +
        id +
        '" value="' +
        productItem["min_amount"] +
        '" ';
    tmp_content_html += 'oninput="validateAmount(' + id + ')">';
    tmp_content_html +=
        '<input id="min_amount' +
        id +
        '" value="' +
        productItem["min_amount"] +
        '" ';
    tmp_content_html += 'style="display: none;">';
    tmp_content_html += '<input id="max_amount' + id + '" value="';
    tmp_content_html += parseInt(productItem["max_amount"]) + '" ';
    tmp_content_html += 'style="display: none;">';
    tmp_content_html +=
        '<button class="btn-right" onclick="increaseAmount(' + id + ')">+</button>';
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";
    tmp_content_html += '<button class="btn_confirm" ';
    tmp_content_html +=
        "onclick=\"addProductOrderCart('" + id + "');\">确定</button>";
    tmp_content_html +=
        '<img class="overlay-image" src="' +
        getImageURL(productItem["product_image"]) +
        '">';

    return tmp_content_html;
}

function myOrderItemTemplate(orderItem, status) {
    var products = orderItem.products;
    status = parseInt(status);
    if (status != 0 && parseInt(orderItem["status"]) != status) return "";

    var old_price = 0,
        new_price = 0,
        total_count = 0;
    for (var i = 0; i < products.length; i++) {
        old_price +=
            parseFloat(products[i].old_price) * parseInt(products[i].amount);
        new_price +=
            parseFloat(products[i].new_price) * parseInt(products[i].amount);
        total_count += parseInt(products[i].amount);
    }
    status = parseInt(orderItem["status"]);
    //if (status == 6 && parseInt(orderItem.pay_type) == 2) status = 7;

    var status_class_List = [
        "waiting",
        "waiting",
        "waiting",
        "completed",
        "closed",
        "closed",
        "closed"
    ];
    var status_string_List = ["待付款", "待成团", "待发货", "交易完成", "交易关闭", "已退款", "已取消"];

    var tmp_content_html = "";
    tmp_content_html += '<div class="order ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += "<h5>订单编号 : " + orderItem["id"] + "</h5>";
    tmp_content_html += '<h5 class="order_state">' + status_string_List[status - 1] + "</h5>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += " onclick=\"showOrderDetailInfo('" + orderItem["id"] + "')\">";
    tmp_content_html += '<img src="' + getImageURL(orderItem["logo"]) + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += "<h5>" + orderItem["name"] + "</h5>";
    tmp_content_html += '<div class="product_price">';

    if (false) {//(orderItem.group_success == 2) {
        tmp_content_html +=
            '<h5><span class="detail_left"><strike>' +
            getPrice(new_price) +
            "</strike></span>";
        tmp_content_html += '<span class="detail_right">' + getPrice(old_price);
    } else {
        tmp_content_html += '<h5><span class="detail_left">' + getPrice(new_price) + "</span>";
        tmp_content_html += '<span class="detail_right"><strike>' + getPrice(old_price) + "</strike>";
    }

    tmp_content_html += "</span></h5></div>";
    tmp_content_html += "<h5>数量 :  <b>" + orderItem.amount + "</b></h5>";

    tmp_content_html += "</div>";
    tmp_content_html += "</div>";
    if (orderItem.group_success != 1) new_price = old_price;
    tmp_content_html +=
        '<div id="order_footer' + orderItem["id"] + '" class="order_footer" ';
    tmp_content_html +=
        'style="height:' +
        (parseInt(orderItem["status"]) >= 3 ? "25" : "55") +
        'px;">';
    tmp_content_html += "<h5>共计 &nbsp;" + (total_count * parseInt(orderItem.amount)) + "件商品, 总价 : &nbsp;";

    /////////////////////////////////////////

    tmp_content_html +=
        '<span class="detail_right">' +
        getPrice(new_price * parseInt(orderItem["amount"])) +
        "</span>";

    /////////////////////////////////////////
    tmp_content_html += "</h5>";

    if (status < 3) {
        switch (status) {
            case 1:
                tmp_content_html +=
                    "<div onclick=\"showPaymentPageFromOrderList('" +
                    orderItem["id"] +
                    "')\">";
                tmp_content_html +=
                    "<h5>&nbsp;&nbsp;&nbsp;付款&nbsp;&nbsp;&nbsp;&nbsp;</h5></div>";
                tmp_content_html += '<div class="disabled"';
                tmp_content_html +=
                    " onclick=\"showCancelOrderConfirm('" + orderItem["id"] + "')\">";
                tmp_content_html += "<h5>取消订单</h5></div>";
                break;
            case 2:
                if (orderItem["pay_type"] == 1) {
                    tmp_content_html +=
                        "<div onclick=\"applyCancelFeedback('" + orderItem["id"] + "')\">";
                    tmp_content_html += "<h5>取消订单</h5></div>";
                } else {
                    tmp_content_html +=
                        "<div onclick=\"showCancelOrderConfirm('" + orderItem["id"] + "')\">";
                    tmp_content_html += "<h5>取消订单</h5></div>";
                }
                break;
        }
    }
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    return tmp_content_html;
}

function myOrderDetailTemplate(orderItem) {
    var iconImage = [
        "assets/images/address@3x.png",
        "assets/images/goods@3x.png"
    ];
    var payType = ["线上支付", "货到付款"];
    var payType_item = ["微信支付", "微信支付"];
    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    var status = parseInt(orderItem.status);

    var status_class_List = [
        "waiting",
        "waiting",
        "waiting",
        "completed",
        "closed",
        "closed",
        "closed"
    ];
    var status_string_List = ["待付款", "待成团", "待发货", "交易完成", "交易关闭", "已退款"];
    //if (status == 6 && parseInt(orderItem.pay_type) == 2) status = 7;

    var tmp_content_html = "";
    if (orderItem["status"] == 1) {
        tmp_content_html += '<div class="counting">';
        tmp_content_html += '<h5>剩余 <span id="remain_time' + orderItem.id + '">'
        tmp_content_html += getCancelTimeString(orderItem.ordered_time) + "</span>，该交易自动关闭</h5>";
        tmp_content_html += "</div>";
        calculateCancelTime(orderItem, orderItem.id);
    }

    tmp_content_html += '<div class="order detail ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<div class="order_header">';
    tmp_content_html += "<h5>订单编号 : " + orderItem["id"] + "</h5>";
    tmp_content_html += '<h5 class="order_state">' + status_string_List[status - 1] + "</h5>";
    tmp_content_html += "</div></div>";

    tmp_content_html += '<div class="order title ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<img src="' + iconImage[0] + '" style="height:auto;">';
    tmp_content_html += '<div class="detail" style="width:calc(100vw - 60px);">';
    //tmp_content_html += ' onclick="showProviderDetailInfo(\'' + id + '\',2)">';
    tmp_content_html += '<h5><span class="">' + orderItem["shop_contact"] + "</span>";
    tmp_content_html += '<span class="">' + orderItem["shop_contact_phone"];
    tmp_content_html += "</span></h5>";
    tmp_content_html += "<h5><span>" + orderItem["shop_address"] + "</span></h5>";
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="order detail ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情</span></h5>';
    var amount = 0;
    var new_price = 0;
    var old_price = 0;
    var old_item_price = 0;
    for (var j = 0; j < orderItem.products.length; j++)
        old_item_price += parseFloat(orderItem.products[j]["old_price"]) * parseInt(orderItem.products[j]["amount"]);

    for (var i = 0; i < orderItem["products"].length; i++) {
        var item = orderItem["products"][i];

        if (getPrice(item["new_price"], 1) == "0.00") continue;
        amount += parseInt(item["amount"]);
        tmp_content_html += '<div class="commodity_body">';
        tmp_content_html += '<img src="' + getImageURL(item["image"]) + '">';
        //tmp_content_html += ' onclick="showProductDetailInfo(\'' + item['id'] + '\',3)">';
        tmp_content_html += '<div class="commodity_detail">';
        //tmp_content_html += ' onclick="showProductDetailInfo(\'' + item['id'] + '\',3)">';
        tmp_content_html += "<h5>" + item["name"] + "</h5>";
        tmp_content_html += '<div class="product_price">';
        if (item["amount"] == 0) {
            // 2-unpaid
            item["new_price"] = "0.00";
            item["old_price"] = "";
        } else {
            // 1-paid
            item["new_price"] = item["new_price"];
            item["old_price"] = item["old_price"];
        }
        new_price += parseFloat(item["new_price"]) * parseInt(item["amount"]);
        old_price += parseFloat(item["old_price"]) * parseInt(item["amount"]);
        tmp_content_html += '<h5><span class="detail_left">' + getPrice(item["new_price"]) + "</span>";
        tmp_content_html += '<span class="detail_right">';
        tmp_content_html += "<strike>" + getPrice(item.old_price) + "</strike>";

        tmp_content_html += "</span>";
        tmp_content_html += "</h5>";
        tmp_content_html += "<h5 style='font-size: 11pt;'>数量 :  <b>" + parseInt(item["amount"]) * parseInt(orderItem.amount)
        tmp_content_html += "</b></h5>";
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
    }
    for (var i = 0; i < orderItem["products"].length; i++) {
        var item = orderItem["products"][i];
        if (getPrice(item["new_price"], 1) != "0.00") continue;
        amount += parseInt(item["amount"]);
        tmp_content_html += '<div class="commodity_body">';
        tmp_content_html += '<img src="' + getImageURL(item["image"]) + '">';
        //tmp_content_html += ' onclick="showProductDetailInfo(\'' + item['id'] + '\',3)">';
        tmp_content_html += '<div class="commodity_detail">';
        //tmp_content_html += ' onclick="showProductDetailInfo(\'' + item['id'] + '\',3)">';
        tmp_content_html += "<h5>" + item["name"] + "</h5>";
        tmp_content_html += '<div class="product_price">';
        if (item["amount"] == 0) {
            // 2-unpaid
            item["new_price"] = "0.00";
            item["old_price"] = "";
        } else {
            // 1-paid
            item["new_price"] = item["new_price"];
            item["old_price"] = item["old_price"];
        }
        new_price += parseFloat(item["new_price"]) * parseInt(item["amount"]);
        old_price += parseFloat(item["old_price"]) * parseInt(item["amount"]);

        tmp_content_html += '<h5><span class="detail_left">' + getPrice(item["new_price"]) + "</span>";
        tmp_content_html += '<span class="detail_right">';
        tmp_content_html += "<strike>" + getPrice(item["old_price"]) + "</strike>";

        tmp_content_html += "</span>";
        tmp_content_html += "</h5>";
        tmp_content_html += "<h5 style='font-size: 11pt;'>数量 :  <b>" + parseInt(item["amount"]) * parseInt(orderItem.amount) + "</b>";
        tmp_content_html += '<span class="detail_right"><a >赠品</a></span>';
        tmp_content_html += "</h5>";
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
    }
    if (orderItem.group_success != 1) new_price = old_price;
    tmp_content_html += '<div id="order_footer' + orderItem["id"] + '" class="order_footer" ';
    tmp_content_html += 'style="height:40px;">';
    tmp_content_html += "<h5>共计 &nbsp;" + amount * parseInt(orderItem.amount) + "件商品, 总价 : &nbsp;";
    tmp_content_html += '<span class="detail_right">' + getPrice(new_price * parseInt(orderItem.amount)) + "</span>";
    tmp_content_html += "</h5>";

    tmp_content_html += "</div>";
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += "<table>";
    tmp_content_html += "<tr><th>付款方式 : </th>";
    tmp_content_html += "<td>" + payType[orderItem["pay_type"] - 1] + "</td></tr>";
    tmp_content_html += "<tr><th>买家留言 : </th>";
    //tmp_content_html += '<td>希望可以尽快发货哦!' + '</td></tr>';
    tmp_content_html += "<td>" + orderItem["note"] + "</td></tr>";
    if (orderItem.pay_type == 2) {
        orderItem.pay_price = 0;
        orderItem.pay_wallet = 0;
    }
    st = status;
    //var difference = (old_price - new_price) * parseInt(orderItem.amount);
    //orderItem['pay_wallet'] = (new_price - parseFloat(orderItem['pay_price']));
    switch (st) {
        case 5: // transaction closed
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += "<table>";
            tmp_content_html += "<tr><th>提交订单时间 : </th>";
            tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
            tmp_content_html += "<tr><th>交易关闭时间 : </th>";
            tmp_content_html += "<td>" + orderItem["closed_time"] + "</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";
            break;
        case 4: // transaction completed
            tmp_content_html += "<tr><th>扣除余额 : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_wallet, 1) + "元</td></tr>";
            tmp_content_html += "<tr><th>" + payType_item[orderItem["pay_type"] - 1] + " : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_price, 1) + "元</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += "<table>";
            tmp_content_html += "<tr><th>提交订单时间 : </th>";
            tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
            if (orderItem["pay_type"] == 1) {
                tmp_content_html += "<tr><th>付款时间 : </th>";
                tmp_content_html += "<td>" + orderItem["paid_time"] + "</td></tr>";
            }
            if (orderItem.distributed_time != null) {
                tmp_content_html += "<tr><th>送达时间 : </th>";
                tmp_content_html += "<td>" + orderItem["distributed_time"] + "</td></tr>";
                tmp_content_html += "<tr><th>配送员 : </th>";
                tmp_content_html += "<td>" + orderItem["dist_name"] + "";
                tmp_content_html += "<span>" + orderItem["dist_phone"] + "</span></td></tr>";
            } else {
                // tmp_content_html += '<tr><th>完成时间 : </th>';
                // tmp_content_html += '<td>' + orderItem['complete_time'] + '</td></tr>';
            }
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            break;
        case 6: // transaction refunded
            tmp_content_html += "<tr><th>扣除余额 : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_wallet, 1) + "元</td></tr>";
            tmp_content_html += "<tr><th>" + payType_item[orderItem["pay_type"] - 1] + " : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_price, 1) + "元</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += "<table>";
            tmp_content_html += "<tr><th>提交订单时间 : </th>";
            tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
            if (orderItem["pay_type"] == 1) {
                tmp_content_html += "<tr><th>付款时间 : </th>";
                tmp_content_html += "<td>" + orderItem["paid_time"] + "</td></tr>";
            }
            tmp_content_html += "<tr><th>申请退款时间 : </th>";
            tmp_content_html += "<td>" + orderItem["closed_time"] + "</td></tr>";
            tmp_content_html += "<tr><th>退款时间 : </th>";
            tmp_content_html += "<td>" + orderItem["refunded_time"] + "</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            break;
        case 7: // transaction canceled
            tmp_content_html += "<tr><th>扣除余额 : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_wallet, 1) + "元</td></tr>";
            tmp_content_html += "<tr><th>" + payType_item[orderItem["pay_type"] - 1] + " : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_price, 1) + "元</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += "<table>";
            tmp_content_html += "<tr><th>提交订单时间 : </th>";
            tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
            if (orderItem["pay_type"] == 1) {
                tmp_content_html += "<tr><th>付款时间 : </th>";
                tmp_content_html += "<td>" + orderItem["paid_time"] + "</td></tr>";
            }
            tmp_content_html += "<tr><th>申请取消时间 : </th>";
            tmp_content_html += "<td>" + orderItem["closed_time"] + "</td></tr>";
            tmp_content_html += "<tr><th>取消时间 : </th>";
            tmp_content_html += "<td>" + orderItem["completed_time"] + "</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            break;
        case 1: // payment waiting
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += "<table>";
            tmp_content_html += "<tr><th>提交订单时间 : </th>";
            tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";
            tmp_content_html += "<div><h5><br><br></h5></div>";
            if (orderItem["pay_type"] == "1") {
                tmp_content_html += '<button class="btn_confirm btn-grey" ';
                tmp_content_html += "onclick=\"showCancelOrderConfirm('" + orderItem.id + "')\">取消订单</button>";
                tmp_content_html += '<button class="btn_confirm btn-cyan" ';
                tmp_content_html += " onclick=\"showPaymentPageFromOrderDetail('";
                tmp_content_html += orderItem.id + "');\">付款</button>";
            } else {
                tmp_content_html += '<button class="btn_confirm btn-grey" ';
                tmp_content_html += "onclick=\"showCancelOrderConfirm('" + orderItem.id + "')\">取消订单</button>";
                tmp_content_html += '<button class="btn_confirm btn-cyan" ';
                tmp_content_html += " onclick=\"showPaymentPageFromOrderDetail('";
                tmp_content_html += orderItem.id + "');\">付款</button>";
            }

            break;
        case 3: // distribution waiting
            tmp_content_html += "<tr><th>扣除余额 : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_wallet, 1) + "元</td></tr>";
            tmp_content_html += "<tr><th>" + payType_item[orderItem["pay_type"] - 1] + " : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_price, 1) + "元</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += "<table>";
            tmp_content_html += "<tr><th>提交订单时间 : </th>";
            tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
            if (orderItem["pay_type"] == 1) {
                tmp_content_html += "<tr><th>付款时间 : </th>";
                tmp_content_html += "<td>" + orderItem["paid_time"] + "</td></tr>";
            }
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            break;
        case 2: // grouping waiting
            tmp_content_html += "<tr><th>扣除余额 : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_wallet, 1) + "元</td></tr>";
            tmp_content_html += "<tr><th>" + payType_item[orderItem["pay_type"] - 1] + " : </th>";
            tmp_content_html += '<td class="price">' + getPrice(orderItem.pay_price, 1) + "元</td></tr>";
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";

            tmp_content_html += '<div class="pay-info">';
            tmp_content_html += "<table>";
            tmp_content_html += "<tr><th>提交订单时间 : </th>";
            tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
            if (orderItem["pay_type"] == 1) {
                tmp_content_html += "<tr><th>付款时间 : </th>";
                tmp_content_html += "<td>" + orderItem["paid_time"] + "</td></tr>";
            }
            tmp_content_html += "</table>";
            tmp_content_html += "</div>";
            tmp_content_html += "<div><h5><br><br></h5></div>";

            if (orderItem["pay_type"] == 1) {
                tmp_content_html += '<div class="pay-button">';
                tmp_content_html += '<div class="disabled" onclick="applyCancelFeedback(\'';
                tmp_content_html += orderItem["id"] + "')\"><h5>取消订单</h5></div></div>";
            } else {
                tmp_content_html += '<div class="pay-button">';
                tmp_content_html += '<div class="disabled" onclick="showCancelOrderConfirm(\'';
                tmp_content_html += orderItem["id"] + "')\"><h5>取消订单</h5></div></div>";
            }

            break;
    }

    return tmp_content_html;
}

function myOrderApplyTemplate(orderItem) {
    var iconImage = [
        "assets/images/address@3x.png",
        "assets/images/goods@3x.png",
        "assets/images/choose_s_d@3x.png",
        "assets/images/choose_s_n@3x.png"
    ];
    var payType = ["线上支付", "货到付款"];
    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    if (orderItem.length == 0) return "";

    orderItem = orderItem[0];
    var myInfo = getSessionMyInfo();

    var status = parseInt(orderItem["grouping_status"]);
    var status_class_List = [
        "waiting",
        "waiting",
        "waiting",
        "completed",
        "closed",
        "closed"
    ];

    var tmp_content_html = "";

    tmp_content_html += '<div class="order title ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<img src="' + iconImage[0] + '" style="height: auto;">';
    tmp_content_html += '<div class="detail" style="width:calc(100vw - 70px);">';
    tmp_content_html += '<h5><span class="">' + myInfo.contact_name + "</span>";
    tmp_content_html += '<span class="">' + myInfo.contact_phone;
    tmp_content_html += "</span></h5>";
    tmp_content_html += "<h5><span>" + myInfo.user_addr + "<span></h5>";
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="order detail ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情<span></h5>';

    var item = orderItem;
    tmp_content_html += '<div class="commodity_body">';
    tmp_content_html += '<img src="' + getImageURL(item["product_image"]) + '"';
    if (pageItemType == 2) {        // from product detail
        tmp_content_html += " onclick=\"showProductDetailInfo('" + item["id"] + "',2)\">";
    } else {
        tmp_content_html += " onclick=\"showOrderDetailInfo('" + item["id"] + "',2)\">";
    }
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += "<h5>" + item["product_name"] + "</h5>";
    tmp_content_html += '<div class="product_price">';
    if (item["status"] == 2) {
        // 2-unpaid
        item["new_price"] = "0.00";
        item["old_price"] = "";
    }
    var amount = 0;
    var prods = orderItem.total_info;
    for (var i = 0; i < prods.length; i++) {
        var product = prods[i];
        amount += parseInt(product["amount"]);
    }
    tmp_content_html += '<h5><span class="detail_left">' + getPrice(item.new_price) + "</span>";
    tmp_content_html += '<span class="detail_right">';
    tmp_content_html += "<strike>" + getPrice(item.old_price) + "</strike>";
    tmp_content_html += "</span>";
    tmp_content_html += "</h5></div>";
    tmp_content_html += '<h5>数量 :  <b id="product_amount">' + item.cur_amount + '</b>'

    if (getPrice(item["new_price"], 1) == "0.00") {   // 2-unpaid
        tmp_content_html += '<span class="detail_right">';
        tmp_content_html += '<a>赠品</a>';
        tmp_content_html += "</span>";
    }

    tmp_content_html += '</h5>';
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div id="order_footer' + orderItem["id"] + '" class="order_footer" ';
    tmp_content_html += 'style="height:40px;">';
    tmp_content_html += "<h5>共计 &nbsp;" + (parseInt(item.cur_amount) * amount) + "件商品, 总价 : &nbsp;";
    tmp_content_html += '<span class="detail_right">' + getPrice(parseFloat(item.old_price) * parseInt(item.cur_amount)) + "</span>";
    tmp_content_html += "</h5>";
    tmp_content_html += "</div>";

    tmp_content_html += "</div>";

    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += "<table>";
    tmp_content_html += "<tr><td>付款方式 : </td>";
    tmp_content_html += '<td class="paytype" onclick="selectPayType();">';

    tmp_content_html += '<label><input type="radio" name="paytype" value="0">';
    tmp_content_html += '<i  id="paytype0" class="fa fa-circle-o"></i>' + payType[0] + "</label>";
    tmp_content_html += '<label><input type="radio" name="paytype" value="1">';
    tmp_content_html += '<i  id="paytype1" class="fa fa-circle-o"></i>' + payType[1] + "</label>";

    tmp_content_html += "</td></tr>";
    tmp_content_html += "</table>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="order detail bottom ' + status_class_List[status - 1] + '"';
    tmp_content_html += ' style="' + (getCouponStatus() == 1 ? "" : "display:none") + '"';
    tmp_content_html += " onclick=\"selectItemCheck('001')\">";
    tmp_content_html += "<div>优惠券</div>";
    tmp_content_html += '<img class="title_img" src="' + iconImage[3] + '"';
    tmp_content_html += ' id="itemCheck001">';
    tmp_content_html += '<h5 class="title_text"><span>满300减30<span></h5>';
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="my-feedback order">';
    tmp_content_html += '<textarea id="textarea" class="form-control" ';
    tmp_content_html += 'placeholder="买家留言" oninput="validateText();"></textarea>';
    tmp_content_html += '<div class="detail_right">';
    tmp_content_html += '<h5 id="textLength">0/100</h5></div>';
    tmp_content_html += "</div>";

    tmp_content_html += "<div><h5><br><br><br><br><br><br></h5></div>";

    tmp_content_html += '<div class="order apply">';

    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += "<table>";
    if ((parseFloat(item.old_price) * parseInt(item.cur_amount)) > getMySessionWallet())
        var wallet_pay_price = getMySessionWallet();
    else
        var wallet_pay_price = parseFloat(item.old_price) * parseInt(item.cur_amount);
    tmp_content_html += '<tr><td>已扣除钱包余额 : <span id="wallet_price">' + getPrice(wallet_pay_price) + "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "<tr><td>&nbsp;";
    tmp_content_html += "</td></tr>";
    tmp_content_html += '<tr><td>应付 : <span id="pay_price">'
    tmp_content_html += getPrice(parseFloat(item.old_price) * parseInt(item.cur_amount)) + "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "<tr><td>拼单成功后节省 : <span>"
    tmp_content_html += getPrice((parseFloat(item.old_price) - parseFloat(item.new_price)) * parseInt(item.cur_amount))
    tmp_content_html += "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "</table>";
    tmp_content_html += "</div>";

    tmp_content_html += '<button id="perform_order" class="btn_confirm btn-cyan" ';
    tmp_content_html += 'onclick="orderFromDetail();">提交订单</button>';

    tmp_content_html += "</div>";

    return tmp_content_html;
}

function myCartOrderApplyTemplate(orderItem) {
    var iconImage = [
        "assets/images/address@3x.png",
        "assets/images/goods@3x.png",
        "assets/images/choose_s_d@3x.png",
        "assets/images/choose_s_n@3x.png"
    ];
    var payType = ["线上支付", "货到付款"];

    var myInfo = getSessionMyInfo();

    if (orderItem.length == 0) return "";

    var status = parseInt(orderItem[0]["grouping_status"]);
    var status_class_List = [
        "waiting",
        "waiting",
        "waiting",
        "completed",
        "closed",
        "closed"
    ];

    var tmp_content_html = "";

    tmp_content_html += '<div class="order title ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<img src="' + iconImage[0] + '" style="height: auto;">';
    tmp_content_html += '<div class="detail" style="width:calc(100vw - 70px);">';
    tmp_content_html += '<h5><span class="">' + myInfo.contact_name + "</span>";
    tmp_content_html += '<span class="">' + myInfo.contact_phone;
    tmp_content_html += "</span></h5>";
    tmp_content_html += "<h5><span>" + myInfo.user_addr + "<span></h5>";
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="order detail ' + status_class_List[status - 1] + '">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '" style="height: auto; vertical-align: middle">';
    tmp_content_html += '<h5 class="title_text"><span>购物详情<span></h5>';
    var cartPayIds = sessionStorage.getObject('cartPayIds');
    var pay_price = 0;
    var diff_pay_price = 0;
    if (cartPayIds != null) {
        for (var kk = 0; kk < cartPayIds.length; kk++) {
            var item = [];
            for (var i = 0; i < orderItem.length; i++) {
                if (orderItem[i].id == cartPayIds[kk]) {
                    item = orderItem[i];
                    break;
                }
            }

            tmp_content_html += '<div class="commodity_body">';
            tmp_content_html += '<img src="' + getImageURL(item["product_image"]) + '"';
            // from product detail
            tmp_content_html += " onclick=\"showProductDetailInfo('" + item["id"] + "',2)\">";
            tmp_content_html += '<div class="commodity_detail">';
            tmp_content_html += "<h5>" + item["product_name"] + "</h5>";
            tmp_content_html += '<div class="product_price">';
            if (item["status"] == 2) {
                // 2-unpaid
                item["new_price"] = "0.00";
                item["old_price"] = "";
            }
            var amount = 0;
            var prods = item.total_info;
            for (var i = 0; i < prods.length; i++) {
                var product = prods[i];
                amount += parseInt(product["amount"]);
            }
            tmp_content_html += '<h5><span class="detail_left">' + getPrice(item.new_price) + "</span>";
            tmp_content_html += '<span class="detail_right">';
            tmp_content_html += "<strike>" + getPrice(item.old_price) + "</strike>";

            tmp_content_html += "</span>";
            tmp_content_html += "</h5></div>";
            tmp_content_html += '<h5>数量 :  <b id="product_amount">' + item.cur_amount + "</b>";
            if (getPrice(item["new_price"], 1) == "0.00") {  // 2-unpaid
                tmp_content_html += '<span class="detail_right">';
                tmp_content_html += '<a href="#" onclick="">赠品</a>';
                tmp_content_html += "</span>";
            }
            tmp_content_html += "</h5>";
            tmp_content_html += "</div>";
            tmp_content_html += "</div>";
            //   }
            tmp_content_html += '<div id="order_footer' + item["id"] + '" class="order_footer" ';
            if (kk == (cartPayIds.length - 1))
                tmp_content_html += 'style="height:40px; border-top:1px solid #f0f0f0;">';
            else
                tmp_content_html += 'style="height:40px; border-top:1px solid #f0f0f0; border-bottom:1px solid lightgrey;">';

            tmp_content_html += "<h5 style='padding: 6px 0;'>共计 &nbsp;" + (parseInt(item.cur_amount) * amount) + "件商品, 总价 : &nbsp;";
            tmp_content_html += '<span class="detail_right">' + getPrice(parseFloat(item.old_price) * parseInt(item.cur_amount)) + "</span>";
            tmp_content_html += "</h5>";
            tmp_content_html += "</div>";

            pay_price += (parseFloat(item.old_price) * parseInt(item.cur_amount));
            diff_pay_price += (parseFloat(item.old_price) - parseFloat(item.new_price)) * parseInt(item.cur_amount)
        }
    }
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += "<table>";
    tmp_content_html += "<tr><td>付款方式 : </td>";
    tmp_content_html += '<td class="paytype" onclick="selectPayType();">';

    tmp_content_html += '<label><input type="radio" name="paytype" value="0">';
    tmp_content_html += '<i  id="paytype0" class="fa fa-circle-o"></i>' + payType[0] + "</label>";
    tmp_content_html += '<label><input type="radio" name="paytype" value="1">';
    tmp_content_html += '<i  id="paytype1" class="fa fa-circle-o"></i>' + payType[1] + "</label>";

    tmp_content_html += "</td></tr>";
    tmp_content_html += "</table>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="order detail bottom ' + status_class_List[status - 1] + '"';
    tmp_content_html += ' style="' + ((getCouponStatus() == 1 && pay_price > 300) ? "" : "display:none") + '"';
    tmp_content_html += " onclick=\"selectItemCheck('001')\">";
    tmp_content_html += "<div>优惠券</div>";
    tmp_content_html += '<img class="title_img" src="' + iconImage[3] + '"';
    tmp_content_html += ' id="itemCheck001">';
    tmp_content_html += '<h5 class="title_text"><span>满300减30<span></h5>';
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="my-feedback order">';
    tmp_content_html += '<textarea id="textarea" class="form-control" ';
    tmp_content_html += 'placeholder="买家留言" oninput="validateText();"></textarea>';
    tmp_content_html += '<div class="detail_right">';
    tmp_content_html += '<h5 id="textLength">0/100</h5></div>';
    tmp_content_html += "</div>";

    tmp_content_html += "<div><h5><br><br><br><br><br><br></h5></div>";

    tmp_content_html += '<div class="order apply">';

    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += "<table>";

    if (pay_price > getMySessionWallet())
        var wallet_pay_price = getMySessionWallet();
    else
        var wallet_pay_price = pay_price;
    tmp_content_html += '<tr><td>已扣除钱包余额 : <span id="wallet_price">' + getPrice(wallet_pay_price) + "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "<tr><td>&nbsp;";
    tmp_content_html += "</td></tr>";
    tmp_content_html += '<tr><td>应付 : <span id="pay_price">' + getPrice(pay_price) + "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "<tr><td>拼单成功后节省 : <span>" + getPrice(diff_pay_price) + "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "</table>";
    tmp_content_html += "</div>";

    tmp_content_html += '<button id="perform_order" class="btn_confirm btn-cyan" ';
    tmp_content_html += 'onclick="orderFromDetail();">提交订单</button>';

    tmp_content_html += "</div>";

    return tmp_content_html;
}

function myGroupingItemTemplate(orderItem, status) {
    status = parseInt(status);
    if (status != 0 && parseInt(orderItem["grouping_status"]) != status)
        return "";
    gst = parseInt(orderItem["grouping_status"]);
    ost = parseInt(orderItem["status"]);
    if (gst == 0) return "";
    var products = orderItem.products;
    var old_price = 0,
        new_price = 0,
        total_count = 0;
    for (var i = 0; i < products.length; i++) {
        old_price +=
            parseFloat(products[i].old_price) * parseInt(products[i].amount);
        new_price +=
            parseFloat(products[i].new_price) * parseInt(products[i].amount);
        total_count += parseInt(products[i].amount);
    }
    status = gst;
    var tmp_content_html = "";
    //    if ((gst == 1 && ost == 2) || (gst == 2 && ost == 3) || (gst == 3 && ost == 5)) {
    if (true) {
        var status_class_List = ["completed", "failed", "waiting"];
        var status_string_List = ["已拼团", "拼团失败", "待成单"];

        tmp_content_html +=
            '<div class="order ' + status_class_List[status - 1] + '">';
        tmp_content_html += '<div class="order_header">';
        tmp_content_html += "<h5>订单编号 : " + orderItem["id"] + "</h5>";
        tmp_content_html +=
            '<h5 class="order_state">' + status_string_List[status - 1] + "</h5>";
        tmp_content_html += "</div>";

        tmp_content_html += '<div class="commodity_body"';
        tmp_content_html +=
            " onclick=\"showGroupingDetailInfo('" + orderItem["id"] + "')\">";
        tmp_content_html += '<img src="' + getImageURL(orderItem["logo"]) + '">';
        tmp_content_html += '<div class="commodity_detail">';
        tmp_content_html += "<h5>" + orderItem["name"] + "</h5>";
        tmp_content_html += '<div class="product_price">';

        if (false) {//(orderItem.group_success == 2) {
            tmp_content_html +=
                '<h5><span class="detail_left"><strike>' +
                getPrice(new_price) +
                "</strike></span>";
            tmp_content_html += '<span class="detail_right">' + getPrice(old_price);
        } else {
            tmp_content_html +=
                '<h5><span class="detail_left">' + getPrice(new_price) + "</span>";
            tmp_content_html +=
                '<span class="detail_right"><strike>' +
                getPrice(old_price) +
                "</strike>";
        }

        tmp_content_html += "</span></h5></div>";
        tmp_content_html += "<h5>数量 :  <b>" + total_count + "</b></h5>";

        tmp_content_html += "</div>";
        tmp_content_html += "</div>";

        tmp_content_html +=
            '<div id="order_footer' + orderItem["id"] + '" class="order_footer" ';
        tmp_content_html += 'style="height:' + (status > 3 ? "25" : "55") + 'px;">';
        tmp_content_html +=
            "<h5>共计 &nbsp;" + orderItem["amount"] + "件商品, 总价 : &nbsp;";
        if (orderItem.group_success != 1) new_price = old_price;
        tmp_content_html +=
            '<span class="detail_right">' +
            getPrice(new_price * parseInt(orderItem.amount)) +
            "</span>";
        tmp_content_html += "</h5>";

        if (status <= 3) {
            switch (status) {
                case 1:
                    tmp_content_html +=
                        "<div onclick=\"showOrderDetailInfo('" +
                        orderItem["id"] +
                        "')\">" +
                        "<h5>查看订单详情</h5></div>";
                    tmp_content_html +=
                        "<div onclick=\"showGroupingDetailInfo('" +
                        orderItem["id"] +
                        "')\">" +
                        "<h5>查看拼团详情</h5></div>";
                    break;
                case 2:
                    tmp_content_html +=
                        "<div onclick=\"showOrderDetailInfo('" +
                        orderItem["id"] +
                        "')\">" +
                        "<h5>查看订单详情</h5></div>";
                    tmp_content_html +=
                        "<div onclick=\"showGroupingDetailInfo('" +
                        orderItem["id"] +
                        "')\">" +
                        "<h5>查看拼团详情</h5></div>";
                    break;
                case 3:
                    tmp_content_html +=
                        "<div onclick=\"showOrderDetailInfo('" +
                        orderItem["id"] +
                        "')\">" +
                        "<h5>查看订单详情</h5></div>";
                    tmp_content_html +=
                        "<div onclick=\"showGroupingDetailInfo('" +
                        orderItem["id"] +
                        "')\">" +
                        "<h5>查看拼团详情</h5></div>";
                    break;
            }
        }
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
    }

    return tmp_content_html;
}

function myGroupingDetailTemplate(orderItem, status) {
    var statusList = ["7", "8", "2", "2"];
    var iconImage = {
        "2": "assets/images/pd_loading@3x.png",
        "7": "assets/images/pd_success@3x.png",
        "8": "assets/images/pd_fail@3x.png"
    };
    var payType = ["线上支付", "货到付款"];
    var count = new Date();
    var hrs = count.getHours();
    var mins = count.getMinutes();
    var secs = count.getSeconds();

    if (!getAuthorizationStatus()) return noItemsTemplate(2);

    var status_message_List = [
        "恭喜您，拼团成功，系统将尽快退还多余金额！",
        "很遗憾，拼单失败，卖家将按照原价给您发货！",
        "还未满足成单条件，请您耐心等候！",
        "还未满足成单条件，请您耐心等候！"
    ];

    var status_string_List = ["拼团成功", "拼团失败", "拼团中", "拼单中"];
    status = parseInt(orderItem.grouping_status) - 1;

    var tmp_content_html = "";
    var progress = Math.round(
        orderItem.man_info.length / parseInt(orderItem.man_cnt) * 100
    );

    if (parseInt(orderItem.group_success) == 1) {
        status = 0;
        progress = 100;
    }

    tmp_content_html += '<div class="grouping">';
    tmp_content_html += '<div class="grouping_header">';
    tmp_content_html += '<h5><img src="' + iconImage[statusList[status]] + '">';
    tmp_content_html += "<span>" + status_string_List[status] + "</span></h5>";
    tmp_content_html +=
        "<h5><span>" + status_message_List[status] + "</span></h5>";

    tmp_content_html += '<div class="commodity_progress">';
    tmp_content_html += '<div class="progress progress-striped active">';
    tmp_content_html +=
        '<div class="progress-bar progress-bar-success" role="progressbar"';
    tmp_content_html += ' aria-valuenow="' + progress + '" aria-valuemin="0"';
    tmp_content_html += ' aria-valuemax="100" style="width: ' + progress + '%;">';
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="progress-text">' + progress + "%</div>";
    tmp_content_html += "</div></div>";

    ManInfo = orderItem["man_info"];
    if (ManInfo.length > 0) {

        ManInfo.sort(function (a, b) {
            if (a.ordered_time > b.ordered_time) return 1;
            else if (a.ordered_time < b.ordered_time) return -1;
            else return 0;
        })

        tmp_content_html += '<div class="man_info">';
        var item = "";

        for (var i = 0; i < (ManInfo.length > 2 ? 2 : ManInfo.length); i++) {
            item = ManInfo[i];
            tmp_content_html += '<img src="' + getImageURL(item["image"]) + '" ';
            tmp_content_html +=
                " onclick='showManDetailInfo(" +
                JSON.stringify(ManInfo) +
                ',"' +
                item["id"] +
                "\");'>";
        }
        if (ManInfo.length < 2) {
            tmp_content_html += '<img src="assets/images/man_b_n@3x.png">';
        }
        tmp_content_html += "</div>";
    }

    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="grouping pay-info">';
    tmp_content_html += "<table>";
    tmp_content_html += "<tr ";
    tmp_content_html +=
        " onclick=\"showProductDetailInfo('" + orderItem.activity_id + "',2)\">";
    tmp_content_html += "<th>商品名称 :</th>";
    tmp_content_html += "<td>" + orderItem["name"];
    tmp_content_html +=
        '<span class="title_arrow"><i class="fa fa-angle-right"></i></span>';
    tmp_content_html += "</td></tr>";
    tmp_content_html += "<tr><th>收货人 :</th>";
    tmp_content_html += "<td><span>" + orderItem["shop_contact"] + "</span>";
    tmp_content_html +=
        "<span>" + orderItem["shop_contact_phone"] + "</span></td></tr>";
    tmp_content_html += "<tr><th>收货地址 :</th>";
    tmp_content_html +=
        '<td style="font-size:10pt;line-height:1.3;word-break: break-all;word-wrap: break-word;">' + orderItem["shop_address"] + "</td></tr>";
    tmp_content_html += "<tr><th>发起拼单时间:</th>";
    tmp_content_html += "<td>" + orderItem["ordered_time"] + "</td></tr>";
    tmp_content_html += "</div>";
    if (progress == 100) {
        setTimeout(function () {
            $(".progress-bar.active, .progress.active .progress-bar").css({background: "#6bbdff"});
            $('.progress-text').css({'color': 'white', 'mix-blend-mode': 'normal'});
        }, 10);
    } else {
        setTimeout(function () {
            $('.progress-text').css({'color': 'white', 'mix-blend-mode': 'normal'});
        }, 10);
    }

    return tmp_content_html;
}

function manDetailTemplate(manInfos, id) {
    var iconImage = ["assets/images/close@3x.png"];
    var manData = manInfos;
    manData.sort(function (a, b) {
        if (a.ordered_time > b.ordered_time) return 1;
        else if (a.ordered_time < b.ordered_time) return -1;
        else return 0;
    })
    manInfos = manData;
    var tmp_content_html = "";
    tmp_content_html += '<div class="grouping mans">';
    tmp_content_html += '<div class="close_button" ';
    tmp_content_html += 'onclick="hideModal();">';
    tmp_content_html += '<img src="assets/images/close@3x.png" style="border:none;"></div>';
    tmp_content_html += '<div class="grouping_header">';
    for (var i = 0; i < manInfos.length; i++) {
        item = manInfos[i];
        //if (item.id != id) continue;
        if (i != 0) continue;
        tmp_content_html += '<h5><img src="' + getImageURL(item.image) + '"></h5>';
        tmp_content_html += "<h5>" + item.name + "</h5>";
        tmp_content_html += "<h5><span>" + item.ordered_time + "参与了拼单</span></h5>";
        break;
    }
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="order detail" style="text-align: left;">';
    for (var i = 0; i < manInfos.length; i++) {
        item = manInfos[i];
        //if (item.id == id) continue;
        if (i == 0) continue;
        tmp_content_html +=
            '<img class="title_img" src="' + getImageURL(item.image) + '" style="width:37px;height:37px;padding:7px;">';
        tmp_content_html += '<h5 class="title_text" style="padding-left:5px;">' + item.name;
        tmp_content_html += "<span>" + item.ordered_time + "加入了拼单</span></h5><br>";
    }
    tmp_content_html += "<br><br></div>";
    tmp_content_html += "</div>";

    return tmp_content_html;
}

function myIntegralTemplate(status) {
    var tmp_content_html = "";
    tmp_content_html += '<div class="integral-item">';
    tmp_content_html += '<div class="border">';
    tmp_content_html += '<h5 class="title"><span>积分规则</span></h5>';
    tmp_content_html += "<h5>1,每实际消费1元积1分</h5>";
    tmp_content_html += "<h5>2,根据积分等级定期可以参与优惠活动</h5>";

    tmp_content_html += "</div>";
    tmp_content_html += "</div>";
    return tmp_content_html;
}

function myMenuItemsTemplate(menuInfo, status) {
    var tmp_content_html = "";
    if (menuInfo["name"] == "退出登录")
        tmp_content_html += '<div class="divider-line"></div>';
    tmp_content_html +=
        '<div class="order detail menu-item" onclick="selectMenu(';
    tmp_content_html += menuInfo["id"] + ')">';
    if (menuInfo["icon"] != undefined)
        tmp_content_html +=
            '<img class="title_img" src="' + menuInfo["icon"] + '">';
    tmp_content_html +=
        '<h5 class="title_text"><span>' + menuInfo["name"] + "<span></h5>";
    tmp_content_html +=
        '<h5 class="title_arrow"><i class="fa fa-angle-right"></i></h5>';
    tmp_content_html += "</div>";
    return tmp_content_html;
}

function myCouponItemsTemplate(couponInfo, status) {
    couponInfo = {
        image: "assets/images/discountCoupon@3x.gif"
    };
    var tmp_content_html = "";
    tmp_content_html += '<img class="" src="' + couponInfo["image"] + '">';
    return tmp_content_html;
}

function myTransactionItemsTemplate(transInfo) {
    var title = "购买商品";
    var type = parseFloat(transInfo["price"]) > 0 ? 1 : 2;
    var pay_type = parseInt(transInfo["type"]) == 1 ? "线上支付" : "货到付款";
    var tmp_content_html = "";
    tmp_content_html += '<div class="trans-info">';
    tmp_content_html += "<table>";
    tmp_content_html +=
        '<tr><th style="color: black;">' + transInfo["content"] + "</th>";
    tmp_content_html +=
        "<td " +
        (type == 1 ? 'style="color:black;font-size:12pt;">-' : 'style="color:black;font-size:12pt;">+') +
        getPrice(Math.abs(parseFloat(transInfo["price"])), false) +
        "</td></tr>";
    tmp_content_html += "<tr><th>" + transInfo["trans_time"] + "</th>";
    tmp_content_html += "<td>" + title + "</td></tr>";
    tmp_content_html += "</table>";
    tmp_content_html += "</div>";

    return tmp_content_html;
}

function myFavoriteItemTemplate(activityItem) {
    var type = parseInt(activityItem.type);
    if (type != 0) return "";
    var id = activityItem.object_id;
    var item = activityItem.detail;
    console.log(type);
    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity my-collection">';
    tmp_content_html += '<div class="commodity_header">';
    tmp_content_html += '<h5>距拼团結束还有<span id="remain_time' + item.id + '">' + getTimeString(item["end_time"]) + "</span></h5>";
    tmp_content_html += "</div>";

    calculateGroupingTime(item, item.id);

    tmp_content_html += '<div class="commodity_body"';
    tmp_content_html += " onclick=\"showProductDetailInfo('" + id + "',1)\">";
    tmp_content_html += '<img src="' + getImageURL(item["product_image"]) + '">';
    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += "<h5>" + item["product_name"] + "</h5></div>";
    tmp_content_html += '<div class="commodity_progress" style="width:75%">';
    tmp_content_html += '<span class="detail_left">' + getPrice(item["new_price"]) + "</span>";
    tmp_content_html += '<span class="detail_right" style="color:darkgrey; "><strike>' + getPrice(item["old_price"]);
    tmp_content_html += "</strike></span>";
    tmp_content_html += "</div>";
    tmp_content_html += "</div></div>";
    return tmp_content_html;
}

function myProviderItemTemplate(providerItem) {
    var type = parseInt(providerItem.type);
    if (type != 1) return "";
    var id = providerItem.id;
    var item = providerItem.detail;
    var tmp_content_html = "";
    tmp_content_html += '<div class="commodity my-provider"';
    tmp_content_html +=
        " onclick=\"showProviderDetailInfo('" + item.id + "',2)\">";
    tmp_content_html += '<img src="' + getImageURL(item["image"]) + '">';
    tmp_content_html += "<h5>" + item["name"] + "</h5>";
    tmp_content_html += "</div>";
    return tmp_content_html;
}

function myProviderDetailTemplate(providerItem) {
    var favourite_img = [
        "assets/images/product_tabbar_icon2_n@3x.png",
        "assets/images/product_tabbar_icon2_d@3x.png"
    ];
    var iconImage = [
        "assets/images/address@3x.png",
        "assets/images/goods@3x.png"
    ];
    var tmp_content_html = "";
    var item = providerItem; //.detail;
    tmp_content_html += '<div class="commodity_body provider-detail">';
    tmp_content_html += '<div class="left-item">';
    tmp_content_html +=
        '<img id="provider_img" src="' + getImageURL(item["image"]) + '">';
    // tmp_content_html += ' onclick="showProviderDetailInfo(' + providerItem['id'] + ')">';
    tmp_content_html +=
        '<h5 id="favourStatus" onclick="setProviderFavourite(\'' +
        providerItem.id +
        "')\">";
    tmp_content_html += '<img src="' + favourite_img[0] + '"> ';
    tmp_content_html += "收藏</h5>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="commodity_detail">';
    tmp_content_html += "<h5>" + item["name"] + "</h5>";
    tmp_content_html += "<h5>" + item["provider_content"] + "</h5>";
    tmp_content_html += "<h5>代理品牌Logo : ";
    var logos = item.logos;
    var leng = logos.length;
    if (leng > 2) leng = 2;
    for (var i = 0; i < leng; i++) {
        tmp_content_html += '<img src="' + getImageURL(logos[i]) + '">';
    }
    tmp_content_html += "</h5>";
    tmp_content_html += "<h5>具体地址 : " + item.address + "</h5>";
    tmp_content_html += "<h5>联系电话 : " + item.contact_phone + "</h5>";
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="order detail provider-detail">';
    tmp_content_html += '<img class="title_img" src="' + iconImage[1] + '">';
    tmp_content_html += '<h5 class="title_text"><span>开团产品</span></h5>';

    var prods = item.products;
    for (i = 0; i < prods.length; i++) {
        tmp_content_html += mainActivityItemTemplate(prods[i]);
    }
    if (prods.length == 0) {
        tmp_content_html += "<div><center>该区域总代理没有开团的产品。</center></div>";
    }
    tmp_content_html += "</div>";

    return tmp_content_html;
}

function myStoreInfoTemplate() {
    var storeItem = getSessionMyInfo();
    var iconImage = ["assets/images/phone@3x.png", "assets/images/name@3x.png"];
    if (storeItem == "") return "";

    //if(status!=0 && productItem['status']!=status) return "";
    var tmp_content_html = "";

    tmp_content_html += '<div class="store">';
    tmp_content_html += '<div class="store_info left">';
    tmp_content_html += '<h5><img src="' + iconImage[0] + '"><h5>';
    tmp_content_html += "<span>" + storeItem["contact_phone"] + "</span>";
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="store_info">';
    // tmp_content_html += '<h5><img src="' + getImageURL(storeItem["user_image"]) + '" ';
    tmp_content_html += '<h5><img src="' + iconImage[1] + '" ';
    tmp_content_html += ' style="border-radius:50%;width:18px;">';
    // tmp_content_html += ' onclick="showImageDetailInfo(\'';
    // tmp_content_html += getImageURL(storeItem["user_image"]) + "')\">";
    tmp_content_html += "<h5>";
    tmp_content_html += "<span>" + storeItem["contact_name"] + "</span>";
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    tmp_content_html += '<div class="store pay-info">';
    tmp_content_html += "<table>";
    tmp_content_html += '<div class="td_row"><div class="td_left" style="padding:12px 0 12px 10px;">地址</div>';
    tmp_content_html += '<div class="td_right">' + storeItem["user_addr"] + "</div><br></div>";
    tmp_content_html += '<div class="td_row"><div class="td_left" style="padding:12px 0 12px 10px;">营业执照编号</div>';
    tmp_content_html += '<div class="td_right">' + storeItem["number"] + "</div></div>";
    tmp_content_html += "</table>";
    tmp_content_html += "</div>";
    tmp_content_html += '<div class="store auth_img">';
    tmp_content_html += '<h5><img src="' + getImageURL(storeItem["cert_image"]) + '" ';
    tmp_content_html += " onclick=\"showImageDetailInfo('" + getImageURL(storeItem["cert_image"]) + "');\">";
    tmp_content_html += "<h5>";
    tmp_content_html += "</div>";
    //    tmp_content_html += '<button class="store btn_confirm" ';
    //    tmp_content_html += 'onclick="applyStoreAuthrization();">提交认证</button>';

    return tmp_content_html;
}

function imageDetailTemplate(imgURL) {
    var iconImage = ["assets/images/close@3x.png"];

    var tmp_content_html = "";
    tmp_content_html += '<div class="store imgs">';
    tmp_content_html += '<div class="close_button" ';
    tmp_content_html += " onclick=\"$('#confirm_dialog').modal('hide')\">";
    tmp_content_html += '<img src="assets/images/close@3x.png"></div>';
    tmp_content_html += '<div class="grouping_header">';
    tmp_content_html += '<h5><img src="' + imgURL + '"></h5>';
    tmp_content_html += "</div>";
    tmp_content_html += "</div>";

    return tmp_content_html;
}

function mainNewsItemTemplate(newsInfo) {
    var cur = new Date();
    var tmp = newsInfo.sent_time.split("-");
    var sent_time = "";
    for (var i = 0; i < tmp.length; i++) {
        sent_time += i == 0 ? tmp[i] : "/" + tmp[i];
    }
    sent_time = new Date(sent_time);
    var cy = cur.getFullYear();
    var cm = cur.getMonth() + 1;
    var cd = cur.getDate();
    var y = sent_time.getFullYear();
    var m = sent_time.getMonth() + 1;
    var d = sent_time.getDate();
    var h = sent_time.getHours();
    var i = sent_time.getMinutes();

    if (m < 10) m = "0" + m;
    if (d < 10) d = "0" + d;
    if (h < 10) h = "0" + h;
    if (i < 10) i = "0" + i;

    sent_time = Math.floor((cur.valueOf() - sent_time.valueOf()) / 1000 / 60);
    if (sent_time >= 0 && sent_time < 2) sent_time = "刚刚";
    else if (sent_time > 0 && sent_time < 20) sent_time = sent_time + "分钟前";
    else {
        sent_time = Math.floor(
            (new Date(cy, cm, cd).valueOf() - new Date(y, m, d).valueOf()) / 1000
        );
        if (sent_time == 0) sent_time = "今天 " + h + ":" + i;
        else if (sent_time == 3600 * 24) sent_time = "昨天 " + h + ":" + i;
        else sent_time = y + "-" + m + "-" + d;
    }
    var tmp_content_html = "";
    tmp_content_html += '<div class="news-item">';
    //tmp_content_html += ' onclick="showMessage(\'' + newsInfo['content'] + '\')">';
    tmp_content_html += "<h5>" + newsInfo["content"] + "</h5>";
    tmp_content_html += '<h5 class="time_text">' + sent_time + "</h5>";
    tmp_content_html += "</div>";
    return tmp_content_html;
}

function myCartItemTemplate(productItems, index) {
    // index is order identifier
    var myWallet = getMySessionWallet();

    var iconImage = [
        "assets/images/choose_b_d@3x.png",
        "assets/images/choose_b_n@3x.png",
        "assets/images/choose_s_d@3x.png",
        "assets/images/choose_s_n@3x.png",
        "assets/images/failed_notice@2x.png",
        "assets/images/choose_b_no@2x.png",
        "assets/images/delete_notice@2x.png"
    ];
    var payType = ["线上支付", "货到付款"];

    var status_class_List = [
        "waiting",
        "waiting",
        "waiting",
        "completed",
        "closed",
        "closed"
    ];
    var status_string_List = ["待付款", "待成团", "待发货", "交易完成", "已退款", "交易关闭"];

    var tmp_content_html = "";

    if (productItems.length == 0) return noItemsTemplate(4);

    //    tmp_content_html += '<div class="order cart ' + status_class_List[0] + '">';

    var amount = 0;
    var new_price = 0;
    for (var i = 0; i < productItems.length; i++) {
        var item = productItems[i];
        var id = item["id"];
        var itemStatus = parseInt(item.cart_include_status) == 1 ? true : false;
        if (parseInt(item["grouping_status"]) >= 4) continue;
        amount += parseInt(item["amount"]);

        tmp_content_html += '<div class="order cart failed" ';
        tmp_content_html += ' id="cartitem_' + item["id"] + '">';

        tmp_content_html += '<img class="status_notice" style="opacity:0;"';
        tmp_content_html += ' id="status_notice' + item["id"] + '"src="' + iconImage[6] + '" >';
        tmp_content_html += '<div class="btn_delete" id="btn_delete' + item["id"] + '" ';
        tmp_content_html += ' onclick="performDeleteItem(' + item["id"] + ')">';
        tmp_content_html += "<span>删除</span>";
        tmp_content_html += '<div style="height:100%;width:1px;display: inline-block; vertical-align: middle"></div>';
        tmp_content_html += "</div>";

        tmp_content_html += '<div class="commodity_body" id="failedItem' + item["id"] + '" >';

        if (getPrice(item["new_price"], 1) != "0.00") {
            // 2-unpaid
            tmp_content_html += '<img class="check_icon" src="' + (itemStatus ? iconImage[0] : iconImage[1]) + '"';
            tmp_content_html += ' id="itemCheck' + item["id"] + '"';
            tmp_content_html += ' onclick="selectItemCheck(' + item["id"] + ')">';

            tmp_content_html += '<img class="body_img" src="' + getImageURL(item["product_image"]) + '"';
            tmp_content_html += ' onclick="selectItemCheck(' + item["id"] + ')">';
        } else {
            tmp_content_html += '<img class="check_icon" src="' + iconImage[5] + '"';
            tmp_content_html += ' id="itemCheck' + item["id"] + '">';
            tmp_content_html += '<img class="body_img" src="' + getImageURL(item["product_image"]) + '">';
        }

        tmp_content_html += '<div class="commodity_detail">';

        tmp_content_html += "<h5";
        tmp_content_html += " onclick=\"showProductDetailInfo('" + item["id"] + "',4)\">";
        tmp_content_html += item["product_name"] + "</h5>";

        tmp_content_html += '<div class="product_price" ';
        tmp_content_html += " onclick=\"showProductDetailInfo('" + item["id"] + "',4)\">";

        if (item["amount"] == 0) {
            // 2-unpaid
            item["new_price"] = "0.00";
            item["old_price"] = "";
        } else if (itemStatus)
            new_price += parseFloat(item["new_price"]) * parseInt(item["cur_amount"]);
        tmp_content_html += '<h5><span class="detail_left">' + getPrice(item["new_price"]) + "</span>";
        tmp_content_html += '<span class="detail_right">';
        tmp_content_html += "<strike>" + getPrice(item["old_price"]) + "</strike>";

        tmp_content_html += "</span>";
        tmp_content_html += "</h5></div>";

        tmp_content_html += '<div class="product_amount">';
        tmp_content_html += '<button class="btn-right" onclick="increaseAmount(' + item["id"] + ')">+</button>';
        tmp_content_html += '<input id="product_amount' + id + '" value="' + item["cur_amount"] + '" ';
        tmp_content_html += 'oninput="validateAmount(' + id + ')">';
        tmp_content_html += '<input id="min_amount' + id + '" value="' + item["min_amount"] + '" ';
        tmp_content_html += 'style="display: none;">';
        tmp_content_html += '<input id="max_amount' + id + '" value="';
        tmp_content_html += parseInt(item["max_amount"]) + '" ';
        tmp_content_html += 'style="display: none;">';
        tmp_content_html += '<button class="btn-left" onclick="decreaseAmount(' + item["id"] + ')">-</button>';
        tmp_content_html += "<h5>数量 : </h5>";
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
        for (var j = 0; j < productItems[i].total_info.length; j++) {
            item = productItems[i].total_info[j];
            if (getPrice(item.price, 1) != "0.00") continue;
            tmp_content_html += '<img class="status_notice" style="opacity:0;"';
            tmp_content_html += ' "src="' + iconImage[6] + '" >';

            tmp_content_html += '<img class="check_icon" src="' + iconImage[6] + '"';
            tmp_content_html += ' style="opacity:0;">';

            tmp_content_html += '<img class="body_img" src="' + getImageURL(productItems[i]["product_image"]) + '"';
            tmp_content_html += ' onclick="selectItemCheck(' + productItems[i]["id"] + ')">';

            tmp_content_html += '<div class="commodity_detail">';

            tmp_content_html += "<h5 ";
            tmp_content_html += " onclick=\"showProductDetailInfo('" + productItems[i]["id"] + "',4)\">";

            tmp_content_html += productItems[i]["product_name"] + "</h5>";
            tmp_content_html += '<div class="product_price" ';
            tmp_content_html += " onclick=\"showProductDetailInfo('" + productItems[i]["id"] + "',4)\">";

            tmp_content_html += '<h5><span class="detail_left" ><a style="font-size: 8pt;">赠品</a></span></h5>';
            tmp_content_html += '<h5><span class="detail_left">' + getPrice(item["price"]) + "</span>";

            tmp_content_html += '<div style="width:100%; margin-left: 52%; font-size: 11pt;font-weight:300;">数量 : &nbsp; x ';
            tmp_content_html += '<span class="added_amount_' + productItems[i].id + '">' + productItems[i].cur_amount + "</span></div>";
            tmp_content_html += "</div>";
            tmp_content_html += "</div>";
        }
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
    }

    for (var i = 0; i < productItems.length; i++) {
        var item = productItems[i];
        itemStatus = parseInt(item.cart_include_status) == 1 ? true : false;

        ////  simulation mode
        //item.grouping_status = 4
        ////

        if (parseInt(item["grouping_status"]) < 4) continue;
        tmp_content_html += '<div class="order cart failed" ';
        tmp_content_html += ' id="cartitem_' + item["id"] + '" ';
        tmp_content_html += ' onclick="cancelDeleteItem(\'' + item['id'] + '\',' + item.grouping_status + ')">';
        // tmp_content_html += '<div class="status_notice">';
        // tmp_content_html += '已失败';
        // tmp_content_html += '</div>';

        tmp_content_html += '<img class="status_notice" ';
        tmp_content_html += 'id="status_notice' + item["id"] + '"src="' + iconImage[4] + '" >';
        tmp_content_html += '<div class="btn_delete" id="btn_delete' + item["id"] + '" ';
        tmp_content_html += ' onclick="performDeleteItem(' + item["id"] + ')">';
        tmp_content_html += "<span>删除</span>";
        tmp_content_html += '<div style="height:100%;width:1px;display: inline-block; vertical-align: middle"></div>';
        tmp_content_html += "</div>";

        tmp_content_html += '<div class="commodity_body" id="failedItem' + item["id"] + '" >';
        tmp_content_html += '<img class="check_icon" src="' + (itemStatus ? iconImage[0] : iconImage[1]) + '"';
        tmp_content_html += ' id="itemCheck' + item["id"] + '"';
        tmp_content_html += ' onclick="cancelDeleteItem(\'' + item['id'] + '\',' + item.grouping_status + ')">';

        tmp_content_html += '<img class="body_img" src="' + getImageURL(item["product_image"]) + '"';
        tmp_content_html += ' id="itemCheck' + item["id"] + '"';
        tmp_content_html += ' onclick="cancelDeleteItem(\'' + item['id'] + '\',' + item.grouping_status + ')">';

        tmp_content_html += '<div class="commodity_detail" id="">';
        tmp_content_html += "<h5";
        tmp_content_html += ' onclick="cancelDeleteItem(\'' + item['id'] + '\',' + item.grouping_status + ')">';
        tmp_content_html += item["product_name"] + "</h5>";
        tmp_content_html += '<div class="product_price"';
        tmp_content_html += ' onclick="cancelDeleteItem(\'' + item['id'] + '\',' + item.grouping_status + ')">';
        if (item["amount"] == 0) {
            // 2-unpaid
            item["new_price"] = "0.00";
            item["old_price"] = "";
        } else {
            // 1-paid
            item["new_price"] = item["new_price"];
            item["old_price"] = item["old_price"];
        }
        if (itemStatus)
            new_price += parseFloat(item["new_price"]) * parseInt(item["amount"]);

        tmp_content_html += '<h5><span class="detail_left">' + getPrice(item["new_price"]) + "</span>";
        tmp_content_html += '<span class="detail_right">';

        tmp_content_html += "<strike>" + getPrice(item["old_price"]) + "</strike>";

        tmp_content_html += "</span>";
        tmp_content_html += "</h5></div>";
        tmp_content_html += '<div class="product_amount">';
        tmp_content_html += '<button class="btn-right" onclick="increaseAmount(' + item["id"] + ')">+</button>';
        tmp_content_html += '<input id="product_amount' + item["id"] + '" value="' + item["cur_amount"] + '" ';
        tmp_content_html += 'oninput="validateAmount(' + item["id"] + ')">';
        tmp_content_html += '<input id="min_amount' + id + '" value="' + item["min_amount"] + '" ';
        tmp_content_html += 'style="display: none;">';
        tmp_content_html += '<input id="max_amount' + id + '" value="';
        tmp_content_html += parseInt(item["max_amount"]) + '" ';
        tmp_content_html += 'style="display: none;">';

        tmp_content_html += '<button class="btn-left" onclick="decreaseAmount(' + item["id"] + ')">-</button>';

        tmp_content_html += "<h5>数量 : </h5>";
        tmp_content_html += "</div>";
        tmp_content_html += "</div>";

        for (var j = 0; j < productItems[i].total_info.length; j++) {
            item = productItems[i].total_info[j];
            if (getPrice(item.price, 1) != "0.00") continue;
            tmp_content_html += '<img class="status_notice" style="opacity:0;"';
            tmp_content_html += ' "src="' + iconImage[6] + '" >';

            tmp_content_html += '<img class="check_icon" src="' + iconImage[6] + '"';
            tmp_content_html += ' style="opacity:0;" >';
            //tmp_content_html += ' onclick="selectItemCheck(' + productItems[i]['id'] + ')">';

            tmp_content_html += '<img class="body_img" src="' + getImageURL(productItems[i]["product_image"]) + '"';
            tmp_content_html += ' onclick="selectItemCheck(' + productItems[i]["id"] + ')">';

            tmp_content_html += '<div class="commodity_detail">';

            tmp_content_html += "<h5 ";
            tmp_content_html += " onclick=\"showProductDetailInfo('" + productItems[i]["id"] + "',4)\">";

            tmp_content_html += productItems[i]["product_name"] + "</h5>";
            tmp_content_html += '<div class="product_price" ';
            tmp_content_html += " onclick=\"showProductDetailInfo('" + productItems[i]["id"] + "',4)\">";

            tmp_content_html += '<h5><span class="detail_left" ><a style="font-size: 8pt;">赠品</a></span></h5>';
            tmp_content_html += '<h5><span class="detail_left">' + getPrice(item["price"]) + "</span>";

            tmp_content_html += '<div style="width:100%; margin-left: 52%; font-size: 11pt;font-weight:300;">数量 : &nbsp; x ';
            tmp_content_html += '<span class="added_amount_' + productItems[i].id + '">' + productItems[i].cur_amount + "</span></div>";

            tmp_content_html += "</div>";
            tmp_content_html += "</div>";
        }

        tmp_content_html += "</div>";
        tmp_content_html += "</div>";
    }

    tmp_content_html += "<div><h5><br><br><br><br><br><br><br><br></h5></div>";

    var pay_wallet_rest = parseFloat(myWallet) - parseFloat(new_price);
    tmp_content_html += '<div class="order apply cart">';

    tmp_content_html += '<div class="pay-info">';
    tmp_content_html += "<table>";
    tmp_content_html += '<tr id="coupon_show"';
    tmp_content_html += ' style="border-bottom:1px solid #f0f0f0; ' + (getCouponStatus() == 1 ? "" : "display:none;") + '">';
    tmp_content_html += '<td colspan="2"';
    tmp_content_html += " onclick=\"selectItemCheck('001')\">";
    tmp_content_html += '优惠券<img class="title_img" src="' + iconImage[3] + '"';
    tmp_content_html += ' id="itemCheck001">';
    tmp_content_html += "满300减30";

    tmp_content_html += "</td></tr>";
    tmp_content_html += '<tr><td colspan="2">已扣除钱包余额 :';
    tmp_content_html += '<span id="wallet_price9999">' + getPrice(myWallet) + "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "<tr>";
    tmp_content_html += '<td rowspan="2"';
    tmp_content_html += " onclick=\"selectItemCheck('000')\">";
    tmp_content_html += '<img class="bottom_img" src="' + iconImage[3] + '"';
    tmp_content_html += ' id="itemCheck000">';
    tmp_content_html += "全选</td>";
    tmp_content_html += '<td style="padding: 0;">';
    tmp_content_html += '合计 :<span id="total_price9999">' + getPrice(new_price) + "</span>";
    tmp_content_html += '<span class="subtext" id="total_count9999">(共0件)</span>';
    tmp_content_html += "</td></tr>";
    tmp_content_html += '<tr><td style="padding: 0;">拼单成功后节省 : <span id="rest_price9999">';
    tmp_content_html += getPrice(pay_wallet_rest) + "</span>";
    tmp_content_html += "</td></tr>";
    tmp_content_html += "</table>";
    tmp_content_html += "</div>";

    tmp_content_html += '<button class="btn_confirm btn-cyan" id="performCart"';
    tmp_content_html += ' onclick="orderFromCart();">结算</button>';

    tmp_content_html += "</div>";

    return tmp_content_html;
}

function successProcessingTemplate(status) {
    var tmp_content_html = "";
    tmp_content_html += '<div class="success_top">';
    tmp_content_html += '<img src="assets/images/pd_success@3x.png">';
    tmp_content_html += "<div>";

    switch (parseInt(status)) { // 1-store activated, 2-
        case 1:
            tmp_content_html += '<h5 class="msg-body">';
            tmp_content_html += "提交成功，请耐心等待后台审核！";
            tmp_content_html += "</h5>";
            tmp_content_html += '<div class="btn_confirm" onclick="back()">返回</div>';
            break;
        case 2:
            tmp_content_html += '<h5 class="msg-body">';
            tmp_content_html += "提交成功，请耐心等待后台审核！";
            tmp_content_html += "</h5>";
            tmp_content_html +=
                '<div class="btn_confirm" onclick="history.back();">返回</div>';
            tmp_content_html += "<h5>暂无拼团消息</h5>";
            break;
        case 3:
            tmp_content_html += "暂无消息";
            break;
        case 4:
            tmp_content_html += "购物车空空如也, 快去逛逛吧!";
            break;
        case 5:
            sessionStorage.removeItem('cur_detail_index');
            sessionStorage.removeItem('cur_menu_index');

            tmp_content_html +=
                '</div><h5 class="msg-body" style="font-size: 12pt;margin:10px  0;">';
            tmp_content_html += "货到付款";
            tmp_content_html += "</h5>";
            tmp_content_html += '<h5 class="msg-body">';
            tmp_content_html += "订单提交成功，请耐心等待！";
            tmp_content_html += "</h5>";
            tmp_content_html +=
                '<div class="btn_confirm form-inline" onclick="location.href=\'order_manage.php\'">查看订单</div>';
            tmp_content_html +=
                '<div class="btn_confirm form-inline" onclick="location.href=\'home.php\'">首页逛逛</div>';
            break;
        case 6:
            sessionStorage.removeItem('cur_detail_index');
            sessionStorage.removeItem('cur_menu_index');
            var curPayInfo = sessionStorage.getObject("site_pay");
            // if (curPayInfo.length == 0) {
            //     showNotifyAlert("订单失败。");
            //     setTimeout(function () {
            //         history.back();
            //     }, 4000);
            //     return;
            // }
            var product_type = "订单编号 ";
            var order_name = "交易";
            if (parseInt(pageItemType) == 25) {
                product_type = "";
                order_name = "订单";
            }
            tmp_content_html += '<h5 class="msg-body" ';
            tmp_content_html +=
                ' style="font-size: 12pt;margin:10px 0; color: #38abff;">';
            tmp_content_html += "惠联彩";
            tmp_content_html += "</h5>";
            tmp_content_html += '<h5 class="msg-body"';
            tmp_content_html += ' style="font-size: 18pt;color: #38abff; margin: 0;">';
            tmp_content_html += getPrice(curPayInfo.cost);
            tmp_content_html += "</h5>";

            if (product_type == '') {
                tmp_content_html += '<h5 class="td_row">';
                tmp_content_html += '<div class="td_left">商  品</div>';
                tmp_content_html += '<div class="td_right" style="text-align: right">';
                tmp_content_html += product_type + curPayInfo.product_name + "</div>";
                tmp_content_html += "</h5>";
            } else {
                tmp_content_html += '<h5 class="td_row">';
                tmp_content_html += '<div class="td_left">商  品</div>';
                tmp_content_html += '<div class="td_right" style="text-align: right">';
                tmp_content_html += "惠联彩</div>";
                tmp_content_html += "</h5>";

                tmp_content_html += '<h5 class="td_row">';
                tmp_content_html += '<div class="td_left">' + product_type + '</div>';
                tmp_content_html += '<div class="td_right" style="text-align: right">';
                tmp_content_html += curPayInfo.product_name + "</div>";
                tmp_content_html += "</h5>";

            }

            tmp_content_html += '<h5 class="td_row">';
            tmp_content_html += '<div class="td_left">' + order_name + "时间</div>";
            tmp_content_html += '<div class="td_right" style="text-align: right">';
            tmp_content_html += curPayInfo.transaction_time + "</div>";
            tmp_content_html += "</h5>";
            tmp_content_html += '<h5 class="td_row">';
            tmp_content_html += '<div class="td_left">支付方式</div>';
            tmp_content_html += '<div class="td_right" style="text-align: right">';
            tmp_content_html += curPayInfo.pay_method + "</div>";
            tmp_content_html += "</h5>";
            tmp_content_html += '<h5 class="td_row">';
            tmp_content_html += '<div class="td_left">' + order_name + "编号</div>";
            tmp_content_html += '<div class="td_right" style="text-align: right">';
            tmp_content_html += curPayInfo.transaction_id + "</div>";
            tmp_content_html += "</h5>";
            tmp_content_html += "<h5><br><br></h5>";
            //             tmp_content_html += '<div class="btn_confirm form-inline" ';
            // //            if (pageItemType == 10)
            //                 tmp_content_html += ' onclick="location.href=\'order_manage.php\';">';
            // //            else if(pageItemType==25)
            // //                tmp_content_html += ' onclick="history.go(-2);">';
            //
            //             tmp_content_html += curPayInfo.pay_method + '支付</div>';
            tmp_content_html +=
                '<div class="btn_confirm form-inline" onclick="location.href=\'order_manage.php\'">查看订单</div>';
            tmp_content_html +=
                '<div class="btn_confirm form-inline" onclick="location.href=\'home.php\'">首页逛逛</div>';

            break;
    }
    tmp_content_html += "</div></div>";

    return tmp_content_html;
}

function noItemsTemplate(status) {
    var tmp_content_html = "";
    tmp_content_html += '<div class="order_no_items">';
    tmp_content_html += '<img src="assets/images/face@3x.gif">';
    tmp_content_html += '<h5 id="msg_txt">';
    switch (status) {
        case 1:
            tmp_content_html += "暂无订单信息";
            break;
        case 2:
            tmp_content_html += "暂无拼团信息";
            break;
        case 3:
            tmp_content_html += "暂无消息";
            break;
        case 4:
            tmp_content_html += "购物车空空如也, 快去逛逛吧!";
            break;
        case 5:
            tmp_content_html += "暂无收藏商品/区域总代理详情";
            break;
        case 6:
            tmp_content_html += "暂无优惠券";
            break;
        case 7:
            tmp_content_html += "暂无信息";
            break;
        case 8:
            tmp_content_html += "暂无商品信息";
            break;
        default:
            tmp_content_html += "暂无信息";
            break;
    }
    tmp_content_html += "</h5>";
    tmp_content_html += "</div>";
    return tmp_content_html;
}

function mainBottomItemTemplate(userType) {
    var menuItem = [{
        id: "1",
        name: "拼货",
        image: "assets/images/tabbar_icon1_d@3x.png",
        image_n: "assets/images/tabbar_icon1_n@3x.png"
    },
        {
            id: "2",
            name: "购物车",
            image: "assets/images/tabbar_icon2_d@3x.png",
            image_n: "assets/images/tabbar_icon2_n@3x.png"
        },
        {
            id: "3",
            name: "消息",
            image: "assets/images/tabbar_icon3_d@3x.png",
            image_n: "assets/images/tabbar_icon3_n@3x.png"
        },
        {
            id: "4",
            name: "我的",
            image: "assets/images/tabbar_icon4_d@3x.png",
            image_n: "assets/images/tabbar_icon4_n@3x.png"
        }
    ];
    var item;
    for (var i = 0; i < 4; i++) {
        item = menuItem[i];
        tmp_content_html += '<div class="bottom_item" ';
        tmp_content_html += ' onclick="selectBottomItem(' + item.id + ',0)">';
        tmp_content_html += '<img src="' + (i == 0 ? item.image : item.image_n);
        tmp_content_html += '" id="bottom_item_image' + item.id + '">';
        tmp_content_html += '<h5 id="bottom_item_text' + item.id;
        tmp_content_html +=
            '" style="color: ' + (i == 0 ? "#38abff" : "black") + '">';
        tmp_content_html += item.name + "</h5>";
        tmp_content_html += "</div>";
    }

    return tmp_content_html;
}