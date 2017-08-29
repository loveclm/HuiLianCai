<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            新增旅游线路
        </h1>
    </section>

    <section class="content">
        <div class="container">
            <div class="row custom-info-row">
                <label class="col-sm-2">旅游线路名称:</label>
                <input type="text" class="col-sm-4" id="coursename" value="<?php echo isset($course) ? $course->name : '';?>" />
                <div id="custom-error-coursename" class="custom-error col-sm-4" style="display: none;">不超过10个字符</div>
            </div>
            <div class="row custom-info-row">
                <label class="col-sm-2">旅游线路折扣比率:</label>
                <input style="text-align: right;" type="text" class="col-sm-1" id="courserate" value="<?php echo isset($course) ? $course->discount_rate : '';?>">
                <label>%</label>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <label>选择景区：</label>
                </div>
                <div class="col-sm-10 custom-course-itemlist-view">
                    <div class="col-sm-5" style="height: 100%; padding: 10px;">
                        <div class="area-list-view">
                            <input id="course-search" placeholder="搜索景点"/><input type="button" value="find" onclick="findAreaInList('<?php echo base_url(); ?>');"/>
                            <div class="form-group">
                                <ul id="courseList">
                                    <?php
                                    $areaCount = count($areaList);
                                    for($i = 0; $i < $areaCount; $i++) {
                                        $area = $areaList[$i];
                                        ?>
                                        <li class="custom-areaitem" id="areaitem-<?php echo $area->id;?>" onclick="selectCourse(<?php echo $area->id;?>);">
                                            <div id="areatitle-<?php echo $area->id;?>"><?php echo $area->name;?></div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="custom-course-itemlist-control">
                            <div class="form-group">
                                <input type="button" value="添加>>" onclick="addAreaToCourse();"/>
                            </div>
                            <div class="form-group">
                                <input type="button" value="<<删除" onclick="removeAreaFromCourse();"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5" style="height: 100%; padding: 10px;">
                        <div class="course-item-view">

                            <ul id="courseItems">
                                <?php
                                if(isset($course)){
                                    $itemList = json_decode($course->point_list);
                                    $itemCount = count($itemList);
                                    for($i = 0; $i < $itemCount; $i++) {
                                        $item = $itemList[$i];

                                        ?>
                                        <li class="custom-courseitem" data-id="<?php echo $item->id;?>" onclick="selectedCourseItem(this);">
                                            <div><?php echo $item->name;?></div>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-offset-2 custom-course-control-view">
                    <input type="button" class="btn btn-primary" onclick="cancel('<?php echo base_url(); ?>');" value="取消" />
                    <input type="button" class="btn btn-primary" onclick="processCourse('<?php echo base_url(); ?>' , '<?php echo isset($course)? $course->id: 0;?>');" value="确认" />
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Course Management JS-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/course.js" charset="utf-8"></script>