<?php

class Pos {

	/* A globl variable to set for the table to query */
	public $tableName;

	/* The edit url variable that will be used in the loadDetails class */
	public $editURL;
	public $permitPage;

	/* This is the global value for the browser and platform to use by all methods */
	public $browser;
	public $platform;
	public $clientId;

	public function __construct() {

		global $pos, $session, $config;

		$this->pos = $pos;
		$this->db = $pos;
		$this->config = $config;
		$this->session = $session;
		$this->ip_address = ip_address();

		$this->ur = load_class('User_agent', 'libraries');
		$this->platform = $this->ur->platform();
		$this->browser = $this->ur->browser();

		// $this->clientId = $this->thisClient();
		$this->clientId = $session->clientId;
	}

	/**
	 * @method thisClient
	 * @desc This method returns the variables that appends to all queries to limit the query the client Id
	 * @return string of clientId query
	 */
	public function thisClient() {

		// call the access level object
		$accessObject = load_class('Accesslevel', 'controllers');
		$accessObject->userId = $this->session->userId;

		// confirm if the is a super user
		if($accessObject->hasAccess('monitoring', 'clients')) {

			// check if a variable has been parsed
			if(!empty($this->session->superClientId)) {

				// set the client id
				$clientId = xss_clean($this->session->superClientId);
				
				// continue with the query to use for the query
				return $clientId;
			} else {
				return $this->session->clientId;	
			}
		} else {
			return $this->session->clientId;
		}

	}

	public function clientData($clientId = null) {
		$clientId = (!empty($clientId)) ? $clientId : $this->clientId;
		$clientData = $this->pushQuery("*", "settings", "(id='{$clientId}' OR clientId='{$clientId}')")[0];

		return $clientData;
	}

	/**
	 * @method quickData
	 * @desc This method enables quick query for information from the DB
	 * @param string $column name of the query to fetch
	 * @param string $tableName name of the table to query
	 * @param string $where_clause The clause to be used for the query
	 * @return string 
	 **/
	public function quickData($column, $tableName, $where_clause = 1) {
		try {
			$stmt = $this->pos->prepare("
				SELECT $column FROM $tableName WHERE $where_clause
			");
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_OBJ);
			
			return (isset($result->$column)) ? $result->$column : null;
		} catch(PDOException $e) {
			return false;
		}
	}

