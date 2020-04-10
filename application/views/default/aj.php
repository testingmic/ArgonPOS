<?php
// call global variables
global $session, $pos, $admin_user, $accessObject, $posClass;
// set the page header type
header("Content-Type: application/json");

// initializing
$response = (object) ["status" => "error", "message" => "Error Processing Request"];

// confirm that the user is logged in
if($admin_user->logged_InControlled()) {

	// create a new object
	$dateClass = load_class('Dates', 'models');
	
	// client data
	$clientData = $posClass->getAllRows("settings", "*", "clientId='{$posClass->clientId}'");
	$branchData = $posClass->getAllRows("branches", "*", "id='{$session->branchId}'");
	
	// set the data set
	$clientData = $clientData[0];
	$branchData = $branchData[0];

	// create new objects
	$accessObject->userId = $session->userId;
	
	// where clause for the user role
	$branchAccess = '';
	$branchAccessInner = '';
	$accessLimit = '';
	$accessLimitInner = '';
	$customerLimit = '';
	$customerLimitInner = '';
	$accessLimitInner2 = '';
	$branchAccess2 = '';
	$clientAccess = " AND a.clientId = '{$posClass->clientId}'";
	$clientAccessInner = " AND b.clientId = '{$posClass->clientId}'";

	// use the access level for limit contents that is displayed
	if(!$accessObject->hasAccess('monitoring', 'branches')) {
		$branchAccess2 = " AND a.branch_id = '{$session->branchId}'";
		$branchAccess = " AND a.branchId = '{$session->branchId}'";
		$branchAccessInner = " AND b.branchId = '{$session->branchId}'";
		$accessLimit = " AND a.recorded_by = '{$session->userId}'";
		$accessLimitInner = " AND b.recorded_by = '{$session->userId}'";
		$accessLimitInner2 = " AND c.recorded_by = '{$session->userId}'";
	}

	// dashboard inights
	if(confirm_url_id(1, 'dashboardAnalytics')) {
		
		if (isset($_POST['getSales']) && confirm_url_id(2, "getSales")) {

			$period = (isset($_POST['salesPeriod'])) ? xss_clean($_POST['salesPeriod']) : "today";

			$session->set_userdata("dashboardPeriod", $period);

			// Check Sales Period
			switch ($period) {
				case 'thisWk':
					$dateFrom = $dateClass->get_week("this_wkstart", date('Y-m-d'));
					$dateTo = $dateClass->get_week("this_wkend", date('Y-m-d'));
					$datePrevFrom = $dateClass->get_week("last_wkstart", date('Y-m-d'));
					$datePrevTo = $dateClass->get_week("last_wkend", date('Y-m-d'));
					$display = "Last Week";
					break;
				case 'thisMonth':
					$dateFrom = $dateClass->get_month("this_mntstart", date('m'), date('Y'));
					$dateTo = $dateClass->get_month("this_mntend", date('m'), date('Y'));
					$datePrevFrom = $dateClass->get_month("last_mntstart", date('m'), date('Y'));
					$datePrevTo = $dateClass->get_month("last_mntend", date('m'), date('Y'));
					$display = "Last Month";
					break;
				case 'thisYr':
					$dateFrom = date('Y-01-01');
					$dateTo = date('Y-12-31');
					$datePrevFrom = date('Y-01-01', strtotime("first day of last year"));
					$datePrevTo = date('Y-12-31', strtotime("last day of last year"));
					$display = "Last Year";
					break;
				default:
					$dateFrom = date('Y-m-d');
					$dateTo = date('Y-m-d');
					$datePrevFrom = date('Y-m-d', strtotime("-1 day"));
					$datePrevTo = date('Y-m-d', strtotime("-1 day"));
					$display = "Yesterday";
					break;
			}

			$totalDiscount = 0;
			$totalSales = 0;
			$totalServed= 0;
			$totalProducts = 0;
			$totalProductsWorth = 0;
			$totalCreditSales = 0;
			$averageSalesValue = 0;
			$totalCostPrice = 0;
			$totalSellingPrice = 0;
			$totalProfit = 0;
			$creditTotalProfitMade = 0;
			$creditTotalDiscountGiven = 0;

			$sales = $posClass->getAllRows(
				"sales a LEFT JOIN customers b ON a.customer_id = b.customer_id", 
				"a.*, b.title, b.firstname, b.lastname, b.phone_1,
					(
						SELECT MAX(b.order_amount_paid) FROM sales b WHERE b.deleted='0' AND 
						(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$accessLimitInner} {$customerLimitInner} {$clientAccessInner}
					) AS highestSalesValue,
					(
						SELECT SUM(b.order_amount_paid) FROM sales b WHERE b.deleted='0' AND
						credit_sales = '1' AND 
						(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$accessLimitInner} {$customerLimitInner} {$clientAccessInner}
					) AS totalCreditSales,
					(
						SELECT AVG(b.order_amount_paid) FROM sales b WHERE b.deleted='0' AND
						(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$accessLimitInner} {$customerLimitInner} {$clientAccessInner}
					) AS averageSalesValue,
					(
						SELECT SUM(b.product_cost_price * b.product_quantity) FROM sales_details b WHERE
						(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') 
						AND b.order_id = a.order_id
						{$branchAccessInner} {$accessLimit} {$clientAccessInner}
					) AS totalCostPrice,
					(
						SELECT SUM(b.product_unit_price * b.product_quantity) AS selling_price FROM sales_details b WHERE
						b.order_id = a.order_id AND
						(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$accessLimit} {$clientAccessInner}
					) AS totalSellingPrice,
					(
						SELECT SUM((b.product_unit_price * b.product_quantity)-(b.product_cost_price * b.product_quantity)) AS order_profit FROM sales_details b WHERE
						b.order_id = a.order_id AND 
						(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') 
						{$branchAccessInner} {$accessLimit} {$clientAccessInner}
					) AS totalProfitMade,
					(
						SELECT SUM((b.product_unit_price * b.product_quantity)-(b.product_cost_price * b.product_quantity))-(a.order_discount) AS order_profit FROM sales_details b WHERE 
						b.order_id = a.order_id AND a.payment_type='credit' AND
						(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}')
						{$branchAccessInner} {$accessLimit} {$clientAccessInner}
				    ) AS creditTotalProfitMade
				", 
				"a.order_status = 'confirmed' && a.deleted = '0' && (DATE(a.order_date) >= '{$dateFrom}' && DATE(a.order_date) <= '{$dateTo}') {$accessLimit} {$branchAccess} {$clientAccess} ORDER BY a.order_date DESC"
			);

			$message = [];

			if ($sales != false && count($sales) > 0) {
				$i = 0;
				$results = [];
				
				foreach ($sales as $data) {
					$i++;

					$orderDate = date('jS F Y h:iA', strtotime($data->order_date));
					$totalOrder= $hrClass->toDecimal($data->order_amount_paid, 2, ',');

					$results[] = [
						'row' => "$i.",
						'order_id' => "<a onclick=\"return getSalesDetails('{$data->order_id}')\" data-toggle=\"tooltip\" title=\"View Trasaction Details\" href=\"javascript:void(0)\" type=\"button\" class=\"get-sales-details text-success\" data-sales-id=\"{$data->order_id}\">#$data->order_id</a> <br> ".ucfirst($data->payment_type),
						'fullname' => "<a onclick=\"return getSalesDetails('{$data->order_id}')\" href=\"javascript:void(0)\" type=\"button\" class=\"get-sales-details text-success\" data-sales-id=\"{$data->order_id}\">{$data->title} {$data->firstname} {$data->lastname}</a>",
						'phone' => $data->phone_1,
						'date' => $orderDate,
						'amount' => "{$clientData->default_currency} {$totalOrder}",
						'action' => " <a title=\"Print Transaction Details\" href=\"javascript:void(0);\" class=\"btn btn-sm btn-outline-primary print-receipt\" data-sales-id=\"{$data->order_id}\"><i class=\"fa fa-print\"></i></a> &nbsp; <a data-toggle=\"tooltip\" title=\"Download Trasaction Details\" href=\"{$config->base_url('export/'.$data->order_id)}\" target=\"_blank\" class=\"btn-outline-success btn btn-sm get-sales-details\" data-sales-id=\"{$data->order_id}\"><i class=\"fa fa-download\"></i></a>",
					];

					$totalDiscount += $data->order_discount;
					$totalCostPrice += $data->totalCostPrice;
					$totalSellingPrice += $data->totalSellingPrice;
					$totalProfit += $data->totalProfitMade;
					$totalSales += $data->order_amount_paid;
					$totalServed += 1;
					$creditTotalProfitMade += $data->creditTotalProfitMade;
					
					$totalProductsWorth = 0;
					$totalCreditSales = $data->totalCreditSales;
				}
				$message = $results;
			}
			$averageSalesValue = ($totalSales > 0 && $totalServed > 0) ? ($totalSales / $totalServed) : 0.00;

			$prevSales = $posClass->getAllRows(
				"sales a", 
				"
					COUNT(*) AS totalPrevServed, SUM(a.order_amount_paid) AS totalPrevSales,
					SUM(a.order_discount) AS totalDiscountGiven,
					(
						SELECT MAX(b.order_amount_paid) FROM sales b WHERE b.deleted = '0' &&
						(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$customerLimitInner}
						{$accessLimitInner} {$clientAccessInner}
					) AS highestSalesValue,
					(
						SELECT SUM(b.order_amount_paid) FROM sales b WHERE
						 b.deleted = '0' &&
						credit_sales = '1' AND
						(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$accessLimitInner} {$customerLimitInner} {$clientAccessInner}
					) AS totalPrevCreditSales,
					(
						SELECT AVG(b.order_amount_paid) FROM sales b WHERE 
						 b.deleted = '0' &&
						(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$customerLimitInner}
						{$accessLimitInner} {$clientAccessInner}
					) AS averageSalesValue,
					(
						SELECT SUM(b.product_cost_price * b.product_quantity) FROM sales_details b WHERE
						(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$accessLimit} {$clientAccessInner}
					) AS totalCostPrice,
					(
						SELECT SUM(b.product_unit_price * b.product_quantity) AS selling_price FROM sales_details b WHERE
						(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$accessLimit} {$clientAccessInner}
					) AS totalSellingPrice,
					(
						SELECT SUM((b.product_unit_price * b.product_quantity)-(b.product_cost_price * b.product_quantity)) AS order_profit FROM sales_details b WHERE
						(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$accessLimit} {$clientAccessInner}
					) AS totalProfitMade
				", 
				"a.order_status = 'confirmed' && a.deleted = '0' {$accessLimit} && DATE(a.order_date) >= '{$datePrevFrom}' && DATE(a.order_date) <= '{$datePrevTo}' {$branchAccess} {$clientAccess}"
			);

			if ($prevSales != false) {
				$prevSales = $prevSales[0];

				$totalSellingTrend = $hrClass->percentDifference(floatval($totalSellingPrice), floatval($prevSales->totalSellingPrice));
				$totalCostTrend = $hrClass->percentDifference(floatval($prevSales->totalCostPrice), floatval($totalCostPrice));
				$totalProfitTrend = $hrClass->percentDifference(floatval($totalProfit-$totalDiscount), floatval($prevSales->totalProfitMade-$prevSales->totalDiscountGiven));
				$totalServedTrend = $hrClass->percentDifference(floatval($totalServed), floatval($prevSales->totalPrevServed));
				$totalSalesTrend = $hrClass->percentDifference(floatval($totalSales), floatval($prevSales->totalPrevSales));
				$totalCreditTrend = $hrClass->percentDifference(floatval($totalCreditSales), floatval($prevSales->totalPrevCreditSales));
				$totalPercent = (!empty($totalCreditSales) && !empty($totalSales)) ? round(($totalCreditSales/$totalSales)*100, 2) : 0.00;
				$averageSalesTrend = $hrClass->percentDifference(floatval($averageSalesValue), floatval($prevSales->averageSalesValue));
				$totalDiscountTrend = $hrClass->percentDifference(floatval($totalDiscount), floatval($prevSales->totalDiscountGiven));

				$status = true;
			}

			//: additional calculations
			$creditProfitPercentage = ($creditTotalProfitMade > 0) ? (($creditTotalProfitMade / $totalProfit) * 100) : 0;
			$creditProfitPercentage = number_format($creditProfitPercentage, 2);
			//: print the result
			echo json_encode([
				"message" => [
					"table" => $message, 
					"totalSales" => [
						"total" => $clientData->default_currency . $hrClass->toDecimal($totalSales, 2, ','), 
						"trend" => $totalSalesTrend ." ". $display
					],
					"totalServed" => [
						"total" => $hrClass->toDecimal($totalServed, 0, ','),
						"trend" => $totalServedTrend ." ". $display
					],
					"totalDiscount" => [
						"total" => $clientData->default_currency . $hrClass->toDecimal($totalDiscount, 0, ','),
						"trend" => $totalDiscountTrend ." ". $display
					],
					"averageSales" => [
						"total"	=> $clientData->default_currency . number_format($averageSalesValue, 2),
						"trend" => $averageSalesTrend ." ". $display
					],
					"totalCredit" => [
						"total" => $clientData->default_currency . $hrClass->toDecimal($totalCreditSales, 2, ','),
						"trend" =>  "<span class='text-gray'>{$totalPercent}% of Total Sales ({$creditProfitPercentage}% of profit)</span>"
					],
					"salesComparison" => [
						"profit" => $clientData->default_currency . $hrClass->toDecimal(($totalProfit-$totalDiscount), 2, ','),
						"profit_trend" =>  $totalProfitTrend ." ". $display,
						"selling" => $clientData->default_currency . $hrClass->toDecimal($totalSellingPrice, 2, ','),
						"selling_trend" =>  $totalSellingTrend ." ". $display,
						"cost" => $clientData->default_currency . $hrClass->toDecimal($totalCostPrice, 2, ','),
						"cost_trend" =>  $totalCostTrend ." ". $display
					]
				],
				"status"  => $status
			]);
			exit;

		} else if (isset($_POST['request']) && $_POST['request'] == "fetchInventoryRecords") {

			$query = $posClass->getAllRows(
				"inventory a INNER JOIN products b ON a.product_id = b.product_id LEFT JOIN users c ON a.recorded_by = c.user_id", 
				"a.*, b.product_title, c.name AS recordedBy", 
				"1 {$branchAccess} {$clientAccess} ORDER BY a.log_date DESC"
			);

			if ($query != false) {
				$i = 0;
				
				foreach ($query as $data) {
					$i++;

					$orderDate = date('jS F Y', strtotime($data->log_date));

					$results[] = [
						'row' => "$i.",
						'product' => "$data->product_title",
						'quantity' => $data->quantity,
						'price' => "{$clientData->default_currency} $data->selling_price",
						'recordedBy' => $data->recordedBy,
						'orderDate' => $orderDate
					];
				}

				$message = $results;

				$status = true;
			}

		} else if (isset($_POST['getSalesDetails']) && confirm_url_id(2, "getSalesDetails")) {

			if (!empty($_POST['salesID'])) {

				$postData = (OBJECT) array_map("xss_clean", $_POST);

				$query = $posClass->getAllRows(
					"sales_details b 
						LEFT JOIN products c ON b.product_id = c.id 
						INNER JOIN sales a ON a.order_id = b.order_id
						LEFT JOIN customers d ON a.customer_id = d.customer_id
					", 
					"c.product_title, b.*, a.order_discount,
					CONCAT(d.firstname ,' ', d.lastname) AS fullname,
					a.order_date, a.order_id, d.phone_1 AS contact,
					a.payment_type
					", 
					"a.clientId = '{$posClass->clientId}' && b.order_id = '{$postData->salesID}' ORDER BY b.id"
				);

				if ($query != false) {

					$subTotal = 0;

					$message = "
					<div class=\"row table-responsive\">
						<table class=\"table table-bordered\">
							<tr>
								<td><strong>Customer Name</strong>: {$query[0]->fullname}</td>
								<td align='left'><strong>Transaction ID:</strong>: {$postData->salesID}</td>
							</tr>
							<tr>
								<td><strong>Contact</strong>: {$query[0]->contact}</td>
								<td align='left'><strong>Transaction Date</strong>: {$query[0]->order_date}</td>
							</tr>
						</table>
		                <table class=\"table table-bordered\">
							<thead>
								<tr>
									<td class=\"text-left\">Product</td>
									<td class=\"text-left\">Quantity</td>
									<td class=\"text-right\">Unit Price</td>
									<td class=\"text-right\">Total</td>
								</tr>
							</thead>
							<tbody>";

					foreach ($query as $data) {
						$productTotal = $hrClass->toDecimal($data->product_total, 2, ',');
						$message .= "
							<tr>
								<td>{$data->product_title}</td>
								<td>{$data->product_quantity}</td>
								<td class=\"text-right\">{$clientData->default_currency} {$data->product_unit_price}</td>
								<td class=\"text-right\">{$clientData->default_currency} {$productTotal}</td>
							</tr>";

						$subTotal += $data->product_total;
						$discount = $hrClass->toDecimal($data->order_discount, 2, ',');
					}
					$overall = $hrClass->toDecimal($subTotal - $discount, 2, ',');
					$message .= "
						<tr>
							<td style=\"font-weight:bolder;text-transform:uppercase\" colspan=\"3\" class=\"text-right\">Subtotal</td>
							<td style=\"font-weight:bolder;text-transform:uppercase\" class=\"text-right\">
								{$clientData->default_currency} ".$hrClass->toDecimal($subTotal, 2, ',')."
							</td>
						</tr>
						<tr>
							<td style=\"font-weight:;text-transform:uppercase\" colspan=\"3\" class=\"text-right\">Discount</td>
							<td style=\"font-weight:;text-transform:uppercase\" class=\"text-right\">{$clientData->default_currency} {$discount}</td>
						</tr>
						<tr>
							<td style=\"font-weight:bolder;text-transform:uppercase\" colspan=\"3\" class=\"text-right\">Overall Total</td>
							<td style=\"font-weight:bolder;text-transform:uppercase\" class=\"text-right\">{$clientData->default_currency} {$overall}</td>
						</tr>
					";

					$message .= "</tbody>
						</table>
					</div>
					<a href=\"{$config->base_url('invoice/'.$postData->salesID)}\" class=\"btn btn-danger btn-sm px-3 float-right\">
			        	<i class=\"fa fa-file-pdf\"></i> 
			        	Print Receipt
			       	</a>";
					$status = true;
				}

			}
		}  else if (isset($_POST['request']) && $_POST['request'] == "getProductThresholds") {

			$postData = (OBJECT) array_map("xss_clean", $_POST);

			$query = $posClass->getAllRows(
				"products a", 
				"a.product_title, a.quantity", 
				" a.status = '1' && 
					a.threshold >= quantity 
					{$branchAccess} {$clientAccess}
					ORDER BY a.id
				"
			);

			if ($query != false) {

				$i = 0;
				foreach ($query as $data) {
					$i++;

					$results[] = [
						'row' => "$i.",
						'product' => "$data->product_title",
						'quantity' => "<span class=\"badge badge-danger\">{$data->quantity} left</span>"
					];

				}
				$message = $results;
				$status = true;

			}
		}

		echo json_encode([
			"message" => $message,
			"status" => $status
		]);
		exit;
	}

	// reporting
	elseif(confirm_url_id(1, 'reportsAnalytics')) {
	 
		$status = false;
		$resultData = [];
		$metric = "unknown";
		// set the query range in an array
		$queryRange = [
			'start' => date("Y-m-d"), 'end' => date("Y-m-d")
		];

		// process the summary results set
		if (isset($_REQUEST['generateReport'], $_REQUEST['queryMetric']) && confirm_url_id(2, 'generateReport')) {

			$postData = (Object) array_map('xss_clean', $_REQUEST);
			$period = (isset($postData->salesPeriod)) ? strtolower($postData->salesPeriod) : "today";
			$metric = (isset($postData->queryMetric)) ? $postData->queryMetric : null;

			// set the range in a session
			if(isset($postData->salesPeriod)) {
				$session->set_userdata("reportPeriod", $period);
			}

			// Check Sales Period
			switch ($period) {
				case 'this-week':
					$groupBy = "DATE";
					$format = "jS M Y";
					$groupByClause = "GROUP BY DATE(a.order_date)";
					$groupByInnerClause = "GROUP BY DATE(b.order_date)";
					$dateFrom = $dateClass->get_week("this_wkstart", date('Y-m-d'));
					$dateTo = $dateClass->get_week("this_wkend", date('Y-m-d'));
					$datePrevFrom = $dateClass->get_week("last_wkstart", date('Y-m-d'));
					$datePrevTo = $dateClass->get_week("last_wkend", date('Y-m-d'));
					$current = "This Week";
					$display = "Compared to Last Week";
					break;
				case 'this-month':
					$groupBy = "DATE";
					$format = "jS M Y";
					$groupByClause = "GROUP BY DATE(a.order_date)";
					$groupByInnerClause = "GROUP BY DATE(b.order_date)";
					$dateFrom = $dateClass->get_month("this_mntstart", date('m'), date('Y'));
					$dateTo = $dateClass->get_month("this_mntend", date('m'), date('Y'));
					$datePrevFrom = $dateClass->get_month("last_mntstart", date('m'), date('Y'));
					$datePrevTo = $dateClass->get_month("last_mntend", date('m'), date('Y'));
					$current = "This Month";
					$display = "Compared to Last Month";
					break;
				case 'this-year':
					$groupBy = "MONTH";
					$format = "F";
					$groupByClause = "GROUP BY MONTH(a.order_date)";
					$groupByInnerClause = "GROUP BY MONTH(b.order_date)";
					$dateFrom = date('Y-01-01');
					$dateTo = date('Y-12-31');
					$datePrevFrom = date('Y-01-01', strtotime("first day of last year"));
					$datePrevTo = date('Y-12-31', strtotime("last day of last year"));
					$current = "This Year";
					$display = "Compared to Last Year";
					break;
				default:
					$groupBy = "HOUR";
					$format = "HA";
					$groupByClause = "GROUP BY HOUR(order_date)";
					$groupByInnerClause = "GROUP BY HOUR(b.order_date)";
					$dateFrom = date('Y-m-d');
					$dateTo = date('Y-m-d');
					$datePrevFrom = date('Y-m-d', strtotime("-1 day"));
					$datePrevTo = date('Y-m-d', strtotime("-1 day"));
					$current = "Today";
					$display = "Compared to Yesterday";
					break;
			}

			$totalSales = 0;
			$totalServed= 0;
			$grossProfit = 0;
			$netProfit = 0;
			$totalRevenue = 0;
			$orderDiscount = 0;
			$averageInventory = 0;
			$netProfitMargin = 0;
			$costOfGoodsSold = 0;
			$salesPerEmployee = 0;
			$highestSalesValue = 0;
			$averageSalesValue = 0;
			$grossProfitMargin = 0;
			$totalQuantitiesSold = 0;
			$averageUnitPerTransaction = 0;

			// save the selected time frame into the database
			if(isset($postData->salesPeriod)) {
				// update the settings
				$posClass->updateData(
					"settings",
					"reports_period='{$period}'",
					"clientId='{$posClass->clientId}'"
				);
			}

			// if the query is for the summary records
			if($metric == 'summaryItems') {

				$sales = $posClass->getAllRows(
					"sales a LEFT JOIN customers b ON a.customer_id = b.customer_id", 
					"a.order_discount, a.order_amount_paid, a.overall_order_amount, a.order_date, b.title, b.firstname, b.lastname, b.phone_1,
						(
							SELECT MAX(b.order_amount_paid) FROM sales b WHERE b.deleted='0' AND
							(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$accessLimitInner} {$customerLimitInner} {$clientAccessInner}
						) AS highestSalesValue,
						(
							SELECT AVG(b.order_amount_paid) FROM sales b WHERE b.deleted='0' AND
							(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$accessLimitInner} {$customerLimitInner} {$clientAccessInner}
						) AS averageSalesValue,
						(
							SELECT SUM((b.product_unit_price * b.product_quantity)-(b.product_cost_price * b.product_quantity)) FROM sales_details b WHERE b.order_id = a.order_id
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS totalGrossProfit,
						(
							SELECT SUM(b.product_cost_price * b.product_quantity) FROM sales_details b WHERE b.order_id = a.order_id
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS costOfGoodsSold,
						(
							SELECT 
								SUM(b.product_quantity)
							FROM sales_details b
							WHERE b.order_id = a.order_id AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS totalQuantitiesSold,
						(
							SELECT 
								SUM(b.quantity)
							FROM products b WHERE status='1'
						) AS productQuantity,
						(
							SELECT SUM(b.order_amount_paid)/(SELECT COUNT(*) FROM users b WHERE b.status='1' && b.access_level NOT IN (1) {$clientAccessInner} {$branchAccessInner})
							FROM sales b WHERE b.deleted='0' AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') {$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS salesPerEmployee
					", 
					"a.order_status = 'confirmed' && a.deleted = '0' {$accessLimit} && (DATE(a.order_date) >= '{$dateFrom}' && DATE(a.order_date) <= '{$dateTo}') {$branchAccess} {$clientAccess} {$customerLimit} ORDER BY a.log_date DESC"
				);

				$productQuantity = 0;

				if ($sales != false) {
					$i = 0;
					
					foreach ($sales as $data) {
						$i++;

						$orderDate = date('jS F Y', strtotime($data->order_date));

						$totalSales += $data->order_amount_paid;
						$totalServed += 1;
						$productQuantity = $data->productQuantity;
						$orderDiscount += $data->order_discount;
						$salesPerEmployee = $data->salesPerEmployee;
						$highestSalesValue = $data->highestSalesValue;
						$averageSalesValue = $data->averageSalesValue;
						$totalQuantitiesSold += $data->totalQuantitiesSold;
						$totalRevenue += $data->overall_order_amount;
						$costOfGoodsSold += $data->costOfGoodsSold;
					}

					$status = true;
				}

				// expenses calculation
				if(!$accessObject->hasAccess('monitoring', 'branches')) {
					$squareFeetArea = $branchData->square_feet_area;
					$allExpenses = ($branchData->recurring_expenses + $branchData->fixed_expenses);
					$fixedCost = $branchData->fixed_expenses;
				} else {
					// square feet area calculation
					$squareFeetArea = $clientData->space_per_square_foot;
					$allExpenses = $clientData->total_expenditure;
					$fixedCost = $branchData->fixed_expenses;
				}

				$salesPerSquareFeet = ($totalSales > 0 && $squareFeetArea > 0) ? $totalSales / $squareFeetArea : 0;

				if($period == "today") {
					$fixedCost = ($fixedCost / date("t"));
					$allExpenses = ($allExpenses / date("t"));
				} elseif($period == "this-week") {
					$fixedCost = ($fixedCost / 4);
					$allExpenses = ($allExpenses / 4);
				} elseif($period == "this-year") {
					$fixedCost = ($fixedCost * 12);
					$allExpenses = ($allExpenses * 12);
				}

				// gross and net revenue calculation
				$grossProfit = $totalRevenue - $costOfGoodsSold;
				if($totalRevenue > 0) {
					$grossProfitMargin = ((($totalRevenue - $costOfGoodsSold) / $totalRevenue) * 100);
				}

				$netProfit = $totalRevenue - ($allExpenses+$costOfGoodsSold);
				if($totalRevenue > 0) {
					$netProfitMargin = (($totalRevenue - ($allExpenses+$costOfGoodsSold)) / $totalRevenue) * 100;
				}

				// inventory calculation
				$endingInventory = $productQuantity;
				$beginingInventory = ($productQuantity + $totalQuantitiesSold);
				$averageInventory = (($beginingInventory+$endingInventory) / 2);

				$stockTurnoverRate = ($costOfGoodsSold > 0 && $averageInventory > 0) ? ($costOfGoodsSold / $averageInventory) : 0;

				// sell through percentage
				$sellThroughPercentage = ($totalQuantitiesSold > 0 && $beginingInventory > 0) ? (($totalQuantitiesSold / $beginingInventory) * 100) : 0;

				// gross margin return on investment
				$grossMarginInvestmentReturn = ($grossProfit > 0 && $averageInventory > 0) ? ($grossProfit / $averageInventory) : 0;

				// unit per transaction
				$averageUnitPerTransaction = ($totalQuantitiesSold > 0) ? ($totalQuantitiesSold / $totalServed) : 0;

				//: count the number of products available
				// $inventoryCount = $posClass->countRows("products a", "a.status='1' {$branchAccess} {$clientAccess}");
				$inventoryCount = $posClass->countRows("products_categories a","1 {$clientAccess}");

				// customer retention rate
				$totalCustomersCount = $posClass->countRows("customers a","a.status='1' {$branchAccess} {$clientAccess}");
				$customersDuringPeriod = $posClass->countRows("customers a","a.status='1' AND (DATE(a.date_log) >= '{$dateFrom}' AND DATE(a.date_log) <= '{$dateTo}') {$branchAccess} {$clientAccess}");
				$customersBeforePeriod = $posClass->countRows("customers a","a.status='1' AND (DATE(a.date_log) < '{$dateFrom}') {$branchAccess} {$clientAccess}");

				// crr calculation
				$customersRetentionRate = ($customersBeforePeriod > 0) ? ((($totalCustomersCount-$customersDuringPeriod)/$customersBeforePeriod) * 100) : 0;

				// previous records
				$prevSales = $posClass->getAllRows(
					"sales a", 
					"
						COUNT(*) AS totalPrevServed, CASE WHEN SUM(a.order_amount_paid) IS NULL THEN 1 ELSE SUM(a.order_amount_paid) END AS totalPrevSales, SUM(a.order_discount) AS total_order_discount, SUM(a.overall_order_amount) AS totalRevenue,
						(
							SELECT MAX(b.order_amount_paid) FROM sales b WHERE b.deleted='0' AND
							(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$customerLimitInner}
							{$accessLimitInner} {$clientAccessInner}
						) AS highestSalesValue,
						(
							SELECT AVG(b.order_amount_paid) FROM sales b WHERE b.deleted='0' AND
							(DATE(b.order_date) >= '{$datePrevFrom}' && DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$customerLimitInner}
							{$accessLimitInner} {$clientAccessInner}
						) AS averageSalesValue,
						(
							SELECT SUM((b.product_unit_price * b.product_quantity)-(b.product_cost_price * b.product_quantity)) FROM sales_details b WHERE b.order_id = a.order_id
							AND (DATE(b.order_date) >= '{$datePrevFrom}' AND DATE(b.order_date) <= '{$datePrevTo}') 
							{$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS totalGrossProfit,
						(
							SELECT SUM(b.product_cost_price * b.product_quantity) FROM sales_details b WHERE b.order_id = a.order_id
							AND (DATE(b.order_date) >= '{$datePrevFrom}' AND DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS costOfGoodsSold,
						(
							SELECT 
								CASE WHEN SUM(b.product_quantity) IS NULL THEN 0.001 ELSE SUM(b.product_quantity) END
							FROM sales_details b
							WHERE b.order_id = a.order_id AND (DATE(b.order_date) >= '{$datePrevFrom}' AND DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS totalQuantitiesSold,
						(
							SELECT 
								SUM(b.quantity)
							FROM products b WHERE status='1'
						) AS productQuantity,
						(
							SELECT SUM(b.order_amount_paid)/(SELECT COUNT(*) FROM users b WHERE b.status='1' && b.access_level NOT IN (1) {$clientAccessInner} {$branchAccessInner})
							FROM sales b WHERE b.deleted='0' AND (DATE(b.order_date) >= '{$datePrevFrom}' AND DATE(b.order_date) <= '{$datePrevTo}') {$branchAccessInner} {$clientAccessInner} {$accessLimit}
						) AS salesPerEmployee
					", 
					"a.order_status = 'confirmed' && a.deleted = '0' {$accessLimit} && DATE(a.order_date) >= '{$datePrevFrom}' && DATE(a.order_date) <= '{$datePrevTo}' {$branchAccess} {$clientAccess} {$customerLimit} ORDER BY a.log_date DESC"
				);

				if ($prevSales != false) {

					$prevSales = $prevSales[0];

					// inventory calculation
					$endingInventory = $productQuantity;
					$prevBeginingInventory = ($productQuantity + $prevSales->totalQuantitiesSold);
					$prevAverageInventory = (($prevBeginingInventory+$endingInventory) / 2);

					$prevStockTurnoverRate = ($prevSales->costOfGoodsSold > 0 && $prevAverageInventory > 0) ? ($prevSales->costOfGoodsSold / $prevAverageInventory) : 0;

					// trend calculation
					$totalServedTrend = $hrClass->percentDifference(floatval($totalServed), floatval($prevSales->totalPrevServed));
					$totalSalesTrend = $hrClass->percentDifference(floatval($totalSales), floatval($prevSales->totalPrevSales));
					$highestSalesTrend = $hrClass->percentDifference(floatval($highestSalesValue), floatval($prevSales->highestSalesValue));
					$averageSalesTrend = $hrClass->percentDifference(floatval($averageSalesValue), floatval($prevSales->averageSalesValue));
					$stockTurnoverRateTrend = $hrClass->percentDifference(floatval($stockTurnoverRate), floatval($prevStockTurnoverRate));
					$averageUnitPerTransactionTrend = ($prevSales->totalPrevServed > 0) ? $hrClass->percentDifference(floatval($averageUnitPerTransaction), (floatval($prevSales->totalQuantitiesSold)/$prevSales->totalPrevServed)) : '<span class="text-success"><i class="mdi mdi-trending-up"></i> 100%</span>';		
					$orderDiscountTrend = $hrClass->percentDifference(floatval($orderDiscount), floatval($prevSales->total_order_discount));
					$salesPerEmployeeTrend = $hrClass->percentDifference(floatval($salesPerEmployee), floatval($prevSales->salesPerEmployee));
					$grossProfitTrend = $hrClass->percentDifference(floatval($grossProfit), (floatval($prevSales->totalRevenue)-$prevSales->costOfGoodsSold));
					$grossProfitMarginTrend = ($prevSales->totalRevenue > 0) ? $hrClass->percentDifference(floatval($grossProfitMargin), (((floatval($prevSales->totalRevenue)-floatval($prevSales->costOfGoodsSold))/floatval($prevSales->totalRevenue)) * 100)) : '<span class="text-success"><i class="mdi mdi-trending-up"></i> 100%</span>';
					$netProfitTrend = $hrClass->percentDifference(floatval($netProfit), (floatval($prevSales->totalRevenue)-($allExpenses+$prevSales->costOfGoodsSold)));
					$netProfitMarginTrend = ($prevSales->totalRevenue > 0) ? $hrClass->percentDifference(floatval($netProfitMargin), (((floatval($prevSales->totalRevenue)-($allExpenses+$prevSales->costOfGoodsSold))/floatval($prevSales->totalRevenue)) * 100)) : '<span class="text-success"><i class="mdi mdi-trending-up"></i> 100%</span>';
					$salesPerSquareFeetTrend = ($prevSales->totalPrevSales > 0 && $salesPerSquareFeet > 0) ? $hrClass->percentDifference(floatval($salesPerSquareFeet), (floatval($prevSales->totalPrevSales)/($salesPerSquareFeet))) : '<span class="text-success"><i class="mdi mdi-trending-up"></i> 100%</span>';
				}

				$resultData[] = [
					"column" => "sell-through-percentage",
					"total" => number_format($sellThroughPercentage, 2) . "%", 
					"trend" => "Sell Through Percentage"
				];
				$resultData[] = [
					"column" => "gross-margin-return-investment",
					"total" => $clientData->default_currency . number_format($grossMarginInvestmentReturn, 2), 
					"trend" => "Gross margin investment returns"
				];
				$resultData[] = [
					"column" => "stock-turnover-rate",
					"total" => $clientData->default_currency . number_format($stockTurnoverRate, 2), 
					"trend" => "Total Stock Turnover Rate"
				];
				$resultData[] = [
					"column" => "total-sales",
					"total" => $clientData->default_currency . number_format($totalSales, 2), 
					"trend" => $totalSalesTrend ." ". $display
				];
				$resultData[] = [
					"column" => "total-orders",
					"total" => $totalServed,
					"trend" => $totalServedTrend ." ". $display
				];
				$resultData[] = [
					"column" => "average-sales",
					"total" => $clientData->default_currency . number_format($averageSalesValue, 2),
					"trend" => $averageSalesTrend ." ". $display
				];
				$resultData[] = [
					"column" => "highest-sales",
					"total" => $clientData->default_currency . number_format($highestSalesValue, 2),
					"trend" => $highestSalesTrend ." ". $display
				];
				$resultData[] = [
					"column" => "order-discount",
					"total" => $clientData->default_currency . number_format($orderDiscount, 2),
					"trend" => $orderDiscountTrend ." ". $display
				];
				$resultData[] = [
					"column" => "average-unit-per-transaction",
					"total" => number_format($averageUnitPerTransaction, 2),
					"trend" => $averageUnitPerTransactionTrend . " ".$display
				];
				$resultData[] = [
					"column" => "sales-per-employee",
					"total" => $clientData->default_currency . number_format($salesPerEmployee, 2),
					"trend" => $salesPerEmployeeTrend . " ".$display
				];
				$resultData[] = [
					"column" => "gross-profit",
					"total" => $clientData->default_currency . number_format($grossProfit, 2),
					"trend" => $grossProfitTrend . " " .$display
				];
				$resultData[] = [
					"column" => "gross-profit-margin",
					"total" => number_format($grossProfitMargin, 2). "%",
					"trend" => $grossProfitMarginTrend . " " .$display
				];
				$resultData[] = [
					"column" => "break-even-point",
					"total" => ($allExpenses > 0 && $grossProfit > 0) ? number_format(($allExpenses/$grossProfit), 2) : 0,
					"trend" => "Sales Break even Point"
				];
				$resultData[] = [
					"column" => "sales-per-category",
					"total" => ($inventoryCount > 0) ? $clientData->default_currency . number_format(($totalSales/$inventoryCount), 2) : 0,
					"trend" => "Sales Per Category"
				];
				$resultData[] = [
					"column" => "net-profit",
					"total" => $clientData->default_currency . number_format($netProfit, 2),
					"trend" => $netProfitTrend . " " .$display
				];
				$resultData[] = [
					"column" => "net-profit-margin",
					"total" => number_format($netProfitMargin, 2). "%",
					"trend" => $netProfitMarginTrend . " " .$display
				];
				$resultData[] = [
					"column" => "sales-per-square-feet",
					"total" => $clientData->default_currency . number_format($salesPerSquareFeet, 2),
					"trend" => $salesPerSquareFeetTrend . " " .$display
				];
				$resultData[] = [
					"column" => "customers-retention-rate",
					"total" => $customersRetentionRate. "%",
					"trend" => 'Customers Retention Rate'
				];
				$resultData[] = [
					"column" => "year-over-year-growth",
					"total" => number_format((($totalSales-$prevSales->totalPrevSales)/$prevSales->totalPrevSales)*100, 2),
					"trend" => "Year over Year Growth Rate"
				];

			}

			// if the user is querying for the sales overview
			elseif(in_array($metric, ['salesOverview', 'ordersCount'])) {

				// payment options processor
				$paymentOptions = $clientData->payment_options;
				$message = [];
				
				// payment sums
				$paymentKeys = array();
				$paymentValues = array();

				// confirm that the record is not empty
				if(!empty($paymentOptions)) {

					// explode the content with the comma
					$opts = explode(',', $paymentOptions);
					
					// using the foreach loop to fetch the records
					foreach ($opts as $eachOption) {
						$queryData = $posClass->getAllRows(
							"sales a", "CASE WHEN SUM(a.order_amount_paid) IS NULL THEN 0.1 ELSE SUM(a.order_amount_paid) END AS total_amount, a.payment_type",
							"a.payment_type = '".strtolower($eachOption)."' && a.deleted='0' && a.order_status='confirmed' && (DATE(a.order_date) >= '{$dateFrom}' && DATE(a.order_date) <= '{$dateTo}') {$branchAccess} {$accessLimit} {$clientAccess}
							"
						);
						
						$paymentData = $queryData[0];
						$paymentKeys[] = ucfirst($eachOption);
						$paymentValues[] = round($paymentData->total_amount);
						
					}
				}

				$paymentBreakdown = [
					"payment_option" => $paymentKeys,
					"payment_values" => $paymentValues
				];

				// loop through the products category
				$category_stmt = $pos->prepare("
					SELECT
						a.id, a.category AS category_name,
						(
							SELECT 
					            CASE 
					                WHEN SUM(c.product_quantity*c.product_unit_price) IS NULL THEN 0.1 
					            ELSE 
					                SUM(c.product_quantity*c.product_unit_price)
					            END
					        FROM 
					        	sales_details c
					        LEFT JOIN products d ON d.id = c.product_id
					        LEFT JOIN sales b ON b.order_id = c.order_id
					       	WHERE 
					        	(DATE(c.order_date) >= '{$dateFrom}' && DATE(c.order_date) <= '{$dateTo}') 
					        	AND d.category_id=a.category_id AND b.deleted='0' 
					        	{$branchAccessInner} {$clientAccessInner} {$accessLimitInner}
						) AS amount
					FROM
						products_categories a
					WHERE 1 {$clientAccess}
				");
				$category_stmt->execute();

				// initializing
				$category_names = [];
				$category_amount = [];
				
				// assign the category sales record
				while($categorySales = $category_stmt->fetch(PDO::FETCH_OBJ)) {
					// assign the data set
					$category_names[] = trim($categorySales->category_name);
					$category_amount[] = round($categorySales->amount);
				}


				// products performance query
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
								(DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS orders_count,
						(
							SELECT 
								SUM(b.product_quantity) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalQuantitySold,
						(
							SELECT 
								SUM(b.product_quantity*b.product_cost_price) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalProductsSoldCost,
						(
							SELECT 
								SUM(b.product_quantity*b.product_unit_price) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalProductsRevenue,
						(
							SELECT 
								SUM((b.product_quantity*b.product_unit_price) - (b.product_quantity*b.product_cost_price)) 
							FROM sales_details b
							LEFT JOIN sales c ON b.order_id = c.order_id
							WHERE 
								c.deleted='0' AND b.product_id = a.id AND
								(DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
								{$branchAccessInner} {$clientAccessInner} {$accessLimitInner2}
						) AS totalProductsProfit
					FROM 
						products a
					LEFT JOIN branches b ON b.id = a.branchId
					WHERE a.status = '1' {$branchAccess} {$clientAccess} ORDER BY totalProductsProfit DESC LIMIT 50

				");
				$products_stmt->execute();
				
				// initializing
				$productsArray = [];
				$iv = 0;

				// loop through the product information that have been fetched
				while($product_result = $products_stmt->fetch(PDO::FETCH_OBJ)) {
					$iv++;
					$productsArray[] = [
						'row_id' => $iv,
						'product_id' => $product_result->id,
						'category_id' => $product_result->category_id,
						'product_title' => "<strong class='text-dark'><a href='".$config->base_url('products/'.$product_result->id)."'>{$product_result->product_title}</a></strong><br><span class='text-gray'>({$product_result->branch_name})</span>",
						'orders_count' => $product_result->orders_count,
						'quantity_sold' => $product_result->totalQuantitySold,
						'total_selling_cost' => $clientData->default_currency.number_format($product_result->totalProductsSoldCost, 2),
						'total_selling_revenue' => $clientData->default_currency.number_format($product_result->totalProductsRevenue, 2),
						'product_profit' => $clientData->default_currency.number_format($product_result->totalProductsProfit, 2)
					];
				}
				
				// dates range
				// get the list of all sales returns
				$sales_list = $pos->prepare("
					SELECT 
						SUM(a.order_amount_paid) AS amount_discounted,
						SUM(a.overall_order_amount) AS amount_not_discounted, 
						DATE(a.order_date) AS dates,
						HOUR(a.order_date) AS hourOfDay,
						MONTH(a.order_date) AS monthOfSale,
						(
							SELECT
								COUNT(*)
							FROM sales b 
							WHERE 
								a.order_status = 'confirmed' AND b.deleted = '0' AND  
								($groupBy(b.order_date) = $groupBy(a.order_date))
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$accessLimitInner} {$clientAccessInner} {$customerLimitInner}
						) AS orders_count,
						(
							SELECT
								COUNT(DISTINCT b.customer_id)
							FROM sales b 
							WHERE 
								b.order_status = 'confirmed' AND b.deleted = '0' AND  
								($groupBy(b.order_date) = $groupBy(a.order_date))
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$accessLimitInner} {$clientAccessInner} {$customerLimitInner}
						) AS unique_customers,
						(
							SELECT CASE WHEN SUM(b.order_amount_paid) IS NULL THEN 0.00 ELSE SUM(b.order_amount_paid) END
							FROM sales b
							WHERE b.payment_type='credit' AND b.order_status='confirmed' AND  
							($groupBy(b.order_date) = $groupBy(a.order_date)) AND b.deleted='0' 
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$accessLimitInner} {$clientAccessInner} {$customerLimitInner} 
						) AS total_credit_sales,
						(
							SELECT CASE WHEN SUM(b.order_amount_paid) IS NULL THEN 0.00 ELSE SUM(b.order_amount_paid) END 
							FROM sales b
							WHERE b.payment_type != 'credit' AND b.order_status='confirmed' AND  
							($groupBy(b.order_date) = $groupBy(a.order_date)) AND b.deleted='0'
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$accessLimitInner} {$clientAccessInner} {$customerLimitInner}
						) AS total_actual_sales,
						(
							SELECT SUM(b.product_cost_price * b.product_quantity) FROM sales_details b WHERE ($groupBy(b.order_date) = $groupBy(a.order_date))
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$customerLimit} {$clientAccessInner} {$accessLimit}
						) AS total_cost_price,
						(
							SELECT SUM(b.product_unit_price * b.product_quantity) FROM sales_details b WHERE ($groupBy(b.order_date) = $groupBy(a.order_date))
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$customerLimit} {$clientAccessInner} {$accessLimit}
						) AS total_selling_price,
						(
							SELECT SUM((b.product_unit_price * b.product_quantity)-(b.product_cost_price * b.product_quantity)) FROM sales_details b WHERE 
							($groupBy(b.order_date) = $groupBy(a.order_date))
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$customerLimit} {$clientAccessInner} {$accessLimit}
						) AS total_profit_made,
						(
							SELECT SUM(b.order_discount) FROM sales b WHERE ($groupBy(b.order_date) = $groupBy(a.order_date)) AND b.deleted='0'
							AND (DATE(b.order_date) >= '{$dateFrom}' AND DATE(b.order_date) <= '{$dateTo}') 
							{$branchAccessInner} {$customerLimit} {$clientAccessInner} {$accessLimit}
						) AS total_order_discount
					FROM sales a
					WHERE 
						a.order_status = 'confirmed' AND a.deleted = '0' AND  
						(DATE(a.order_date) >= '{$dateFrom}' AND DATE(a.order_date) <= '{$dateTo}') 
						{$branchAccess} {$accessLimit} {$clientAccess} {$customerLimit} {$groupByClause}
				");
				$sales_list->execute();

				// set some array functions
				$Labeling = array();
				$amount_discounted = array();
				$amount_not_discounted = array();
				$lowestSalesValue = 0;
				$highestSalesValue = 0;
				$totalOrdersCount = 0;
				$creditSales = array();
				$actualSales = array();
				$uniqueCustomers = array();
				$returningCustomers = array();
				$totalCostPrice = array();
				$totalSellingPrice = array();
				$totalProfit = array();

				// fetch the information
				while($sales_result = $sales_list->fetch(PDO::FETCH_OBJ)) {
					// confirm which record to fetch
					if($period == "today") {
						$Labeling[] = $sales_result->hourOfDay;
					} elseif($period == "this-year") {
						$Labeling[] = $sales_result->monthOfSale;
					} else {
						$Labeling[] = date('Y-m-d', strtotime($sales_result->dates));
					}
					// use amount if the sales overview is queried 
					if($metric == 'salesOverview') {
						$amount_not_discounted[] = $sales_result->amount_not_discounted;
						$amount_discounted[] = $sales_result->amount_discounted;
						$creditSales[] = $sales_result->total_credit_sales;
						$actualSales[] = $sales_result->total_actual_sales;

						$totalCostPrice[] = $sales_result->total_cost_price;
						$totalSellingPrice[] = $sales_result->total_selling_price;
						$totalProfit[] = $sales_result->total_profit_made - $sales_result->total_order_discount;
					} else {
						$amount_discounted[] = $sales_result->orders_count;
					}
					$uniqueCustomers[] = $sales_result->unique_customers;
					$totalOrdersCount += $sales_result->orders_count;
					
					// if the metric is orders count
					if($metric == "ordersCount") {
						$returningCustomers[] = ($sales_result->orders_count-$sales_result->unique_customers);
					}
				}

				// fetch the maximum value if the query is for the sales overview
				if($metric == "salesOverview") {
					$lowestSalesValue = (!empty($amount_discounted)) ? min($amount_discounted) : 0.00;
					$highestSalesValue = (!empty($amount_discounted)) ? max($amount_discounted) : 0.00;

					$amountNotDiscountedData = array_combine($Labeling, $amount_not_discounted);
					$actualSalesData = array_combine($Labeling, $actualSales);
					$creditSalesData = array_combine($Labeling, $creditSales);

					$totalCostData = array_combine($Labeling, $totalCostPrice);
					$totalSellingData = array_combine($Labeling, $totalSellingPrice);
					$totalProfitData = array_combine($Labeling, $totalProfit);
				}

				// run this section if the orders count metric was parsed
				if($metric == "ordersCount") {
					// get the list of unique customers
					$actualUniqueCustomers = array_combine($Labeling, $uniqueCustomers);
					$actualReturningCustomers = array_combine($Labeling, $returningCustomers);
				}
				// combine the hour and sales from the database into one set
				$databaseData = array_combine($Labeling, $amount_discounted);

				// labels check 
				$listing = "list-days";
				if($period == "today") {
					$listing = "hour";
				}

				// if the period is a year
				if(in_array($period, ["this-year"])) {
					$listing = "year-to-months";
				}

				// if the metric is orders count
				if($metric == "ordersCount") {
					$format = "Y-m-d";
				}

				// replace the empty fields with 0
				$replaceEmptyField = $dateClass->dataDateComparison($listing, $Labeling, array($dateFrom, $dateTo), $posClass->stringToArray($clientData->shop_opening_days));

				// append the fresh dataset to the old dataset
				$freshData = array_replace($databaseData, $replaceEmptyField);
				ksort($freshData);

				// fetch the maximum value if the query is for the sales overview
				if($metric == "salesOverview") {
					$actualSalesRecord = array_replace($actualSalesData, $replaceEmptyField);
					$creditSalesRecord = array_replace($creditSalesData, $replaceEmptyField);
					$amountNotDiscounted = array_replace($amountNotDiscountedData, $replaceEmptyField);
					ksort($actualSalesRecord);
					ksort($creditSalesRecord);
					ksort($amountNotDiscounted);

					$totalCostRecord = array_replace($totalCostData, $replaceEmptyField);
					$totalSellingRecord = array_replace($totalSellingData, $replaceEmptyField);
					$totalProfitRecord = array_replace($totalProfitData, $replaceEmptyField);
					ksort($totalCostRecord);
					ksort($totalSellingRecord);
					ksort($totalProfitRecord);
				}

				// if the metric is orders count
				if($metric == "ordersCount") {
					// fill in the unique customers list
					$actualUniqueCustomersRecord = array_replace($actualUniqueCustomers, $replaceEmptyField);
					$actualReturningCustomersRecord = array_replace($actualReturningCustomers, $replaceEmptyField);
					ksort($actualUniqueCustomersRecord);
					ksort($actualReturningCustomersRecord);
				}

				$labelArray = array_keys($freshData);
				$labels = [];
				// confirm which period we are dealing with
				if($listing == "list-days") {
					// change the labels to hours of day
					foreach($labelArray as $key => $value) {
						$labels[] = date("Y-m-d", strtotime($value));
					}
				} elseif($listing == "hour") {
					// change the labels to hours of day
					foreach($labelArray as $key => $value) {
						$labels[] = $dateClass->convertToPeriod("hour", $value);
					}
				} elseif($listing == "year-to-months") {
					// change the labels to hours of day
					foreach($labelArray as $key => $value) {
						$labels[] = $dateClass->convertToPeriod("month", $value);
					}
				}

				// Parse the amount into the chart array data
				$resultData = array();
				$resultData["type"] = "bar";
				$resultData["fill"] = false;
				$resultData["legend"] = false;
				$resultData["borderColor"] = "#fff";
				$resultData["borderWidth"] = 2;
				$resultData["labeling"] = $labels;
				$resultData["data"] = array_values($freshData);

				// if the metric is orders count
				if($metric == "ordersCount") {
					$resultData["count"] = $totalOrdersCount;
					$resultData["unique_customers"] = array_values($actualUniqueCustomersRecord);
					$resultData["returning_customers"] = array_values($actualReturningCustomersRecord);
				}
				
				// parse the highest sales value
				if($metric == "salesOverview") {
					$resultData["sales"] = [
						'discount_effect' => [
							'with_discount' => array_values($freshData),
							'without_discount' => array_values($amountNotDiscounted),
							'total_sale' => $clientData->default_currency . number_format(array_sum(array_values($amountNotDiscounted)), 2),
							'discounted_sale' => $clientData->default_currency . number_format(array_sum(array_values($freshData)), 2)
						],
						'highest' => $clientData->default_currency . number_format($highestSalesValue, 2),
						'lowest' => $clientData->default_currency . number_format($lowestSalesValue, 2),
						'actuals' => array_values($actualSalesRecord),
						'credit' => array_values($creditSalesRecord),
						'comparison' => [
							'total_sales' => $clientData->default_currency . number_format(array_sum($amount_discounted), 2),
							'total_actual_sales' => $clientData->default_currency . number_format(array_sum($actualSalesRecord), 2),
							'total_credit_sales' => $clientData->default_currency . number_format(array_sum($creditSalesRecord), 2),
						],
						'revenue' => [
							'cost' => array_values($totalCostRecord),
							'selling' => array_values($totalSellingRecord),
							'profit' => array_values($totalProfitRecord),
						],
						'payment_options' => $paymentBreakdown,
						'products_performance' => $productsArray,
						'category_sales' => [
							'labels' => $category_names,
							'data' => $category_amount
						]
					];
				}
				
			}

			// if the metric is to fetch the sales attendants perfomance 
			elseif($metric == 'salesAttendantPerformance') {

				// if the sales records of attendant is being fetched
				if(isset($_POST['salesAttendantHistory'])) {

					// set the variables
					$userId = (isset($postData->userId)) ? $postData->userId : null;
					$recordType = (isset($postData->recordType)) ? $postData->recordType : null;

					// check which details to fetch
					if($recordType == "customer") {
						$where = "a.customer_id = '{$userId}'";
					} else {
						$where = "a.recorded_by='{$userId}'";
					}

					$dateFrom = $session->queryRange['start'];
					$dateTo = $session->queryRange['end'];
					
					// run this query
					$salesAttendants = $posClass->getAllRows(
						"sales a LEFT JOIN customers b ON b.customer_id=a.customer_id",
						"a.payment_type, a.order_amount_paid, DATE(a.order_date) AS order_date, 
						a.order_id, b.customer_id,
						CONCAT(b.firstname, ' ', b.lastname) AS fullname, a.credit_sales
						",
						"{$where} AND a.order_status='confirmed' AND a.deleted='0'
							AND (DATE(a.order_date) >= '{$dateFrom}' && 
							DATE(a.order_date) <= '{$dateTo}') {$branchAccess} {$accessLimit} {$clientAccess} ORDER BY DATE(a.order_date) ASC"
					);

					// set the response data
					$resultData = $salesAttendants;

				} else {

					// access control
					$userLimit = "";
					if(!$accessObject->hasAccess('monitoring', 'branches')) {
						$userLimit = "AND a.user_id='{$session->userId}'";
					}

					// begin the query
					$salesAttendants = $posClass->getAllRows(
						"users a", 
						"a.user_id, a.daily_target, a.monthly_target, a.weekly_target,
						CONCAT(a.name) AS fullname,
						(
							SELECT 
								CASE WHEN SUM(b.order_amount_paid) IS NULL THEN 0.00 ELSE SUM(b.order_amount_paid) END 
							FROM sales b 
							WHERE 
								b.recorded_by=a.user_id AND b.order_status='confirmed' AND b.deleted='0' AND
								(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') 
								{$branchAccessInner} {$clientAccessInner}
						) AS amnt,
						(
							SELECT 
								SUM(b.product_quantity)
							FROM sales_details b
							LEFT JOIN sales c ON c.order_id = b.order_id
							WHERE c.recorded_by = a.user_id AND c.order_status='confirmed'
								AND c.deleted = '0' AND
								(DATE(c.order_date) >= '{$dateFrom}' && DATE(c.order_date) <= '{$dateTo}')
								{$branchAccessInner} {$clientAccessInner}

						) AS total_items_sold,
						(
							SELECT COUNT(*) FROM sales b 
							WHERE 
								b.recorded_by=a.user_id AND b.order_status='confirmed' AND b.deleted='0' AND
								(DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') 
								{$branchAccessInner} {$clientAccessInner}
						) AS orders",
						"a.status='1' {$userLimit} {$branchAccess} {$clientAccess} ORDER BY amnt DESC LIMIT 10"
					);
					
					$personnelNames = array();
					$personnelSales = array();
					$attendantSales = array();
					$salesTarget = 0;

					//: loop throught the dataset
					foreach($salesAttendants as $eachPersonnel) {
						//: append the data
						$personnelNames[] = $eachPersonnel->fullname;
						$personnelSales[] = $eachPersonnel->amnt;

						$uName = "<div class='text-left'><a data-name=\"{$eachPersonnel->fullname}\" data-record=\"attendant\" data-value=\"{$eachPersonnel->user_id}\" class=\"view-user-sales font-weight-bold\" href='javascript:void(0)'>{$eachPersonnel->fullname}</a></div>";

						if($period == "today") {
							$salesTarget = $eachPersonnel->daily_target;
						} elseif($period == "this-week") {
							$salesTarget = $eachPersonnel->weekly_target;
						} elseif($period == "this-month") {
							$salesTarget = $eachPersonnel->monthly_target;
						} elseif($period == "this-year") {
							$salesTarget = ($eachPersonnel->monthly_target * 12);
						}

						$salesTargetPercentage = ($eachPersonnel->amnt > 0 && $salesTarget > 0) ? (($eachPersonnel->amnt / $salesTarget) * 100) : 0; 

						$attendantSales[] = [
							'fullname' => $uName,
							'sales' => [
								'orders' => $eachPersonnel->orders,
								'amount' => $clientData->default_currency.number_format($eachPersonnel->amnt, 2),
								'average_sale' => ($eachPersonnel->orders > 0) ? $clientData->default_currency.number_format(($eachPersonnel->amnt/$eachPersonnel->orders), 2) : $clientData->default_currency."0.00"
							],
							'items' => [
								'total_items_sold' => (int) $eachPersonnel->total_items_sold,
								'average_items_sold' => ($eachPersonnel->orders > 1) ? number_format($eachPersonnel->total_items_sold / $eachPersonnel->orders) : 0
							],
							'targets' => [
								'target_amount' => $clientData->default_currency.number_format($salesTarget, 2),
								'target_percent' => number_format($salesTargetPercentage, 2)."%"
							]
						];
					}

					$resultData = [
						'list' => $attendantSales,
						'names' => $personnelNames,
						'sales' => $personnelSales
					];

				}
			
			}

			// if the metric is to fetch the top contacts performance
			elseif($metric == 'topContactsPerformance') {

				if(empty($session->reportingCustomerId)) {
					// Fetch the list of contacts and order total amounts
					$contactsPerformance = $pos->prepare("
						SELECT
							a.customer_id, CONCAT(a.firstname, ' ', a.lastname) AS customer_name,
							(
							    SELECT 
							    	CASE WHEN SUM(b.order_amount_paid) IS NULL THEN 0.00 ELSE SUM(b.order_amount_paid) END
							   	FROM
							    	sales b
							    WHERE
							    	b.order_status='confirmed'
							    	AND b.deleted='0' 
							    	AND (DATE(b.order_date) >= '{$dateFrom}' 
							    	AND DATE(b.order_date) <= '{$dateTo}')
							    	AND b.customer_id = a.customer_id
							    	{$accessLimitInner} {$branchAccessInner} {$clientAccessInner}
							) AS total_amount,
							(
							    SELECT 
							    	CASE WHEN SUM(b.order_amount_balance) IS NULL THEN 0 ELSE SUM(b.order_amount_balance) END
							   	FROM
							    	sales b
							    WHERE
							    	b.order_status='confirmed' 
							    	AND b.deleted='0'
							    	AND (DATE(b.order_date) >= '{$dateFrom}' 
							    	AND DATE(b.order_date) <= '{$dateTo}')
							    	AND b.customer_id = a.customer_id
							    	{$accessLimitInner} {$branchAccessInner} {$clientAccessInner}
							) AS total_balance,
							(
							    SELECT 
							    	COUNT(*)
							   	FROM
							    	sales b
							    WHERE
							    	b.order_status='confirmed' 
							    	AND b.deleted='0'
							    	AND (DATE(b.order_date) >= '{$dateFrom}' 
							    	AND DATE(b.order_date) <= '{$dateTo}')
							    	AND b.customer_id = a.customer_id
							    	{$accessLimitInner} {$branchAccessInner} {$clientAccessInner}
							) AS orders_count

						FROM
							customers a
						WHERE
							a.status = '1' {$branchAccess} {$clientAccess}
						ORDER BY total_amount DESC LIMIT 30
					");
					$contactsPerformance->execute();

					$resultData = [];
					$row = 0;
					// set the response data
					while($result = $contactsPerformance->fetch(PDO::FETCH_OBJ)) {
						if($result->total_amount > 0) {
							$row++;
							$result->row_id = $row;
							$result->fullname = "<a href=\"{$config->base_url('reports/'.$result->customer_id)}\" title=\"Click to list customer orders history\" data-value=\"{$result->customer_id}\" class=\"customer-orders\" data-name=\"{$result->customer_name}\">{$result->customer_name}</a>";

							$result->action = "<a href=\"javascript:void(0);\" title=\"Click to list customer orders history\" data-name=\"{$result->customer_name}\" data-record=\"customer\" data-value=\"{$result->customer_id}\" class=\"view-user-sales btn btn-sm btn-outline-success\"><i class=\"fa fa-list\"></i></a> <a href=\"{$config->base_url('reports/'.$result->customer_id)}\" title=\"Click to list customer orders history\" data-name=\"{$result->customer_name}\" data-record=\"customer\" data-value=\"{$result->customer_id}\" class=\"btn btn-sm btn-outline-primary\"><i class=\"fa fa-chart-bar\"></i></a>";

							$result->total_amount = "{$clientData->default_currency} ".number_format($result->total_amount, 2);
							$resultData[] = $result;
						}
					}
				}

			}

			// if the metric is to fetch the performance of various branches
			elseif($metric == 'branchPerformance') {
				
				//: fetch the dataset
				$stmt = $pos->prepare("
					SELECT 
						a.branch_name, a.square_feet_area,
						(
							SELECT 
								COUNT(*)
							FROM sales b
							WHERE b.branchId = a.id AND b.deleted='0'
								AND (DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS orders_count,
						(
							SELECT 
								ROUND(SUM(b.order_amount_paid) ,2) 
							FROM sales b
							WHERE b.branchId = a.id AND b.deleted='0'
								AND (DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS total_sales,
						(
							SELECT 
								ROUND(MAX(b.order_amount_paid) ,2)
							FROM sales b
							WHERE b.branchId = a.id AND b.deleted='0' 
								AND (DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS highest_sales,
						(
							SELECT 
								ROUND(MIN(b.order_amount_paid) ,2) 
							FROM sales b
							WHERE b.branchId = a.id AND b.deleted='0'
								AND (DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS lowest_sales,
						(
							SELECT 
								ROUND(AVG(b.order_amount_paid) ,2)
							FROM sales b
							WHERE b.branchId = a.id AND b.deleted='0'
								AND (DATE(b.order_date) >= '{$dateFrom}' && DATE(b.order_date) <= '{$dateTo}') AND order_status='confirmed'
								{$accessLimitInner} {$clientAccessInner}
						) AS average_sales
					FROM branches a
					WHERE 1 {$branchAccess2} {$clientAccess}
						ORDER BY total_sales ASC
				");
				$stmt->execute();

				//: fetch the other totals
				$chart_stmt = $pos->prepare("
					SELECT 
						SUM(a.order_amount_paid) AS amount, 
						DATE(a.order_date) AS dates,
						HOUR(a.order_date) AS hourOfDay,
						MONTH(a.order_date) AS monthOfSale
					FROM sales a
					WHERE 
						a.order_status = 'confirmed' AND a.deleted = '0' AND  
						(DATE(a.order_date) >= '{$dateFrom}' AND DATE(a.order_date) <= '{$dateTo}') 
						{$branchAccess} {$accessLimit} {$clientAccess} {$groupByClause}
				");
				$chart_stmt->execute();

				// Check If User ID Exists
				$branchesList = $posClass->getAllRows("branches", "branch_name, id", "clientId='{$posClass->clientId}' AND deleted='0'");

				// set some array functions
				$Labeling = array();
				$amount = array();
				$summary = [];
				$lowestSalesValue = 0;
				$highestSalesValue = 0;

				//: using while
				while($chart_result = $chart_stmt->fetch(PDO::FETCH_OBJ)) {
					
					// confirm which record to fetch
					if($period == "today") {
						$Labeling[] = $chart_result->hourOfDay;
					} elseif($period == "this-year") {
						$Labeling[] = $chart_result->monthOfSale;
					} else {
						$Labeling[] = date('Y-m-d', strtotime($chart_result->dates));
					}
					$amount[] = $chart_result->amount;
				}

				//: branch summaries
				while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
					$result->square_feet_sales = ($result->square_feet_area > 0) ? $clientData->default_currency.number_format(($result->total_sales / $result->square_feet_area), 2) : 0;
					$result->lowest_sales = $clientData->default_currency.number_format($result->lowest_sales, 2);
					$result->highest_sales = $clientData->default_currency.number_format($result->highest_sales, 2);
					$result->average_sales = $clientData->default_currency.number_format($result->average_sales, 2);
					$result->total_sales = $clientData->default_currency.number_format($result->total_sales, 2);
					
					$summary[] = $result;
				}

				// chart data
				$chartData = array();
				$chartData["type"] = "bar";
				$chartData["fill"] = false;
				$chartData["legend"] = false;
				$chartData["borderColor"] = "#fff";
				$chartData["borderWidth"] = 2;
				$chartData["data"] = $amount;
				$chartData["labels"] = $Labeling;

				$resultData = [
					'summary' => $summary,
					'sales_overview' => $chartData
				];
			}

			// set the query range in an array
			$queryRange = [
				'start' => $dateFrom, 'end' => $dateTo
			];

			// set the data query range in a session
			$session->set_userdata('queryRange', $queryRange);

		}

		// Set the response data to display
		$response = [
			'status' => 200,
			'request' => $metric,
			'result' => $resultData,
			'date_range' => json_encode($queryRange)
		];

	}

	// fetch customers list for json
	elseif(isset($_POST["fetchCustomersOptionsList"]) && confirm_url_id (1, "fetchCustomersOptionsList")) {
		// fetch the data
		$customersClass = load_class("Customers", "controllers");
		$customers = $customersClass->fetch("id, customer_id, firstname, lastname, CONCAT(firstname, ' ', lastname) AS fullname, preferred_payment_type, date_log, clientId, branchId, phone_1, phone_2, email, residence", "AND customer_id != 'WalkIn'");

		// fetch the data
		$customers_list = [];

		$response = [
			"status" => 200,
			"result" => $customers
		];
	}

	// fetch customers list for json
	elseif(isset($_POST["fetchPOSProductsList"]) && confirm_url_id (1, "fetchPOSProductsList")) {
		// query the database
		$result = $posClass->getAllRows("products", 
			"id, product_image, category_id, product_title, source, quantity, category_id, product_image AS image, product_id, product_price, date_added, product_description, product_price, cost_price, threshold", "status = '1' AND branchId = '{$session->branchId}' AND clientId = '{$posClass->clientId}'");

		// data
		$productsList = [];
		$ii = 0;
		// initializing
		if(count($result) > 0) {
			// loop through the list of products
			foreach($result as $results) {
				// increment
				$ii++;

				// add to the list to return
				$productsList[] = [
					'row_id' => $ii,
					'product_id' => $results->id,
					'product_code' => $results->product_id,
					'product_title' => $results->product_title,
					'price' => $results->product_price,
					'threshold' => $results->threshold,
					'source' => $results->source,
					'product_description' => $results->product_description,
					'date_added' => $results->date_added,
					'cost_price' => $results->cost_price,
					'category_id' => $results->category_id,
					'image' => (($results->source == 'Vend') ? $results->product_image : ((empty($results->product_image)) ? $config->base_url("assets/images/products/default.png") : ((!empty($results->product_image) && file_exists($results->product_image)) ? $config->base_url($results->product_image) : $config->base_url("assets/images/products/$results->product_image")))),
					'product_quantity' => $results->quantity,
					'product_price' => "<input class='form-control input_ctrl' style='width:100px' data-row-value=\"{$results->id}\" data-product-id='{$results->id}' name=\"product_price\" value=\"".$results->product_price."\" id=\"product_price_{$results->id}\" type='number' min='1'>",
					'quantity' => "<input data-row-value=\"{$results->id}\" class='form-control input_ctrl' style='width:100px' data-product-id='{$results->id}' value='1' name=\"product_quantity\" id=\"product_quantity_{$results->id}\" type='number' min='1'>",
					'overall' => "<span data-row-value=\"{$results->id}\" id=\"product_overall_price\">".number_format($results->product_price, 0)."</span>",
	                "action" => "<button data-image=\"{$results->product_image}\" type=\"button\" class=\"btn btn-success atc-btn\" data-row-value=\"{$results->id}\" data-name=\"{$results->product_title}\"><i class=\"ion-ios-cart\"></i> Add</button>"

				];
			}
		}

		$response = [
			"status" => 200,
			"result" => $productsList
		];

	}

	// point of sale processing
	elseif(confirm_url_id(1, 'pointOfSaleProcessor')) {

		// adding a new customer
		if(isset($_POST["nc_firstname"]) and confirm_url_id(2, 'quick-add-customer')) {
			
			// create a new object
			$customersObj = load_class("Customers", "controllers");

			// clean the submitted post data
			$customer = (object) array_map("xss_clean", $_POST);
			
			// validate the user data
			if(empty(trim($customer->nc_firstname))){
				$response->message = "Please enter customer's name";
			} elseif(!empty($customer->nc_email) && !filter_var($customer->nc_email, FILTER_VALIDATE_EMAIL)) {
				$response->message = "Please enter a valid email address";
			}
			else{
				$newCustomer = $customersObj->quickAdd($customer);
				if(!empty($newCustomer)){
					$response->status = "success";
					$response->message = "Customer Added";
					$response->data = $newCustomer;
				}
			}
		}

		// saving the point of sale register
		elseif(isset($_POST["customer"]) and confirm_url_id(2, 'saveRegister')) {
			
			// create a new orders object
			$ordersObj = load_class("Orders", "controllers");
			$register = (object) $_POST;

			// ensure all required parameters was parsed
			if(
				isset(
					$register->payment_type, $register->amount_paying, 
					$register->customer, $register->amount_to_pay,
					$register->total_to_pay, $register->discountType, $register->discountAmount
				)
			) {
				// submit the data for processing
				$result = $ordersObj->saveRegister($register);

				// get the response from the call
				if(!empty($result)){
					$response->status = "success";
					$response->message = "Register Saved";
					$response->data = $result;
				}
			}
		}

		// initiate an email to be sent to the user
		elseif(isset($_POST["sendMail"], $_POST["thisEmail"]) and confirm_url_id(2, 'sendMail')) {

			// clean the submitted post data
			$email = (object) array_map("xss_clean", $_POST);

			// validate the data submitted by the user
			if(!filter_var($email->thisEmail, FILTER_VALIDATE_EMAIL)) {
				$response->message = "Please enter a valid email address";
			} elseif(strlen($session->_oid_Generated) < 12) {
				$response->message = "An invalid Order ID was submitted";
			}
			else {

				// additional data
				$email->branchId = (isset($email->branchId)) ? $email->branchId : $session->branchId;
				$email->template_type = (isset($email->thisRequest)) ? $email->thisRequest : "invoice";
				$email->itemId = $session->_oid_Generated;
				$email->fullname = (isset($email->fullname)) ? str_replace(["\t", "\n"], "", trim($email->fullname)) : null;

				// generate the recipient list
				$email->recipients_list = [
					"recipients_list" => [
						[
							"fullname" => $email->fullname,
							"email" => $email->thisEmail,
							"customer_id" => $session->_uid_Generated,
							"branchId" => $session->branchId
						]
					]
				];

				// submit the data for insertion into the mail sending list
				$ordersObj = load_class("Orders", "controllers");
				$newMail = $ordersObj->quickMail($email);

				// get the response
				if(!empty($newMail)){
					$response->status = "success";
					$response->message = "Invoice successfully sent via Email";
					$response->data = $newMail;
				}
			}
		}

		//: payment processor
		elseif(isset($_POST["processMyPayment"], $_POST["orderId"], $_POST["orderTotal"]) && confirm_url_id(2, 'processMyPayment')) {
			//: initializing
			$postData = (Object) array_map("xss_clean", $_POST);
			$theTeller = load_class('Payswitch', 'controllers');
			$status = false;

			// Check If Order Is Saved In Database
			$check = $posClass->getAllRows(
				"sales",
				"COUNT(*) as orderExists",
				"transaction_id = '{$postData->orderId}'"
			);

			if ($check != false && $check[0]->orderExists == 1) {

				// Call Teller Processor
				$process = $theTeller->initiatePayment($postData->orderTotal, $postData->userEmail, $postData->orderId);

	            // Check Response Code & Execute
	            if (isset($process['code']) && ($process['code'] == '200')) {

	                $session->set_userdata("tellerPaymentTransID", $postData->orderId);

	                if ($process['status'] == 'vbv required') {
	                    // VBV Required
	                    $message = [
	                    	"msg" => '
	                    	<p class="alert alert-warning">Your Card Needs Authorization.</p>
	                    	<a href="'.($process['reason']).'" target="_blank" class="btn btn-success">Click To Verify Card</a>',
	                    	"action" => false
	                    ];
	                } else {
	                	// No VBV Required
	                	$session->set_userdata("tellerUrl", $process['checkout_url']);

	                	$message = [
	                		"msg" => $process['checkout_url'],
	                		"action" => true
	                	];
	                }
	                $status = true;
	            } else {
	                $message = '<p class="text-danger">Failed To Process Payment!</p>';
	            }

			}

			$response = [
				"status" => $status,
				"message" => $message
			];
		
		}

		//: check payment status
		elseif (isset($_POST['checkPaymentStatus']) && confirm_url_id(2, "checkPaymentStatus")) {
			// create new object
			$theTeller = load_class('Payswitch', 'controllers');
			$status = false;
			// check the session has been set
			if ($session->tellerPaymentStatus == true) {
				$transaction_id = xss_clean($session->tellerPaymentTransID);

				$checkStatus = $posClass->getAllRows(
					"sales",
					"order_status",
					"payment_date IS NOT NULL && transaction_id = '{$transaction_id}'"
				);

				if ($checkStatus != false && $checkStatus[0]->order_status == "confirmed") {
					$status = true;
					// Unset Session
					$session->unset_userdata("tellerPaymentStatus");
					$session->unset_userdata("tellerPaymentTransID");
					$session->unset_userdata("tellerUrl");
				} else if ($checkStatus != false && $checkStatus[0]->order_status == "cancelled") {
					$status = "cancelled";
					// Unset Session
					$session->unset_userdata("tellerPaymentStatus");
					$session->unset_userdata("tellerPaymentTransID");
					$session->unset_userdata("tellerUrl");
				}
			}

			$response = [
				"status" => $status,
				"result" => ""
			];
			

		}

		//: cancel payment
		elseif(isset($_POST['cancelPayment']) && confirm_url_id(2, "cancelPayment")) {

			// assign variable to the transaction id
			$transaction_id = xss_clean($session->tellerPaymentTransID);
			$status = false;
			// update the sales table
			$query = $posClass->updateData(
				"sales",
				"order_status = 'cancelled', deleted = '1'",
				"transaction_id = '{$transaction_id}'"
			);

			// return true
			if ($query == true) {
				$status = 200;
				// Unset Session
				$session->unset_userdata("tellerPaymentStatus");
				$session->unset_userdata("tellerPaymentTransID");
				$session->unset_userdata("tellerUrl");
			}

			$response = [
				"status" => $status,
				"result" => ""
			];

		}

	}

	//: Quotes / Requests
	elseif(isset($_POST["listRequests"], $_POST["requestType"]) && confirm_url_id(1, 'listRequests')) {
		// assign variable to remove
		$postData = (Object) array_map('xss_clean', $_POST);

		// check the access levels
		if($accessObject->hasAccess('view', 'branches')) {
			// list all quotes
			$accessLevelClause = "AND rq.branchId = '{$session->branchId}'"; 
		}

		// query the database
		$result = $posClass->getAllRows(
			"requests rq 
				LEFT JOIN customers ct ON ct.customer_id = rq.customer_id
				LEFT JOIN users us ON us.user_id = rq.recorded_by
				LEFT JOIN branches bc ON bc.id = rq.branchId
			", 
			"rq.id, rq.branchId, rq.request_id, rq.customer_id, rq.currency, 
				CASE WHEN rq.request_discount IS NULL THEN rq.request_total ELSE (rq.request_total-rq.request_discount) END AS request_sum, rq.request_date, bc.branch_name, rq.request_type,
				rq.recorded_by, CONCAT(ct.firstname, ' ', ct.lastname) AS customer_name,
				us.name AS recorded_by
			", 
			"rq.deleted = '0' {$accessLevelClause} AND 
				rq.clientId = '{$posClass->clientId}' AND rq.request_type = '{$postData->requestType}' ORDER BY rq.id DESC"
		);

		// initializing
		$row = 0;
		$results = [];

		// loop through the list of items
		foreach($result as $eachRequest) {
			// configure the
			$row++;

			$eachRequest->action = "<div align=\"center\">";
	        
	        $eachRequest->action .= "<a class=\"btn btn-sm btn-outline-primary\" title=\"Export the {$eachRequest->request_type} to PDF\" data-value=\"{$eachRequest->request_id}\" href=\"".$config->base_url('export/'.$eachRequest->request_id)."\" target=\"_blank\"><i class=\"fa fa-file-pdf\"></i> </a> &nbsp;";

	        // check if the user has access to delete this item
	        if($accessObject->hasAccess('delete', strtolower($eachRequest->request_type.'s'))) {
	        	// print the delete button
	        	$eachRequest->action .= "<a class=\"btn btn-sm delete-item btn-outline-danger\" data-msg=\"Are you sure you want to delete this {$eachRequest->request_type}\" data-request=\"{$eachRequest->request_type}\" data-url=\"{$config->base_url('aj/deleteData')}\" data-id=\"{$eachRequest->request_id}\" href=\"javascript:void(0)\"><i class=\"fa fa-trash\"></i> </a>";
	        }

	        $eachRequest->action .= "</div>";

			// append to the list of items
			$results[] = [
				'row_id' => $row,
				'request_type' => $eachRequest->request_type,
				'request_id' => "<a target=\"_blank\" class=\"text-success\" title=\"Click to view full details\" href=\"{$config->base_url('export/'.$eachRequest->request_id)}\">{$eachRequest->request_id}</a>",
				'branch_name' => $eachRequest->branch_name,
				'customer_name' => $eachRequest->customer_name,
				'quote_value' => "{$clientData->default_currency} ". number_format($eachRequest->request_sum, 2),
				'recorded_by' => $eachRequest->recorded_by,
				'request_date' => $eachRequest->request_date,
				'action' => $eachRequest->action
			];
		}

		$response = [
			'status' => 200,
			'result' => $results,
			'rows_count' => count($result)
		];

	}

	//: Process requests
	// Submit to cart for processing
	elseif(isset($_POST['selectedProducts'], $_POST["request"], $_POST["customerId"], $_POST["discountType"], $_POST["discountAmt"]) && confirm_url_id(1, 'pushRequest')) {
		// #initializing
		$data = "";
		$status = 500;
		$responseData = array();
		$currentRequest = $session->thisRequest;
		
		// check all the values parsed
		if($currentRequest != $_POST["request"]) {
			$data = "Sorry! Another tab is opened, refresh this page to continue.";
		} elseif($_POST["customerId"] == "null") {
			$data = "Sorry! Customer Name cannot be empty.";
		} elseif(!in_array($_POST["request"], ["QuotesList", "OrdersList"])) {
			$data = "Sorry! An invalid request was made.";
		} elseif(!in_array($_POST["discountType"], ["percentage", "cash"])) {
			$data = "Sorry! An invalid discount type selected.";
		} else {
			# check the user input
			$discountAmt = (isset($_POST["discountAmt"])) ? xss_clean($_POST["discountAmt"]) : 0;
			$discountType = (isset($_POST["discountType"])) ? xss_clean($_POST["discountType"]) : 0;
			$customerId = xss_clean($_POST["customerId"]);

			# products list
			$productsList = $_POST['selectedProducts'];

			# call the orders controller
			$requestClass = load_class('Requests', 'controllers');

			# get the request type
			$request = ($_POST["request"] == 'QuotesList') ? 'Quote' : 'Order';

			// process the order list
			if($requestClass->addRequest($request, $customerId, $discountAmt, $discountType, $productsList)) {
				$status = 200;
				$data = [
					"message" => "Record was successfully inserted!",
					"invoiceNumber" => $posClass->lastColumnValue('request_id', 'requests'),
					"requestType" => strtolower($request)
				];
			} else {
				$data = "Sorry! There was an error while processing the request.";
			}
		}

		$response = array(
			"status" => $status,
			"result" => $data
		);

	}

	//: branch management
	elseif(confirm_url_id(1, "branchManagment")) {

		// initializing
		$message = "Error processing request!";
		$status = false;

		// fetch the list of all branches
		if (isset($_POST['request'], $_POST['request']) and confirm_url_id(2, "fetchBranchesLists")) {

			$condition = "AND deleted=?";
			$message = [];

			// set the branch id to use
			$branchData = (Object) array_map("xss_clean", $_POST);

			// confirm the user permission to perform this action
			if($accessObject->hasAccess('view', 'branches')) {
				// fetch the branch information
				$query = $pos->prepare("
					SELECT * 
					  FROM branches 
					WHERE clientId = ? {$condition} ORDER BY id");

				if ($query->execute([$posClass->clientId, 0])) {
					$i = 0;
					while ($data = $query->fetch(PDO::FETCH_OBJ)) {
						$i++;

						$branch_type = "<br><span class='badge text-white ".(($data->branch_type == 'Store') ? "badge-dark" : "badge-primary")."'>{$data->branch_type}</span>";

						$action = '<div width="100%" align="center">';
						if($accessObject->hasAccess('update', 'branches')) {
							$action .=  "<button class=\"btn btn-sm btn-outline-success edit-branch\" data-branch-id=\"{$data->branch_id}\">
								<i class=\"fa fa-edit\"></i>
							</button> ";
						}

						if($accessObject->hasAccess('delete', 'branches')) {
							$action .= "<button class=\"btn btn-sm ".(($data->status == 1) ? "btn-outline-danger" : "btn-outline-primary")." delete-item\" data-url=\"".$config->base_url('aj/branchManagment/updateStatus')."\" data-state=\"{$data->status}\" data-msg=\"".(($data->status == 1) ? "Are you sure you want to set the {$data->branch_name} as inactive?" : "Do you want to proceed and set the {$data->branch_name} as active?")."\" data-request=\"branch-status\" data-id=\"{$data->id}\">
								<i class=\"fa ".(($data->status == 1) ? "fa-stop" : "fa-play")."\"></i>
							</button> ";
						}

						$action .= "</div>";

						$message[] = [
							'row_id' => $i,
							'branch_id' => $data->branch_id,
							'branch_type' => $data->branch_type,
							'branch_name_text' => $data->branch_name,
							'branch_name' => $data->branch_name.$branch_type,
							'location' => $data->location,
							'email' => $data->branch_email,
							'contact' => $data->branch_contact,
							'status' => "<div align='center'>".(($data->status == 1) ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>")."</div>",
							'action' => $action
						];
					}
					$status = true;
				}
			} else {
				$message = [];
			}

		} elseif(isset($_POST['branchId'], $_POST['getBranchDetails']) && confirm_url_id(2, "getBranchDetails")) {

			$branchId = xss_clean($_POST['branchId']);

			// set the branch id to use
			$branchData = (Object) array_map("xss_clean", $_POST);

			// check if the user has permissions to perform this action
			if($accessObject->hasAccess('view', 'branches')) {

				// Check If Branch Exists
				$query = $pos->prepare("SELECT * FROM branches WHERE branch_id = ? && deleted = ? && clientId = ?");

				if ($query->execute([$branchId, '0', $posClass->clientId])) {

					$data = $query->fetch(PDO::FETCH_OBJ);

					$session->curBranchId = $branchId;

					$message = [
						"branch_id"	=> $data->branch_id,
						"branch_name"	=> $data->branch_name,
						"branch_name_text" => $data->branch_name,
						"contact" => $data->branch_contact,
						"email"	=> $data->branch_email,
						"location"	=> $data->location,
						"status"	=> (($data->status == 1) ? "Active" : "Inactive"),
						"branch_type" => $data->branch_type
					];
					$status = true;
				} else {
					$message = "Sorry! Branch Cannot Be Found.";
				}
			} else {
				$message = "Sorry! You do not have the required permissions to perform this action.";
			}
			
		} elseif (isset($_POST['branchName'], $_POST['branchType']) && confirm_url_id(2, "addBranchRecord")) {

			// set the branch id to use
			$branchData = (Object) array_map("xss_clean", $_POST);

			// Check If Fields Are Not Empty
			if (!empty($_POST['branchName']) && !empty($_POST['branchType'])) {

				// validate the records
				if(!empty($branchData->email) && !filter_var($branchData->email, FILTER_VALIDATE_EMAIL)) {
					$message = "Please enter a valid email address";
				} elseif(!empty($branchData->phone) && !preg_match('/^[0-9+]+$/', $branchData->phone)) {
					$message = "Please enter a valid contact number";
				} elseif(!in_array($branchData->branchType, ['Store', 'Warehouse'])) {
					$message = "Invalid branch type was submitted";
				} else {
					
					// if the user wants to add a new record
					if ($branchData->record_type == "new-record") {

						// Check Email Exists
						$checkData = $posClass->getAllRows("branches", "COUNT(*) AS proceed", "branch_name='{$branchData->branchName}' && branch_type = '{$branchData->branchType}' && deleted = '0' && clientId = '{$posClass->clientId}'");

						if ($checkData != false && $checkData[0]->proceed == '0') {

							// check if the user has permissions to perform this action
							if($accessObject->hasAccess('add', 'branches')) {
								//: Generate a new branch id
								$branch_id = random_string('alnum', 12);

								// Add Record To Database
								$response = $posClass->addData(
									"branches" ,
									"clientId='{$posClass->clientId}', branch_type='{$branchData->branchType}', branch_name='{$branchData->branchName}', location='{$branchData->location}', branch_email='{$branchData->email}', branch_contact='{$branchData->phone}', branch_logo='{$clientData->client_logo}', branch_id = '{$branch_id}'"
								);

								// Record user activity
								$posClass->userLogs('branches', $branch_id, 'Added a new Store Outlet into the System.');

								if ($response == true) {
									// Show Success Message
									$message = "Branch Have Been Successfully Registered.";
									$status = true;
								} else {
									$message = "Error encountered while processing request.";
								}
							} else {
								$message = "Sorry! You do not have the required permissions to perform this action.";
							}
						} else {
							$message = "Sorry! This branch already exist.";
						}
					} else if ($branchData->record_type == "update-record") {
						// CHeck If User ID Exists
						$checkData = $posClass->getAllRows("branches", "COUNT(*) AS branchTotal", "branch_id='{$branchData->branchId}'");

						if ($checkData != false && $checkData[0]->branchTotal == '1') {

							// call the branch id from the session
							$branchData->branchId = $session->curBranchId;

							// check if the user has permissions to perform this action
							if($accessObject->hasAccess('update', 'branches')) {
								// update user data
								$response = $posClass->updateData(
									"branches",
									"branch_name='{$branchData->branchName}', location='{$branchData->location}', branch_type='{$branchData->branchType}', branch_email='{$branchData->email}', branch_contact='{$branchData->phone}'",
									"branch_id='{$branchData->branchId}' && clientId='{$posClass->clientId}'"
								);

								// Record user activity
								$posClass->userLogs('branches', $branchData->branchId, 'Updated the details of the Store Outlet.');

								if ($response == true) {

									$message = "Store Outlet Details Have Been Successfully Updated.";
									$status = true;
								} else {
									$message = "Sorry! Store Outlet Records Failed To Update.";
								}
							} else {
								$message = "Sorry! You do not have the required permissions to perform this action.";
							}

						} else {
							$message = "Sorry! Store Outlet Does Not Exist.";
						}
						// Update Record
					} else {
						$message = "Your Request Is Not Recognized";
					}
				}

			} else {
				$message = "Please Check All Required Fields.";
			}

		}

		// update the branch state
		elseif(isset($_POST['itemToDelete'], $_POST['itemId']) && confirm_url_id(2, "updateStatus")) {
			// set the branch id to use
			$branchData = (Object) array_map("xss_clean", $_POST);

			// confirm the user permission to perform this action
			if($accessObject->hasAccess('update', 'branches')) {
				// get the branch status using the id parsed
				$query = $pos->prepare("SELECT status FROM branches WHERE id = ? && deleted = ? && clientId = ?");
				// execute and fetch the record
				if ($query->execute([$branchData->itemId, '0', $posClass->clientId])) {
					// get the data
					$data = $query->fetch(PDO::FETCH_OBJ);
					
					// branch status
					$state = ($data->status == 1) ? 0 : 1;
					
					// update the information
					$posClass->updateData(
						"branches",
						"status='{$state}'",
						"clientId='{$posClass->clientId}' AND id='{$branchData->itemId}'"
					);

					// Record user activity
					$posClass->userLogs('branches', $branchData->itemId, 'Updated the status of the branch and set it as '.(($state) ? "Active" : "Inactive"));

					$status = true;
					$message = "Branch status was Successfully updated";

				}
			} else {
				$message = "Sorry! You do not have the required permissions to perform this action.";
			}
		}

		// update the company settings
		elseif(isset($_POST['updateCompanyDetail']) && confirm_url_id(2, "updateCompanyDetail")) {
			// assign variables to the data that have been parsed
			$postData = (Object) array_map('xss_clean', $_POST);

			$status = 500;

			// start configuring the dataset
			if(!isset($postData->company_name) || (empty($postData->company_name))) {
				// print the error message
				$message = '<div class="alert alert-danger">Sorry! Company name cannot be empty.</div>';
			} elseif(!empty($postData->email) && !filter_var($postData->email, FILTER_VALIDATE_EMAIL)) {
				$message = '<div class="alert alert-danger">Please enter a valid email address</div>';
			} elseif(!empty($postData->primary_contact) && !preg_match('/^[0-9+]+$/', $postData->primary_contact)) {
				$message = '<div class="alert alert-danger">Please enter a valid primary contact</div>';
			} elseif(!empty($postData->secondary_contact) && !preg_match('/^[0-9+]+$/', $postData->secondary_contact)) {
				$message = '<div class="alert alert-danger">Please enter a valid secondary contact</div>';
			} else {
				
				$uploadDir = 'assets/images/company/';

				// check if the user has permissions to perform this action
				if($accessObject->hasAccess('update', 'settings')) {
					
					// File path config 
		            $fileName = basename($_FILES["company_logo"]["name"]); 
		            $targetFilePath = $uploadDir . $fileName; 
		            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

		            // Allow certain file formats 
		            $allowTypes = array('jpg', 'png', 'jpeg'); 
		            
		            // check if its a valid image
		            if(!empty($fileName) && in_array($fileType, $allowTypes)){
		            	
		               	// set a new filename
		               	$fileName = $uploadDir . random_string('alnum', 25).'.'.$fileType;

		                // Upload file to the server 
		                if(move_uploaded_file($_FILES["company_logo"]["tmp_name"], $fileName)){ 
		                    $uploadedFile = $fileName;
		                    $uploadStatus = 1; 
		                } else { 
		                    $uploadStatus = 0; 
		                    $message = '<div class="alert alert-danger">Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.</div>';
		                }

		            } else { 
		                $uploadStatus = 0;
		            }

		            // clock display
		            $display_clock = isset($postData->display_clock) ? (int)$postData->display_clock : 0;
		            // available colors
		            $themeColors = [
		            	"danger" => ["bg_colors"=>"bg-danger text-white no-border","bg_color_code"=>"#f5365c", "bg_color_light"=>"#f3adbb",
		            		"btn_outline" => "btn-outline-danger"
		            	],
		            	"indigo" => ["bg_colors"=>"bg-indigo text-white no-border","bg_color_code"=>"#5603ad", "bg_color_light"=>"#5e72e4",
		            		"btn_outline" => "btn-outline-primary"
		            	],
						"orange" => ["bg_colors"=>"bg-orange text-white no-border","bg_color_code"=>"#fb6340", "bg_color_light"=>"#e6987a",
							"btn_outline" => "btn-outline-warning"
						],
						"blue" => ["bg_colors"=>"bg-blue text-white no-border","bg_color_code"=>"#324cdd", "bg_color_light"=>"#97a5f1",
							"btn_outline" => "btn-outline-info"
						],
						"purple" => ["bg_colors"=>"bg-purple text-white no-border","bg_color_code"=>"#8965e0", "bg_color_light"=>"#97a5f1",
							"btn_outline" => "btn-outline-primary"
						],
						"green" => ["bg_colors"=>"bg-green text-white no-border","bg_color_code"=>"#24a46d", "bg_color_light"=>"#2dce89",
							"btn_outline" => "btn-outline-success"
						],
						"teal" => ["bg_colors"=>"bg-teal text-white no-border","bg_color_code"=>"#0da5c0", "bg_color_light"=>"#11cdef",
							"btn_outline" => "btn-outline-secondary"
						]
					];
		            
		            // theme color
		            $theme_color = (in_array($postData->theme_color, array_keys($themeColors))) ? $themeColors[$postData->theme_color] : $themeColors['purple'];

		            // update user data
					$response = $posClass->updateData(
						"settings",
						"client_name='{$postData->company_name}', client_email='{$postData->email}', client_website='{$postData->website}', primary_contact='{$postData->primary_contact}', secondary_contact='{$postData->secondary_contact}', address_1='{$postData->address}', display_clock='{$display_clock}', theme_color_code='{$postData->theme_color}', theme_color='".json_encode($theme_color)."'
						",
						"clientId='{$posClass->clientId}'"
					);

					// Record user activity
					$posClass->userLogs('settings', $session->clientId, 'Updated the general settings tab of the Company.');

					// continue
		            $status = 200;

		            $message = "Settings updated";

					// update the client logo
					if($uploadStatus == 1) {
						$posClass->updateData(
							"settings",
							"client_logo='{$uploadedFile}'",
							"clientId='{$posClass->clientId}'"
						);

						$status = 201;
						$message = $config->base_url($uploadedFile);
					}

				} else {
					$message = '<div class="alert alert-danger">Sorry! You do not have the required permissions to perform this action.</div>';
				}

			}

		}

		// update the sales section
		elseif(isset($_POST["updateSalesDetails"], $_POST["receipt_message"]) && confirm_url_id(2, "updateSalesDetails")) {
			// assign variables to the data that have been parsed
			$postData = (Object) $_POST;

			// preset the state
			$status = 500;
			$uploadStatus = 0;
			$workingDays = null;

			// working days processing
			if(isset($postData->opening_days) && !empty($postData->opening_days)) {
				$workingDays = implode(",", $postData->opening_days);
			}
			$print_receipt = isset($postData->print_receipt) ? xss_clean($postData->print_receipt) : null;
			// update user data
			$response = $posClass->updateData(
				"settings",
				"print_receipt='{$print_receipt}',
				expiry_notification_days='".xss_clean($postData->exp_notifi_days)."', 
				allow_product_return='".xss_clean($postData->allow_product_return)."',
				fiscal_year_start='".xss_clean($postData->fiscal_year_start)."',
				shop_opening_days='".xss_clean($workingDays)."',
				default_currency='".xss_clean($postData->default_currency)."',
				receipt_message='".xss_clean($postData->receipt_message)."',
				terms_and_conditions='".htmlentities($postData->terms_and_conditions)."'
				",
				"clientId='{$posClass->clientId}'"
			);

			// Record user activity
			$posClass->userLogs('settings', $session->clientId, 'Updated the sales details tab of the Company.');

			$uploadDir = 'assets/images/company/';

			// File path config 
	        $fileName = basename($_FILES["company_logo"]["name"]); 
	        $targetFilePath = $uploadDir . $fileName; 
	        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

	        // Allow certain file formats 
	        $allowTypes = array('jpg', 'png', 'jpeg'); 
	        
	        // check if its a valid image
	        if(!empty($fileName) && in_array($fileType, $allowTypes)){
	        	
	           	// set a new filename
	           	$fileName = $uploadDir . random_string('alnum', 25).'.'.$fileType;

	            // Upload file to the server 
	            if(move_uploaded_file($_FILES["company_logo"]["tmp_name"], $fileName)){ 
	                $uploadedFile = $fileName;
	                $uploadStatus = 1; 
	            }
	        }

	        $status = 201;
	        $message = "Settings updated";
	        
	        if($uploadStatus) {
		        $posClass->updateData(
					"settings",
					"manager_signature='{$uploadedFile}'",
					"clientId='{$posClass->clientId}'"
				);
				$message = $config->base_url($uploadedFile);
		    }		
		}

		// save reports information
		elseif(isset($_POST["saveReportsRecord"]) && confirm_url_id(2, "saveReportsRecord")) {
			// assign variables to the data that have been parsed
			$postData = (Object) $_POST;

			// saving the information
			if(isset($postData->attendantPerformance)) {
				$posClass->updateData(
					"settings",
					"reports_sales_attendant='".xss_clean($postData->attendantPerformance)."'",
					"clientId='{$posClass->clientId}'"
				);
			}
		}

		// save the reports data
		elseif(isset($_POST["updateReportDetails"]) && confirm_url_id(2, "updateReportsDetails")) {
			// assign variables to the data that have been parsed
			$postData = (Object) $_POST;

			if(isset($postData->squareFeetArea)) {

				// initializing
				$squareArea = $postData->squareFeetArea;
				$totalExpenses = 0;
				$totalSquareFoot = 0;

				// loop through the list of records parsed
				foreach ($squareArea as $key => $value) {
					// sum up the total expenses
					$totalExpenses += ($postData->recurringExpense[$key] + $postData->fixedExpense[$key]);
					$totalSquareFoot += $value;

					// update the branch information
					$posClass->updateData(
						"branches",
						"square_feet_area='".xss_clean($value)."', 
						recurring_expenses='".$postData->recurringExpense[$key]."',
						fixed_expenses='".$postData->fixedExpense[$key]."'",
						"clientId='{$posClass->clientId}' AND id='{$key}'"
					);

				}

				$posClass->updateData(
					"settings",
					"total_expenditure='".xss_clean($totalExpenses)."',
					space_per_square_foot='".xss_clean($totalSquareFoot)."'",
					"clientId='{$posClass->clientId}'"
				);

				// Record user activity
				$posClass->userLogs('settings', $session->clientId, 'Updated the reports details tab of the Company.');

				$status = 201;
			}
		}

		// load payment options of this client
		elseif(isset($_POST['loadPaymentOptions']) && confirm_url_id(2, "loadPaymentOptions")) {
			// fetch the payment options of this client
			$paymentOptions = $clientData->payment_options;
			$message = [];
			// confirm that the record is not empty
			if(!empty($paymentOptions)) {

				// explode the content with the comma
				$opts = explode(',', $paymentOptions);
				
				// using the foreach loop to fetch the records
				foreach ($opts as $eachOption) {
					// append to the list of payment options
					$message[] = $eachOption;
				}

				$status = 200;
			}
		}

		// update the payment options of the client
		elseif(isset($_POST['updatePaymentOptions'], $_POST['Option'], $_POST['Value']) && confirm_url_id(2, 'updatePaymentOptions')) {
			
			// check if the user has permissions to perform this action
			if($accessObject->hasAccess('update', 'settings')) {
				
				// assign variables to the data that have been parsed
				$branchData = (Object) $_POST;

				// fetch the payment options of this client
				$paymentOptions = $clientData->payment_options;
				$branchData->Value = $branchData->Value;

				// confirm that the record is not empty
				if(!empty($paymentOptions)) {

					// begin the options
					$options = [];

					// explode the content with the comma
					$opts = explode(',', $paymentOptions);
					
					// using the foreach loop to fetch the records
					foreach ($opts as $eachOption) {
						// append to the list of payment options
						$options[] = $eachOption;
					}

					// if the module parsed is in the array and the current value is unchecked
					if(in_array($branchData->Option, $options) && ($branchData->Value == 'unchecked')) {
						// search item in the array
						if (($key = array_search($branchData->Option, $options)) !== false) {
						    unset($options[$key]);
						}
					} elseif(!in_array($branchData->Option, $options) && ($branchData->Value == 'checked')) {
						// if not then push the new module into the list
						array_push($options, $branchData->Option);
					}

				} else {
					// if it is empty then start a new module list
					$options = [$branchData->Option];
				}

				// update the information
				$posClass->updateData(
					"settings",
					"payment_options='".implode(",", $options)."'",
					"clientId='{$posClass->clientId}'"
				);

				// Record user activity
				$posClass->userLogs('settings', $session->clientId, 'Updated the payment options of the Company.');

				$message = $options;

			}

		}

		$response = [
			"message"	=> $message,
			"status"	=> $status
		];
	}

	//: inventory management
	elseif(confirm_url_id(1, "inventoryManagement")) {

		//: initializing
		$message = "";
		$status = false;
		
		// create a new products object
		$productsClass = load_class("Products", "controllers");

		$baseUrl = $config->base_url();

		//: process inventories
		if (isset($_POST['request'])) {

			if (isset($_POST['branchID'], $_POST['location'], $_POST['request']) && confirm_url_id(2, "getAllProducts")) {

				$branchID = (!empty($_POST['branchID'])) ? xss_clean($_POST['branchID']) : null;

				$allProducts = $productsClass->all(false, $branchID);
				$session->set_userdata("invSelectedCard", $branchID);

				if (!empty($allProducts)) {
					$i = 0;
					
					$message = [];

					foreach ($allProducts as $product) {
						$i++;
						$price = $hrClass->toDecimal($product->product_price, 2, ',');

						// Check Indicator
						$calcA = (0.5 * $product->quantity) + $product->quantity;
						$calcB = (0.5 * $product->quantity);
						$indicator = ($calcA > $product->threshold) ? "success" : 
						(
							($calcB <= $product->threshold) ? "danger" : 
							(
								($calcA > $product->threshold && $calcB < $product->threshold) ? "warning" : null
							)
						);

						$action = "<div class='text-center'>
								<a class=\"btn btn-outline-info btn-sm\" href=\"{$baseUrl}products/{$product->pid}\">
	                                <i class=\"fa fa-edit\"></i></a>";
			            // ensure the user has the needed permissions
			            if($accessObject->hasAccess('inventory_branches', 'products')) {
			            	// show button if the quantity is more than 1
				            if($product->quantity > 0) {
				            	$action .= "<a class=\"btn btn-sm btn-outline-success transfer-product\" href=\"javascript:void(0)\" onclick=\"return transferProduct('{$product->product_id}');\"><i class=\"fa fa-share\"></i></a>";
				            }
				        }
			            $action .= "</div>";

						$message[] = [
							'row_id' => $i,
							'product_name' => "<img src=\"".(($product->source == 'Vend') ? $product->product_image : ((!empty($product->product_image) && file_exists("assets/images/products/".$product->product_image) ? $config->base_url("assets/images/products/{$product->product_image}") : (empty($product->product_image) ? $config->base_url("assets/images/products/default.png") : $config->base_url($product->product_image)))))."\" alt=\"\" height=\"52\">
			                    <p class=\"d-inline-block align-middle mb-0\">
			                        <a href=\"{$baseUrl}products/{$product->pid}\" class=\"d-inline-block align-middle mb-0 product-name\">
			                        {$product->product_title}</a> 
			                        <br>
			                        ".(($product->source == 'Vend') ? "<span class='badge badge-success'> {$product->source}</span>" : null)."
			                    </p>",
			                'category' => $product->category,
			                'price' => "{$clientData->default_currency} {$price}",
			                'quantity' => "<div class='text-center'>{$product->quantity}</div>",
			                'indicator' => "<div class='text-center'><span style=\"font-size: 9px;\" class=\"fa fa-circle text-{$indicator}\"></span></div>",
			                'action' => $action
						];
			        }
			        $status = true;
			    }
			
			} else if (isset($_POST['transferProductQuantity'], $_POST['branchId']) && confirm_url_id(2, "submitTransferProduct")) {

				// if the session parsed does not match the id sent 
				// disallow the user to continue with the process
				if($session->currentBranchId != ($_POST["transferFrom"])) {
					echo json_encode([
						"message" => "Sorry! Please refresh the page to try again.",
						"status" => "error",
					]);
					exit;
				}

				// confirm that the product quantity has been set and a branch id has been parsed
				if (!empty($_POST['transferProductQuantity']) && !empty($_POST['branchId']) && ($_POST['branchId'] != "null")) {

					$postData = (Object) array_map("xss_clean", $_POST);

					$product = $productsClass->getProduct($postData->transferProductID, "", $postData->transferFrom);

					$sellPrice = $product->product_price;

					// check if the product quantity parsed is more than the available
					if($postData->transferProductQuantity > $product->quantity) {
						// print error message
						$message = "Sorry! You have entered a quantity higher than what's available";
					} else {

						// Check If Product Exists In Receiving Branch
						$check = $productsClass->getCountdata(
							"products", 
							"product_id = '{$postData->transferProductID}' && branchId = '{$postData->branchId}'"
						);

						if ($check == 0) {
							// Get Product Details
							$product->branchId = $postData->branchId;
							$product->transferQuantity = $postData->transferProductQuantity;
							$product->userId = $session->userId;
							// generate a new stock auto id
							$stockAutoId = random_string('alnum', 12);
							
							// set the stock id 
							$product->stockAutoId = $stockAutoId;

							// Add Quantity To Product Table Stock
							$query = $productsClass->addStockToBranch($product);
						} else {
							$query = $productsClass->updateBranchProductStock($postData, 'product_id');
						}

						if ($query == true) {
							// Reduce Sending Shop Product Stock
							$hrClass->updateData(
								"products", 
								"quantity = (quantity - $postData->transferProductQuantity)",
								"product_id = '{$postData->transferProductID}' && branchId = '{$postData->transferFrom}'"
							);

							// Add Product Transfer To Inventory
							$postData->clientId = $posClass->clientId;
							$postData->userId = $session->userId;
							$postData->selling_price = $sellPrice;
							$productsClass->addToInventory($postData);

							$status = true;
						} else {
							$message = "Transfer Failed";
						}

					}

				} else {
					$message = "Please Check All Fields Are Required";
				}

			}

			//: bulk transfer of products
			else if (isset($_POST['bulkTransferProducts'], $_POST['productIds'], $_POST["transferFrom"]) && confirm_url_id(2, 'bulkTransferProducts')) {

				// if the session parsed does not match the id sent 
				// disallow the user to continue with the process
				if($session->currentBranchId != ($_POST["transferFrom"])) {
					echo json_encode([
						"message" => "Sorry! Please refresh the page to try again.",
						"status" => "error",
					]);
					exit;
				}

				// ensure all required information has been parsed
				if (!empty($_POST['productIds']) && !empty($_POST['branchId'])) {

					// continue processing the request
					$products = explode(",", $_POST['productIds']);

					$postData = (Object) array_map("xss_clean", $_POST);
					
					$error_state = false;

					foreach ($products as $productToTransfer) {

						$currItem = explode("=", $productToTransfer);
						$productID = (int) xss_clean($currItem[0]);
						$productQty= (int) xss_clean($currItem[1]);
						$postData->transferProductQuantity = $productQty;

						// confirm that at least all products has the value 1
						if($productQty == 0) {
							$error_state = true;
						}
					}

					if($error_state) {
						
						$message = "Ensure no item has the quantity of 0";
						
						echo json_encode([
							"message" => $message,
							"status" => $status
						]);
						exit;
					}

					else {

						// generate a new stock auto id
						$stockAutoId = random_string('alnum', 12);

						// loop through the list of all products to transfer
						foreach ($products as $productToTransfer) {

							$currItem = explode("=", $productToTransfer);
							$productID = (int) xss_clean($currItem[0]);
							$productQty= (int) xss_clean($currItem[1]);
							$postData->transferProductQuantity = $productQty;

							$productData = $hrClass->getAllRows("products","product_price, cost_price, product_id", "id='{$productID}'")[0];

							$sellPrice = $productData->product_price;
							$nproductID = $productData->product_id;
							$costPrice = $productData->cost_price;

							// Check If Product Exists
							$check = $productsClass->getCountdata(
								"products", 
								"product_id = '{$nproductID}' && branchId = '{$postData->branchId}'"
							);

							if ($check == 0) {
								// Get Product Details
								$product = $productsClass->getProduct($nproductID, "", $postData->transferFrom);

								$product->branchId = $postData->branchId;
								$product->transferQuantity = $productQty;
								$product->userId = $session->userId;
								$product->stockAutoId = $stockAutoId;

								// Add Quantity To Product Table Stock
								$query = $productsClass->addStockToBranch($product);
							} else {
								$postData->transferProductID = $nproductID;
								$query = $productsClass->updateBranchProductStock($postData);
							}

							if ($query == true) {
								// Reduce Branch Transferring Product Stock
								$hrClass->updateData(
									"products", 
									"quantity = (quantity - $productQty)",
									"id = '{$productID}' && branchId = '{$postData->transferFrom}'"
								);

								// Add Product Transfer To Inventory
								$postData->clientId = $posClass->clientId;
								$postData->userId = $session->userId;
								$postData->selling_price = $sellPrice;
								$productsClass->addToInventory($postData);

								$status = true;
								$message = "Products Successfully Transfered";
							} else {
								$message = "Transfer Failed";
							}
						}

					}
				}

			}

			echo json_encode([
				"message" => $message,
				"status" => $status
			]);
			exit();

		}

		//: update the stock of the existing products
		elseif(isset($_POST["updateWareHouseStock"], $_POST["stockQuantities"]) && confirm_url_id(2, 'updateWareHouseStock')) {
			
			//: begin the processing
			$stockData = $_POST["stockQuantities"];
			$branchId = $session->currentBranchId;
			$autoId = random_string('alnum', 25);
			$updateQuery = '';

			$insertQuery = 'INSERT INTO products_stocks (clientId, branchId, auto_id, product_id, cost_price, retail_price, previous_quantity, quantity, total_quantity, threshold, recorded_by) VALUES ';

			//: explode the list
			$stockLevels = explode(",", $stockData);

			if(empty($stockLevels)) {
				echo json_encode([
					"message" => "Please at least one item to continue",
					"status" => $status
				]);
				exit;
			}

			//: loop through the list
			foreach($stockLevels as $eachStock) {

				//: explode each stock
				$eachExplode = explode("|", $eachStock);

				//: assign variables
				if(isset($eachExplode[2])) {

					//: assign variables
					$productId = (int) $eachExplode[0];
					$costPrice = xss_clean($eachExplode[1]);
					$retailPrice = xss_clean($eachExplode[2]);
					$quantity = (isset($eachExplode[3])) ? (int) $eachExplode[3] : 0;
					$threshold = (isset($eachExplode[4])) ? (int) $eachExplode[4] : 0;

					// each product information
					$eachProduct = $hrClass->getAllRows("products", "quantity", "id='{$productId}'")[0];
					$newQuantity = $eachProduct->quantity+$quantity;

					//: form the sql query to insert
					$updateQuery .= "UPDATE products SET quantity = (quantity+$quantity), threshold='{$threshold}', cost_price = '{$costPrice}', product_price='{$retailPrice}' WHERE id = '{$productId}' AND branchId='{$session->currentBranchId}' AND clientId='{$posClass->clientId}';";

					$insertQuery .= "('{$posClass->clientId}', '{$session->currentBranchId}', '{$autoId}', '{$productId}', '{$costPrice}', '{$retailPrice}', '{$eachProduct->quantity}', '{$quantity}', '{$newQuantity}', '{$threshold}', '{$session->userId}'),";
				}

			}

			$insertQuery = substr($insertQuery, 0, -1).";";
			
			// Get Product Details
			$queryString = $productsClass->updateWareHouseStock($insertQuery, $updateQuery);
			
			//: if the result is true
			if($queryString) {
				$status = "success";
				$message = "Products stock was successfully updated.";
			}

			echo json_encode([
				"message" => $message,
				"status" => $status
			]);
			exit;

		} else {
			
			$res = (Object) [
				'status' => 'error', 
				'message' => 'Error Processing Request',
				'branchId' => $session->branchId
			];
			$imgDir = "assets/images/products";

			if(isset($_POST['addProduct']) && confirm_url_id(2, 'addProduct')){

				if(($_POST["product_type"] == "New") && (empty($_POST['category']) || ($_POST['category'] == "null"))) {
					$res->message = "Please select product's category";
				}
				elseif(empty($_POST['title'])) {
					$res->message = "Please enter product's title";
				}
				elseif(empty($_POST['cost'])) {
					$res->message = "Please enter product's cost price";
				}
				elseif(empty($_POST['price'])) {
					$res->message = "Please enter product's retail price";
				}
				elseif(empty($_POST['quantity']) || ($_POST['quantity'] < 1)) {
					$res->message = "Please enter product's quantity";
				}
				else{
					$postData = (Object) array_map("xss_clean", $_POST);
					$uploadDir = 'assets/images/products/';

					// File path config 
		            $fileName = basename($_FILES["product_image"]["name"]); 
		            $targetFilePath = $uploadDir . $fileName; 
		            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

		            $fileName = (empty($fileName)) ? "default.png" : $fileName;

		            // Allow certain file formats 
		            $allowTypes = array('jpg', 'png', 'jpeg'); 
		            
		            // check if its a valid image
		            if(!empty($fileName) && in_array($fileType, $allowTypes)){
		            	
		               	// set a new filename
		               	$fileName = random_string('alnum', 25).'.'.$fileType;

		                // Upload file to the server 
		                if(move_uploaded_file($_FILES["product_image"]["tmp_name"], $uploadDir .$fileName)){ 
		                    $uploadedFile = $fileName;
		                    $uploadStatus = 1; 
		                } else { 
		                    $uploadStatus = 0; 
		                    $res->message = '<div class="alert alert-danger">Sorry, JPG, JPEG, & PNG files are accepted.</div>';
		                }

		            } else { 
		                $uploadStatus = 0;
		            }
		            $postData->threshold = (isset($postData->threshold)) ? (int) $postData->threshold : 10;
		            $postData->image = $uploadDir .$fileName;

		            // auto generated id for stock management
		            $postData->autoId = random_string('alnum', 12);

					if($productsClass->addProduct($postData)){
						$res->status = "success";
						$res->message = "Product Successfully Added";
						$res->branchId = $session->currentBranchId;
					}
				}
			}


			elseif(isset($_POST['editProduct']) && confirm_url_id(2, 'editProduct')){
				// validate the user data
				if((empty($_POST['category']) || ($_POST['category'] == "null"))) {
					$res->message = "Please select product's category";
				}
				elseif(empty($_POST['title'])) {
					$res->message = "Please enter product's title";
				}
				elseif(empty($_POST['cost'])) {
					$res->message = "Please enter product's cost price";
				}
				elseif(empty($_POST['price'])) {
					$res->message = "Please enter product's retail price";
				}
				else {
					$postData = (Object) array_map("xss_clean", $_POST);
					$uploadDir = 'assets/images/products/';

					// File path config 
		            $fileName = basename($_FILES["product_image"]["name"]); 
		            $targetFilePath = $uploadDir . $fileName; 
		            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

		            $fileName = (empty($fileName)) ? "default.png" : $fileName;

		            // Allow certain file formats 
		            $allowTypes = array('jpg', 'png', 'jpeg'); 

		            // check if its a valid image
		            if(!empty($fileName) && in_array($fileType, $allowTypes)){
		            	
		               	// set a new filename
		               	$fileName = random_string('alnum', 25).'.'.$fileType;

		                // Upload file to the server 
		                if(move_uploaded_file($_FILES["product_image"]["tmp_name"], $uploadDir .$fileName)){ 
		                    $uploadedFile = $uploadDir .$fileName;
		                } else { 
		                    $uploadStatus = 0; 
		                    $res->message = '<div class="alert alert-danger">Sorry, JPG, JPEG, & PNG files are accepted.</div>';
		                }
		                
		            } else { 
		                $uploadedFile = null;
		            }

		            $postData->threshold = (isset($postData->threshold)) ? (int) $postData->threshold : 10;
		            $postData->image = $uploadedFile;

		            if($productsClass->updateProduct($postData)){
						$res->status = "success";
						$res->message = [
							"result" => "Product Successfully Updated",
							"productId" => $postData->productId
						];
					}
				}
			}

			elseif(isset($_POST['removeProduct'])){
					$productId = xss_clean($_POST['productId']);
					if($productsClass->removeProduct($productId)){
						$res->status = "success";
						$res->message = "Product has been removed";
					}
			}

			elseif(isset($_POST['getProduct'], $_POST['transferFrom'])){
				$productId = xss_clean($_POST['productId']);
				$product = $productsClass->getProduct($productId);
				$categories = $productsClass->getCategories();

				if(!empty($product) && !empty($categories)){
					$res->status = "success";
					$res->categories = $categories;
					$res->product = $product;
					$res->branchId = $_POST['transferFrom'];
				}
			}

			elseif(isset($_POST['getWarehouseProduct'], $_POST['transferFrom'])){
				$productId = xss_clean($_POST['productId']);
				$transferFrom = xss_clean($_POST['transferFrom']);

				$product = $productsClass->getProduct($productId, null, $transferFrom);

				$categories = $productsClass->getCategories();

				if(!empty($product) && !empty($categories)){
					$res->status = "success";
					$res->message = "Displaying product content";
					$res->categories = $categories;
					$res->product = $product;			
				}
			}

			echo json_encode($res);
			exit;
		}
	}

	//: user management
	elseif(confirm_url_id(1, "userManagement")) {

		//: initializing
		$message = "Error Processing Request";
		$status = false;

		//: fetch the list of all users
		if (isset($_POST['fetchUsersLists']) && confirm_url_id(2, 'fetchUsersLists')) {

			$condition = "";
			$message = [];

			$query = $pos->prepare("
				SELECT a.*, b.access_name, c.branch_name 
				  FROM users a 
			INNER JOIN access_levels b
					ON a.access_level = b.id
			INNER JOIN branches c
					ON c.id = a.branchId
				 WHERE a.clientId = ? && a.status = ? {$condition}");

			if ($query->execute([$session->clientId, '1'])) {
				$i = 0;

				while ($data = $query->fetch(PDO::FETCH_OBJ)) {
					$i++;

					$date = date('jS F, Y', strtotime($data->created_on));

					$action = '<div width="100%" align="center">';

					if($accessObject->hasAccess('update', 'users')) {
						if(in_array($data->access_level, [1, 2]) && (in_array($session->accessLevel, [1, 2]))) {
								$action .= "<button class=\"btn btn-sm btn-outline-success edit-user\" data-user-id=\"{$data->user_id}\">
								<i class=\"fa fa-edit\"></i>
							</button> ";
						} elseif(!in_array($data->access_level, [1, 2])) {
							$action .= "<button class=\"btn btn-sm btn-outline-success edit-user\" data-user-id=\"{$data->user_id}\">
								<i class=\"fa fa-edit\"></i>
							</button> ";
						}
					}

					if($accessObject->hasAccess('accesslevel', 'users')) {
						$action .= "<button class=\"btn btn-sm btn-outline-primary edit-access-level\" data-user-id=\"{$data->user_id}\">
								<i class=\"fa fa-sitemap\"></i>
							</button> ";
					}

					if($accessObject->hasAccess('delete', 'users')) {
						if(in_array($data->access_level, [1, 2]) && (in_array($session->accessLevel, [1, 2]))) {
							$action .= "<button class=\"btn btn-sm btn-outline-danger delete-user\" data-user-id=\"{$data->user_id}\">
								<i class=\"fa fa-trash\"></i>
							</button> ";
						} elseif(!in_array($data->access_level, [1, 2])) {
							$action .= "<button class=\"btn btn-sm btn-outline-danger delete-user\" data-user-id=\"{$data->user_id}\">
								<i class=\"fa fa-trash\"></i>
							</button> ";
						}
					}

					$action .= "</div>";

					$message[] = [
						'user_id' => $data->user_id,
						'row_id' => $i,
						'fullname' => $data->name,
						'branch_name' => $data->branch_name,
						'access_level' => $data->access_name,
						'access_level_id' => $data->access_level,
						'gender' => $data->gender,
						'branchId' => $data->branchId,
						'contact' => $data->phone,
						'email' => $data->email,
						'registered_date' => $date,
						'action' => $action,
						'deleted' => 0
					];

				}
				$status = true;
			}

		}

		//: get the details of a single user
		elseif(isset($_POST['getUserDetails'], $_POST['userId']) && confirm_url_id(2, "getUserDetails")) {

			$userId = xss_clean($_POST['userId']);

			// Check If User Exists
			$query = $pos->prepare(
				"SELECT u.*, al.access_name FROM users u INNER JOIN access_levels al ON u.access_level = al.id WHERE u.user_id = ? && u.status = ?"
			);

			if ($query->execute([$userId, '1'])) {

				$data = $query->fetch(PDO::FETCH_OBJ);

				$message = [
					"user_id"	=> $data->user_id,
					"fullname"	=> $data->name,
					"access_level_id" => $data->access_level,
					"acl_name"	=> $data->access_name,
					"gender"	=> $data->gender,
					"contact"	=> $data->phone,
					"email"		=> $data->email,
					"country"	=> $data->country_id,
					"branchId" => $data->branchId,
					'daily_target' => $data->daily_target,
					'monthly_target' => $data->monthly_target,
					'weekly_target' => $data->weekly_target,
				];
				$status = true;
			} else {
				$message = "Sorry! User Cannot Be Found.";
			}
			
		} 

		//: add or update user information
		elseif(isset($_POST['fullName'], $_POST['access_level']) && confirm_url_id(2, "addUserRecord")) {

			// Check If Fields Are Not Empty
			if (!empty($_POST['fullName']) && !empty($_POST['access_level']) && !empty($_POST['gender'])  && !empty($_POST['phone']) && !empty($_POST['email'])) {

				$userData = (Object) array_map("xss_clean", $_POST);

				if(!empty($userData->email) && !filter_var($userData->email, FILTER_VALIDATE_EMAIL)) {
					$message = "Please enter a valid email address";
				} elseif(!empty($userData->phone) && !preg_match('/^[0-9+]+$/', $userData->phone)) {
					$message = "Please enter a valid contact number";
				} else {

					if ($userData->record_type == "new-record") {

						// Check Email Exists
						$checkData = $posClass->getAllRows("users", "COUNT(*) AS proceed", "email='{$userData->email}' && status = '1'");

						if ($checkData != false && $checkData[0]->proceed == '0') {

							// Add Record To Database
							$getUserId   = random_string('alnum', mt_rand(20, 30));
							$getPassword = random_string('alnum', mt_rand(8, 10));
							$hashPassword= password_hash($getPassword, PASSWORD_DEFAULT);

							$response = $posClass->addData(
								"users" ,
								"clientId='{$session->clientId}', user_id='{$getUserId}', name='{$userData->fullName}', gender='{$userData->gender}', email='{$userData->email}', phone='{$userData->phone}', access_level='{$userData->access_level}', branchId='{$userData->branchId}', password='{$hashPassword}', daily_target='{$userData->daily_target}', weekly_target='{$userData->weekly_target}', monthly_target='{$userData->monthly_target}'
								"
							);

							if ($response == true) {

								// Record user activity
								$posClass->userLogs('users', $getUserId, 'Added a new user.');
								
								// Assign Roles To User
								$accessObject->assignUserRole($getUserId, $userData->access_level);

								// Show Success Message
								$message = "User Have Been Successfully Registered.";
								$status = true;
							} else {
								$message = "Sorry! User Records Failed To Save.";
							}
						} else {
							$message = "Sorry! Email Already Belongs To Another User.";
						}
					} else if ($userData->record_type == "update-record") {
						// CHeck If User ID Exists
						$checkData = $posClass->getAllRows("users", "COUNT(*) AS userTotal, access_level", "user_id='{$userData->userId}'");

						if ($checkData != false && $checkData[0]->userTotal == '1') {

							// update user data
							$response = $posClass->updateData(
								"users",
								"name='{$userData->fullName}', gender='{$userData->gender}', email='{$userData->email}', phone='{$userData->phone}', access_level='{$userData->access_level}', branchId='{$userData->branchId}', daily_target='{$userData->daily_target}', weekly_target='{$userData->weekly_target}', monthly_target='{$userData->monthly_target}'",
								"user_id='{$userData->userId}' && clientId='{$session->clientId}'"
							);

							if ($response == true) {

								// Record user activity
								$posClass->userLogs('users', $userData->userId, 'Update the user details.');

								// check if the user has the right permissions to perform this action
								if($accessObject->hasAccess('accesslevel', 'users')) {

									// Check If User ID Exists
									$userRole = $posClass->getAllRows("user_roles", "COUNT(*) AS userTotal, permissions", "user_id='{$userData->userId}'");

									// confirm if the user has no credentials
									if($userRole[0]->userTotal == 0) {
										// insert the permissions to this user
										$getPermissions = $accessObject->getPermissions($userData->access_level)[0]->default_permissions;
										// assign these permissions to the user
										$accessObject->assignUserRole($userData->userId, $userData->access_level);
									}

									// Check Access Level
									if ($userData->access_level != $checkData[0]->access_level) {

										$getPermissions = $accessObject->getPermissions($userData->access_level)[0]->default_permissions;

										$accessObject->assignUserRole($userData->userId, $userData->access_level, $getPermissions);
									}
								}

								$message = "User Details Have Been Successfully Updated.";
								$status = true;
							} else {
								$message = "Sorry! User Records Failed To Update.";
							}
						} else {
							$message = "Sorry! User Does Not Exist.";
						}
						// Update Record
					} else {
						$message = "Your Request Is Not Recognized";
					}
				}
			} else {
				$message = "Please Check All Required Fields.";
			}
		} 

		//: load the user access level details
		else if (isset($_POST['fetchAccessLevelPermissions']) && (isset($_POST['access_level'])) || (isset($_POST['user_id']) && $_POST['getUserAccessLevels']) && confirm_url_id(2, "permissionManagement")) {

			if (isset($_POST["getUserAccessLevels"]) ||(isset($_POST['access_level']) && !empty($_POST['access_level']) && $_POST['access_level'] != "null")) {

				$access_level = (isset($_POST['access_level'])) ? xss_clean($_POST['access_level']) : null;
				$access_user  = (isset($_POST['user_id'])) ? xss_clean($_POST['user_id']) : null;

				// Check If User Is Selected
				if (!empty($access_user) && $access_user != "null") {

					// Get User Permissions
					$query = $posClass->getAllRows("user_roles", "permissions", "user_id='{$access_user}'");

					if ($query != false) {
						$message = json_decode($query[0]->permissions);
						$status = true;
					} else {
						$message = "Sorry! No Permission Found For This User";
					}
				} else {

					$query = $accessObject->getPermissions($access_level);
					if ($query != false) {
						$message = json_decode($query[0]->default_permissions);
						$status = true;
					} else {
						$message = "Sorry! No Permission Found.";
					}

				}
			} else {
				$message = "Sorry! You Need To Select An Access Level";
			}

		}

		//: save user access level settings
		elseif(isset($_POST['saveAccessLevelSettings'], $_POST['aclSettings'], $_POST['acl'], $_POST['accessUser']) && confirm_url_id(2, "saveAccessLevelSettings")) {

			if (!empty($_POST['acl']) && $_POST['acl'] != "null" && !empty($_POST['aclSettings']) && $_POST['aclSettings'] != "null") {

				$access_level = xss_clean($_POST['acl']);
				$access_user  = xss_clean($_POST['accessUser']);
				$aclSettings  = $_POST['aclSettings'];

				// Prepare Settings
				$aclPermissions = array();
				$array_merged = array();

				foreach($aclSettings as $eachItem) {
					$expl = explode(",", $eachItem);

					$aclPermissions[$expl[0]][$expl[1]] =  xss_clean($expl[2]);

				}

				$permissions = json_encode(array("permissions" => $aclPermissions));

				if ($access_user != "" && $access_user != "null") {
					// Update Settings For User
					$checkData = $posClass->getAllRows("users", "COUNT(*) AS userTotal", "user_id='{$access_user}' && status = '1'");

					if ($checkData != false && $checkData[0]->userTotal == '1') {

						$query = $accessObject->assignUserRole($access_user, $access_level, $permissions);

						if ($query == true) {
							$message = "Access Level Updated Successfully!";
							$status = true;
						} else {
							$message = "Sorry! Access Level Update Failed.";
						}

					} else {
						// $message = "Sorry! User Does Not Exist.";
					}

				} else {
					// Update Settings For Access Level Group
					$checkData = $posClass->getAllRows("access_levels", "COUNT(*) AS aclTotal", "id='{$access_level}'");

					if ($checkData != false && $checkData[0]->aclTotal == '1') {

						$stmt = $pos->prepare(
			                "UPDATE access_levels SET default_permissions = '{$permissions}' WHERE id = '{$access_level}'"
			            );

			            if ($stmt->execute()) {
			                $message = "Access Level Updated Successfully";
			                $status  = true;
			            } else {
							$message = "Sorry! Access Level Update Failed.";
						}

					} else {
						$message = "Sorry! Access Level Does Not Exist.";
					}
				}
			} else {
				$message = "Sorry! You Need To Select An Access Level";
			}

		}

		//: save the user profile information
		elseif(isset($_POST["userId"], $_POST["email"], $_POST["phone"], $_POST["gender"], $_POST["fullName"]) && confirm_url_id(2, "quickUpdate")) {
			//: process the user information parsed
			$postData = (Object) array_map('xss_clean', $_POST);

			//: validate the user information
			if(!empty($postData->email) && !filter_var($postData->email, FILTER_VALIDATE_EMAIL)) {
					$message = "Please enter a valid email address";
			} elseif(!empty($postData->phone) && !preg_match('/^[0-9+]+$/', $postData->phone)) {
				$message = "Please enter a valid contact number";
			} elseif($postData->userId != $session->userId) {
				$message = "You are not permitted to update this account.";
			} else {
				//: update the user information
				$stmt = $pos->prepare("UPDATE users SET name=?, phone=?, email=?, gender=? WHERE user_id =?");
				$stmt->execute([
					$postData->fullName, $postData->phone, 
					$postData->email, $postData->gender, $postData->userId
				]);

				//: update the password column of the user

				// print success message
				$status = true;
				$message = "Profile was successfully updated.";

			}

		}
		//: set the response to return
		$response = [
			"message"	=> $message,
			"status"	=> $status
		];
	}

	//: customers management
	elseif (confirm_url_id(1, "customerManagement")) {
		
		//: begin the queries
		if(isset($_POST["listCustomers"]) && confirm_url_id(2, "listCustomers")) {
			//: query for the list of all customers
			$customersClass = load_class("Customers", "controllers");
			$customersList = $customersClass->fetch("a.id, a.title, a.customer_id, a.firstname, a.lastname, CONCAT(a.firstname, ' ', a.lastname) AS fullname, a.preferred_payment_type, a.date_log, a.clientId, a.branchId, a.phone_1, a.phone_2, a.email, a.residence, b.branch_name", "AND a.customer_id != 'WalkIn' {$branchAccess}",
				"LEFT JOIN branches b ON b.id = a.branchId"
			);

			// fetch the data
			$customers = [];
			$row_id = 0;
			// loop through the list
			foreach($customersList as $eachCustomer) {
				$row_id++;
				// set the action button
				$eachCustomer->row_id = $row_id;
				$eachCustomer->action = "<div align=\"center\">";

				if($accessObject->hasAccess('update', 'customers')) {
		        	$eachCustomer->action .= "<a class=\"btn btn-sm edit-customer btn-outline-success\" title=\"Update Customer Details\" data-value=\"{$eachCustomer->customer_id}\" href=\"javascript:void(0)\"><i class=\"fa fa-edit\"></i> </a>";
		        } 

		        $eachCustomer->action .= "&nbsp;<a href=\"{$config->base_url('reports/'.$eachCustomer->customer_id)}\" title=\"Click to list customer orders history\" data-value=\"{$eachCustomer->customer_id}\" class=\"customer-orders btn btn-outline-primary btn-sm\" data-name=\"{$eachCustomer->fullname}\"><i class=\"fa fa-chart-bar\"></i></a>";

		        if($accessObject->hasAccess('delete', 'customers')) {
                    $eachCustomer->action .= "&nbsp;<a href=\"javascript:void(0);\" class=\"btn btn-sm btn-outline-danger delete-item\" data-msg=\"Are you sure you want to delete this Customer?\" data-request=\"customer\" data-url=\"{$config->base_url('aj/customerManagement/deleteCustomer')}\" data-id=\"{$eachCustomer->id}\"><i class=\"fa fa-trash\"></i></a>";
                }
		        $eachCustomer->action .= "</div>";

		        $eachCustomer->fullname = "<a data-id=\"{$eachCustomer->customer_id}\" data-info='".json_encode($eachCustomer)."'>{$eachCustomer->title} {$eachCustomer->fullname}<br><span style='background-color: #9ba7ca;' class='badge badge-default'>{$eachCustomer->branch_name}</span></a>";
				//append to the list
				$customers[] = $eachCustomer;
			}

			$response = [
				"status" => 200,
				"result" => $customers
			];
		}

		//: update the customer information
		elseif(isset($_POST["nc_firstname"], $_POST["customer_id"]) && confirm_url_id(2, "updateCustomerDetails")) {
			//: update the information
			$postData = (Object) array_map('xss_clean', $_POST);

			// create a new object
			$customersObj = load_class("Customers", "controllers");

			// validate the user data
			if(empty(trim($postData->nc_firstname))){
				$response->message = "Please enter customer's name";
			} elseif(!empty($postData->nc_email) && !filter_var($postData->nc_email, FILTER_VALIDATE_EMAIL)) {
				$response->message = "Please enter a valid email address";
			} elseif(!empty($postData->nc_contact) && !preg_match("/^[0-9+]+$/", $postData->nc_contact)) {
				$response->message = "Please enter a valid contact number";
			}
			else{
				// update the customer information
                if($postData->request == "update-record") {
                    $updateCustomer = $customersObj->quickUpdate($postData);
                    if(!empty($updateCustomer)){
                        $response->status = 200;
                        $response->message = "Customer data successfully updated";
                    }
                } else {
                    // add the customer information
                    $addCustomer = $customersObj->quickAdd($postData);
                    if(!empty($addCustomer)){
                        $response->status = 200;
                        $response->message = "Customer data successfully inserted";
                    }
                }
			}
		}

		elseif(isset($_POST["itemId"], $_POST["itemToDelete"]) && confirm_url_id(2, 'deleteCustomer')) {
			$postData = (Object) array_map("xss_clean", $_POST);

			if(empty($postData->itemId)) {
				$response->message = "Error processing request";
			} else {
				$query = $pos->prepare("UPDATE customers SET status='0' WHERE id='{$postData->itemId}' AND clientId='{$session->clientId}'");
				if($query->execute()) {
					$posClass->userLogs('customer', $postData->itemId, 'Deleted the customer details.');
					$response->status = true;
					$response->href = $config->base_url('customers');
					$response->message = "Customer successfully deleted";
				}
			}
		}

	}

	//: delete data
	elseif(isset($_POST['itemToDelete'], $_POST['itemId']) and confirm_url_id(1, 'deleteData')) {
		// confirm if an id was parsed
		$itemId = (isset($_POST['itemId'])) ? xss_clean($_POST["itemId"]) : null;
		
		$process = $pos->prepare("UPDATE requests SET deleted = ? WHERE request_id = ?");
		$process->execute([1, $itemId]);

		if($process) {
			$status = true;
			$message = 'Record was successfully deleted.';
		}

		$response = array(
			"status" => $status,
			"message" => $message,
			"request" => 'deleteItem',
			"itemId" => $itemId,
			"thisRequest" => xss_clean($_POST['itemToDelete']),
			"tableName" => xss_clean($_POST['itemToDelete']).'sList'
		);
		
	}

	//: Products category management
	elseif(confirm_url_id(1, "categoryManagement")) {

		//: list categories
		if(isset($_POST["listProductCategories"]) && confirm_url_id(2, "listProductCategories")) {
			//: run the query
			$i = 0;
            # list categories
            $categoryList = $posClass->getAllRows("products_categories a", "a.*, (SELECT COUNT(*) FROM products b WHERE a.category_id = b.category_id) AS products_count", "a.clientId='{$session->clientId}'");

            $categories = [];
            // loop through the branches list
            foreach($categoryList as $eachCategory) {
            	$i++;
            	
            	$eachCategory->row = $i;
            	$eachCategory->action = "";

            	if($accessObject->hasAccess('category_edit', 'products')) {

            		$eachCategory->action .= "<a data-content='".json_encode($eachCategory)."' href=\"javascript:void(0);\" class=\"btn btn-sm btn-outline-primary edit-category\" data-id=\"{$eachCategory->id}\"><i class=\"fa fa-edit\"></i></a>";
            	}
            	
            	if($accessObject->hasAccess('category_delete', 'products')) {
            		$eachCategory->action .= "<a href=\"javascript:void(0);\" class=\"btn btn-sm btn-outline-danger delete-item\" data-msg=\"Are you sure you want to delete this Product Category?\" data-request=\"category\" data-url=\"{$config->base_url('aj/categoryManagement/deleteCategory')}\" data-id=\"{$eachCategory->id}\"><i class=\"fa fa-trash\"></i></a>";
            	}

            	if(empty($eachCategory->action)) {
            		$eachCategory->action = "---";
            	}

                $categories[] = $eachCategory;
           	}

           	$response = [
           		'status' => 200,
           		'result' => $categories
           	];
		}
		elseif(isset($_POST["name"], $_POST["dataset"]) && confirm_url_id(2, 'saveCategory')) {
			$postData = (Object) array_map("xss_clean", $_POST);

			//: initializing
			$response = (Object) [
				'status' => 'error', 
				'message' => 'Error Processing Request',
				'branchId' => $session->branchId
			];

			if(empty($postData->name)) {
				$response->message = "Category name cannot be empty";
			} else {
				if($postData->dataset == "update") {
					$query = $pos->prepare("UPDATE products_categories SET category = '{$postData->name}' WHERE id='{$postData->id}' AND clientId='{$session->clientId}'");

					if($query->execute()) {
						$response->status = 200;
						$response->message = "Product category was updated";
						$response->href = $config->base_url('settings/prd');
					}
				}
				elseif($postData->dataset == "add") {
					$category_id = "PCAT".random_string('nozero', 5);
					$query = $pos->prepare("INSERT INTO products_categories SET category = '{$postData->name}', category_id='{$category_id}', clientId='{$session->clientId}'");
					if($query->execute()) {
						$response->status = 200;
						$response->message = "Product category was inserted";
						$response->href = $config->base_url('settings/prd');
					}
				}
			}

		}
		elseif(isset($_POST["itemId"], $_POST["itemToDelete"]) && confirm_url_id(2, 'deleteCategory')) {
			$postData = (Object) array_map("xss_clean", $_POST);

			//: initializing
			$response = (Object) [
				'status' => 'error', 
				'message' => 'Error Processing Request',
				'branchId' => $session->branchId
			];

			if(empty($postData->itemId)) {
				$response->message = "Error processing request";
			} else {
				$query = $pos->prepare("DELETE FROM products_categories WHERE id='{$postData->itemId}' AND clientId='{$session->clientId}'");
				if($query->execute()) {
					$response->reload = true;
					$response->status = true;
					$response->href = $config->base_url('settings/prd');
					$response->message = "Product category successfully deleted";
				}
			}
		}
	}

	//: Import manager
	elseif(confirm_url_id(1, "importManager")) {

		// create a new object for the access level
		if($accessObject->hasAccess('view', 'settings')) {
			
			// set the valid requests that can be made
			$validRequests = [
			    "customer" => [
			        "fa fa-users",
			        "Import Customers List in Bulk"
			    ],
			    "product" => [
			        "fa fa-shopping-cart",
			        "Import Products List in Bulk"
			    ],
			    "user" => [
			        "fa fa-user",
			        "Import Admin Users into the Database"
			    ]
			];
				
			// assign the variable
		    $currentData = (isset($SITEURL[3])) ? strtolower($SITEURL[3]) : strtolower(xss_clean($SITEURL[2]));
		    $branchId = $session->useBranchId;

		    // columns to use for the query
	        if($currentData == "customer") {
	            // accepted column names
	            $acpCols = [
	                "customer_id"=>"Customer Code", "title"=>"Title", "firstname"=>"Firstname", "gender"=>"Gender", "lastname"=>"Lastname", 
	                "phone_1"=>"Contact Number", "email"=>"Email Address", 
	                "date_of_birth"=>"Date of Birth", "residence"=>"Residence", "city"=>"City"
	            ];
	        } elseif($currentData == "product") {
	            // accepted column names for the products
	            $acpCols = [
	            	"product_id" => "Product Code", 
	                "category_id" => "Product Category", "product_title" => "Product Title", 
	                "product_description" => "Product Description",
	                "product_price" => "Retail Price", "cost_price" => "Product Cost Price",
	                "threshold" => "Product Threshold", "quantity" => "Product Quantity"
	            ];
	        } elseif($currentData == "user") {
	            // accepted column names for the products
	            $acpCols = [
	                "user_id" => "User ID", "name" => "Fullname", 
	                "gender" => "Gender", "email" => "Email Address",
	                "phone" => "Contact Number", "username" => "username",
	                "password" => "Password"
	            ];
	        }

	        // set the branch id in session
	        if(isset($_POST["setBranchId"], $_POST["curBranchId"]) && confirm_url_id(2, 'setBranchId')) {
	            // set the branch id in session
	            $session->curBranchId = (int) $_POST["curBranchId"];

	            // parse success response
	            $response->status = 200;
	            $response->result = "Branch successfully set";
	        }

	        // if there is any file uploaded
	        elseif(isset($_FILES['csv_file']) && !empty($_FILES['csv_file']) && confirm_url_id(2, 'loadCSV')) {

	            // reading tmp_file name
	            $fileData = fopen($_FILES['csv_file']['tmp_name'], 'r');

	            // get the content of the file
	            $column = fgetcsv($fileData);
	            $csvData = array();
	            $csvSessionData = array();
	            $error = false;
	            $i = 0;

	            //using while loop to get the information
	            while($row = fgetcsv($fileData)) {
	            	// session data
	            	$csvSessionData[] = $row;

	                // push the data parsed by the user to the page
	                if($i < 10)  {
	                	$csvData[] = $row;
	                }
	                // increment
	                $i++;
	            }
	            // set the content in a session
	            $session->set_userdata('csvSessionData', $csvSessionData);

	            // set the data to send finally
	            $response = array(
	                'column'	=> $column,
	                'csvData'	=>  $csvData,
	                'data_count' => count($csvSessionData)
	            );
	        }

	        // form content has been submitted
	        elseif(isset($_POST["csvKey"], $_POST["csvValues"], $_POST["uploadCSVData"]) && confirm_url_id(2, "uploadCSVData")) {

	            // initializing
				$response = (Object) [
					'status' => "error",
					'result' => "Unknown request parsed"
				];

	            // begin processing
	            $response->result = "Error processing request.";
	            
	            // confirm that the keys are not empty
	            if(!empty($_POST["csvKey"]) and is_array($_POST["csvKey"])) {
	                // not found
	                $notFound = 0;

	                // check if the keys are all valid
	                foreach($_POST["csvKey"] as $thisKey) {
	                    if(!in_array($thisKey, array_values($acpCols))) {
	                        $notFound++;
	                    }
	                }

	                // check if the branch id session is not empty
	                if(empty($session->curBranchId)) {
	                	// break the code if an error was found
	                    $response->result = 'Please select a Branch to continue.';
	                    $response->trigger= 'importModal';
	                }
	                // count the number of columns parsed to the accepted 
	                elseif(count($_POST["csvKey"]) > count(array_keys($acpCols))) {
	                    // break the code if an error was found
	                    $response->result = 'Required columns exceeded. Please confirm and try.';
	                } elseif($notFound) {
	                    // break the code if an error was found
	                    $response->result = 'Invalid column parsed. Please confirm all columns match.';
	                } else {
	                    // start at zero
	                    $i = 0;
	                    $unqKey = '';
	                	$unqData = '';

	                    // other configuration for missing unique ids
		                //: search if customer code was not parsed then set it
		            	if(($currentData == "customer") && (!in_array("Customer Code", $_POST["csvKey"]))) {
		            		// append the customer_id column and value
		            		$unqKey = "`customer_id`,";
		            	} elseif(($currentData == "product") && (!in_array("Product Code", $_POST["csvKey"]))) {
		            		// append the product_id column and value
		            		$unqKey = "`product_id`,";
		            	} elseif(($currentData == "user") && (!in_array("User ID", $_POST["csvKey"]))) {
		            		// append the user_id column and value
		            		$unqKey = "`user_id`,";
		            	}

		                // begin the processing of the array data
		            	$sqlQuery = "INSERT INTO {$currentData}s (`clientId`,`branchId`, {$unqKey}";
	                    
	                    // continue processing the request
	                    foreach($_POST["csvKey"] as $thisKey) {
	                        // increment
	                        $i++;
	                        // append to the sql query
	                        $sqlQuery .= "`".array_search(xss_clean($thisKey), $acpCols)."`";
	                        // append a comma if the loop hasn't ended yet
	                        if($i < count($_POST["csvKey"])) $sqlQuery .= ",";
	                    }
	                    // append the last bracket
	                    $sqlQuery .= ") VALUES";

	                    $newCSVArray = [];
	                    // set the values
	                    if(!empty($_POST["csvValues"]) and is_array($_POST["csvValues"])) {
	                        // begin
	                        $iv = 0;

	                        // loop through the values list
	                        foreach($_POST["csvValues"] as $key => $eachCsvValue) {
	                            // print each csv value
	                            foreach($eachCsvValue as $eKey => $eValue) {
	                                $newCSVArray[$eKey][] = $eachCsvValue[$eKey];
	                            }
	                        }

	                        $newCSVArray = [];
	                        foreach($session->csvSessionData as $key => $eachCsvValue) {
	                            $newCSVArray[$key] = $eachCsvValue;
	                        }
	                    }

	                    // run this section if the new array is not empty
	                    if(!empty($newCSVArray)) {

	                        // loop through each array dataset
	                        foreach($newCSVArray as $eachData) {

	                        	//: search if customer code was not parsed then set it
	                            if(($currentData == "customer") && (!in_array("Customer Code", $_POST["csvKey"]))) {
	                                // append the customer_id column and value
	                                $unqData = "'".random_string('alnum', 15)."',";
	                            } elseif(($currentData == "product") && (!in_array("Product Code", $_POST["csvKey"]))) {
	                                // append the product_id column and value
	                                $unqData = "'".random_string('alnum', 15)."',";
	                            } elseif(($currentData == "user") && (!in_array("User ID", $_POST["csvKey"]))) {
	                                // append the user_id column and value
	                                $unqData = "'".random_string('alnum', 15)."',";
	                            }

	                            // initializing
	                            $sqlQuery .= "('{$session->clientId}','{$session->curBranchId}',{$unqData}";
	                            $ik = 0;
	                            // loop through each data
	                            foreach($eachData as $eachKey => $eachValue) {
	                                $ik++;
	                                // create sql string for the values
	                                $sqlQuery .= "'".xss_clean($eachValue)."'";

	                                if($ik < count($_POST["csvKey"])) $sqlQuery .= ",";
	                            }
	                            // end
	                            $sqlQuery .= "),";
	                        }

	                        $sqlQuery = substr($sqlQuery, 0, -1) . ';';

	                        // execute the sql statement
	                        $query = $pos->prepare($sqlQuery);

	                        // confirm that the query was successful
	                        if($query->execute()) {
	                            // set the status to true
	                            $session->csvSessionData = null;
	                            $session->curBranchId = null;
	                            $response->result = $currentData;
	                            $response->status = "success";
	                            $response->message = ucfirst($currentData)."s data was successfully imported.";
	                        }
	                    }
	                }
	            }
	        }

		}

	}

	//: Return product
	elseif(confirm_url_id(1, "returnOrderProcessor")) {
		//: search for product
		if(confirm_url_id(2, 'searchOrder')) {
			//: order id
			$orderId = xss_clean($_POST["orderId"]);

			//: create a new object
			$orderObj = load_class('Orders', 'controllers');

			//: load the data
			$data = $orderObj->saleDetails($orderId);

			$response = [
				'orderId' => $orderId,
				'orderDetails' => $data,
				'count' => count($data)
			];
		}
	}

}

echo json_encode($response);