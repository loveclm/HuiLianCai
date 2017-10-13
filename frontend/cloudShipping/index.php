<?php
    $shop_id = 0;
    $type = 0;
    $target_id = 0;

    if(isset($_GET['shopid']))  $shop_id = $_GET['shopid'];
    if(isset($_GET['type']))    $type = $_GET['type'];
    if(isset($_GET['targetid'])) $target_id = $_GET['targetid'];

    $url = 'cloudshop/user_login.php?shopid='.$shop_id.'&type='.$type.'&targetid='.$target_id;
?>
<script type="text/javascript">
    window.location.href='<?= $url ?>';
</script>

