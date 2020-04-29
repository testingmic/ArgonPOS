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

	public function __construct() {
		
		$this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
		$this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
		$this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/evelynapi.php';
		$this->user_data = get_option('evelynpos_api');

		add_action('init', array($this, 'validationCookie'));
	}
	
	public function verifyKey() {

		if(empty($this->user_data)) {
			return;
		}

		$payload = [
			'api_uname' => $this->user_data['message']['username'],
			'api_key' => $this->user_data['apikey']
		];
		
		if($this->validationCookie()) {

			$validate = Curl::curlHander($payload, 'auth', 'POST');

			if($validate['status'] == 'success') {
				
				$validate['apikey'] = $this->user_data['apikey'];
				update_option('evelynpos_api', $validate);

				return true;
			}

		}

		return true;
	}

	public function validationCookie() { 
		// Time of user's visit
		$currentTime = time();

		// Check if cookie is already set
		if(isset($_COOKIE['wpb_evlyn_validation_cookie'])) {
		 	// check if the expiry time is up
		 	if(base64_decode($_COOKIE['wpb_evlyn_validation_cookie']) >= time()) {
		 		return false;
		 	} else {
		 		@setcookie('wpb_evlyn_validation_cookie',  base64_encode(time()+(60*60)), 'Session', "/");
		 		return true;
		 	}
		}

		@setcookie('wpb_evlyn_validation_cookie',  base64_encode(time()+(60*60)), 'Session', "/");

		return true;

		wp_die();
	} 
	
}