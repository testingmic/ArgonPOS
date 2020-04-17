<?php
// header
$PAGETITLE = "Analytics";

// include the important files
require_once "headtags.php";

global $setupInfo, $clientData, $filterPeriod;

$session->reportingCustomerId = null;
$session->productsLimit = 50;

// insight to request for
$session->insightRequest = [
    "paymentOptionsInsight",
    "actualsCreditInsight", "costSellingProfitInsight",
    "discountEffectInsight", "salesOverview"
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
            .reports-summary .card p, .reports-details .card p {
                font-size: 12px;
                /*color: #fff;*/
            }
            .reports-summary .card h3, .reports-details .card h3 {
                color: #fff;
            }
        </style>
        <div class="row justify-content-center reports-summary">
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="total-sales">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Total Sales</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body bg-default--> 
                </div><!--end card--> 
            </div> <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="average-sales">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Average Transaction Value</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="net-profit-margin">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Net Profit Margin</span>
                        <h3 class="my-3">0</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="break-even-point">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Break Even Point</span>
                        <h3 class="my-3">0</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
        </div><!--end row-->

        <div class="row reports-details">

            <div class="col-lg-8 sales-overview-container">
                <div class="card">

                    <div class="card-body"> 
                        <div class="row justify-content-between">
                            <div class="col-md-4">
                                <h4 class="header-title mt-0">Discount Effect</h4>
                            </div>
                            <div>
                                <p data-model="sales-without-discount" class="mb-0 p-2 rounded">Sales With Discount: <span>GH¢0.00</span></p>
                            </div><!--end col--> 
                            <div>
                                <p data-model="sales-with-discount" class="mb-0 p-2 rounded">Sales Without Discount:<span>GH¢0.00</span></p>
                            </div><!--end col--> 
                        </div>
                        <div class="chart-sales">
                            <div id="sales-overview-chart" class="apex-charts"></div>
                        </div>
                    </div><!--end card-body-->
                    
                </div><!--end card-->
            </div><!--end col-->
            <div class="col-lg-4 offline">
              <div class="card">
                <div class="card-body sales-payment-options">
                    <h4 class="header-title mt-0">Payment Options Summary</h4>  
                    <div class="payment-chart">
                      <div id="payment-options" class="apex-charts"></div>
                    </div> 
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="highest-sales">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Highest Sales</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="gross-profit">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Gross Profit</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="gross-profit-margin">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Gross Profit Margin</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            
            <div class="col-md-6 col-lg-3">
                <div class="card report-card" data-report="net-profit">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Net Profit</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

            <div class="col-md-6 col-lg-4">
                <div class="card report-card" data-report="order-discount">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Order Discount</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-success"><i class="mdi mdi-trending-up"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            
            <div class="col-md-6 col-lg-4">
              <div class="card report-card" data-report="average-unit-per-transaction">
                  <div class="card-body bg-default">
                      <div class="float-right">
                          <i class="report-main-icon"></i>
                      </div> 
                      <span class="text-gray">Average Unit Per Transaction</span>
                      <h3 class="my-3">0.00</h3>
                      <p class="mb-0 text-muted text-truncate">
                        <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                      </p>
                  </div><!--end card-body--> 
              </div><!--end card--> 
            </div> <!--end col-->
            
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" data-report="sales-per-employee">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Sales Per Employee</span>
                        <h3 class="my-3">0.00</h3>
                        <p class="mb-0 text-muted text-truncate">
                          <span class="text-danger"><i class="mdi mdi-trending-down"></i>0.00%</span> Yesterday
                        </p>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            
            <div class="col-lg-12 offline">
              <div class="card">
                <?= connectionLost(); ?>
                  <div class="card-body">
                      <div class="row justify-content-between">
                        <div>
                            <h4 class="header-title mt-0">Paid vs Credit</h4>
                        </div>
                        <div>
                            <p data-model="total-sales" class="mb-0 p-2 rounded"><b>Total Sales: </b><span>GH¢0.00</span></p>
                        </div><!--end col-->
                        <div>
                            <p data-model="total-actual-sales" class="mb-0 p-2 rounded"><b>Paid: </b><span>GH¢0.00</span></p>
                        </div><!--end col--> 
                        <div>
                            <p data-model="total-credit-sales" class="mb-0 p-2 rounded"><b>Credit: </b><span>GH¢0.00</span></p>
                        </div><!--end col--> 
                      </div>
                      <div class="chart-comparison">
                          <div id="sales-comparison" class="apex-charts"></div>
                      </div>                                        
                  </div><!--end card-body-->
              </div><!--end card-->
            </div><!--end col-->
           
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" data-report="sales-per-category">
                    <div class="card-body bg-default" style="padding-left: 10px; padding-right: 10px;">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Sales per Category</span>
                        <h3 class="my-3">0</h3>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

            <div class="col-md-6 col-lg-4">
                <div class="card report-card" data-report="stock-turnover-rate">
                    <div class="card-body bg-default" style="padding-left: 10px; padding-right: 10px;">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Stock Turnover Rate</span>
                        <h3 class="my-3">0</h3>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

            <div class="col-md-6 col-lg-4">
                <div class="card report-card" data-report="gross-margin-return-investment">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Gross Margin Return on Investment </span>
                        <h3 class="my-3">0</h3>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->


            <div class="clearfix"></div>

            <div class="col-lg-12 offline">
              <div class="card">
                  <div class="card-body">
                      <div class="row justify-content-between">
                        <div>
                          <h4 class="header-title mt-0">Products Cost, Sold & Revenue</h4>  
                        </div>
                        <div>
                          
                        </div>
                      </div>
                      <div class="revenue-chart" style="min-height: 380px">
                          <div id="revenue-trend" class="apex-charts"></div>
                      </div>
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