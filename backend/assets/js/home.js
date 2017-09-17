/*
 fileName: area.js
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {
    showTopLists(1);
});

function showTopLists(id) {

    $.ajax({
        type: 'post',
        url: baseURL + 'home_controller/home_listing',
        dataType: 'json',
        data: {id: id},
        success: function (res) {
            if (res.status == 'success') {
                $('#header_tbl').html(res.header);
                $('#content_tbl').html(res.content);
                $('#footer_tbl').html(res.footer);
            } else {
                alert('search failed!');
                console.log(res.data);
            }
        }
    });
}

function test_api111() {
    var posi = [116.404845, 39.898345];
    var id = '1200000018';
    var phone = '12345678901';
    $.ajax({
        type: 'POST',
        //url: 'http://www.ayoubc.com/backend/api/Areas/getMyAreaInfos',
        url: 'http://192.168.2.18/backend/api/Areas/setAreaBuyOrder',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'pos': posi,
            'id': id,
            'phone': phone
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function test_api() {
    var posi = [116.404845, 39.898345];
    var id = '1200000023';
    var phone = '24562456245';
    var cost = '00505000007';
    var cost = '6';
    var type = '3';
    var shopid = '6';
    $.ajax({
        type: 'POST',
        //url: 'http://www.ayoubc.com/backend/api/Areas/getMyOrderInfos',
//        url: 'http://192.168.2.18/backend/api/Areas/setAreaBuyOrder',
        url: 'http://www.ayoubc.com/backend/api/Areas/setPayOrder',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'id': id,
            'phone': phone,
            'cost': cost,
            'type': type,
            'shop': shopid,
            'pos': posi
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function test_api33() {
    var posi = [116.404845, 39.898345];
    var id = '11';
    var phone = '24562456245';
    var cost = '00402700012';
    var type = '4';
    var code = '123skla8kso98alk29lkngb23ioemv56';
    $.ajax({
        type: 'GET',
//        url: 'http://116.196.83.125/test/example/jsapi.php',
        url: 'http://www.ayoubc.com/test/example/jsapi.php',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'id': '3'
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}