<?php 
global $config, $posClass, $accessObject;
$CSS_ARRAY = [];

// set a common variable
$baseUrl = $config->base_url();

// ensure that the user is logged in
if(!$admin_user->logged_InControlled()) {
    include_once "login.php";
    exit;
}

// client data
$clientData = $posClass->getAllRows("settings", "*", "clientId='{$posClass->clientId}'");
$branchData = $posClass->getAllRows("branches", "*", "id='{$session->branchId}'");
$clientData = $clientData[0];
$branchData = $branchData[0];

// create a new object for the access level
$accessObject->userId = $session->userId;

function connectionLost($message = "Connection lost. Reconnect to view content") {
  return '<div class="offline-placeholder main-body-loader" style="display: none">
          <div class="offline-content text-center">
              <p>'.$message.'</p>
              <button type="button" class="btn cursor btn-warning">Reconnect</button>
              <button type="reset" class="btn btn-danger hidden"><i style="font-size:17px;padding:4px" class="fa fa-times"></i></button>
          </div>
      </div>';
}

function nonWorkingDay($message = "Please note that the Point of Sale is Closed for Today.") {
  return '<div class="offline-placeholder main-body-loader" style="display: true">
          <div class="offline-content text-center">
              <p class="alert alert-warning text-white" style="border-radius:0px">'.$message.'</p>
          </div>
      </div>';
}

