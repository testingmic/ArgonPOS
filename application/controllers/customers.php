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

	public function allContactDetails($columns = "cut.*,") {

		try {
			// run the sql query
			$stmt = $this->db->prepare("
				SELECT 
					$columns CONCAT(cut.firstname, ' ', cut.lastname) AS fullname,
					cont.country_name, cc.name AS company_name,
					(SELECT COUNT(*) FROM sales WHERE order_status = ?) AS orders_count
				FROM
					customers cut 
				LEFT JOIN countries cont ON cont.id = cut.country
				LEFT JOIN customers_company cc ON cut.company_id = cc.id
				WHERE 
					cut.status = ? AND cut.clientId = ?
			");
			$stmt->execute([1, 1, $this->clientId]);

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
			return false;
		}
	}
	
	public function contactDetails($contactId, $columns = "cut.*,") {

		try {
			// run the sql query
			$stmt = $this->db->prepare("
				SELECT 
					$columns CONCAT(cut.firstname, ' ', cut.lastname) AS fullname,
					cont.country_name, cc.name AS company_name,
					(SELECT COUNT(*) FROM sales WHERE customer_id = ? AND order_status = ?) AS orders_count
				FROM
					customers cut 
				LEFT JOIN countries cont ON cont.id = cut.country
				LEFT JOIN customers_company cc ON cut.company_id = cc.id
				WHERE 
					cut.customer_id = ? AND cut.status = ? AND cut.clientId = ?
			");
			$stmt->execute([$contactId, 1, $contactId, 1, $this->clientId]);

			return $stmt->fetch(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
			return false;
		}
	}

	public function companyDetails($companyId) {

		try {
			// run the sql query
			$stmt = $this->db->prepare("
				SELECT 
					com.*
				FROM
					customers_company com 
				WHERE com.id = ? AND com.status = ?
			");
			$stmt->execute([$companyId, 1]);

			return $stmt->fetch(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
			return false;
		}
	}
	

	function fetch($columns = "*", $whereClause = null){
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

	//
	public function addCustomer(stdClass $postData) {

		try {

			if(!empty($postData)) {

				// insert user organization details first
				$customer_id = "EVC".random_string('nozero', 12);
				
				if(!empty($postData->company_id) && ($this->countRows('customers_company', "id='{$postData->company_id}'") > 0)) {
					$n_company_id = $postData->company_id;
				} else {
					$n_company_id = $this->quickInsertReturnId("customers_company", 
						"INSERT INTO customers_company 
							SET name='{$postData->company_name}',
							clientId='{$this->clientId}'
						"
					);
				}

				$stmt = $this->db->prepare("INSERT INTO customers SET clientId = ?, customer_id=?, firstname=?, lastname=?, phone_1=?, phone_2=?, email=?, residence=?, country=?, city=?, relationship_manager=?, department=?, title=?, owner_id=?, company_id=?, postal_address = ?, nationality = ?,
					branchId = ?, date_of_birth = ?
				");
				
				return $stmt->execute([
					$this->clientId, $customer_id, $this->sentenceCase($postData->firstname), 
					$this->sentenceCase($postData->lastname), $postData->phone1, 
					$postData->phone2, $postData->email, $postData->residence, 
					$postData->country, $postData->city, $postData->relationship_manager, 
					$postData->department, $postData->title, 
					$this->session->userId, $n_company_id, $postData->postal,
					$postData->nationality, $this->session->branchId, $postData->date_of_birth
				]);
			}

		} catch(PDOException $e) { return false; }
	}

	public function updateCustomer(stdClass $postData) {

		try {

			if(!empty($postData)) {

				if(!empty($postData->company_id) && ($this->countRows('customers_company', "id='{$postData->company_id}'") > 0)) {
					$n_company_id = $postData->company_id;
				} else {
					$n_company_id = $this->quickInsertReturnId("customers_company", 
						"INSERT INTO customers_company 
							SET name='{$postData->company_name}',
							clientId='{$this->clientId}'
						"
					);

					$this->updateFirmCompanies($this->clientId);
				}

				$stmt = $this->db->prepare("UPDATE customers SET firstname=?, lastname=?, phone_1=?, phone_2=?, email=?, residence=?, country=?, city=?, relationship_manager=?, department=?, title=?, company_id=?,  postal_address = ?, nationality = ?, date_of_birth = ? WHERE customer_id=? AND clientId = ?");
				
				return $stmt->execute([
					$this->sentenceCase($postData->firstname), 
					$this->sentenceCase($postData->lastname), $postData->phone1, 
					$postData->phone2, $postData->email, 
					$postData->residence, $postData->country, 
					$postData->city, $postData->relationship_manager, $postData->department, 
					$postData->title, $n_company_id, $postData->postal,
					$postData->nationality, $postData->date_of_birth,
					$postData->customer_id, $this->clientId
				]);
			}

			return true;

		} catch(PDOException $e) { return false; }
	}

	public function addOrganization(stdClass $postData) {

		$stmt = $this->db->prepare("INSERT INTO customers_company SET item_utype=?,  name=?, contact=?, address=?, email=?, industry=?, comments=?, owner_id=?, website=?");
		
		return $stmt->execute([$postData->user_type, $postData->name, $postData->contact, $postData->address, $postData->email, $postData->industry, $postData->comments, $this->session->userId, $postData->website]);

	}

	public function updateOrganization(stdClass $postData)  {

		$stmt = $this->db->prepare("UPDATE customers_company SET item_utype=?, name=?, contact=?, address=?, email=?, industry=?, comments=?, website=? WHERE id=?");
		
		return $stmt->execute([$postData->user_type, $postData->name, $postData->contact, $postData->address, $postData->email, $postData->industry, $postData->comments, $postData->website, $postData->id]);

	}

	public function deleteCustomer($customer_id) {
		$stmt = $this->db->prepare("UPDATE customers SET status=? WHERE customer_id=?");
		
		return $stmt->execute([0, $customer_id]);
	}

	public function deleteOrganization($companyId) {
		try {
			// update the status of the organization
			$stmt = $this->db->prepare("UPDATE customers_company SET status=? WHERE id=?");
			$stmt->execute([0, $companyId]);
			// remove all contacts who is attached to this organization
			$stmt2 = $this->db->prepare("UPDATE customers SET company_id=? WHERE company_id=?");
			$stmt2->execute([NULL, $companyId]);
			// remove all leads who is attached to this organization
			$stmt3 = $this->db->prepare("UPDATE leads SET leads_firm_id=? WHERE leads_firm_id=?");
			$stmt3->execute([NULL, $companyId]);
			// return true
			return true;
		} catch(PDOException $e) {
			return false;
		}
	}

	public function lastRowId($table) {
		$stmt = $this->db->prepare("SELECT id FROM $table WHERE status=? ORDER BY id DESC LIMIT 1");
		$stmt->execute([1]);

		$result = $stmt->fetch(PDO::FETCH_OBJ);
		return $result->id;
	}
}