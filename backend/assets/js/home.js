/*
 fileName:
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
    var phone = '12345678901';
    var activity = ['201506347949', '1006301908', '201506349434'];
    var count = 1;
    var type = 1;
    var ids = [2006301908,101506350114,201506347949];
    //for( var i =0; i < 3; i++) {
        $.ajax({
            type: 'POST',
            url: 'http://192.168.2.15/huiliancai/backend/api/myFavorite',
            dataType: 'json',
            data: {
                //'type' : 1,
                //'brand' : 1
                'phone': '12345678901',
                //'order' : '10550775856966401',
                //'reason' : 'adksjfhkjdhkjadsf',
                //'coupon' : 0,
                //'wallet' : 0,
                //'money' : 40,
                //'type' : 0,
                //'object_id' : '1015075898158880',
                //'favorite_id' : 5,
                //'activity' : '1015075898158880',
                //'count':2,
                //'pay_method':1,
                //'password' : '111111'
                // 'type': 1,
                // 'name': 'akkjds',
                // 'address': 'adfdasakkjds',
                // 'contact_name': 'bbb',
                // 'contact_phone': '123121311',
                // 'logo': 'uploads/hlc15076923353753.png',
                // 'cert': 'uploads/hlc15076871603906.png',
                // 'cert_num': '1232132345435345'
            },
            success: function (data, textStatus, jqXHR) {
                console.log(data);
                //alert(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    //}
}
