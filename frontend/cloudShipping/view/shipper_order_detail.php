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
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

</body>

<?php include('page_footer.php'); ?>
<script type="text/javascript" src="assets/js/shipper_order_detail.js"></script>
<script type="text/javascript" src="assets/js/item_templates.js"></script>

</html>