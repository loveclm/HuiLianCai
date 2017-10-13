var userinfo = {
    'shop_logo_img': '',
    'shop_license_img': ''
};

$(document).ready(function () {
    if (getAuthorizationStatus()) {
        location.href = 'user_register_success.php'
    }
    $('#userID').val(getPhoneNumber());

    resize_individual_info();

    checkValidation()
});

function checkValidation() {
    // check authrization code input state

    $('#shop_logo_img').click(function () {
        $('#upload_shop_logo').trigger('click');
    });

    $('#shop_license_img').click(function () {
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

    $('#shop_addr').on('input focus change', function (e) {
        var shop_addr = $('#shop_addr').val().toString();
        if (shop_addr.length > 30) {
            $('#shop_addr').val(shop_addr.substr(0, 30));
        }
    });

    $('#contact_person').on('input focus change', function (e) {
        var contact_person = $('#contact_person').val().toString();
        if (contact_person.length > 5) {
            $('#contact_person').val(contact_person.substr(0, 5));
        }
    });

    $('#contact_person_phone').on('input focus change', function (e) {
        var contact_person_num = $('#contact_person_phone').val().toString();
        if (contact_person_num.length > 11) {
            $('#contact_person_phone').val(contact_person_num.substr(0, 11));
        }
    });

    $('#business_license_number').on('input focus change', function (e) {
        var business_license_num = $('#business_license_number').val().toString();
        if (business_license_num.length > 50) {
            $('#business_license_number').val(business_license_num.substr(0, 50));
        }
        activateAuthButton()
    });

    $('input').on('input focus change', function () {
        activateAuthButton()
    })
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
            showMessage("图片文件格式错误。");
            return;
        }
        if (input.files[0].size > 10000000) {
            showMessage("图片要不超过10M。");
            return;
        }

        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

//        var reader = new FileReader();
//        reader.onload = function (e) {
//            sessionStorage.removeItem(id)
        document.getElementById(id).src = 'assets/images/' + filename;
        sendUploadImageRequest(data, id, filename)
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
        showAuthRequire('认证信息提交后则不能修改, <br>确认要提交吗？');
    } else {
        showMessage('每个项目都不能清空。');
    }
}

function OnOk() {
    sendUploadUserInfo(userinfo);
}

function OnCancel() {
    $('#auth_question').modal('hide');
}

function checkItems() {
    var status = true
    var shop_type = $('#shop_type').val().toString();
    if (shop_type == '0') status = false;
    else userinfo['shop_type'] = shop_type;

    var shop_name = $('#shop_name').val();
    if (shop_name == "") status = false;
    else userinfo['shop_name'] = shop_name;

    var shop_addr = $('#shop_addr').val();
    if (shop_addr == "") status = false;
    else userinfo['shop_addr'] = shop_addr;

    var contact_person = $('#contact_person').val();
    if (contact_person.length < 2 || contact_person.length > 5) status = false;
    else userinfo['contact_person'] = contact_person;

    var contact_person_phone = $('#contact_person_phone').val();
    if (contact_person_phone.length != 11) status = false;
    else userinfo['contact_person_phone'] = contact_person_phone;

    var business_license_num = $('#business_license_number').val();
    if (business_license_num == "") status = false;
    else userinfo['business_license_num'] = business_license_num;

    userinfo['phone'] = $('#userID').val();

    if (sessionStorage.getItem('shop_logo_img') == undefined) {
        status = false;
    } else {
        userinfo['shop_logo_img'] = sessionStorage.getItem('shop_logo_img')
        document.getElementById('shop_logo_img').src = getImageURL(userinfo.shop_logo_img)
    }

    if (sessionStorage.getItem('shop_license_img') == undefined) {
        status = false;
    } else {
        userinfo['shop_license_img'] = sessionStorage.getItem('shop_license_img')
        document.getElementById('shop_license_img').src = getImageURL(userinfo.shop_license_img)
    }

    return status;
}