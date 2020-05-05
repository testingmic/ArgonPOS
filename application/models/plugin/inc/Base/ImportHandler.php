<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

class ImportHandler {

	public $exists;

	public function __construct($woocommerce_state, $userData, $currentUserId) {
		global $wpdb;
			
		$this->site_url = "http://localhost/analitica_innovare/evelynpos3/";
		$this->evdb = $wpdb;
		$this->userData = $userData;
		$this->userId = $currentUserId;
		$this->woocommerce_state = $woocommerce_state;

		$this->controller = new BaseController();
	}
	
	public function importInventory(array $inventoryList) {

		// check if the inventory list is empty
		if(empty($inventoryList)) {
			return;
		}

		// list of ids to be imported
		$imageIds = [];
		$productIds = [];
		$inventoryIds = [];

		// loop through the inventory list
		foreach ($inventoryList as $eachItem) {
			
			// assign variables
			$productId = $eachItem['product_id'];
			$branchId = $eachItem['branchId'];

			// check if the product already exists
			if(!$this->productDetails($productId."-".$branchId)->exists) {
				
				//insert the product in the database
				$insertId = $this->addProduct($eachItem);

				array_push($productIds, $productId."_".$eachItem['branchId']);
				array_push($inventoryIds, $insertId."_".$eachItem['pid']);
				array_push($imageIds, $insertId+1);
			}

		}

		// update the inserted products list
		$uData = $this->userData;

		// append the products id in a array to the user data
		$i = 0;
		foreach($inventoryIds as $itemId) {
			$i++;
			array_push($uData['inventory']['productIds'], $itemId);
			array_push($uData['inventory']['uniqueIds'], $productIds[$i]);
		}
		
		// set the last import data
		$uData['last_import'] = date("jS F, Y, H:i:sa");

		// update the options table
		update_option('evelynpos_api', $uData);

		// push the list in a session
		@$_SESSION['EvelynApi']['importedInventory'] = $imageIds;

		return true;

	}

	public function productDetails($productId) {

		$this->exists = false;

		$query = $this->evdb->query("
			SELECT * 
			FROM {$this->evdb->prefix}posts 
			WHERE post_name='{$productId}' AND post_type='product' AND post_status != 'trash'
		");

		if(!empty($query)) {
			$this->exists = true;
		}

		return $this;

	}

	public function addProduct(array $productDetails) {

		if(!empty($productDetails)) {

			$data = (Object) $productDetails;

			$query = "INSERT INTO {$this->evdb->prefix}posts SET ";

			$query .= "post_author = '".$this->userId."', post_date = now(), post_date_gmt = now()";

			if(isset($data->product_description) && !empty($data->product_description)) {
				$query .= ", post_content='".sanitize_text_field($data->product_description)."'";
			}

			if(isset($data->product_title) && !empty($data->product_title)) {
				$query .= ", post_title='".sanitize_text_field($data->product_title)."'";
			}

			$query .= ", post_status='publish', comment_status='closed', ping_status='closed'";

			if(isset($data->product_id) && !empty($data->product_id)) {
				$query .= ", post_name='".sanitize_text_field($data->product_id."-".$data->branchId)."'";
			}

			$query .= ", post_modified=now(), post_modified_gmt=now(), post_parent='0', post_type='product'";

			// run the query
			$this->evdb->query( $query );
			$lastId = $this->evdb->insert_id;

			// format the guid
			$guid = get_option( 'home' )."?post_type=product&p={$lastId}";

			// update the guid
			$this->evdb->query( "UPDATE {$this->evdb->prefix}posts SET guid = '{$guid}' WHERE id ='{$lastId}'");

			// upload the product image
			$img_query = "INSERT INTO {$this->evdb->prefix}posts SET ";
			$img_query .= "post_author = '".$this->userId."', post_date = now(), post_date_gmt = now()";
			$img_query .= ", guid = '{$this->site_url}{$data->product_image}', post_status='publish', comment_status='closed', ping_status='closed'";
			$img_query .= ", post_modified=now(), post_modified_gmt=now(), post_parent='0', post_type='attachment'";

			// process the product image
			$this->evdb->query( $img_query );

			// fetch the image id and assign it to a variable
			$imageId = $this->evdb->insert_id;
			
			// insert the post meta data
			$pr_meta_query = "
				INSERT INTO {$this->evdb->prefix}postmeta (post_id, meta_key, meta_value) 
				VALUES 
					('{$lastId}', '_edit_last', '1'), 
					".(isset($data->product_price) ? "('{$lastId}', '_regular_price', '{$data->product_price}'), " : null)."
					".(isset($data->product_price) ? "('{$lastId}', '_price', '{$data->product_price}'), " : null)."
					".(isset($data->product_price) ? "('{$lastId}', '_sale_price', '{$data->product_price}'), " : null)."
					".(isset($imageId) ? "('{$lastId}', '_thumbnail_id', '{$imageId}'), " : null)."
					('{$lastId}', '_stock_status', 'instock'),
					".(isset($data->quantity) ? "('{$lastId}', '_stock', '{$data->quantity}'), " : null)." 
					('{$lastId}', '_downloadable', 'no');
				";

			// run the query
			$this->evdb->query( $pr_meta_query );
			
			// update the product meta data 
			if($this->woocommerce_state) {
				
				// generate the query string
				$wc_query = "INSERT INTO {$this->evdb->prefix}wc_product_meta_lookup SET ";
				$wc_query .= "product_id = '{$lastId}'";

				if(isset($data->product_sku)) {
					$wc_query .= ", sku = '".sanitize_text_field($data->product_sku)."'";
				}

				if(isset($data->product_price)) {
					$wc_query .= ", min_price = '".sanitize_text_field($data->product_price)."'";
				}

				if(isset($data->product_price)) {
					$wc_query .= ", max_price = '".sanitize_text_field($data->product_price)."'";
				}

				if(isset($data->quantity)) {
					$wc_query .= ", stock_quantity = '".sanitize_text_field($data->quantity)."'";
				}

				$this->evdb->query( $wc_query );
			}

			return $lastId;
		}

	}

}