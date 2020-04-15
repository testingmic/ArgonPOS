<?php
$PAGETITLE = "Store Outlets";

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// include the important files
require_once "headtags.php";
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
        <?php if($accessObject->hasAccess('add', 'branches') || $accessObject->hasAccess('update', 'branches')) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="javascript:void(0)" data-toggle="modal" data-target="#newModalWindow" class="btn add-new-modal btn-sm btn-neutral"><i class="fa fa-plus"></i> New Outlet</a>
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
              <div class="card-body table-responsive">
                  <table class="table branchesLists datatable-buttons">
                    <thead class="text-capitalize">
                      <tr>
                        <th>ID</th>
                        <th>Outlet Name</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="15%">Action</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
              </div>
          </div>
      </div>
      

  </div><!--end row-->
  
  <?php if($accessObject->hasAccess('add', 'branches') || $accessObject->hasAccess('update', 'branches')) { ?>
  <div class="modal fade" id="newModalWindow">
    <div class="overlay"></div>
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?= form_loader(); ?>
        <div class="modal-header">
          <h5 class="modal-title">Store Outlet Details</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="card mb-0 p-3">
          <div class="form-result"></div>
            <form autocomplete="Off" class="needs-validation submitThisForm" method="post" action="<?= $config->base_url('api/branchManagment/addBranchRecord'); ?>">
              <div class="form-row">
                <div class="col-md-8 mb-3">
                  <label for="branchName">Outlet Name *</label>
                  <input type="text" class="form-control" id="branchName" name="branchName" placeholder="Outlet name" value="" required="">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="branchType">Outlet Type *</label>
                  <select name="branchType" id="branchType" class="form-control">
                    <option value="Store">Store</option>
                    <option value="Warehouse">Warehouse</option>
                  </select>
                </div>
              </div>
              
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label for="location">Location</label>
                  <input type="text" class="form-control" name="location" id="location" placeholder="Location" value="">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="phone">Phone</label>
                  <input type="text" maxlength="15" class="form-control" name="phone" id="phone" placeholder="Phone" value="">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="">
                </div>
              </div>
              <div class="modal-footer p-0">
                <input type="hidden" name="record_type" value="new-record">
                <input type="hidden" name="branchId" value="null" class="branchId">
                <input type="hidden" name="status" value="1">
                <input type="hidden" name="this-form" value="branches">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button class="btn <?= $clientData->btn_outline; ?> submit-form" type="submit">
                  <i class="fa fa-save"></i> Save Record
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
<?php require_once 'foottags.php'; ?>
</body>
</html>