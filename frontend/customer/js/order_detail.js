/**
 * Created by Administrator on 8/9/2017.
 */

var order_detail = [];
var phone_num = "";

$(function(){
    phone_num = localStorage.getItem('phone_number');
    resize_order_detail();
    display_order_detail_data();
});

window.addEventListener('resize', function(event){
    resize_order_detail();
});

function back(){
    sessionStorage.removeItem('cur_order');
    history.back();
}

function display_order_detail_data(){
    //loading the information of the selected order
    order_detail = sessionStorage.getObject('cur_order');
    if(order_detail == null){
        $('#container').html("");
        $('#app_footer').html("");

        return;
    }

    //------- show the scenic list
    var state_string_List = ['使用中','未付款','已取消','已过期'];

    // show individual scenic data
    var content_html="";
    var footer_html="";
    // show each order information in order list
    content_html += '<div class="order order_using">';
    content_html += '<div class="order_header">';
    content_html += '   <h5>订单编号 : '+ order_detail['id']+'</h5>';
    content_html += '   <h5 class="order_state">'+state_string_List[order_detail['state']-1]+'</h5>';
    content_html += '</div>';
    content_html += '<div class="order_body">';
    content_html += '   <img src="'+order_detail['image']+'">';
    content_html += '   <div>';
    content_html += '       <h5>'+order_detail['name']+'</h5>';

    if(order_detail['pay_method'] == 1)
        content_html += '   <h5 style="color: red">¥' +order_detail['value']+ '</h5>';
    else
        content_html += '   <h5>授权码 : ' +order_detail['value']+ '</h5>';

    content_html += '</div></div></div>';

    footer_html += '<div class="order_footer">';

    switch(parseInt(order_detail['state']))
    {
        case 1:  // the case of using state
            // configure detail information
            content_html += '<div class="order_detail">';
            content_html += '   <h5>有交日期&nbsp&nbsp&nbsp  '+ order_detail['expiration_time']+'</h5>';
            content_html += '</div>';

            content_html += '<div class="order_detail">';
                if(order_detail['pay_method'] == 1){
                    content_html += '   <h5>下单时间&nbsp&nbsp&nbsp  '+ order_detail['order_time']+'</h5>';
                    content_html += '   <h5>购买时间&nbsp&nbsp&nbsp  '+ order_detail['paid_time']+'</h5>';
                }
                else{
                    content_html += '   <h5>验证时间&nbsp&nbsp&nbsp  '+ order_detail['paid_time']+'</h5>';
                }
            content_html += '</div>';
            //make control button
            footer_html +='    <div onclick="showMainpage()"><h5>开始导游</h5></div>';
            break;

        case 2:  // the case of unpaid state
            // configure detail information
            content_html += '<div class="order_detail">';
            content_html += '   <h5>下单时间&nbsp&nbsp&nbsp  '+ order_detail['order_time']+'</h5>';
            content_html += '</div>';
            //make control button
            footer_html +='    <div onclick="cancelOrder()"><h5>取消订单</h5></div>';
            footer_html +='    <div onclick="pay_for_Order()"><h5>付款</h5></div>';
            break;

        case 3:   // the case of cancelled state
            // configure detail information
            content_html += '<div class="order_detail">';
            content_html += '   <h5>下单时间&nbsp&nbsp&nbsp  '+ order_detail['order_time']+'</h5>';
            content_html += '   <h5>取消时间&nbsp&nbsp&nbsp  '+ order_detail['cancelled_time']+'</h5>';
            content_html += '</div>';
            //make control button
            footer_html +='    <div onclick="purchase_again_Order()"><h5>重新购买</h5></div>';
            break;

        case 4:  // the case of expired state
            // configure detail information
            content_html += '<div class="order_detail">';
            content_html += '   <h5>有交日期&nbsp&nbsp&nbsp  '+ order_detail['expiration_time']+'</h5>';
            content_html += '</div>';
            content_html += '<div class="order_detail">';
            content_html += '   <h5>下单时间&nbsp&nbsp&nbsp  '+ order_detail['order_time']+'</h5>';
            content_html += '   <h5>购买时间&nbsp&nbsp&nbsp  '+ order_detail['paid_time']+'</h5>';
            content_html += '</div>';
            //make control button
            footer_html +='    <div onclick="purchase_again_Order()"><h5>重新购买</h5></div>';
            break;
    }
    //content_html += '</div>'
    $('#container').html(content_html);
    $('#app_footer').html(footer_html);
}


function cancelOrder() {

    sessionStorage.setItem('cancel_order_id', order_detail['id']);

    $('#confirm').show();
}

function OnCancel(){
    $('#confirm').hide();
    sessionStorage.removeItem('cancel_order_id');
}

function pay_for_Order() {
    var cur_order = order_detail;
    // calculate order's price
    var real_cost = cur_order['cost'] * cur_order['discount_rate'];

    var payment_data = {
        type : 4,      // 1: tourism course, 2: scenic area,  3: attraction, 4: authorize code
        id : cur_order['id'],
        name: cur_order['name'],
        image: cur_order['image'],
        cost: cur_order['cost'],
        real_cost: real_cost
    };

    sessionStorage.setObject('payment_data', payment_data);
    window.location.href = '../views/purchase.html';
}

function purchase_again_Order() {
    pay_for_Order();
}

function showMainpage(){
    window.location.href = '../index.php';
}

function resize_order_detail(){
    initRatio = getDevicePixelRatio();
    var ratio = getDevicePixelRatio()/initRatio;
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var scale = Math.min(width/640,height/1010) * ratio;

    //width = 640*scale;
    $('#content').css({width:width, height:height});
    $('#app_header').css({width:width});
    $('#app_footer').css({width:width});

    // resize map region
    var map_top = document.getElementById('app_header').clientHeight;
    var map_width = document.getElementById('content').clientWidth;
    var map_height = document.body.clientHeight - map_top;
    $('#container').css({display:'block',width:map_width, height:map_height, top:map_top, bottom:0});

    var content_margin=(document.body.clientWidth-width)/2;
    $('#back_img').css({position:'fixed',left: content_margin+10});
}
