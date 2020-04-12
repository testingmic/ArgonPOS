<?php
$PAGETITLE = "User Login History";

// include the important files
require_once "headtags.php";

global $accessObject, $posClass;

$accessChecker = $accessObject->hasAccess('inventory_branches', 'products');
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
              <?php if(!$session->accountExpired) { ?>
	              <?php if($accessObject->hasAccess('view', 'users')) { ?>
	              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>users"><i class="fas fa-users"></i> Users</a></li>
	          	  <?php } ?>
          		<?php } ?>
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

      <div class="col-lg-12 col-sm-12">
          <div class="card">
              	<div class="card-body table-responsive">
					<table class="table datatable-buttons" width="100%">
						<thead class="text-capitalize">
							<tr class="text-left">
								<th>#</th>
								<th>Name</th>
								<th>Username</th>
								<th>IP Address</th>
								<th>Browser Details</th>
								<th>Login Date</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							// where clause setting
							$whereClause = "a.clientId = '{$posClass->clientId}'";
							$row = 0;

							if(!$accessChecker) {
							    $whereClause .= " AND a.branchId = '{$session->branchId}' && a.user_id='{$session->userId}'";
							}

							// run the query
							$stmt = $pos->prepare("
								SELECT 
								a.*, b.name, b.login AS username, c.branch_name
								FROM users_login_history a
								LEFT JOIN users b ON a.user_id = b.user_id
								LEFT JOIN branches c ON c.id = a.branchId
								WHERE $whereClause ORDER BY a.id DESC LIMIT 200
							");
							$stmt->execute();
							// using the while loop to get the records
							while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
								// increment
								$row++;
								
								// print the records
								print "<tr>";
								print "<td>{$row}</td>";
								print "<td>{$result->name}
									<br><small class='text-white badge badge-primary'>{$result->branch_name}</small></td>";
								print "<td>{$result->username}</td>";
								print "<td>{$result->log_ipaddress}</td>";
								print "<td>{$result->log_browser}</td>";
								print "<td>{$result->date_logged}</td>";
								print "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
          </div>
      </div>
  </div><!--end row-->
<?php require_once 'foottags.php'; ?>
</body>
</html>