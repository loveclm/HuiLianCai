/*
 fileName: map.js
 description: process AMap function and manage Tourist areas
 */

// variables for AMap
var map = null; // AMap pointer
var state = 'new';
// selected position
var leftBottom = [116.391541, 39.913155];
var rightTop = [116.402635, 39.92223931];

// current position
var currentLocation = [];

// flag for MouseTool
var isFirst = true;
var base_url = "";
//list for Attraction Mark
var markList = [];
var markerId = 100;

var district, polygons = [], citycode;

var citySelect, districtSelect, areaSelect;

var opts;
var imageLayer = null;
var mapMarker = null;
var mapMarker1 = null;
var dragging = false;
var dragging1 = false;
var cornerLocation = [];

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

    citySelect = document.getElementById('city');
    districtSelect = document.getElementById('district');
    areaSelect = document.getElementById('street');
//行政区划查询
    opts = {
        subdistrict: 1,   //返回下一级行政区
        showbiz: false  //最后一级返回街道信息
    };
    district = new AMap.DistrictSearch(opts);//注意：需要使用插件同步下发功能才能这样直接使用
    district.search('中国', function (status, result) {
        if (status == 'complete') {
            getData(result.districtList[0]);
        }
    });

    //var mouseTool = new AMap.MouseTool(map); //在地图中添加MouseTool插件
    //var drawRectangle = mouseTool.rectangle(); //用鼠标工具画矩形
    isFirst = true;
    //AMap.event.addListener(mouseTool, 'draw', function (e) {
    //    if (!isFirst) return;
    //    mouseTool.close();
    //    isFirst = false;
    //    var path = e.obj.getPath();
    //    leftBottom = [path[3].lng, path[3].lat];
    //    rightTop = [path[1].lng, path[1].lat];
    //
    //    var arr = [leftBottom, rightTop];
    //    console.log(e.obj.getPath());//获取路径
    //    $('#area-position').val(JSON.stringify(arr));
    //});


}


function getData(data, level) {
    var bounds = data.boundaries;
    if (bounds) {
        for (var i = 0, l = bounds.length; i < l; i++) {
            var polygon = new AMap.Polygon({
                map: map,
                strokeWeight: 1,
                strokeColor: '#CC66CC',
                fillColor: '#CCF3FF',
                fillOpacity: 0.5,
                path: bounds[i]
            });
            polygons.push(polygon);
            map.setFitView();//地图自适应
            var pos = map.getCenter();
            currentLocation = [pos['lng'], pos['lat']];
            console.log(currentLocation);
            var position = currentLocation;

            leftBottom = [position[0] - .001, position[1] - .001];
            rightTop = [position[0] + .001, position[1] + .001];

            imageLayer.setBounds(new AMap.Bounds(leftBottom, rightTop));
            cornerLocation = [rightTop[0], leftBottom[1]];
            mapMarker.setPosition(currentLocation);
            mapMarker1.setPosition(cornerLocation);
            var arr = [leftBottom, rightTop];
            $('#area-position').val(JSON.stringify(arr));
        }

    }


    //清空下一级别的下拉列表
    if (level === 'province') {
        citySelect.innerHTML = $("#cityName").html();
        districtSelect.innerHTML = $("#districtName").html();
        areaSelect.innerHTML = '';
    } else if (level === 'city') {
        districtSelect.innerHTML = '';
        areaSelect.innerHTML = '';
    } else if (level === 'district') {
        areaSelect.innerHTML = '';
    }

    var subList = data.districtList;
    var contentSub, curlevel, curList;
    if ($("#provinceName").html() != '') {
        contentSub = new Option($("#provinceName").html());
        curList = document.querySelector('#province');
        curList.add(contentSub);
    }
    if ($("#cityName").html() != '') {
        contentSub = new Option($("#cityName").html());
        curList = document.querySelector('#city');
        curList.add(contentSub);
    }
    if ($("#districtName").html() != '') {
        contentSub = new Option($("#districtName").html());
        curList = document.querySelector('#district');
        curList.add(contentSub);
    }

    if (subList) {
        contentSub = new Option('--请选择--');
        curlevel = subList[0].level;
        curList = document.querySelector('#' + curlevel);
        curList.add(contentSub);
        for (var i = 0, l = subList.length; i < l; i++) {
            var name = subList[i].name;
            var levelSub = subList[i].level;
            var cityCode = subList[i].citycode;
            contentSub = new Option(name);
            contentSub.setAttribute("value", levelSub);
            contentSub.center = subList[i].center;
            contentSub.adcode = subList[i].adcode;
            curList.add(contentSub);
        }
    }
}

