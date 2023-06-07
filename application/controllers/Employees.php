<?php 
/**
 * @class Employees
 * Extends the HR Super Class
 * @desc Controls all aspects of Employees Management
 *
 **/
class Employees extends Pos {

	/**
	 * @method updateEmployee 
	 * @param (Object) $postData
	 * 
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

	

}