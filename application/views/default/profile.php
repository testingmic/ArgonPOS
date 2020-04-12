<?php
$PAGETITLE = "User Profile";

// include the important files
require_once "headtags.php";

//: query for the user information
$userId = $session->userId;

//: run the query for the user information
$stmt = $pos->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);

//: fetch the data
$userData = $stmt->fetch(PDO::FETCH_OBJ);
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
              <?php if($accessObject->hasAccess('view', 'users')) { ?>
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>users"><i class="fas fa-users"></i> Users</a></li>
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
          	<div class="card-body">
          		<?php if(!$session->accountExpired) { ?>
              	<form autocomplete="Off" class="needs-validation submitThisForm" novalidate="" method="post" action="<?= $config->base_url('aj/userManagement/quickUpdate'); ?>">
              	<?php } else { ?>
              	<form autocomplete="Off" class="needs-validation submitThisForm" method="post" action="javascript:void(0);">
              	<?php } ?>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="fullName">Full Name *</label>
							<input type="text" value="<?= $userData->name ?>" class="form-control" id="fullName" name="fullName" placeholder="Full Name" value="" required="">
						</div>
						<div class="col-md-4 mb-3">
							<label for="access_level">Access Level *</label>
							<select disabled="disabled" name="access_level" id="access_level" class="selectpicker form-control">
								<option value="null">Please select</option>
								<?php
								// filters
								if($accessObject->hasAccess('accesslevel', 'users')) {
									$notIn = "1";
								} else {
									$notIn = "id NOT IN (1, 2)";
								}
								// run the query
								$stmt = $pos->prepare("SELECT id, access_name FROM `access_levels` WHERE $notIn");
								$stmt->execute();
								while($al = $stmt->fetch(PDO::FETCH_OBJ)) {
									?>
									<option <?= ($userData->access_level == $al->id) ? "selected" : null ?> data-name="<?= $al->access_name; ?>" value="<?= $al->id ?>"><?= $al->access_name; ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="col-md-4 mb-3">
							<label for="gender">Gender *</label>
							<select name="gender" id="gender" class="form-control selectpicker">
								<option value="null">Please Select</option>
								<option <?= ($userData->gender == "Male") ? "selected" : null; ?> value="Male">Male</option>
								<option <?= ($userData->gender == "Female") ? "selected" : null; ?> value="Female">Female</option>
							</select>
						</div>
					</div>
					
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="phone">Phone *</label>
							<input type="text" value="<?= $userData->phone ?>" class="form-control" name="phone" id="phone" placeholder="Phone" value="">
						</div>
						<div class="col-md-4 mb-3">
							<label for="email">Email *</label>
							<input type="email" value="<?= $userData->email ?>" class="form-control" name="email" disabled id="email" placeholder="Email" value="">
						</div>
						<div class="col-md-4 mb-3">
							<label for="branchId">User Branch</label>
							<select disabled="disabled" name="branchId" id="branchId" class="form-control selectpicker">
								<option value="null">Please select</option>
								<?php
								$branches = $pos->prepare("SELECT id, branch_name FROM `branches`");
								$branches->execute();
								while($branch = $branches->fetch(PDO::FETCH_OBJ)) {
									?>
									<option <?= ($userData->branchId == $branch->id) ? "selected" : null ?> data-name="<?= $branch->branch_name; ?>" value="<?= $branch->id ?>"><?= $branch->branch_name; ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<?php if(!$session->accountExpired) { ?>
					<div class="modal-footer">
						<input type="hidden" name="this-form" value="users">
						<input type="hidden" name="userId" value="<?= $userData->user_id ?>" class="userId">
						<button class="btn <?= $clientData->btn_outline ?> submit-form" type="submit"><i class="fa fa-save"></i> Save Record</button>
					</div>
					<?php } ?>
				</form>
			</div>
          </div>
      </div>
      

  </div><!--end row--> 


<?php require_once 'foottags.php'; ?>
</body>
</html>