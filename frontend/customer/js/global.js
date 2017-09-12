var app_data = {
    // timerID,           // timer indentifier
    'time_counter' : 0,   // timer counter that is used when timer runs
    'phone_num' : "",     // user's phone number
    'auth_status' : 0,    // user's authorization stat
    'sms_code' : ""       // SMS authorization code
}

var SERVER_URL = 'http://192.168.2.18/backend/'

// send login request to server
function sendLoginRequest(phonenumber, password) {
    // $.ajax({
    //     type: 'POST',
    //     url: SERVER_URL + 'api/Login', //rest API url
    //     dataType: 'json',
    //     data: {'phone': phonenumber, 'password': password}, // set function name and parameters
    //     success: function (data) {
    //         if (data.status == false) {
    //             switch (parseInt(data.err_code)) {
    //                 case 1:  // user is wrong
    //                     alert('该用户不存在。');
    //                     break;
    //                 case 2: // password is wrong
    //                     alert('密码错误。');
    //                     break;
    //                 case 3: //
    //             }
    //             sessionStorage.setItem('phone_num', phonenumber);
    //         } else {
    //             sessionStorage.setItem('phone_num', phonenumber);
    //             sessionStorage.setItem('auth_status', 1);
    //
    //             window.location.href = '../../index.html';
    //         }
    //     },
    //     error: function (data) {
    //         alert('服务器错误。')
    //     }
    // });

    sessionStorage.setItem('phone_num', phonenumber);
    sessionStorage.setItem('auth_status', 1);
    window.location.href = '../../index.html';
}
//send user register request to server
function sendRegisterRequest(phonenumber, password, servant_phone) {
    // $.ajax({
    //     type: 'POST',
    //     url: SERVER_URL + 'api/RegisterUser', //rest API url
    //     dataType: 'json',
    //     data: {'phone': phonenumber, 'password': password, 'servant':servant_phone}, // set function name and parameters
    //     success: function (data) {
    //         if (data.status == false) {
    //             alert('这个用户已存在。');
    //         } else {
    //             sessionStorage.setItem('phone_num', phonenumber);
    //             sessionStorage.setItem('auth_state', 0);
    //
    //             $('#auth_question').modal();
    //             showModalToCenter('auth_question');
    //         }
    //     },
    //     error: function (data) {
    //         alert('服务器错误。')
    //     }
    // });

    // show the dialog
    sessionStorage.setItem('phone_num', phonenumber);
    $('#auth_question').modal();
    showModalToCenter('auth_question');
}
// send password set forget password request
function sendSetforgetPassword() {
    // $.ajax({
    //     type: 'POST',
    //     url: SERVER_URL + 'api/forgetPassword', //rest API url
    //     dataType: 'json',
    //     data: {'phone': phonenumber, 'password': password}, // set function name and parameters
    //     success: function (data) {
    //         if (data.status == false) {
    //             alert('用户不存在。');
    //         } else {
    //             showMessage('message_dialog', '密码已修改成功');
    //             window.location.href = '../../index.html';
    //         }
    //     },
    //     error: function (data) {
    //         alert('服务器错误。')
    //     }
    // });

    showMessage('message_dialog', '密码已修改成功');
    window.location.href = 'login.html';
}
// upload user's individual information to server
function sendUploadUserInfo(userinfo) {
    // upload two files.


    // $.ajax({
    //     type: 'POST',
    //     url: SERVER_URL + 'api/uploadUserInfo', //rest API url
    //     dataType: 'json',
    //     data: userinfo, // set function name and parameters
    //     success: function (data) {
    //         if (data.status == false) {
    //             alert('上传失败。');
    //         } else {
    //             app_data.auth_status = 1;
    //             sessionStorage.setItem('auth_status', 1);
    //             window.location.href = '../../index.html';
    //         }
    //     },
    //     error: function (data) {
    //         alert('服务器错误。')
    //     }
    // });

    window.location.href = "register_success.html";
}

function sendSMSToServer(phone_num) {
    // run timer
    app_data.time_counter = 60;
    app_data.sms_code = "";
    app_data['timerID'] = setInterval(function () {calculateRemainTime()}, 1000);
    $('#sms_button').attr({
        'id' : 'sms_button_sending',
        'onclick' : 'restoreSMSButton()'
    });

    // send SMS sending request in backend server.
    // $('#loading').css({display:'block'});
    // $.ajax({
    //     type: 'POST',
    //     url: 'http://www.ayoubc.com/tour/plugin/SMS/SendTemplateSMS.php', //rest API url
    //     dataType: 'json',
    //     data: {'phone_num':  phone_num}, // set function name and parameters
    //     success: function(data){
    //         // get SMS code from received data
    //         $('#loading').css({display:'none'});
    //         if(data['result'] == "success") {
    //             app_data.sms_code = data['code'];
    //         }else{
    //             app_data.sms_code = "";
    //             alert(data.error['0']);
    //         }
    //     },
    //     fail: function(){
    //         return;
    //     }
    // });

    app_data.sms_code = "1234";
}
function restoreSMSButton() {
    clearTimer();

    $('#sms_button_sending').attr({
        'id' : 'sms_button',
        'onclick' : 'sendingSMS()'
    });
    $('#sms_button').html('重新获取');
}

