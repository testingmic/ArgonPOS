<?php
#FETCH SOME GLOBAL FUNCTIONS
global $config, $posClass;

#Define Root Directory
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
  <title>Reset Password - <?= config_item('site_name'); ?></title>
  <link rel="canonical" href="<?= $rootDir; ?>" />
  <meta name="keywords" content="dashboard, pos">
  <link rel="icon" href="<?= $rootDir; ?>assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="<?= $rootDir; ?>assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="<?= $rootDir; ?>assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="<?= $rootDir; ?>assets/css/argon.min9f1e.css?v=1.1.0" type="text/css">
  <script src="<?= $rootDir; ?>assets/vendor/jquery/dist/jquery.min.js"></script>
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
              <?php
              global $session;

              $access_token = random_string('alnum', '45');
              $session->set_userdata("access_token", $access_token);


              if(isset($_GET["pwd"]) and !confirm_url_id(1)) {

                if(isset($_GET["tk"])) {

                  $code = xss_clean($_GET["tk"]);

                  if(strlen($code) > 29) {

                    $stmt = $pos->query("SELECT * FROM users_reset_request WHERE request_token='$code' AND token_status='PENDING'");

                    if($stmt->rowCount()) {

                      foreach($stmt as $results) {

                        $expiry_time = $results["expiry_time"];
                        $username = $results["username"];
                        $user_id = $results["user_id"];
                        $session->set_userdata("resetAccess_Token", $code);
                        $session->set_userdata("resetUserId", $user_id);

                        if($expiry_time > time()) {
                          ?>
                          <div class="text-center text-muted mb-4">
                            <small>Reset your user password.</small>
                          </div>
                          <form autocomplete="Off" method="post" class="submitForm form-horizontal auth-form my-4" action="<?= $config->base_url('ajax_login/doResetPassword') ?>">

                            <div class="form-group form-group-default">
                              <label>Enter Password</label>
                              <div class="controls">
                                <input type="password" name="password" placeholder="Enter Password" class="form-control">
                              </div>
                            </div>
                            <!-- END Form Control-->
                            <!-- START Form Control-->
                            <div class="form-group form-group-default">
                              <label>Confirm Password</label>
                              <div class="controls">
                                <input type="password" class="form-control" name="password2" placeholder="Confirm Password">
                              </div>
                            </div>
                            <input name="reset-password" type="hidden">
                            <input type="hidden" name="user_id" value="<?php print base64_encode($user_id); ?>">
                            <input type="hidden" name="access_token" value="<?php print base64_encode($access_token); ?>">
                            <div class="form-group mb-0 row">
                              <div class="col-12 mt-2">
                                <button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">Reset Password <i class="fas fa-sign-in-alt ml-1"></i></button>
                              </div><!--end col--> 
                            </div> <!--end form-group--> 
                          </form>
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
                                  }, 20000);
                                }
                              })
                            });
                          </script>
                          <?php
                        } else {
                          // update the database and set the token status to expired
                          $pos->execute("UPDATE users_reset_request SET token_status='EXPIRED' WHERE request_token='$code' AND username='$username' AND user_id='$user_id'");

                          $pos->execute("INSERT INTO users_activity_logs SET fulldate=now(), user_id='$user_id', date_recorded=now(), username='$username', description='$username supplied an expired Password Reset Access Token for processing.'");

                          print_msg("danger", "Sorry! The password reset token has expired. Please <a href='".$config->base_url()."recover'><strong>click here</strong></a> to request for a new token.");
                        }
                      }
                    }  else {
                      print_msg("danger", "Sorry! An invalid password reset token was submitted.");
                    }
                  } else {
                    print_msg("danger", "Sorry! An invalid password reset token was submitted.");
                  }
                } else {
                  print_msg("danger", "Sorry! An invalid password reset token was submitted.");
                }
              } elseif(isset($_GET["tk"]) and confirm_url_id(1, 'act')) {
                $code = xss_clean($_GET["tk"]);
                ?>
                <h3 class="text-center">Account Verification</h3>
                <?php
                if(strlen($code) >= 40) {
                  $stmt = $pos->query("
                    SELECT 
                      a.clientId, a.branchId, a.name, a.email, a.verify_token,
                      b.setup_info, a.id, a.user_id
                    FROM users a
                    LEFT JOIN settings b ON b.clientId = a.clientId
                    WHERE a.verify_token='$code'
                  ");
                  if($stmt->rowCount()) {
                    while($results = $stmt->fetch(PDO::FETCH_OBJ)) {
                      // update the user information
                      $setupInfo = json_decode($results->setup_info);
                      $setupInfo->verified = 1;

                      // unset the access token
                      $pos->query("UPDATE settings SET setup_info='".json_encode($setupInfo)."' WHERE clientId = '{$results->clientId}'");

                      // update the user status
                      $pos->query("UPDATE users SET status='1', verify_token=NULL WHERE id = '{$results->id}'");

                      // log the user activity
                      $posClass->userLogs("setup-verify", $results->clientId, "Has verified the Store Details submitted and activated the Admin User Account for the Store.", $results->clientId, $results->branchId, $results->user_id);

                      print_msg("success", "Congrats! Your account has successfully been verified. You can now proceed to <a href='".$config->base_url('login')."'>Login</a>");

                    }
                  } else {
                    print_msg("danger", "Sorry! An invalid authorization token was submitted.");
                  }
                } else {
                  print_msg("danger", "Sorry! An invalid authorization token was submitted.");
                }
              } else {
                print_msg("danger", "Sorry! An invalid authorization token was submitted.");
              }
              ?>

              <div class="form-result"></div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="<?= $config->base_url('login') ?>" class="text-light"><small>Sign into your Account</small></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="<?= $rootDir; ?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php exit; ?>