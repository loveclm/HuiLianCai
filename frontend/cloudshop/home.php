<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body>
<div class="page-wrapper">
    <div class="page-content-wrapper">
        <div class="page-content" style="">
            <div id="product_container" class="product_container" style="overflow-y: scroll;">
            </div>
            <div id="advertise_header" class="carousel slide">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                </div>
                <!-- Left and right controls -->

                <a class="left carousel-control" href="#advertise_header" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#advertise_header" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
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
        <img src="assets/images/tabbar_icon1_d@3x.png" id="bottom_item_image1">
        <h5 id="bottom_item_text1" style="color: #38abff">拼货</h5>
    </div>
    <div class="bottom_item"  onclick="selectBottomItem(2,0)">
        <img src="assets/images/tabbar_icon2_n@3x.png" id="bottom_item_image2">
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
<script type="text/javascript" src="assets/js/main.js"></script>
<script type="text/javascript" src="assets/js/item_templates.js"></script>

</html>