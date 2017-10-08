<!DOCTYPE html>
<html lang="en">
<?php include('page_header.php'); ?>
<script type="text/javascript" src="../cloudshop/assets/global/plugins/datetimepicker/jquery.simple-dtpicker.js"></script>
<link type="text/css" href="../cloudshop/assets/global/plugins/datetimepicker/jquery.simple-dtpicker.css" rel="stylesheet" />
<body>
<div class="page-wrapper">
    <div class="page-content-wrapper">
        <div class="page-content" style="">
            <div id="product_container" class="product_container" style="overflow-y: scroll;">
            </div>
            <div id="advertise_header" class="fixed-header">
            </div>
            <div id="horizontal_menu_bar"  style="padding-left:5px;" >
                送达时间
                <input type="text" name="date10" style="width:25%; margin-top: 2px;" value=""/>&nbsp-&nbsp
                <input type="text" name="date11" style="width:25%; margin-top: 2px;" value=""/>
                <button type="button" class="btn_custom btn-primary" style="margin:0px 10px;">查询</button>
                <script type="text/javascript">
                    $(function(){
                        var date = new Date();
                        date.setDate(date.getDate()-1);
                        $('*[name=date10]').appendDtpicker({
                            "closeOnSelected": true,
                            "dateOnly":true,
                            "locale": "cn"
                        });
                        $('*[name=date10]').handleDtpicker('setDate', date);

                    });
                    $(function(){
                        $('*[name=date11]').appendDtpicker({
                            "closeOnSelected": true,
                            "dateOnly":true,
                            "locale": "cn"
                        });
                    });
                </script>
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
        <h5 id="bottom_item_text1">拼货</h5>
    </div>
    <div class="bottom_item" style="width: 33%" onclick="selectShipperBottomItem(2,0)">
        <img src="assets/images/tabbar_icon2_n@3x.png" id="bottom_item_image2">
        <h5 id="bottom_item_text2" >购物车</h5>
    </div>
    <div class="bottom_item"  style="width: 33%" onclick="selectShipperBottomItem(3,0)">
        <img src="assets/images/tabbar_icon4_n@3x.png" id="bottom_item_image4">
        <h5 id="bottom_item_text4" >我的</h5>
    </div>
</div>

</body>

<?php include('page_footer.php'); ?>
<script type="text/javascript" src="assets/js/shipper_history.js"></script>
<script type="text/javascript" src="assets/js/item_templates.js"></script>

</html>