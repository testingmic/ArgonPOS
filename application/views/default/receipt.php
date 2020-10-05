<?php 
// global variables
global $accessObject, $config, $posClass;
// client data
$clientData = $posClass->getAllRows("settings", "*", "clientId='{$posClass->clientId}'");
$branchData = $posClass->getAllRows("branches", "*", "id='{$session->branchId}'");

// set the data set
$clientData = (!empty($clientData)) ? $clientData[0] : null;
$storeTheme = (Object) json_decode($clientData->theme_color);

$clientData->bg_color = $storeTheme->bg_colors;
$clientData->bg_color_code = $storeTheme->bg_color_code;
$clientData->bg_color_light = $storeTheme->bg_color_light;
$clientData->btn_outline = $storeTheme->btn_outline;

// initializing
$request_type = 'Order';
$found = false;
$orderId = null;
$rows = "";
$discount = 0;
$overall = 0;
$subTotalRow = "";
$subTotal = 0;

$accessObject->userId = $session->userId;

// prince invoice
if(confirm_url_id(1)) {

    // assign value
    $orderId = xss_clean($SITEURL[1]);
    $initials = strtoupper(substr($orderId, 0, 3));

    // access permissions
    $branchAccess = "";
    $accessLimit = "";

    // use the access level for limit contents that is displayed
    if(!$accessObject->hasAccess('monitoring', 'branches')) {
        $branchAccess = " && a.branchId = '{$session->branchId}'";
        $accessLimit = " && a.recorded_by = '{$session->userId}'";
    }
    $query = null;

    // if the id starts with INV then its a sales register record
    if(in_array($initials, ["INV", "POS"])) {
        //&& a.branchId = '{$session->branchId}'
        // search the sales records table
        $query = $posClass->getAllRows(
            "sales_details b 
                LEFT JOIN products c ON b.product_id = c.id 
                INNER JOIN sales a ON a.order_id = b.order_id 
                LEFT JOIN customers d ON d.customer_id = a.customer_id
                LEFT JOIN users e ON e.user_id = a.recorded_by
                LEFT JOIN branches f ON f.id = b.branchId
            ", 
            "c.product_title, b.*, a.order_discount AS request_discount, a.order_amount_paid,
            b.product_unit_price AS product_price, a.payment_type, a.order_date AS request_date, 
            e.name AS recorder_name, a.overall_order_amount, a.order_amount_balance,
            f.branch_name, CONCAT(d.firstname, ' ', d.lastname) AS customer_name, 
            d.phone_1, d.residence, d.email", 
            "a.clientId = '{$posClass->clientId}' && b.order_id = '{$orderId}' {$branchAccess} {$accessLimit} ORDER BY b.id"
        );
    } 

    if (!empty($query)) {

        $found = true;

        foreach ($query as $data) {

            $data->request_type = (isset($data->request_type)) ? $data->request_type : "INVOICE";

            $productTotal = number_format($data->product_total, 2);
            $rows .= "
                <tr>
                    <td style='border-bottom: solid 1px #ccc; padding-bottom: 10px;padding-top: 10px; font-family: Calibri Light;'>{$data->product_title}</td>
                    <td style='border-bottom: solid 1px #ccc; font-family: Calibri Light;'>{$data->product_quantity}</td>
                    <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc; padding-bottom: 10px;padding-top: 10px;\">{$clientData->default_currency} {$data->product_price}</td>
                    <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc; padding-bottom: 10px;padding-top: 10px;\">{$clientData->default_currency} {$productTotal}</td>
                </tr>";

            $subTotal += $data->product_total;
            $discount = number_format($data->request_discount, 2);
        }
        $overall = number_format($subTotal - $discount, 2);
        $request_date = $query[0]->request_date;
        $request_type = $query[0]->request_type;
        $recorded_by = $query[0]->recorder_name;
        $order_balance = $query[0]->order_amount_balance;
        $amount_tendered = number_format(($query[0]->order_amount_paid+$order_balance), 2);
        $order_amount_paid = number_format($query[0]->order_amount_paid, 2);

        $subTotalRow = "
            <tr>                                                        
                <td class=\"border-0\"></td>
                <td colspan=\"2\" style=\"padding-top: 30px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>Sub Total (".count($query)." Items)</b></td>
                <td style=\"padding-top: 10px; padding-left: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>{$clientData->default_currency} ".number_format($subTotal, 2)."</b></td>
            </tr>
            <tr>
                <th class=\"border-0\"></th>                                                        
                <td colspan=\"2\" style=\"padding-top: 10px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>Discount</b></td>
                <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>{$clientData->default_currency} {$discount}</b></td>
            </tr>
            <tr class=\"bg-dark text-white\">
                <th class=\"border-0\"></th>                                                        
                <td colspan=\"2\" style=\"padding-top: 10px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc; background: #f4f4f4;\"><b>Order Total</b></td>
                <td style=\"padding: 5px; font-family: Calibri Light; background-color:{$clientData->bg_color_code}; color:#fff; border-bottom: solid 1px #ccc;\"><b>{$clientData->default_currency} {$overall}</b></td>
            </tr>
            <tr class=\"bg-dark text-white\">
                <th class=\"border-0\"></th>                                                        
                <td colspan=\"2\" style=\"padding-top: 10px; padding-bottom:10px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>Amount Paid</b></td>
                <td style=\"padding: 5px; font-family: Calibri Light; border-bottom: solid 1px #ccc;\"><b>{$clientData->default_currency} ".($amount_tendered)."</b></td>
            </tr>
            <tr class=\"bg-dark text-white\">
                <th class=\"border-0\"></th>                                                        
                <td colspan=\"2\" style=\"padding-top: 10px; background: #f4f4f4; padding-bottom:10px; font-family: Calibri Light\"><b>Balance</b></td>
                <td style=\"padding: 5px; font-family: Calibri Light; background-color:{$clientData->bg_color_code}; color:#fff; \"><b>{$clientData->default_currency} {$order_balance}</b></td>
            </tr>
        ";
    }
}
// print error message if the id was not found
if(!$found) {
    show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>EvelynPOS_Invoice_<?= $orderId ?></title>
</head>
<body>
	<div style="margin: auto auto; width: 610px; background: #fff; border-radius: 5px; box-shadow: 0px 1px 2px #000;">
		<table width="600px" cellpadding="0" style="min-height: 400px; margin: auto auto;" cellspacing="0">
			<tr style="padding: 5px; border-bottom: solid 1px #ccc;">
				<td colspan="4" align="center" style="padding: 10px">
					<h1 style="margin-bottom: 0px; margin-top:0px"><?= strtoupper($clientData->client_name); ?></h1>
					<?= !empty($query[0]->branch_name) ? "({$query[0]->branch_name})" : null; ?><br>
					Served by: <?= $query[0]->recorder_name ?>
					<hr style="border: dashed 1px #ccc;">
                    <div style="font-family: Calibri Light; font-size: 16px">
    					Receipt #: <strong><?= $orderId; ?></strong><br>
    					<?= date("d M Y h:ia", strtotime($request_date)) ?>
                    </div>
					<hr style="border: dashed 1px #ccc;">
				</td>
			</tr>
			<tr style="padding: 5px; border-bottom: solid 1px #ccc;">
				<td colspan="4" align="center" style="padding-bottom: 10px; font-family: Calibri Light;">
					<strong style="font-size: 20px"><?= $query[0]->customer_name; ?></strong>
					<br>
					<?= !empty($query[0]->phone_1) ? "({$query[0]->phone_1})" : null; ?>
				</td>
			</tr>
			<tr>
				<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: <?= $clientData->bg_color_code ?>; color: #fff;"><strong>Product</strong></td>
				<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: <?= $clientData->bg_color_code ?>; color: #fff"><strong>Quantity</strong></td>
				<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: <?= $clientData->bg_color_code ?>; color: #fff"><strong>Unit Price</strong></td>
				<td style="padding: 5px; font-family: Calibri Light; text-transform: uppercase; background: <?= $clientData->bg_color_code ?>; color: #fff"><strong>Subtotal</strong></td>
			</tr>
			<?= $rows ?>
			<?= $subTotalRow ?>
		</table>
		<table width="600px">
			<tbody style="text-align: center;">
				<tr>
					<td colspan="4">
                        <hr style="border: dashed 1px #ccc; text-align: center;">
						<img width="150px" src="<?= $config->base_url('assets/images/logo.png'); ?>"  alt="logo-small" class="logo-sm">
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<script>
		window.onload = (evt) => {
			window.print();
		}

		window.onafterprint = (evt) => {
			window.close();
		}
	</script>
</body>
</html>