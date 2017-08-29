<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            新增景区
        </h1>
    </section>

    <section class="content">
        <input id="custom-base-url" value="<?php echo base_url(); ?>" style="display: none;"/>
        <?php
            if(isset($area)){
                $areaInfo = json_decode($area->info);
                //var_dump(json_encode($areaInfo->position));
            }
        ?>
            <div class="container">
                <div class="row">
                    <div class="col-xs-6 col-sm-4 form-inline">
                        <div class="form-group area-add-view">
                            <label for="exampleInputName2">景区名称:</label>
                            <input type="text" class="form-control" id="areaname" value="<?php echo isset($area) ? $area->name : '';?>" />
                            <input type="text" id="point-list" style="display:none;" value="<?php echo isset($area) ? $area->id : '';?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-4 form-inline">
                        <div class="form-group area-add-view">
                            <label>上传录音：</label>
                            <a class="btn btn-primary" href="#" onclick="uploadAreaAudio()">
                                <span>上传录音</span>
                            </a>
                            <input id="upload-area-audio" type="file" style="display: none"></input>
                            <label id="area-audio-file"><?php echo isset($areaInfo) ? $areaInfo->audio : '';?></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="form-inline">
                        <div class="form-group area-add-view">
                            <label for="exampleInputName2">景区折扣比率:</label>
                            <input type="text" class="form-control" id="arearate" value="<?php echo isset($area) ? $area->discount_rate : '';?>">
                            <label">%</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName2">景区折扣比率:</label>
                        <input id="cityName" type="text" placeholder="输入您要定位的地址" value="<?php echo isset($area) ? ($area->address) : '';?>"/>
                        <input id="area-position" style="display: none;" value="<?php echo isset($area) ? json_encode($areaInfo->position) : '';?>" />
                    </div>
                    <div class="col-sm-7">
                        <div id="custom-map-container" style="height: 600px;"></div>
                    </div>
                    <div class="col-sm-3" style="display:<?php echo $isEdit=='0'?'none':'block'; ?>; border: 1px solid;height: 600px;">

                        <div class="point-list-view">
                            <div class="form-group col-sm-6">
                                <input id="upload-overlay" type="file" style="display: none;"/>
                                <button class="btn btn-primary" type="button" onclick="uploadOverlay();">上传覆盖图</button>
                                <input id="area-overlay" value="<?php echo isset($area) ? ($areaInfo->overay) : '';?>" style="display: none;"/>
                            </div>
                            <div class="form-group col-sm-6">
                                <button class="btn btn-primary" type="button" onclick="showAddPoint();">标记景点</button>
                            </div>
                            <div class="form-group">
                                <div id="pointList">

                                </div>
                            </div>
                        </div>

                        <div class="point-add-view" style="display: none;">
                            <input id="point-view-index" style="display: none;" value="0"/>
                            <div class="form-group">
                                <label>景点名称：</label>
                                <input type="text" class="form-control" id="pointname">
                            </div>
                            <div class="form-group">
                                <label>景点简述：</label>
                                <input type="text" class="form-control" id="pointdescription">
                            </div>

                            <div class="form-group">
                                <input id="upload-point-image" type="file" style="display: none;">
                                <input id="pointimage" value="" style="display: none;"/>
                                <label>上传图片：</label>
                                <a class="btn btn-primary" onclick="uploadPointImage();">
                                    <span>上传图片</span>
                                </a>
                            </div>

                            <div class="form-group">
                                <img id="point-item-image" style="height: 150px;width: 100%;" src=""/>
                            </div>

                            <div class="form-group">
                                <label>上传录音：</label>
                                <a class="btn btn-primary" onclick="uploadPointAudio();">
                                    <span>上传录音</span>
                                </a>
                                <input id="upload-point-audio" type="file" style="display: none;">
                                <label id="pointaudio" value="" style="display: none"></label>
                                <label id="pointaudio_view" value="" style=""></label>
                            </div>

                            <div class="form-group">
                                <label>景点价格：</label>
                                <input type="text" class="form-control" id="pointprice">
                            </div>

                            <div class="form-group">
                                <input type="checkbox" id="pointfree"/> 试听
                            </div>

                            <div class="form-group">
                                <button type="button" onclick="addPoint(0);">取消</button>
                                <button type="button" onclick="addPoint(1);">完成</button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <input type="button" class="btn btn-primary"
                   onclick="addTouristArea('<?php echo base_url(); ?>', <?php echo isset($area)? $area->id: 0;?>);"
                   value="保存" />

    </section>
</div>

<!-- Baidu Map JS-->
<script src="https://webapi.amap.com/maps?v=1.3&key=0250860ccb5953fa5d655e8acf40ebb7&plugin=AMap.PolyEditor,AMap.MouseTool"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/map.js" charset="utf-8"></script>