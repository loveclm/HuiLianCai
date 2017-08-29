<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- FontAwesome 4.3.0 -->
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons 2.0.0 -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet"
          type="text/css"/>

    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/dist/css/custom.css" rel="stylesheet" type="text/css"/>

    <style>
        .error {
            color: red;
            font-weight: normal;
        }
    </style>
    <!-- jQuery 2.1.4 -->

    <script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.4.min.js"></script>
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url(); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>CI</b>AS</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>惠联采运营</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="user-image"
                                 alt="User Image"/>
                            <span class="hidden-xs"><?php echo $name; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="img-circle"
                                     alt="User Image"/>

                                <p>
                                    <?php echo $name; ?>
                                    <small><?php echo $role_text; ?></small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo base_url(); ?>loadChangePass"
                                       class="btn btn-default btn-flat"><i class="fa fa-key"></i>修改密码</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat"><i
                                            class="fa fa-sign-out"></i>登出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <?php
        $menu_acc = isset($menu_access) ? json_decode($menu_access) : '';
        ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header"></li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_10 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>">
                        <i class="fa fa-dashboard"></i> <span>首页</span></i>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_20 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>">
                        <i class="fa fa-plane"></i>
                        <span>轮播图管理</span>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_30 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>">
                        <i class="fa fa-ticket"></i>
                        <span>用户管理</span>
                    </a>
                </li>
                <?php
                if ($role == ROLE_ADMIN || $role == ROLE_MANAGER) {
                    ?>
                    <li class="treeview">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-laptop"></i>
                            <span>商品模板</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li style="<?php echo(($menu_acc != '') ? ($menu_acc->p_80 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-users"></i>
                                    商品模板
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    分类管理
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    品牌管理
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    单位管理
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview"
                        style="<?php echo(($menu_acc != '') ? ($menu_acc->p_40 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-upload"></i>
                            <span>商品管理</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-laptop"></i>
                            <span>餐装活动</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li style="<?php echo(($menu_acc != '') ? ($menu_acc->p_80 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-users"></i>
                                    单品活动
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    套装活动
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview"
                        style="<?php echo(($menu_acc != '') ? ($menu_acc->p_61 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-upload"></i>
                            <span>订单管理</span>
                        </a>
                    </li>
                    <li class="treeview"
                        style="<?php echo(($menu_acc != '') ? ($menu_acc->p_70 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-upload"></i>
                            <span>提现管理</span>
                        </a>
                    </li>
                    <li class="treeview"
                        style="<?php echo(($menu_acc != '') ? ($menu_acc->p_70 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-upload"></i>
                            <span>交易明细</span>
                        </a>
                    </li>
                    <li class="treeview"
                        style="<?php echo(($menu_acc != '') ? ($menu_acc->p_70 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-upload"></i>
                            <span>优惠券管理</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-laptop"></i>
                            <span>销售统计</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li style="<?php echo(($menu_acc != '') ? ($menu_acc->p_80 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-users"></i>
                                    配送员统计
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    商品销量统计
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    便利店统计
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    供货商统计
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    品牌统计
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    分类统计
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    销售业绩
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-laptop"></i>
                            <span>推荐人统计</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li style="<?php echo(($menu_acc != '') ? ($menu_acc->p_80 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-users"></i>
                                    供货商
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    终端便利店
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview"
                        style="<?php echo(($menu_acc != '') ? ($menu_acc->p_70 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-upload"></i>
                            <span>消息管理</span>
                        </a>
                    </li>

                    <?php
                }
                if ($role == ROLE_ADMIN) {
                    ?>
                    <li class="treeview">
                        <a href="<?php echo base_url(); ?>">
                            <i class="fa fa-laptop"></i>
                            <span>系统管理</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li style="<?php echo(($menu_acc != '') ? ($menu_acc->p_80 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-users"></i>
                                    运营人员管理
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    角色管理
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>">
                                    <i class="fa fa-circle-o"></i>
                                    修改密码
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>