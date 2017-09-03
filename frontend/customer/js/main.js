/**
 * Created by Administrator on 8/4/2017.
 */
var initRatio;
var bCommentaryState = 0;
var bAutomaticState = 0;
var bPhoneverified = 0;
var bAuthorizing = 0;

var phone_num = "";

var new_scenic_id = "";
var shop_id = 0;
var bMovable = 0;
var cur_scenic_data = null;

window.addEventListener('resize', function(event){
    resize();
});

$(document).ready(function(){
    // Check browser support(local storage)
    if (typeof(Storage) === "undefined") {
        document.body.innerHTML = '<div style="position: relative">Can not show web page in current browser.</div>';
        return;
    }
    // resize client region
    resize();
    sessionStorage.setItem('shopid', shop_id);

    // loading needed data in local storage
    loadDataFormStorage();

    // if phone was not verified then show verification dialog
    if(bPhoneverified == 0)
        $('#login').show();

    //  event listener
    // change explaining state with button click event
    $('#btn-commentary').click(function(){
        if(bCommentaryState == 0)
        {
            $('#btn-commentary').css({'background':'url(\'resource/image/home_commentary_off.png\') no-repeat', 'background-size':'contain'});
            explain_area_control("stop");
            showNotification('已关闭景区讲解');
        }else
        {
            $('#btn-commentary').css({'background':'url(\'resource/image/home_commentary_on.png\') no-repeat', 'background-size':'contain'});
            explain_area_control("play");
            showNotification('已开启景区讲解');
        }

        bCommentaryState = 1 - bCommentaryState;
        sessionStorage.setItem('explain_check', bCommentaryState);

    });

    // change auto playing state with button click event
    $('#btn-automatic').click(function(){
        if(bAutomaticState == 0)
        {
            showNotification('已关闭景区讲解');
            $('#btn-automatic').css({'background':'url(\'resource/image/home_automatic_off.png\') no-repeat', 'background-size':'contain'});
        }else
        {
            showNotification('已开启景区讲解');
            $('#btn-automatic').css({'background':'url(\'resource/image/home_automatic_on.png\') no-repeat', 'background-size':'contain'});
        }

        bAutomaticState = 1 - bAutomaticState;
        sessionStorage.setItem('auto_explain_check', bAutomaticState);
    });

    // change auto playing state with button click event
    $('#btn-position').click(function(){
        if(bMovable == 1)
        {
            $('#btn-position').css({'background':'url(\'resource/image/home_position_on.png\') no-repeat', 'background-size':'contain'});
        }else
        {
            $('#btn-position').css({'background':'url(\'resource/image/home_position_off.png\') no-repeat', 'background-size':'contain'});
        }

        bMovable = 1 - bMovable;
        sessionStorage.setItem('movable', bMovable);
    });
});

// loading  the data from local storage
function loadDataFormStorage(){
    initialize();

    if(shop_id == 0){
        shop_id = parseInt(sessionStorage.getItem('shop_id'));
    }

    bPhoneverified = parseInt(sessionStorage.getItem('phone_verified'));
    if(bPhoneverified == 0)
        localStorage.setItem('phone_number', "");
    else
        phone_num = localStorage.getItem('phone_number');

    // loading information of the current scenic area
    new_scenic_id = sessionStorage.getItem('new_scenic_id');
    if(new_scenic_id != "") {
        showScenicareaInformation();
    } else{
        cur_scenic_data = sessionStorage.getObject('cur_scenic_area');
        if(cur_scenic_data === null) return;

        // displaying the information of current scenic area in the Map
        showScenicareaInformation();
    }
}

// showing the information of current scenic area
function showScenicareaInformation(){
    bMovable = parseInt(sessionStorage.getItem('movable'));
    if(bMovable == 1)
        $('#btn-position').css({'background':'url(\'resource/image/home_position_off.png\') no-repeat', 'background-size':'contain'});
    else
        $('#btn-position').css({'background':'url(\'resource/image/home_position_on.png\') no-repeat', 'background-size':'contain'});

    if( new_scenic_id != "") {
        sessionStorage.setItem('new_scenic_id', '');
        getScenicareafromID(new_scenic_id);
    }else {
        map.clearMap();
        setOverlay();           // add overlay image in gaoMap
        showAttractionInfos();  // show all the attraction marks
    }
}

