<?php 

class Expenses extends Pos {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Process the form submitted
	 * Check the request parsed
	 **/
	public function pushExpense(stdClass $postData) {

		if(!empty($postData) && isset($postData->userRequest)) {

			if($postData->userRequest == 'addExpense') {
				return $this->addExpense($postData);
			} elseif($postData->userRequest == 'updateExpense') {
				return $this->updateExpense($postData);
			}

		} else {
			return false;
		}
	}

	private function addExpense($postData) {
		try {

			$stmt = $this->pos->prepare("
				INSERT INTO expenses
				SET 
					clientId = ?, branchId = ?, category_id = ?,
					start_date = ?, amount = ?, tax = ?, description = ?,
					payment_type = ?, created_by = ?
			");
			return $stmt->execute([
				$this->clientId, $postData->outletId, $postData->category,
				$postData->date, $postData->amount, $postData->tax,
				nl2br($postData->description), $postData->payment_type,
				$this->session->userId
			]);

		} catch(PDOException $e) {
			return false;
		}
	}

	private function updateExpense($postData) {
		
		try {

			// save the previous data set
			$prevData = $this->getAllRows("expenses", "*", "clientId='{$this->clientId}' AND id='{$postData->expenseId}'")[0];

			/* Record the initial data before updating the record */
			$this->dataMonitoring('expenses', $postData->expenseId, json_encode($prevData));

			$stmt = $this->pos->prepare("
				UPDATE expenses
				SET 
					branchId = ?, category_id = ?,
					start_date = ?, amount = ?, tax = ?, description = ?,
					payment_type = ?
				WHERE clientId = ? AND id = ?
			");
			return $stmt->execute([
				$postData->outletId, $postData->category,
				$postData->date, $postData->amount, $postData->tax,
				nl2br($postData->description), $postData->payment_type,
				$this->clientId, $postData->expenseId
			]);

		} catch(PDOException $e) {
			return false;
		}
	}
}
?>