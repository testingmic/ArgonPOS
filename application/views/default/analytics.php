<?php
$PAGETITLE = "Analytics";

// include the important files
require_once "headtags.php";
?>
<!-- Page Content-->
<!-- Header -->
<div class="header bg-primary pb-6">
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
          <select class="form-control selectpicker" name="periodSelect">
              <option <?= ($session->reportPeriod == "today") ? "selected" : null ?> value="today">Today</option>
              <option <?= ($session->reportPeriod == "this-week") ? "selected" : null ?> value="this-week">This Week</option>
              <option <?= ($session->reportPeriod == "this-month") ? "selected" : null ?> value="this-month">This Month</option>
              <option <?= ($session->reportPeriod == "this-year") ? "selected" : null ?> value="this-year">This Year</option>
          </select>
      </div> <!--end col-->
      <div class="col-lg-1 mb-2 hidden">
          <button class="btn btn-block btn-primary"><i class="fa fa-filter"></i></button>
      </div>
  

      <div class="col-lg-12 col-sm-12">
          

        <style>
            .reports-summary .card p, .reports-details .card p {
                font-size: 12px;
                color: #fff;
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
            
            <div class="col-lg-4 offline">

              <div class="card">
                <?= connectionLost(); ?>
                <div class="card-body mb-0" style="padding-bottom: 0px" data-report="orders-trend">
                    <div class="row">                                            
                        <div class="col-lg-8 align-self-center">
                            <div class="impressions-data">
                                <h4 class="mt-0 header-title">Orders Trend</h4>
                                <h3 class="mn-3">0 <small>total orders</small></h3>
                            </div>
                        </div>
                        <div class="col-lg-4 text-right orders-loader"></div>
                    </div>
                </div>
                <div class="card-body overflow-hidden p-0">
                    <div class="d-flex mb-0 h-100">
                        <div class="w-100">
                            <div class="apexchart-wrapper">
                                <div id="dash_spark_1" class="chart-gutters"></div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>

            <div class="col-lg-8 offline">
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
                <div class="card report-card" data-report="customers-retention-rate">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Customer Retention Rate</span>
                        <h3 class="my-3">0.00</h3>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->
            
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" data-report="sell-through-percentage">
                    <div class="card-body bg-default" style="padding-left: 10px; padding-right: 10px;">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Sell Through Percentage</span>
                        <h3 class="my-3">0.00</h3>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

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

            <div class="col-md-6 col-lg-4">
                <div class="card report-card" data-report="year-over-year-growth">
                    <div class="card-body bg-default">
                        <div class="float-right">
                            <i class="report-main-icon"></i>
                        </div> 
                        <span class="text-gray">Year over Year Growth</span>
                        <h3 class="my-3">0</h3>
                    </div><!--end card-body--> 
                </div><!--end card--> 
            </div> <!--end col-->

            <div class="clearfix"></div>

            <div class="col-lg-12 sales-attendant-performance <?= ($clientData->reports_sales_attendant != "sales-attendant-performance") ? "hidden" : null ?> offline">
                <div class="card">
                  <div class="card-body">
                      <div class="row justify-content-between">
                        <div>
                          <h4 class="header-title mt-0">Top Sales People</h4>  
                        </div>
                      </div>
                      <div class="mt-4 table-responsive">
                          <table width="100%" class="table mb-0 attendant-performance">
                              <thead class="thead-light">
                              <tr>
                                  <th width="30%">Name</th>
                                  <th>Revenue</th>
                                  <th>Sales Count</th>
                                  <th>Avg. Sale Value</th>
                                  <th>Target</th>
                                  <th>% of Target Met</th>
                                  <th>Items Sold</th>
                                  <th>Avg. Items per Sale</th>
                              </tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                  </div>
              </div>
            </div>
            <div class="col-lg-4 offline team-performance <?= ($clientData->reports_sales_attendant != "team-performance") ? "hidden" : null ?>">
              <div class="card">
                  <div class="card-body">
                      <div class="row justify-content-between">
                        <div>
                          <h4 class="header-title mt-0">Sales Attendant Performance (Sales)</h4>  
                        </div>
                      </div>
                      <div class="attendant-chart">
                          <div id="attendant-performance" class="apex-charts"></div>
                      </div>  
                  </div>
              </div>
            </div>
            
            <div class="col-lg-12 offline">
              <div class="card">
                  <div class="card-body">
                      <div class="row justify-content-between">
                        <div>
                          <h4 class="header-title mt-0">Top 50 Best Products Performance (Sales)</h4>  
                        </div>
                        <div>
                          
                        </div>
                      </div>
                      <div class="table-responsive">
                          <table width="100%" class="table mb-0 products-performance">
                              <thead class="thead-light">
                              <tr>
                                  <th>#</th>
                                  <th width="30%">Name</th>
                                  <th>Orders</th>
                                  <th>Quantity Sold</th>
                                  <th>Cost</th>
                                  <th>Revenue</th>
                                  <th>Profit</th>
                              </tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                    </div>
              </div>
            </div>

            <div class="col-lg-8 offline">
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

            <div class="col-lg-4 offline">
              <div class="card">
                <div class="card-body sales-category-options">
                    <h4 class="header-title mt-0">Sales Per Products Category</h4>  
                    <div class="category-chart">
                      <div id="category-options" class="apex-charts"></div>
                    </div> 
                </div>
              </div>
            </div>

            <div class="col-lg-12 branch-performance offline">
                <div class="card">
                    <div class="card-body table-responsive">
                        <h4 class="header-title mt-0">Branch Performance Overview</h4>
                        <table class="table nowrap branch-overview datatable-buttons">
                            <thead>
                                <th>Name</th>
                                <th>Total Sales</th>
                                <th>Highest Sale</th>
                                <th>Lowest Sale</th>
                                <th>Average Sale</th>
                                <th>Orders</th>
                                <th>Sales Per Square Foot</th>
                            </thead>
                            <tbody></tbody>
                        </table>
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
                                      <th class="border-top-0">Balance</th>
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
            <div class="modal-body"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<?php require_once 'foottags.php'; ?>

<script>
  $(async function() {
    $(`table[class~="salesLists"]`).dataTable();
    $(`table[class~="inventoryLists"]`).dataTable();
    $(`table[class~="thresholdLists"]`).dataTable();
    await doOnlineCheck().then((itResp) => {
        if(itResp == 1) {
            noInternet = false;
            $(`div[class="connection"]`).css('display','none');
        } else {
            noInternet = true;
            $(`div[class~="offline"] div[class="card"]`).css('min-height','200px');
            $(`div[class="connection"]`).css('display','block');
        }
    }).catch((err) => {
        noInternet = true;
        $(`div[class~="offline"] div[class="card"]`).css('min-height','200px');
        $(`div[class="connection"]`).css('display','block');
    });
  });
</script>

</body>
</html>