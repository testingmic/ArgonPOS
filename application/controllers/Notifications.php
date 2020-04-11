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
	
	// This is the response general response to be returned
	public $notice = null;


	public function __construct() {
		parent::__construct();

		/* Load the logged in user data */
		$this->clientInfo = $this->clientData();
		$this->setupInfo = json_decode($this->clientInfo->setup_info);
	}

	/**
	 * @method welcomeNotice
	 * This notice is shown on first login after setup
	 * It will continue to show unless the user mutes it.
	 *
	 **/
	public function welcomeNotice() {

		/**
		 * Confirm that the initializing is set to true
		 * Each notice will have a trigger and close functions
		 * @param trigger_func (Takes the name of the modal to show)
		 * @param close_func (sets the client id and the name of the modal window to close)
		 * @param header (This is the header for the modal)
		 * @param content (This is the message to show)
		 **/
		if($this->setupInfo->initializing) {
			$this->notice = [
				'modal' => "WelcomeModal",
				'function' => $this->jsFunctions('confirmWelcomeNotice'),
				'header' => $this->systemNotification('header', 'welcomeNote'),
				'content' => $this->placeholderReplacer($this->systemNotification('content', 'welcomeNote'))
			];
		}

		return $this->notice;

	}

	/** 
	 * @method systemNotification
	 * Query the database table and submit the information that the user requests
	 * @param string $columnName This is the name of the column to return its value
	 * @return string
	 **/
	private function systemNotification($columnName, $dataType) {

		try {

			$stmt = $this->pos->prepare("SELECT * FROM system_notices WHERE status = ? AND data_type = ?");
			$stmt->execute([1, $dataType]);

			$result = $stmt->fetch(PDO::FETCH_OBJ);

			return (isset($result->$columnName)) ? $result->$columnName : null;

		} catch(PDOException $e) {
			return false;
		}

	}

	/**
	 * @method placeholderReplacer
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

	private function jsFunctions($functionName) {

		$code = null;

		switch ($functionName) {
			case 'confirmWelcomeNotice':
				$code = 
				<<<EOF
				$(`div[class~="WelcomeModal"] button[class="close"]`).on('click', function(e) {
					e.preventDefault();
					$.post(`\${baseUrl}aj/notificationHandler/seenWelcome`, {confirmWelcomeNotice: true}, function(resp) {
						if(resp.status == 'error') {
							Toast.fire({
								type: 'error',
								title: 'Sorry! There was an error while processing the request. Please try again later'
							});
						} else {
							$(`div[class~="WelcomeModal"]`).modal('hide');
						}
					}, 'json');
				})
				EOF;
				break;
			
			default:
				# code...
				break;
		}

		return $code;
	}

	public function setUserSeen($clientId, $dataType) {

		$this->pos->beginTransaction();
		try {

			//: log the user activity as having seen the initial notification
			$this->setupInfo->initializing = 0;

	        //: update the user settings 
	        $stmt = $this->pos->prepare("UPDATE settings SET setup_info = ? WHERE clientId = ?");
	        $stmt->execute([json_encode($this->setupInfo), $clientId]);

	        //: fetch the list of all clients
			$stmt = $this->pos->prepare("SELECT id, seen_by FROM system_notices WHERE data_type = ?");
			$stmt->execute([$dataType]);

			$result = $stmt->fetch(PDO::FETCH_OBJ);

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
	        $this->userLogs('setup-initializing', $clientId, 'Set the initializing to false.');

	        //: commit the transaction
	        $this->pos->commit();

			return true;

		} catch(PDOException $e) {
			$this->pos->rollBack();
			return $e->getMessage();
		}

	}

}
?>