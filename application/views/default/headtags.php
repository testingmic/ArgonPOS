<?php 
// get some global variables
global $config, $posClass, $accessObject;

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
$userData = $posClass->getAllRows("users", "*", "user_id='{$session->userId}'");
$clientData = $clientData[0];
$branchData = $branchData[0];
$userData = $userData[0];

// notification loaders
$notify = load_class('Notifications', 'controllers', $clientData->id);
$Notification = $notify->availableNotification();
$notify->accountNotification();

$storeTheme = (Object) json_decode($clientData->theme_color);
$setupInfo = (Object) json_decode($clientData->setup_info);

// set some variables
$clientData->bg_color = $storeTheme->bg_colors;
$clientData->bg_color_code = $storeTheme->bg_color_code;
$clientData->bg_color_light = $storeTheme->bg_color_light;
$clientData->btn_outline = $storeTheme->btn_outline;

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

function form_loader() {
  return '<div class="form-content-loader" style="display: none;">
        <div class="offline-content text-center">
            <p><i class="fa fa-spin fa-spinner fa-3x"></i></p>
        </div>
    </div>';
}

// filters available
if($setupInfo->type == "alpha") {
    // alpha account filters
    $filterPeriod = [
        "today" => 'Today',
        "this-week" => "This Week",
        "last-30-days" => "Last 30 Days",
        "last-month" => "Last Month (".date("F", strtotime("-1 month")).")",
        "this-month" => "This Month (".date("F").")",
        "same-month-last-year" => "Same Month Last Year",
        "this-year" => "This Year (January - December ".date("Y").")",
        "all-time" => "All Time (".date("jS M Y", strtotime($setupInfo->setup_date))." - till date)",
    ];
} else {
    $filterPeriod = [
        "today" => 'Today',
        "this-week" => "This Week",
        "this-month" => "This Month",
        "this-year" => "This Year"
    ];
    $filterPeriod = [
        "today" => 'Today',
        "this-week" => "This Week",
        "last-30-days" => "Last 30 Days",
        "last-month" => "Last Month (".date("F", strtotime("-1 month")).")",
        "this-month" => "This Month (".date("F").")",
        "same-month-last-year" => "Same Month Last Year",
        "this-year" => "This Year (January - December ".date("Y").")",
        "all-time" => "All Time (".date("jS M Y", strtotime($setupInfo->setup_date))." - till date)",
    ];
}

