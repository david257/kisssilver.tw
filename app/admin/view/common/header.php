{include file="common/header_meta" /}
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

            <?php
            $headerAuthNodes = [];
            if(!empty($adminNodes)) {
            foreach($adminNodes as $nodeid => $node) {
                if(in_array($nodeid, $headerNodes)) {
                    $headerAuthNodes[] = ["title" => $node["title"], "url" => $node['url']];
                }

                if(isset($node["child"]) && !empty($node["child"])) {
                    foreach($node["child"] as $snodeid => $snode) {
                        if(in_array($snodeid, $headerNodes)) {
                            $headerAuthNodes[] = ["title" => $snode["title"], "url" => $snode['url']];
                        }
                    }
                }
            }
            }
            ?>
            <?php if(!empty($headerAuthNodes)) {
                foreach($headerAuthNodes as $k => $v) {
            ?>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{:$v['url']}" class="nav-link">{$v['title']}</a>
            </li>
            <?php } } ?>

            <li class="nav-item d-none d-sm-inline-block">
                <a href="/" target="_blank" class="nav-link">瀏覽前台</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    用戶:{$loginuser}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="{:admin_link('User/change_pass')}" class="DialogForm dropdown-item">
                        修改密碼
                    </a>
                    <a href="{:admin_link('Login/logout')}" class="AjaxTodo dropdown-item">
                        登出
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{:admin_link('Index/index')}">
            <span class="brand-text font-weight-light" style="font-size: 35px;
    color: #fff;
    display: block;
    text-align: center;
    margin: 0 auto;">KISS SILVER</span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2" id="side_menu">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item has-treeview">
                        <a href="{:admin_link('Index/index')}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                控制台
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>

                    <?php
                    if(!empty($adminNodes)) {
                        foreach($adminNodes as $nodeid => $node) {
                            if(isset($node["url"]) && !empty($node['url'])) {
                                $url = $node["url"];
                            } else {
                                $url = "#";
                            }
                    ?>
                    <li class="nav-item has-treeview">
                        <a href="{:$url}" class="nav-link parentnode">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                {:$node['title']}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <?php
                        if(isset($node["child"]) && !empty($node["child"])) {
                        ?>
                        <ul class="nav nav-treeview">
                            <?php
                            foreach($node["child"] as $snodeid => $snode) {
                                if(isset($snode["url"]) && !empty($snode['url'])) {
                                    $url = $snode["url"];
                                } else {
                                    $url = "#";
                                }
                            ?>
                            <li class="nav-item">
                                <a href="{$url}" class="nav-link subnode">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{$snode['title']}</p>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } } ?>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
