<?php
// check if the user has already validated their Api Key
ob_start();
$verifiedKey = $this->verifyKey();
$limit = (!empty($this->user_data) && isset($this->user_data['limit'])) ? $this->user_data['limit'] : 10;
?>
<?= $this->woocommerce_check; ?>
<div class="evelyn-wrapper">
	<h2>Inventory<?= !empty($this->user_data) ? ': '.$this->user_data['message']['client_name'] : null ?></h2>
	<hr>
	<div class="row">
		<div class="col-lg-6">
			<span><em>Displaying list of products in stock.</em></span>
		</div>
		<div class="col-lg-6 text-right">
			<button class="button-primary import-inventory">Refresh Inventory</button>
		</div>
	</div>
	<div class="inventory-listing">
		<div class="row">
			<div class="form-content-loader" style="display: none;">
		        <div class="offline-content text-center">
		            <p><i class="fa fa-spin fa-spinner fa-3x"></i></p>
		        </div>
		    </div>
			<table class="wp-list-table widefat fixed striped inventory">
				<thead>
					<tr>
		                <th width="5%">#</th>
		                <th width="30%" class="strong">Item Name</th>
		                <th class="strong">Category</th>
		                <th class="strong">Cost Price</th>
		                <th class="strong">Retail Price</th>
		                <th class="strong">Stock Quantity</th>
		                <th width="10%"></th>
		            </tr>
	            </thead>
	            <tbody>
	            	<tr>
	            		<td colspan="8" class="text-center"><em>No data to display at the moment.</em></td>
	            	</tr>
	            </tbody>
			</table>
			<input type="hidden" name="startIndex" value="<?= isset($_GET['start']) ? (int) $_GET['start'] : 0 ?>" class="startIndex">
			<input type="hidden" name="endIndex" value="<?= isset($_GET['end']) ? (int) $_GET['end'] : $limit ?>" class="endIndex">
		</div>
	</div>
	<div class="modal myModal deleteModal text-center" style="display: none;">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close">&times;</span>
				<h2>Delete Inventory Record</h2>
			</div>
			<div class="modal-body">
				<p>
					You have opted to delete this Product from your inventory list. This will remove the product permanently from your product list. Do you wish to continue with the process?
				</p>
				<p>
					<input type="hidden" name="productId" id="productId" class="productId">
					<button class="button button-success confirm-delete">Yes</button>
					<button class="button button-danger closeModal">Cancel</button>
				</p>
			</div>
		</div>
	</div>
</div>