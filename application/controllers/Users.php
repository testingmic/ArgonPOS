<?php 
/**
 * @class Users
 * Extends the HR Super Class
 * @desc Controls all aspects of Users Management
 *
 **/
class Users extends Pos {

	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @method addAccount
	 * @desc Insert a new User Account
	 * @param $postData
	 * @return bool
	 **/
	public function addAccount(stdClass $postData) {

		try {

			//: Load the access level details
			$accessQuery = $this->pushQuery("*", "access_levels", "id='{$postData->user_type}'")[0];

			//: insert the user record
			$stmt = $this->db->prepare("
				INSERT INTO users SET fullname = ?, access_level = ?, unique_id = ?, 
					username = ?, password = ?, created_by = ?, clientId = ?
			");
			$stmt->execute([
				$postData->fullname,
				$accessQuery->access_name, $postData->unique_id, $postData->username,
				password_hash($postData->password, PASSWORD_DEFAULT),
				$this->session->userId, $this->clientId
			]);

			//: get the access level permissions
			$accessLevel = $accessQuery->default_permissions;

			//: insert the user access permissions
			$this->db->query("INSERT INTO user_roles SET user_id = '{$postData->unique_id}', permissions='{$accessLevel}'");

			/**
			 * @method sendEmail - Send the user credentials to the new user
			 */
			// $this->addEmail($fullname = null, $subject, $sent_to, $copy_to = null, $sent_from, $message);

			/**
			 * @method userLogs - Update the user activity 
			 **/
			$this->userLogs('user', $postData->unique_id, 'Added a new User Account Details');

			return true;
		} catch(PDOException $e) {
			return false;
		}
	}

	/**
	 * @method updateAccountName
	 * @desc This method updates the fullname of the user
	 * @param stdClass $postData
	 * @param string $postData->fullname This is the fullname of the user
	 * @param string $postData->unique_id This the unique id of the user
	 * @return bool
	 **/
	public function updateAccountName(stdClass $postData) {

		try {

			$stmt = $this->db->prepare("UPDATE users SET fullname = ? WHERE unique_id = ?");
			return $stmt->execute([$postData->fullname, $postData->unique_id]);

		} catch(PDOException $e) {
			return false;
		}
	}


	/**
	 * @method updateStatus
	 * @desc Update the user status
	 * @param $postData
	 * @return bool
	 **/
	public function updateStatus(stdClass $postData) {

		try {

			$stmt = $this->db->prepare("UPDATE users SET status = ? WHERE unique_id = ? AND clientId = ?");
			return $stmt->execute([
				$postData->status, $postData->unique_id, $this->clientId
			]);

		} catch(PDOException $e) {
			return false;
		}
	}


	/**
	 * @method updatePassword
	 * @desc This method updates the password of a user
	 * @return bool
	 **/
	public function updatePassword($postData) {
		/* Fetch the existing password */
		// $dbPassword = $this->recordDetails($postData->unique_id, "password")[0]->password;

		/* Confirm if the password */
		// if(password_verify($postData->curPassword, $dbPassword)) {

			/* Update the user password */
			$stmt = $this->db->prepare("
				UPDATE users SET password = ? WHERE unique_id = ? AND clientId = ?
			");
			return $stmt->execute([
				password_hash($postData->password, PASSWORD_DEFAULT), 
				$postData->unique_id, $this->clientId
			]);

		// } else {
		// 	return false;
		// }
	}

	/**
	 * @method logged_InControlled
	 * @desc This confirms if the user  is loggedin
	 * @param array $session
	 * @param string $session->puserLoggedIn
	 * @param string $session->userId
	 * @return bool
	 */
	public function logged_InControlled() {
		return ($this->session->puserLoggedIn && $this->session->userId) ? true : false;
	}

	public function logout_user() {
		
		$this->session->unset_userdata("puserLoggedIn");
		$this->session->unset_userdata("userId");
		$this->session->unset_userdata("userName");
		$this->session->sess_destroy();
		
	}
	
	public function logged_Role() {
		// return ($this->session->userdata("accessLevel") AND $this->session->userdata("accessLevel")) ? true : false;	
	}
}
?>