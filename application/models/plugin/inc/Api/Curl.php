<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Api;

class Curl {

	final static function curlHander($payload, $endpoint, $method = 'GET', $userdata = null) {
		
		$api_url = "http://localhost/analitica_innovare/evelynpos/api/{$endpoint}";

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
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
				if ($payload) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
				$api_url = sprintf("%s?%s", $api_url, http_build_query($payload));
				break;
		}

	   	curl_setopt($curl, CURLOPT_URL, $api_url);
	   	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	   		($endpoint == 'auth') ? null : 'Authorization: Bearer '.base64_encode($userdata['message']['username'].':'.$userdata['apikey']),
	   		'Content-Type: '. ($endpoint == 'auth') ? 'application/x-www-form-urlencoded' : 'application/json',
	   	));
	   	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	   	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	   
	   	$result = curl_exec($curl);
	   
	   	curl_close($curl);
	   
	   	return json_decode($result, true);

	}

}