<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body>
<img class="col-md-offset-5 col-md-2 col-xs-offset-3 col-xs-6" id="success_logo" src="assets/images/suceeess.png">
<div class="col-md-offset-4 col-md-4 col-xs-offset-1 col-xs-10">
    <div style="font-size: 18px;">
        <label>提交成功，请耐心等待后台审核！</label>
    </div>
    <div class="btn_back" style="text-align: center">
        <label onclick="back()">返回</label>
    </div>
</div>
</div>

</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>
<script type="text/javascript">
    function back() {
        window.location.href = '../index.php';
    }

</script>
</html>
