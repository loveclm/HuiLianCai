/*
 fileName: shop.js
 description: process Shop
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

});

// Search course on Course List Page
function searchAuth(url) {
    var authType = $("#searchType :selected").val();
    var name = $('#searchName').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'ALL' : name;

    location.href = url + 'authListing/' + authType + '/' + name + '/' + status;
}

// Search course on Course List Page
function searchAuthOrder(url, authid) {
    var status = $('#searchStatus :selected').val();

    location.href = url + 'authDetail/' + authid + '/' + status;
}

// Search course on Course List Page
function authDetail(url) {
    var name = $('#searchName').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'all' : name;

    location.href = url + 'authDetail/' + name + '/' + status;
}

// Search course on Course List Page
function authOrderItem(url, id) {
    location.href = url + 'authOrderDetail/' + id;
}

// Search course on Course List Page
function addMoney(url) {
    var money = $('#auth-count').val();
    var id = $('#savingId').html();
    location.href = url + 'authAdd/' + money + '/' + id;
}

//return previos page
function showMoney() {
    $('#custom-generate-auth-view').hide();
    $('#custom-generate-auth-count-view').show();
}

//return previos page
function showSelect(id) {
    $('#custom-generate-auth-view').show();
    $('#savingId').html(id);
}

//return previos page
function cancel(url) {
    if (url == '')
        window.history.back();
    else
        location.href = url+'authmanage';
}
