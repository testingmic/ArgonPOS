<?php $baseUrl = $config->base_url(); global $SITEURL, $clientData, $Notification, $accessObject; ?>
<!-- Footer -->
  <?php if(!confirm_url_id(0, 'point-of-sale')) { ?>
  <footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6">
        <div class="copyright text-center text-lg-left text-muted">
          &copy; <?= date("Y") ?> <a href="<?= config_item('base_url') ?>" class="font-weight-bold ml-1" target="_blank"><?= config_item('site_name') ?></a>
        </div>
      </div>
      <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="<?= config_item('site_url') ?>" class="nav-link" target="_blank"><?= config_item('developer') ?></a>
          </li>
        </ul>
      </div>
    </div>
  </footer>
  <?php } ?>
  <?php if(!$session->accountExpired) { ?>
    <?php if(in_array($SITEURL[0], ["customers", "customer-detail"])) { ?>
      <?php if($accessObject->hasAccess('update', 'customers')) { ?>
      <div class="modal fade" id="newCustomerModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="content-loader"><i class="fa fa-spinner fa-pulse fa-3x"></i></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Update Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="card mb-0 p-3">
                    <form autocomplete="Off" method="POST" class="form py-0" id="updateCustomerForm">
                      <div class="form-row mb-2">
                        <div class="form-group col-md-2">
                          <label for="inputState">Title</label>
                          <select id="newCustomer_title" name="nc_title" class="form-control selectpicker">
                            <option value="null">-- Select --</option>
                            <option value="Mr">Mr.</option>
                            <option value="Mrs">Mrs.</option>
                            <option  value="Dr">Dr.</option>
                            <option  value="Miss">Miss.</option>
                            <option value="Prof">Prof.</option>
                            <option value="Hon">Hon.</option>
                          </select>
                        </div>
                        <div class="form-group col-md-5">
                          <label for="newCustomer_firstname">First Name</label>
                          <input type="text" class="form-control" name="nc_firstname" id="newCustomer_firstname" placeholder="First Name">
                        </div>
                        <div class="form-group col-md-5">
                          <label for="newCustomer_lastname">Last Name</label>
                          <input type="text" class="form-control" name="nc_lastname" id="newCustomer_lastname" placeholder="Last Name">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label for="newCustomer_primarycontact">Primary Phone No.</label>
                          <input placeholder="Contact Number" type="text" class="form-control" name="nc_contact" id="newCustomer_primarycontact">
                        </div>
                        <div class="form-group col-md-4">
                          <label for="newCustomer_seccontact">Email Address</label>
                          <input placeholder="Email Address" type="text" class="form-control" name="nc_email" id="newCustomer_seccontact">
                        </div>
                        <div class="form-group col-md-4">
                          <label for="newCustomer_residence">Place of Residence</label>
                          <input placeholder="Place of Residence" type="text" class="form-control" name="residence" id="newCustomer_residence">
                        </div>
                      </div>
                      <input type="hidden" name="customer_id">
                      <input type="hidden" name="request" value="update-record">
                    </form>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="updateCustomerForm" class="btn <?=  $clientData->btn_outline; ?>"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
      </div>
      <?php } ?>
    <?php } ?>
  <?php } ?>
  </div>

  <?php if(!$session->accountExpired) { ?>
  
  <div class="modal fade launchModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <?= form_loader(); ?>
          <div class="card mb-0 p-3">
            <div class="modal-header">
                <h5 class="modal-title mt-0 show-modal-title" id="myLargeModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body show-modal-body" style="min-height: 250px; padding-top: 0px"></div>
          </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
  <?php if(in_array($SITEURL[0], ['orders', 'quotes', 'outlets', 'product-types','customers', 'expenses-category', 'expenses'])) { ?>
  <div class="modal fade delete-modal" tabindex="-1" id="deleteData" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="exampleModalLabel">Delete Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-top:0px">
                <div class="card mb-0 p-3">
                    <form method="POST" class="submitThisForm" action="">
                        <div class="form-group mb-3 details-pane"></div><!--end form-group-->
                        <div class="form-group mb-3 text-right">
                          <input type="hidden" name="itemToDelete" class="itemToDelete">
                          <input type="hidden" name="itemId" class="itemId">
                          <button data-dismiss="modal" aria-label="Close" class="btn btn-outline-default">Cancel</button>
                          <button type="submit" class="btn <?= $clientData->btn_outline ?>">Yes Confirm</button>
                        </div>
                    </form>
                </div>
                <div class="form-result"></div>
            </div>
        </div>
    </div>
  </div>
  <?php } ?>
  
  <?php if(in_array($SITEURL[0], ['users'])) { ?>
  <div class="modal fade deleteModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom: 0px">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                  <div class="card mb-0 p-3">
                    <p class="show-delete-msg"></p>
                    <p class="show-delete-body">Are You Sure You Want To Delete This?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-default" data-dismiss="modal">Cancel</button>
                        <button class="btn <?= $clientData->btn_outline ?> confirm-delete-btn" type="button">
                            <i class="fa fa-check"></i> Yes Confirm
                        </button>
                    </div>
                  </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <?php } ?>
  
  <?php if(in_array($SITEURL[0], ['sales', 'index', 'dashboard'])) { ?>
  <div class="modal fade sendMailModal" tabindex="-1" id="sendMailModal" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title mt-0" id="exampleModalLabel">Send Receipt via Email</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="card mb-0 p-3">
                      <div class="form-group mb-3 details-pane">
                          <div class="form-group">
                              <label for="fullname">Name</label>
                              <input type="text" name="fullname" id="fullname" class="form-control">
                          </div>
                          <div class="form-group">
                              <label for="send_email">Email Address</label>
                              <input type="email" name="send_email" id="send_email" class="form-control">
                              <input type="hidden" name="request_type" class="request_type">
                          </div>
                      </div><!--end form-group-->
                      <div class="form-group mb-3 text-right">
                          <button href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="btn btn-danger">Cancel</button>
                          <input type="hidden" name="receiptId" class="receiptId">
                          <input type="hidden" name="customerId" class="customerId">
                          <button href="javascript:void(0)" class="resend-email-button btn btn-success">Send Mail</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <?php } ?>

  <?php } ?>

