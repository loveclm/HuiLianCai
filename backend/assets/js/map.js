/*
 fileName: map.js
 description: process AMap function and manage Tourist areas
 */

// variables for AMap
var map = null; // AMap pointer
var imageLayer = null;
var state = 'new';
// selected position
var leftBottom = [];
var rightTop = [];

// current position
var currentLocation = [];

// flag for MouseTool
var isFirst = true;
var base_url = "";
//list for Attraction Mark
var markList = [];
var markerId = 100;

/* function: initMap
 description: Init AMap using center position and add AMap.MouseTool plugin
 param: center // center position of current map view
 */
function initMap(center) {
    map = new AMap.Map('custom-map-container', {
        resizeEnable: true,
        zoom: 16,
        center: center//地图中心点
    });

    var mouseTool = new AMap.MouseTool(map); //在地图中添加MouseTool插件
    var drawRectangle = mouseTool.rectangle(); //用鼠标工具画矩形
    isFirst = true;
    AMap.event.addListener(mouseTool, 'draw', function (e) {
        if (!isFirst) return;
        mouseTool.close();
        isFirst = false;
        var path = e.obj.getPath();
        leftBottom = [path[3].lng, path[3].lat];
        rightTop = [path[1].lng, path[1].lat];
        var arr = [leftBottom, rightTop];
        console.log(e.obj.getPath());//获取路径
        $('#area-position').val(JSON.stringify(arr));
    });
}

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {
    var position = $('#area-position').val();
    if (position != '' && position != undefined) {
        var positionObj = JSON.parse(position);
        var url = $('#custom-base-url').val();
        base_url = url;

        leftBottom = positionObj[0];
        rightTop = positionObj[1];

        var overlay = $('#area-overlay').val();
        var imageLayer = new AMap.ImageLayer({
            url: url + 'uploads/' + overlay,
            bounds: new AMap.Bounds(
                leftBottom,   //左下角
                rightTop    //右上角
            ),
            zooms: [10, 18]
        });

        map = new AMap.Map('custom-map-container', {
            resizeEnable: true,
            center: leftBottom,
            zoom: 16,
            layers: [
                new AMap.TileLayer(),
                imageLayer
            ]
        });

        addPointFromArea(url);
    }
    else {
        // init AMap
        currentLocation = [116.403322, 39.900255];
        initMap(currentLocation);
    }

    /*
     Event code that find string for Search of Tourist Area
     */
    AMap.plugin('AMap.Autocomplete', function () {//回调函数
        var autoOptions = {
            city: "", //城市，默认全国
            input: "cityName"//使用联想输入的input的id
        };
        var autocomplete = new AMap.Autocomplete(autoOptions);

        AMap.event.addListener(autocomplete, "select", function (data) {
            console.log(data);
            currentLocation = [data['poi']['location']['lng'], data['poi']['location']['lat']];
            initMap(currentLocation);
        });
    });

    /*
     Event code that upload overlay image to Tourist Area
     */
    var files;
    $('#upload-overlay').on('change', prepareUpload);
    function prepareUpload(event) {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
        files = event.target.files;

        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

        $.ajax({
            url: base_url + 'api/Areas/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    if (data['status'] == true) {
                        $('#area-overlay').val(data['file']);
                        if (map == null) {
                            map = new AMap.Map('custom-map-container', {
                                resizeEnable: true,
                                center: currentLocation,
                                zoom: 16,
                            });
                        }

                        if (imageLayer != null) {
                            imageLayer.setMap(null);
                        }
                        imageLayer = new AMap.ImageLayer({
                            url: base_url + 'uploads/' + data['file'],
                            bounds: new AMap.Bounds(
                                leftBottom,   //左下角
                                rightTop    //右上角
                            ),
                            zooms: [10, 18]
                        });
                        imageLayer.setMap(map);
                    }
                }
                else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });

    }

    //upload image for attraction
    $('#upload-point-image').on('change', uploadPointImage);
    function uploadPointImage(event) {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
        files = event.target.files;

        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

        $.ajax({
            url: base_url + 'api/Areas/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    if (data['status'] == true) {
                        var url = base_url + 'uploads/' + data['file'];
                        $("#point-item-image").attr("src", url);
                        $("#pointimage").val(data['file']);
                    }
                }
                else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });

    }

    //upload audio for attraction
    $('#upload-point-audio').on('change', uploadPointAudio);
    function uploadPointAudio(event) {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
        files = event.target.files;

        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

        $.ajax({
            url: base_url + 'api/Areas/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    if (data['status'] == true) {
                        $("#pointaudio").val(data['file']);
                        $("#pointaudio_view").html(data['file']);
                    }
                }
                else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });

    }

    //upload audio for attraction
    $('#upload-area-audio').on('change', uploadAreaAudio);
    function uploadAreaAudio(event) {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
        files = event.target.files;

        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

        $.ajax({
            url: base_url + 'api/Areas/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    if (data['status'] == true) {
                        $("#area-audio-file").html(data['file']);
                    }
                }
                else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });

    }
});

