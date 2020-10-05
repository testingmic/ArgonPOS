<?php
// global variables
global $posClass, $accessObject, $SITEURL;
$data = 'Data successfully Synchronized';
$request = '';
$status = 500;
$syncTable = null;
$responseData = array();

// check if user is logged in
if($admin_user->logged_InControlled()) {

	// client data
	$clientData = $posClass->getAllRows(
		"settings", "*", 
		"clientId='{$session->clientId}'
	");

	// client access controls
	$brAccess = '';
	$branchAccess = '';
	$branchAccessInner = '';
	$accessLimit = '';
	$accessLimitInner = '';
	$customerLimit = '';
	$customerLimitInner = '';
	$accessLimitInner2 = '';
	$clientAccess = " AND a.clientId = '{$session->clientId}'";
	$clientAccessInner = " AND b.clientId = '{$session->clientId}'";
	
	$accessObject->userId = $session->userId;

	// use the access level for limit contents that is displayed
	if(!$accessObject->hasAccess('monitoring', 'branches')) {
		$brAccess = " AND a.id = '{$session->branchId}'";
		$branchAccess = " AND a.branchId = '{$session->branchId}'";
		$branchAccessInner = " AND b.branchId = '{$session->branchId}'";
		$accessLimit = " AND a.recorded_by = '{$session->userId}'";
		$accessLimitInner = " AND b.recorded_by = '{$session->userId}'";
		$accessLimitInner2 = " AND c.recorded_by = '{$session->userId}'";
	}

	// set the data set
	$clientData = $clientData[0];
	
	//: Synchronize data with that which is online
	if(confirm_url_id(1, 'sync') && confirm_url_id(2)) {
		
		//: loading the data
		$syncData = (array) $_POST;
		$syncTable = xss_clean($SITEURL[2]);

		$i = 0;

		//: confirm that the index db is not empty
		if(isset($syncData['syncData'])) {
			
			//: Loop through the dataset
			foreach($syncData['syncData'] as $eachData) {

				//: process this section if the data is for users
				if(confirm_url_id(2, 'users')) {
				
					//: start the checks
					$userId = xss_clean($eachData['user_id']);

					if(isset($eachData['deleted']) && ($eachData['deleted'] == 1)) {
						$stmt = $pos->prepare("UPDATE users SET status='0' WHERE user_id='{$userId}'");
						$stmt->execute();
					} else {
						$email = xss_clean($eachData['email']);
						$access_level_id = xss_clean($eachData['access_level_id']);
						$gender = xss_clean($eachData['gender']);
						$branchId = xss_clean($eachData['branchId']);
						$fullname = xss_clean($eachData['fullname']);
						$contact = xss_clean($eachData['contact']);
						
						//: check if the user already exists
						$checkData = $posClass->getAllRows("users", "COUNT(*) AS proceed, access_level", "email='".($email)."' && user_id='".($userId)."' && status = '1'");

						//: if the record does not already exist
						if ($checkData != false && $checkData[0]->proceed == '0') {
							// Add Record To Database
							$getUserId   = $userId;
							$getPassword = random_string('alnum', mt_rand(8, 10));
							$hashPassword= password_hash($getPassword, PASSWORD_DEFAULT);

							$response = $posClass->addData(
								"users" ,
								"clientId='{$session->clientId}', user_id='{$getUserId}', name='{$fullname}', gender='{$gender}', email='{$email}', phone='{$contact}', access_level='{$access_level_id}', branchId='{$branchId}', password='{$hashPassword}'"
							);

							$accessObject->assignUserRole($getUserId, $access_level_id);

						} else {
							//: update the users datable
							$response = $posClass->updateData(
								"users" ,
								"clientId='{$session->clientId}', name='{$fullname}', gender='{$gender}', email='{$email}', phone='{$contact}', access_level='{$access_level_id}', branchId='{$branchId}'",
								"user_id='{$userId}' && clientId='{$session->clientId}'"
							);

							// check if the user has the right permissions to perform this action
							if($accessObject->hasAccess('accesslevel', 'users')) {

								// Check If User ID Exists
								$userRole = $posClass->getAllRows("user_roles", "COUNT(*) AS userTotal, permissions", "user_id='{$userId}'");

								// confirm if the user has no credentials
								if($userRole[0]->userTotal == 0) {
									// insert the permissions to this user
									$getPermissions = $accessObject->getPermissions($access_level_id);
									// assign these permissions to the user
									$accessObject->assignUserRole($userId, $access_level_id);
								}

								// Check Access Level
								if ($access_level_id != $checkData[0]->access_level) {

									$getPermissions = $accessObject->getPermissions($access_level_id);

									$accessObject->assignUserRole($userId, $access_level_id, $getPermissions);
								}
							}

						}

					}

				}

				//: run this section if the data is for sales
				elseif(confirm_url_id(2, 'sales') && isset($eachData['order_id'])) {

					//: assign the variables
					$orderId = xss_clean($eachData['order_id']);

					//: check if the user already exists
					$checkData = $posClass->getAllRows("sales", "COUNT(*) AS proceed", "order_id='{$orderId}'");

					//: if the record does not already exist
					if ($checkData != false && $checkData[0]->proceed == '0') {

						//: confirm that the needed params were parsed
						if(isset($eachData['branchId'], $eachData['mode'], $eachData['credit_sales'], $eachData['customer_id'], $eachData['recorded_by'], $eachData['order_amount_paid'], $eachData['overall_order_amount'], $eachData['order_discount'], $eachData['payment_type'], $eachData['order_date'])) {

							$branchId = xss_clean($eachData['branchId']);
							$clientId = xss_clean($eachData['clientId']);
							$mode = xss_clean($eachData['mode']);
							$credit_sales = xss_clean($eachData['credit_sales']);
							$source = xss_clean($eachData['source']);
							$customer_id = xss_clean($eachData['customer_id']);
							$ordered_by_id = xss_clean($eachData['ordered_by_id']);
							$recorded_by = xss_clean($eachData['recorded_by']);
							$orderTotal = xss_clean($eachData['order_amount_paid']);
							$overall_order_amount = xss_clean($eachData['overall_order_amount']);
							$order_amount_balance = xss_clean($eachData['order_amount_balance']);
							$order_discount = xss_clean($eachData['order_discount']);
							$order_date = xss_clean($eachData['order_date']);
							$order_status = xss_clean($eachData['order_status']);
							$payment_type = xss_clean($eachData['payment_type']);
							$transaction_id = xss_clean($eachData['transaction_id']);

							//: execute the query
							$salesSQL = $pos->prepare("
								INSERT INTO sales SET 
									clientId = '{$clientId}', source = '{$source}', 
									branchId = '{$branchId}', order_id = '{$orderId}',
									customer_id = '{$customer_id}', recorded_by = '{$recorded_by}', 
									credit_sales = '{$credit_sales}', order_amount_paid='{$orderTotal}',
									order_discount='{$order_discount}', order_status='{$order_status}',
									overall_order_amount='{$overall_order_amount}', mode='{$mode}',
									order_amount_balance='{$order_amount_balance}', 
									order_date='{$order_date}', payment_type='{$payment_type}',
									transaction_id='{$transaction_id}'
							");
							$salesSQL->execute();


							// get the id
							foreach($eachData["saleItems"] as $eachDetail) {

								$autoId = xss_clean($eachDetail['auto_id']);

								//: check if the user already exists
								$checkData2 = $posClass->getAllRows("sales_details", "COUNT(*) AS proceed", "auto_id='{$autoId}'");

								//: if the record does not already exist
								if ($checkData2 != false && $checkData2[0]->proceed == '0') {

									// confirm all needed parameters was parsed
									if(isset($eachDetail['order_id'], $eachDetail['branchId'], $eachDetail['product_id'], $eachDetail['product_quantity'], $eachDetail['product_unit_price'], $eachDetail['product_total'])) {

										//: assign the variables
										$orderId = xss_clean($eachDetail['order_id']);
										$branchId = xss_clean($eachDetail['branchId']);
										$clientId = xss_clean($eachDetail['clientId']);
										$product_id = xss_clean($eachDetail['product_id']);
										$product_quantity = xss_clean($eachDetail['product_quantity']);
										$product_unit_price = xss_clean($eachDetail['product_unit_price']);
										$product_total = xss_clean($eachDetail['product_total']);
										$order_date = (isset($eachDetail['order_date'])) ?xss_clean($eachDetail['order_date']) : date("Y-m-d H:i:s");

										$salesDetailsSQL = $pos->prepare("
											INSERT INTO sales_details SET 
												clientId = '{$clientId}', branchId = '{$branchId}',
												order_id = '{$orderId}', product_id = '{$product_id}', 
												product_quantity = '{$product_quantity}', 
												product_unit_price = '{$product_unit_price}', 
												product_total='{$product_total}',
												order_date='{$order_date}', auto_id='{$autoId}'
										");
										$salesDetailsSQL->execute();

										$stmt2 = $pos->prepare("
											UPDATE products SET quantity = (quantity-$product_quantity) WHERE id = '$product_id'
										");
										$stmt2->execute();						
									}
								}
							}
						}

					}

				}

				//: run this section if the branches list is been syncronized
				elseif(confirm_url_id(2, 'branches')) {
					//: assign variable
					$branchData = (Object) $eachData;

					// validate the records
					if(!empty($branchData->email) && !filter_var($branchData->email, FILTER_VALIDATE_EMAIL)) {
						$data = "Please enter a valid email address";
					} elseif(!empty($branchData->contact) && !preg_match('/^[0-9+]+$/', $branchData->contact)) {
						$data = "Please enter a valid contact number";
					} elseif(!in_array($branchData->branch_type, ['Store', 'Warehouse'])) {
						$data = "Invalid branch type was submitted";
					} else {

						// Check Email Exists
						$checkData = $posClass->getAllRows("branches", "COUNT(*) AS proceed", "branch_id='{$branchData->branch_id}'
						");

						if ($checkData != false && $checkData[0]->proceed == '0') {
							$response = $posClass->addData(
								"branches" ,
								"clientId='{$session->clientId}', branch_type='{$branchData->branch_type}', branch_name='{$branchData->branch_name_text}', location='{$branchData->location}', branch_email='{$branchData->email}', branch_contact='{$branchData->contact}', branch_logo='{$clientData->client_logo}', branch_id='{$branchData->branch_id}'"
							);
						} else {
							// update user data
							$response = $posClass->updateData(
								"branches",
								"branch_name='{$branchData->branch_name_text}', location='{$branchData->location}', branch_email='{$branchData->email}',
									branch_type='{$branchData->branch_type}', 
									branch_contact='{$branchData->contact}'",
								"branch_id='{$branchData->branch_id}' && clientId='{$session->clientId}'"
							);
						}
					}
				}

				//: sync customers data
				elseif(confirm_url_id(2, 'customers')) {
					//: assign variable
					$thisData = (Object) $eachData;

					// , $thisData->branchId, $thisData->clientId

					// validate the records
					if(isset($thisData->firstname, $thisData->lastname, $thisData->title, $thisData->phone_1, $thisData->fullname, $thisData->customer_id, $thisData->clientId, $thisData->branchId)) {

						//: check if the customer id does not already exist
						$checkData = $posClass->getAllRows("customers", "COUNT(*) AS proceed", "customer_id='{$thisData->customer_id}'
						");

						//: ensure the customer credentials does not already exit
						if ($checkData != false && $checkData[0]->proceed == '0') {

							$response = $posClass->addData(
								"customers" ,
								"clientId='{$thisData->clientId}', customer_id='{$thisData->customer_id}', title='{$thisData->title}', firstname='{$thisData->firstname}', lastname='{$thisData->lastname}', email='{$thisData->email}', phone_1='{$thisData->phone_1}', branchId='{$thisData->branchId}'"
							);
						}

					}
				}

				$i++;

				$status = 200;

			}

		}

	}

	//: if the user user wants to preload some data
	elseif(isset($_POST["preloadData"], $_POST["dataType"]) && confirm_url_id(1, 'preloadData')) {

		// set the variable
		$dataType = xss_clean($_POST['dataType']);
		
		// load the data response
		if($dataType == "customers") {

			//: query string
			$stmt = $pos->prepare("
				SELECT title, customer_id, firstname, lastname, CONCAT(firstname, ' ', lastname) AS fullname, phone_1, title, email, date_of_birth, residence, gender, city, phone_2, residence 
				FROM `customers` 
				WHERE status='1' AND clientId='{$session->clientId}' AND branchId='{$session->branchId}' 
				ORDER BY firstname
			");

			// execute the statement
			$stmt->execute();

			// return the dataset
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		// request for products list
		elseif($dataType == "request_products") {
			// query the database
			$result = $posClass->getAllRows("products", 
				"id, product_image, category_id, product_title, quantity, category_id, product_image AS image, cost_price, threshold, date_added,
					product_id, product_price, product_price", "status = '1' AND branchId = '{$session->branchId}' AND clientId = '{$session->clientId}'");

			// data
			$productsList = [];
			$ii = 0;

			// initializing
			if(count($result) > 0) {

				// loop through the list of products
				foreach($result as $results) {

					// increment
					$ii++;

					$results->product_image = (empty($results->product_image)) ? "assets/images/products/default.png" : "assets/images/products/".$results->product_image;

					// add to the list to return
					$productsList[] = [
						'row_id' => $ii,
						'product_id' => $results->id,
						'product_code' => $results->product_id,
						'product_title' => $results->product_title,
						'price' => $results->product_price,
						'category_id' => $results->category_id,
						'image' => $results->image,
						'threshold' => $results->threshold,
						'cost_price' => $results->cost_price,
						'date_added' => $results->date_added,
						'product_quantity' => $results->quantity
					];
				}
			}

			$data = $productsList;
		}

		// load the sales
		elseif (in_array($dataType, ["sales", "reports"])) {
			$request = $dataType;
			# code...
			if($dataType == "sales") {
				$stmt = $pos->prepare("
					SELECT 
						a.id, a.clientId, a.branchId, a.mode, a.order_id, a.customer_id,
						a.recorded_by, a.order_amount_paid, a.order_discount, 
						a.order_amount_balance, a.overall_order_amount, a.log_date, a.state,
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
						DATE(a.order_date) = CURDATE() 
					AND 
						a.deleted='0' {$branchAccess} {$accessLimit} {$clientAccess}
				");
				$stmt->execute();

				$data = [];
				while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
					$result->saleItems = $posClass->getAllRows("sales_details a LEFT JOIN products b ON b.id=a.product_id", "a.*, b.product_title", "a.order_id='{$result->order_id}'");
					$data[] = $result;
				}
			} elseif($dataType == "reports") {
				$branches = $pos->prepare("
					SELECT 
						a.branch_name, a.id, a.square_feet_area,
						(
							SELECT 
								COUNT(*)
							FROM sales b
							WHERE b.branchId = a.id AND b.deleted='0'
								AND (DATE(b.order_date) = CURDATE()) AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS orders_count,
						(
							SELECT 
								ROUND(SUM(b.order_amount_paid) ,2) 
							FROM sales b
							WHERE b.branchId = a.id 
								AND (DATE(b.order_date) = CURDATE()) AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS total_sales,
						(
							SELECT 
								ROUND(MAX(b.order_amount_paid) ,2)
							FROM sales b
							WHERE b.branchId = a.id 
								AND (DATE(b.order_date) = CURDATE()) AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS highest_sales,
						(
							SELECT 
								ROUND(MIN(b.order_amount_paid) ,2) 
							FROM sales b
							WHERE b.branchId = a.id
								AND (DATE(b.order_date) = CURDATE()) AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS lowest_sales,
						(
							SELECT 
								ROUND(AVG(b.order_amount_paid) ,2)
							FROM sales b
							WHERE b.branchId = a.id 
								AND (DATE(b.order_date) = CURDATE()) AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS average_sales
					FROM branches a
					WHERE 1 {$brAccess} {$clientAccess}
						ORDER BY total_sales ASC
				");
				$branches->execute();

				// begin the query
				$salesAttendants = $posClass->getAllRows(
					"users a", 
					"a.user_id,
					CONCAT(a.name) AS fullname,
					(
						SELECT 
							CASE WHEN SUM(b.order_amount_paid) IS NULL THEN 0.00 ELSE SUM(b.order_amount_paid) END 
						FROM sales b 
						WHERE 
							b.recorded_by=a.user_id AND b.order_status='confirmed' AND
							(DATE(b.order_date) = CURDATE()) 
							{$branchAccessInner} {$clientAccessInner}
					) AS amnt,
					(
						SELECT COUNT(*) FROM sales b 
						WHERE 
							b.recorded_by=a.user_id AND b.order_status='confirmed' AND
							(DATE(b.order_date) = CURDATE()) 
							{$branchAccessInner} {$clientAccessInner}
					) AS orders",
					"a.status='1' {$branchAccess} {$clientAccess} ORDER BY amnt DESC LIMIT 10"
				);
				
				$personnelNames = array();
				$personnelSales = array();
				$attendantSales = array();

				//: loop throught the dataset
				foreach($salesAttendants as $eachPersonnel) {
					//: append the data
					$personnelNames[] = $eachPersonnel->fullname;
					$personnelSales[] = $eachPersonnel->amnt;

					$uName = "<div class='text-left'><a data-name=\"{$eachPersonnel->fullname}\" data-record=\"attendant\" data-value=\"{$eachPersonnel->user_id}\" class=\"view-user-sales font-weight-bold\" href='javascript:void(0)'>{$eachPersonnel->fullname}</a></div>";

					$attendantSales[] = [
						'fullname' => $uName,
						'orders' => $eachPersonnel->orders,
						'amnt' => $clientData->default_currency.number_format($eachPersonnel->amnt, 2)
					];
				}

				$resultData = [
					"reports_id" => "sales_attendant_performance",
					'list' => $attendantSales,
					'names' => $personnelNames,
					'sales' => $personnelSales
				];

				$branch = ["reports_id" => "branch_performance"];
				//: branch summaries
				while($result = $branches->fetch(PDO::FETCH_OBJ)) {
					$result->square_feet_sales = ($result->total_sales > 0 && $result->square_feet_area > 0) ? $clientData->default_currency.number_format(($result->total_sales / $result->square_feet_area), 2) : $clientData->default_currency."0.00";
					$result->lowest_sales = $clientData->default_currency.number_format($result->lowest_sales, 2);
					$result->highest_sales = $clientData->default_currency.number_format($result->highest_sales, 2);
					$result->average_sales = $clientData->default_currency.number_format($result->average_sales, 2);
					$result->total_sales = $clientData->default_currency.number_format($result->total_sales, 2);
					$branch[] = $result;
				}

				//: products performance
				$products_stmt = $pos->prepare("
					SELECT
						a.id, a.category_id, a.product_title, b.branch_name,
						(
							SELECT 
								COUNT(c.order_id) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) = CURDATE()) 
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS orders_count,
						(
							SELECT 
								SUM(b.product_quantity) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) = CURDATE())
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalQuantitySold,
						(
							SELECT 
								SUM(b.product_quantity*b.product_cost_price) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) = CURDATE())
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalProductsSoldCost,
						(
							SELECT 
								SUM(b.product_quantity*b.product_unit_price) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) = CURDATE())
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalProductsRevenue,
						(
							SELECT 
								SUM((b.product_quantity*b.product_unit_price) - (b.product_quantity*b.product_cost_price)) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) = CURDATE()) 
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalProductsProfit
					FROM 
						products a
					LEFT JOIN branches b ON b.id = a.branchId
					WHERE a.status = '1' {$branchAccess} {$clientAccess} ORDER BY totalProductsProfit DESC LIMIT 100

				");
				$products_stmt->execute();
				
				// initializing
				$iv = 0;
				$productsData = ["reports_id" => "products_performance"];
				//: branch summaries
				while($product_result = $products_stmt->fetch(PDO::FETCH_OBJ)) {
					$iv++;
					$productsData[] =  [
						'row_id' => $iv,
						'product_id' => $product_result->id,
						'category_id' => $product_result->category_id,
						'product_title' => "<strong class='text-dark'><a href='".$config->base_url('products/product-details/'.$product_result->id)."'>{$product_result->product_title}</a></strong><br><span class='text-gray'>({$product_result->branch_name})</span>",
						'orders_count' => $product_result->orders_count,
						'quantity_sold' => $product_result->totalQuantitySold,
						'total_selling_cost' => number_format($product_result->totalProductsSoldCost, 2),
						'total_selling_revenue' => number_format($product_result->totalProductsRevenue, 2),
						'product_profit' => number_format($product_result->totalProductsProfit, 2)
					];
				}		

				// parse the data to use back
				$data = [
					"branch_performance" => [
						"reports_id" => $branch
					],
					"sales_attendant_performance" => [
						"reports_id" => $resultData
					],
					"products_performance" => [
						"reports_id" => $productsData
					]
				];
			}

			if(!empty($data)) {
				$status = 200;
			}

		}

	}
}

echo json_encode([
	"request"	=> $request,
	"result"	=> $data,
	"status"	=> $status,
	"sync_data" => $syncTable
]);
?>