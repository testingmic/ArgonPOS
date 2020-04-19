<?php
// header
$PAGETITLE = "Customers Reports";

// include the important files
require_once "headtags.php";

global $setupInfo, $clientData, $filterPeriod;

$session->reportingCustomerId = null;
$session->productsLimit = 50;

// insight to request for
$session->insightRequest = [
    "productCategoryInsight",
    "customerOrdersInsight", "salesOverview"
];
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
            .customer-summary .card p, .reports-details .card p {
                font-size: 12px;
                /*color: #fff;*/
            }
            .customer-summary .card h3, .reports-details .card h3 {
                color: #fff;
            }
        </style>

        <div class="row reports-details">
            
            <div class="col-lg-12 offline">

              <div class="card">
                <?= connectionLost(); ?>
                <div class="card-body mb-0" style="padding-bottom: 0px" data-report="orders-trend">
                    <div class="row">
                        <div class="col-lg-8 align-self-center">
                            <div class="impressions-data">
                                <h4 class="mt-0 header-title">Orders Trend</h4>
                                <h2 class="mn-3">0 <small>total orders</small></h2>
                            </div>
                        </div>
                        <div class="col-lg-4 text-right orders-loader"></div>
                    </div>
                </div>
                <div class="card-body overflow-hidden p-0">
                    <div class="d-flex mb-0 h-100">
                        <div class="w-100">
                            <div class="apexchart-wrapper">
                                <div id="customer_orders_trend" class="chart-gutters"></div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-12 offline">
                
              <div class="card">
                <?= connectionLost(); ?>
                  <div class="card-body order-list">
                      <h4 class="header-title mt-0 mb-3">Top 30 Best Performing Customers</h4>
                      <div class="table-responsive">
                          <table class="table table-hover text-left custPerformance datatable-buttons">
                              <thead class="thead-light">
                                  <tr>
                                      <th class="border-top-0">#</th>
                                      <th class="border-top-0">Name</th>
                                      <th class="border-top-0">Total Orders</th>
                                      <th class="border-top-0">Orders Amount</th>
                                      <th class="border-top-0">Action</th>
                                  </tr><!--end tr-->
                              </thead>
                              <tbody></tbody>
                          </table> <!--end table-->                                               
                      </div><!--end /div-->
                  </div><!--end card-body-->
              </div><!--end card-->

            </div><!--end col-->

          </div> 


      </div>
    
  </div><!--end row-->
  
  <div class="modal fade attendantHistory" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mt-0">Sales History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="card mb-0 p-3">
                <table class="table table-hover datatable-buttons salesLists">
                      <thead class="thead-light">
                          <tr>
                              <th class="border-top-0">#</th>
                              <th class="border-top-0">Order ID</th>
                              <th class="border-top-0">Fullname</th>
                              <th class="border-top-0">Date</th>
                              <th class="border-top-0">Amount</th>
                          </tr><!--end tr-->
                      </thead>
                      <tbody></tbody>
                  </table>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
<?php require_once 'foottags.php'; ?>
<?php if($session->accountExpired) { ?>
<script>
  $(`select[name="periodSelected"]`).prop('disabled', true);
</script>
<?php } ?>
</body>
</html>