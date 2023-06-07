<?php
/**
 * Notifications 
 *
 * Manages all interactions with the logged in user
 *
 * @package		POS
 * @subpackage	Notification Super Class
 * @category	User Interactions
 * @author		VisamiNetSolutions Dev Team
 */
class Notifications extends Pos {
	
	// Set the client id to use
	public $curClientId;

	// This is the response general response to be returned
	public $notice = null;
	public $message = null;
	public $hasExpired = false;

	public $clientInfo;
	public $setupInfo;
	public $themeColors;

	// This is the global variable that checks if a notification is available for display
	public $notificationAvailable = false;

	const TRIAL = "trial";
	const BASIC = "basic";
	const ALPHA = "alpha";

	public function __construct($curClientId = null) {
		parent::__construct();

		/* Load the logged in user data */
		$this->curClientId = (empty($curClientId)) ? $this->clientId : $curClientId;
		$this->clientInfo = $this->clientData($this->curClientId);
		$this->setupInfo = json_decode($this->clientInfo->setup_info);
		$this->themeColors = json_decode($this->clientInfo->theme_color);
	}

	/**
	 * @method availableNotification
	 * This notice is shown on first login after setup
	 * It will continue to show unless the user mutes it.
	 *
	 **/
	public function availableNotification() {

		/**
		 * Loop through the list of system notices availabe
		 * This should fetch what relates to the logged in user
		 **/
		foreach($this->systemNotification($this->curClientId) as $eachNotice) {

			/* Show if the user has not already seen it */
			if($this->notYetSeen($eachNotice->seen_by)) {
				
				/**
				 * Confirm that the initializing is set to true
				 * Each notice will have a trigger and close functions
				 * @param trigger_func (Takes the name of the modal to show)
				 * @param close_func (sets the client id and the name of the modal window to close)
				 * @param header (This is the header for the modal)
				 * @param content (This is the message to show)
				 **/
				$this->notice = [
					'section' => $this->stringToArray($eachNotice->section),
					'modal' => $eachNotice->modal,
					'function' => $this->jsFunctions($eachNotice->modal, $eachNotice->modal_function, $eachNotice->uniqueId, $eachNotice->notice_type),
					'header' => $eachNotice->header,
					'content' => $this->placeholderReplacer($eachNotice->content)
				];

				/** Set the notification ID in a session **/
				$this->session->notificationId = $eachNotice->uniqueId;

				/* break on first seen */
				break;
			}
		}

		return $this->notice;

	}

