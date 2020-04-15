<?php
$acceptedRequests = ["quote", "order"];

global $admin_user, $accessObject;

$accessObject->userId = $session->userId;

// ensure that the user is logged in
if(!$admin_user->logged_InControlled()) {
    include_once "login.php";
    exit;
}

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// confirm that the correct url has been used
if(!confirm_url_id(1) || (isset($SITEURL[1]) && !in_array($SITEURL[1], $acceptedRequests))) {
    show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// ensure the user has the required permissions
if(!$accessObject->hasAccess('view', 'quotes') && !$accessObject->hasAccess('view', 'orders')) {
    show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// set the page title
$PAGETITLE = ucfirst($SITEURL[1])."s";

$newItem = "New ".ucfirst($SITEURL[1]);
$tableClass = ucfirst($SITEURL[1])."sList";
$sessionName = ucfirst($SITEURL[1])."sList";
$modalWindow = strtolower($SITEURL[1])."sModalWindow";

// include the important files
require_once "headtags.php";

// set the session
if(isset($_SESSION[$sessionName])) {
    unset($_SESSION[$sessionName]);
}

$session->set_userdata('thisRequest', $sessionName);

$productsClass = load_class("Products", "controllers");
$categories = $productsClass->getCategories();
?>
<!-- Page Content-->
<!-- Header -->
<div class="header <?= $clientData->bg_color ?> pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>"><i class="fas fa-home"></i> Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?><?= strtolower($PAGETITLE) ?>"><?= $PAGETITLE ?></a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)">New <?= substr($PAGETITLE, 0, -1) ?></a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">
        <span class="hide-walk-in-customer" data-request="<?= $sessionName ?>"></span>
        <div class="col-lg-5">
            <div class="card">
                <?= form_loader(); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9 col-md-10">
                            <label for="customer_name"><strong>Name of Customer</strong></label><br>
                            <div class="text-center">
                                <select class="form-control customer-select selectpicker" name="customer">
                                    <option value="null">-- Select Customer --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-2">
                            <label for="quick-add-customer">&nbsp;</label><br>
                            <button data-toggle="modal" data-target="#newCustomerModal" class="btn <?=  $clientData->btn_outline; ?> btn-block quick-add-customer"><i class="fa fa-plus"></i> New</button>
                        </div>                                      
                    </div>                                               
                </div><!--end card-body-->
                <div class="card-body pt-0">
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <label for="category-select"><strong>Products List</strong></label>
                        </div>
                        <div class="col-lg-5 mr-auto mb-2">
                            <select class="form-control category-select selectpicker">
                                <option value="all">All Products</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $key => $cat): ?>
                                        <option value="<?= $cat->category_id ?>"><?= $cat->category ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>  
                        <div class="col-lg-5 ml-auto mb-2">
                            <input type="text" id="products-search-input" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-sm-12 col-lg-12">
                            <dv class="card shadow-none products-table-card slim-scroll" style="max-height: 350px;overflow-y: auto">
                                <div class="card-body">
                                    <table class="table datatables" id="products-table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th colspan="2">Product</th>
                                                <th style="white-space: nowrap">Unit Price</th>
                                            </tr>
                                        </thead>
                                        <tbody class="pos-products-list"></tbody>
                                    </table>
                                </div>
                            </dv>
                        </div>

                    </div>
                </div><!--end card-body--> 
            </div><!--end card--> 
        </div><!--end col--> 

        <div class="col-lg-7">
            <div class="row">
                <div class="col-12">
                    <div class="tab-content detail-list" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="sales_details">
                        <div class="card">
                                <?= form_loader(); ?>
                          <div class="card-body request-form">
                                    <div style="margin: 0px" class="row justify-content-between mb-3">
                                        <div>
                                            <h4 class="header-title mt-0 mb-3"><strong><?= $PAGETITLE; ?> Product Details</strong></h4>
                                        </div>
                                        <div class="save-div ">
                                            <button data-request="save-reload" type="button" class="btn save-request <?=  $clientData->btn_outline; ?>"><i class="fa fa-save"></i> Save Record</button>

                                            <button data-request="save-invoice" type="button" class="btn save-request btn-outline-primary"><i class="fa fa-file-pdf"></i> Save & Download</button>
                                        </div>
                                    </div>
                                    <div class="slim-scroll" style="max-height: 275px;min-height: 275px;overflow-x:hidden;overflow-y: auto">
                                        <div class="row">
                                            <table class="table" id="product-select-table" style="min-height: 200px">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">#</th>
                                                        <th>Product</th>
                                                        <th>Unit Price</th>
                                                        <th width="13%">Quantity</th>
                                                        <th>Total</th>
                                                        <th width="1"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="products-table-body">
                                                    <tr class="empty-message-row">
                                                        <td colspan="6">
                                                            <h5 class="text-center">No Products Added</h5>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row payment_type_div mt-5 justify-content-between">
                                        <div class="col-lg-6 col-md-5"><br>
                                            <div title="Subtotal of Products" data-toggle="tooltip">
                                                Subtotal: <br><span class="sub_total" id="sub_total">GH&cent; 0.00</span>
                                            </div>
                                        </div>
                                        <div class="order_discounting col-lg-6 col-md-6 text-right">
                                            <label for="discount_amount">Discount:</label><br>
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-light active">
                                                    <input title="Apply Cash Discount" value="cash" type="radio" name="discount_type" id="discount_type_1" checked=""> Cash
                                                </label>
                                                <label class="btn btn-outline-light">
                                                    <input title="Apply Percentage Discount" value="percentage" type="radio" name="discount_type" id="discount_type_2"> %
                                                </label>
                                                <input title="Discount Amount / Percentage" data-toggle="tooltip" style="border-radius: 0px;width:90px" type="text" name="discount_amount" id="discount_amount" onkeypress="return isNumber(event);" maxlength="12" class="form-control text-center">
                                            </div>                                
                                        </div>
                                    </div>

                                    <h6 class="font-weight-bold text-center mt-3"><?= strtoupper($SITEURL[1]) ?> TOTAL</h6>
                                    <button class="<?= $clientData->bg_color; ?> btn-block btn"><h3 class="text-white"><span class="font-16"><?= $clientData->default_currency ?></span> <span class="total-to-pay-amount" data-order-total='0'>0.00</span></h3></button>
                                  </div>
                            </div><!--end row-->                                             
                        </div><!--end general detail-->                        
                    </div><!--end tab-content-->                     
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end col-->                        
    </div><!-- End row -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="content-loader"><i class="fa fa-spinner fa-pulse fa-3x"></i></div>
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">New Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mb-0 p-3">
                    <form autocomplete="Off" class="form py-2" id="newCustomer_form">
                        <div class="form-row mb-2">
                            <div class="form-group col-md-2">
                                <label for="inputState">Title</label>
                                <select id="newCustomer_title" name="nc_title" class="form-control">
                                    <option value="Mr">Mr.</option>
                                    <option value="Mrs">Mrs.</option>
                                    <option  value="Dr">Dr.</option>
                                    <option  value="Miss">Miss.</option>
                                    <option value="Prof">Prof.</option>
                                    <option value="Hon">Hon.</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="newCustomer_firstname">First Name</label>
                                <input type="text" class="form-control" name="nc_firstname" id="newCustomer_firstname" placeholder="First Name">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="newCustomer_lastname">Last Name</label>
                                <input type="text" class="form-control" name="nc_lastname" id="newCustomer_lastname" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="newCustomer_primarycontact">Primary Phone No.</label>
                                <input type="text" class="form-control" name="nc_contact" id="newCustomer_primarycontact">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="newCustomer_seccontact">Email Address</label>
                                <input type="text" class="form-control" name="nc_email" id="newCustomer_seccontact">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="newCustomer_form" class="btn <?=  $clientData->btn_outline; ?>"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">var sessionName = '<?= $sessionName ?>';</script>
<?php require_once 'foottags.php'; ?>
</body>
</html>