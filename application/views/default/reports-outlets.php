<?php
// header
$PAGETITLE = "Sales Outlets Report";

// include the important files
require_once "headtags.php";

global $setupInfo, $clientData, $filterPeriod;

$session->reportingCustomerId = null;
$session->productsLimit = 50;

// insight to request for
$session->insightRequest = [];
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
  
  <div class="row mb-2 pos-reporting">
      <div class="col-lg-9 col-mb-8"></div>
      <div class="col-lg-3 col-mb-4 mb-2">
          <select class="form-control selectpicker" name="periodSelected">
            <?php foreach($filterPeriod as $key => $value) { ?>
            <option <?= ($session->reportPeriod == $key) ? "selected" : null ?> value="<?= $key ?>"><?= $value ?></option>
            <?php } ?>
          </select>
      </div> <!--end col-->
      <div class="col-lg-1 mb-2 hidden">
          <button class="btn btn-block btn-primary"><i class="fa fa-filter"></i></button>
      </div>
  

      <div class="col-lg-12 col-sm-12">
          

        <style>
            .outlets-summary .card p, .reports-details .card p {
                font-size: 12px;
                /*color: #fff;*/
            }
            .outlets-summary .card h3, .reports-details .card h3 {
                color: #fff;
            }
        </style>
        <div class="row justify-content-center outlets-summary">
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="total-sales">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Total Sales</span>
                        <h3 class="my-3">0</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="average-sales">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Average Sales</span>
                        <h3 class="my-3">0</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="most-performing">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Most Performing Outlet</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body bg-default--> 
                </div><!--end card--> 
            </div> <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="least-performing">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Least Performing Outlet</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

        </div><!--end row-->

        <div class="row reports-details">

            <div class="col-lg-12 branch-performance offline">
                <div class="card">
                    <?= connectionLost(); ?>
                    <div class="card-body table-responsive slim-scroll">
                        <table class="table nowrap branch-overview datatable-buttons">
                            <thead>
                                <th>Name</th>
                                <th>Total Sales</th>
                                <th>Highest Sale</th>
                                <th>Lowest Sale</th>
                                <th>Average Sale</th>
                                <th>Sales Count</th>
                                <th>Sales Per Square Foot</th>
                                <th>Customers Count</th>
                                <th>Average Sales Per Customer</th>
                                <th>Products Count</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
          </div> 


      </div>
    
  </div><!--end row-->

<?php require_once 'foottags.php'; ?>
<?php if($session->accountExpired) { ?>
<script>
  $(`select[name="periodSelected"]`).prop('disabled', true);
</script>
<?php } ?>
</body>
</html>