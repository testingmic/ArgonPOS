<?php 

class Crons {

	public $dbConn;

	public function __construct() {
		$this->baseUrl = "https://pos.visaminetsolutions.com/";
		$this->siteName = "ArgonPOS";
	}

	private function dbConn() {
		
		// CONNECT TO THE DATABASE
		$connectionArray = array(
			'hostname' => "mysql.uk.cloudlogin.co",
			'database' => 'kofifenix_einvnt',
			'username' => 'kofifenix_einvnt',
			'password' => 't0Wwp0KB7r'
		);

		$conn_name = $connectionArray['username'];
		// run the database connection
		try {
			$conn = "mysql:host={$connectionArray['hostname']}; dbname={$connectionArray['database']}; charset=utf8";			
			$this->dbConn = new PDO($conn, $connectionArray['username'], $connectionArray['password']);
			$this->dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbConn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
		} catch(PDOException $e) {
			die("Database Connection Error: ".$e->getMessage());
		}

		return $this->dbConn;

	}

	private function loadSalesInvoice($invoiceId, $queryType = "invoice") {

		// begin the database connection
		$this->dbConn();

		$found = false;
		$orderId = null;
		$rows = "";
		$discount = 0;
		$overall = 0;
		$subTotalRow = "";
		$subTotal = 0;
		$mailerContent = "";

		// check the request that has been parsed
		if($queryType == "invoice") {
			// continue the processing
			$stmt = $this->dbConn->prepare(
	            "SELECT
					c.product_title, b.*, a.order_discount AS request_discount, a.order_amount_paid,
		            b.product_unit_price AS product_price, a.payment_type, a.order_date AS request_date, 
		            e.name AS recorder_name, a.overall_order_amount, a.order_amount_balance,
		            f.branch_name, CONCAT(d.firstname, ' ', d.lastname) AS customer_name, 
		            d.phone_1, d.residence, d.email, g.receipt_message,
		            g.client_name, g.client_email, g.client_logo, g.client_website
	            FROM sales_details b 
	            LEFT JOIN products c ON b.product_id = c.id 
	            LEFT JOIN sales a ON a.order_id = b.order_id 
	            LEFT JOIN customers d ON d.customer_id = a.customer_id
	            LEFT JOIN users e ON e.user_id = a.recorded_by
	            LEFT JOIN branches f ON f.id = b.branchId
	            LEFT JOIN settings g ON f.clientId = b.clientId
	            WHERE a.order_id = '{$invoiceId}' ORDER BY b.id
	        ");
		} else {
			// search the requests table
	        $stmt = $this->dbConn->prepare(
	            "SELECT 
		            c.product_title, b.*, a.request_discount, a.request_date, a.request_type,
		            CONCAT(d.firstname, ' ', d.lastname) AS customer_name, 
		            f.branch_name, d.phone_1, e.name AS recorder_name, 
		            d.residence, d.email, g.receipt_message,
		            g.client_name, g.client_email, g.client_logo, g.client_website
	            FROM requests_details b 
	                LEFT JOIN products c ON b.product_id = c.id 
	                INNER JOIN requests a ON a.request_id = b.request_id 
	                LEFT JOIN customers d ON d.customer_id = a.customer_id
	                LEFT JOIN users e ON e.user_id = a.recorded_by
	                LEFT JOIN branches f ON f.id = a.branchId
	                LEFT JOIN settings g ON f.clientId = a.clientId
	            WHERE b.request_id = '{$invoiceId}'
	        ");
		}

        $stmt->execute();

        if ($stmt->rowCount()) {

	        $found = true;
	        
	        while ($data = $stmt->fetch(PDO::FETCH_OBJ)) {
	        	$query[] = $data;
	            $productTotal = number_format($data->product_total, 2);
	            $rows .= "
	                <tr>
	                    <td style='border-bottom: solid 1px #ccc; padding-bottom: 10px;padding-top: 10px; font-family: Calibri Light;'>{$data->product_title}</td>
	                    <td style='border-bottom: solid 1px #ccc; font-family: Calibri Light;'>{$data->product_quantity}</td>
	                    <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc; padding-bottom: 10px;padding-top: 10px;\">GH&cent; {$data->product_price}</td>
	                    <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc; padding-bottom: 10px;padding-top: 10px;\">GH&cent; {$productTotal}</td>
	                </tr>";

	            $subTotal += $data->product_total;
	            $discount = number_format($data->request_discount, 2);
	        }

	        $overall = number_format($subTotal - $discount, 2);
	        $request_date = $query[0]->request_date;
	        $request_type = (isset($query[0]->request_type)) ? $query[0]->request_type : "Invoice";
	        $recorded_by = $query[0]->recorder_name;
	        $order_balance = ($queryType == "invoice") ? $query[0]->order_amount_balance : 0.00;
	        $order_amount_paid = ($queryType == "invoice") ? number_format($query[0]->order_amount_paid, 2) : 0.00;

	        $subTotalRow = "
	            <tr>                                                        
	                <td class=\"border-0\"></td>
	                <td colspan=\"2\" style=\"padding-top: 30px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>Sub Total (".count($query)." Items)</b></td>
	                <td style=\"padding-top: 10px; padding-left: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>GH&cent; ".number_format($subTotal, 2)."</b></td>
	            </tr>
	            <tr>
	                <th class=\"border-0\"></th>                                                        
	                <td colspan=\"2\" style=\"padding-top: 10px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>Discount</b></td>
	                <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>GH&cent; {$discount}</b></td>
	            </tr>
	            <tr class=\"bg-dark text-white\">
	                <th class=\"border-0\"></th>                                                        
	                <td colspan=\"2\" style=\"padding-top: 10px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>Order Total</b></td>
	                <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>GH&cent; {$overall}</b></td>
	            </tr>
	            <tr class=\"bg-dark text-white\">
	                <th class=\"border-0\"></th>                                                        
	                <td colspan=\"2\" style=\"padding-top: 10px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>Amount Paid</b></td>
	                <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>GH&cent; {$order_amount_paid}</b></td>
	            </tr>";

	        // show this section if its an invoice
	        if($queryType == "invoice") {
				$subTotalRow .= "<tr class=\"bg-dark text-white\">
		                <th class=\"border-0\"></th>                                                        
		                <td colspan=\"2\" style=\"padding-top: 10px; padding-bottom:10px; font-family: Calibri Light\"><b>Balance</b></td>
		                <td style=\"padding: 5px; font-family: Calibri Light\"><b>GH&cent; {$order_balance}</b></td>
		            </tr>";
		    }

	        $mailerContent = '
	        	<style>
				    .bg-span {
						color: #5e2572; 
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
				<div style="margin: auto auto; width: 610px;">
					<table width="600px" cellpadding="0" style="min-height: 400px; margin: auto auto;" cellspacing="0">
						<tr style="padding: 5px; border-bottom: solid 1px #ccc;">
							<td colspan="4" align="center" style="padding: 10px;">
								<h1 style="margin-bottom: 0px; margin-top:0px">'.strtoupper($query[0]->client_name).'</h1>
								'.(!empty($query[0]->branch_name) ? "(".$query[0]->branch_name.")" : null).'<br>
								'.(($queryType == "invoice") ? "Served by: " : "Prepared By: ").' '.$query[0]->recorder_name.'
								<hr style="border: dashed 1px #ccc;">
								<div style="font-family: Calibri Light; font-size: 16px">
								Receipt #: <strong>'.$invoiceId.'</strong><br>
								'.date("d M Y h:ia", strtotime($request_date)).'
								</div>
								<hr style="border: dashed 1px #ccc;">
							</td>
						</tr>
						<tr style="padding: 5px; border-bottom: solid 1px #ccc;">
							<td colspan="4" align="center" style="padding-bottom: 10px; font-family: Calibri Light;">
								<strong style="font-size: 20px;">'.$query[0]->customer_name.'</strong>
								<br>
								'.(!empty($query[0]->phone_1) ? "({$query[0]->phone_1})" : null).'
							</td>
						</tr>
						<tr>
							<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: #9932cc; color: #fff;"><strong>Product</strong></td>
							<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: #9932cc; color: #fff"><strong>Quantity</strong></td>
							<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: #9932cc; color: #fff"><strong>Unit Price</strong></td>
							<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: #9932cc; color: #fff"><strong>Subtotal</strong></td>
						</tr>
						'.$rows.'
						'.$subTotalRow.'
					</table>
					<table width="600px">
						<tbody style="text-align: center;">
							<tr>
								<td colspan="4">
									<hr style="border: dashed 1px #ccc; text-align: center;">
									<img width="150px" src="'.$this->baseUrl.'assets/images/logo.png"  alt="logo-small" class="logo-sm">
								</td>
							</tr>
						</tbody>
					</table>
				</div>';
	    }

	    // generate a new pdf document
		$fileName = strtolower("C:\\xampp\htdocs\\analitica_innovare\\einventory\\assets\\pdfs\\".$request_type."_".$invoiceId);

		// $this->generateEmailAttachment($fileName, $mailerContent);

		return $mailerContent;
	}

	private function generateEmailAttachment($fileName, $fileContent) {

		// create a new object
		require "C:\\xampp\htdocs\\analitica_innovare\\einventory\\system\\libraries\\pdf\\tcpdf_include.php";

		// require "/home/www/dev.analiticainnovare.net/pos/pos/system/libraries/pdf/tcpdf_include.php";

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Argon POS - VisamiNetSolutions');
		$pdf->SetTitle("$invoiceId | $clientData->branch_name - ".config_item('site_name'));
		$pdf->SetSubject('Sales Invoice');
		$pdf->SetKeywords('cms, sales, invoice, pos, VisamiNetSolutions');

		// set default header data
		$pdf->SetHeaderData('assets/images/logo.png', PDF_HEADER_LOGO_WIDTH, $clientData->branch_name, "Phone: " .$clientData->branch_contact."\nEmail: ".$clientData->client_email."\nWebsite: ".$clientData->client_website);

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
		$pdf->writeHTML($fileContent, false, false, true, false, '');
		
		//Close and output PDF document
		$pdf->Output($fileName.".pdf", 'F');

	}

	private function generateGeneralMessage($message, $subject, $template_type) {

		$mailerContent = '
		<div style="margin: auto auto; width: 610px;">
			<table width="600px" border="1" cellpadding="0" style="min-height: 400px; margin: auto auto;" cellspacing="0">
				<tr style="padding: 5px; border-bottom: solid 1px #ccc;">
					<td colspan="4" align="center" style="padding: 10px;">
						<h1 style="margin-bottom: 0px; margin-top:0px">'.$this->siteName.'</h1>
						<hr style="border: dashed 1px #ccc;">
						<div style="font-family: Calibri Light; background: #9932cc; font-size: 20px; padding: 5px;color: white; text-transform: uppercase; font-weight; bolder">
						<strong>'.$subject.'</strong>
						</div>
						<hr style="border: dashed 1px #ccc;">
					</td>
				</tr>

				<tr>
					<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase;">
						'.$message.'
					</td>
				</tr>
			</table>
			<table width="600px">
				<tbody style="text-align: center;">
					<tr>
						<td colspan="4">
							<hr style="border: dashed 1px #ccc; text-align: center;">
							<img width="150px" src="'.$this->baseUrl.'assets/images/logo.png"  alt="logo-small" class="logo-sm">
						</td>
					</tr>
				</tbody>
			</table>
		</div>';

		return $mailerContent;
	}

	private function loadEmailRequests() {
		
		// begin the database connection
		$this->dbConn();

		try {

			// run the query
			$stmt = $this->dbConn->prepare(
	            "SELECT 
	            	a.*, 
	            	b.client_name, b.client_email, b.client_logo, b.primary_contact, b.address_1,
	            	c.branch_name, c.location, c.branch_contact, c.branch_email
	            FROM email_list a
	            LEFT JOIN settings b ON a.clientId = b.clientId
	            LEFT JOIN branches c ON c.branch_id = a.branchId
	            WHERE a.sent_status='0' AND a.deleted='0' LIMIT 5
	        ");
	        $stmt->execute();

	        $dataToUse = null;

	        // looping through the content
	        while($result = $stmt->fetch(PDO::FETCH_OBJ)) {
	        	
	        	// commence the processing
	        	if(in_array($result->template_type, array("invoice", "request"))) {
	        		$subject = ($result->template_type == "invoice") ? "Sales Invoice #{$result->itemId}" : ucfirst($result->template_type)." Invoice #{$result->itemId}";
	        		$dataToUse = $this->loadSalesInvoice($result->itemId, $result->template_type);
	        	} elseif(in_array($result->template_type, array("login", "recovery"))) {
	        		$subject = $result->subject;
	        		$dataToUse = $this->generateGeneralMessage($result->message, $subject, $result->template_type);
	        	}

	        	// use the content submitted
	        	if(!empty($dataToUse)) {

	        		// convert the recipient list to an array
	        		$recipient_list = json_decode($result->recipients_list, true);
	        		$recipient_list = $recipient_list["recipients_list"];
	        		
	    			// submit the data for processing
	    			$mailing = $this->cronSendMail($recipient_list, $subject, $dataToUse);

	    			// set the mail status to true
	    			if($mailing) {
	    				$this->dbConn->query("UPDATE email_list SET sent_status = '1', date_sent=now() WHERE id='{$result->id}'");

	    				print "Mails successfully sent\n";
	    			}

	        	}
	        }

	    } catch(PDOException $e) {
	    	return $e->getMessage();
	    }

	}

	private function cronSendMail($recipient_list, $subject, $message) {

		require "C:\\xampp\htdocs\\analitica_innovare\\einventory\\system\\libraries\\Phpmailer.php";
		require "C:\\xampp\htdocs\\analitica_innovare\\einventory\\system\\libraries\\Smtp.php";

		$mail = new Phpmailer();
		$smtp = new Smtp();

		$config = (Object) array(
			'subject' => $subject,
			'headers' => "From: (Argon POS - VisamiNetSolutions) <info@analiticainnovare.com> \r\n Content-type: text/html; charset=utf-8",
			'Smtp' => true,
			'SmtpHost' => 'mail.supremecluster.com',
			'SmtpPort' => '465',
			'SmtpUser' => 'no-reply@analiticainnovare.net',
			'SmtpPass' => '8MiF3u8vkD',
			'SmtpSecure' => 'ssl'
		);

		$mail->isSMTP();
		$mail->SMTPDebug = 1;
		$mail->Host = $config->SmtpHost;
		$mail->SMTPAuth = true;
		$mail->Username = $config->SmtpUser;
		$mail->Password = $config->SmtpPass;
		$mail->SMTPSecure = $config->SmtpSecure;
		$mail->Port = $config->SmtpPort;

		// set the user from which the email is been sent
		$mail->setFrom('no-reply@analiticainnovare.net', 'EvelynPOS - Analitica Innovare');

		// loop through the list of recipients for this mail
        foreach($recipient_list as $emailRecipient) {
			$mail->addAddress($emailRecipient['email'], $emailRecipient['fullname']);
		}

		// this is an html message
		$mail->isHTML(true);

		// set the subject and message
		$mail->Subject = $subject;
		$mail->Body    = $message;
		
		// send the email message to the users
		if($mail->send()) {
			return true;
		} else {
 			return false;
		}

	}

	private function customerPreferredPayment() {
		// begin the database connection
		$this->dbConn();
		$last30Days = date("Y-m-d", strtotime("30 days ago"));

		try {

			// run the query
			$stmt = $this->dbConn->prepare("
				SELECT
					a.customer_id, CONCAT(a.firstname, ' ', a.lastname) AS fullname, a.email,
					(
						SELECT 
							GROUP_CONCAT(b.payment_type) 
						FROM 
							sales b 
						WHERE 
							b.customer_id=a.customer_id AND b.deleted='0' AND b.order_status='confirmed'
							AND (DATE(b.order_date) >= '{$last30Days}' AND DATE(b.order_date) <= CURDATE())
					) AS preferred_payment_type
				FROM customers a
			");
			$stmt->execute();

			//: array set
			$customerGrouping = array();

			//: Loop through the list of all items
			while($result = $stmt->fetch(PDO::FETCH_OBJ)) {

				// get the list of all items
				$customerGrouping[$result->customer_id] = array(
					"customer_id" => $result->customer_id,
					"customer_name" => $result->fullname,
					"preferred_payment" => $result->preferred_payment_type
				);

			}

			// new customer array
			$newCustomer = array();

			// loop through each customer
			foreach($customerGrouping as $eachCustomer) {
				// get the preferred payment type
				$payment_type = explode(",", $eachCustomer["preferred_payment"]);
				$eachType = array();
				
				// loop through each item and set that as an array
				foreach($payment_type as $thisType) {
					$eachType[$thisType][] = $thisType;
				}
				// another array
				$nextTypeArray = array();

				// loop through the grouped type
				foreach($eachType as $key => $value) {
					$nextTypeArray[$key] = count($eachType[$key]);
				}

				// get the maximum payment type used
				$maximumUsed = array_search(max($nextTypeArray), $nextTypeArray);

				// set up a new user information
				$newCustomer[] = array(
					"customer_id" => $eachCustomer["customer_id"],
					"customer_name" => $eachCustomer["customer_name"],
					"preferred_payment" => $maximumUsed
				);
			}

			// loop through each user and update the database table
			foreach ($newCustomer as $thisCustomer) {
				// update the database
				$this->dbConn->query("
					UPDATE customers 
					SET preferred_payment_type = '{$thisCustomer["preferred_payment"]}'
					WHERE customer_id = '{$thisCustomer["customer_id"]}'
				");
				print "{$thisCustomer["customer_name"]} payment type has been updated\n";
			}


		} catch(PDOException $e) {
	    	return $e->getMessage();
	    }

	}

	public function processAllMails() {
		$this->loadEmailRequests();
		$this->customerPreferredPayment();
	}

}

// create new object
$cronMailer = new Crons;
$cronMailer->processAllMails();
?>