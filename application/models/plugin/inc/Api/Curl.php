<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Api;

class Curl {

	final static function curlHander($payload, $endpoint, $method = 'GET', $userdata = null) {
		
		$api_url = 'http://localhost/analitica_innovare/evelynpos/api/';

		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($payload) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($payload) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));			 					
				break;
			default:
				if ($payload)
					$url = sprintf("%s?%s", $api_url, http_build_query($payload));
		}

	   	curl_setopt($curl, CURLOPT_URL, $api_url.$endpoint.$url);
	   	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	   		($endpoint == 'auth') ? null : 'Authorization: Bearer '.base64_encode($userdata->username.':'.$userdata->apikey),
	   		'Content-Type: '. ($endpoint == 'auth') ? 'application/json' : 'application/x-www-form-urlencoded',
	   	));
	   	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	   	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	   
	   	$result = curl_exec($curl);
	   
	   	curl_close($curl);
	   
	   	return json_decode($result, true);

	}

}