function form_loader() {
  return '<div class="form-content-loader" style="display: none;">
              <div class="offline-content text-center">
                  <p><i class="fa fa-spin fa-spinner fa-3x"></i></p>
              </div>
          </div>';
}
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="<?= config_item('site_name'); ?>">
  <title><?= $PAGETITLE; ?> :: <?= config_item('site_name'); ?></title>
  <!-- Extra details for Live View on GitHub Pages -->
  <!-- Canonical SEO -->
  <link rel="canonical" href="<?= $baseUrl ?>" />
  <!--  Social tags      -->
  <meta name="keywords" content="dashboard, bootstrap 4 dashboard, bootstrap 4 design, bootstrap 4 system, bootstrap 4, bootstrap 4 uit kit, bootstrap 4 kit, argon, argon ui kit, <?= config_item('site_name'); ?>, html kit, html css template, web template, bootstrap, bootstrap 4, css3 template, frontend, responsive bootstrap template, bootstrap ui kit, responsive ui kit, argon dashboard">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <!-- Schema.org markup for Google+ -->
  <meta itemprop="name" content="<?= $PAGETITLE; ?> :: <?= config_item('site_name'); ?>">
  <meta itemprop="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <!-- Twitter Card data -->
  <meta name="twitter:card" content="product">
  <meta name="twitter:site" content="@visaminetsolutions">
  <meta name="twitter:title" content="<?= $PAGETITLE; ?> :: <?= config_item('site_name'); ?>">
  <meta name="twitter:description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="twitter:creator" content="@visaminetsolutions">
  <meta name="twitter:image" content="<?= $config->base_url('assets/images/logo.png') ?>">
  <!-- Open Graph data -->
  <meta property="og:title" content="<?= $PAGETITLE; ?> :: <?= config_item('site_name'); ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?= $baseUrl ?>" />
  <meta property="og:image" content="<?= $config->base_url('assets/images/logo.png') ?>" />
  <meta property="og:description" content="Start your development with a Dashboard for Bootstrap 4." />
  <meta property="og:site_name" content="<?= config_item('site_name'); ?>" />
  <!-- Favicon -->
  <link rel="icon" href="<?= $baseUrl ?>assets/img/brand/favicon.png" type="image/png">
  <!-- Fonts -->
  <!-- Icons -->
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link href="<?= $baseUrl ?>assets/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
  <link href="<?= $baseUrl ?>assets/vendor/jquery-steps/jquery.steps.css"  rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/select2/dist/css/select2.min.css" type="text/css">
  <link href="<?= $baseUrl ?>assets/vendor/summernote/summernote-bs4.css" rel="stylesheet" />
  <link rel="company_parameters" _cl='{"_un":"Emmallen Networks","_cl":"<?= $session->clientId ?>","_clb":"<?= $session->branchId ?>","_ud":"<?= $session->userId ?>","_un":"<?= $session->userName; ?>","_hi":"<?= $accessObject->hasAccess('monitoring', 'branches'); ?>"}'>
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/argon.min9f1e.css?v=1.1.0" type="text/css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/custom.css" type="text/css">
</head>
<body>
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="<?= $baseUrl; ?>">
          <img src="<?= $baseUrl ?>assets/img/brand/blue.png" class="navbar-brand-img" alt="...">
        </a>
        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block <?= (in_array($SITEURL[0], ["requests", "point-of-sale"])) ? "acitve" : null; ?>" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['dashboard', 'index'])) ? "active" : null; ?>" href="<?= $baseUrl ?>dashboard">
                <i class="ni ni-shop text-info"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['point-of-sale'])) ? "active" : null; ?>" href="<?= $baseUrl ?>point-of-sale">
                <i class="ni ni-ui-04 text-info"></i>
                <span class="nav-link-text">Point of Sale</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['sales', 'quotes', 'orders'])) ? "active" : null; ?>" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
                <i class="ni ni-single-copy-04 text-pink"></i>
                <span class="nav-link-text">Sales</span>
              </a>
              <div class="collapse <?= (in_array($SITEURL[0], ['sales', 'quotes', 'orders'])) ? "show" : null; ?>" id="navbar-forms">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>sales" class="nav-link">Sales History</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>quotes" class="nav-link">Quotes</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>orders" class="nav-link">Orders</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['inventory', 'product'])) ? "active" : null; ?>" href="<?= $baseUrl ?>inventory">
                <i class="ni ni-ungroup text-orange"></i>
                <span class="nav-link-text">Inventory</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['analytics'])) ? "active" : null; ?>" href="<?= $baseUrl ?>analytics">
                <i class="ni ni-chart-pie-35 text-info"></i>
                <span class="nav-link-text">Analytics</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['branches'])) ? "active" : null; ?>" href="<?= $baseUrl ?>branches">
                <i class="ni ni-archive-2 text-green"></i>
                <span class="nav-link-text">Branches</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['users-login-history', 'users', 'users-login-history'])) ? "active" : null; ?>" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                <i class="ni ni-align-left-2 text-default"></i>
                <span class="nav-link-text">Users</span>
              </a>
              <div class="collapse <?= (in_array($SITEURL[0], ['users', 'users-login-history', 'users-activity-logs'])) ? "show" : null; ?>" id="navbar-tables">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>users" class="nav-link">Users</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>users-login-history" class="nav-link">Login History</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>users-activity-logs" class="nav-link">Activity Logs</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['settings'])) ? "active" : null; ?>" href="<?= $baseUrl ?>settings">
                <i class="ni ni-map-big text-primary"></i>
                <span class="nav-link-text">Settings</span>
              </a>
            </li>
          </ul>
          <!-- Divider -->
          <hr class="my-3">
        </div>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Navbar links -->
          <ul class="navbar-navalign-items-center ml-md-auto">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
            <li class="nav-item d-sm-none">
              <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                <i class="ni ni-zoom-split-in"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ni ni-bell-55"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                <!-- Dropdown header -->
                <div class="px-3 py-3">
                  <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">2</strong> notifications.</h6>
                </div>
                <!-- List group -->
                <div class="list-group list-group-flush">
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="<?= $baseUrl ?>assets/img/theme/team-1.jpg" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                      </div>
                    </div>
                  </a>
                </div>
                <!-- View all -->
                <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ni ni-ungroup"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default dropdown-menu-right">
                <div class="row shortcuts px-4">
                  <a href="<?= $baseUrl ?>point-of-sale" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-yellow">
                      <i class="ni ni-basket"></i>
                    </span>
                    <small>POS</small>
                  </a>
                  <a href="<?= $baseUrl ?>sales" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                      <i class="ni ni-credit-card"></i>
                    </span>
                    <small>Sales</small>
                  </a>
                  <a href="<?= $baseUrl ?>analytics" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                      <i class="ni ni-books"></i>
                    </span>
                    <small>Analytics</small>
                  </a>
                  <a href="<?= $baseUrl ?>branches" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                      <i class="ni ni-pin-3"></i>
                    </span>
                    <small>Branches</small>
                  </a>
                  <a href="<?= $baseUrl ?>inventory" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                      <i class="fa fa-list"></i>
                    </span>
                    <small>Inventory</small>
                  </a>
                  <a href="<?= $baseUrl ?>users" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-gray-dark">
                      <i class="fa fa-users"></i>
                    </span>
                    <small>Users</small>
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center ml-auto ml-md-0">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="<?= $baseUrl ?>assets/img/theme/team-4.jpg">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold"><?= $session->userName; ?></span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <a href="<?= $baseUrl ?>profile" class="dropdown-item">
                  <i class="ni ni-single-02"></i>
                  <span>My profile</span>
                </a>
                <a href="<?= $baseUrl ?>settings" class="dropdown-item">
                  <i class="ni ni-settings-gear-65"></i>
                  <span>Settings</span>
                </a>
                <a href="<?= $baseUrl ?>users-activity-logs" class="dropdown-item">
                  <i class="ni ni-calendar-grid-58"></i>
                  <span>Activity</span>
                </a>
                <a href="<?= $baseUrl ?>support" class="dropdown-item">
                  <i class="ni ni-support-16"></i>
                  <span>Support</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item logout" data-value="logout" href="<?= $config->base_url('login?logout'); ?>">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>