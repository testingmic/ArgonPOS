<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Base;

use \Inc\Api\Curl;

class BaseController {

	public $plugin;
	public $plugin_url;
	public $plugin_path;
	public $user_data;
	public $do_verify;
	public $app_name;
	public $woocommerce_check;
	public $woocommerce_state;
	public $userId;

	public function __construct() {
		
		$this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
		$this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
		$this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/evelynapi.php';
		$this->user_data = get_option('evelynpos_api');
		$this->app_name = 'EvelynPOS';
		$this->woocommerce_state = true;

		$this->checkWoocommerce();

		add_action( 'init', array($this, 'validationCookie') );
	}
	
	public function verifyKey($pushedPayload = null) {

		if(empty($this->user_data) && empty($pushedPayload)) {
			return;
		}

		$dbPayload = [
			'api_uname' => $this->user_data['message']['username'],
			'api_key' => $this->user_data['apikey']
		];

		$payload = !(empty($pushedPayload)) ? $pushedPayload : $dbPayload;
		$verify = !empty($pushedPayload) ? true : $this->do_verify;
		
		if($verify) {

			$vData = Curl::curlHander($payload, 'auth', 'POST');

			if($vData['status'] == 'success') {

				$uData = empty($this->user_data) ? [] : $this->user_data;

				$uData['status'] = $vData['status'];
				$uData['message'] = $vData['message'];
				$uData['apikey'] = $payload['api_key'];
				$uData['apiEndpoint'] = $vData['apiEndpoint'];
				$uData['inventory'] = (isset($uData['inventory'])) ? $uData['inventory'] : [];
				$uData['inventory']['productIds'] = (is_array($uData['inventory']['productIds'])) ? $uData['inventory']['productIds'] : [];
				$uData['inventory']['uniqueIds'] = (is_array($uData['inventory']['uniqueIds'])) ? $uData['inventory']['uniqueIds'] : [];
				$uData['limit'] = (isset($uData['limit'])) ? $uData['limit'] : 10;
				
				update_option('evelynpos_api', $uData);

				return true;
			} else {
				return false;
			}

		}

		return true;
	}

	public function validationCookie() { 

		// Time of user's visit
		$currentTime = time();

		$this->userId = apply_filters( 'determine_current_user', false );

		// Check if cookie is already set
		if(isset($_COOKIE['wordpress_evlyn_validation_cookie'])) {
		 	// check if the expiry time is up
		 	if(base64_decode($_COOKIE['wordpress_evlyn_validation_cookie']) >= time()) {
		 		$this->do_verify = false;
		 	} else {
		 		setcookie('wordpress_evlyn_validation_cookie',  base64_encode(time()+(60*60)), 'Session', ABSPATH);
		 		$this->do_verify = true;
		 	}
		} else {
			setcookie('wordpress_evlyn_validation_cookie',  base64_encode(time()+(60*60)), 'Session', ABSPATH);
			$this->do_verify = true;
		}

		return $this->do_verify;

		wp_die();
	} 
	
	/**
	 * Check if WooCommerce is active
	 **/	
	public function checkWoocommerce() {

		if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

		  	$this->woocommerce_check = '<div style="margin-left:0px; margin-bottom:10px" class="notice notice-warning settings-error is-dismissible"> <p><strong>
		  		<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">This theme recommends the following plugin: <em><a href="'.get_admin_url().'plugin-install.php?tab=plugin-information&amp;plugin=woocommerce&amp;TB_iframe=true&amp;width=640&amp;height=500" class="thickbox">WooCommerce</a></em>.</span>
		  		<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><a href="'.get_admin_url().'plugin-install.php?s=woocommerce&amp;tab=search&type=term">Begin installing plugin</a></span>
		  		</strong>
		  	</p>
		  	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

		  	$this->woocommerce_state = false;
		}
	}


	public function uploadImage($imagePath) {

		$postImage = $imagePath;

		// //Fetch and Store the Image	
		$get = wp_remote_get( $postImage );
		$type = wp_remote_retrieve_header( $get, 'content-type' );
		$mirror = wp_upload_bits(rawurldecode(basename( $postImage )), '', wp_remote_retrieve_body($get));
		
		// //Attachment options
		$attachment = array(
			'post_title'=> basename( $postImage ),
			'post_mime_type' => $type,
			'post_guid' => $mirror['url']
		);

		return $attachment;
	}

	public function updateInventoryImages($postData) {

		global $wpdb;

		// ensure that the form has been submitted
		if(isset($_POST['completeImport']) && isset($_POST['postIds'])) {
			
			// ensure that the values parsed are the expected values
			if($_POST['postIds'] == json_encode($_SESSION['EvelynApi']['importedInventory'])) {
				// continue processing the request
				$postIds = json_decode($_POST['postIds'], true);

				foreach($postIds as $eachPost) {
					$postId = $eachPost;

					$post = get_post( $postId );

					$imageInfo = $this->uploadImage($post->guid);

					$wpdb->query( "UPDATE {$wpdb->prefix}posts SET guid = '".$imageInfo['post_guid']."', post_title = '".$imageInfo['post_title']."', post_mime_type = '".$imageInfo['post_mime_type']."' WHERE id ='{$postId}'");

				}

				$_SESSION['EvelynApi']['importedInventory'] = [];

				return "<div class='success'><div class='notice notice-success'>Inventory Images successfully uploaded.</div></div>";

				
			} else {
				return "<div class='error'>Sorry! An invalid request was parsed.</div>";
			}

		}

	}

}