function initialize(){
    initializeStorage();

    // loading information related with attraction explain
    bCommentaryState = parseInt(sessionStorage.getItem('explain_check'));
    bAutomaticState = parseInt(sessionStorage.getItem('auto_explain_check'));
    bMovable = parseInt(sessionStorage.getItem('movable'));

    if(bCommentaryState == 0)
        $('#btn-commentary').css({'background':'url(\'resource/image/home_commentary_on.png\') no-repeat', 'background-size':'contain'});
    else
        $('#btn-commentary').css({'background':'url(\'resource/image/home_commentary_off.png\') no-repeat', 'background-size':'contain'});

    if(bAutomaticState == 0)
        $('#btn-automatic').css({'background':'url(\'resource/image/home_automatic_on.png\') no-repeat', 'background-size':'contain'});
    else
        $('#btn-automatic').css({'background':'url(\'resource/image/home_automatic_off.png\') no-repeat', 'background-size':'contain'});

    if(bMovable == 1)
        $('#btn-position').css({'background':'url(\'resource/image/home_position_off.png\') no-repeat', 'background-size':'contain'});
    else
        $('#btn-position').css({'background':'url(\'resource/image/home_position_on.png\') no-repeat', 'background-size':'contain'});

    // loading gaode map
    initMap();
}

function initializeStorage(){
    // if value is null then initialize value
    if(sessionStorage.getItem('phone_verified') === null)
        sessionStorage.setItem('phone_verified', 0);

    if(sessionStorage.getItem('explain_check') === null)
        sessionStorage.setItem('explain_check', 0);

    if(sessionStorage.getItem('auto_explain_check') === null)
        sessionStorage.setItem('auto_explain_check', 0);

    if(sessionStorage.getItem('movable') === null)
        sessionStorage.setItem('movable', 0);

    // current verified phone number
    if(localStorage.getItem('phone_number') === null)
        localStorage.setItem('phone_number', "");

    if( sessionStorage.getItem('shop_id') == null)
        sessionStorage.setItem('shop_id', "");

    // new scenic area id : this area is the scenic area that exchange with current scenic area
    if(sessionStorage.getItem('new_scenic_id') === null)
        sessionStorage.setItem('new_scenic_id', "");

}

function showNotification(data){
    $('#notification').html(data);

    $('#notification').show();
    setTimeout(function() { $('#notification').hide(); }, 3000);
}

function start_explain_area() {
    // play audio with the setted music type
    var music = document.getElementById('area_music'); // id for audio element
    $("#audioSource").attr("src", cur_scenic_data.audio).detach().appendTo("#area_music");

    music.load();
    explain_area_control("play");
}

function  explain_area_control(data) {
    // play audio with the setted music type
    var music = document.getElementById('area_music'); // id for audio element
    if(data == "play") {
        $('#area_music').trigger('play');
        //music.play();
    }else if(data == "stop"){
        $("#area_music").trigger('pause');
        $("#area_music").prop("currentTime",0);
        //music.pause();
    }
}

function start_explain_attraction(index) {
    // play audio with the setted music type
    var music = document.getElementById('music'); // id for audio element
    $("#audioSource").attr("src", cur_scenic_data.attractions[index].audio_files[cur_voice_type-1]).detach().appendTo("#music");

    music.load();
    explain_attraction_control("play");
}

function explain_attraction_control(data) {
    // play audio with the setted music type
    var music = document.getElementById('music'); // id for audio element
    if(data == "play") {
        $('#music').trigger('play');
        //music.play();
    }else if(data == "stop"){
        $("#music").trigger('pause');
        $("#music").prop("currentTime",0);
        //music.pause();
    }
}

