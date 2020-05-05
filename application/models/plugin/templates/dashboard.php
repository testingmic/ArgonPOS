<?php
// check if the user has already validated their Api Key
ob_start();
$verifiedKey = $this->verifyKey();
?>

<?= $this->woocommerce_check; ?>

<div class="evelyn-wrapper">
	<h2>Dashboard<?= !empty($this->user_data) ? ': '.$this->user_data['message']['client_name'] : null ?></h2>
	<!-- <pre> -->
	<?php
	// // print_r($this->uploadImage('http://localhost/analitica_innovare/evelynpos3/assets/images/products/default.png'));
	// $this->user_data['inventory']['productIds'] = [];
	// $this->user_data['inventory']['uniqueIds'] = [];
	// update_option('evelynpos_api', $this->user_data);
	// print $this->userId;
	// print_r($_SESSION['EvelynApi']['importedInventory']);
	// print "<hr>";
	// print_r($this->user_data);
	?>
	<!-- </pre> -->
	<input type="hidden" readonly="" name="admin_pg_url" value="<?= get_admin_url(); ?>" class="admin_pg_url">
	<div class="tabset">
		<!-- Tab 1 -->
		<input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
		<label for="tab1">Api Validation</label>
		<!-- Tab 2 -->
		<input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
		<label for="tab2">Account Details</label>
		<!-- Tab 3 -->
		<input type="radio" name="tabset" id="tab3" aria-controls="dunkles" <?= isset($_GET['import_images'])  ? "checked" : null ?>>
		<label for="tab3">Data Import & Syncing</label>

		<div class="tab-panels">
			<section id="marzen" class="tab-panel">
				<h2>Api Validation</h2>
				<?php if(!$verifiedKey) { ?>
					<div class="error">Please enter your <strong>Username</strong> and <strong>API Key</strong> to validate your Account.</div>
				<?php } else { ?>
					<div class="notice notice-success settings-error is-dismissible"> 
						<p><strong>Account Verified.</strong></p>
						<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-lg-6">
						<form action="ajaxurl" autocomplete="Off" id="apiValidateForm" class="evelynpos-form">
							<div class="search-form">
								<label for="media-search-input" class="media-search-input-label">Username</label>
								<input type="search" style="width: 48.5%" id="media-search-input" class="search" name="Username" <?= ($verifiedKey) ? "value=\"{$this->user_data['message']['username']}\" disabled=\"disabled\"" : null; ?>>
							</div>
							<div class="search-form mt-2">
								<label for="media-search-input2" class="media-search-input-label">Api Key</label>
								<input style="width: 50%" type="search" id="media-search-input2" class="search" name="apiKey" <?= ($verifiedKey) ? "value=\"{$this->user_data['apikey']}\" disabled=\"disabled\"" : null; ?>>
							</div>
							<?php if(!$verifiedKey) { ?>
								<div class="search-form mt-2">
									<button class="button button-primary sync-data">Validate Key</button>
								</div>
							<?php } ?>
						</form>
					</div>
					<div class="col-lg-12">
						<div class="account-results"></div>
					</div>
				</div>
			</section>
			<section id="rauchbier" class="tab-panel">
				
				<div class="row account-wrapper">
					<div class="col-lg-12">
						<h2>Account Details</h2>
					</div>
					<?php if($verifiedKey) { ?>
						<div class="col-lg-6">
							<div class="form-group">
								<label for="company_name">Store Name</label>
								<input value="<?= $this->user_data['message']['client_name'] ?>" type="text" class="form-control" name="company_name" id="company_name" placeholder="Store Name">
							</div><!--end form-group-->
							<div class="form-group">
								<label for="setEmail">Email address</label>
								<input type="email" value="<?= $this->user_data['message']['client_email'] ?>" name="email" class="form-control" id="email" placeholder="Enter email">
							</div><!--end form-group-->
							<div class="form-group">
								<label for="setPassword">Website</label>
								<input value="<?= $this->user_data['message']['client_website'] ?>" type="text" class="form-control" name="website" id="website" placeholder="Website">
							</div><!--end form-group-->
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label for="primary_contact">Primary Contact</label>
								<input value="<?= $this->user_data['message']['primary_contact'] ?>" placeholder="Primary Contact" type="text" class="form-control" name="primary_contact" id="primary_contact">
							</div>
							<div class="form-group">
								<label for="secondary_contact">Secondary Contact</label>
								<input value="<?= $this->user_data['message']['secondary_contact'] ?>" placeholder="Secondary Contact" type="text" class="form-control" name="secondary_contact" id="secondary_contact">
							</div>
							<div class="form-group">
								<label for="address">Address</label>
								<input value="<?= $this->user_data['message']['address'] ?>" placeholder="Address" type="text" class="form-control" name="address" id="address">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group text-right">
								<button type="submit" class="button button-primary sync-data">Update Account Information</button>
							</div>
						</div>
						<div class="col-lg-12 mt-2"><div class="account-results"></div></div>
					<?php } else { ?>
						<button class="button button-success validate-account">Validate Account</button>
					<?php } ?>
				</div>
			</section>
			<section id="dunkles" class="tab-panel">
				<div class="text-center">
					<?php if($verifiedKey && !isset($_GET["import_images"])) { ?>
						<p>Use the button below to syncronize data from your <strong>Evelyn Account</strong> into <strong><?php form_option( 'blogname' ); ?></strong>. This will allow you to efficiently control your Store.</p>
					<?php } ?>
					<?php if($verifiedKey) { ?>
						<?php if(!isset($_GET["import_images"])) { ?>
						<button class="button button-success import-data">Import Inventory Data</button>
						<button class="button button-primary sync-data">Syncronize Data</button>

						<div class="modal myModal importModal" style="display: none;">
							<div class="modal-content">
								<div class="modal-header">
									<span class="close">&times;</span>
									<h2>Import Inventory Data</h2>
								</div>
								<div class="modal-body">
									<p>
										Hello, You have opted to import <strong>Inventory</strong> from <strong><?= $this->user_data['message']['client_name'] ?></strong>
										in your <strong><?= $this->app_name; ?> Account</strong> into your store. By confirming, this will request for Inventory Records from your POS.<br>
										<hr>
										<strong>Last Import Date: </strong> <?= !empty($this->user_data['last_import']) ? $this->user_data['last_import'] : 'Unknown'; ?>
										<hr> <em>A maximum of 1000 records will be returned per request.</em>
									</p>
									<p>
										<button class="button button-success confirm-import">Confirm</button>
										<button class="button button-danger closeModal">Cancel</button>
									</p>
								</div>
							</div>
						</div>
						<div class="modal myModal syncronizeModal" style="display: none;">
							<div class="modal-content">
								<div class="modal-header">
									<span class="close">&times;</span>
									<h2>Syncronize Data</h2>
								</div>
								<div class="modal-body">
									<p>
										Hello, You have opted to sync data between <strong><?= $this->user_data['message']['client_name'] ?></strong> and <strong><?= $this->app_name; ?> Account</strong>. Do you wish to continue with the process?
									</p>
									<p>
										<button class="button button-success confirm-sync">Confirm</button>
										<button class="button button-danger closeModal">Cancel</button>
									</p>
								</div>
							</div>
						</div>
						<?php } else { ?>
							<h3>Import Product Images</h3>
							<p>Please complete the import of the inventory by downloading the product images into wordpress.</p>

							<?php if(!empty($_SESSION['EvelynApi']['importedInventory'])) { ?>
								<pre>
							<?php if(isset($_POST['completeImport']) && isset($_POST['postIds'])) {

								// confirm that the post ids submitted is equal to that which is set in session
								if($_POST['postIds'] == json_encode($_SESSION['EvelynApi']['importedInventory'])) {
									// continue to process the request
									print_r($this->updateInventoryImages($_POST));
								} else {
									print "<div class='error'>Sorry! An invalid request was parsed.</div>";
								}

							} ?>

							<form action="<?= get_admin_url() ?>admin.php?page=evelynpos_api&import_images" method="POST">
								<input type="hidden" name="completeImport" class="completeImport" value="parseData">
								<input type="hidden" name="postIds" class="postIds" value="<?= json_encode($_SESSION['EvelynApi']['importedInventory']) ?>">
								<button class="button button-primary" type="submit">Import Images</button>
							</form>
							<?php } else { ?>
							No valid Product Ids were submitted. <a href="<?= get_admin_url() ?>admin.php?page=evelynpos_api">Return</a>
							<?php } ?>
						<?php } ?>
					<?php } else { ?>
						<button class="button button-success validate-account">Validate Account</button>
					<?php } ?>
				</div>
			</section>
		</div>

	</div>
</div>
<?php ob_end_flush(); ?>