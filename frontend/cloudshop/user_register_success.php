<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body>

</div>

</body>

<?php include('page_footer.php'); ?>

<script src="assets/js/user_manage/login.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('body').html(successProcessingTemplate('1'))
    })
    function back() {
        setAuthRequestStatus(true);
        window.location.href = 'myfunction_manage.php';
    }

</script>
</html>