function display_attraction_data() {
    //------- show the attraction list
    // show individual tourism data
    var content_html = '<div id="search_attraction">';
    content_html += '   <div class="has-feedback">';
    content_html += '   <input type="text" class="form-control input-sm" onchange="filter_attraction(this.value)" placeholder="请输入景点">';
    content_html += '   <span class="glyphicon glyphicon-search form-control-feedback"></span>';
    content_html += '</div></div>';
    $('#detail_content_search').html(content_html);

    content_html = '<div id="attraction_list">';
    if (cur_scenic_data != null) {
        for (var i = 0; i < cur_scenic_data['attractions'].length; i++) {

            content_html += '   <div class="attraction_item" id="attraction_item' + (i + 1) + '" onclick="selectAttraction(' + i + ')">';
            content_html += '   <img src="resource/image/attraction.png" style="float: left; height:100%">';
            content_html += '   <h4 style="float: left; font-weight: bold; margin-top:5px; margin-left:10px ">' + cur_scenic_data['attractions'][i]['name'] + '</h4></div>';
        }
    }
    content_html += '</div>';
    $('#detail_content_data').html(content_html);
}

function filter_attraction(search_text){
    if(cur_scenic_data == null) return;

    var content_html = '<div id="attraction_list">';
    for( var i = 0; i < cur_scenic_data['attractions'].length; i++){
        if(cur_scenic_data['attractions'][i]['name'].indexOf(search_text)>=0) {
            content_html += '   <div class="attraction_item" id="attraction_item' + (i+1) +'" onclick="selectAttraction('+ i +')">';
            content_html += '   <img src="resource/image/被你整晕了.png" style="float: left; height:100%">';
            content_html += '   <h4 style="float: left; font-weight: bold; margin-top:5px; margin-left:10px ">' + cur_scenic_data['attractions'][i]['name'] + '</h4></div>';
        }
    }
    content_html += '</div>';
    $('#detail_content_data').html(content_html);
}

/*****************************************
 resize display
 ****************************************/
function resize(){
    initRatio = getDevicePixelRatio();
    var ratio = getDevicePixelRatio()/initRatio;
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;

    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var scale = Math.min(width/640,height/1010) * ratio;

    //width = 640*scale
    $('#content').css({width:width, height:height});
    $('#app_header').css({display:'block', width:width});
    $('#app_footer').css({width:width});
    if(height < 450){
        $('.amap-zoomcontrol').css({display:'none'});
        $('.menu-image').hide();
    }else{
        $('.amap-zoomcontrol').css({display:'block'});
        $('.menu-image').show();
    }
    // resize map region
    var map_top = document.getElementById('app_header').clientHeight;
    var map_bottom = document.getElementById('app_footer').clientHeight;
    var map_width = document.getElementById('content').clientWidth;
    var map_height = document.body.clientHeight - map_top - map_bottom;
    $('#custom-map-container').css({display:'block',width:map_width, height:map_height, top:map_top, bottom:map_bottom});

    // redistribution buttons
    $('#btn-help').show();
    var content_margin=(document.body.clientWidth-width)/2;
    var btn_height = document.getElementById('btn-help').clientHeight;
    var dh = btn_height+10;
    var delta = 10;
    if(height < 600){
        btn_height  = map_height/8;
        delta = 5;
        dh = btn_height + delta;
    }

    $('#btn-help').css({display:'block', top:map_top + delta, right:content_margin, width: btn_height, height: btn_height});
    $('#btn-follow').css({display:'block', top:map_top + dh + delta, right:content_margin, width: btn_height, height: btn_height});
    $('#btn-order').css({display:'block', top:map_top + dh*2 + delta, right:content_margin, width: btn_height, height: btn_height});
    $('#btn-scenic').css({display:'block', top:map_top + dh*3 + delta, right:content_margin, width: btn_height, height: btn_height});
    $('#btn-commentary').css({display:'block', bottom:map_bottom + 2*dh + delta/2, right:content_margin, width: btn_height, height: btn_height});
    $('#btn-automatic').css({display:'block', bottom:map_bottom + dh + delta/2, right:content_margin, width: btn_height, height: btn_height});
    $('#btn-position').css({display:'block', bottom:map_bottom + delta, right:content_margin, width: btn_height, height: btn_height});

    //set margin of login modal dialog
    $('.custom-modal').css({'margin-left':content_margin,'margin-right':content_margin});

    // set bottom of the menu detail dialog
    $('#menu-detail-dialog').css({bottom: map_bottom, width:map_width});
    $('#menu-detail').css({bottom:map_bottom});
}