(!confirm_url_id(0, 'customer-detail')) ? $session->unset_userdata("customerBranchId") : null;

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
  <link rel="canonical" href="<?= $baseUrl ?>" />
  <meta name="keywords" content="">
  <meta itemprop="name" content="<?= $PAGETITLE; ?> :: <?= config_item('site_name'); ?>">
  <link rel="prc" _cl='{"_un":"<?= $clientData->client_name ?>","_cl":"<?= $session->clientId ?>","_clb":"<?= $session->branchId ?>","_ud":"<?= $session->userId ?>","_un":"<?= $session->userName; ?>","_hi":"<?= $accessObject->hasAccess('monitoring', 'branches'); ?>","cur":"<?= $clientData->default_currency ?>","prt":"<?= $clientData->print_receipt ?>","_clbn":"<?= $branchData->branch_name ?>"}'>
  <meta itemprop="description" content="<?= config_item('site_name') ?> - Best Point of Sale Application to ease the stress of managing your stores.">
  <meta name="twitter:card" content="product">
  <meta name="twitter:site" content="@argonpos">
  <meta name="twitter:title" content="<?= $PAGETITLE; ?> :: <?= config_item('site_name'); ?>">
  <meta name="twitter:creator" content="@argonpos">
  <meta name="twitter:image" content="<?= $config->base_url('assets/images/logo.png') ?>">
  <meta property="og:title" content="<?= $PAGETITLE; ?> :: <?= config_item('site_name'); ?>" />
  <meta property="og:url" content="<?= $baseUrl ?>" />
  <meta property="og:image" content="<?= $config->base_url('assets/images/logo.png') ?>" />
  <meta property="og:site_name" content="<?= config_item('site_name'); ?>" />
  <link rel="icon" href="<?= $baseUrl ?>assets/img/brand/favicon.png" type="image/png">
  <link href="<?= $baseUrl ?>assets/css/datepicker.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link href="<?= $baseUrl ?>assets/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
  <link href="<?= $baseUrl ?>assets/vendor/jquery-steps/jquery.steps.css"  rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/select2/dist/css/select2.min.css" type="text/css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/argon.min9f1e.css?v=1.1.0" type="text/css">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/custom.css" type="text/css">
  <style>
    <?php if(confirm_url_id(0, 'point-of-sale')) { ?>
    .wizard > .steps .current a,
    .wizard > .steps .current a:hover,
    .wizard > .steps .current a:active
    {
        background: <?= $clientData->bg_color_code; ?>;
        color: #fff;
        cursor: default;
    }
    .wizard > .steps .done a,
    .wizard > .steps .done a:hover,
    .wizard > .steps .done a:active
    {
        background: <?= $clientData->bg_color_light; ?>;
        color: #fff;
    }
    <?php } ?>
    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: #fff;
        background-color: <?= $clientData->bg_color_code; ?>
    }
    .nav-pills .nav-link, .btn-neutral {
      color: <?= $clientData->bg_color_code; ?>;
    }
    [data-toggle=buttons]:not(.btn-group-colors)>.btn.active, 
    .slim-scroll::-webkit-scrollbar-thumb, 
    .page-item.active .page-link, 
    .custom-control-input:checked~.custom-control-label::before {
      background-color: <?= $clientData->bg_color_code; ?>;
      border: solid 1px #fff;
    }
    .blur-content {
      <?php if($session->accountExpired) { ?>
        pointer-events: none;
        filter: blur(4px);
      <?php } ?>
    }
  </style>
