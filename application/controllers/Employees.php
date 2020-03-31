<?php 
/**
 * @class Employees
 * Extends the HR Super Class
 * @desc Controls all aspects of Employees Management
 *
 **/
class Employees extends Pos {
	
	/* Set a globa variable for the table */
	public $tb;

	public function __construct() {
		parent::__construct();
		$this->tb = 'employees';
		$this->users = load_class('Users', 'controllers');
	}

	/**
	 * @method addEmployee 
	 * @param (Object) $postData
	 * @return bool
	 **/
	public function addEmployee(stdClass $postData) : bool {

		$this->db->beginTransaction();

		try {

			$postData->unique_id = random_string('alnum', mt_rand(30, 45));

			$stmt = $this->db->prepare("
				INSERT INTO
					{$this->tb}
				SET 
					unique_id = ?, employee_id = ?,
					firstname = ?, lastname = ?, email = ?, work_email = ?,
					primary_contact = ?, secondary_contact = ?, date_of_birth = ?, ssnit = ?,
					residence = ?, postal_address = ?, qualification = ?, appointment_date = ?,
					description = ?, last_review_date = now(), basic_salary = ?, marital_status = ?, 
					account_name = ?, account_number = ?, bank_name = ?, bank_branch = ?,
					position = ?, date_leaving = ?, tin_number = ?, 
					country = ?, department = ?, employee_status = ?, clientId = ?
			");
			$stmt->execute([
				$postData->unique_id, $postData->employee_id,
				$postData->firstname, $postData->lastname, 
				$postData->email, $postData->work_email, $postData->primary_contact, 
				$postData->secondary_contact, $postData->date_of_birth, strtoupper($postData->ssnit),
				$postData->residence, $postData->postal_address, $postData->qualification, 
				$postData->appointment_date, $postData->description, 
				$postData->basic_salary, $postData->marital_status, 
				$postData->account_name, $postData->account_number, 
				$postData->bank_name, $postData->bank_branch, $postData->position,
				$postData->date_leaving, $postData->tin_number, 
				$postData->country, $postData->department, $postData->employee_status, $this->clientId
			]);

			$this->employeeAllowances($postData->employeeAllowances, $postData->unique_id);

			$userRecord = (Object) [];
			$userRecord->user_type = 'Employee';
			$userRecord->fullname = $postData->firstname . " " . $postData->lastname;
			$userRecord->unique_id = $postData->unique_id;
			$userRecord->username = $postData->username;
			$userRecord->password = $postData->password;

			/* Create new user account */
			$this->users->addAccount($userRecord);

			/**
			 * @method userLogs - Update the user activity 
			 **/
			$this->userLogs('employee', $postData->unique_id, 'Added a new Employee Record');

			$this->db->commit();

			return true;

		} catch(PDOException $e) {
			$this->db->rollBack();
			return true;
		}
	}

	/**
	 * @method addMilestone
	 * @desc Insert a new employee milestone
	 * @param $postData
	 * @return bool
	 **/
	public function addMilestone(stdClass $postData) : bool {

		try {

			$stmt = $this->db->prepare("
				INSERT INTO
					employees_milestones
				SET 
					unique_id = ?, employee_id = ?, 
					record_date = ?, subject = ?, description = ?,
					recorded_by = ?, milestone_type = ?, favicon = ?, clientId = ?
			");
			$stmt->execute([
				$postData->unique_id, $postData->employee_id, $postData->record_date,
				$postData->subject, nl2br($postData->description), $this->session->userId,
				$postData->milestone_type, $postData->favicon, $this->clientId
			]);

			/**
			 * @method userLogs - Update the user activity 
			 **/
			$this->userLogs('milestone', $postData->unique_id, 'Added a new Employee Milestone');

			return true;

		} catch(PDOException $e) {
			return false;
		}
	}


	/**
	 * @method updateEmployee 
	 * @param (Object) $postData
	 * @return bool
	 **/
	public function updateEmployee(stdClass $postData) : bool {

		$this->db->beginTransaction();
		try {

			/* Set the table name */
			$this->tableName = 'employees';
			
			/* Record the initial data before updating the record */
			$this->dataMonitoring(
				'employee',
				$postData->unique_id, 
				json_encode($this->recordDetails($postData->unique_id, "*", "AND mn.status='1'"))
			);
			
			$stmt = $this->db->prepare("
				UPDATE
					{$this->tb}
				SET 
					firstname = ?, lastname = ?, employee_id = ?, email = ?, work_email = ?,
					primary_contact = ?, secondary_contact = ?, date_of_birth = ?, ssnit = ?,
					residence = ?, postal_address = ?, qualification = ?, appointment_date = ?,
					description = ?, last_review_date = now(), basic_salary = ?, marital_status = ?, 
					account_name = ?, account_number = ?, bank_name = ?, bank_branch = ?, 
					employee_status = ?, position = ?, date_leaving = ?, 
					tin_number = ?, department = ?, country = ?
				WHERE
					unique_id = ? AND clientId = ?
			");
			$stmt->execute([
				$postData->firstname, $postData->lastname, $postData->employee_id, 
				$postData->email, $postData->work_email, $postData->primary_contact, 
				$postData->secondary_contact, $postData->date_of_birth, strtoupper($postData->ssnit),
				$postData->residence, $postData->postal_address, $postData->qualification, 
				$postData->appointment_date, $postData->description, 
				$postData->basic_salary, $postData->marital_status, 
				$postData->account_name, $postData->account_number, 
				$postData->bank_name, $postData->bank_branch,
				$postData->employee_status, $postData->position, $postData->date_leaving,
				$postData->tin_number, $postData->department, $postData->country,
				$postData->unique_id, $this->clientId
			]);

			$this->employeeAllowances($postData->employeeAllowances, $postData->unique_id);

			$userRecord = (Object) [];
			$userRecord->fullname = $postData->firstname . " " . $postData->lastname;
			$userRecord->unique_id = $postData->unique_id;

			/* Create new user account */
			$this->users->updateAccountName($userRecord);

			/**
			 * @method userLogs - Update the user activity 
			 **/
			$this->userLogs('employee', $postData->unique_id, 'Updated the employee details');

			$this->db->commit();

			return true;

		} catch(PDOException $e) {
			$this->db->rollBack();
			return false;
		}
	}

	/**
	 * @method employeeAllowances 
	 * @param $employeeId
	 * @param $requestName This can either by add or update
	 * @return bool
	 **/
	public function employeeAllowances($employeeAllowances, $employeeId) : bool {

		try {

			/* Set the table name */
			$this->tableName = 'employees_allowances';
			
			/* Record the initial data before updating the record */
			$this->dataMonitoring(
				'employee_allowances', 
				$employeeId, 
				json_encode($this->recordDetails(null, "*", "mn.employee_id='{$employeeId}'"))
			);

			/* Delete the employee allowance records and insert a new data */
			$stmt = $this->db->prepare("DELETE FROM employees_allowances WHERE employee_id = ?");
			$stmt->execute([$employeeId]);

			/* Confirm that the allowances are not empty */
			if(is_array($employeeAllowances) && !empty($employeeAllowances)) {

				/* Loop through the list of user allowances */
				foreach($employeeAllowances as $eachAllowance) {
					// run this section if the request is allowance
					$stmt = $this->db->prepare("
						INSERT INTO 
							employees_allowances
						SET 
							allowance_id = ?, employee_id = ?, amount = ?, type = ?, clientId = ?
					");
					$stmt->execute([
						$eachAllowance['allowance_id'],
						$employeeId,
						$eachAllowance['allowance_amount'],
						$eachAllowance['allowance_type'],
						$this->clientId
					]);
				}
			}

			return true;

		} catch(PDOException $e) {
			return false;
		}
	}

	/**
	 * @method deleteEmployee
	 * @desc This deletes a employee record
	 * @param string $employeeId - This is the only parameter that is required
	 * @return bool
	 **/
	public function deleteEmployee($employeeId) : bool {
		return $this->deleteRecord($this->tb, $employeeId);

		/**
		 * @method userLogs - Update the user activity 
		 **/
		$this->userLogs('employee', $employeeId, 'Deleted the employee');
	}

}