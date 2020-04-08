<?php 

class Orders extends Pos {
	const PAYMENT_CARD = "card";
	const PAYMENT_CASH = "cash";
	const PAYMENT_MOMO = "MoMo";
	const PAYMENT_CREDIT = "credit";
	const CONFIRMED = "confirmed";
	const PENDING = "pending";

	public function __construct() {
		parent::__construct();
	}

	public function saveRegister(stdClass $register){

		if(empty($register->products)) return false;

		$computed = (object)[
			"creditSales" => $register->payment_type == self::PAYMENT_CREDIT ?  "1" : "0",
			"branchId" => $this->session->branchId,
			"source" => "Argon",
			"orderStatus" => in_array($register->payment_type, [self::PAYMENT_CARD, self::PAYMENT_MOMO]) ? self::PENDING : self::CONFIRMED,
			"orderId" => "POS".date("Ym")."{$this->orderIdFormat($this->session->branchId, 2)}".$this->orderIdFormat(($this->maximumColumnId("id", "sales"))+2),
			"transactionId" => random_string('nozero', 12),
			"currentUser" => $this->session->userId,
			"productsCount" => count($register->products),
			"amountPaid" => $register->amount_paying
		];

		// form the sql query to parse
		$salesDetailsSQL = "INSERT INTO sales_details(clientId, branchId, auto_id, order_id, product_id, product_quantity, product_unit_price, product_cost_price, product_total) VALUES ";
		$updateQuantity = "";
		$amountPayable = 0;

		$counter = 0;

		array_map(function($product) use (&$amountPayable, &$salesDetailsSQL, $register, $computed, &$counter, &$updateQuantity){

			$counter ++;
			$productId = $product['id'];
			$productQty = $product['qty'];
			$productPrice = $this->thisProductInfo($productId, 'product_price');
			$costPrice = $this->thisProductInfo($productId, 'cost_price');
			$productTotal = round($productPrice*$productQty, 2);
			$amountPayable += $productTotal;

			$salesDetailsSQL .= "(";
			$salesDetailsSQL .= "'".$this->session->clientId."', ";
			$salesDetailsSQL .= $computed->branchId.", ";
			$salesDetailsSQL .= "'".random_string('alnum', 25)."', ";
			$salesDetailsSQL .= "'".$computed->orderId."', ";
			$salesDetailsSQL .= "'".$productId."', ";
			$salesDetailsSQL .= $productQty.", ";
			$salesDetailsSQL .= $productPrice.", ";
			$salesDetailsSQL .= $costPrice.", ";
			$salesDetailsSQL .= $productTotal." ";
			$salesDetailsSQL .= ")";
			if($counter < $computed->productsCount) $salesDetailsSQL .= ",";

			$updateQuantity .= "UPDATE products SET quantity = (quantity-$productQty) WHERE id = '$productId'";

			if($counter < $computed->productsCount) $updateQuantity .= ";";

		}, $register->products);

		// assign the amount being paid
		$register->amount_paying = (!empty($register->amount_paying)) ? $register->amount_paying : 0;

		// order discount mechanism
		if($register->discountType == "cash") {
			$orderDiscount = (int) $register->discountAmount;
		} elseif($register->discountType == "percentage") {
			// calculate a percentage of the today order amount
			$orderDiscount = ((int)$register->discountAmount/100) * $amountPayable;
			$orderDiscount = number_format($orderDiscount, 2);
		}

		// generate the 
		$overallOrderTotal = $amountPayable;
		$amountPayable = ($amountPayable - $orderDiscount);

		// calculate the balance
		$totalBalance = ($register->payment_type != self::PAYMENT_CREDIT) ? ($register->amount_paying-$amountPayable) : ($overallOrderTotal-$orderDiscount);

		// form the sales query
		$salesSQL = "INSERT INTO sales(
				clientId, source, branchId, order_id, customer_id, 
				recorded_by, credit_sales, order_amount_paid, order_discount, 
				order_status, overall_order_amount, order_amount_balance, 
				payment_type, transaction_id
		) VALUES (
			'{$this->session->clientId}',
			'{$computed->source}',
			'{$computed->branchId}',
			'{$computed->orderId}',
			'{$register->customer}',
			'{$computed->currentUser}',
			'{$computed->creditSales}',
			'{$amountPayable}',
			'{$orderDiscount}',
			'{$computed->orderStatus}',
			'{$overallOrderTotal}',
			'{$totalBalance}',
			'{$register->payment_type}',
			'{$computed->transactionId}'
		)";

