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
                <form role="form" id="addProvider" action="<?php echo base_url() ?>provider_add" method="post">
                    <div class="row form-inline">
                        <label> *供货商账号 : </label>
                        <div class="input-group margin">
                            <input name="userid" type="text" id="userid" class="form-control"
                                   value="<?php echo isset($provider) ? $provider->userid : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/ disabled>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *初始密码 : </label>

                        <div class="input-group margin">
                            <input name="password" type="text" id="password" class="form-control"
                                   value="<?php echo isset($provider) ? $provider->orderID : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/ disabled>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *供货商名称 : </label>

                        <div class="input-group margin">
                            <input name="provider_name" type="text" id="provider_name" class="form-control"
                                   value="<?php echo isset($provider) ? $provider->username : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/ disabled>
                        </div>
                    </div>

                    <!-- ////////////////////Address Part  -->
                    <div id="tip" class="row form-inline">
                        <label> *所属县区 : </label>

                        <div class="input-group margin">
                            <?php
                            $address = isset($provider->address) ? $provider->address : '';
                            $addrs = explode(',', $address);
                            ?>
                            <select id='province' onchange='search(this)' disabled></select>
                            <select id='city' onchange='search(this)' disabled></select>
                            <select id='district' onchange='search(this)' disabled></select>
                            <select id='street' onchange='setCenter(this)' style="display: none;"></select>

                            <input name="provinceName" id="provinceName" style="display: none;"
                                   value="<?php echo $address != '' ? ($addrs[0]) : ''; ?>">
                            <input name="cityName" id="cityName" style="display: none;"
                                   value="<?php echo $address != '' ? ($addrs[1]) : ''; ?>">
                            <input name="districtName" id="districtName" style="display: none;"
                                   value="<?php echo $address != '' ? ($addrs[2]) : ''; ?>">
                            <input name="address_district" id="address_district"
                                   value="<?php $address != '' ? $addrs[0] . $addrs[1] . $addrs[2] : ''; ?>"
                                   style="display: none;">
                        </div>
                    </div>
                    <!-- ////////////////////   address-end             -->

                    <div class="row form-inline">
                        <label> *详细地址 : </label>

                        <div class="input-group margin">
                            <input name="address_detail" type="text" id="detail_address" class="form-control"
                                   value="<?php echo $address != '' ? ($addrs[3]) : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/ disabled>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *联系人 : </label>

                        <div class="input-group margin">
                            <input name="contact_name" type="text" id="contact_name" class="form-control"
                                   value="<?php echo isset($provider) ? $provider->contact_name : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/ disabled>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *联系电话 : </label>

                        <div class="input-group margin">
                            <input name="contact_phone" type="text" id="contact_phone" class="form-control"
                                   value="<?php echo isset($provider) ? $provider->contact_phone : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/ disabled>
                        </div>
                    </div>
                    <?php
                    $more_data = json_decode(isset($provider) ? $provider->more_data : null);
                    ?>
                    <div class="row form-inline">
                        <label> *公司简介 : </label>

                        <div class="input-group margin">
                        <textarea name="content" class="form-control" rows="3" placeholder=""
                                  style="margin: 0px -1px 0px 0px; min-width: 400px; max-height: 150px; max-width:400px; " disabled><?php echo isset($more_data) ? $more_data->content : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *公司LOGO : </label>

                        <div id="company_logo_content" class="input-group margin">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $company_logo = isset($more_data) ? json_decode($more_data->logo) : ['', 'assets/images/picture.png'];
                                ?>
                                <img id="company_logo_image" src="<?= base_url() . $company_logo[1]; ?>"
                                     alt="user image" class="online" style="height: 130px; width:180px; padding: 20px; padding-bottom:2px;"><br>
                                <input id="upload_company_logo" type="file" style="display: none"/>
                                <input name="logo" id="company_logo_src" type="text" style="display: none"
                                       value='<?= json_encode($company_logo); ?>'>
                                <span id="company_logo_filename"><?= $company_logo[0] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *营业执照编号 : </label>

                        <div class="input-group margin">
                            <input name="cert_no" type="text" id="cert_no" class="form-control"
                                   value="<?= isset($more_data) ? $more_data->cert_no : ''; ?>"
                                   style="margin: 0 ; padding: 0px 20px;"/ disabled>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *营业执照 : </label>
                        <div id="company_cert_content" class="input-group margin">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $company_cert = isset($more_data) ? json_decode($more_data->cert) : ['', 'assets/images/picture.png'];
                                ?>
                                <img id="company_cert_image" src="<?= base_url() . $company_cert[1]; ?>"
                                     alt="user image" class="online" style="height: 200px; width:400px; padding: 20px; padding-bottom:2px;"><br>
                                <input id="upload_company_cert" type="file" style="display: none"/>
                                <input name="cert" id="company_cert_src" type="text" style="display: none"
                                       value='<?= json_encode($company_cert); ?>'>
                                <span id="company_cert_filename"><?= $company_cert[0] ?></span>
                            </div>
                       </div>
                    </div>
                    <div class="row form-inline">
                        <label> *代理品牌LOGO : </label>

                        <div id="company_brand_content" class="input-group margin" style="width: 85%; left:80px;">
                            <div class="form-group text-center" style="padding: 0px 20px;">
                                <?php
                                $company_brand_list = isset($more_data) ? json_decode($more_data->brand) : [];
                                $i = 0;
                                foreach ($company_brand_list as $company_brand) {
                                    $i++;
                                    ?>
                                    <div class="company_brand" style="float: left;">
                                        <img id="<?= 'company_brand' . $i . '_image' ?>" src="<?= base_url() . $company_brand[1]; ?>"
                                             onclick="<?= '$(\'#upload_company_brand' . $i . '\').click();'; ?>"
                                             alt="user image" class="online" style="height: 130px; width:180px; padding: 20px; padding-bottom:2px;"><br>
                                        <input id="<?= 'upload_company_brand' . $i ?>" class="upload_company_brand"
                                               type="file" style="display: none"/>
                                        <input name="<?= 'brand' . $i; ?>" id="<?= 'company_brand' . $i . '_src' ?>"
                                               type="text" style="display: none"
                                               value='<?= json_encode($company_brand); ?>'>
                                        <span id="<?= 'company_brand' . $i . '_filename' ?>"><?= $company_brand[0] ?></span>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <input name="brand_count" id="brand_count" value="<?= count($company_brand_list); ?>"
                                   style="display: none">
                            <input id="upload_company_brand" type="file" style="display: none"/>
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> *平台佣金 : </label>

                        <div class="input-group margin">
                            <span> 每笔订单收取供货商费用的百分比 </span>
                            <input name="percent" type="text" id="percent"
                                   value="<?= isset($provider->ratio) ? number_format((float)$provider->ratio*100,2,'.','') : 0; ?>"
                                   style="margin: 0px 10px ; padding: 0px 10px; width: 80px; text-align: right;"/ disabled>%
                        </div>
                    </div>
                    <div class="row form-inline">
                        <label> 推荐业务员 : </label>
                        <div class="form-group margin">
                            <select name="saleman" class="form-control" id="searchType"  disabled>
                                <?php
                                if (count($salemans) > 0) {
                                    $i = 0;
                                    foreach ($salemans as $item) {
                                        $i++;
                                        $select_state = 0;
                                        if (isset($more_data))
                                            if ($item->id == $provider->saleman) $select_state = 1;

                                        if ($select_state == 1) {
                                            ?>
                                            <option value="<?php echo $item->id; ?>" selected><?= $item->username; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?php echo $item->id; ?>"><?= $item->username; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-xs-12 col-sm-12 form-inline">
                                <a class="btn btn-default form-control" href="<?php echo base_url(); ?><?= $shop_manager_number == '' ? 'provider' : 'home';?>">
                                    <span>返回</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3 col-md-offset-7" style="position: absolute; top: 125px">
            <?php
            $this->load->helper('form');
            $error = $this->session->flashdata('error');
            if ($error) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php } ?>
            <?php
            $success = $this->session->flashdata('success');
            if ($success) {
                ?>
                <div class="alert alert-success alert-dismissable" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <input type="text" value="<?php echo $this->session->flashdata('success'); ?>"
                           id="success_message" style="display:none;">
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/user_manage/provider.js" charset="utf-8"></script>
<script
        src="http://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool,AMap.DistrictSearch"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/addressSupport.js" charset="utf-8"></script>