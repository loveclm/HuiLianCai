<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            角色列表
        </h1>
    </section>
    <section class="content" style="min-height: 800px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 box-header">
                        <a class="btn btn-primary" href="#" onclick="$('#custom-generate-auth-count-view').show();">
                            添加
                        </a>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <table class="table area-result-view table-bordered table-hover">
                        <thead>
                        <tr style="background-color: lightslategrey;">
                            <th width="">序号</th>
                            <th>用户角色</th>
                            <th width="">功能设置</th>
                            <th width="">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($userRecords)) {
                            $i=0;

                            foreach ($userRecords as $record) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $record->role ?></td>
                                    <div id="permission<?php echo $record->id ?>"
                                         style="display: none"><?php echo isset($record->permission)?$record->permission:''; ?></div>
                                    <td class="text-center">
                                        <a href="#"
                                           onclick="showRoleEdit('<?php echo $record->id; ?>');">
                                            操作设置 &nbsp;
                                        </a>
                                    </td>
                                    <td class="text-center" disabled>
                                        <a href="#" data-userid="<?php echo $record->id; ?>"
                                           onclick="confirmDelete('<?php echo $record->id; ?>')">
                                            删除 &nbsp;
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <div id="custom-confirm-delete-view" class="modal-dialog text-center" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="$('#custom-confirm-delete-view').hide();">
                                    <span aria-hidden="true">×</span></button>
                                <span class="modal-title">提示</span>
                                <div id="roleId" style="display: none;"></div>
                            </div>
                            <div class="modal-body">
                                <label>确定删除？</label><br><br>
                                <a href="#" class="btn btn-default" onclick="$('#custom-confirm-delete-view').hide();">取消</a>
                                <a href="#" class="btn btn-primary" onclick="deleteRole('<?php echo base_url(); ?>');"">确定</a>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>

                    <div id="custom-generate-auth-count-view" class="modal-dialog text-center" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-group form-inline">
                                    <label>&nbsp;&nbsp;*角色名称 &nbsp;: </label>
                                    <input id="rolename" type="text" maxlength="20"/>
                                </div><br>
                                <a href="#" class="btn btn-default" onclick="$('#custom-generate-auth-count-view').hide();">取消</a>
                                <a href="#" class="btn btn-primary" onclick="addRole('<?php echo base_url(); ?>' );">确定</a>
                            </div>
                            <div class="form-group alert-danger" id="alertmsg" style="display: none;"></div>
                        </div>
                        <!-- /.modal-content -->
                    </div>

                    <div id="custom-generate-auth-view" class="modal-dialog text-center" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="$('#custom-generate-auth-view').hide();">
                                    <span aria-hidden="true">×</span></button>
                                <span class="modal-title">功能设置</span>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 form-group" style="text-align: left;">
                                        <div id="treeview-container">
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-default" onclick="$('#custom-generate-auth-view').hide();">取消</a>
                                <a href="#" class="btn btn-primary" onclick="updateRole('<?php echo base_url(); ?>', '<?php echo $role; ?>' );">确定</a>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jstree/logger.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jstree/treeview.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sysrole.js" charset="utf-8"></script>
