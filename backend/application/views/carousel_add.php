<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row form-inline">
                <div class="form-group">
                    <label> *类型 : </label>
                </div>
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input type="radio" name="radio_caro_type" value="1" checked>
                            广告
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="radio_caro_type" value="2">
                            单品活动
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="radio_caro_type" value="3">
                            餐装活动
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="radio_caro_type" value="4">
                            供货商
                        </label>
                    </div>
                </div>
                <div class="row form-inline">
                </div>
                <div class="form-group" id="select_active_group" style="display: none">
                    <label>*活动名称 :</label>
                    <div class="input-group margin">
                        <select id="select_active_list" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="1">Alabama</option>
                            <option value="2">Alaska</option>
                            <option value="3">California</option>
                            <option value="4">Delaware</option>
                            <option value="5">Tennessee</option>
                            <option value="6">Texas</option>
                            <option value="7">Washington</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row form-inline">
            </div>
            <div class="row form-inline">
                <div class="form-group">
                    <label> *排序 :</label>
                    <div class="input-group margin">
                        <input type="text" id="sort_number" class="form-control input-text"
                               value="0"
                               style="margin: 0 ; padding: 0px 20px;"/>
                        <span class="input-group-addon" style="margin: 0;padding: 0px 10px;">
                            <a href="#" onclick="spinnerChange(1);"><i class="fa fa-caret-up"></i></a><br>
                            <a href="#" onclick="spinnerChange(0);"><i class="fa fa-caret-down"></i></a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row form-inline">
                <div class="form-group">
                    <a class="btn btn-primary form-control" href="<?php echo base_url() . 'carousel_upload'; ?>">
                        <span>*上传图片并裁剪</span>
                    </a>
                </div>
            </div>
            <div class="row text-center">
                <div id="image_content" class="form-group">
                    <img src="<?php echo isset($imagefile) ? $imagefile : 'assets/images/logo.png'; ?>" alt="user image"
                         class="online">
                </div>
                <div class="form-group">
                    <span id="image_filename"><?php
                        $fname = isset($imagefile) ? explode('/', $imagefile) : '';
                        echo isset($fname[count($fname)-1]) ? $fname[count($fname)-1] : 'logo.png';
                        ?>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 form-inline">
                    <a class="btn btn-default form-control" href="<?php echo base_url(); ?>carousel">
                        <span>取消</span>
                    </a>
                    <a class="btn btn-primary form-control" href="#" id="carousel_add_submit">
                        <span>提交</span>
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/carousel.js" charset="utf-8"></script>