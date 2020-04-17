<?php
$PAGETITLE = "Expenses";

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// include the important files
require_once "headtags.php";

$session->insightRequest = [];
global $accessObject;
?>
<!-- Page Content-->
<?= connectionLost(); ?>
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
        <?php if($accessObject->hasAccess('expenses_add', 'expenses')) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="javascript:void(0)" class="btn btn-sm add-expense btn-neutral"><i class="fa fa-plus"></i> New Expense</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">
      
      <div class="col-lg-12 col-sm-12">
          <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table data-content="non-filtered" class="table nowrap expensesList">
                      <thead class="thead-light">
                          <tr>
                              <th width="7%">#</th>
                              <th>Date</th>
                              <th>Category</th>
                              <th>Amount</th>
                              <th>Tax</th>
                              <th>Payment Type</th>
                              <th>Description</th>
                              <th>Created By</th>
                              <th width="10%"></th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                </div>
              </div>
          </div>
      </div>     

  </div><!--end row-->
<?php if($accessObject->hasAccess('expenses_add', 'expenses') || $accessObject->hasAccess('expenses_add', 'expenses')) { ?>
<div class="modal fade expensesModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
      <div class="modal-content">
        <?= form_loader(); ?>
          <div class="modal-header">
              <h5 class="modal-title mt-0" id="myLargeModalLabel">Expense </h5>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body slim-scroll show-modal-body" style="padding-top: 0px; max-height: 650px; overflow: scroll; overflow-x: hidden">
            <div class="card mb-0 p-3">
              <form action="<?= $baseUrl ?>api/expensesManagement/manageExpenses" class="expenseForm" method="POST">
                <div class="form-row">
                  <?php if($accessObject->hasAccess('monitoring', 'branches')) { ?>
                  <div class="form-group col-lg-12">
                    <label for="outletId">Select Outlet <span class="required">*</span></label>
                    <select name="outletId" id="outletId" class="form-control selectpicker">
                      <option value="null">--Select Outlet--</option>
                      <?php 
                      // get the list of all outlets
                      $outletsList = $posClass->getAllRows("branches", 
                        "id, branch_name", 
                        "clientId='{$posClass->clientId}' AND status='1'"
                      );
                      // loop through the list of outlets
                      foreach($outletsList as $eachOutlet) {
                        // list the outlets
                        print "<option value=\"{$eachOutlet->id}\">{$eachOutlet->branch_name}</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <?php } ?>
                  <div class="form-group col-lg-12">
                    <label for="date">Date <span class="required">*</span></label>
                    <input value="<?= date("Y-m-d") ?>" autocomplete="Off" type="text" maxlength="10" placeholder="Select Expense Date" name="date" id="date" class="form-control datepicker">
                  </div>
                  <div class="form-group col-lg-12">
                    <label for="date">Expense Category <span class="required">*</span></label>
                    <select name="category" id="category" class="form-control selectpicker">
                      <option value="null">--Select Category--</option>
                      <?php 
                      // get the list of all category
                      $categoryList = $posClass->getAllRows("expenses_category", 
                        "id, name", 
                        "clientId='{$posClass->clientId}' AND status='1'"
                      );
                      // loop through the list of category
                      foreach($categoryList as $eachCategory) {
                        // list the category
                        print "<option value=\"{$eachCategory->id}\">{$eachCategory->name}</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-lg-12">
                    <label for="Amount">Amount <span class="required">*</span></label>
                    <input autocomplete="Off" type="text" maxlength="13" placeholder="Enter Expense Amount" name="amount" id="amount" onkeypress="return isNumber(event)" class="form-control">
                  </div>
                  <div class="form-group col-lg-12">
                    <label for="tax">Tax Amount</label>
                    <input autocomplete="Off" type="text" maxlength="13" placeholder="Expense Tax Amount" name="tax" id="tax" onkeypress="return isNumber(event)" class="form-control">
                  </div>
                  <div class="form-group col-lg-12">
                    <label for="description">Description</label>
                    <textarea name="description" rows="4" id="description" class="form-control"></textarea>
                  </div>
                  <div class="form-group col-lg-12">
                    <div class="text-right">
                      <input type="hidden" name="expenseId" class="expenseId">
                      <button type="submit" class="btn <?=  $clientData->btn_outline; ?>"><i class="fa fa-save"></i> Save</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<?php } ?>
<?php require_once 'foottags.php'; ?>
</body>
</html>