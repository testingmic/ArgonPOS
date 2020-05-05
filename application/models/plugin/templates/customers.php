<?php
// check if the user has already validated their Api Key
ob_start();
$verifiedKey = $this->verifyKey();
?>
<?= $this->woocommerce_check; ?>
<div class="evelyn-wrapper">
	<h2>Customers<?= !empty($this->user_data) ? ': '.$this->user_data['message']['client_name'] : null ?></h2>
	<hr>
	<div class="tabset">


	</div>

</div>