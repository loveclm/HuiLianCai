/*
 fileName: area.js
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {
    if(provider_id == '0')
        showTopLists(1);
    else
        showTopLists(2);
});

function showTopLists(id) {

    $.ajax({
        type: 'post',
        url: baseURL + 'home_controller/home_listing',
        dataType: 'json',
        data: {id: id},
        success: function (res) {
            console.log(res);
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


function test_api() {
    var phone = 'aaa';
    var saleman = '13122132122';
    var password = '111111';
    var type = '3';
    var shopid = '6';
    $.ajax({
        type: 'POST',
        url: 'http://192.168.2.15/huiliancai/backend/api/shippingItems',
        dataType: 'json',
        data: {
            'userid': 'bbb',
            //'password': '111111',
            //'brand': 1,
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            alert(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}
