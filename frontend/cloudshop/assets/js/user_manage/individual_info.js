var userinfo = {
    'shop_logo_img': '',
    'shop_license_img': ''
};

$(document).ready(function () {
    document.title = '终端便利店认证';
    var userinfo = localStorage.getItem('user_register_detail')
    if (userinfo != undefined) {
        userinfo = JSON.parse(userinfo);
        $('#userID').val(getPhoneNumber());
        if (userinfo.shop_name != undefined) {
            $('#shop_logo_img').attr('src', getImageURL(userinfo.shop_logo_img));
            $('#shop_license_img').attr('src', getImageURL(userinfo.shop_license_img));
            $('#shop_type').val(userinfo.shop_type);
            $('#shop_name').val(userinfo.shop_name);
            $('#contact_person').val(userinfo.contact_person);
            $('#contact_person_phone').val(userinfo.contact_person_phone);
            $('#business_license_number').val(userinfo.business_license_num);
            $('#shop_addr').val(userinfo.shop_addr_gps);
            if (userinfo.shop_addr != undefined) {
                var shop_addr = userinfo.shop_addr.toString().split(',');
                $('#provinceName').val(shop_addr[0]);
                $('#cityName').val(shop_addr[1]);
                $('#districtName').val(shop_addr[2]);
            }
            sessionStorage.setItem('shop_license_img', userinfo.shop_license_img);
            sessionStorage.setItem('shop_logo_img', userinfo.shop_logo_img);
            setTimeout(function () {
                document.getElementById('province').innerHTML = '<option>' + shop_addr[0] + '</option>';
                document.getElementById('city').innerHTML = '<option>' + shop_addr[1] + '</option>';
                ;
                document.getElementById('district').innerHTML = '<option>' + shop_addr[2] + '</option>';
                ;
            }, 0);
        }
        if (!getAuthRequestStatus()) { // userInfo is not sent
            $(document).tooltip();
            resize_individual_info();
            checkValidation();
            activateAuthButton();
        } else if (getSessionMyInfo().status == '4') { // enable editing, authorization failed
//            setAuthRequestStatus(false);
            //showAuthRequire();
            $(document).tooltip();
            resize_individual_info();
            checkValidation();
            activateAuthButton();
        } else { // userInfo is sent
            $('#btn_Authorize').html('认证中');
            $('input').attr('disabled', true);
            $('select').attr('disabled', true);
        }
    } else { // userInfo is not exist
        if (!getRegisterStatus()) { // not registered
            showAuthRequire();
            return;
        } else if (getSessionMyInfo().status == '4') { // enable editing, authorization failed
            //setAuthRequestStatus(false);
//            showAuthRequire();
            //$('#btn_Authorize').html('认证失败');
            //$('input').attr('disabled', true);
            // $('select').attr('disabled', true);
            // return;
        }
        $(document).tooltip();

        if (getPhoneNumber() == '')
            document.getElementById('userID').removeAttribute('disabled');
        else
            $('#userID').val(getPhoneNumber());

//    alert('positioning.');
//    getMyPosition();

        resize_individual_info();

        checkValidation();
        activateAuthButton();
    }
    setTimeout(function () {
        getWeixinLocation();
    }, 10);
});

