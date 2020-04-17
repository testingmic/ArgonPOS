<?php
$PAGETITLE = "Customers List";

global $session;

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}
// include the important files
require_once "headtags.php";
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
        <?php if($accessObject->hasAccess('add', 'customers')) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="javascript:void(0)" class="btn btn-sm add-customer btn-neutral"><i class="fa fa-plus"></i> New Customer</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">
      
      <div class="col-lg-12 col-sm-12 customersList">
          <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table data-content="non-filtered" class="table nowrap datatable-buttons customersList" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead class="thead-light">
                          <tr>
                              <th width="7%">#</th>
                              <th>Fullname</th>
                              <th>Email</th>
                              <th>Contact</th>
                              <th>Log Date</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                </div>
              </div>
          </div>
      </div>

  </div><!--end row-->
<?php require_once 'foottags.php'; ?>
</body>
</html>