<html>
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <button style="margin-top: 50px; padding: 10px;" onclick="OnPay()"><h3>pay test</h3></button>
</body>
<script type="text/javascript" src="assets/js/plugins/jquery.min.js"></script>
<!--<script type="text/javascript" src="jquery.min.js"></script>-->
<script type="text/javascript">

    function OnPay() {
        $.ajax({
            url: "wxh5.php",
            type: "POST",
            data: {
                'id' : '238239859822729',
                'cost': '0.01'
            },
            success: function (result) {
                console.log(result);
                if (result != '')
                    alert(result);
                else
                    alert("payment failed");
            }
        });
    }

</script>
</html>