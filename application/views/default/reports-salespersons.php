<?php
// header
$PAGETITLE = "Sales Personnel Reports";

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
            .reports-summary .card p, .reports-details .card p {
                font-size: 12px;
                /*color: #fff;*/
            }
            .reports-summary .card h3, .reports-details .card h3 {
                color: #fff;
            }
        </style>
       
        <div class="row reports-details">

            <div class="col-lg-12 sales-attendant-performance <?= ($clientData->reports_sales_attendant != "sales-attendant-performance") ? "hidden" : null ?> offline">
                <div class="card">
                  <div class="card-body">
                      <div class="table-responsive">
                          <table width="100%" class="table mb-0 datatable-buttons attendant-performance">
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
            <div class="modal-body table-responsive">
              <div class="card mb-0 p-3">
                <table data-content="non-filtered" class="table nowrap datatable-buttons salesLists" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Transaction ID</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Sales Value</th>
                        </tr>
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