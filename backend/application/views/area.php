<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <a href="#" onclick="test_api();">首页</a>
        </h1>
    </section>

    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <table class="table table-bordered area-result-view">
                    <thead>
                        <tr style="background-color: lightslategrey;">
                            <th>排名</th>
                            <th width="100">供货商账号</th>
                            <th width="150">供货商名称</th>
                            <th>销量</th>
                            <th width="100">销售金额</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $areaCount = count($areaList);

                            for($i = 0; $i < $areaCount; $i++){
                                $area = $areaList[$i];
                                $points = json_decode($areaList[$i]->point_list);
                                $pointCount = count($points);
                                ?>
                                <tr>
                                    <td><?php echo $area->name;?></td>
                                    <td><?php echo $pointCount;?></td>
                                    <td><?php echo $area->price;?></td>
                                    <td><?php echo $area->address;?></td>
                                    <td><?php echo $area->status == 1 ? '已上架': '未上架'; ?></td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <div id="custom-confirm-delete-view" style="display:none;">
                        <p>
                            是否要删除此景区？
                        </p>
                        <div class="form-group">
                            <button onclick="deleteArea('<?php echo base_url(); ?>', 0);">取消</button>
                            <button onclick="deleteArea('<?php echo base_url(); ?>', 1);">确定</button>
                        </div>

                    </div>
                    <div id="custom-confirm-deploy-view" style="display:none;">
                        <p>
                            是否要上架此景区？
                        </p>
                        <div class="form-group">
                            <button onclick="deployArea('<?php echo base_url(); ?>', 0);">取消</button>
                            <button onclick="deployArea('<?php echo base_url(); ?>', 1);">确定</button>
                            <input id="current-areaid" style="display: none;"/>
                            <input id="current-areastatus" style="display: none;"/>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/area.js" charset="utf-8"></script>