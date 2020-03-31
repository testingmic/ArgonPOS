<?php
$PAGETITLE = "Sales History";

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
  
  <div class="row">
      
      <div class="col-lg-9 col-mb-8"></div>
      <!-- <div class="col-lg-3 hidden">
          <div class="form-group">
              <select class="form-control selectpicker" name="customerBranch">
                  <?php 
                  //$stmt = $pos->prepare("SELECT * FROM  branches WHERE clientId = ? ");
                  //$stmt->execute([$session->clientId]);
                  // loop through the list
                  //while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
                     // print "<option ".(($session->branchId == $result->id) ? "selected" : null)." value='{$result->id}''>{$result->branch_name}</option>";
                  //}
                  ?>
              </select>
          </div>
      </div> --> <!--end col-->
      <div class="col-lg-3 col-mb-4 mb-2">
          <select class="form-control selectpicker" name="periodSelected">
              <option value="today">Today</option>
              <option <?= ($session->dashboardPeriod == "thisWk") ? "selected" : null ?> value="thisWk">
                  This Week
              </option>
              <option <?= ($session->dashboardPeriod == "thisMonth") ? "selected" : null ?> value="thisMonth">
                  This Month
              </option>
              <option <?= ($session->dashboardPeriod == "thisYr") ? "selected" : null ?> value="thisYr">
                  This Year
              </option>
          </select>
      </div> <!--end col-->
      <div class="col-lg-1 mb-2 hidden">
          <button class="btn btn-block btn-primary"><i class="fa fa-filter"></i></button>
      </div>
  

      <div class="col-lg-12 col-sm-12 overallSalesHistory">
          <div class="card">
              <div class="card-body table-responsive">
                  <table data-content="non-filtered" class="table nowrap datatable-buttons salesLists" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead class="thead-light">
                          <tr>
                              <th>ID</th>
                              <th>Transaction ID</th>
                              <th>Customer</th>
                              <th>Contact</th>
                              <th>Date</th>
                              <th>Sales Value</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
              </div>
          </div>
      </div>
      

  </div><!--end row-->

  


<?php require_once 'foottags.php'; ?>

<script>
  $(function() {
        hideLoader();
    });
</script>

</body>
</html>