	/**
	 * @method recordDetails($recordId, $whereClause)
	 * @desc This call returns the details of a single / multiple records in the database that meets the filter
	 * @return array
	 *
	 **/
	public function recordDetails($recordId=null, $columnNames = 'mn.*', $whereClause = 1, $joinClause = null) {

		global $config;

		try {

			// call the access level object
			$accessObject = load_class('Accesslevel', 'controllers');
			$accessObject->userId = $this->session->userId;


			$filter = (!empty($recordId)) ? "mn.unique_id = '{$recordId}'" : null;
			$clientLimit = ($this->tableName == "countries") ? null : "mn.clientId = '{$this->clientId}' AND ";

			$stmt = $this->pos->prepare("
				SELECT {$columnNames} FROM {$this->tableName} mn $joinClause WHERE $clientLimit {$filter} {$whereClause}
			");
			
			$stmt->execute();

			$results = [];
			$row = 0;

			if($stmt->rowCount() > 0) {
				while($result = $stmt->fetch(PDO::FETCH_OBJ)) {

					$row++;

					$result->editButton = '';
					$result->deleteButton = '';

					/* If the result set contains a unique_id then set the action, delete and edit buttons */
					if(isset($result->unique_id) && !empty($this->editURL)) {

						// confirm if the user has the permission to edit this item
						if($accessObject->hasAccess('view', $this->permitPage)) {
							$result->editButton = "-";
						}

						if($accessObject->hasAccess('update', $this->permitPage)) {
							$result->editButton = "<a class=\"btn btn-outline-success\" href=\"".$config->base_url("{$this->editURL}/{$result->unique_id}")."\"><i class=\"fa fa-edit\"></i></a>";
						}

						// confirm if the user has the permission to delete this item
						if($accessObject->hasAccess('delete', $this->permitPage)) {
							$result->deleteButton = " <a data-content=\"{$this->permitPage}\" data-value=\"{$result->unique_id}\" data-msg=\"Are you sure you want to delete this ".substr((ucfirst($this->permitPage)), 0, -1)."?\" class=\"btn btn-outline-danger delete-button\" href=\"javascript:void(0)\"><i class=\"fa fa-trash\"></i></a>";
						}

						$result->actionButton = $result->editButton . $result->deleteButton;
					}
					
					/* Set the row id for the result set that has been retrieved from the database */
					$result->row = $row;

					$results[] = $result;
				}
			}

			return $results;

		} catch(PDOException $e) {
			return [];
		}
	}

	/**
	 * @method lastRowId()
	 * @param $tableName The user needs to specify the table name for the query
	 * @return $rowId
	 **/
	public function lastRowId($tableName, $clientId = null) {

		// client id
		$clientId = empty($clientId) ? $this->clientId : $clientId;

		// process the request
		$stmt = $this->pos->prepare("
				SELECT id AS rowId FROM {$tableName} WHERE clientId = ? ORDER BY id DESC LIMIT 1
		");
		$stmt->execute([$clientId]);

		return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_OBJ)->rowId : 0;
	}

	/**
	 * @method deleteRecord($recordId)
	 * @desc This method is used for deleting a record from the database
	 * @desc This will be used in all aspects of the application
	 * @return bool
	 **/
	private function deleteRecord($tableName, $recordId) : bool {

		try {

			$stmt = $this->pos->prepare("
				UPDATE {$tableName} SET status = ? WHERE unique_id = ? AND clientId = ?
			");
			return $stmt->execute([0, $recordId, $this->clientId]);

		} catch(PDOException $e) {
			return false;
		}
	}

	/**
	 * @method itemsCount($whereClause)
	 * @desc This method counts the number of rows found
	 * @return int
	 *
	 **/
	public function itemsCount($tableName, $whereClause = 1) {
		try {

			$stmt = $this->pos->prepare("
				SELECT * FROM {$tableName} WHERE $whereClause AND clientId = ?
			");
			$stmt->execute([$this->clientId]);

			return $stmt->rowCount();

		} catch(PDOException $e) {
			return false;
		}
	}

	/**
	 * @method pushQuery($columns, $table, $whereClause)
	 * @desc Receives user query and returns the full data array
	 * @return array
	 **/
	public function pushQuery($columns = "*", $tableName, $whereClause = null) {
		try {

			$stmt = $this->pos->prepare("SELECT {$columns} FROM {$tableName} WHERE $whereClause");
			// print ("SELECT {$columns} FROM {$tableName} WHERE $whereClause");
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
			return [];
		}
	}

	public function justExecute($queryString) {
		try {

			$stmt = $this->pos->prepare("$queryString");
			return $stmt->execute();

		} catch(PDOException $e) {
			return [];
		}
	}

	/**
	 * @method userLogs
	 * @param $page 	This is the page that the user is managing
	 * @param $itemId	This relates to the item that is being managed
	 * @param $description This is the full description of what is being done
	 * @return null
	 *
	 **/
	public function userLogs($page, $itemId = null, $description, $clientId = null, $branchId = null, $userId = null) {
		
		try {

			$ur_agent = $this->platform .' | '.$this->browser . ' | '.ip_address();

			$stmt = $this->pos->prepare("
				INSERT INTO 
					users_activity_logs 
				SET
					clientId = ?, branchId = ?, page = ?, itemId = ?, 
					description = ?, userId = ?, user_agent = ?
			");
			$stmt->execute([
				(empty($clientId) ? $this->clientId : $clientId), 
				(empty($branchId) ? $this->session->branchId : $branchId),
				$page, $itemId, 
				$description, (empty($userId) ? $this->session->userId : $userId), $ur_agent
			]);

		} catch(PDOException $e) {
			return false;
		}
	}

	/**
	 * @method dataMonitoring
	 * @param string $data_type	This is the data that the user is updating (employee, leave)
	 * @param string $unique_id	This is the unique id that defines a recordset
	 * @param json $data_set 	This is a json encoded data of the initial record before update
	 * @return bool
	 **/
	public function dataMonitoring($data_type, $unique_id, $data_set) : bool {

		try {

			$ur_agent = $this->platform .' | '.$this->browser . ' | '.ip_address();

			$stmt = $this->pos->prepare("
				INSERT INTO 
					data_monitoring 
				SET 
					data_type = ?, unique_id = ?, data_set = ?, user_id = ?, user_agent = ?, clientId = ?
			");
			return $stmt->execute([$data_type, $unique_id, $data_set, $this->session->userId, $ur_agent, $this->clientId]);

		} catch(PDOException $e) {
			return false;
		}
	}

	/**
	 * @method percentageCalculator
	 * @param totalAmount	This is the Amount that the percentage is to be calculated on
	 * @param percentageValue	This is the percentage value that is to be used for the calculation
	 * @return number_format of the value result
	 **/
	public function percentageCalculator($totalAmount, $percentageValue) {

		return number_format(
			(
				($percentageValue / 100 ) * $totalAmount), 2
			);

	}

	/**
	 * @method dateFormater
	 * @param string $dateParam
	 * @return date
	 **/
	public function dateFormater(array $dateParam) {
		$dateParam = (object)$dateParam;
		$date = ($dateParam->date) ?? date("Y-m-d");
		$period = ($dateParam->period) ?? "+1 days";
		$format = ($dateParam->format) ?? "Y-m-d";

		return date("$format", strtotime($date . " $period "));
	}

	/**
	 * @method sendEmail
	 * @param $fullname	- This is the fullname of the recipient
	 * @param $subject - This will contain the subject of the mail
	 * @param $sent_to - This is the email address of the recipient
	 * @param $copy_to - This will contain the list of users to copy the message to
	 * @param $sent_from - This is the email from which the mail will be sent 
	 * @param $message - This will contain the actual content of the email
	 * @return bool
	 **/
	public function sendEmail($unique_id, $template = 'default', $fullname = null, $subject, $sent_to, $message, $copy_to = null) : bool {

		$stmt = $this->pos->prepare("
			INSERT INTO emails SET unique_id = ?, template=?, subject = ?, fullname = ?, sent_to = ?, message = ?, copy_to = ?, clientId = ?
		");
		return $stmt->execute([$unique_id, $template, $subject, $fullname, $sent_to, $message, $copy_to, $this->clientId]);
	}

	/**
	 * @method allowedTime
	 * @desc Check if the User is within the time frame for logging an attendance
	 * @return bool
	 */
	public function allowedTime($openingHour = "5:00", $closinghour = "23:59") {
		
	    $currentTime = DateTime::createFromFormat('H:i', date("H:i"));
		$fromTime = DateTime::createFromFormat('H:i', $openingHour);
		$endTime = DateTime::createFromFormat('H:i', $closinghour);

		if ($currentTime > $fromTime && $currentTime < $endTime) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * This method fetches rows and returns it as an object
	 * @param string $table this is the table name
	 * @param string $columns This is the columns that the user wants to fetch the value
	 * @param string $where_clause this is the where clause to apply to the call
	 * LEFT JOIN query can be added to this call by appending it after the table name query
	 * @return Object 
	 **/
	public function getAllRows($table, $columns, $where_clause = 1) {
		$response = false;
		$stmt = $this->pos->prepare("SELECT {$columns} FROM {$table} WHERE {$where_clause}");
		// print ("SELECT {$columns} FROM {$table} WHERE {$where_clause}");
		if ($stmt->execute()) {
			$response = $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		return $response;
	}

	/**
	 * @method stringToArray
	 * @desc Converts a string to an array
	 * @param $string The string that will be converted to the array
	 * @param $delimeter The character for the separation
	 * @return bool
	 */
	public function stringToArray($string, $delimiter = ",") {
		$array = [];
		$expl = explode($delimiter, $string);
		foreach($expl as $each) {
			$array[] = $each;
		}
		return $array;
	}

	/**
	 * @method attachmentsTotalSize
	 * @desc calculates the size of all attachements in the session tree
	 * @return string size of files in actual form
	 **/
	public function attachmentsTotalSize() {

		//: Process the email attachments
		if(!empty($this->session->reportsAttachment) && is_array($this->session->reportsAttachment)) {
			
			// calculate the file size
			$totalFileSize = 0;
			
			// using foreach loop to get the list of attached documents
			foreach($this->session->reportsAttachment as $key => $values) {
				
				//: get the file size
				$n_FileSize = file_size_convert("assets/reports/tmp/{$values['item_id']}");
				$n_FileSize_KB = file_size("assets/reports/tmp/{$values['item_id']}");
				$totalFileSize += $n_FileSize_KB;
			}

			return round(($totalFileSize / 1024), 2);
		}
	}

	/**
	 * This method fetches the last column value of any table
	 * @param string $column This is the column that the user wants to fetch the value
	 * @param string $table this is the table name
	 * @return bool
	 **/
	public function maximumColumnId($column, $table) {
		
		try {
			$stmt = $this->pos->prepare("SELECT MAX(`$column`) as ID FROM `$table`");
			$stmt->execute();
			while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
				return $result->ID;
			}
		} catch(PDOException $e) { }
	}

	/**
	 * This method fetches the last column value of any table
	 * @param string $column This is the column that the user wants to fetch the value
	 * @param string $table this is the table name
	 * @return bool
	 **/
	public function lastColumnValue($column, $table) {
		
		try {
			$stmt = $this->pos->prepare("SELECT `$column` as last_column FROM `$table` ORDER BY id DESC LIMIT 1");
			$stmt->execute();
			while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
				return $result->last_column;
			}
		} catch(PDOException $e) { }
	}

	/**
	 * This method generates a unique id by adding preceeding zeros to the number
	 * @param string $requestId this is the id that has been submitted to be reformated.
	 * @param string $number This is the number of zeros to add
	 * @return bool
	 **/
	public function orderIdFormat($requestId, $number = 5) {
		$preOrder = str_pad($requestId, $number, '0', STR_PAD_LEFT);
		return $preOrder;
	}

	/**
	 * This method inserts into a table 
	 * @param string $table This is the table name to be updated
	 * @param string $columnValues These are the columns to update
	 * @return bool
	 **/
	public function addData($table, $columnValues) {
		$response = false;
		$stmt = $this->pos->prepare("INSERT INTO {$table} SET {$columnValues}");
		if ($stmt->execute()) {
			$response = true;
		}
		return $response;
	}

	/**
	 * This method updates a table 
	 * @param string $table This is the table name to be updated
	 * @param string $columnValues These are the columns to update
	 * @param string $where_clause This is the clause to apply
	 * @return bool
	 **/
	public function updateData($table, $columnValues, $where_clause) {
		$response = false;
		$stmt = $this->pos->prepare("UPDATE {$table} SET {$columnValues} WHERE {$where_clause}");
		if ($stmt->execute()) {
			$response = true;
		}
		return $response;
	}

	/**
	 * This method counts the number of rows in a particular table
	 * @param string $table This is the table name
	 * @param string $where_clause This is the clause to be used for the query
	 * @return numeric Number of row count
	 */
	public function countRows($table, $where_clause = 1) {
		$stmt = $this->pos->prepare("SELECT * FROM $table WHERE $where_clause");
		$stmt->execute();
		return $stmt->rowCount();
	}

	/**
     * A method to convert to decimal place
     * @param Float $isValue Pass value to convert
     * @return String
     **/
    public function toDecimal($isValue, $decimal = 2, $delimiter = "") {
        $this->_total = number_format((float)$isValue, $decimal, '.', $delimiter);

        return $this->_total;
    }

    /**
     * @method percent Different
     * @desc This method calculates the percentage difference between two values
     * @param numeric or float $current_value The first value to be compared
     * @param numeric or float $previous_value The value to compare with
     * @return string
     **/
    public function percentDifference($current_value, $previous_value) {

		$percentage = 0;

		// find the difference between the values
		$difference = ($current_value - $previous_value);
		//print $current_value;

		// find the percentage of the difference
		$percentage = ($difference != 0 && $current_value != 0) ? (($difference / $current_value)) * 100 : 0;

		// ensure that it is not in negative mode
		$percentage = ($percentage < 0) ? ($percentage * -1) : $percentage;

		if($current_value > $previous_value) {
			$prefix = '<span class="text-success"><i class="fa fa-arrow-circle-up"></i> '. $this->toDecimal($difference, 2, ',');
		} else {
			$prefix = '<span class="text-danger"><i class="fa fa-arrow-circle-down"></i> '. $this->toDecimal($difference, 2, ',');
		}

		$percentile = $prefix . ' ('.number_format(round($percentage), 0).'%)</span>';
		
		return $percentile;
	}

	/**
	 * This reverts all transactions that were not correctly processed
	 * @method revertTransaction
	 * @param no parameter (users the logged in client as the reference)
	 * @return bool
	 **/
	public function revertTransaction($clientId = null) {

      try {

      	// set the client id
      	$clientId = (empty($clientId)) ? $this->clientId : $clientId;

        // fetch the sale item
        $stmt = $this->pos->prepare("
          SELECT * FROM sales WHERE revert = ? AND clientId = ?
        ");
        $stmt->execute([1, $clientId]);

        // loop through each sale
        while($eachSale = $stmt->fetch(PDO::FETCH_OBJ)) {

          // foreach sale record fetch the sales details
          $stmt2 = $this->pos->prepare("
            SELECT * FROM sales_details WHERE order_id = ?
          ");
          $stmt2->execute([$eachSale->order_id]);

          // loop through the list and update the table respectively
          while($eachDetail = $stmt2->fetch(PDO::FETCH_OBJ)) {
            
            // update the equivalent product information
            $this->pos->query("UPDATE products SET quantity = (quantity+{$eachDetail->product_quantity}) WHERE id = '{$eachDetail->product_id}'");
          }

          // record the activity
          $this->userLogs('pos-revert', $eachSale->order_id, 'The Transaction recorded at the Point of Sale has been reverted. Reason: Unable to process payment.');

          // update the sale record when the process is done
          $this->pos->query("UPDATE sales SET revert = '0' WHERE id = '{$eachSale->id}'");
        }

        return true;
      } catch(PDOException $e) {
        return false;
      }
    }
}
?>