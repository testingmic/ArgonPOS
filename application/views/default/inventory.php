<?php
$PAGETITLE = "Inventory Management";
$productsObj = load_class("Products", "controllers");

global $accessObject;

// create a new object for the access level
$accessObject->userId = $session->userId;

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

$categories = $productsObj->getCategories();
$defaultImg = $productsObj->getDefaultImage();

// ensure the user has the required permissions
if(!$accessObject->hasAccess('inventory_branches', 'products')) {
    $allBranches = $productsObj->getAllBranches($session->branchId);
} else {
    $allBranches = $productsObj->getAllBranches();
}
$options = "";

// include the important files
require_once "headtags.php";
?>
<?= connectionLost(); ?>
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
  
  <div class="row">
      
    <?php if ($allBranches != false) {

    foreach ($allBranches as $branch) { ?>

    <?php $options .= "<option value=\"{$branch->id}\">{$branch->branch_name}</option>"; ?>

    <div class="col-md-4 branch-info">
        <a href="<?= $config->base_url("inventory/inventory-details/{$branch->id}") ?>">
            <div class="card report-card">
                <div class="card-body">
                    <div class="float-right text-right">
                        <i class="fa <?= ($branch->branch_type == 'Store') ? "fa-map-marker-outline" : "fa-sitemap" ?> report-main-icon"></i><br>
                        <strong><?= ($branch->branch_type == 'Store') ? "<span class='badge badge-info'>Store</span>" : "<span class='badge badge-secondary'>Warehouse</span>" ?></strong>
                    </div> 
                    <span class="badge badge-<?= ($branch->status) ? "success" : "danger" ?>"><?= ($branch->status == 1) ? "Active" : "Inactive" ?></span>
                    <h5 class="my-3 total-sales"><?= $branch->branch_name ?></h5>
                    <p class="mb-0 text-muted text-truncate total-sales-trend">
                        <?= $productsObj->getCountdata("products", "branchId = '{$branch->id}'") ?> products
                    </p>
                </div><!--end card-body--> 
            </div><!--end card-->
        </a>
    </div><!--end col--> 

    <?php } } ?>
      
  </div><!--end row-->
<?php require_once 'foottags.php'; ?>
</body>
</html>