function calculateRemainTime(){
    app_data.time_counter--;
    if(app_data.time_counter == 0) {
        clearTimer();
        restoreSMSButton();
        return;
    }
    $('#sms_button_sending').html(app_data.time_counter + '秒可重发');
}

function clearTimer(){
    clearInterval(app_data.timerID);
    app_data.timerID = undefined;
}

function selectBottomItem(index) {
    switch (index){
        case 0:
            break;
        case 1:
            break;
        case 2:
            break;
        case 3:
            break;
    }
}

function showModalToCenter(id){
    var width = document.body.clientWidth
        || document.documentElement.clientWidth
        || window.innerWidth;
    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var dialog_height = $('#' + id).css('height');
    var margin_height = (parseInt(height) - parseInt(dialog_height))/2;
    $('.modal-scrollable').css({width:parseInt(width)*0.7, margin: 'auto', 'margin-top': margin_height});
}

function showMessage( message) {
    $('#message_dialog .modal-body').html('<b>' + message + '</b>')
    $('#message_dialog').modal();
    setTimeout(function(){
        $('#message_dialog').modal('hide');
    }, 3000);
    showModalToCenter('message_dialog');
}
// generate simulation datas for menu bar
function simulat_menu_infos(){
    data.menu_info = [
        {
            'id':'0',       // product kind id
            'name':'推荐',   // product kind name
            'brand': [
                {
                    'id':'11',          // brand id
                    'name':'康师傅'},    // brand name
                { 'id':'12', 'name':'伊利'},
                { 'id':'13', 'name':'蒙牛'},
                { 'id':'14', 'name':'今麦郎'},
                { 'id':'15', 'name':'统一'},
                { 'id':'16', 'name':'白象'},
                { 'id':'17', 'name':'华丰'},
                { 'id':'18', 'name':'五谷道场'},
                { 'id':'19', 'name':'农心'}
            ]
        },
        {
            'id':'1',
            'name':'食品',
            'brand': [
                { 'id':'12', 'name':'伊利'},
                { 'id':'13', 'name':'蒙牛'},
                { 'id':'15', 'name':'统一'},
                { 'id':'17', 'name':'华丰'},
                { 'id':'18', 'name':'五谷道场'},
            ]
        },
        {
            'id':'2',
            'name':'方便面',
            'brand': [
                { 'id':'11', 'name':'康师傅'},
                { 'id':'13', 'name':'蒙牛'},
                { 'id':'14', 'name':'今麦郎'},
                { 'id':'16', 'name':'白象'},
                { 'id':'17', 'name':'华丰'},
                { 'id':'19', 'name':'农心'}            ]
        },
        {
            'id':'3',
            'name':'乳制品',
            'brand': [
                { 'id':'11', 'name':'康师傅'},
                { 'id':'12', 'name':'伊利'},
                { 'id':'13', 'name':'蒙牛'},
                { 'id':'14', 'name':'今麦郎'},
                { 'id':'16', 'name':'白象'}]
        },
        {
            'id':'4',
            'name':'冰淇淋',
            'brand': [
                { 'id':'12', 'name':'伊利'},
                { 'id':'13', 'name':'蒙牛'},
                { 'id':'15', 'name':'统一'},
                { 'id':'17', 'name':'华丰'},
                { 'id':'18', 'name':'五谷道场'},
                { 'id':'19', 'name':'农心'}            ]
        },
        {
            'id':'5',
            'name':'面包',
            'brand': [
                { 'id':'11', 'name':'康师傅'},
                { 'id':'13', 'name':'蒙牛'},
                { 'id':'16', 'name':'白象'},
                { 'id':'17', 'name':'华丰'},
                { 'id':'18', 'name':'五谷道场'},
                { 'id':'19', 'name':'农心'}            ]
        },
        {
            'id':'6',
            'name':'火腿肠',
            'brand': [
                { 'id':'16', 'name':'白象'},
                { 'id':'17', 'name':'华丰'},
                { 'id':'18', 'name':'五谷道场'},
                { 'id':'19', 'name':'农心'}            ]
        },
        {
            'id':'7',
            'name':'饮料',
            'brand': [
                { 'id':'11', 'name':'康师傅'},
                { 'id':'12', 'name':'伊利'},
                { 'id':'16', 'name':'白象'},
                { 'id':'17', 'name':'华丰'},
                { 'id':'18', 'name':'五谷道场'},
                { 'id':'19', 'name':'农心'}            ]
        },
        {
            'id':'8',
            'name':'生活用品',
            'brand': [
                { 'id':'14', 'name':'今麦郎'},
                { 'id':'15', 'name':'统一'},
                { 'id':'16', 'name':'白象'},
                { 'id':'17', 'name':'华丰'}]
        }
    ];

    display_menu_infos();
}
// generate advertise image list for the advertise part
function simulate_advertise_images(){
    data.advertise_imgs = [
        'images/tmp/u1.png',
        'images/tmp/u2.png',
        'images/tmp/u3.jpg'
    ];

    display_advertise_images();
}

// This is the part that store and load the object in localStorage
Storage.prototype.setObject = function(key, value) {
    this.setItem(key, JSON.stringify(value));
}
Storage.prototype.getObject = function(key) {
    var val = this.getItem(key);
    if(val == "" || val == null) return null;
    return JSON.parse(val);
}