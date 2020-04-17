<?php
// ensure this file is being included by a parent file
if( !defined( 'SITE_URL' ) && !defined( 'SITE_DATE_FORMAT' ) ) die( 'Restricted access' );

class Customers extends Pos {

	# Main PDO Connection Instance
	protected $db;
	protected $session;

	public function __construct($clientId = null){
		parent::__construct();

		$this->clientId = (!empty($clientId)) ? $clientId : $this->clientId;
	}

	public function fetch($columns = "*", $whereClause = null, $leftJoin = null, $limit = 100000){
		$sql = "SELECT $columns FROM customers a {$leftJoin} WHERE a.clientId = ? AND a.status = ? {$whereClause} ORDER BY a.id DESC LIMIT {$limit}";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$this->clientId, 1]);
		return $stmt->fetchAll(PDO::FETCH_OBJ);	
	}

	public function quickAdd(stdClass $postData) {

		try {

			if(!empty($postData)) {

				// insert user organization details first
				$customer_id = (empty($postData->customer_id)) ? "POS".random_string('nozero', 12) : $postData->customer_id;
								
				$stmt = $this->db->prepare("INSERT INTO customers SET customer_id=?, firstname=?, lastname=?, phone_1=?, title=?, owner_id=?, email=?, branchId = ?, clientId = ?, phone_2 = ?, residence = ?, postal_address =?");
				
				if($stmt->execute([
					$customer_id, 
					$postData->nc_firstname, 
					$postData->nc_lastname, 
					$postData->nc_contact,
					$postData->nc_title, 
					$postData->userId,
					$postData->nc_email,
					$postData->branchId,
					$postData->clientId,
					(isset($postData->n_contact2)) ? $postData->n_contact2 : null,
					(isset($postData->residence)) ? $postData->residence : null,
					(isset($postData->postal_address)) ? $postData->postal_address : null
				])) {
					$this->userLogs('customer', $customer_id, 'Added a new Customer');
					return (object)[
						$customer_id, 
						$postData->nc_firstname, 
						$postData->nc_lastname,
						$postData->nc_email,
						$postData->nc_contact,
					];
				}
				return false;
			}

		} catch(PDOException $e) { return false; }
	}

	public function quickUpdate(stdClass $postData) {

		try {

			if(!empty($postData)) {

				// insert user organization details first				
				$stmt = $this->db->prepare("
					UPDATE customers SET residence = ?, firstname=?, lastname=?, phone_1=?, title=?, email=?, postal_address = ?, phone_2 = ?
					WHERE clientId = ? AND customer_id = ?
				");
				
				if($stmt->execute([
					$postData->residence, 
					$postData->nc_firstname,
					$postData->nc_lastname,
					$postData->nc_contact,
					$postData->nc_title, 
					$postData->nc_email,
					(isset($postData->postal_address)) ? $postData->postal_address : null,
					(isset($postData->n_contact2)) ? $postData->n_contact2 : null,
					$postData->clientId,
					$postData->customer_id
				])) {
					$this->userLogs('customer', $postData->customer_id, 'Updated the customer details');
					return true;
				}
				return false;
			}

		} catch(PDOException $e) { return false; }
	}
	
}