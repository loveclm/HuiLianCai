<div class="content-wrapper" style="min-height: 100%">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <span class="head-selected">后台管理中心</span> / <?php echo $pageTitle ?>
        </h1>
    </section>
    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div>
                <div class="row form-inline">
                    <label> 类型 : </label>
                    <div class="input-group margin">
                        <?php
                        $type = '';
                        if(isset($shop)) {
                            switch (intval($shop->type)) {
                                case 1:
                                    $type .= '便利店'; break;
                                case 2:
                                    $type .= '中型超市'; break;
                                case 3:
                                    $type .= '餐饮店'; break;
                                case 4:
                                    $type .= '其他业态'; break;
                            }
                        }
                        ?>
                        <label style="text-align: left"><?php echo isset($shop) ? $type : ''; ?></label>
                    </div>
                </div>
                <div class="row form-inline">
                    <label> 终端便利店账号 : </label>

                    <div class="input-group margin">
                        <label style="text-align: left"><?php echo isset($shop) ? $shop->userid : ''; ?></label>
                    </div>
                </div>
                <?php
                $more_data = json_decode(isset($shop) ? $shop->more_data : null);
                ?>
                <div class="row form-inline">
                    <label> 头像 : </label>

                    <div id="logo" class="input-group margin">
                        <div class="form-group text-center" style="padding: 0px 20px;">
                            <?php
                            $logo = isset($more_data) ? $more_data->logo : 'assets/images/user_logo.png';
                            ?>
                            <img id="logo_image" src="<?= base_url() . $logo; ?>"
                                 alt="user image" class="online" style="height: 50px; width:50px; border-radius: 50%;"><br>
                        </div>
                    </div>
                </div>
                <div class="row form-inline">
                    <label> 终端便利店 : </label>

                    <div class="input-group margin">
                        <label style="text-align: left"><?php echo isset($shop) ? $shop->username : ''; ?></label>
                    </div>
                </div>

                <div class="row form-inline">
                    <label> 地址 : </label>

                    <div class="input-group margin">
                        <label style="text-align: left; width: 100%;"><?php echo isset($shop) ? $shop->address : ''; ?></label>
                    </div>
                </div>
                <div class="row form-inline">
                    <label> 联系人 : </label>

                    <div class="input-group margin">
                        <label style="text-align: left"><?php echo isset($shop) ? $shop->contact_name : ''; ?></label>
                    </div>
                </div>
                <div class="row form-inline">
                    <label> *联系电话 : </label>

                    <div class="input-group margin">
                        <label style="text-align: left"><?php echo isset($shop) ? $shop->contact_phone : ''; ?></label>
                    </div>
                </div>
                <div class="row form-inline">
                    <label> 推荐人手机号 : </label>

                    <div class="input-group margin">
                        <label style="text-align: left"><?php echo isset($shop) ? $shop->saleman_mobile : ''; ?></label>
                    </div>
                </div>
                <div class="row form-inline">
                    <label> 积分 : </label>

                    <div class="input-group margin">
                        <label style="text-align: left"><?php echo isset($shop) ? $shop->integral : '0'; ?></label>
                    </div>
                </div>
                <div class="row form-inline">
                    <label> 营业执照相片 : </label>
                </div>
                <div class="row form-inline">
                    <div id="company_cert_content" class="input-group margin" style="left:100px;">
                        <div class="form-group text-center" style="padding: 0px 20px;">
                            <img id="company_cert_image" src="<?= isset($more_data) ? base_url() . $more_data->cert :  base_url() .'assets/images/picture.png'; ?>"
                                 alt="user image" class="online" style="height: 180px; width:300px; padding: 20px; padding-bottom:2px;"><br>
                        </div>
                   </div>
                </div>
                <div class="row form-inline">
                    <label> 注册时间 : </label>
                    <div class="input-group margin">
                        <label style="text-align: left"><?php echo isset($shop) ? $shop->created_time : ''; ?></label>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/user_manage/shop.js" charset="utf-8"></script>
