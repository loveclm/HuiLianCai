/*
    fileName: course.js
    description: process Tourist Course
*/

var currentSelectedArea = 0; // current selected area in tourist areas list
var currentSelectedCourseItem = null; // current selected area in area list of course

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function(){

});

// select area from Area List
function selectCourse(id) {

    $( ".custom-areaitem" ).removeClass( "selected-area" );
    $( ".custom-courseitem" ).removeClass( "selected-courseitem" );
    $('#areaitem-' + id).addClass('selected-area');
    currentSelectedArea = id;
}

// add selected area to course
function addAreaToCourse() {

    var areaName = $('#areatitle-' + currentSelectedArea).text();
    $("#courseItems").append("<li class='custom-courseitem' onclick='selectedCourseItem(this)' " +
        "data-id='" + currentSelectedArea+"'><div>" + areaName + "</div></li>");
}

//Select area in Course
function selectedCourseItem(obj) {

    $( ".custom-courseitem" ).removeClass( "selected-courseitem" );
    $( ".custom-areaitem" ).removeClass( "selected-area" );
    $(obj).addClass('selected-courseitem');
    currentSelectedCourseItem = obj;
}

//Remove area from course
function removeAreaFromCourse() {

    $(currentSelectedCourseItem).remove();
}

//Get area list on Course Add and Edit Page
function getAreas() {

    var ret = [];
    var list = $('#courseItems');
    var areaList = list.children();

    for(var i = 0; i < areaList.length; i++){

        var areaId = $(areaList[i]).attr('data-id');
        var areaTitle = $('#areatitle-' + areaId).text();
        ret.push({id: areaId, name: areaTitle});
    }
    return JSON.stringify(ret);
}

// Search course on Course List Page
function searchCourse(url) {

    var name = $('#searchName').val();
    var status = $('#searchStatus :selected').val();
    name = name == '' ? 'all': name;

    location.href = url + 'courseListing/' + name+ '/' + status;
}

// Add and update Tourist Course
function processCourse(url, id) {

    var area = $("#coursename").val();
    var rate = $("#courserate").val();

    if(area.length > 10){
        $('#custom-error-coursename').show();
        return;
    }


    var touristArea = '';
    var reqUrl = '';
    var info={
        overay:'capture.jpg',
    };
    if(parseInt(id) != 0){
        touristArea = {
            id: parseInt(id), name: area, discount_rate: rate, address: '', status: 0, type: 2, info:JSON.stringify(info), point_list: getAreas()
        };
        reqUrl = url + "api/Areas/save/" + id;
    }
    else {
        touristArea = {
            name: area, discount_rate: rate, address: '', status: 0, type: 2, info:JSON.stringify(info), point_list: getAreas()
        };
        reqUrl = url + "api/Areas/save";
    }

    $.post(reqUrl, touristArea, function(result){
        console.log(result);
        location.href = url + 'course';
    });

    return;
}

// This part is action processing for Course
// delete and deploy Course
function deleteAreaConfirm(id) {

    $('#custom-confirm-delete-view').show();
    $('#current-areaid').val(id);
}

function deleteArea(url, type) {

    $('#custom-confirm-delete-view').hide();
    if(type == 1){

        $.post(url + "api/Areas/remove/" + $('#current-areaid').val(), function(result){
            location.href = url + 'course';
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
    if(type == 1){

        var touristArea = {
            id: $('#current-areaid').val(),
            status: $('#current-areastatus').val()
        };

        $.post(url + "api/Areas/save/" + touristArea['id'], touristArea, function(result){
            location.href = url + 'course';
        });
    }
}

//return previos page
function cancel(url) {
    location.href = url + 'course';
}

//
function findAreaInList(url) {
    var strKey = $('#course-search').val();
    $.post(url + "api/Areas/find/" +strKey, function(result){
        $("#courseList").empty();
        var areaList = result;
        for(var i = 0; i < areaList.length; i++ ){
            var area = areaList[i];
            $("#courseList").append("<li class='custom-areaitem' " +
                "id='areaitem-"+ area['id'] +"' onclick='selectCourse(" + area['id'] +");'>" +
                "<div id='areatitle-"+ area['id'] +"'>" + area['name'] + "</div></li>");
        }

    });
}