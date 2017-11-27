var userinfo = {
    'shop_logo_img': '',
    'shop_license_img': ''
};

$(document).ready(function () {
    if(getAuthorizationStatus()){
        location.href='user_register_success.php'
    }
    $('#shop_logo_img').click(function () {
        $('#upload_shop_logo').trigger('click');
    });
    $('#shop_license_img').click(function () {
        $('#upload_shop_license').trigger('click');
    });

    $('#userID').val(getPhoneNumber());

    resize_individual_info();

    checkValidation()
});

function checkValidation() {
    // check authrization code input state
    $('#shop_name').on('input', function (e) {
        var shop_name = $('#shop_name').val().toString();
        if (shop_name.length > 10) {
            $('#shop_name').val(shop_name.substr(0, 10));
        }
    });

    $('#shop_addr').on('input', function (e) {
        var shop_addr = $('#shop_addr').val().toString();
        if (shop_addr.length > 30) {
            $('#shop_addr').val(shop_addr.substr(0, 30));
        }
    });

    $('#contact_person').on('input', function (e) {
        var contact_person = $('#contact_person').val().toString();
        if (contact_person.length > 5) {
            $('#contact_person').val(contact_person.substr(0, 5));
        }
    });

    $('#contact_person_phone').on('input', function (e) {
        var contact_person_num = $('#contact_person_phone').val().toString();
        if (contact_person_num.length > 11) {
            $('#contact_person_phone').val(contact_person_num.substr(0, 11));
        }
    });

    $('#business_license_number').on('input', function (e) {
        var business_license_num = $('#business_license_number').val().toString();
        if (business_license_num.length > 50) {
            $('#business_license_number').val(business_license_num.substr(0, 50));
        }
        if (checkItems()) {
            $('#btn_Authorize').attr({onclick: 'confirm()', style: 'background:#38abff'})
        }
        else {
            $('#btn_Authorize').attr({onclick: '', style: 'background:darkgrey'})
        }
    });
    $('input').on('input', function () {
        if (checkItems()) {
            $('#btn_Authorize').attr({onclick: 'confirm()', style: 'background:#38abff'})
        }
        else {
            $('#btn_Authorize').attr({onclick: '', style: 'background:darkgrey'})
        }
    })
}

function resize_individual_info() {
    var height = document.body.clientHeight
        || document.documentElement.clientHeight
        || window.innerHeight;
    var footer_height = $('.page-footer').css('height');
    $('.page-content').css({'height': parseInt(height) - parseInt(footer_height)});
}

function add_upload_file(input, id) {
    if (input.files && input.files[0]) {
        // check whether file extension is ( jpg, jpeg, png)
        var filename = input.files[0].name;
        var k = filename.indexOf(".");
        var len = filename.length - k - 1;
        var file_ext = filename.substr(k + 1, len);

        if (file_ext != 'jpg' && file_ext != 'jpeg' && file_ext != 'png') {
            showMessage('文件格式错误。');
            return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById(id).src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
        userinfo[id] = filename;
    }
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
    if (userinfo.shop_license_img == "" || userinfo.shop_logo_img=='') return false;

    var shop_type = $('#shop_type').val();
    if (shop_type == "请选择") return false;
    userinfo['shop_type'] = shop_type;

    var shop_name = $('#shop_name').val();
    if (shop_name == "") return false;
    userinfo['shop_name'] = shop_name;

    var shop_addr = $('#shop_addr').val();
    if (shop_addr == "") return false;
    userinfo['shop_addr'] = shop_addr;

    var contact_person = $('#contact_person').val();
    if (contact_person.length < 2 || contact_person.length > 5) return false;
    userinfo['contact_person'] = contact_person;

    var contact_person_phone = $('#contact_person_phone').val();
    if (contact_person_phone.length != 11) return false;
    userinfo['contact_person_phone'] = contact_person_phone;

    var business_license_num = $('#business_license_number').val();
    if (business_license_num == "") return false;
    userinfo['business_license_num'] = business_license_num;

    userinfo['phone'] = $('#userID').val();
    return true;
}