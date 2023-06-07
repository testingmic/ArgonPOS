<?php
// ensure this file is being included by a parent file
if( !defined( 'SITE_URL' ) && !defined( 'SITE_DATE_FORMAT' ) ) die( 'Restricted access' );

class Payswitch extends Pos
{

    public $userId;
    private $_status = false;
    private $_message = '';
    public $call_type;

    private $pass_code = 'hellow';
    private $teller_merchant_id = "TTM-00000261";
    private $teller_username = 'gados5c4b00ab38d35';
    private $teller_api_key = 'ZDk4MDVkZjA2NjZhZDk2ZmFkM2NjYzYyMTBiMDk5M2I=';
    private $teller_endpoint = "https://test.theteller.net/checkout/initiate";


    public function __construct(){
        parent::__construct();
    }

    /**
     * A method to process payment to The Teller
     *
     * @param String $isAmount      Pass the order total amount to pay.
     * @param String $isEmail       Pass the email of customer.
     * @param String $isOrderNumber Pass the email message.
     * 
     * @return Boolean
     */
    public function initiatePayment($isAmount, $isEmail = null, $isOrderNumber)
    {
        $this->_status = '';

        // Prepare Authentication
        $username = $this->teller_username;
        $api_key  = $this->teller_api_key;
        $basic_auth_key =  'Basic ' . base64_encode($username . ':' . $api_key);

        // Convert Amount
        $amount = $isAmount * 100 /*5 * 100*/;
        $amount = str_pad($amount, 12, '0', STR_PAD_LEFT);

        // Prepare Payload To Pass To Curl
        $payload = json_encode(
            [
                "merchant_id"   => $this->teller_merchant_id, 
                "transaction_id"=> $isOrderNumber,
                "desc"          => "Payment for items on Argon POS.",
                "amount"        => $amount,
                "redirect_url"  => $this->config->base_url('callback'), 
                "email"         => (empty($isEmail)) ? "emmallob14@gmail.com" : $isEmail
            ]
        );

        $curl = curl_init();

        curl_setopt_array(
            $curl, 
            array(
                CURLOPT_URL => $this->teller_endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CAINFO => dirname(__FILE__)."\cacert.pem",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: ".$basic_auth_key,
                    "Cache-Control: no-cache",
                    "Content-Type: application/json"
                ),
            )
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $this->_status = json_decode($response, true);
        }

        return $this->_status;
    }

    public function initiateMOMOTransfer($transactionId, $acc_number, $account_issuer, $desc, $amount) {
        
        $payload = json_encode([
            "account_number"=>"$acc_number", "account_issuer"=>"$account_issuer", "merchant_id"=>$this->teller_merchant_id, "transaction_id"=>"$transactionId", "processing_code"=>"404000", "amount"=>"$amount",  "r-switch"=>"FLT", "desc"=>"$desc", "pass_code"=>"{$this->pass_code}"
        ]);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->curl_url."/v1.1/transaction/process",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CAINFO => dirname(__FILE__)."\cacert.pem",
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $payload,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic ".base64_encode("{$this->teller_username}:{$this->teller_api_key}"),
            "Cache-Control: no-cache",
            "Content-Type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        return $response;
    }
    
    public function initiateBankTransfer($transactionId, $acc_number, $acc_bank, $desc, $amount) {
        
        $payload = json_encode([
            "account_number"=>"$acc_number", "account_bank"=>"$acc_bank", "account_issuer"=>"GIP", "merchant_id"=>$this->teller_merchant_id, "transaction_id"=>"$transactionId", "processing_code"=>"404020", "amount"=>"$amount",  "r-switch"=>"FLT", "desc"=>"$desc", "pass_code"=>"{$this->pass_code}"
        ]);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->curl_url."/v1.1/transaction/process",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CAINFO => dirname(__FILE__)."\cacert.pem",
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $payload,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic ".base64_encode("{$this->teller_username}:{$this->teller_api_key}"),
            "Cache-Control: no-cache",
            "Content-Type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        return $response;
    }
    
    public function completeBankTransfer($transactionId) {
        
        $payload = json_encode([
            "merchant_id"=>$this->teller_merchant_id, "transaction_id"=>"$transactionId"
        ]);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->curl_url."/v1.1/transaction/bank/ftc/authorize",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CAINFO => dirname(__FILE__)."\cacert.pem",
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $payload,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic ".base64_encode("{$this->teller_username}:{$this->teller_api_key}"),
            "Cache-Control: no-cache",
            "Content-Type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        return $response;
    }
    
    public function verifyTransaction($transactionId) {
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->curl_url."/v1.1/users/transactions/".$transactionId."/status",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CAINFO => dirname(__FILE__)."\cacert.pem",
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Merchant-Id: ".$this->teller_merchant_id
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;

    }
    
    
    public function format_amount($amount) {
        $pre_amount = str_pad($amount, 10, '0', STR_PAD_LEFT);
        return $pre_amount."00";
    }

    public function getOrderDetails($transactionId)
    {
        $this->_status = false;

        $query = $this->getAllRows(
            "sales", "*", "transaction_id = '{$transactionId}'"
        );

        if ($query != false) {
            $this->_status = $query[0];
        }

        return $this->_status;
    }

    public function updateOrderPayment($transactionId, $status = "confirmed", $revert){

        $this->_status = false;

        $this->_status = $this->updateData("sales", "order_status = '{$status}', payment_date = now(), revert='{$revert}'", "transaction_id = '{$transactionId}'");
        
        if($status != "confirmed") {
          $this->revertTransaction();
        }

        return $this->_status;
    }
}
?>