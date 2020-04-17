<?php
$PAGETITLE = "Customer Report";

global $accessObject;

// set the where
$where = '';
$branchId = '';

$accessObject->userId = $session->userId;

// use the access level for limit contents that is displayed
if(!$accessObject->hasAccess('monitoring', 'branches')) {
    $where = "AND c.branchId = '{$session->branchId}'";
}
// confirm if a customer id has been parsed
if(confirm_url_id(1)) {
    
    // assign a variable to the customer id
    $customerId = xss_clean($SITEURL[1]);

    // save this customer id in a session
    $session->set_userdata('reportingCustomerId', $customerId);

    $stmt = $pos->prepare("
        SELECT c.id, c.customer_id, c.branchId,
            c.title, c.firstname, c.lastname,
            CONCAT(c.firstname, ' ', c.lastname) AS fullname, 
            c.email, c.phone_1, c.phone_2, 
            c.description, c.date_log, c.residence,
            c.user_type, users.name AS sales_fullname, c.branchId
        FROM customers c
        LEFT JOIN users ON users.user_id=c.owner_id
        WHERE 
            c.status='1' AND c.customer_id = ? AND 
            c.clientId = ? {$where}
    ");
    $stmt->execute([$customerId, $session->clientId]);

    $customerDetails = $stmt->fetch(PDO::FETCH_OBJ);
}
// if the customer name was found
if(isset($customerDetails->fullname)) {
    // include the important files
    require_once "headtags.php";

    // insight to request for
    $session->insightRequest = [
      'discountEffectInsight', 'productsPerformanceInsight',
      'paymentOptionsInsight', 'salesOverview'
    ];

    //: set the customer branchId in a session
    $session->customerBranchId = $customerDetails->branchId;
?>

  <div class="header pb-6 d-flex align-items-center" style="min-height: 350px; background-image: url(<?= $baseUrl ?>assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default customersList opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
      <div class="row" style="width: 100%">
        <div class="col-lg-7 col-md-10">
          <h1 class="display-2 text-white"><?= $customerDetails->fullname ?></h1>
          <?php if(!$session->accountExpired && $customerId != 'WalkIn') { ?>
            <?php if($accessObject->hasAccess('update', 'customers')) { ?>
              <a href="javascript:void(0);" data-value="<?= $customerDetails->customer_id ?>" class="edit-customer btn btn-neutral">Edit Customer</a>
              <a href="" data-id="<?= $customerDetails->customer_id ?>" data-info='<?= json_encode($customerDetails) ?>'></a>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Page content -->
  <div class="container-fluid mt--6">
    <div class="row">

      <div class="col-xl-4 order-xl-2">
        
        <div class="form-group text-white">
          <label for="periodSelect">Filter Record By Period</label>
          <select class="form-control selectpicker" name="periodSelected">
            <?php foreach($filterPeriod as $key => $value) { ?>
            <option <?= ($session->reportPeriod == $key) ? "selected" : null ?> value="<?= $key ?>"><?= $value ?></option>
            <?php } ?>
          </select>
        </div> <!--end col-->
        <div class="card card-profile">
          <img src="<?= $baseUrl ?>assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
          <div class="row justify-content-center">
            <div class="col-lg-3 order-lg-2">
              <div class="card-profile-image">
                <a href="#">
                  <img src="<?= $baseUrl ?>assets/images/avatar.jpg" class="rounded-circle">
                </a>
              </div>
            </div>
          </div>
          <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
            <div class="d-flex justify-content-between">
              <a href="javascript:void(0)" class="btn btn-sm btn-info mr-4" data-toggle="tooltip" title="Coming Soon">Connect</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default float-right" data-toggle="tooltip" title="Coming Soon">Message</a>
            </div>
          </div>
          <div class="card-body pt-0">
            <div class="row">
              <div class="col">
                <div class="card-profile-stats d-flex justify-content-center">
                  <div>
                    <span class="heading">Date Joined</span>
                    <span class="description"><?= $customerDetails->date_log ?></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <div class="h5 font-weight-300">
                <i class="fa fa-location-arrow mr-2"></i><?= $customerDetails->residence ?>
              </div>
              <div class="h5 font-weight-300">
                <i class="fa fa-envelope mr-2"></i><?= $customerDetails->email ?>
              </div>
              <div class="h5 font-weight-300">
                <i class="fa fa-phone mr-2"></i><?= $customerDetails->phone_1 ?>
              </div>
              <div class="h5 mt-4">
                <i class="ni business_briefcase-24 mr-2"></i>
                <?= $customerDetails->description ?>
              </div>
            </div>
          </div>
        </div>
        <div class="card report-card" data-report="total-orders">
            <div class="card-body border bg-default">
                <div class="float-right">
                    <i class="dripicons-wallet report-main-icon"></i>
                </div> 
                <span class="text-gray">Total Orders</span>
                <h3 class="my-3 text-white">0</h3>
                <p class="mb-0 text-muted text-truncate">
                  <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00</span> Yesterday
                </p>
            </div><!--end card-body--> 
        </div><!--end card--> 
        <!-- Progress track -->
        <div class="card">
          <!-- Card header -->
          <div class="card-header">
            <!-- Title -->
            <h5 class="h3 mb-0">Payment Options</h5>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <!-- List group -->
            <div class="payment-chart">
              <div id="payment-options" class="apex-charts"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-8 order-xl-1">
        <div class="row">
          <div class="col-lg-6">
            <div class="card bg-default border-0">
              <!-- Card body -->
              <div class="card-body" data-report="total-sales">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Purchases</h5>
                    <h3 class="my-3 text-white">0.00</h3>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                      <i class="ni ni-active-40"></i>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0 text-sm text-whites text-truncate">
                  <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00%</span> Yesterday
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card bg-default border-0" data-report="average-sales">
              <!-- Card body -->
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0 text-white">Average Purchase</h5>
                    <h3 class="my-3 text-white">0.00</h3>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                      <i class="ni ni-spaceship"></i>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0 text-sm text-truncate">
                  <span class="text-white mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                  <span class="text-nowrap text-light">Since last month</span>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="card pos-reporting">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="mb-0">Purchase Insight</h3>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row justify-content-start reports-summary">

              <div class="col-md-6">
                  <div class="card report-card" data-report="highest-sales">
                      <div class="card-body border">
                          <div class="float-right">
                              <i class="dripicons-meter report-main-icon"></i>
                          </div> 
                          <span class="text-gray">Highest Sales</span>
                          <h3 class="my-3">0.00</h3>
                          <p class="mb-0 text-muted text-truncate">
                            <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                          </p>
                      </div><!--end card-body--> 
                  </div><!--end card--> 
              </div> <!--end col-->

              <div class="col-md-6">
                  <div class="card report-card" data-report="order-discount">
                      <div class="card-body border">
                          <div class="float-right">
                              <i class="dripicons-meter report-main-icon"></i>
                          </div> 
                          <span class="text-gray">Discounts Enjoyed</span>
                          <h3 class="my-3">0.00</h3>
                          <p class="mb-0 text-muted text-truncate">
                            <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                          </p>
                      </div><!--end card-body--> 
                  </div><!--end card--> 
              </div> <!--end col-->
            </div>
             <div class="row">

              <div class="col-lg-12">                                                        
                  <div class="card">

                      <div class="card-body"> 
                          <div class="row">
                              <div class="col-md-4">
                                  <h4 class="header-title mt-0">Sales Overview</h4>
                              </div>
                              <div class="col-md-4">
                                  <p data-model="highest-sale" class="mb-0 p-2 bg-soft-success rounded"><b>Highest: </b><span>GH¢0.00</span></p>
                              </div><!--end col--> 
                              <div class="col-md-4">
                                  <p data-model="lowest-sale" class="mb-0 p-2 bg-soft-danger rounded"><b>Lowest: </b><span>GH¢0.00</span></p>
                              </div><!--end col--> 
                          </div>                  
                          <div class="chart-sales">
                              <div id="sales-overview-chart" class="apex-charts"></div>
                          </div>
                      </div><!--end card-body-->
                      
                  </div><!--end card-->
              </div><!--end col-->
              <a href="javascript:void(0)" data-value="<?= $customerId ?>" data-value="<?= $customerDetails->fullname ?>" data-record="customer" class="view-user-sales"></a>
              <div class="col-lg-12">
                  <div class="card">
                      <?= connectionLost(); ?>
                      <div class="attendantHistory">
                          <div class="modal-header">
                              <h5 class="mt-0">Sales History</h5>
                          </div>
                          <table data-content="non-filtered" class="table nowrap datatable-buttons salesLists" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Sales Value</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                      </div><!-- /.modal -->
                  </div>
              </div>

              <div class="col-lg-12">
                <div class="card">
                  <div class="modal-header">
                      <h5 class="mt-0">Most Purchased Products</h5>
                  </div>
                  <div class="table-responsive slim-scroll">
                    <table width="100%" class="table mb-0 products-performance">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th width="30%">Name</th>
                            <th>Sale Count</th>
                            <th>Items Sold</th>
                            <th>Items Sold Per Day</th>
                            <th>Closing Inventory</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Profit</th>
                            <th>Return Count</th>
                            <th>Returns %</th>
                            <th>Created</th>
                            <th>First Sale</th>
                            <th>Last Sale</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>

          </div>

        </div>
      </div>
    </div>
  </div>
<?php require_once 'foottags.php'; ?>
<script>
$(function() {
    cusPurHis();
    $(`select[name="periodSelected"]`).on('change', function() {
        let periodSelected = $(this).val();
        cusPurHis(periodSelected);
    });
    $(`select[name="employeeId"`).on('change', function() {
        window.location.href = `${baseUrl}customer-detail/`+$(this).val();
    });
    <?php if(!$session->accountExpired && $customerId != 'WalkIn') { ?>
    $(`div[class="main-content"]`).on('click', `a[class~="edit-customer"]`, function(e) {
        let userId = $(this).data('value');
            userData = $(`a[data-id='${userId}']`).data('info');
        $(`div[id="newCustomerModal"]`).modal('show');
        $(`div[id="newCustomerModal"] h5[class="modal-title"]`).html('Update Customer');
        $(`div[id="newCustomerModal"] select[name="nc_title"]`).val(userData.title).change();
        $(`div[id="newCustomerModal"] input[name="nc_firstname"]`).val(userData.firstname);
        $(`div[id="newCustomerModal"] input[name="nc_lastname"]`).val(userData.lastname);
        $(`div[id="newCustomerModal"] input[name="nc_email"]`).val(userData.email);
        $(`div[id="newCustomerModal"] input[name="nc_contact"]`).val(userData.phone_1);
        $(`div[id="newCustomerModal"] input[name="residence"]`).val(userData.residence);
        $(`div[id="newCustomerModal"] input[name="customer_id"]`).val(userId);
        $(`div[id="newCustomerModal"] input[name="request"]`).val('update-record');
    });
    <?php } elseif($session->accountExpired) { ?>
    $(`select[name="periodSelected"]`).prop('disabled', true);
    <?php } ?>
});
</script>
</body>
</html>
<?php } else { ?>
    <?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server'); ?>
<?php } ?>