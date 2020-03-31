<?php 

class Requests extends Pos {
	
	public function __construct(){
		parent::__construct();
	}
		
	public function addRequest($request, $customerId, $discountAmt, $discountType, $productsList) {

		// confirm that the customer id has been set
		if(isset($customerId)){
					
			#begin multiple tansactions
			$this->db->beginTransaction();
					
			# using the try functionality to process the form
			try {
				#fetch the browser details
				$browser = $this->browser." ".$this->platform;
				$ip = ip_address();
				$cartOutput = "";
				$cartTotal = 0;

				// Start the For Each loop
				$i = 0;
				$cartTotal = 0;

				# orderid is always the last order id plus 1
				$requestId = (($request == 'Quote') ? 'QT' : 'ORD').date("Ym").$this->orderIdFormat(($this->maximumColumnId("id", "requests"))+2);

				// get the list of cart data and insert 
				foreach($productsList as $eachProduct) {

					#split the cart session 
					$productId = $eachProduct["productId"];

					#query the database
					$sql = $this->db->prepare("SELECT id FROM products WHERE id=? LIMIT 1");
					$sql->execute([$productId]);

					#fetch the results
					while($row = $sql->fetch(PDO::FETCH_OBJ)) {
						
						#mechanism for price totalling
						$itemTotal = ($eachProduct['productQuantity'] * $eachProduct["productPrice"]);
						$cartTotal += $itemTotal;
						
						#record the entire user cart details
						$stmt = $this->db->prepare("
							INSERT INTO requests_details 
							SET request_id = ?, product_id = ?, product_quantity = ?, product_price = ?, product_total = ?
						");
						$stmt->execute([
							$requestId, $productId, $eachProduct['productQuantity'],
							$eachProduct['productPrice'], $itemTotal
						]);
					}
				}

				// order discount mechanism
				if($discountType == "cash") {
					$requestDiscount = (int) $discountAmt;
				} elseif($discountType == "percentage") {
					// calculate a percentage of the today order amount
					$requestDiscount = ((int)$discountAmt/100) * $cartTotal;
					$requestDiscount = number_format($requestDiscount, 2);
				}
				
				// continue validation
				$overallTotal = (int)($cartTotal) - (int)($requestDiscount);

				#insert the order
				$stmt = $this->db->prepare("
					INSERT INTO requests 
					SET	clientId = ?, branchId = ?, request_id = ?, customer_id = ?,
						request_type = ?, request_total = ?, request_discount = ?,
						recorded_by = ?, request_overall = ?
				");
				$stmt->execute([
					$this->session->clientId, $this->session->branchId, $requestId,
					$customerId, $request, $overallTotal, $requestDiscount, $this->session->userId,
					$cartTotal
				]);
	
				#end the query
				$this->db->commit();
				
				return true;
					
				
		
			} catch (PDOException $e) {
				#cancel all activities that was previously done.
				$this->db->rollback();
				#print error message
				return false;
			}
				
			
				
		}
	}
	
}