</head>
<body>
  <div class="connection"><div class="connection-lost">Connection lost. Changes will be synced once it is restored. <i class="fa fa-spinner fa-spin"></i><div class="internet-check" data-internet-up="0"></div></div></div><div class="connection"><div class="connection-restored">Your connection is restored, refreshing content <i class="fa fa-spin fa-spinner"></i><div class="internet-check" data-internet-up="0"></div></div></div>
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
            <li class="nav-item blur-content">
              <a class="nav-link <?= (in_array($SITEURL[0], ['point-of-sale'])) ? "active" : null; ?>" href="<?= $baseUrl ?>point-of-sale">
                <i class="ni ni-ui-04 text-success"></i>
                <span class="nav-link-text">Point of Sale</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (in_array($SITEURL[0], ['sales', 'return', 'quotes', 'orders'])) ? "active" : null; ?>" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
                <i class="ni ni-single-copy-04 text-pink"></i>
                <span class="nav-link-text">History</span>
              </a>
              <div class="collapse <?= (in_array($SITEURL[0], ['sales', 'return', 'quotes', 'orders'])) ? "show" : null; ?>" id="navbar-forms">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>sales" class="nav-link">Sales History</a>
                  </li>
                  <?php if($accessObject->hasAccess('view', 'quotes')) { ?>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>quotes" class="nav-link blur-content shortcut-offline">Quotes</a>
                  </li>
                  <?php } ?>
                  <?php if($accessObject->hasAccess('view', 'orders')) { ?>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>orders" class="nav-link blur-content shortcut-offline">Orders</a>
                  </li>
                  <?php } ?>
                  <?php if($clientData->allow_product_return) { ?>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>return" class="nav-link blur-content shortcut-offline">Return Sale</a>
                  </li>
                  <?php } ?>
                </ul>
              </div>
            </li>
            <li class="nav-item blur-content offline-menu">
              <a class="nav-link <?= (in_array($SITEURL[0], ['customers'])) ? "active" : null; ?>" href="<?= $baseUrl ?>customers">
                <i class="fa fa-users text-purple"></i>
                <span class="nav-link-text">Customers</span>
              </a>
            </li>
            <li class="nav-item blur-content offline-menu">
              <a class="nav-link <?= (in_array($SITEURL[0], ['inventory', 'inventory-details', 'products', 'product-types', 'product-brands'])) ? "active" : null; ?>" href="#navbar-inventory" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-inventory">
                <i class="ni ni-ungroup text-orange"></i>
                <span class="nav-link-text">Inventory</span>
              </a>
              <div class="collapse <?= (in_array($SITEURL[0], ['inventory', 'inventory-details', 'products', 'product-types', 'product-brands'])) ? "show" : null; ?>" id="navbar-inventory">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>inventory" class="nav-link">Inventory List</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>product-types" class="nav-link">Product Types</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item blur-content offline-menu">
              <a class="nav-link <?= (in_array($SITEURL[0], ['expenses', 'expenses-category'])) ? "active" : null; ?>" href="#navbar-expenses" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-expenses">
                <i class="fa fa-money-bill text-danger"></i>
                <span class="nav-link-text">Expenses</span>
              </a>
              <div class="collapse <?= (in_array($SITEURL[0], ['expenses', 'expenses-category'])) ? "show" : null; ?>" id="navbar-expenses">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>expenses" class="nav-link">Expenses</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>expenses-category" class="nav-link">Categories</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item blur-content offline-menu">
              <a class="nav-link <?= (in_array($SITEURL[0], ['reports-sales', 'reports-inventory', 'reports-customers', 'reports-salespersons', 'reports-outlets'])) ? "active" : null; ?>" href="#navbar-analytics" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-expenses">
                <i class="ni ni-chart-pie-35 text-info"></i>
                <span class="nav-link-text">Reports</span>
              </a>
              <div class="collapse <?= (in_array($SITEURL[0], ['reports-sales', 'reports-inventory', 'reports-customers', 'reports-salespersons', 'reports-outlets'])) ? "show" : null; ?>" id="navbar-analytics">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>reports-sales" class="nav-link">Sales</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>reports-inventory" class="nav-link">Inventory</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>reports-customers" class="nav-link">Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>reports-salespersons" class="nav-link">Sales Personnel</a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>reports-outlets" class="nav-link">Outlets</a>
                  </li>
                </ul>
              </div>
            </li>
            <?php if($accessObject->hasAccess('view', 'settings')) { ?>    
            <li class="nav-item offline-menu">
              <a class="nav-link <?= (in_array($SITEURL[0], ['outlets', 'users', 'settings', 'import'])) ? "active" : null; ?>" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                <i class="ni ni-map-big text-primary"></i>
                <span class="nav-link-text">Setup</span>
              </a>
              <div class="collapse <?= (in_array($SITEURL[0], ['users','outlets', 'settings', 'import'])) ? "show" : null; ?>" id="navbar-tables">
                <ul class="nav nav-sm flex-column">
                  <?php if(!$session->accountExpired) { ?>
                    <?php if($accessObject->hasAccess('view', 'users')) { ?>
                    <li class="nav-item blur-content">
                      <a href="<?= $baseUrl ?>users" class="nav-link">
                        <i class="fa fa-users text-dark"></i>Users
                      </a>
                    </li>
                    <?php } ?>
                    <?php if($accessObject->hasAccess('view', 'branches')) { ?>
                    <li class="nav-item">
                      <a class="nav-link blur-content" href="<?= $baseUrl ?>outlets">
                        <i class="ni ni-archive-2 text-green"></i>
                        <span class="nav-link-text">Store Outlets</span>
                      </a>
                    </li>
                    <?php } ?>
                    <?php if($accessObject->hasAccess('update', 'settings')) { ?>
                    <li class="nav-item blur-content">
                      <a class="nav-link <?= (in_array($SITEURL[0], ['settings'])) ? "active" : null; ?>" href="<?= $baseUrl ?>settings">
                        <i class="ni ni-align-left-2 text-default"></i>
                        <span class="nav-link-text">Settings</span>
                      </a>
                    </li>
                    <li class="nav-item blur-content">
                      <a class="nav-link <?= (in_array($SITEURL[0], ['import'])) ? "active" : null; ?>" href="<?= $baseUrl ?>import">
                        <i class="fa fa-download text-purple"></i>
                        <span class="nav-link-text">Import Data</span>
                      </a>
                    </li>
                    <?php } ?>
                  <?php } ?>
                  <li class="nav-item">
                    <a href="<?= $baseUrl ?>billing" class="nav-link">
                      <i class="fa fa-shopping-basket text-orange"></i>Billing
                    </a>
                  </li>
                </ul>
              </div>
            </li>
            <?php } ?>            
          </ul>
          <?php if(!confirm_url_id(0, 'point-of-sale')) { ?>
          <hr class="my-3">
          <div class="text-center">
            <?= $notify->message; ?>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark <?= $clientData->bg_color ?> border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Search form -->
          <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
            <div class="form-group mb-0">
              <?php if($clientData->display_clock) { ?>
              <div class="liveclock text-white"><i class="fa fa-clock"></i> <span><?= date("F d Y, H:i:s"); ?></span></div>
              <?php } ?>
            </div>
          </form>
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center ml-md-auto">
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
                <i class="ni ni-ungroup"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default dropdown-menu-right">
                <div class="row shortcuts px-4">
                  <a href="<?= $baseUrl ?>point-of-sale" class="col-4 blur-content shortcut-item">
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
                  <a href="<?= $baseUrl ?>analytics" class="col-4 shortcut-item shortcut-offline">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                      <i class="ni ni-books"></i>
                    </span>
                    <small>Analytics</small>
                  </a>
                  <?php if($accessObject->hasAccess('view', 'branches')) { ?>
                  <a href="<?= $baseUrl ?>branches" class="col-4 blur-content shortcut-item shortcut-offline">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                      <i class="ni ni-pin-3"></i>
                    </span>
                    <small>Outlets</small>
                  </a>
                  <?php } ?>
                  <a href="<?= $baseUrl ?>inventory" class="col-4 blur-content shortcut-item shortcut-offline">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                      <i class="fa fa-list"></i>
                    </span>
                    <small>Inventory</small>
                  </a>
                  <?php if($accessObject->hasAccess('view', 'users')) { ?>
                  <a href="<?= $baseUrl ?>users" class="col-4 blur-content shortcut-item shortcut-offline">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-gray-dark">
                      <i class="fa fa-users"></i>
                    </span>
                    <small>Users</small>
                  </a>
                  <?php } ?>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center ml-auto ml-md-0">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <i class="fa fa-user"></i>
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold"><?= $userData->name; ?></span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <a href="<?= $baseUrl ?>profile" class="dropdown-item blur-content shortcut-offline">
                  <i class="ni ni-single-02"></i>
                  <span>My profile</span>
                </a>
                <?php if(!$session->accountExpired) { ?>
                  <?php if($accessObject->hasAccess('view', 'settings')) { ?>
                  <a href="<?= $baseUrl ?>settings" class="dropdown-item blur-content shortcut-offline">
                    <i class="ni ni-settings-gear-65"></i>
                    <span>Settings</span>
                  </a>
                  <?php } ?>
                <?php } ?>
                <a href="<?= $baseUrl ?>users-login-history" class="dropdown-item blur-content shortcut-offline">
                  <i class="ni ni-calendar-grid-58"></i>
                  <span>Login History</span>
                </a>
                <!-- <a href="<?= $baseUrl ?>support" class="dropdown-item">
                  <i class="ni ni-support-16"></i>
                  <span>Support</span>
                </a> -->
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
    <div class="main-content-loader main-body-loader" style="display: flex">
        <i class="fa fa-3x fa-pulse fa-spinner"></i>
    </div>