	/** 
	 * @method systemNotification
	 * Query the database table and submit the information that the user requests
	 * @param string $columnName This is the name of the column to return its value
	 * 
	 * @return array|object
	 **/
	private function systemNotification($clientId) {

		try {

			$stmt = $this->pos->prepare("
				SELECT * 
				FROM system_notices 
				WHERE  
					status = '1' AND (related_to = 'general' OR related_to LIKE '%{$clientId}%')
				ORDER BY id ASC LIMIT 100
			");
			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $result;

		} catch(PDOException $e) {
			return false;
		}

	}

	/**
	 * @method placeholderReplacer
	 * This replaces string incoming from the db with correct words
	 * @return string
	 **/
	private function placeholderReplacer($messageContent) {

		$messageContent = str_replace(
			["{{clientName}}", "{{appName}}", "{{appURL}}"],
			["<strong>".$this->clientInfo->client_name."</strong>", 
			"<strong>".config_item('site_name')."</strong>", config_item('base_url')], 
			$messageContent
		);

		return $messageContent;
	}

	private function jsFunctions($modalName, $functionName, $uniqueId, $noticeType) {

		$code = null;

		switch ($functionName) {
			case 'generalNoticeHandler':
				$code = "
				$(`div[class~=\"{$modalName}\"] button[class=\"close\"], div[class~=\"{$modalName}\"] button[data-dismiss=\"modal\"]`).on('click', function(e) {
					e.preventDefault();
					$.post(`\${baseUrl}api/notificationHandler/activeNotice`, {unqID: '{$uniqueId}', noteType: '{$noticeType}'}, function(resp) {
						if(resp.status == 'error') {
							Toast.fire({
								type: 'error',
								title: 'Sorry! There was an error while processing the request. Please try again later'
							});
						} else {
							$(`div[class~=\"{$modalName}\"]`).modal('hide');
							setTimeout(function(e) {
								$(`div[class=\"notification-content\"]`).html(``);
							}, 2000)
						}
					}, 'json');
				})";
				break;
			
			default:
				# code...
				break;
		}

		return $code;
	}

	/**
	 * This checks if the client id is in the list of users who have already seen the notice
	 * @return bool
	 **/
	private function notYetSeen($seenList) {

		$seenList = $this->stringToArray($seenList);
		return (bool) !in_array($this->curClientId, $seenList) ? true : false;
	}

	/**
	 * Set the user as having seen the notification
	 * @param string $clientId The id of the client
	 * @param string $uniqueId This is the unique id of the notification
	 * @param string $noteType This is the unique name of the notification 
	 * @return bool
	 **/
	public function setUserSeen($clientId, $uniqueId, $noteType) {

		try {

			//: fetch the list of all clients
			$stmt = $this->pos->prepare("SELECT id, seen_by FROM system_notices WHERE uniqueId = ?");
			$stmt->execute([$uniqueId]);

			//: fetch the results
			$result = $stmt->fetch(PDO::FETCH_OBJ);

			//: count the number of rows found
			if($stmt->rowCount() > 0) {

				//: begin transaction for the remaining queries
				$this->pos->beginTransaction();
				
				//: log the user activity as having seen the initial notification
				$this->setupInfo->$noteType = 1;

		        //: update the user settings 
		        $stmt = $this->pos->prepare("UPDATE settings SET setup_info = ? WHERE clientId = ?");
		        $stmt->execute([json_encode($this->setupInfo), $clientId]);

				// convert the users to array
				$seenUsers = (!empty($result->seen_by)) ? $this->stringToArray($result->seen_by) : null;

				// confirm that the client id is not in the array
				if(!empty($seenUsers) && !in_array($clientId, $seenUsers)) {
					array_push($seenUsers, $clientId);
				} else {
					$seenUsers = [$clientId];
				}

				// update the seen users status
				$stmt = $this->pos->prepare("UPDATE system_notices SET seen_by = ? WHERE id = ?");
				$stmt->execute([implode(",", $seenUsers), $result->id]);

				//: Log the user activity
		        $this->userLogs($noteType, $this->session->clientId, 'Have acknowledged of having seen the notification shared across the Application.');

		        //: Set the notification id in session to null
		        $this->session->notificationId = null;

		        //: commit the transaction
		        $this->pos->commit();

				return true;

			} else {
				return false;
			}

		} catch(PDOException $e) {
			$this->pos->rollBack();
			return $e->getMessage();
		}

	}

	/**
	 * Check the user type and print the notice to the user
	 * @return bool
	 **/
	public function accountNotification() {

		$this->session->accountExpired = false;

		/* Check the date span */
		$daysRemaining = $this->daysDiff(date("Y-m-d"), $this->setupInfo->expiry_date);

		if($daysRemaining <= 14) {
			/* Print the notice */
			$this->message = "<div class='text-danger'>You have <strong>{$daysRemaining} days left</strong> to end your <strong>{$this->setupInfo->type}</strong> plan.</div>";

			/* If the user has expired usage */
			if($daysRemaining <= 0) {
				/* Set the expiry message */
				$this->message = "<span class='text-danger'>Your {$this->setupInfo->type} plan has expired.</span>";
				/* Set a session in motion */
				$this->session->accountExpired = true;
				$this->hasExpired = true;
			}
		}

		return $this;
	}
}
?>