<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body class="page-footer-fixed">
    <div class="page-wrapper">
        <div class="page-content-wrapper">
            <div class="page-content" style="overflow-y: scroll;">
                <div class="register_row">
                    <label>*上传店招：</label>
                    <img id="shop_logo_img" src="assets/images/shop_logo.png">
                    <input type="file" id="upload_shop_logo" onchange="add_upload_file(this, 'shop_logo_img');" style="display: none">
                </div>
                <div class="register_row">
                    <label>*类型：</label>
                    <select id="shop_type" class="form-control input-small input-inline">
                        <option value="请选择">请选择</option>
                        <option value="便利店">便利店</option>
                        <option value="中型超市">中型超市</option>
                        <option value="餐饮店">餐饮店</option>
                        <option value="其他业态">其他业态</option>
                    </select>
                </div>
                <div class="register_row">
                    <label>*账号：</label>
                    <input id="userID" type="text" value="15842163214" disabled>
                </div>
                <div class="register_row">
                    <label>*名称：</label>
                    <input id="shop_name" type="text" placeholder="例如：辛集镇惠佳便利店">
                </div>
                <div class="register_row">
                    <label>*地址：</label>
                    <input id="shop_addr" type="text" placeholder="山东省临沂市">
                </div>
                <div class="register_row">
                    <label>*联系人：</label>
                    <input id="contact_person" type="text">
                </div>
                <div class="register_row">
                    <label>*联系电话：</label>
                    <input id="contact_person_phone" type="text">
                </div>
                <div class="register_row">
                    <label style="width: 100%">*营业执照编号：</label>
                    <textarea class="form-control" id="business_license_number" rows="3" style="padding: 10px"></textarea>
                </div>
                <div class="register_row">
                    <label style="width: 100%">*营业执照编号：</label>
                    <img id="shop_license_img" src="assets/images/shop_license.png" style="margin-left: 20%">
                    <input type="file" id="upload_shop_license" onchange="add_upload_file(this, 'shop_license_img');" style="display: none">
                </div>
            </div>
        </div>
        <div class="page-footer">
            <div id="btn_Authorize" onclick="confirm()">提交认证</div>
        </div>
    </div>

    <div id="auth_question" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body" style="text-align: center">
            <b>认证信息提交后则不能修改，确认要提交吗？</b>
        </div>
        <div class="modal-footer">
            <div class="btn_custom" onclick="OnOk()">确认</div>
            <div class="btn_custom" id="cancel_button" onclick="OnCancel()">取消</div>
        </div>
    </div>
</body>

<?php include('page_footer.php'); ?>

    <script src="assets/js/user_manage/individual_info.js" type="text/javascript"></script>
</html>
