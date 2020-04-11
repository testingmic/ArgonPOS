<?php
$PAGETITLE = "Customer Report";

global $accessObject;

// set the where
$where = '';
$branchId = '';

$accessObject->userId = $session->userId;

// use the access level for limit contents that is displayed
if(!$accessObject->hasAccess('monitoring', 'branches')) {
    $where = "AND customers.branchId = '{$session->branchId}'";
}
// confirm if a customer id has been parsed
if(confirm_url_id(1)) {
    
    // assign a variable to the customer id
    $customerId = xss_clean($SITEURL[1]);

    // save this customer id in a session
    $session->set_userdata('reportingCustomerId', $customerId);

    $stmt = $pos->prepare("
        SELECT customers.id, customers.customer_id, 
            CONCAT(customers.firstname, ' ', customers.lastname) AS fullname, 
            customers.email AS cemail, customers.phone_1 AS cphone_1, customers.phone_2 AS cphone_2, 
            customers.user_type, users.name AS sales_fullname, customers.branchId
        FROM customers
        LEFT JOIN users ON users.user_id=customers.owner_id
        WHERE 
            customers.status='1' AND customers.customer_id = ? AND 
            customers.clientId = ? {$where}
    ");
    $stmt->execute([$customerId, $session->clientId]);

    $customerDetails = $stmt->fetch(PDO::FETCH_OBJ);
}
// if the customer name was found
if(isset($customerDetails->fullname)) {
    // include the important files
    require_once "headtags.php";
?>
<!-- Header -->
<div class="header <?= $clientData->bg_color ?> pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>"><i class="fas fa-home"></i> Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>analytics"><i class="fa fa-chart-bar"></i> Analytics</a></li>
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
  
  <div class="row mb-2 pos-reporting bg-white">
      <div class="col-lg-3"></div>
      <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
          <div class="form-group">
              <label for="customerBranch">Branch</label>
              <select disabled="" class="form-control selectpicker" name="customerBranch">
                  <?php 
                  $stmt = $pos->prepare("
                      SELECT * FROM 
                          branches
                      WHERE clientId = ?
                  ");
                  $stmt->execute([$session->clientId]);
                  // loop through the list
                  while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
                      print "<option ".(($customerDetails->branchId == $result->id) ? "selected" : null)." value='{$result->id}''>{$result->branch_name}</option>";
                  }
                  ?>
              </select>
          </div>
      </div> <!--end col-->
      <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
          <div class="form-group">
              <label for="employeeId">Select Employee</label>
              <select class="form-control selectpicker" name="employeeId">
                  <?php 
                  $stmt = $pos->prepare("
                      SELECT 
                          CONCAT(customers.firstname, ' ', customers.lastname) AS fullname,
                          customers.customer_id, b.branch_name
                      FROM 
                          customers
                      LEFT JOIN branches b ON b.id = customers.branchId
                      WHERE 
                          customers.status='1' AND customers.clientId = ? {$where}
                  ");
                  $stmt->execute([$session->clientId]);
                  // loop through the list
                  while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
                      print "<option ".(($result->customer_id == $customerId) ? "selected" : null)." value='{$result->customer_id}''>{$result->fullname}</option>";
                  }
                  ?>
              </select>
          </div>
      </div> <!--end col-->
      <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
          <div class="form-group">
              <label for="periodSelect">Filter by Period</label>
              <select class="form-control selectpicker" name="periodSelect">
                <?php foreach($filterPeriod as $key => $value) { ?>
                <option <?= ($session->reportPeriod == $key) ? "selected" : null ?> value="<?= $key ?>"><?= $value ?></option>
                <?php } ?>
              </select>
          </div>
      </div> <!--end col-->

      <div class="col-lg-12">
        
          <div class="row justify-content-center reports-summary">
              <div class="col-md-3">
                  <div class="card report-card" data-report="total-sales">
                      <div class="card-body">
                          <div class="float-right">
                              <i class="dripicons-monitor report-main-icon"></i>
                          </div> 
                          <span class="text-gray">Total Sales</span>
                          <h3 class="my-3">0.00</h3>
                          <p class="mb-0 text-muted text-truncate">
                            <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00%</span> Yesterday
                          </p>
                      </div><!--end card-body--> 
                  </div><!--end card--> 
              </div> <!--end col--> 

              <div class="col-md-3">
                  <div class="card report-card" data-report="average-sales">
                      <div class="card-body">
                          <div class="float-right">
                              <i class="dripicons-clock report-main-icon"></i>
                          </div> 
                          <span class="text-gray">Avg. Sales</span>
                          <h3 class="my-3">0.00</h3>
                          <p class="mb-0 text-muted text-truncate">
                            <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                          </p>
                      </div><!--end card-body--> 
                  </div><!--end card--> 
              </div> <!--end col--> 

              <div class="col-md-3">
                  <div class="card report-card" data-report="highest-sales">
                      <div class="card-body">
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

              <div class="col-md-3">
                  <div class="card report-card" data-report="total-orders">
                      <div class="card-body">
                          <div class="float-right">
                              <i class="dripicons-wallet report-main-icon"></i>
                          </div> 
                          <span class="text-gray">Total Orders</span>
                          <h3 class="my-3">0</h3>
                          <p class="mb-0 text-muted text-truncate">
                            <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00</span> Yesterday
                          </p>
                      </div><!--end card-body--> 
                  </div><!--end card--> 
              </div> <!--end col--> 

          </div><!--end row-->

          <div class="row">

              <div class="col-lg-12">                                                        
                  <div class="card">

                      <div class="card-body"> 
                          <div class="row">
                              <div class="col-md-6">
                                  <h4 class="header-title mt-0">Sales Overview</h4>
                              </div>
                              <div class="col-md-3">
                                  <p data-model="highest-sale" class="mb-0 p-2 bg-soft-success rounded"><b>Highest: </b><span>GH¢0.00</span></p>
                              </div><!--end col--> 
                              <div class="col-md-3">
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
              <div class="col-12">
                  <div class="card">
                      <?= connectionLost(); ?>
                      <div class="attendantHistory">
                          <div class="modal-header">
                              <h5 class="mt-0">Sales History</h5>
                          </div>
                          <div class="modal-body"></div>
                      </div><!-- /.modal -->
                  </div>
              </div>

          </div>

      </div>
    
  </div>



<?php require_once "foottags.php" ?>
<script>
$(function() {
    cusPurHis();
    $(`select[name="periodSelect"]`).on('change', function() {
        let periodSelected = $(this).val();
        cusPurHis(periodSelected);
    });

    $(`select[name="employeeId"`).on('change', function() {
        window.location.href = `${baseUrl}reports/`+$(this).val();
    });
});
</script>
</body>
</html>
<?php } else { ?>
    <?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server'); ?>
<?php } ?>