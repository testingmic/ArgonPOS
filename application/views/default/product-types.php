<?php
$PAGETITLE = "Product Types";

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// include the important files
require_once "headtags.php";

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
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/inventory"><i class="ni ni-ungroup"></i> Inventory</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $PAGETITLE ?></a></li>
            </ol>
          </nav>
        </div>
        <?php if($accessObject->hasAccess('category_add', 'products')) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="javascript:void(0)" class="btn btn-sm add-category btn-neutral"><i class="fa fa-plus"></i> New Product Type</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">
      
      <div class="col-lg-12 col-sm-12 productsList">
          <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table data-content="non-filtered" class="table nowrap datatable-buttons productsList" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead class="thead-light">
                          <tr>
                              <th width="7%">#</th>
                              <th>Category ID</th>
                              <th>Name</th>
                              <th>Products Count</th>
                              <th width="10%">Action</th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                </div>
              </div>
          </div>
      </div>     

  </div><!--end row-->
<?php if($accessObject->hasAccess('category_add', 'products') || $accessObject->hasAccess('category_edit', 'products')) { ?>
<div class="modal fade categoryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
        <?= form_loader(); ?>
          <div class="modal-header">
              <h5 class="modal-title mt-0 show-modal-title" id="myLargeModalLabel">Product Categories</h5>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body show-modal-body" style="padding-top: 0px">
            <div class="form-row">
              <div class="form-group col-lg-12">
                <label for="category_name">Name</label>
                <input type="text" placeholder="Enter Product Type name" name="category_name" id="categoryModal" class="form-control">
                <input type="hidden" name="request" value="update">
                <input type="hidden" name="categoryId" class="categoryId">
              </div>
              <div class="form-group col-lg-12">
                <div class="text-right">
                  <button type="submit" class="btn <?=  $clientData->btn_outline; ?>"><i class="fa fa-save"></i> Save</button>
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