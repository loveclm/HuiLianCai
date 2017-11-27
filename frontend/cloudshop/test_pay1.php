<html>
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <button onclick="onPay()"><h3>pay test</h3></button>
</body>
<script type="text/javascript" src="assets/js/plugins/jquery.min.js"></script>

<script type="text/javascript">

    function onPay() {
//	alert('111');
        $.ajax({
            url: "wxpay_wap.php",
            type: "post",
            data: {
                'userid' : '238239859822729',
                'title':'aaa',
                'money': 0.01
            },
            success: function (result) {
                console.log(result);
                if (result != '')
                    alert(result);
                else
                    alert("payment failed");
            },
            fail: function(result){
        	alert(result);
            }
        });
    }

</script>
</html>