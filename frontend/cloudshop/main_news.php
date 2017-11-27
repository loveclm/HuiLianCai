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
    <div class="bottom_item" onclick="selectBottomItem(1,0)">
        <img src="assets/images/tabbar_icon1_n@3x.png" id="bottom_item_image1">
        <h5 id="bottom_item_text1">拼货</h5>
    </div>
    <div class="bottom_item"  onclick="selectBottomItem(2,0)">
        <img src="assets/images/tabbar_icon2_n@3x.png" id="bottom_item_image2">
        <span id="cart_amount" class="badge badge-alert" style="right:55%"></span>
        <h5 id="bottom_item_text2" >购物车</h5>
    </div>
    <div class="bottom_item"  onclick="selectBottomItem(3,0)">
        <img src="assets/images/tabbar_icon3_n@3x.png" id="bottom_item_image3">
        <h5 id="bottom_item_text3" >消息</h5>
    </div>
    <div class="bottom_item"  onclick="selectBottomItem(4,0)">
        <img src="assets/images/tabbar_icon4_n@3x.png" id="bottom_item_image4">
        <h5 id="bottom_item_text4" >我的</h5>
    </div>
</div>

</body>

<?php include('page_footer.php'); ?>
<script type="text/javascript" src="assets/js/main_news.js"></script>

</html>