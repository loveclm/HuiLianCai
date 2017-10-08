<!DOCTYPE html>
<html lang="en">

<?php include('page_header.php'); ?>

<body>
<div class="page-wrapper">
    <div class="page-content-wrapper">
        <div class="page-content" style="">
            <div id="advertise_header" class="carousel slide vertical nicer" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#advertise_header" data-slide-to="0" class="active"></li>
                    <li data-target="#advertise_header" data-slide-to="1"></li>
                    <li data-target="#advertise_header" data-slide-to="2"></li>
                    <li data-target="#advertise_header" data-slide-to="3"></li>
                    <li data-target="#advertise_header" data-slide-to="4"></li>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <div class="carousel_item">
                            <img src="assets/images/tmp/u1.png">
                        </div>
                    </div>
                    <div class="item">
                        <div class="carousel_item">
                            <img src="assets/images/tmp/u2.png">
                        </div>
                    </div>
                    <div class="item">
                        <div class="carousel_item">
                            <img src="assets/images/tmp/u3.jpg">
                        </div>
                    </div>
                    <div class="item">
                        <div class="carousel_item">
                            <img src="assets/images/tmp/u1.png">
                        </div>
                    </div>
                    <div class="item">
                        <div class="carousel_item">
                            <img src="assets/images/tmp/u2.png">
                        </div>
                    </div>
                </div>
                <!-- Left and right controls -->

                <a class="left carousel-control" href="#advertise_header" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#advertise_header" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <div id="horizontal_menu_bar">
            </div>
            <div id="detail_menu">
                <div id="detail_menu_content"></div>
                <div id="detail_menu_mask"></div>
            </div>
            <div class="product_container" style="overflow-y: scroll;">

                <div class="order order_using">
                    <div class="order_header">
                        <h5>距离拼团結束还有 15 : 23 : 30</h5>
                    </div>
                    <div class="order_body" onclick="showOrderDetailInfo(0)">
                        <img src="assets/images/logo.png">
                        <div>
                            <h5 class="order_name">首都拼团結束</h5>
                            <h5 class="order_info">
                                <span>1g*12/箱</span>
                                <span class="order_right">500箱起拼</span>
                            </h5>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-success" role="progressbar"
                                     aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                                    <span class="sr-only"> 40% Complete (success) </span>
                                </div>
                            </div>
                            <h5>
                                <span class="order_old_price "><strike>¥30.00</strike></span>
                                <span class="order_right">¥10.00</span>
                            </h5>
                        </div>
                    </div>
                    <div class="order_footer">
                        <div onclick="purchase_again_Order('3')">
                            <h5>重新购买</h5>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

</div>
</body>

<?php include('page_footer.php'); ?>

<script type="text/javascript" src="assets/js/main.js"></script>

</html>