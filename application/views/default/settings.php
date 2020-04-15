<?php
// globalize everything
global $accessObject, $posClass;
// create a new object for the access level
$accessObject->userId = $session->userId;

$PAGETITLE = "Settings";

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// ensure that the user is logged in
if(!$admin_user->logged_InControlled()) {
  include_once "login.php";
  exit;
}

// run this page if the user has the required permissions
if($accessObject->hasAccess('view', 'settings')) {

// include the important files
require_once "headtags.php";

global $clientData;

// array for product expiry period
$expiryRange = [
  '2 WEEK' => 'Two Weeks (14 Days)',
  '1 MONTH' => 'One Month (30 Days)',
  '2 MONTH' => 'Two Months (60 Days)',
  '3 MONTH' => 'Three Months (Quarterly)',
  '6 MONTH' => 'Six Months (Semi Annually)'
];

$themeColors = ["danger", "indigo", "orange", "blue", "purple", "green", "teal", "darker"];
?>
<!-- Page Content-->
<!-- Header -->
<div class="header <?= $clientData->bg_color ?> pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>"><i class="fas fa-home"></i> Dashboard</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $PAGETITLE ?></a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">
  
    <div class="col-sm-12">
      <div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="general_detail_tab" data-toggle="pill" href="#general_detail">Information</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="sales_options_tab" data-toggle="pill" href="#sales_options">Sales, Invoices & Stock Notifications</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="payment_options_tab" data-toggle="pill" href="#payment_options">Payment Options</a>
                  </li>
                </ul>        
              </div><!--end card-body-->
            </div><!--end card-->
          </div><!--end col-->
        </div><!--end row-->


        <div class="row">
          <div class="col-12">

            <div class="tab-content detail-list" id="pills-tabContent">

              <div class="tab-pane fade show active" id="general_detail">
                <div class="row">

                  <div class="col-12">
                    <div class="card">
                      <?= connectionLost(); ?>
                      <?= form_loader(); ?>
                      <div class="card-body">
                        <form autocomplete="Off" autocomplete="Off" data-form="company-details" class="company_settings" action="<?= $config->base_url('api/branchManagment/settingsManager/updateCompanyDetail'); ?>" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="company_name">Store Name</label>
                                <input value="<?= $clientData->client_name ?>" type="text" class="form-control" name="company_name" id="company_name" placeholder="Full Name">
                              </div><!--end form-group-->
                              <div class="form-group">
                                <label for="setEmail">Email address</label>
                                <input type="email" value="<?= $clientData->client_email ?>" name="email" class="form-control" id="email" placeholder="Enter email">
                              </div><!--end form-group-->
                              <div class="form-group">
                                <label for="setPassword">Website</label>
                                <input value="<?= $clientData->client_website ?>" type="text" class="form-control" name="website" id="website" placeholder="Website">
                              </div><!--end form-group-->
                              <div class="form-group">
                                <label for="display_clock">Display clock on header</label>
                                <div style="padding-left: 3.5rem;" class="custom-control custom-switch switch-primary">
                                  <input type="checkbox" <?= ($clientData->display_clock == "1") ? "checked" : null; ?> name="display_clock" value="1" class="custom-control-input" id="display_clock">
                                  <label class="custom-control-label" for="display_clock">Show Clock</label>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="primary_contact">Primary Contact</label>
                                <input value="<?= $clientData->primary_contact ?>" placeholder="Primary Contact" type="text" class="form-control" name="primary_contact" id="primary_contact">
                              </div>
                              <div class="form-group">
                                <label for="secondary_contact">Secondary Contact</label>
                                <input value="<?= $clientData->secondary_contact ?>" placeholder="Secondary Contact" type="text" class="form-control" name="secondary_contact" id="secondary_contact">
                              </div>
                              <div class="form-group">
                                <label for="address">Address</label>
                                <input value="<?= $clientData->address_1 ?>" placeholder="Address" type="text" class="form-control" name="address" id="address">
                              </div>
                              <?php
                              // Theme colors available in alpha version
                              if($setupInfo->type == "alpha") { ?>
                              <div class="form-group">
                                <label for="theme_color">Theme Color</label>
                                <select name="theme_color" id="theme_color" class="form-control selectpicker">
                                <?php
                                foreach($themeColors as $value) {
                                  // print the period range
                                  print "<option ".(($clientData->theme_color_code == $value) ? "selected" : null)." value='{$value}'>".ucfirst($value)."</option>";
                                }
                                ?>
                              </select>
                              </div>
                              <?php } ?>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="client_logo">Store Logo</label><br>
                                <input type="file" name="company_logo" id="company_logo" class="form-control">
                                <img data-name="company_logo" width="100px" src="<?= $config->base_url($clientData->client_logo) ?>" alt="">
                              </div>
                            </div>
                            <?php if($accessObject->hasAccess('update', 'settings')) { ?>
                              <div class="col-lg-10"></div>
                              <div class="col-lg-2 text-right">
                                <input type="hidden" name="updateCompanyDetail" value="updateCompanyDetail">
                                <button type="submit" class="btn <?= $clientData->btn_outline ?>"><i class="fa fa-save"></i> Save Changes</button>
                              </div>
                            <?php } ?>
                          </div> 
                        </form>
                      </div><!--end card-body-->
                    </div><!--end card-->
                  </div><!--end col-->
                </div><!--end row-->                                             
              </div>

              <style>
                input[type="radio"]:checked {
                  margin-left: 25px;
                  border: 1px solid blue!important;
                }
              </style>
              <div class="tab-pane fade" id="payment_options">
                <div class="card" style="background: none;">
                  <?= connectionLost(); ?>
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="card">

                        <div class="card-body">
                          <div class="media setting-card">
                            <span class="sett-card-icon set-icon-success">
                              <i class="mdi mdi-cash-multiple"></i>
                            </span>
                            <div class="media-body align-self-center"> 
                              <div class="setting-detail">
                                <h3 class="mb-0 mt-0">Cash</h3>
                                <p class="text-muted mb-0">Accept Cash as a medium of exchange at the various Active Stores</p>
                              </div> 
                              <div class="mt-2">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                  <label class="btn <?= $clientData->btn_outline ?> btn-sm">
                                    <input style="cursor: pointer;" data-module="cash" data-value="checked" type="radio" name="options"> Enable
                                  </label>                                            
                                  <label class="btn btn-outline-danger btn-sm">
                                    <input style="cursor: pointer;" data-module="cash" data-value="unchecked" type="radio" name="options"> Disable
                                  </label>
                                </div>
                              </div>                                                  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="media setting-card">
                            <span class="sett-card-icon set-icon-danger">
                              <i class="mdi mdi-database"></i>
                            </span>
                            <div class="media-body align-self-center"> 
                              <div class="setting-detail">
                                <h3 class="mb-0 mt-0">Debit/Credit Card</h3>
                                <p class="text-muted mb-0">Allow customers to pay with their Visa / Master Cards at the various Stores.</p>
                              </div> 
                              <div class="mt-2">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                  <label class="btn <?= $clientData->btn_outline ?> btn-sm">
                                    <input style="cursor: pointer;" data-module="card" data-value="checked" type="radio" name="options"> Enable
                                  </label>                                            
                                  <label class="btn btn-outline-danger btn-sm">
                                    <input style="cursor: pointer;" data-module="card" data-value="unchecked" type="radio" name="options"> Disable
                                  </label>
                                </div>
                              </div>                                                  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="media setting-card">
                            <span class="sett-card-icon set-icon-purple">
                              <i class="mdi mdi-cellphone-iphone"></i>
                            </span>
                            <div class="media-body align-self-center"> 
                              <div class="setting-detail">
                                <h3 class="mb-0 mt-0">Mobile Money</h3>
                                <p class="text-muted mb-0">Enabling this option will allow customers to Pay for items with their MoMo Accounts</p>
                              </div> 
                              <div class="mt-2">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                  <label class="btn <?= $clientData->btn_outline ?> btn-sm">
                                    <input style="cursor: pointer;" data-module="MoMo" data-value="checked" type="radio" name="options"> Enable
                                  </label>                                            
                                  <label class="btn btn-outline-danger btn-sm">
                                    <input style="cursor: pointer;" data-module="MoMo" data-value="unchecked" type="radio" name="options"> Disable
                                  </label>
                                </div>
                              </div>                                                  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="media setting-card">
                            <span class="sett-card-icon set-icon-purple">
                              <i class="mdi mdi-account-arrow-right-outline"></i>
                            </span>
                            <div class="media-body align-self-center"> 
                              <div class="setting-detail">
                                <h3 class="mb-0 mt-0">Credit</h3>
                                <p class="text-muted mb-0">Allow customers to purchase items on credit and pay at a later date.</p>
                              </div> 
                              <div class="mt-2">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                  <label class="btn <?= $clientData->btn_outline ?> btn-sm">
                                    <input style="cursor: pointer;" data-module="credit" data-value="checked" type="radio" name="options"> Enable
                                  </label>                                            
                                  <label class="btn btn-outline-danger btn-sm">
                                    <input style="cursor: pointer;" data-module="credit" data-value="unchecked" type="radio" name="options"> Disable
                                  </label>
                                </div>
                              </div>                                                  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>  
              </div>


              <div class="tab-pane fade" id="sales_options">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <?= form_loader(); ?>
                      <?= connectionLost(); ?>
                      <div class="card-body">
                        <form autocomplete="Off" data-form="sales-details" class="company_settings" action="<?= $config->base_url('api/branchManagment/settingsManager/updateSalesDetails'); ?>" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-6 col-lg-6">
                              <div class="form-group">
                                <label for="receipt_message">Receipt Invoice Message</label>
                                <input value="<?= $clientData->receipt_message ?>" type="text" class="form-control" name="receipt_message" id="receipt_message" placeholder="Message to appear beneath printed invoices">
                              </div><!--end form-group-->
                              <div class="form-group">
                                <label for="company_logo">Manager's Signature</label><br>
                                <input type="file" name="company_logo" id="company_logo2" class="form-control">
                                <img data-name="company_logo" width="100px" src="<?= $config->base_url($clientData->manager_signature) ?>" alt="">
                              </div>
                              <div class="form-group">
                                <label for="terms_and_conditions">Terms & Conditions</label>
                                <textarea name="terms_and_conditions" id="terms_and_conditions" rows="10" class="form-control"><?= $clientData->terms_and_conditions ?></textarea>
                              </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                              <div class="form-group">
                                <label for="opening_days">Store Opening Days (This controls whether the POS is opened for sales or not)</label>
                                <?php
                                // get the existing opening days
                                $openingDays = $clientData->shop_opening_days;
                                $days = [];
                                // confirm that the record is not empty
                                if(!empty($openingDays)) {
                                // explode the content with the comma
                                  $days = explode(',', $openingDays);
                                }
                                // get the days of the week
                                for($i = 0; $i < 7; $i++) {
                                  $today = date("l", strtotime("Monday +$i day"));
                                  ?>
                                  <div style="padding-left: 3.5rem;" class="custom-control custom-switch switch-primary">
                                    <input type="checkbox" name="opening_days[]" value="<?= ucfirst($today) ?>" class="custom-control-input" id="<?= $today ?>" <?= in_array($today, $days) ? "checked=\"checked\"" : null; ?>>
                                    <label class="custom-control-label" for="<?= $today ?>"><?= $today; ?></label>
                                  </div>
                                <?php } ?>
                              </div>
                              <div class="form-group col-lg-6 col-md-12 col-sm-12">
                                  <label for="default_currency">Default Currency</label>
                                    <select name="default_currency" id="default_currency" class="form-control selectpicker">
                                    <?php
                                    $curList = $posClass->getAllRows("settings_currency", "*");
                                    // loop through the branches list
                                    foreach($curList as $eachCur) {
                                      ?>
                                      <option <?= ($eachCur->currency == $clientData->default_currency) ? "selected" : null; ?> value="<?= $eachCur->currency ?>"><?= $eachCur->currency ?></option>
                                      <?php 
                                    }
                                    ?>
                                    </select>
                                </div>
                                <hr>
                                <div class="form-group">
                                  <label for="opening_days">Print Receipts automatically at Point of Sale Outlets</label>
                                  <div style="padding-left: 3.5rem;" class="custom-control custom-switch switch-primary">
                                    <input type="checkbox" <?= ($clientData->print_receipt == "yes") ? "checked" : null; ?> name="print_receipt" value="yes" class="custom-control-input" id="print_receipt">
                                    <label class="custom-control-label" for="print_receipt">Print Receipt</label>
                                  </div>
                                </div>
                            </div>
                            <div class="col-lg-12"><hr></div>
                            <div class="col-md-3">
                              <label for="exp_notifi_days">Product Expiry Notification Period</label>
                              <select name="exp_notifi_days" id="exp_notifi_days" class="form-control selectpicker">

                                <?php
                                foreach($expiryRange as $key => $value) {
                                  // print the period range
                                  print "<option ".(($clientData->expiry_notification_days == $key) ? "selected" : null)." value='{$key}'>{$value}</option>";
                                }
                                ?>
                              </select>
                            </div>
                            <div class="col-md-3">
                              <label for="allow_product_return">Product Return</label>
                              <select name="allow_product_return" id="allow_product_return" class="form-control selectpicker">
                                <?php
                                print "<option ".(($clientData->allow_product_return == "0") ? "selected" : null)." value='0'>Dont Allow Product Return</option>";
                                print "<option ".(($clientData->allow_product_return == "1") ? "selected" : null)." value='1'>Allow Product Return</option>";
                                ?>
                              </select>
                            </div>
                            <div class="col-md-3">
                              <label for="fiscal_year_start">Fiscal Year Start</label>
                              <select name="fiscal_year_start" id="fiscal_year_start" class="form-control selectpicker">
                                <?php
                                // loop through from 1 to 12
                                for($i=0; $i < 12; $i++) {
                                  // set the month 
                                  $month = date("Y-m-01", strtotime("January +$i month"));
                                  $label = date("F, Y", strtotime("January +$i month"));
                                  // print the period range
                                  print "<option ".(($clientData->fiscal_year_start == $month) ? "selected" : null)." value='{$month}'>1st {$label}</option>";
                                }
                                ?>
                              </select>
                            </div>

                            <?php if($accessObject->hasAccess('update', 'settings')) { ?>
                              <div class="col-lg-10 mt-4"></div>
                              <div class="col-lg-2 text-right">
                                <input type="hidden" name="updateSalesDetails" value="updateSalesDetails">
                                <button type="submit" class="btn <?= $clientData->btn_outline ?>"><i class="fa fa-save"></i> Save Changes</button>
                              </div>
                            <?php } ?>

                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
      

  </div><!--end row-->
  

<?php require_once "foottags.php" ?>

<script>
  <?php if($accessObject->hasAccess('update', 'settings')) { ?>
    $(`input[name="company_logo"]`).change(function() {
      var parentDiv = $(this).parent(`form[class="company_settings"]`).attr('data-form');

      var file = this.files[0];
      var fileType = file.type;
      var match = ['image/jpeg', 'image/png', 'image/jpg'];
      if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))) {
        $(`form[data-form="${parentDiv}"] div[class="form-result"]`).html('<div class="alert alert-danger" align="center">Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.</div>');
        $(`form[data-form="${parentDiv}"] input[name="company_logo"]`).val('');

        setTimeout(function(e) {
          $(`form[data-form="${parentDiv}"] div[class="form-result"]`).html(``);
        }, 3000);
        return false;
      }
    });
    $(`form[class="company_settings"]`).on('submit', async function(e){
      let curForm = $(this).attr('data-form');
      let clkButton = $(`form[data-form="${curForm}"] button[type="submit"]`);
      let subResult = $(`form[data-form="${curForm}"] div[class="form-result"]`);

      e.preventDefault();
      $(`div[class="form-result"]`).html(``);
      $(`div[class="form-content-loader"]`).css("display","flex");

      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
          clkButton.attr("disabled","disabled");
        },
        success: function(resp){
          subResult.html(resp.message);
          if(resp.status == 200){
            Toast.fire({
              type: "success",
              title: "Store details successfully updated."
            });
            $(`form[data-form="${curForm}"] input[name="company_logo"]`).val('');
          } else if(resp.status == 201){
            Toast.fire({
              type: "success",
              title: "Store details successfully updated."
            })
            $(`form[data-form="${curForm}"] img[data-name="company_logo"]`).attr('src', resp.message);
          }
          clkButton.removeAttr("disabled");
        }, error: function(err) {
          Toast.fire({
            type: "error",
            title: "Sorry! An error was encountered while processing the request."
          })
          clkButton.removeAttr("disabled");
          $(`div[class="form-content-loader"]`).css("display","none");
        }, complete: function(data) {
          $(`div[class="form-content-loader"]`).css("display","none");
        }
      });
    });

    $(`div[id="payment_options"] div[class="col-lg-4"] input`).on('change', function(i, e) {
      let Option = $(this).attr('data-module');
      let Value = $(this).attr('data-value');

      $.ajax({
        url: `${baseUrl}api/branchManagment/updatePaymentOptions`,
        data: { updatePaymentOptions: true, Option: Option, Value: Value },
        type: "POST",
        dataType: "JSON",
        success: function(resp) {
          console.log(resp)
        }
      });
    });
  <?php } ?>
</script>
</body>
</html>
<?php 
} else {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
}
?>