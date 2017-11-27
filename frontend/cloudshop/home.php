<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php');?>

<body>

<div class="page-wrapper">
    <div class="page-content-wrapper">
        <div class="page-content" style="">
            <div id="product_container" class="product_container" style="overflow-y: scroll;">
            </div>
            <div id="advertise_header" class="owl-carousel">
            </div>
            <div id="horizontal_menu_bar">
            </div>
            <div id="detail_menu">
                <div id="detail_menu_content"></div>
                <div id="detail_menu_mask" onclick="selectCurMenuItem();"></div>
            </div>
        </div>
    </div>
    <input id="cur_location" value="<?php echo json_encode($_SERVER['REMOTE_ADDR'].$location)?>" style="display: none">
</div>

<div id="addToCart_dialog" class="modal fade" tabindex="-1" data-backdrop="basic" data-keyboard="false">
    <div class="modal-body">
        <div class="product_amount"><span>选择数量</span>
            <button class="btn-right" onclick="increaseAmount(9999)">+</button>
            <input id="product_amount9999" value="0" oninput="validateAmount(9999)">
            <input id="min_amount9999" value="1" style="display: none;">
            <input id="max_amount9999" value="100" style="display: none;">
            <button class="btn-left" onclick="decreaseAmount(9999)">-</button>
        </div>
    </div>
    <div class="modal-footer" style="border: none;">
        <button type="button" class="btn_custom btn-default" style="width:35%;"
                onclick="$('#addToCart_dialog').modal('hide');">取消
        </button>
        <button type="button" class="btn_custom"
                onclick="onAddCart()" style="width:35%; padding-left:0;padding-right:0;">加入购物车
        </button>
        <br><br>
    </div>
</div>

<div class="page-footer" id="page-footer">
    <div class="bottom_item" onclick="selectBottomItem(1,0)">
        <img src="assets/images/tabbar_icon1_d@3x.png" id="bottom_item_image1">
        <h5 id="bottom_item_text1" style="color: #38abff">拼货</h5>
    </div>
    <div class="bottom_item" onclick="selectBottomItem(2,0)">
        <img src="assets/images/tabbar_icon2_n@3x.png" id="bottom_item_image2">
        <span id="cart_amount" class="badge badge-alert" style="right:55%"></span>
        <h5 id="bottom_item_text2">购物车</h5>
    </div>
    <div class="bottom_item" onclick="selectBottomItem(3,0)">
        <img src="assets/images/tabbar_icon3_n@3x.png" id="bottom_item_image3">
        <h5 id="bottom_item_text3">消息</h5>
    </div>
    <div class="bottom_item" onclick="selectBottomItem(4,0)">
        <img src="assets/images/tabbar_icon4_n@3x.png" id="bottom_item_image4">
        <h5 id="bottom_item_text4">我的</h5>
    </div>
</div>

</body>

<?php include('page_footer.php'); ?>
<script type="text/javascript" src="assets/js/main.js"></script>

</html>