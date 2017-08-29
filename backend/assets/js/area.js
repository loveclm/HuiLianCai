/*
 fileName: area.js
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

});

function deleteAreaConfirm(id) {
    $('#custom-confirm-delete-view').show();
    $('#current-areaid').val(id);
}

function deleteArea(url, type) {

    $('#custom-confirm-delete-view').hide();
    if (type == 1) {
        $.post(url + "api/Areas/remove/" + $('#current-areaid').val(), function (result) {
            location.href = url + 'area';
        });
    }
}

function deployAreaConfirm(id) {

    $('#custom-confirm-deploy-view').show();
    $('#current-areaid').val(id);
    $('#current-areastatus').val(1);
}

function undeployAreaConfirm(id) {

    $('#custom-confirm-deploy-view').show();
    $('#current-areaid').val(id);
    $('#current-areastatus').val(0);
}

function deployArea(url, type) {

    $('#custom-confirm-deploy-view').hide();
    if (type == 1) {

        var touristArea = {
            id: $('#current-areaid').val(),
            status: $('#current-areastatus').val()
        };

        $.post(url + "api/Areas/save/" + touristArea['id'], touristArea, function (result) {
            location.href = url + 'area';
        });
    }
}

function searchArea(url) {

    var name = $('#searchName').val();
    var address = $('#searchAddress :selected').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'all' : name;
    location.href = url + 'area/listing/' + name + '/' + address + '/' + status;
}

function test_api() {
    var posi = [116.404845, 39.898345];
    var id = '21';
    var phone = '24562456245';
    $.ajax({
        type: 'POST',
        url: 'http://admin:1234@116.196.83.125/backend/api/Areas/getAllCourseInfos',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'pos' : posi,
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

function test_api22() {
    var posi = [116.404845, 39.898345];
    var id = '';
    var phone = '24562456245';
    var cost = '00402700012';
    var type = '4';
    $.ajax({
        type: 'POST',
        url: 'http://116.196.83.125/backend/api/Areas/setAreaBuyOrder',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'id' : id,
            'phone': phone,
            'cost': cost,
            'type': type
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
    var id = '';
    var phone = '24562456245';
    var cost = '00402700012';
    var type = '4';
    var code = 'CODE';
    $.ajax({
        type: 'GET',
        url: 'http://116.196.83.125/test/example/jsapi.php',
        dataType: 'json',
        username: 'admin',
        password: '1234',
        data: {
            'code' : code
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