function search(obj) {
    //清除地图上所有覆盖物
    for (var i = 0, l = polygons.length; i < l; i++) {
        polygons[i].setMap(null);
    }
    var option = obj[obj.options.selectedIndex];
    var keyword = option.text; //关键字
    var adcode = option.adcode;
    console.log(JSON.stringify(option.value));
    district.setLevel(option.value); //行政区级别
    if (option.value == 'province') $("#provinceName").html(keyword);
    else if (option.value == 'city') $("#cityName").html(keyword);
    else if (option.value == 'district') $("#districtName").html(keyword);
    district.setExtensions('all');
    //行政区查询
    //按照adcode进行查询可以保证数据返回的唯一性
    district.search(adcode, function (status, result) {
        if (status === 'complete') {
            getData(result.districtList[0], obj.id);
        }
    });
}

function setCenter(obj) {
    map.setCenter(obj[obj.options.selectedIndex].center);
}

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
$(document).ready(function () {

    mapMarker = null;
    mapMarker1 = null;
    imageLayer = null;
    dragging = false;
    dragging1 = false;
    cornerLocation = [];
    map = null;

    currentLocation = [(leftBottom[0] + rightTop[0]) / 2, (leftBottom[1] + rightTop[1]) / 2];
    cornerLocation = [rightTop[0], leftBottom[1]];
    var position = $('#area-position').val();
    if (position != '' && position != undefined) {

        initMap(currentLocation);
        var positionObj = JSON.parse(position);
        var url = $('#custom-base-url').val();
        base_url = url;

        leftBottom = positionObj[0];
        rightTop = positionObj[1];
        cornerLocation = [rightTop[0], leftBottom[1]];
        currentLocation = [(leftBottom[0] + rightTop[0]) / 2, (leftBottom[1] + rightTop[1]) / 2];
        var overlay = $('#area-overlay').val();
        //var imageLayer = new AMap.ImageLayer({
        //    url: url + 'uploads/' + overlay,
        //    bounds: new AMap.Bounds(
        //        leftBottom,   //左下角
        //        rightTop    //右上角
        //    ),
        //    zooms: [5, 18]
        //});
        map = new AMap.Map('custom-map-container', {
            resizeEnable: true,
            center: currentLocation,
            zoom: 17,
            scrollWheel: true
            //layers: [
            //    new AMap.TileLayer(),
            //    imageLayer
            //]
        });

        imageLayer = new AMap.ImageLayer({
            url: url + 'uploads/' + overlay,
            bounds: new AMap.Bounds(
                leftBottom,     //左下角
                rightTop        //右上角
            ),
            zooms: [2, 18],
            map: map
        });
        mapMarker = new AMap.Marker({
            map: map,
            icon: base_url + 'assets/images/control.png',
            offset: new AMap.Pixel(-20, -20),
            position: currentLocation,
            draggable: true
        });
        dragging = false;
        mapMarker.on('dragstart', function (e) {
            dragging = true;
        });
        mapMarker.on('dragend', function () {
            dragging = false;
        });
        mapMarker.on('mousemove', function (e) {
            if (dragging) {
                var target = e['target']['G'];
                var position = [e['lnglat']['lng'], e['lnglat']['lat']];
                // calculate moving amount
                var dx = position[0] - currentLocation[0];
                var dy = position[1] - currentLocation[1];
                currentLocation = position;
                // move overlay
                leftBottom[0] += dx;
                leftBottom[1] += dy;
                rightTop[0] += dx;
                rightTop[1] += dy;

                imageLayer.setBounds(new AMap.Bounds(leftBottom, rightTop));
                cornerLocation = [rightTop[0], leftBottom[1]];
                mapMarker1.setPosition(cornerLocation);
                var arr = [leftBottom, rightTop];
                $('#area-position').val(JSON.stringify(arr));
            }
        });
        dragging1 = false;
        mapMarker1 = new AMap.Marker({
            map: map,
            icon: base_url + 'assets/images/control.png',
            offset: new AMap.Pixel(-20, -20),
            position: cornerLocation,
            draggable: true
        });
        dragging1 = false;
        mapMarker1.on('dragstart', function (e) {
            dragging1 = true;
        });
        mapMarker1.on('dragend', function () {
            dragging1 = false;
        });
        mapMarker1.on('mousemove', function (e) {
            if (dragging1) {
                var target = e['target']['G'];
                var position = [e['lnglat']['lng'], e['lnglat']['lat']];
                // move overlay
                rightTop[0] = position[0];
                leftBottom[1] = position[1];
                leftBottom[0] = currentLocation[0] - (position[0] - currentLocation[0]);
                rightTop[1] = currentLocation[1] - (position[1] - currentLocation[1]);

                imageLayer.setBounds(new AMap.Bounds(leftBottom, rightTop));
                var arr = [leftBottom, rightTop];
                $('#area-position').val(JSON.stringify(arr));
            }
        });
        addPointFromArea(url);
    }
    else {
        // init AMap
        currentLocation = [(leftBottom[0] + rightTop[0]) / 2, (leftBottom[1] + rightTop[1]) / 2];
        cornerLocation = [rightTop[0], leftBottom[1]];
        initMap(currentLocation);

        imageLayer = new AMap.ImageLayer({
            url: base_url + 'assets/images/bound.png',
            bounds: new AMap.Bounds(
                leftBottom,     //左下角
                rightTop        //右上角
            ),
            zooms: [1, 18],
            map: map
        });
        mapMarker = new AMap.Marker({
            map: map,
            icon: base_url + 'assets/images/control.png',
            offset: new AMap.Pixel(-20, -20),
            position: currentLocation,
            draggable: true
        });
        dragging = false;
        mapMarker.on('dragstart', function (e) {
            dragging = true;
        });
        mapMarker.on('dragend', function () {
            dragging = false;
        });
        mapMarker.on('mousemove', function (e) {
            if (dragging) {
                var target = e['target']['G'];
                var position = [e['lnglat']['lng'], e['lnglat']['lat']];
                // calculate moving amount
                var dx = position[0] - currentLocation[0];
                var dy = position[1] - currentLocation[1];
                currentLocation = position;
                // move overlay
                leftBottom[0] += dx;
                leftBottom[1] += dy;
                rightTop[0] += dx;
                rightTop[1] += dy;

                imageLayer.setBounds(new AMap.Bounds(leftBottom, rightTop));
                cornerLocation = [rightTop[0], leftBottom[1]];
                mapMarker1.setPosition(cornerLocation);
                var arr = [leftBottom, rightTop];
                $('#area-position').val(JSON.stringify(arr));
            }
        });
        mapMarker1 = new AMap.Marker({
            map: map,
            icon: base_url + 'assets/images/control.png',
            offset: new AMap.Pixel(-20, -20),
            position: cornerLocation,
            draggable: true
        });
        dragging1 = false;
        mapMarker1.on('dragstart', function (e) {
            dragging1 = true;
        });
        mapMarker1.on('dragend', function () {
            dragging1 = false;
        });
        mapMarker1.on('mousemove', function (e) {
            if (dragging1) {
                var target = e['target']['G'];
                var position = [e['lnglat']['lng'], e['lnglat']['lat']];
                // move overlay
                rightTop[0] = position[0];
                leftBottom[1] = position[1];
                leftBottom[0] = currentLocation[0] - (position[0] - currentLocation[0]);
                rightTop[1] = currentLocation[1] - (position[1] - currentLocation[1]);

                imageLayer.setBounds(new AMap.Bounds(leftBottom, rightTop));
                var arr = [leftBottom, rightTop];
                $('#area-position').val(JSON.stringify(arr));
            }
        });
    }
});