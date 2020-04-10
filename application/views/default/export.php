<?php
//initializing
$found = false;
// call the global function
global $posClass, $pos;

// client data
$clientData = $posClass->getAllRows("settings", "*", "clientId='{$session->clientId}'");
$branchData = $posClass->getAllRows("branches", "*", "id='{$session->branchId}'");

// set the data set
$clientData = $clientData[0];
$storeTheme = (Object) json_decode($clientData->theme_color);

$clientData->bg_color = $storeTheme->bg_colors;
$clientData->bg_color_code = $storeTheme->bg_color_code;
$clientData->bg_color_light = $storeTheme->bg_color_light;
$clientData->btn_outline = $storeTheme->btn_outline;

$sales_id = null;
$result = null;

global $accessObject;

$accessObject->userId = $session->userId;

// check if a third parameter was parsed in the url
if(confirm_url_id(1)) {
	
	// assign value
    $sales_id = xss_clean($SITEURL[1]);
    $initials = strtoupper(substr($sales_id, 0, 3));

    // access permissions
    $branchAccess = "";
    $accessLimit = "";

    // use the access level for limit contents that is displayed
    if(!$accessObject->hasAccess('monitoring', 'branches')) {
        $branchAccess = " && a.branchId = '{$session->branchId}'";
        $accessLimit = " && a.recorded_by = '{$session->userId}'";
    }

    // if the id starts with INV then its a sales register record
    if(in_array($initials, ["INV", "POS"])) {
		// fetch the sales details
		$sales = $pos->prepare("
			SELECT *, sales.order_date AS request_date,
				users.user_id, users.name AS owner_name, ct.phone_1,
				ct.email, ct.firstname, ct.lastname, ct.residence
			FROM sales
			LEFT JOIN users ON users.user_id = sales.recorded_by
			LEFT JOIN customers ct ON ct.customer_id = sales.customer_id
			WHERE sales.clientId = '{$session->clientId}' AND order_id = ? {$branchAccess} {$accessLimit}
		");
	} else {
		// fetch the order / quote details
		$sales = $pos->prepare("
			SELECT *,
				users.user_id, users.name AS owner_name, ct.phone_1,
				ct.email, ct.firstname, ct.lastname, ct.residence
			FROM requests
			LEFT JOIN users ON users.user_id = requests.recorded_by
			LEFT JOIN customers ct ON ct.customer_id = requests.customer_id
			WHERE requests.clientId = '{$session->clientId}' AND request_id = ? {$branchAccess} {$accessLimit}
		");
	}
	// execute the statement
	$sales->execute([$sales_id]);
	// count the number of rows
	if($sales->rowCount() > 0) {
		// using while loop to get the data
		$result = $sales->fetch(PDO::FETCH_OBJ);
		$found = true;
		$session->set_userdata('itemId', $sales_id);
		$session->set_userdata('itemType', 'sales');
	}
}

if(!empty($result)) {
	// remove every server time limit
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	// call the header file
	load_file(
		array(
			'tcpdf_include'=>'libraries/pdf'
		)
	);
	$format = "pdf";
	// ---------------------------------------------------------
	error_reporting(0);

	// --------------------------------------------------------
	// set the styles for the invoice page
	$invoice_data = '';

	$style = <<<EOF
	<!-- EXAMPLE OF CSS STYLE -->
	<style>
	    .bg-span {
			color: {$clientData->bg_color_code}; 
	    	font-size: 30px;
	    	font-weight:bolder;
	    	max-width: 100px;
	    }
	    h1 {
	        color: navy;
	        font-family: times;
	        font-size: 20pt;
	        text-decoration: none;
	    }
	</style>
	EOF;

	$invoice_data .= "
	$style
	<table class=\"table table-bordered\" style=\"background: #ffffff none repeat scroll 0 0;border-bottom: 2px solid #f4f4f4;position: relative;box-shadow: 0 1px 2px #acacac;width:100%;font-family: open sans; width:100%;margin-bottom:2px\" border=\"0\" cellpadding=\"5px\" cellspacing=\"5px\">
		<tbody>
			<tr>
				<td align=\"left\" width=\"35%\" class=\"bg-span\">
					".((isset($result->request_type)) ? strtoupper($result->request_type) : "RECEIPT")."</td>
				<td align=\"right\" width=\"65%\"></td>
			</tr>
			<tr>
				<td align=\"left\" width=\"35%\">
					<span><strong>ID:</strong> #{$sales_id}</span><br>
					<span><strong>Date:</strong></span> ".date("jS M Y h:iA", strtotime($result->request_date))."<br>
						<strong>Served By:</strong> ".($result->owner_name)."<br>
				</td>
				<td align=\"right\" width=\"65%\">
					<address>
	                    <strong class=\"font-14\">Billed To :</strong><br>{$result->firstname} {$result->lastname}<br>
	                    {$result->phone_1}<br>
	                    {$result->email}<br>
	                    {$result->residence}<br>
	                </address>
				</td>
			</tr>
			<tr>
				<td></td>
				<td valign=\"center\" style='padding-right:20px;vertical-align:middle' width=\"50%\" align=\"right\"></td>
			</tr>
		</tbody>
	</table>
	<table border=\"0\" width=\"100%\" cellpadding=\"20px\" cellspacing=\"0px\">
		<tbody>
			<tr>
				<td style=\"background-color:{$clientData->bg_color_code}; color:#fff; font-size: 16px\" valign=\"top\">&nbsp;List of items for this 
				<strong>".((isset($result->request_type)) ? strtoupper($result->request_type) : "POS")."</strong></td>
			</tr>
		</tbody>
	</table>

	<table class=\"table table-bordered\" border=\"0\" width=\"100%\" cellpadding=\"15px\" cellspacing=\"0px\" style=\"border:solid 1px #cccccc\">
		<thead>
			<tr style=\"border:solid 1px #cccccc\">
				<th style=\"border:solid 1px #cccccc\" width=\"10%\" align=\"left\"><strong>NO.</strong></th>
				<th style=\"border:solid 1px #cccccc\" width=\"30%\" align=\"left\"><strong>ITEM DESCRIPTION</strong></th>
				<th style=\"border:solid 1px #cccccc\" width=\"20%\" align=\"left\"><strong>QUANTITY</strong></th>
				<th style=\"border:solid 1px #cccccc\" width=\"20%\" align=\"left\"><strong>UNIT PRICE</strong></th>
				<th style=\"border:solid 1px #cccccc\" width=\"20%\" align=\"left\"><strong>TOTAL</strong></th>
			</tr>
		</thead>

		<tbody>";
			
			if(in_array($initials, ["INV", "POS"])) {
				#fetch the orders list
				$user_orders = $pos->prepare("
					SELECT *,
						rq.order_discount AS request_discount, 
						rq.order_amount_paid AS request_total, 
						rq.currency, rq.order_date AS request_date, rq.customer_id,
						products.product_id, products.product_title, 
						products.product_description,
						products.product_image, rqd.product_unit_price AS product_price
					FROM sales_details rqd
					LEFT JOIN sales rq ON rq.order_id = rqd.order_id
					LEFT JOIN products ON rqd.product_id = products.id
					WHERE rqd.order_id=?
				");
			} else {
				$user_orders = $pos->prepare("
					SELECT *,
						rq.request_discount, 
						rq.request_total,
						rq.currency, rq.request_date, rq.customer_id,
						products.product_id, products.product_title, 
						products.product_description,
						products.product_image, rqd.product_price
					FROM requests_details rqd
					LEFT JOIN requests rq ON rq.request_id = rqd.request_id
					LEFT JOIN products ON rqd.product_id = products.id
					WHERE rqd.request_id=?
				");
			}
			$user_orders->execute([$sales_id]);
			#initials
			$orderTotal = 0;
			#count the number of rows
			if($user_orders->rowCount() > 0) {
				$ii = 0;
				#list them here 
				while($results4 = $user_orders->fetch(PDO::FETCH_OBJ)) {
					$ii++;
					$o_discount = $results4->request_discount;
					$overall_price = $results4->request_total-$o_discount;
					$o_paid = $results4->request_total;

					$price = $results4->product_price;

					$pricetotal = ($price * $results4->product_quantity);
					$orderTotal = $pricetotal + $orderTotal;
					setlocale(LC_MONETARY, "en_US");
					$pricetotal = number_format($pricetotal, 2);
					
					$invoice_data .= "<tr>
						<td width=\"10%\">{$ii}</td>
						<td width=\"30%\">{$results4->product_title}</td>
						<td width=\"20%\">{$results4->product_quantity}</td>
						<td width=\"20%\" align=\"left\">{$clientData->default_currency} ".number_format($results4->product_price, 2)."</td>
						<td width=\"20%\" align=\"left\">{$clientData->default_currency} {$pricetotal}</td>
					</tr>";
				}
			}
		$invoice_data .= "
		";
		$invoice_data .= "</tbody></table>";

		if($user_orders->rowCount() > 0) {
			$invoice_data .= "<table class=\"table table-bordered\" width=\"100%\" border=\"0\" cellpadding=\"30px\" cellspacing=\"30px\">";
			$invoice_data .= "<tr><td>&nbsp;</td></tr>";
			$invoice_data .= "</table>";

			$invoice_data .= "<table width=\"100%\" class=\"table table-bordered\" border=\"0\" cellpadding=\"10px\" cellspacing=\"0\" style=\"background: #ffffff none repeat scroll 0 0;border-bottom: 2px solid #fff;position: relative;box-shadow: 0 1px 5px #acacac;width:100%;font-family: open sans; width:100%;margin-bottom:2px\">";
			$invoice_data .= "<tr style=\"border-top: #ccc 1px solid\">
					<td style=\"padding:10px\" colspan=\"2\"></td>
					<td style=\"padding:10px\" align=\"right\" style=\"font-weight:none;text-transform:uppercase\">SUB TOTAL (".$user_orders->rowCount()." Items):</td>
					<td style=\"text-transform:uppercase\">{$clientData->default_currency} ".number_format($orderTotal, 2)."</td>
				</tr>";
				$invoice_data .= "<tr>
					<td colspan=\"2\"></td>
					<td align=\"right\" style=\"text-transform:uppercase\">DISCOUNT:</td>
					<td style=\"text-transform:uppercase\">{$clientData->default_currency} ".number_format($o_discount, 2)."</td>
				</tr>";
			$invoice_data .= "<tr>
				<td style=\"padding:10px\"></td>
				<td style=\"padding:10px\"></td>
				<td align=\"right\" style=\"background-color:#f4f4f4;padding:10px;font-weight:bolder;text-transform:uppercase\"><strong>TOTAL PAYABLE:</strong></td>
				<td align=\"left\"x style=\"padding:10px;font-weight:bolder;background-color:{$clientData->bg_color_code};color:#fff;text-transform:uppercase\"><strong>{$clientData->default_currency} ".number_format($overall_price, 2)."</strong></td>
			</tr>";
			if(in_array($initials, ["INV", "POS"])) {
				$invoice_data .= "<tr>
				<td style=\"padding:10px\"></td>
				<td style=\"padding:10px\"></td>
				<td align=\"right\" style=\"padding:10px;text-transform:uppercase\">TOTAL PAID:</td>
				<td align=\"left\"x style=\"padding:10px;text-transform:uppercase\">{$clientData->default_currency} ".number_format($result->order_amount_paid, 2)."</td>
			</tr>";
			}
			
			$invoice_data .= "<tr>
				<td style=\"padding:10px\" colspan=\"2\"></td>
				<td  align=\"right\" style=\"padding:10px\" style=\"background-color:#f4f4f4;font-weight:bolder;text-transform:uppercase\"><strong>BALANCE:</strong></td>
				<td style=\"padding:10px\" style=\"font-weight:bolder;background-color:{$clientData->bg_color_code};color:#fff;text-transform:uppercase\"><strong>{$clientData->default_currency} ".number_format(($result->order_amount_balance), 2)."</strong></td>
			</tr>";
			
			$invoice_data .= "</table>";
		}

	// print $invoice_data;
	// exit;
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// print_r(DOC_ROOT."");exit;
	// confirm that the format should be in pdf
	if(in_array($format, array("pdf"))) {
		
		$file_name = strtolower("C:\\xampp\htdocs\\pos\\assets\\pdfs\\invoice_$sales_id");

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(config_item('site_name').' - '.config_item('developer'));
		$pdf->SetTitle("$sales_id | $clientData->client_name - ".config_item('site_name'));
		$pdf->SetSubject('Point of Sale Receipt');
		$pdf->SetKeywords('cms, sales, invoice, pos');

		// set default header data
		$pdf->SetHeaderData('C:\\xampp\htdocs\\pos\\assets\\images\\logo.png', PDF_HEADER_LOGO_WIDTH, $clientData->client_name, "Phone: " .$clientData->primary_contact."\nEmail: ".$clientData->client_email."\nWebsite: ".$clientData->client_website);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('dejavusans', '', 10);

		// add a page
		$pdf->AddPage('portrait', 'A4');

		// output the HTML content
		$pdf->writeHTML($invoice_data, false, false, true, false, '');
		
		//print $report_data;
		// instantiate and use the dompdf class
		
		//Close and output PDF document
		$pdf->Output($file_name.".pdf", 'I');
	}
} else {
	show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
}
exit;
?>