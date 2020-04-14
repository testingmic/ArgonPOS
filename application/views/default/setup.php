<?php 
global $hrClass, $accessObject, $posClass;

// initializing
$status = 500;

// initializing
$response = (Object) ["status" => "danger", "result" => "Error Processing Request"];

$password_ErrorMessage = "<div class='text-left' style='width:100%'>Sorry! Please use a stronger password. <br><strong>Password Format</strong><br><ul>
	<li style='padding-left:15px;'>Password should be at least 8 characters long</li>
	<li style='padding-left:15px;'>At least 1 Uppercase</li>
	<li style='padding-left:15px;'>At least 1 Lowercase</li>
	<li style='padding-left:15px;'>At least 1 Numeric and</li>
	<li style='padding-left:15px;'>at least 1 Special Character</li></ul></div>";

// process setup
if(isset($_POST["fullname"], $_POST["store_name"], $_POST["store_email"], $_POST["password"]) && confirm_url_id(1, 'letsBegin')) {
	
	//: assign variables
	$postData = (Object) array_map('xss_clean', $_POST);

	//: validate the information parsed
	if(isset($postData->fullname) and (strlen($postData->fullname) < 3)) {
		$response->result = 'Sorry! The Fullname must be at least 3 characters long.';
	} elseif(isset($postData->contact) && !preg_match('/^[0-9+]+$/', $postData->contact)) {
		$response->result = 'Sorry! Please enter a valid contact number';
	} elseif(isset($postData->store_name) and (strlen($postData->store_name) < 5)) {
		$response->result = 'Sorry! The Store Name must be at least 5 characters long.';
	} else{
		// email check
		if(isset($postData->store_email) and filter_var($postData->store_email, FILTER_VALIDATE_EMAIL)) {
			
			// check for the various items
			$check = $posClass->getAllRows(
			"settings", "COUNT(*) AS rows_count", "client_email = '{$postData->store_email}'")[0];

			$check2 = $posClass->getAllRows(
			"users", "COUNT(*) AS rows_count", "email = '{$postData->store_email}'")[0];
			
			// count the number of rows found
			if($check->rows_count != 0 || $check2->rows_count != 0) {
				$response->result = 'Sorry! This Email Address already exist.';
			} else {
				// password test
				if(!passwordTest($postData->password)) {
					$response->result = $password_ErrorMessage;
				} else {

					// create a new object
					$setupObject = load_class('Setup', 'controllers');
					$postData->subscribeTo = $session->subscriptionPack;

					// insert the record into the database
					$setup = $setupObject->initialSetup($postData);

					// check if the response is true
					if($setup) {
						$response->status = 'success';
						$response->result = 'Store Setup was successfully processed. Please check your email for further instructions.';
					}
				}
			}
		}
	}
}

// print the data in a json
echo json_encode($response);
?>