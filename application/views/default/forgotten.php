<?php
#FETCH SOME GLOBAL FUNCTIONS
global $config;

// Define Root Directory
$rootDir = $config->base_url();

// ensure that the user is logged in
if($admin_user->logged_InControlled()) {
    include_once "dashboard.php";
    exit;
}
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Request Password Reset - <?= config_item('site_name'); ?></title>
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
              <img src="<?= $config->base_url('assets/img/brand/white.png'); ?>" height="55" alt="logo" class="auth-logo" style="border-radius: 5px">
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
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Please enter your email to recover your account</small>
              </div>
              <form autocomplete="Off" action="<?= $config->base_url('ajax_login/doRecover') ?>" class="submitForm">
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input name="email" class="form-control" placeholder="Email" type="email">
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4">Request Link</button>
                </div>
              </form>
              <div class="form-result"></div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="<?= $config->base_url('login') ?>" class="text-light"><small>Sign into your Account</small></a>
            </div>
            <!-- <div class="col-6 text-right">
              <a href="<?= $config->base_url('register') ?>" class="text-light"><small>Create new account</small></a>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="<?= $rootDir; ?>assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="<?= $rootDir; ?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(`form[class~="submitForm"]`).on('submit', function(e) {
        e.preventDefault();
        var formButton = $(`form[class~="submitForm"] [type="submit"]`);
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                formButton.prop('disabled', true);
                $(`div[class="form-result"]`).html(`<div align="center">Processing request <i class="fa fa-spin fa-spinner"></i></div>`);
            },
            success: function(resp) {
                if(resp.status != 200) {
                    $(`div[class="form-result"]`).html(`<div class="alert alert-danger">${resp.result}</div>`);
                } else {
                    $(`form[class~="submitForm"]`)[0].reset();
                    $(`div[class="form-result"]`).html(`<div class="alert alert-success">${resp.result}</div>`);
                }
            },
            error: function(err) {
                formButton.prop('disabled', false);
                $(`div[class="form-result"]`).html(``);
            },
            complete: function(data) {
                formButton.prop('disabled', false);
                setTimeout(function() {
                    $(`div[class="form-result"]`).html(``);
                }, 7000);
            }
        })
    });
  </script>
</body>

</html>
<?php exit; ?>