function showAddPoint() {

    $('.point-add-view').show();
    $('.point-list-view').hide();

    $('#pointname').val('');
    $('#pointdescription').val('');
    $('#pointprice').val('');
    $('#point-view-index').val('0');
    $("#point-item-image").attr("src", '');
    $("#pointimage").val('');
    $("#pointfree").attr("checked", false);

}

// Add attraction to Tourist Area
function addPointFromArea(url) {

    var areaid = $('#point-list').val();
    $.post(url + "api/Areas/edit/" + areaid, '', function (result) {
        console.log(result);

        var objList = JSON.parse(result['point_list']);
        console.log(objList);
        for (var i = 0; i < objList.length; i++) {
            var obj = objList[i];
            var pointName = obj['name'];
            var pointDescription = obj['description'];
            var pointPrice = obj['price'];
            var pointImage = obj['image'];
            var pointAudio = obj['audio_1'];

            var pointFree = obj['trial'];
            var pointPosition = JSON.parse(obj['position']);

            markerId = markerId + 1;
            var marker = new AMap.Marker({ //添加自定义点标记
                map: map,
                position: pointPosition, //基点位置
                offset: new AMap.Pixel(-17, -42), //相对于基点的偏移位置
                draggable: true,
                id: markerId
            });
            marker.on('dragend', function (e) {
                var target = e['target']['G'];
                var position = [e['lnglat']['lng'], e['lnglat']['lat']];
                $('#pointposition-' + target['id']).val(JSON.stringify(position));
                console.log(e);
            });

            marker.on('click', function (e) {
                var target = e['target']['G'];
                var targetId = target['id'];
                showEditPoint(targetId);
                console.log(e);
            });

            markList.push(marker);

            $("#pointList").append("<li id='pointitem-" + markerId + "'><div class='col-sm-6'>" + pointName + "</div>" +
                "<input style='display: none;' value='" + pointDescription + "'/>" +
                "<input style='display: none;' value='" + pointPrice + "'/>" +
                "<input id='pointposition-" + markerId + "' style='display: none;' value='" + JSON.stringify(pointPosition) + "'/>" +
                "<input style='display: none;' value='" + pointImage + "'/>" +
                "<input style='display: none;' value='" + pointAudio + "'/>" +
                "<input style='display: none;' value='" + pointFree + "'/>" +
                "<div class='col-sm-3' data-id='" + markerId + "' onclick='editPoint(this);'><a href='#'>编辑</a></div>" +
                "<div class='col-sm-3' data-id='" + markerId + "' onclick='deletePoint(this);'><a href='#'>删除</a></div>" +
                "</li>");
        }
    });
}

function addPoint(param) {
    var pointName = $('#pointname').val();
    var pointDescription = $('#pointdescription').val();
    var pointPrice = $('#pointprice').val();
    var pointImage = $('#pointimage').val();
    var pointAudio = $('#pointaudio').val();

    var pointFree = ($('#pointfree').is(":checked") == true) ? '1' : '0';

    $('.point-add-view').hide();
    $('.point-list-view').show();
    console.log(pointImage+','+pointAudio);

    if (param == 1) {

        var pointIndex = $('#point-view-index').val();
        var ptCenter = map.getCenter();

        if (pointIndex == '0') {
            markerId = markerId + 1;
            var marker = new AMap.Marker({ //添加自定义点标记
                map: map,
                position: ptCenter, //基点位置
                offset: new AMap.Pixel(-17, -42), //相对于基点的偏移位置
                draggable: true,
                id: markerId
            });
            marker.on('dragend', function (e) {
                var target = e['target']['G'];
                var position = [e['lnglat']['lng'], e['lnglat']['lat']];
                $('#pointposition-' + target['id']).val(JSON.stringify(position));
                console.log(e);
            });

            marker.on('click', function (e) {
                var target = e['target']['G'];
                var targetId = target['id'];
                showEditPoint(targetId);
                console.log(e);
            });

            markList.push(marker);

            $("#pointList").append("<li id='pointitem-" + markerId + "'><div class='col-sm-6'>" + pointName + "</div>" +
                "<input style='display: none;' value='" + pointDescription + "'/>" +
                "<input style='display: none;' value='" + pointPrice + "'/>" +
                "<input id='pointposition-" + markerId + "' style='display: none;' value='" + JSON.stringify([ptCenter['lng'], ptCenter['lat']]) + "'/>" +
                "<input style='display: none;' value='" + pointImage + "'/>" +
                "<input style='display: none;' value='" + pointAudio + "'/>" +
                "<input style='display: none;' value='" + pointFree + "'/>" +
                "<div class='col-sm-3' data-id='" + markerId + "' onclick='editPoint(this);'><a href='#'>编辑</a></div>" +
                "<div class='col-sm-3' data-id='" + markerId + "' onclick='deletePoint(this);'><a href='#'>删除</a></div>" +
                "</li>");
        }
        else {
            var pointInfo = $('#pointitem-' + pointIndex).children();
            $(pointInfo[0]).text(pointName);
            $(pointInfo[1]).val(pointDescription);
            $(pointInfo[2]).val(pointPrice);
            $(pointInfo[4]).val(pointImage);
            $(pointInfo[5]).val(pointAudio);
            $(pointInfo[6]).val(pointFree);
        }
    }
}
// edit Attraction
function editPoint(e) {

    var targetId = $(e).attr('data-id');
    showEditPoint(targetId);
}

