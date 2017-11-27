/*
 fileName:
 description: process Tourist Area
 */

// Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
var tree_data = [];

$(document).ready(function () {
    loadingRoleData();

    $('#show-values').on('click', function(){
        $('#values').text(
            $('#treeview-container').treeview('selectedValues')
        );
    });
    showList();
});

function showList() {
    $.ajax({
        type: 'POST',
        url: baseURL + 'systemmanage/item_listing',
        dataType: 'json',
        data:{ id : 1 },
        success: function(res){
            if (res.status == 'success') {
                $('#header_tbl').html(res.header);
                $('#content_tbl').html(res.content);
                $('#footer_tbl').html(res.footer);
                //executionPageNation();
            } else {
                // alert('search failed!');
                console.log(res.data);
            }
        }
    });
}

function loadingRoleData(){
    $.ajax({
        type: 'POST',
        url: baseURL + 'systemmanage/roleInfos',
        dataType: 'json',
        success: function(data){
            tree_data = data;
        }
    });
}

function makeContent(data){
    var content_html = '';
    if(data.length > 0){
        content_html += '<ul>';
        for(var i =0; i < data.length; i++){
            if( data[i]['id'] == '') {
                content_html += '<li>'+data[i]['text'];
            }else{
                content_html += '<li data-value="'+data[i]['id']+'">'+data[i]['text'];
            }
            if(data[i]['data'].length > 0){
                content_html += makeContent(data[i]['data']);
            }
            content_html += '</li>';
        }
        content_html += '</ul>'
    }
    return content_html;
}

function confirmDelete(id) {
    $('#roleId').html(id);
    $('#custom-confirm-delete-view').show();
}

function showRoleEdit(roleId) {
    $('#roleId').html(roleId);
    var content = makeContent(tree_data);
    $('#treeview-container').html(content);
    JSON.parse($('#permission'+roleId).html());
    $('#treeview-container').treeview({
        data : JSON.parse($('#permission'+roleId).html())
    });
    $('#custom-generate-auth-view').show();
}

function updateRole(url, role) {
    var roleId = $('#roleId').html();
    var permission =  $('#treeview-container').treeview('selectedValues');
    $('#custom-generate-auth-view').hide();

    $('#permission' + roleId).html(JSON.stringify(permission));
    $.ajax({
        type: 'POST',
        url: url + 'systemmanage/updateRole',
        dataType: 'json',
        data: {
            'id': roleId,
            'permission': JSON.stringify(permission)
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            if (data['status']) {
                if(role == roleId) location.href = url+'logout';
                else location.reload();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}
function addRole(url) {
    var roleName = $('#rolename').val();
    if (roleName == '' || roleName.length > 10) {
        $('#alertmsg').html("不超过10个字符；名称不允许相同.");
        $('#alertmsg').show();
        return;
    } else {
        $('#alertmsg').html("");
        $('#alertmsg').hide();
    }
    $('#custom-generate-auth-count-view').hide();
    $.ajax({
        type: 'POST',
        url: url + 'systemmanage/addRole',
        dataType: 'json',
        data: {
            'roleName': roleName
        },
        success: function (data, textStatus, jqXHR) {
            if (JSON.parse(data['status'])) {
                location.reload();
            } else {
                $('#alertmsg').html("不超过10个角色.");
                $('#alertmsg').show();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}
function deleteRole(url) {
    var roleId = $('#roleId').html();
    location.href = url + 'systemmanage/deleteRole/' + roleId;
}
