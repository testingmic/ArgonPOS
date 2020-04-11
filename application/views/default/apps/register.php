<?php
$page_header = "Register";
require "header.php";
// initializing
$session->subscriptionPack = 'trial';

// check what the user is registering
if(isset($_GET["v"])) {
  // set the value
  $reg = strtolower(xss_clean($_GET["v"]));

  // accepted check
  if(in_array($reg, ["basic", "alpha"])) {
    $session->subscriptionPack = $reg;
  }
}
?>
<div class="main-content">
<!-- Header -->
<div class="header bg-gradient-primary pt-0 mt-0">
  <!-- Page content -->
    <div class="container">
      <!-- Table -->
      <div class="row justify-content-center">
        <div class="col-lg-12 text-center">
          <h1 class="text-white" style="font-size: 40px;">Let's get started</h1>
          <p class="text-white">Try <strong><?= config_item('site_name') ?></strong> for 14days. No credit card, no payments</p>
        </div>
        <div class="col-lg-6 col-md-8">
          <div class="card bg-secondary border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form" class="setup" action="<?= $baseUrl; ?>/register" method="POST" autocomplete="Off">
                <div class="form-group">
                  <label for="store_name">Fullname</label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni"></i></span>
                    </div>
                    <input name="fullname" class="form-control" placeholder="Your fullname" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="contact">Contact Number</label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni"></i></span>
                    </div>
                    <input name="contact" class="form-control" placeholder="Your Contact Number" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="store_name">Store Name</label>
                  <div class="input-group input-group-merge input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                    </div>
                    <input class="form-control" name="store_name" placeholder="Store Name" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <div class="input-group input-group-merge input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" name="store_email" placeholder="Email" type="email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" name="password" placeholder="Password" type="password">
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary mt-4">Create account</button>
                </div>
              </form>
              <div class="form-result text-center mt-3"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</div>
<?php require "footer.php"; ?>