function checkValidation() {
    // check authrization code input state

    $('#shop_logo_img').click(function () {
        $('#upload_shop_logo').val('');
        $('#upload_shop_logo').trigger('click');
    });

    $('#shop_license_img_cover').click(function () {
        $('#upload_shop_license').val('');
        $('#upload_shop_license').trigger('click');
    });

    $('#upload_shop_logo').on('change', function (e) {
        add_upload_file(e, this, 'shop_logo_img')

    })
    $('#upload_shop_license').on('change', function (e) {
        add_upload_file(e, this, 'shop_license_img')

    })

    $('#shop_type').on('change focus click', function () {
        activateAuthButton()
    })

    $('#shop_name').on('input focus change', function (e) {
        var shop_name = $('#shop_name').val().toString();
        if (shop_name.length > 10) {
            $('#shop_name').val(shop_name.substr(0, 10));
        }
    });
    // $('#shop_name').on('click', function (e) {
    //     var shop_name = $('#shop_name').val().toString();
    //     showNotifyAlert('便利店名称应该不超过10个字符。', 1)
    // });

    $('#shop_addr').on('input focus change', function (e) {
        var shop_addr = $('#shop_addr').val().toString();
        if (shop_addr.length > 30) {
            $('#shop_addr').val(shop_addr.substr(0, 30));
        }
    });
    $('#shop_addr').on('click', function (e) {
        var shop_addr = $('#shop_addr').val().toString();
        // showNotifyAlert('便利店地址应该不超过30个字符。', 1);
        if ($('#shop_addr').val() == '') {
            getWeixinLocation();
        }
    });

    $('#contact_person').on('input focus change', function (e) {
        var contact_person = $('#contact_person').val().toString();
        if (contact_person.length > 5) {
            $('#contact_person').val(contact_person.substr(0, 5));
        }
    });

    // $('#contact_person').on('click', function (e) {
    //     var contact_person = $('#contact_person').val().toString();
    //     showNotifyAlert('姓名应该2-5个字符。', 1);
    // });

    $('#contact_person_phone').on('input focus change', function (e) {
        var contact_person_num = $('#contact_person_phone').val().toString();
        if (contact_person_num.length > 11) {
            $('#contact_person_phone').val(contact_person_num.substr(0, 11));
        }
    });
    // $('#contact_person_phone').on('click', function (e) {
    //     var contact_person_num = $('#contact_person_phone').val().toString();
    //     showNotifyAlert('联系电话应该11个字符。', 1);
    // });

    $('#business_license_number').on('input focus change', function (e) {
        var business_license_num = $('#business_license_number').val().toString();
        if (business_license_num.length > 30) {
            $('#business_license_number').val(business_license_num.substr(0, 30));
        }
        activateAuthButton()
    });
    // $('#business_license_number').on('click', function (e) {
    //     var business_license_num = $('#business_license_number').val().toString();
    //     showNotifyAlert('营业执照编号应该不超过30个字符。', 1);
    // });

    $('input').on('input focus change', function () {
        activateAuthButton();
        $('.ui-tooltip.ui-corner-all').show();
    })

    $('input').on('blur', function () {
        $('.ui-tooltip.ui-corner-all').hide();
    });

    setTimeout(function () {
        $('input').trigger('change');
        $('input').trigger('blur');
    }, 500);
}

function resize_individual_info() {
    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = $('.page-footer').css('height');
    $('.page-content').css({'height': parseInt(height) - parseInt(footer_height)});
}

function add_upload_file(event, input, id) {
    if (input.files && input.files[0]) {
        // check whether file extension is ( jpg, jpeg, png)
        files = event.target.files;
        var filename = input.files[0].name;
        var k = filename.indexOf(".");
        var len = filename.length - k - 1;
        var file_ext = filename.substr(k + 1, len);

        if (input.files[0].type != "image/jpeg" && input.files[0].type != "image/png") {
            showNotifyAlert("图片文件格式错误。");
            return;
        }
        if (input.files[0].size > 5000000) {
            showNotifyAlert("图片请不要超过5M。");
            return;
        }

        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

//        var reader = new FileReader();
//        reader.onload = function (e) {
//            sessionStorage.removeItem(id)
//        document.getElementById(id).src = 'assets/images/' + filename;
//        $('#shop_license_img_cover').attr('style','opacity:0');
//        $('#shop_license_img_cover').css({'width':$('#shop_license_img').css('width')})
        if (id == "shop_logo_img")
            sendUploadLogoImageRequest(data, id, filename);
        else
            sendUploadImageRequest(data, id, filename);
//        }
//        reader.readAsDataURL(input.files[0]);
        //userinfo[id] = filename;
    }
}

