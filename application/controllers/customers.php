<?php
// ensure this file is being included by a parent file
if( !defined( 'SITE_URL' ) && !defined( 'SITE_DATE_FORMAT' ) ) die( 'Restricted access' );

class Customers extends Pos {

	# Main PDO Connection Instance
	protected $db;
	protected $session;

	public function __construct(){
		parent::__construct();
	}

	public function fetch($columns = "*", $whereClause = null){
		$sql = "SELECT $columns FROM customers WHERE clientId = ? AND status = ? AND branchId = ? {$whereClause} ORDER BY firstname, lastname";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$this->clientId, "1", $this->session->branchId]);
		return $stmt->fetchAll(PDO::FETCH_OBJ);	
	}

	public function quickAdd(stdClass $customerData) {

		try {

			if(!empty($customerData)) {

				// insert user organization details first
				$customer_id = "EVC".random_string('nozero', 12);
								
				$stmt = $this->db->prepare("INSERT INTO customers SET customer_id=?, firstname=?, lastname=?, phone_1=?, title=?, owner_id=?, email=?, branchId = ?, clientId = ?");
				
				if($stmt->execute([
					$customer_id, 
					$customerData->nc_firstname, 
					$customerData->nc_lastname, 
					$customerData->nc_contact,
					$customerData->nc_title, 
					$this->session->userId,
					$customerData->nc_email,
					$this->session->branchId,
					$this->clientId
				])) {
					$this->userLogs('customer', $customer_id, 'Added a new Customer');
					return (object)[
						$customer_id, 
						$customerData->nc_firstname, 
						$customerData->nc_lastname,
						$customerData->nc_email,
						$customerData->nc_contact
					];
				}
				return false;
			}

		} catch(PDOException $e) { return false; }
	}

	public function quickUpdate(stdClass $customerData) {

		try {

			if(!empty($customerData)) {

				// insert user organization details first				
				$stmt = $this->db->prepare("UPDATE customers SET residence = ?, firstname=?, lastname=?, phone_1=?, title=?, email=? WHERE branchId = ? AND clientId = ? AND customer_id = ?");
				
				if($stmt->execute([
					$customerData->residence, 
					$customerData->nc_firstname, 
					$customerData->nc_lastname, 
					$customerData->nc_contact,
					$customerData->nc_title, 
					$customerData->nc_email,
					$this->session->branchId,
					$this->clientId,
					$customerData->customer_id
				])) {
					$this->userLogs('customer', $customerData->customer_id, 'Updated the customer details');
					return true;
				}
				return false;
			}

		} catch(PDOException $e) { return false; }
	}
	
}