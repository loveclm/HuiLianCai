<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/croppie/croppie.css">

<div class="content-wrapper" style="min-height: 100%">
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
        <input id="ret_Url" style="display: none;" value="<?php echo isset($ret_Url) ? $ret_Url : ''; ?>"/>
    </section>
    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row form-inline">
                <div class="form-group">
                    <span>图片裁剪<br></span>
                </div>
            </div>
            <div class="row form-inline text-center">
                <div class="form-group">
                    <div id="upload-origin" style="width:100%;"></div>
                </div>
            </div>
            <div class="row form-inline">
                <div class="form-group">
                    <span>预览框<br></span>
                </div>
            </div>
            <div class="row form-inline text-center">
                <div class="form-group" style="">
                    <div id="upload-destination"
                         style="background:#a0a0a0;width:350px;padding:1px;height:200px;margin-top:10pxborder:1px solid darkgray;"></div>
                </div>
                <div class="form-group" style="padding-top:10px;">
                    <button class="btn btn-primary form-control" onclick="$('#upload').click();">选择图片</button>
                    <input type="file" id="upload" style="display:none;"/>
                    <br><br>
                    <button class="btn btn-primary form-control" id="upload-complete">确定</button>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="assets/plugins/croppie/croppie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/upload_img.js" charset="utf-8"></script>