<div class="notification-content"></div>
<div class="payment-backdrop hidden text-center">
  <div class="payment-buttons text-center">
    <button class="btn <?= $clientData->bg_color ?> return-to-payment-window">View Payment Window</button>
    <button class="btn btn-danger cancel-ongoing-payment-activity">Cancel</button>
  </div>
</div>
<div class="svon" data-value="1"></div>
<!-- Core -->
<script src="<?= $baseUrl ?>assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/js-cookie/js.cookie.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/select2/dist/js/select2.min.js"></script>
<?php if(!in_array($SITEURL[0], ["import", "products"])) { ?>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
<?php } ?>
<?php if(in_array($SITEURL[0], ["reports", "customer-detail", "analytics", "dashboard", "index"])) { ?>
<script src="<?= $baseUrl ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<?php } ?>
<?php if(in_array($SITEURL[0], ["dashboard", "index"])) { ?>
<script src="<?= $baseUrl ?>assets/vendor/chart.js/dist/Chart.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/chart.js/dist/Chart.extension.js"></script>
<?php } ?>
<?php if($clientData->display_clock) { ?>
<script src="<?= $baseUrl ?>assets/vendor/moment/min/moment.min.js"></script>
<?php } ?>
<?php if(in_array($SITEURL[0], ["point-of-sale", "requests"])) { ?>
<script src="<?= $baseUrl ?>assets/vendor/jquery-steps/jquery.steps.min.js"></script>
<?php } ?>
<?php if(in_array($SITEURL[0], ["settings"])) { ?>
<script src="<?= $baseUrl ?>assets/vendor/summernote/summernote-bs4.min.js"></script>
<?php } ?>
<script type="text/javascript"><?php if(in_array($SITEURL[0], ["point-of-sale", "requests"])) { ?>Cookies.set("sidenav-state", "unpinned");<?php } else { ?>Cookies.set("sidenav-state", "pinned");<?php } ?></script>
<script src="<?= $baseUrl ?>assets/js/datepicker.min.js"></script>
<script src="<?= $baseUrl ?>assets/js/argon.min9f1e.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script type="text/javascript">var baseUrl = '<?= $baseUrl; ?>';</script>
<script src="<?= $baseUrl ?>assets/js/_js.v1.js"></script>
<script>
<?php if($clientData->display_clock) { ?>
function update_clock() {
  $(`div[class~="liveclock"] span`).html(moment().format("LL HH:mm:ss"));
}
var clock_tick = function clock_tick() {
  setInterval('update_clock();', 1000);
}
clock_tick();
<?php } ?>
$(async function() {
<?php
// check the welcome notice
if(!empty($Notification)) {

  // print the welcome notice
  $Notification = (Object) $Notification;
  
  // print this information if the current page is within the range of display
  if(in_array($SITEURL[0], $Notification->section)) {
    
    // set the content of the div
    print "$(`div[class='notification-content']`).html(`{$Notification->content}`);\n";
    
    // show the modal content
    print "$(`div[class~='{$Notification->modal}']`).modal({backdrop: 'static', keyboard: false});\n";

    // print out the function to run this modal notification
    print "{$Notification->function};\n";
  }
}
?>
hL();
<?php if(confirm_url_id(1, 'inventory-details')) { ?>
  var identifyCurrentBranch = () => {
      var site2 = branchID;
      fetchAllProducts(site2);
  }
  identifyCurrentBranch();
<?php } ?>
genIds();
<?php if(confirm_url_id(0, 'dashboard')) { ?>
  await dOC().then((itResp) => {
    if (itResp == 1) {
      let cSt = Cookies.get('offlineSales');
      if(cSt == 'available') {
        syncOfflineData('sales').then((resp) => {
          preloadData('sales').then((res) => {
              preloadData('reports');
              Cookies.set('offlineSales', 'unavailable');
          });
        });
      }
    }
  });
  //dPv('sales').then((res) => {
<?php } ?>
<?php if(confirm_url_id(0, 'settings')) { ?>
// $('textarea[name="terms_and_conditions"]').summernote({
//   // width: 600,
//   // height: 150,
//   // minHeight: 120,
//   // maxHeight: 200,
//   // focus: false
// });
<?php } ?>
});
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('<?= $baseUrl ?>sw.js')
      .then(registration => {
        console.log(`Service Worker registered! Scope: ${registration.scope}`);
      })
      .catch(err => {
        console.log(`Service Worker registration failed: ${err}`);
      });
  });
}
</script>