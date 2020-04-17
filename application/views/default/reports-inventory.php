<?php
// header
$PAGETITLE = "Inventory Reports";

// include the important files
require_once "headtags.php";

global $setupInfo, $clientData, $filterPeriod;

$session->reportingCustomerId = null;
$session->productsLimit = 500;

// insight to request for
$session->insightRequest = [
    "productCategoryInsight", "productsPerformanceInsight",
    "costSellingProfitInsight", "salesOverview"
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
       

        <div class="row reports-details">

            
            <div class="col-lg-12 offline">
              <div class="card">
                  <div class="card-body">
                      <div class="table-responsive slim-scroll">
                          <table width="100%" class="table mb-0 datatable-buttons products-performance">
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