<?php
#FETCH SOME GLOBAL FUNCTIONS
global $config, $message;

// Define Root Directory
$rootDir = $config->base_url();
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Page Not Found - <?= config_item('site_name'); ?></title>
  <link rel="canonical" href="<?= $rootDir; ?>" />
  <meta name="keywords" content="dashboard, pos">
  <link rel="icon" href="<?= $rootDir; ?>assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="<?= $rootDir; ?>assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="<?= $rootDir; ?>assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="<?= $rootDir; ?>assets/css/argon.min9f1e.css?v=1.1.0" type="text/css">
</head>

<body class="bg-default">
  <!-- Main content -->
  <div class="main-content">
    <!-- Header -->
    <div class="header bg-gradient-primary py-lg-6 pt-lg-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <p class="text-lead text-white">
                <img src="<?= $config->base_url('assets/img/brand/white.png'); ?>" height="55" alt="logo" class="auth-logo" style="border-radius: 5px">
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0">
            <img src="<?= $config->base_url('assets/img/404.jpg'); ?>" alt="" class="d-block mx-auto mt-4" height="250">
            <div class="card-body text-center px-lg-5 py-lg-5">
              <div class="form-result">
                <h4 class="mt-0 mb-3"><p>Sorry the page you are trying to view does not exist on this server</p></h4>
                <a href="<?= $config->base_url('dashboard'); ?>" class="btn btn-sm btn-primary">Back to Dashboard</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="<?= $rootDir; ?>assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="<?= $rootDir; ?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= $rootDir; ?>assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="<?= $rootDir; ?>assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="<?= $rootDir; ?>assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Argon JS -->
  <script src="<?= $rootDir; ?>assets/js/argon.min9f1e.js?v=1.1.0"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="<?= $rootDir; ?>assets/js/demo.min.js"></script>
</body>
</html>