<?php 
global $hrClass, $accessObject;

// initializing
$status = 500;
$data = [];
$where = '';
$accessObject->userId = $session->userId;

// validate the dataset
$postData = (Object) array_map('xss_clean', $_POST);
$auth = load_class('Authenticate', 'controllers');

// set the working days
$workingDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

$password_ErrorMessage = "<div class='alert alert-danger' style='width:100%'>Sorry! Please use a stronger password. <br><strong>Password Format</strong><br><ul>
	<li style='padding-left:15px;'>Password should be at least 8 characters long</li>
	<li style='padding-left:15px;'>At least 1 Uppercase</li>
	<li style='padding-left:15px;'>At least 1 Lowercase</li>
	<li style='padding-left:15px;'>At least 1 Numeric</li>
	<li style='padding-left:15px;'>At least 1 Special Character</li></ul></div>";

//: If the user needs to login
// confirm that the user has submitted the login form
if(isset($_POST["username"], $_POST["password"]) && confirm_url_id(1, "dL")) {

	// assign variables and clean them
	$username = xss_clean($_POST["username"]);
	$password = xss_clean($_POST["password"]);
	$href = isset($_POST["href"]) ? xss_clean($_POST["href"]) : "";

	if(!filter_var($username, FILTER_VALIDATE_EMAIL)) {
		$data = "<div class='alert alert-danger'>Sorry! Invalid username/password</div>";
	} else if(strlen($username) < 8) {
		
		$data = "<div class='alert alert-danger'>Sorry! Invalid username/password</div>";

	} else {
		// submit credentials to the users class to process
		if ($auth->processLogin($username, $password, $href)->status) {
			$status = 200;
			$data = "<div class='alert alert-success'>Login successful</div>";
		} else {
			$data = "<div class='alert alert-danger'>Invalid Username/Password</div>";
		}

	}
}

// confirm that the user wants to logout of the system
elseif(confirm_url_id(1, "dLg")) {
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

// confirm that the user wants to reset his or her password
elseif($SITEURL[1] == "doRecover") {

	// confirm that the email field was submitted
	if(isset($_POST["email"]) && !empty($_POST["email"])) {
		
		// set a variable
		$email = xss_clean($_POST["email"]);

		// validate the email address
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$data = "Sorry! We could not validate your email address!";
		} else {
			
			// continue to process the form
			if($auth->sendPasswordResetToken($email)) {
				$status = 200;
				$data = "Please check your email for steps to reset password.<script>$(\".submitForm\")[0].reset();</script>";
			} else {
				$data = "Sorry! We could not validate your email address!";
			}
		}
	} else {
		$data = "Sorry! Please enter your email address to continue!";
	}

}

// confirm if the user wants to reset his or her password
elseif(confirm_url_id(1, "doResetPassword") && isset($_POST["reset-password"])) {
	
	// confirm that all the fields has been parsed
	if(isset($_POST["password"]) and isset($_POST["password2"]) and isset($_POST["user_id"])) {

		// assign variables to the passwords
		$password = xss_clean($_POST["password"]);
		$password2 = xss_clean($_POST["password2"]);
		$access_token = base64_decode(xss_clean($_POST["access_token"]));
		$access_session = $session->userdata("access_token");
		$reset_token = $session->userdata("resetAccess_Token");
		$username = $session->userdata("userName");
		$user_id = $session->userdata("resetUserId");
		$user_id_parsed = base64_decode($_POST["user_id"]);
		
		// confirm that the user id matches what we have in the session
		if($access_token != $access_session) {
			$data = "Sorry! A Session Access Token forgery has been detected. Reload the page to continue.";
		} elseif($user_id_parsed != $user_id) {
			$data = "Sorry! A Session Access Token forgery has been detected. Reload the page to continue.";
		} else {
			
			// confirm that the password is a bit strong
			if($password != $password2) {
				$data = "Sorry! The passwords do not match!";
			} elseif(!passwordTest($password)) {
				$data = "$password_ErrorMessage";
			} else {

				// do update the password and print out success message
				$reset = $auth->resetUserPassword($password, $user_id, $username, $reset_token);
				
				// continue to process the form
				if($reset) {
					$status = 200;
					// status
					$data = "Your password was successfully changed.<script>$(\".submitForm\")[0].reset(); setTimeout(function() { window.location.href='".$config->base_url('login')."'; }, 2000);</script> <a href='".$config->base_url()."login'><strong> Click Here </strong></a> to login";
					// unset settions
					$session->set_userdata("resetAccess_Token", null);
					$session->set_userdata("access_token", null);
				} else {
					$data = "Sorry! We could not validate the information that was submitted for processing.";
				}
			}
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