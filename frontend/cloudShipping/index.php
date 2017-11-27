<?php
$shop_id = 0;
$type = 0;
$target_id = 0;

if (isset($_GET['shopid'])) $shop_id = $_GET['shopid'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['targetid'])) $target_id = $_GET['targetid'];

$url = 'user_login.php?shopid=' . $shop_id . '&type=' . $type . '&targetid=' . $target_id;

include('page_header.php');
include('page_footer.php');
?>

<script type="text/javascript">
    if (getUserInfo() == '') {
        sessionStorage.clear({});
        localStorage.clear({});
        location.href = 'user_login.php';
    }else {
        location.href = 'shipper_order.php';
    }
</script>

