<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
<!--    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">-->
    <!-- summernote -->
    <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">

    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!--    <script-->
<!--            src="https://code.jquery.com/jquery-3.7.1.min.js"-->
<!--            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="-->
<!--            crossorigin="anonymous"></script>-->
<!---->
<!--    <script-->
<!--            src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"-->
<!--            integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY="-->
<!--            crossorigin="anonymous"></script>-->

<!--    <script src="/plugins/jquery/jquery.min.js"></script>-->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <?php if (!Yii::$app->user->isGuest) { ?>
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                       aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="/dist/img/user1-128x128.jpg" alt="User Avatar"
                                     class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="/dist/img/user8-128x128.jpg" alt="User Avatar"
                                     class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="/dist/img/user3-128x128.jpg" alt="User Avatar"
                                     class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i
                                                    class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                       role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php } ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php if (!Yii::$app->user->isGuest) { ?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= \yii\helpers\Url::toRoute(['site/index']) ?>" class="brand-link">
                <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                     style="opacity: .8">
                <span class="brand-text font-weight-light">Склад</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= Yii::$app->user->identity->username ?></a>
                        <span><?=
                            Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                'Вийти',
                                ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm() ?></span>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                               aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-arrow-circle-right"></i>
                                <p>
                                    Реалізація
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['invoice/index', 'action' => 'sale']) ?>"
                                       class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>Накладні</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['invoice/index', 'action' => 'bill']) ?>"
                                       class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>Рахунки</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['invoice/index', 'action' => 'rejected']) ?>"
                                       class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>Відмінені</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-arrow-circle-left"></i>
                                <p>
                                    Закупівлі
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['invoice/index', 'action' => 'income']) ?>"
                                       class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>Накладні</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['invoice/index', 'action' => 'import']) ?>"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Імпорт</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['invoice/index', 'action' => 'office']) ?>"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>На балансі</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['invoice/index', 'action' => 'order']) ?>"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Наступне замовленя</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>
                                    Склад
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('invoice/store') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Товари на складі</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('invoice/check') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Звірка по товарам</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p>
                                    Оплати
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['payment/index']) ?>"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Платежі грн</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute(['payment/index']) ?>"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Платежі SEK</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Сервіс
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('category/index') ?>" class="nav-link">
                                        <i class="fas fa-boxes nav-icon"></i>
                                        <p>Категорії товарів</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('payment-category/index') ?>" class="nav-link">
                                        <i class="fas fa-coins nav-icon"></i>
                                        <p>Категорії платежів</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('product/index') ?>" class="nav-link">
                                        <i class="fas fa-dolly nav-icon"></i>
                                        <p>Товари</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('customer/index') ?>" class="nav-link">
                                        <i class="fas fa-address-card nav-icon"></i>
                                        <p>Контрагенти</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('customers-staff/index') ?>" class="nav-link">
                                        <i class="fas fa-address-card nav-icon"></i>
                                        <p>Співробітники</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= \yii\helpers\Url::toRoute('proposal/index') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Пропозиції</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
    <?php } ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <?php if (!empty($this->params['breadcrumbs'])): ?>
                    <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
                <?php endif ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <?php if (!Yii::$app->user->isGuest) { ?>
    <footer class="main-footer hidden-sm hidden-xs">
        <strong>Copyright &copy; 2023 <a href="https://adminlte.io">HANDYcars</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.2.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <?php } ?>

    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<style>
    .pagination a, .pagination span {
        border: 1px solid #eaeaea;
        display: inline-block;
        text-transform: uppercase;
        text-align: center;
        color: #999;
        padding: 4px 12px;
    }

    /*.pagination li.prev, li.next {*/
    /*    display: inline-block;*/
    /*    border: 1px solid #eaeaea;*/
    /*    color: #999;*/
    /*}*/

    .pagination li.active {
        background-color: #ccc !important;
        border-color: #CACACA;

    }
</style>
<!-- jQuery -->
<!--<script src="/plugins/jquery/jquery.min.js"></script>-->
<!-- jQuery UI 1.11.4 -->
<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/plugins/moment/moment.min.js"></script>
<!--<script src="/plugins/daterangepicker/daterangepicker.js"></script>-->
<!-- Tempusdominus Bootstrap 4 -->
<script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="/dist/js/demo.js"></script>-->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="/dist/js/pages/dashboard.js"></script>-->
<script src="/plugins/select2/js/select2.full.min.js"></script>

<script>//Initialize Select2 Elements
   $('.select2').select2()

   //Initialize Select2 Elements
   $('.select2bs4').select2({
     theme: 'bootstrap4'
   })
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
