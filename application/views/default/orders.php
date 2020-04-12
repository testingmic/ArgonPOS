<?php
$PAGETITLE = "Orders";
$newItem = "New Order";
$tableClass = "OrdersList";
$sessionName = "OrdersList";
$modalWindow = "ordersModalWindow";

// ensure that the user is logged in
if(!$admin_user->logged_InControlled()) {
    include_once "login.php";
    exit;
}

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

global $accessObject;

// create a new object for the access level
$accessObject->userId = $session->userId;

// ensure the user has the required permissions
if(!$accessObject->hasAccess('view', 'quotes')) {
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
        <?php if(!$session->accountExpired) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="<?= $config->base_url("requests/".substr(strtolower($PAGETITLE), 0, -1)); ?>" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i>  New Order</a>
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
                  <table id="<?= $tableClass ?>" class="table datatable-buttons table-flush nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th><?= substr($PAGETITLE, 0, -1) ?> ID</th>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th><?= substr($PAGETITLE, 0, -1) ?> Value</th>
                            <th>Recorded By</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                  </table>
              </div>
          </div>
      </div>

  </div><!--end row-->
<?php require_once 'foottags.php'; ?>
<script type="text/javascript">
  var sessionName = '<?= $sessionName ?>';
  $('#<?= $tableClass ?>').DataTable();
  listRequests('<?= substr($PAGETITLE, 0, -1) ?>', '<?= $tableClass ?>');
</script>
</body>
</html>