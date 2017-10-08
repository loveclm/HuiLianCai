<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body>
<div class="page-wrapper">
    <div class="page-content-wrapper">
        <div class="page-content" style="">
            <div id="product_container" class="product_container" style="overflow-y: scroll;">
            </div>
            <div id="advertise_header" class="fixed-header">
            </div>
            <div id="horizontal_menu_bar">
            </div>
            <div id="detail_menu">
                <div id="detail_menu_content"></div>
                <div id="detail_menu_mask"></div>
            </div>
        </div>
    </div>
</div>

<div class="page-footer">
    <div class="bottom_item" style="width: 33%" onclick="selectShipperBottomItem(1,0)">
        <img src="assets/images/dist_tabbar_icon1_n@3x.png" id="bottom_item_image1">
        <h5 id="bottom_item_text1">配送订单</h5>
    </div>
    <div class="bottom_item" style="width: 33%" onclick="selectShipperBottomItem(2,0)">
        <img src="assets/images/dist_tabbar_icon2_n@3x.png" id="bottom_item_image2">
        <h5 id="bottom_item_text2" >历史配送</h5>
    </div>
    <div class="bottom_item"  style="width: 33%" onclick="selectShipperBottomItem(3,0)">
        <img src="assets/images/dist_tabbar_icon3_d@3x.png" id="bottom_item_image4">
        <h5 id="bottom_item_text3" >我的</h5>
    </div>
</div>

</body>

<?php include('page_footer.php'); ?>
<script type="text/javascript" src="assets/js/shipper_manage.js"></script>
<script type="text/javascript" src="assets/js/item_templates.js"></script>

</html>