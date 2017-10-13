<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body class="page-footer-fixed">
    <div class="page-wrapper">
        <div class="page-content-wrapper">
            <div class="page-content" style="overflow-y: scroll;background: white;">
                <div class="register_row">
                    <label>*上传店招：</label>
                    <img id="shop_logo_img" src="assets/images/shop_logo.png">
                    <input type="file" id="upload_shop_logo" style="display: none">
                </div>
                <div class="register_row">
                    <label>*类型：</label>
                    <select id="shop_type" class="form-control input-small input-inline">
                        <option value="0">请选择</option>
                        <option value="1">便利店</option>
                        <option value="2">中型超市</option>
                        <option value="3">餐饮店</option>
                        <option value="4">其他业态</option>
                    </select>
                </div>
                <div class="register_row">
                    <label>*账号：</label>
                    <input id="userID" type="text" value="" disabled>
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
                    <input id="contact_person_phone" type="number">
                </div>
                <div class="register_row">
                    <label style="width: 100%">*营业执照编号：</label>
                    <textarea class="form-control" id="business_license_number" rows="3"></textarea>
                </div>
                <div class="register_row">
                    <label  style="width: auto">*营业执照编号：</label>
                    <img id="shop_license_img" src="assets/images/shop_license.png" style="">
                    <input type="file" id="upload_shop_license" style="display: none">
                </div>
            </div>
        </div>
        <div class="page-footer">
            <div id="btn_Authorize" onclick="">提交认证</div>
        </div>
    </div>
</body>

<?php include('page_footer.php'); ?>

    <script src="assets/js/user_manage/individual_info.js" type="text/javascript"></script>
</html>
