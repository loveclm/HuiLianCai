<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body>

<div id="top_dialog" class="modal amount-setting fade" tabindex="-1" data-backdrop="basic" data-keyboard="false">
    <div class="modal-body">
    </div>
</div>
<div id="overlay-image"></div>

<div class="page-wrapper">
    <div class="page-content-wrapper">
        <div class="page-content" style="">
            <div class="product_container" style="overflow-y: scroll;">

                <div id="advertise_header" class="owl-carousel">
                </div>
                <div id="product_container"></div>

            </div>
        </div>
    </div>

</div>

<div class="page-footer">
    <div class="bottom_item_product" onclick="selectBottomItem(1)">
        <img src="assets/images/product_tabbar_icon1_n@3x.png" id="bottom_item_image1">
        <h5 id="bottom_item_text1">主页</h5>
    </div>
    <div class="bottom_item_product" onclick="selectBottomItem(2)">
        <img src="assets/images/product_tabbar_icon2_n@3x.png" id="bottom_item_image2">
        <h5 id="bottom_item_text2">收藏</h5>
    </div>
    <div class="bottom_item_product" onclick="selectBottomItem(3)">
        <img src="assets/images/product_tabbar_icon3_n@3x.png" id="bottom_item_image3">
        <span id="cart_amount" class="badge badge-alert"></span>
        <h5 id="bottom_item_text3">购物车</h5>
    </div>
    <div id="bottom-btn-detail1" class="bottom_item_product button1" onclick="selectBottomItem(4)">
        加入购物车
    </div>
    <div id="bottom-btn-detail2" class="bottom_item_product button2" onclick="selectBottomItem(5)">
        一键参团
    </div>
</div>

<div class="modal fade" id="confirm_dialog">
</div>

</body>
<?php include('page_footer.php'); ?>

<script type="text/javascript" src="assets/js/product_detail.js"></script>

</html>