function activateAuthButton(imgName) {
    if (checkItems()) {
        $('#btn_Authorize').attr({onclick: 'confirm()', style: 'background:#38abff'})
    }
    else {
        $('#btn_Authorize').attr({onclick: '', style: 'background:darkgrey'})
    }
}

function confirm() {
    if (checkItems()) {
        setAuthRequestStatus(false);
        showAuthRequire('认证信息提交后则不能修改, <br>确认要提交吗？', '确认');
    } else {
        showNotifyAlert('每个项目都不能为空。');
    }
}

function checkItems() {
    var status = true
    var shop_type = $('#shop_type').val().toString();
    if (shop_type == '0') {
        status = false;
    }
    else {
        userinfo['shop_type'] = shop_type;
    }

    var shop_name = $('#shop_name').val();
    if (shop_name == "") {
        status = false;
    } else {
        userinfo['shop_name'] = shop_name;
    }

    userinfo['phone'] = $('#userID').val();

    var shop_addr_gps = $('#shop_addr').val();
    if (shop_addr_gps == "") {
        status = false;
    } else {
        userinfo['shop_addr_gps'] = shop_addr_gps;
    }

    var province = $('#provinceName').val();
    var city = $('#cityName').val();
    var district = $('#districtName').val();

    if (province == '' || city == '' || district == '') status = false;
    else userinfo['shop_addr'] = province + ',' + city + ',' + district;


    var contact_person = $('#contact_person').val();
    if (contact_person.length < 2 || contact_person.length > 5) status = false;
    else userinfo['contact_person'] = contact_person;

    var contact_person_phone = $('#contact_person_phone').val();
    if (contact_person_phone.length != 11)
        status = false;
    else userinfo['contact_person_phone'] = contact_person_phone;

    var business_license_num = $('#business_license_number').val();
    if (business_license_num == "") status = false;
    else userinfo['business_license_num'] = business_license_num;

    var lat_lng = $('#my_LatLng').val();
    if (lat_lng == "") {
        // showNotifyAlert('用户拒绝授权获取地理位置。');
        lat_lng = [];
        userinfo['lat'] = '';
        userinfo['lng'] = '';
    } else {
        userinfo['lat'] = JSON.parse(lat_lng).lat;
        userinfo['lng'] = JSON.parse(lat_lng).lng;
    }

    if (sessionStorage.getItem('shop_logo_img') == undefined) {
        status = false;
    } else {
        userinfo['shop_logo_img'] = sessionStorage.getItem('shop_logo_img')
        document.getElementById('shop_logo_img').src = getImageURL(userinfo.shop_logo_img)
    }

    if (sessionStorage.getItem('shop_license_img') == undefined) {
        status = false;
        $('#shop_license_img_cover').attr('style', 'opacity:0.5');
    } else {
        userinfo['shop_license_img'] = sessionStorage.getItem('shop_license_img');
        if (userinfo['shop_license_img'] != '') {
            document.getElementById('shop_license_img').src = getImageURL(userinfo.shop_license_img)
            //$('#shop_license_img').css({'width': 'auto'});
            $('#shop_license_img_cover').attr('style', 'opacity:0');
        } else {
            $('#shop_license_img_cover').attr('style', 'opacity:0.5');
        }
    }

//    $('#shop_license_img_cover').css({'width': $('#shop_license_img').css('width')})
    return status;
}

function OnOk() {
    $('#auth_question').modal('hide');
    //alert(JSON.stringify(userinfo));
    if (getAuthRequestStatus() && getSessionMyInfo().status != '4')
        history.back();
    else {
        localStorage.setItem("user_register_detail", JSON.stringify(userinfo));
        sendUploadUserInfo(userinfo);
    }
}

function OnCancel() {
    $('#auth_question').modal('hide');
}
