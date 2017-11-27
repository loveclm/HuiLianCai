<?php
$shop_id = 0;
$type = 0;
$target_id = 0;

if (isset($_GET['shopid'])) $shop_id = $_GET['shopid'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['targetid'])) $target_id = $_GET['targetid'];

$url = 'user_login.php';
include('page_header.php');
include('page_footer.php');
?>
<script type="text/javascript">
    $(document).ready(function () {
        if(localStorage.getItem('isLogout')!='1') {
           // if (!getRegisterStatus())
           //     location.href='user_login.php';
           // else
                location.href='home.php?iId=1';
        }else{
            localStorage.removeItem('isLogout');
         //   window.close();
            location.href='user_login.php'
        }
    })
</script>

