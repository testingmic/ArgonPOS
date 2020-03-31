<?php

class Logger extends Hr {
	
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @method Logger
	 * @desc The Logger File is reponsible for monitoring user access to a page
	 *	It takes recognition of the number of times the user has visited the page and the referals
	 **/
	public function Logger() {
		// Get the user environment
		$user_platform = $this->platform;
		$user_browser = $this->browser;

		$user_cookie = $this->userLogger();

		

	}

	private userLogger() {
		// set the unique cookie for this user
		if(!isset($_COOKIE['Logger'])) {
			session_set_cookie_params(
				'Logger', (60*60*24*30), '/',
			);
		}

		return $_COOKIE['Logger'];
	}

	/**
	 * @method ipaddressVerified
	 * @description This is to verify if the user's IP Address is within range
	 * @return bool
	 **/
	private function ipVerified() {
		
		#query the ip-api website based on the users ip address for related information
		$query = json_decode(file_get_contents('http://ip-api.com/json/'.$this->ip_address), true);
		
		#get the user information
		if(isset($query) && ($query['status'] == 'success')) {
			
			#assign some variables
			if(($query['city'] == 'Accra') && ($query["as"] == "AS30986 Scancom Ltd.")) {
				// return true
				if(($query['lat'] == '5.5502') && ($query['lon'] == '-0.2174')) {
					return true;
				}

				return false;
			}
		}

		return false;
	}

}

?>