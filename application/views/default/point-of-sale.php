<?php
global $posClass, $session;

$PAGETITLE = "Point of Sale";
require_once "headtags.php";
$customersClass = load_class("Customers", "controllers");
$productsClass = load_class("Products", "controllers");
$products = $productsClass->all();
$categories = $productsClass->getCategories();

global $branchData, $clientData;

$openingDays = $clientData->shop_opening_days;
$validDate = true;

if(!in_array(date("l"), $posClass->stringToArray($openingDays))) {
  $validDate = false;
}
?>
<style type="text/css">
a[href="#finish"] {
    display: block !important;
}
@media (min-width: 1000px) {
  .page-content {
    height: 500px!important;
    /*overflow-y: auto!important;*/
  }
}
</style>
<!-- Page Content-->
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-3">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="javascript:void(0)"><i class="fas fa-shopping-cart"></i> <?= $PAGETITLE ?></a></li>
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
      <div class="col-lg-7">
        <div class="card">
          <?php //= (!$validDate) ? nonWorkingDay() : null; ?>
          <div class="card-body" style="padding-top: 10px; padding-bottom: 0px">
            <div class="content-loader register-form-loader" style="display: none"><i class="fa fa-3x fa-pulse fa-spinner"></i></div>
            <form id="pos-form-horizontal" class="pos-form-horizontal form-wizard-wrapper register-form" method="POST" action="">
              <h3>Customer</h3>
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <div class="text-center col-lg-12">
                        Customer
                      </div>
                      <div class="col-lg-12 text-center">
                        <select class="form-control customer-select custom-select2" name="customer">
                          <option value="WalkIn">Walk In Customer</option>
                        </select>
                      </div>
                    </div><!--end form-group-->
                  </div>
                </div>
              </fieldset><!--end fieldset-->
              <h3>Products</h3>
              <fieldset>
                <div class="row">
                  <div class="col-4 mr-auto mb-2">
                    <select class="form-control category-select">
                      <option value="all">All Products</option>
                      <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $key => $cat): ?>
                          <option value="<?= $cat->category_id ?>"><?= $cat->category ?></option>
                        <?php endforeach ?>
                      <?php endif ?>
                    </select>
                  </div>  
                  <div class="col-4 ml-auto mb-2">
                    <input type="text" id="products-search-input" class="form-control" placeholder="Search">
                  </div>  
                  <div class="col-sm-12">
                    <dv class="card shadow-none products-table-card slim-scroll" style="max-height: 330px;overflow-y: auto">
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
                </div><!--end row-->
              </fieldset><!--end fieldset-->
              <h3>Payment</h3>
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row justify-content-center">
                      <div class="col-lg-12 text-center">Payment Type</div>
                      <div class="col-lg-8 text-center">
                        <select name="payment_type" class="form-control custom-select2 payment-type-select">
                          <option value="0">--Please Select--</option>
                          <?php
                          $payments = $customersClass->getAllRows(
                            "settings",
                            "payment_options",
                            "clientId = '{$session->clientId}'"
                          );
                          if (isset($payments[0])) {
                            
                            $options_avail = explode(",", $payments[0]->payment_options);

                            foreach($options_avail as $option) {
                              if($option == "card") {
                                echo "<option value=\"{$option}\">Debit/Credit Card</option>";
                              } else {
                                echo "<option value=\"{$option}\">".ucfirst($option)."</option>";
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div>
                        <span class="payment-processing-span"></span>
                        <button type="button" class="btn btn-primary d-none make-online-payment">Pay</button>
                        <button type="button" class="btn btn-danger d-none cancel-online-payment">Cancel</button>
                      </div>

                      <div class="form-group cash-processing mt-5">
                        <hr>
                        <div class="justify-content-center row">
                          <div class="cash-process-container text-center">
                            <label for="amount_to_pay"><strong>To Pay</strong></label>
                            <input type="text" placeholder="0.00" readonly="readonly" name="amount_to_pay" id="amount_to_pay" class="form-control">
                          </div>
                          <div class="cash-process-container text-center">
                            <label for="amount_paying"><strong>Amount Paid</strong></label>
                            <input onkeypress="return isNumber(event);" type="text" name="amount_paying" id="amount_paying" style="padding: 25px; border: solid 1px #ccc; font-size: 30px" class="form-control">
                          </div>
                          <div class="cash-process-container text-center">
                            <label for="amount_balance"><strong>Balance</strong></label>
                            <input type="text" placeholder="0.00" readonly="readonly" name="amount_balance" id="amount_balance" class="form-control">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!--end row-->
              </fieldset><!--end fieldset-->
              <h3>Complete</h3>
              <fieldset>
                <div class="p-3">
                  <div class="card-border border-success bg-soft-success card m-auto text-center" style="width: 100%">
                    <div class="card-body">
                      <h5 data-bind-html="customer" class="text-center font-weight-bold"></h5>
                      <span class="font-12"><?= $branchData->branch_name ?></span>
                      <hr style="border:none;border-bottom: 1px dashed #ccc">
                      <div><strong>Order #<span class="generated_order"></span></strong></div>
                      <hr style="border:none;border-bottom: 1px dashed #ccc" class="mb-3">
                      <table class="mt-5 table text-left">
                        <thead>
                          <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th class="text-right">Subtotal</th>
                          </tr>
                        </thead>
                        <tbody data-bind-html="productrow" class="receipt-table-body">
                        </tbody>
                        <tfoot>
                          <tr><td></td></tr>
                          <tr style="border:none;border-top: 2px solid rgba(0,0,0,0.2)">
                            <td></td>
                            <td class="font-weight-bold">Sub Total</td>
                            <th class="text-right font-weight-bold font-18" data-bind-html='totaltopay'>0.00</th>
                          </tr>
                          <tr style="border:none;border-top: 2px solid rgba(0,0,0,0.2)">
                            <td></td>
                            <td>Discount</td>
                            <th class="text-right" data-bind-html='discount_amount'>0.00</th>
                          </tr>
                          <tr style="border:none;border-top: 2px solid rgba(0,0,0,0.2)">
                            <td></td>
                            <td>Amount to Pay</td>
                            <th class="text-right font-weight-bold font-18" data-bind-html='payment'>0.00</th>
                          </tr>
                          <tr style="border:none;border-top: 2px solid rgba(0,0,0,0.2)">
                            <th></th>
                            <td class="font-weight-bold">Amount Paid</td>
                            <th class="text-right font-weight-bold" data-bind-html='amount_paid'>0.00</th>
                          </tr>
                          <tr style="border-top: 0; border-bottom: 2px solid #ccc">
                            <th></th>
                            <td>Balance</td>
                            <th class="text-right font-weight-bold font-18" data-bind-html='balance'>0.00</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="form-group mt-5">
                    <label class="font-weight-bold my-2">Email Receipt</label>
                    <div class="input-group">
                      <input type="email" name="email" id="receipt-email" placeholder="Email Address" class="form-control">
                      <div class="input-group-addon">
                        <button type="button" class="btn send-email btn-primary">Send</button>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset><!--end fieldset-->
            </form><!--end form-->
            <div class="custom-steps-actions py-3 row justify-content-<?= ($validDate) ? "between" : "end" ?>">
              <?php if($validDate) { ?>
              <button type="button" data-toggle="modal" data-target="#newCustomerModal" class="btn mb-2 btn-sm btn-primary newCustomer_trigger"><i class="fa fa-user"></i> New Customer</button>
              <button type="button" data-toggle="modal" data-target="#discardModal" class="btn mb-2 btn-danger discardSale_trigger">Discard</button>
              <button class="btn btn-primary print-receipt" type="button"><i class="fa fa-print"></i> Print Receipt</button>
              <?php } ?>
              <div class="float-right">
                <button type="button" class="btn mb-2 btn-primary" data-step-action="previous">Previous</button>
                <button type="button" class="btn mb-2 btn-primary" data-step-action="next">Next</button>
                <button type="button" class="btn mb-2 btn-primary" data-step-action="finish">Finish</button>
              </div>
            </div>
          </div><!--end card-body-->
        </div><!--end card-->
      </div><!--end col-->
      <div class="col-lg-5">
        <div class="card">
          <?php //= (!$validDate) ? nonWorkingDay() : null; ?>
          <div class="card-body register-build">
            <div class="content-loader register-form-loader" style="display: none"><i class="fa fa-3x fa-pulse fa-spinner"></i></div>
            <h5 class="text-center text-default">Customer</h5>
            <h6 class="text-center text-muted selected-customer-name">No Customer Selected</h6>
            <div class="slim-scroll" style="max-height: 325px;overflow-y: auto">
              <table class="table" id="product-select-table" style="min-height: 250px">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th width="10%">Quantity</th>
                    <th>Subtotal</th>
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
            <div class="row payment_type_div mt-3 justify-content-between">
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
                    <input title="Discount Amount / Percentage" data-toggle="tooltip" style="border-radius: 0px;width:90px" type="text" name="discount_amount" id="discount_amount" onkeypress="return isNumber(event);" maxlength="12" class="form-control">
                </div>                                
              </div>
            </div>

            <h6 class="font-weight-bold text-center mt-3">Total To Pay</h6>
            <button class="btn-primary btn-block btn"><h3 class="text-white"><span class="font-16">GHC</span> <span class="total-to-pay-amount" data-order-total='0'>0.00</span></h3></button>
          </div>
        </div>
      </div>
      <div class="row"></div>
  </div>
<?php if($validDate) { ?>
<!-- end page content -->
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
            <div class="modal-footer">
                <button type="submit" form="newCustomer_form" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade discardModal" tabindex="-1" id="discardModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="exampleModalLabel">Discard Sale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mb-0 p-3">
                    <div class="form-group mb-3 details-pane">
                        <p>Are you sure you want to discard this sale transaction?</p>
                    </div><!--end form-group-->
                    <div class="form-group mb-3 text-right">
                      <a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="btn btn-danger">Cancel</a>
                      <a href="javascript:void(0)" class="discard-sale btn btn-success">Yes Confirm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="payment_check" data-value="1"></div>
<?php require_once 'foottags.php'; ?>
<script src="<?= $baseUrl ?>assets/vendor/jquery-steps/jquery.steps.min.js"></script>
<script>
  $(function() {
    hideLoader();
  });
</script>
<?php if($validDate) { ?>
<script src="<?= $config->base_url('assets/js/pos.js'); ?>" type="text/javascript"></script>
<?php } else { ?>
<script type="text/javascript">
$("#pos-form-horizontal").steps({
  headerTag: "h3",
    bodyTag: "fieldset",
    transitionEffect: "slide",
    enablePagination: false
});
var initialiteProductSelect = () => {
    $(".product-select").on("change", async function(){
      if($(this).is(":checked")) {
        await addProductRow($(this).data())
        .then((row) => {
          recalculateTotalToPay();
          amountPaying();
          let subTotalBox = $(".row-subtotal", $(`tr.products-row[data-row-id='${row.productId}']`));
          let receipt_subTotal = $(".receipt-row-subtotal", $(`tr.receipt-product-row[data-row-id='${row.productId}']`));
          let receipt_qty = $(".receipt-row-quantity", $(`tr.receipt-product-row[data-row-id='${row.productId}']`));
          $(`.product-quantity[data-row='${row.productId}']`).on("input", function(){
            let currentInput = $(this);
            let selectedQty = currentInput.val().length ? parseInt(currentInput.val()) : 0;
            let maximumQty = parseInt(currentInput.attr('data-max'));
            let productName = currentInput.attr('data-name');

            if(selectedQty < 1) { 
              selectedQty = 1;
              currentInput.val(1); 
            }
            let subtotal = (productPrices[row.productId]*selectedQty).toFixed(2);
            subTotalBox.text(subtotal);
            receipt_qty.text(selectedQty);
            receipt_subTotal.text(formatCurrency(subtotal));
            recalculateTotalToPay();
            amountPaying();
            
            if(selectedQty > maximumQty) {
              currentInput.val(maximumQty);
              Toast.fire({
                type: 'error',
                title: `Sorry. Maximum number of available ${productName} is ${maximumQty}`
              });
            }
          })
          $(`.remove-row[data-row='${row.productId}']`).on("click", function(){
            removeProductRow(row.productId);
            $(`.product-select[data-product-id='${row.productId}']`).prop({"checked": false})
          })
          $(".print-receipt").on("click", printReceipt)
        })
      }
      else removeProductRow($(this).data("productId"))
    });
}
</script>
<?php } ?>
</body>
</html>