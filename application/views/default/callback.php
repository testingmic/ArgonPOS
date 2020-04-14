<?php
global $config, $session;

$theTeller = load_class("Payswitch", "controllers");

$payment_successful = false;
$message = '';

// Sanitize Call Backs
$status = (isset($_GET["status"])) ? xss_clean($_GET["status"]) : null;

if (isset($_GET['status'], $_GET['code'], $_GET['transaction_id'])) {
    // Sanitize Inputs
    $code       = (isset($_GET["code"])) ? xss_clean($_GET["code"]) : '';
    $reason     = (isset($_GET["reason"])) ? xss_clean($_GET["reason"]) : '';
    $trans_id   = (isset($_GET["transaction_id"])) ? xss_clean($_GET["transaction_id"]) : '';

    // Check Sales Table For Order Checks
    if (!empty($trans_id)) {

        $saleDetail = $theTeller->getOrderDetails($trans_id);

        // Check For Order Number & Unique Token
        if ($saleDetail != false && $trans_id == $saleDetail->transaction_id) {

            // Check For Response Code
            if ($code == '000' && $status == 'approved') {

                // Update Order Payment Status
                $query = $theTeller->updateOrderPayment($trans_id, "confirmed", 0);

                if ($query == true) {
                    $payment_successful = true;
                    $session->set_userdata("_oid_LastPaymentMade", true);
                    $session->set_userdata("tellerPaymentStatus", true);
                    $message = "Payment Success";
                } else {
                    $message = "Error Encountered";
                }

            } else {
                if ($status == 'cancelled') {

                    $theTeller->updateOrderPayment($trans_id, "cancelled", 1);

                    $session->set_userdata("tellerPaymentStatus", true);

                    $payment_successful = true;
                } else {
                    $message = 'Payment was unsuccessful';
                }
            }
        } else {
            $message = "Payment order number Not Recognized";
        }
    } else {
        $message = "Payment Order Numbers Not Recognized";
    }
} else {
    $message = "Sorry! Your payment is not recognized";
}

if ($payment_successful == false) {

    $button = '<a href="'.$session->tellerUrl.'" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Try Payment Again</a>';

    show_error("Payment Unsuccessful", $message, $template = 'error_general', 500, $button);

} else {
    echo '
    <script>window.close();</script>
    ';
}