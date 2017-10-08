<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php');
$type = isset($_GET['page']) ? $_GET['page'] : '1';
?>

<body style="background: white;">
<div class="my-feedback">
    <textarea id="textarea" class="form-control"
              placeholder="<?= ($type == '1') ? '快来写下你的建议吧' : '退货说明'; ?>"
              oninput="validateText();"></textarea>
    <div class="detail_right">
        <h5 id="textLength">4/200</h5>
    </div>
    <button class="btn_confirm" onclick="showConfirm();">提交<?= ($type == '1') ? '' : '申请'; ?></button>
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
    $(function () {
        app_data.phone_num = sessionStorage.getItem('phone_num');
    });

    function showConfirm() {
        $('.confirm-dlg').css({'display': 'block'});
        setTimeout(function () {
            window.history.back();
        }, 3000);

    }

</script>
</html>
