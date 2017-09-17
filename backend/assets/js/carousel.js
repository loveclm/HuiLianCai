/*
 fileName: area.js
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute

$(document).ready(function () {
    var pageName = $("#page_Name").val();
    switch (pageName) {
        case 'carousel':
            showLists(1);
            break;
        case 'carousel_add':
            break;
    }
});

function showLists(id) {

    $.ajax({
        type: 'post',
        url: baseURL + 'carousel_controller/item_listing',
        dataType: 'json',
        data: {id: id},
        success: function (res) {
            if (res.status == 'success') {
                $('#header_tbl').html(res.header);
                $('#content_tbl').html(res.content);
                $('#footer_tbl').html(res.footer);
            } else {
                alert('search failed!');
                console.log(res.data);
            }
        }
    });
}

$('input[name="radio_caro_type"]').click(function () {
    switch($(this).val()){
       case "1":  // hide active name select item
           $('#select_active_group').css({'display':'none'});
           break;
       case "2":  // 单品活动
           $('#select_active_group').css({'display':'block'});
           break;
       case "3":  //餐装活动
           $('#select_active_group').css({'display':'block'});
           break;
       case "4":  //供货商
           $('#select_active_group').css({'display':'block'});
           break;
   }
});

$('#carousel_add_submit').on('click', function () {
    var image_name = $("#image_filename").html().trim();
    if (image_name == 'logo.png') {
        alert("请选择图片并裁剪.");
        return;
    }
    var type = $("input[name='radio_caro_type']:checked").val();
    var sort = $("#sort_number").val();
    if (sort == '' || sort == '0') {
        sort = 1000;
    } else if (parseFloat(sort) <= 0) {
        alert("排序不允许为0.");
        return;
    }

    var activity = $('#select_active_list').val();

    var itemInfo = {
        'image': image_name,
        'type': type,
        'sort': sort,
        'activity': activity,
        'status': 0 // 0-disable, 1-available
    };

    $.ajax({
        url: baseURL + "carousel_controller/addItem2DB",
        type: "POST",
        data: {'itemInfo': itemInfo},
        success: function (result) {
            console.log(result);
            if (result != 0)
                location.href = baseURL + 'carousel';
            else
                window.alert("Database is not responding.");
        }
    });
});


function spinnerChange(val) {
    var sort = $("#sort_number").val();
    if (val == 1) sort++;
    else if (sort > 0) sort--; else sort=0;
    $("#sort_number").val(sort);
}

function deleteConfirm(id) {
    $("#item_id").val(id);
    $("#confirm_delete").show();
}

function deployConfirm(id, status) {
    if(status == 0)// status=0-disable, 1-available
        $("#confirm-deploy-message").html("确定要下架吗？");
    else
        $("#confirm-deploy-message").html("确定要上架吗？");
    $("#item_id").val(id);
    $("#item_status").val(status); // status=0-disable, 1-available
    $("#confirm_deploy").show();
}

function deployItem() {
    var id = $("#item_id").val();
    $("#confirm_deploy").hide();


    var itemInfo = {
        'id': id,
        'status': $("#item_status").val() // status=0-disable, 1-available
    };

    $.ajax({
        url: baseURL + "carousel_controller/addItem2DB",
        type: "POST",
        data: {'itemInfo': itemInfo},
        success: function (result) {
            console.log(result);
            if (result != 0)
                showLists(1);
            else
                window.alert("上架数量最多才5张.");
        }
    });

}

function deleteItem() {
    var id = $("#item_id").val();
    $("#confirm_delete").hide();

    var itemInfo = {
        'id': id,
    };

    $.ajax({
        url: baseURL + "carousel_controller/deleteItemFromDB",
        type: "POST",
        data: {'itemInfo': itemInfo},
        success: function (result) {
            console.log(result);
            if (result != 0)
                showLists(1);
            else
                window.alert("Database is not responding.");
        }
    });

}