//show Point Edit window
function showEditPoint(targetId) {

    var pointInfo = $('#pointitem-' + targetId).children();
    var pointName = $(pointInfo[0]).text();
    var pointDescription = $(pointInfo[1]).val();
    var pointPrice = $(pointInfo[2]).val();
    var pointImage = $(pointInfo[4]).val();
    var pointAudio = $(pointInfo[5]).val();
    var pointFree = $(pointInfo[6]).val();

    $('#pointname').val(pointName);
    $('#pointdescription').val(pointDescription);
    $('#pointprice').val(pointPrice);
    $('#point-view-index').val(targetId);

    $("#point-item-image").attr("src", base_url + 'uploads/' + pointImage);
    $("#pointaudio_view").html(pointAudio);
    $("#pointimage").val(pointImage);
    $("#pointaudio").val(pointAudio);
    if (pointFree == '1') {

        $('#pointfree')[0].checked = true;
    } else {

        $('#pointfree')[0].checked = false;
    }

    $('.point-add-view').show();
    $('.point-list-view').hide();
}

// delete Attraction
function deletePoint(e) {
    var targetId = $(e).attr('data-id');
    for (var i = 0; i < markList.length; i++) {
        var maker = markList[i];
        var makerId = maker['G']['id'];
        if (targetId == makerId) {
            map.remove(maker);
            markList.splice(i, 1);
            break;
        }
    }
    $(e).parent().remove();
}

function addTouristArea(url, isEdit) {
    var area = $("#areaname").val();
    var rate = $("#arearate").val();
    var overlay = $('#area-overlay').val();
    var address = $('#cityName').val();

    if (area == '') {
        window.alert("Please enter area name.");
        return;
    }

    var info = {
        overay: overlay,
        position: $('#area-position').val() != 0 ? JSON.parse($('#area-position').val()) : '',
        audio: $('#area-audio-file').html()
    };

    var attraction_list = getAttractions();
    var price = 0;
    for (var i = 0; i < attraction_list.length; i++) {
        if (attraction_list[i].trial == '1') continue;
        price += parseInt(attraction_list[i].price);
    }

    var touristArea = {
        name: area,
        discount_rate: rate,
        address: address,
        status: 0,
        type: 1,
        price: price,
        info: JSON.stringify(info),
        point_list: JSON.stringify(attraction_list)
    };
    console.log(touristArea);

    var area_id = $('#point-list').val();
    var url_suffix = (area_id == undefined) ? "" : ("/" + area_id);
    $.post(url + "api/Areas/save" + url_suffix, touristArea, function (result) {
        location.href = url + 'area';
    });
}

function getAttractions() {
    var ret = [];
    var area_id = $('#point-list').val();
    var list = document.getElementById('pointList');
    var pointList = list.getElementsByTagName('li');

    for (var i = 0; i < pointList.length; i++) {
        var pointInfo = $(pointList[i]).children();
        var pointName = $(pointInfo[0]).text();
        var pointDescription = $(pointInfo[1]).val();
        var pointPrice = $(pointInfo[2]).val();
        var pointPosition = $(pointInfo[3]).val();
        var pointImage = $(pointInfo[4]).val();
        var pointAudio = $(pointInfo[5]).val();
        var pointFree = $(pointInfo[6]).val();
        var point = {
            id: area_id + "_" + (i + 1),
            name: pointName,
            description: pointDescription,
            price: pointPrice == '' ? '0' : pointPrice,
            discount_rate: '1',
            image: pointImage,
            audio_1: pointAudio,
            audio_2: pointAudio,
            audio_3: pointAudio,
            trial: pointFree,
            position: pointPosition
        };
        ret.push(point);
    }
    return ret;//JSON.stringify(ret);
}

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

function uploadOverlay() {
    $('#upload-overlay').click();
}
function uploadPointImage() {
    $('#upload-point-image').click();
}
function uploadPointAudio() {
    $('#upload-point-audio').click();
}
function uploadAreaAudio() {
    $('#upload-area-audio').click();
}