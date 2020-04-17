<?php
$PAGETITLE = "Expenses Category";

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
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>expenses"><i class="ni ni-ungroup"></i> Expenses</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $PAGETITLE ?></a></li>
            </ol>
          </nav>
        </div>
        <?php if($accessObject->hasAccess('category_add', 'expenses')) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="javascript:void(0)" class="btn btn-sm add-category btn-neutral"><i class="fa fa-plus"></i> New Expense Category</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">
      <style>
        table.dataTable thead tr th {
            word-wrap: break-word!important;
            word-break: break-all!important;
        }
      </style>
      <div class="col-lg-12 col-sm-12">
          <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table width="100%" class="table expenseCategories">
                      <thead class="thead-light">
                        <tr>
                            <th width="7%">#</th>
                            <th width="20%">Category</th>
                            <th width="60%">Category Description</th>
                            <th width="13%"></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                </div>
              </div>
          </div>
      </div>     

  </div><!--end row-->
<?php if($accessObject->hasAccess('category_add', 'expenses') || $accessObject->hasAccess('category_update', 'expenses')) { ?>
<div class="modal fade categoryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
      <div class="modal-content">
        <?= form_loader(); ?>
          <div class="modal-header">
              <h5 class="modal-title mt-0 show-modal-title" id="myLargeModalLabel">Expense Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body show-modal-body" style="padding-top: 0px">
            <div class="card mb-0 p-3">
              <div class="form-row">
                <div class="form-group col-lg-12">
                  <label for="name">Name</label>
                  <input autocomplete="Off" type="text" placeholder="Enter category name" name="name" id="name" class="form-control">
                  <input type="hidden" name="request" value="update">
                  <input type="hidden" name="categoryId" class="categoryId">
                </div>
                <div class="form-group col-lg-12">
                  <label for="description">Description</label>
                  <textarea name="description" rows="6" id="description" class="form-control"></textarea>
                </div>
                <div class="form-group col-lg-12">
                  <div class="text-right">
                    <button type="submit" class="btn <?=  $clientData->btn_outline; ?>"><i class="fa fa-save"></i> Save</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php } ?>
<?php require_once 'foottags.php'; ?>
</body>
</html>