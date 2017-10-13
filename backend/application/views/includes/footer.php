<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>The jin hu</b> Cloud Shop System | Version 1.0
    </div>
    <strong>Copyright &copy; 2017-2018 <a href="<?php echo base_url(); ?>">Thejinhu</a>.</strong> All rights reserved.
</footer>

<script src="<?php echo base_url(); ?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>-->
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datetimepicker/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/fastclick/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(); ?>assets/plugins/table2Excel/jquery.table2excel.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>

<script type="text/javascript">
    var baseURL = "<?php echo base_url(); ?>";
    var loginID = "<?php echo $login_id; ?>";

    var windowURL = window.location.href;
    pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
    var x = $('a[href="' + pageURL + '"]');
    x.addClass('active');
    x.parent().addClass('active');
    var y = $('a[href="' + windowURL + '"]');
    y.addClass('active');
    y.parent().addClass('active');

    //Initialize Select2 Elements
    $(".select2").select2();

    //datepicker plugin
    //link
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    $(document).one('ajaxloadstart.page', function (e) {
        autosize.destroy('textarea[class*=autosize]');

        $('.limiterBox,.autosizejs').remove();
        $('.daterangepicker.dropdown-menu,.colorpicker.dropdown-menu,.bootstrap-datetimepicker-widget.dropdown-menu').remove();
    });
    $(document).ready(function () {
         $(window).resize(function () {
            var main_page_width = document.body.clientWidth
                || document.documentElement.clientWidth
                || window.innerWidth;

            if (main_page_width <= 1450)
                $("#main_page_body").addClass("sidebar-collapse");
            else
                $("#main_page_body").removeClass("sidebar-collapse");
        });

    });

    // check activity state each every year ror month
    setInterval( function(){
        //return;
        $.ajax({
           type : 'post',
           url : baseURL + 'cron_controller',
           data : { 'data' : 'test'},
            success: function (data, textStatus, jqXHR) {
                var currentdate = new Date();
                var datetime = "Last Sync: " + currentdate.getDate() + "/"
                    + (currentdate.getMonth()+1)  + "/"
                    + currentdate.getFullYear() + " @ "
                    + currentdate.getHours() + ":"
                    + currentdate.getMinutes() + ":"
                    + currentdate.getSeconds();
                console.log(datetime);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }, 500000);

    // check message status
    setTimeout( chkMessage(), 2000);
    setInterval( chkMessage(), 100000);

    function chkMessage(){
        $.ajax({
            type : 'post',
            url : baseURL + 'cron_controller/chkMessages',
            data : { 'id' : loginID},
            success: function (data, textStatus, jqXHR) {
                //console.log(data);
                if(data == '0'){
                    $('#message_detail').css({'display':'none'});
                }else{
                    $('#message_detail').html(data);
                    $('#message_detail').css({'display':'block'});
                    $('#message_detail').attr('data-original-title', data + ' 新消息');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }
</script>
</body>
</html>