<?php 
global $hrClass, $accessObject;

// initializing
$status = 500;
$data = [];
$where = '';
$accessObject->userId = $session->userId;

// validate the dataset
$postData = (Object) array_map('xss_clean', $_POST);

// set the working days
$workingDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

//: If the user needs to login
// confirm that the user has submitted the login form
if(isset($_POST["username"], $_POST["password"]) && confirm_url_id(1, "doLogin")) {

	// assign variables and clean them
	$username = xss_clean($_POST["username"]);
	$password = xss_clean($_POST["password"]);
	$href = isset($_POST["href"]) ? xss_clean($_POST["href"]) : "";
	$auth = load_class('Authenticate', 'controllers');

	if(strlen($username) < 4) {
		
		$data = "<div class='alert alert-danger'>Sorry! Invalid username/password</div>";

	} else if(strlen($username) < 8) {
		
		$data = "<div class='alert alert-danger'>Sorry! Invalid username/password</div>";

	} else {
		
		// submit credentials to the users class to process
		if ($auth->processLogin($username, $password, $href)->status) {
			$status = 200;
			$data = "<div class='alert alert-success'>Login successful</div>";
		} else {
			$data = $auth->response;
		}

	}
}

// confirm that the user wants to logout of the system
elseif(confirm_url_id(1, "doLogout")) {
	// check what to perform
	if(isset($_POST["toPerform"])) {
		// log the user out
		if($_POST["toPerform"] == "logout") {
			// unset all other sessions
			$session->unset_userdata("puserLoggedIn");
			$session->unset_userdata("userId");
			$session->unset_userdata("userName");
			// destroy all sessions that has been set
			$session->sess_destroy();
			// set the session logout to true
			$session->set_userdata("logoutOk", true);
			// print succcess code
			$status = 200;
		} else {
			// lock the user session
			$session->set_userdata("userSessionLocked", true);
			// $status success message
			$status = 200;
		}
	}
}

//: Update 
// set the response data
$responseData = [
	'status' => $status,
	'result' => $data
];

// print the data in a json
print json_encode($responseData);
?>