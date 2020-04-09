<?php
$PAGETITLE = "Customer Purchases Report";

global $accessObject;

// set the where
$where = '';
$branchId = '';

$accessObject->userId = $session->userId;

// use the access level for limit contents that is displayed
if(!$accessObject->hasAccess('monitoring', 'branches')) {
    $where = "AND customers.branchId = '{$session->branchId}'";
}
// confirm if a customer id has been parsed
if(confirm_url_id(1)) {
    
    // assign a variable to the customer id
    $customerId = xss_clean($SITEURL[1]);

    // save this customer id in a session
    $session->set_userdata('reportingCustomerId', $customerId);

    $stmt = $pos->prepare("
        SELECT customers.id, customers.customer_id, 
            CONCAT(customers.firstname, ' ', customers.lastname) AS fullname, 
            customers.email AS cemail, customers.phone_1 AS cphone_1, customers.phone_2 AS cphone_2, 
            customers.user_type, users.name AS sales_fullname, customers.branchId
        FROM customers
        LEFT JOIN users ON users.user_id=customers.owner_id
        WHERE 
            customers.status='1' AND customers.customer_id = ? AND 
            customers.clientId = ? {$where}
    ");
    $stmt->execute([$customerId, $session->clientId]);

    $customerDetails = $stmt->fetch(PDO::FETCH_OBJ);
}
// if the customer name was found
if(isset($customerDetails->fullname)) {
    // include the important files
    require_once "headtags.php";
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>"><i class="fas fa-home"></i> Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>analytics"><i class="fa fa-chart-bar"></i> Analytics</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $PAGETITLE ?></a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once "foottags.php" ?>
<script>
$(function() {
    cusPurHis();
    $(`select[name="periodSelect"]`).on('change', function() {
        let periodSelected = $(this).val();
        cusPurHis(periodSelected);
    });

    $(`select[name="employeeId"`).on('change', function() {
        window.location.href = `${baseUrl}reports/`+$(this).val();
    });
});
</script>
</body>
</html>
<?php } else { ?>
    <?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server'); ?>
<?php } ?>