<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body>

<div id="content" class="page-wrapper">
    <div id="container" class="page-content-wrapper">

        <div id="horizontal_order_menu_bar">
        </div>
        <div id="product_container" class="tab-pane">

        </div>
        <!-- /.tab-content -->
    </div>
</div>
<div class="modal custom-modal" id="confirm">
    <div class="modal-dialog" id="confirm_dialog">
        <div class="modal-content">
            <!--<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">请删除</h4>
            </div>-->
            <div class="modal-body">
                <p>确认取消订单吗？</p>
            </div>
            <div class="modal-footer" style="border: none;">
                <button type="button" class="btn btn-default" onclick="SendOrderCancelRequest()">确认</button>
                <button type="button" class="btn btn-primary" onclick="OnCancel()">取消</button>
            </div>
            <br><br>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
        <button type="button" class="btn_custom btn-default"
                onclick="$('#addToCart_dialog').modal('hide');">取消
        </button>
        <button type="button" class="btn_custom"
                onclick="onAddCart()">加入购物车
        </button>
    </div>
</div>

</body>

<?php include('page_footer.php'); ?>
<script type="text/javascript" src="assets/js/provider_detail.js"></script>

</html>