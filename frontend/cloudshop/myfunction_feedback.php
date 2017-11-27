<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php');
$type = isset($_GET['page']) ? $_GET['page'] : '1';
?>

<body style="background: white;">
<div class="my-feedback">
    <textarea id="textarea" class="form-control"
              placeholder="<?= ($type == '1') ? '快来写下你的建议吧' : '退货说明'; ?>"
              oninput="validateText();" title="请至少输入10个字"></textarea>
    <div class="detail_right">
        <h5 id="textLength">0/100</h5>
    </div>
    <button class="btn_confirm" onclick="sendFeedback();">提交<?= ($type == '1') ? '' : '申请'; ?></button>
</div>
<div class="confirm-dlg" style="display: none;">
    <img src="assets/images/pd_success@3x.png">
    <?php
    if ($type == '1') {
        ?>
        <h5>反馈成功</h5>
        <h5><span>您的反馈我们会认真查看，感谢您对我们的支持</span></h5>
        <?php
    } else {
        ?>
        <h5><span>您的退款申请已提交，我们将会尽快处理！</span></h5>
        <?php
    }
    ?>
</div>

</body>

<?php include('page_footer.php'); ?>

<script type="text/javascript">

    var pageId = parseInt(<?=$type?>);
    $(function () {
        document.title = (pageId == 1) ? '意见反馈' : '退款';
        validateText();
        app_data.phone_num = getPhoneNumber();
        $(document).tooltip();
    });

    function sendFeedback() {
        var msg = $('#textarea').val();
        if (msg == '') {
            if (pageId == 1) showNotifyAlert('快来写下你的建议吧。');
            if (pageId == 2) showNotifyAlert('请输入退货说明。');
            return;
        }

        switch (pageId) {
            case 1:
                sendMyFeedbackRequest(msg)
                break;
            case 2:
                var payInfo = addSessionOnlinePayOrderInfo(0)[0];
                sendCancelOrderRequest(payInfo.id, msg);
                break;
            default:
                break;
        }

    }

    function showConfirm() {
        $('.confirm-dlg').css({'display': 'block'});
        setTimeout(function () {
            window.history.back();
        }, 3000);

    }

</script>
</html>
