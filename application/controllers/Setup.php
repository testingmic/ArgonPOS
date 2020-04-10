<?php 

class Setup extends Pos {

	public function __construct() {
		parent::__construct();
	}

	public function initialSetup(StdClass $postData) {
		
		$this->pos->beginTransaction();

		try {
			// generate
			$clientId = random_string('alnum', 12);

			// outlet types
			if($postData->subscribeTo == 'trial') {
				$outlets = 2;
			} elseif($postData->subscribeTo == 'basic') {
				$outlets = 1;
			} elseif($postData->subscribeTo == 'alpha') {
				$outlets = 2000;
			}

			// setup string information
			$setupType = '{"type":"'.$postData->subscribeTo.'","verified":0,"setup_date":"'.date('Y-m-d').'","expiry_date":"'.date("Y-m-d", strtotime("today +14 day")).'","outlets":"'.$outlets.'","initializing":"1"}';

			// setup the store
			$stmt = $this->pos->prepare("
				INSERT INTO settings
				SET clientId = ?, client_name = ?, client_email = ?, 
					primary_contact = ?, setup_info = ?
			");
			$stmt->execute([
				$clientId, $postData->store_name, $postData->store_email, 
				$postData->contact, $setupType
			]);

			// set up the first branch
			$this->addBranchData($postData->store_name, $postData->store_email, $postData->contact, $clientId);

			// set up the user
			$userData = (Object) [];
			$verifyToken = random_string('alnum', mt_rand(40, 60));
			$userData->user_id = random_string('alnum', 15);
			$userData->clientId = $clientId;
			$userData->branchId = $this->lastRowId('branches');
			$userData->name = $postData->fullname;
			$userData->email = $postData->store_email;
			$userData->contact = $postData->contact;
			$userData->login = $postData->store_email;
			$userData->password  = password_hash($postData->password, PASSWORD_DEFAULT);
			$userData->access_level = 2;

			// add user account data
			$this->addUserAccounts($userData, $verifyToken);

			// assign user roles
			$accessObject = load_class('Accesslevel', 'controllers');
			$accessObject->assignUserRole($userData->user_id, 'Admin');

			// send an email message
			$this->triggerEmail($clientId, $userData->branchId, $postData->store_name, $userData->email, $verifyToken, $userData->user_id, $userData->name);

			// log the user activity
			$this->userLogs("setup", $userData->clientId, "This is the initial setup process that has been carried out by {$postData->store_name}.", $userData->clientId, $userData->branchId, $userData->user_id);

			// commit the transaction for processing
			$this->pos->commit();

			// return true
			return true;
		} catch(PDOException $e) {
			$this->pos->rollBack();
			return $e->getMessage();
		}
	}

	private function addBranchData($storeName, $storeEmail, $storeContact, $clientId) {
		try {
			// setup the 1st point of sale outlet
			$branch_stmt = $this->pos->prepare("
				INSERT INTO branches
				SET branch_id = ?, clientId = ?, branch_type = ?, branch_name = ?, 
				branch_color = ?, branch_contact = ?, branch_email = ?, status = ?
			");
			return $branch_stmt->execute([
				random_string('alnum', 12), $clientId, 'Warehouse',
				$storeName, 'badge-purple', $storeContact, $storeEmail, 1
			]);
		} catch(PDOException $e) {
			return false;
		}
	}

	private function addUserAccounts(StdClass $userData, $verifyToken) {

		try {
			$stmt = $this->pos->prepare("
				INSERT INTO users SET 
					clientId = ?, branchId = ?, user_id = ?,
					name = ?, email = ?, phone = ?, login = ?,
					access_level = ?, password = ?, status = ?, verify_token = ?
			");
			return $stmt->execute([
				$userData->clientId, $userData->branchId, $userData->user_id,
				$userData->name, $userData->email, $userData->contact, $userData->email,
				2, $userData->password, 0, $verifyToken
			]);
		} catch(PDOException $e) {
			return false;
		}
	}

	private function triggerEmail($clientId, $branchId, $storeName, $emailAddress, $verifyToken, $userId, $fullname) {

		// form the email message
		$emailSubject = "Setup - $storeName \[".config_item('site_name')."\]";
		$emailMessage = "Hello $fullname,\n";
		$emailMessage .= "Thank you for registering your store <strong>{$storeName}</strong> with ".config_item('site_name').". We are pleased to have you join and experiment with our platform.\n\n";
		$emailMessage .= "One of our personnel will get in touch shortly to assist you with additional setup processes that is required to aid you quick start the usage of the application.\n\n";
		$emailAddress .= "In the mean time please use the following credentials to login into the system.\n\n";
		$emailAddress .= "<strong>Username:</strong> {$emailAddress}\n";
		$emailAddress .= "<a href='".$this->config->base_url('verify/act?tk='.$verifyToken)."'><strong>Click Here</strong></a> to verify your Email Address\n\n";
		
		$userEmail = [
            "recipients_list" => [
                [
                    "fullname" => $fullname,
                    "email" => $emailAddress,
                    "customer_id" => $userId,
                    "branchId" => $branchId
                ]
            ]
        ];

		// record the email sending to be processed by the cron job
		$stmt = $this->pos->prepare("
			INSERT INTO email_list 
			SET clientId = ?, branchId = ?,
				template_type = ?, itemId = ?, recipients_list = ?,
				request_performed_by = ?, message = ?, subject = ?
		");
		return $stmt->execute([
			$clientId, $branchId, 'general', 
			$clientId, json_encode($userEmail), $userId, $emailAddress, $emailSubject
		]);
	}
}
?>