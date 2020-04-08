<?php
$PAGETITLE = "User Management";

// call the access object
global $accessObject;

// ensure that the user is logged in
if(!$admin_user->logged_InControlled()) {
  include_once "login.php";
  exit;
}

// create a new object for the access level
$accessObject->userId = $session->userId;

//: check online status
if(isset($_POST["onlineCheck"]) && confirm_url_id(1, 'onlineCheck')) {
    print 1;
    exit;
}

// run this page if the user has the required permissions
if($accessObject->hasAccess('view', 'users')) {

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
        <?php if($accessObject->hasAccess('add', 'users') || $accessObject->hasAccess('update', 'users')) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="javascript:void(0)" data-toggle="modal" data-target="#newModalWindow" class="btn add-new-modal btn-sm btn-neutral"><i class="fa fa-plus"></i> New User</a>
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
              	<?php if($accessObject->hasAccess('view', 'users')) { ?>
				<div class="card-body table-responsive">
					<table class="table usersAccounts datatable-buttons" width="100%">
						<thead class="text-capitalize">
							<tr>
								<th></th>
								<th width="18%">Name</th>
								<th>Branch</th>
								<th class="text-center">Access Level</th>
								<th class="text-center">Phone</th>
								<th>Email</th>
								<th>Registered</th>
								<th class="text-center" width="15%">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<?php } ?>
          </div>
      </div>
      

  </div><!--end row-->
  
  <?php if($accessObject->hasAccess('add', 'users') || $accessObject->hasAccess('update', 'users')) { ?>
	<div class="modal fade" id="newModalWindow">
		<div class="overlay"></div>
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">User Details</h5>
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="form-result"></div>
					<form autocomplete="Off" class="needs-validation submitThisForm" novalidate="" method="post" action="<?= $config->base_url('ajax/userManagement/addUserRecord'); ?>">
						<div class="form-row">
							<div class="col-md-4 mb-3">
								<label for="fullName">Full Name *</label>
								<input type="text" class="form-control" id="fullName" name="fullName" placeholder="Full Name" value="" required="">
							</div>
							<div class="col-md-4 mb-3">
								<label for="access_level">Access Level *</label>
								<select name="access_level" id="access_level" class="selectpicker form-control">
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
										<option data-name="<?= $al->access_name; ?>" value="<?= $al->id ?>"><?= $al->access_name; ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-md-4 mb-3">
								<label for="gender">Gender *</label>
								<select name="gender" id="gender" class="form-control selectpicker">
									<option value="null">Please Select</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-4 mb-3">
								<label for="phone">Phone *</label>
								<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="">
							</div>
							<div class="col-md-4 mb-3">
								<label for="email">Email *</label>
								<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="">
							</div>
							<div class="col-md-4 mb-3">
								<label for="branchId">User Branch</label>
								<select name="branchId" id="branchId" class="form-control selectpicker">
									<option value="null">Please select</option>
									<?php
									$branches = $pos->prepare("SELECT id, branch_name FROM `branches`");
									$branches->execute();
									while($branch = $branches->fetch(PDO::FETCH_OBJ)) {
										?>
										<option data-name="<?= $branch->branch_name; ?>" value="<?= $branch->id ?>"><?= $branch->branch_name; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-lg-4 col-md-6">
								<label for="cost" class="text-primary-light">Daily Target</label>
                              	<div class="input-group">
                                  <div class="input-group-prepend"><span class="input-group-text">GH&cent;</span></div>
                                  <input type="number" step="1" value="" class="form-control" name="daily_target">
                              	</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<label for="cost" class="text-primary-light">Weekly Target</label>
                              	<div class="input-group">
                                  <div class="input-group-prepend"><span class="input-group-text">GH&cent;</span></div>
                                  <input type="number" step="1" value="" class="form-control" name="weekly_target">
                              	</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<label for="cost" class="text-primary-light">Monthly Target</label>
                              	<div class="input-group">
                                  <div class="input-group-prepend"><span class="input-group-text">GH&cent;</span></div>
                                  <input type="number" step="1" value="" class="form-control" name="monthly_target">
                              	</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="this-form" value="users">
							<input type="hidden" name="record_type" value="new-record">
							<input type="hidden" name="userId" value="<?= random_string('alnum', 15) ?>" class="userId">
							<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
							<button class="btn btn-primary submit-form" type="submit">
								<i class="fa fa-save"></i> Save Record
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
  


<?php require_once 'foottags.php'; ?>
<script>
  $(function() {
    hideLoader();
  })
</script>
</body>
</html>
<?php 
} else {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
}
?>