/**
 * Created by Administrator on 8/8/2017.
 */

var order_List = [];
var phone_num = "";

$(function(){
    phone_num = localStorage.getItem('phone_number');
    resize_orderlist();
    getMyOrdersFromServer();
    //loadOrderData();
});

window.addEventListener('resize', function(event){
    resize_orderlist();
});

function loadOrderData(){

    order_List = sessionStorage.getObject('cur_orders');
    if(order_List === null)
        getMyOrdersFromServer();
    else
        display_order_data();
}

function display_order_data(){
    //------- show the scenic list
    var state_class_List = ['order_using','order_unpaid','order_cancelled','order_expired'];
    var state_string_List = ['使用中','未付款','已取消','已过期'];

    // show individual scenic data
    var content_html_all = "";
    var content_html_unpaid = "";
    var content_html_cancelled = "";
    var content_html_expired = "";
    var tmp_content_html="";

    $('#tab_all').html(content_html_all);
    $('#tab_unpaid').html(content_html_unpaid);
    $('#tab_cancelled').html(content_html_cancelled);
    $('#tab_expired').html(content_html_expired);

    if(sessionStorage.getItem('phone_number') == null || sessionStorage.getItem('phone_number')=="") return;
    if( order_List == null) return;

    // show each order information in order list
    for( var i = 0; i < order_List.length; i++){
        tmp_content_html="";
        tmp_content_html += '<div class="order '+state_class_List[order_List[i]['state']-1] +'">';
        tmp_content_html += '<div class="order_header">';
        tmp_content_html += '   <h5>订单编号 : '+ order_List[i]['id']+'</h5>';
        tmp_content_html += '   <h5 class="order_state">'+state_string_List[order_List[i]['state']-1]+'</h5>';
        tmp_content_html += '</div>';
        tmp_content_html += '<div class="order_body" onclick="showOrderDetailInfo('+ i +')">';
        tmp_content_html += '   <img src="'+order_List[i]['image']+'">';
        tmp_content_html += '   <div>';
        tmp_content_html += '       <h5>'+order_List[i]['name']+'</h5>';

        if(order_List[i]['pay_method'] == 1)
            tmp_content_html += '   <h5 style="color: red">¥' + parseFloat( order_List[i]['value']).toFixed(2)+ '</h5>';
        else
            tmp_content_html += '   <h5>授权码 : ' + order_List[i]['value']+ '</h5>';

        tmp_content_html += '</div></div>';

        if(order_List[i]['state'] != "1"){
            tmp_content_html += '<div class="order_footer">';

            switch(order_List[i]['state'])
            {
                case "2":
                    tmp_content_html +='    <div onclick="cancelOrder('+order_List[i]['id']+')"><h5>取消订单</h5></div>';
                    tmp_content_html +='    <div onclick="pay_for_Order('+i+')"><h5>付款</h5></div>';
                    break;
                case "3":
                case "4":
                    tmp_content_html +='    <div onclick="purchase_again_Order('+i+')"><h5>重新购买</h5></div>';
                    break;
            }
            tmp_content_html += '</div>'
        }
        tmp_content_html +='</div>';

        content_html_all += tmp_content_html;
        switch (order_List[i]['state'])
        {
            case "2":
                content_html_unpaid += tmp_content_html;
                break;
            case "3":
                content_html_cancelled += tmp_content_html;
                break;
            case "4":
                content_html_expired += tmp_content_html;
                break;
        }
    }
    $('#tab_all').html(content_html_all);
    $('#tab_unpaid').html(content_html_unpaid);
    $('#tab_cancelled').html(content_html_cancelled);
    $('#tab_expired').html(content_html_expired);

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var index = $(e.target).closest('li').index();

        sessionStorage.setItem('order_tab_index', index);
    });

    var index = sessionStorage.getItem('order_tab_index');
    if( index != null)
        $('.nav-tabs li:eq('+index+') a').tab('show');
}

function cancelOrder(index) {
    sessionStorage.setItem('cancel_order_id', index);

    $('#confirm').show();
}

function OnCancel(){
    $('#confirm').hide();
    sessionStorage.removeItem('cancel_order_id');
}
function pay_for_Order(index) {
    // calculate order's price
    var cur_order = order_List[index];
    var real_cost = cur_order['cost'] * cur_order['discount_rate'];

    var payment_data = {
        type : cur_order['order_kind'],      // 1: tourism course, 2: scenic area,  3: attraction, 4: authorize code
        id : cur_order['id'],
        name: cur_order['name'],
        image: cur_order['image'],
        cost: cur_order['cost'],
        real_cost: real_cost
    };

    sessionStorage.setObject('payment_data', payment_data);
    window.location.href = '../views/purchase.html';
}

function purchase_again_Order(index) {
    pay_for_Order(index);
}

function showOrderDetailInfo(index)
{
    var cur_order = order_List[index];
    sessionStorage.setObject('cur_order', cur_order);

    window.location.href = '../views/order_detail.html';
}

function resize_orderlist(){
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

    // resize map region
    var map_top = document.getElementById('app_header').clientHeight;
    var map_width = document.getElementById('content').clientWidth;
    var map_height = document.body.clientHeight - map_top;
    $('#container').css({display:'block',width:map_width, height:map_height, top:map_top, bottom:0});

    var content_margin=(document.body.clientWidth-width)/2;
    $('#back_img').css({position:'fixed',left: content_margin+10});

    var header_height = document.getElementById('tab_header').clientHeight;
    $('#tab_all').css({height:map_height-header_height-4});
    $('#tab_unpaid').css({height:map_height-header_height-4});
    $('#tab_cancelled').css({height:map_height-header_height-4});
    $('#tab_expired').css({height:map_height-header_height-4});
}