		try {
			$this->pos->beginTransaction();
			
			$this->pos->exec($salesSQL);
			$this->pos->exec($salesDetailsSQL);
			$this->pos->exec($updateQuantity);

			if($this->pos->commit()){
				
				$this->userLogs('pos', $computed->orderId, 'Recorded a new Sale at the POS');
				$this->session->set_userdata("_oid_Generated", $computed->orderId);
				$this->session->set_userdata("_uid_Generated", $register->customer);

				return (object) [
					"orderId" => $computed->transactionId,
					"_oid" => $computed->orderId,
					"orderTotal" => $amountPayable
				];
			}

		} catch (PDOException $e) {
			sendJSON($e->getMessage(),1);
			$this->pos->rollback();
			return false;			
		}
	}

	public function thisProductInfo($productId, $column) {
		$stmt = $this->pos->prepare("SELECT $column FROM products WHERE id = ?");
		$stmt->execute([$productId]);
		$result = $stmt->fetch(PDO::FETCH_OBJ);

		return $result->$column;
	}

	public function quickMail(stdClass $mail) {

		try {
			$stmt = $this->pos->prepare("
				INSERT INTO email_list 
				SET clientId = ?, branchId = ?,
					template_type = ?, itemId = ?, recipients_list = ?,
					request_performed_by = ?
			");
			$this->userLogs('pos-invoice', $mail->itemId, 'Requested to send this receipt has mail.');
			return $stmt->execute([
				$this->session->clientId, $mail->branchId, $mail->template_type, 
				$mail->itemId, json_encode($mail->recipients_list),
				$this->session->userId
			]);
		} catch(PDOException $e) {
			return false;
		}

	}

	public function saleDetails($orderId) {
		// global variables
		global $accessObject;

		// where clause for the user role
		$branchAccess = '';
		$accessLimit = '';
		$clientAccess = " AND a.clientId = '{$this->session->clientId}'";

		// create new objects
		$accessObject->userId = $this->session->userId;

		// parse the access information
		if(!$accessObject->hasAccess('monitoring', 'branches')) {
			$branchAccess = " AND a.branchId = '{$this->session->branchId}'";
			$accessLimit = " AND a.recorded_by = '{$this->userId}'";
		}

		//: search for the product details
		$stmt = $this->pos->prepare("
			SELECT 
				a.id, a.clientId, a.branchId, a.mode, a.order_id, a.customer_id,
				a.recorded_by, a.order_amount_paid, a.order_discount, 
				a.order_amount_balance, a.overall_order_amount, a.log_date,
				a.payment_type, a.transaction_id, a.payment_date,
				a.order_date, a.source, a.ordered_by_id,
				a.credit_sales, a.order_status, DATE(a.order_date) AS today_date,
				CONCAT(b.firstname, ' ', b.lastname) AS customer_fullname,
				b.phone_1 AS customer_contact,
				HOUR(a.order_date) AS hour_of_day,
				(SELECT SUM(c.product_cost_price*c.product_quantity) FROM sales_details c WHERE c.order_id = a.order_id) AS total_cost_price,
				(SELECT SUM(c.product_unit_price*c.product_quantity) FROM sales_details c WHERE c.order_id = a.order_id) AS total_expected_selling_price
			FROM
				sales a 
			LEFT JOIN customers b ON b.customer_id = a.customer_id
			WHERE 
				a.order_id LIKE '%{$orderId}%'
			AND 
				a.deleted='0' {$branchAccess} {$accessLimit} {$clientAccess}
		");
		$stmt->execute();

		$data = [];
		while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
			$result->saleItems = $this->getAllRows("sales_details a LEFT JOIN products b ON b.id=a.product_id", "a.*, b.product_title", "a.order_id='{$result->order_id}'");
			$data[] = $result;
		}

		return $data;
	}
}