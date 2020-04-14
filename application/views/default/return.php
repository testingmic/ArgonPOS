<?php
$PAGETITLE = "Return Sale";

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// include the important files
require_once "headtags.php";

//: initializing
$orderId = null;
$found = false;

//: check if an id has been parsed
if(confirm_url_id(1)) {
  
  //: assign the order id
  $orderId = xss_clean($SITEURL[1]);

  //: create a new object
  $orderObj = load_class('Orders', 'controllers');

  //: load the data
  $orderData = $orderObj->saleDetails($orderId);
  
  //: if the order data is not empty
  if(!empty($orderData)) {
    $found = true;
    $orderData = $orderData[0];
    $session->returnOrderId = $orderId;
  }
}
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
              <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $PAGETITLE ?></a></li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="<?= $baseUrl ?>point-of-sale" class="btn btn-sm btn-neutral"><i class="fa fa-shopping-cart"></i> New Sale</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">

    <div class="col-lg-12 col-sm-12 customersList">
      <div class="card">
        <div class="card-body">
          
          <div class="row justify-content-center">

            <div class="col-lg-6">
              <div class="form-group text-center">
                <label for="order_id_search">Type the Order ID to Return</label>
                <input style="font-size: 25px;" <?= ($found) ? "disabled" : null; ?> type="text" value="<?= $orderId ?>" maxlength="16" placeholder="" name="order_id_search" id="order_id_search" class="text-center text-uppercase form-control">
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  
                  <div class="order-details">

                    <?php if(!$found) { ?>
                      <?php if($clientData->allow_product_return) { ?>
                        <div class="table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <th>Customer Details</th>
                              <th>Order ID</th>
                              <th>Order Total</th>
                              <th>Amount Paid</th>
                              <th>Order Date</th>
                              <th></th>
                            </thead>
                            <tbody>
                              <tr><td colspan="6">No sale found with the specified id</td></tr>
                            </tbody>
                          </table>
                        </div>
                      <?php } else { ?>
                        <div class="alert alert-warning text-center">
                          <h1 class="text-white">SOLD PRODUCTS ARE NOT RETURNABLE</h1>
                        </div>
                      <?php } ?>
                    <?php } else { ?>
                      <div class="row">

                        <div class="col-lg-7 col-md-12 table-responsive">
                          <h3 class="text-center">Sale Items List</h3>
                          <table class="table table-bordered">
                            <thead>
                              <th>#</th>
                              <th>Product</th>
                              <th>Unit Price</th>
                              <th>Quantity</th>
                              <th>Subtotal</th>
                              <th></th>
                            </thead>
                            <tbody>
                              <?php 
                              $i = 0;
                              $productsQuantity = 0;
                              foreach($orderData->saleItems as $eachProduct) { 
                                $i++;
                                $productsQuantity += $eachProduct->product_quantity;
                              ?>
                                <tr data-row="<?= $eachProduct->product_id ?>">
                                  <td><?= $i ?></td>
                                  <td><?= $eachProduct->product_title ?></td>
                                  <td><?= $eachProduct->product_unit_price ?></td>
                                  <td><input type="number" min="0" max="<?= $eachProduct->product_quantity ?>" value="<?= $eachProduct->product_quantity ?>" class="form-control text-center" onkeypress="return isNumber(event);" name="product_quantity_<?= $eachProduct->product_id ?>"></td>
                                  <td><span><?= $eachProduct->product_total ?></span></td>
                                  <td><button class="btn btn-sm btn-outline-danger" data-row="<?= $eachProduct->product_id ?>"><i class="fa fa-trash"></i></button></td>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                          <div class="row payment_type_div mt-3 justify-content-between">
                            <div class="col-lg-6 col-md-5"><br>
                              <div>
                                Subtotal: <br><span class="sub_total" id="sub_total"><?= $clientData->default_currency ?> <?= number_format($orderData->overall_order_amount,2) ?></span>
                              </div>
                            </div>

                            <div class="order_discounting text-right">
                              <label for="discount_amount">Discount:</label><br>
                              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                  <?php if($productsQuantity != 1) { ?>
                                  <label class="btn btn-outline-light active">
                                      <input title="Apply Cash Discount" value="cash" type="radio" name="discount_type" id="discount_type_1" checked=""> Cash
                                  </label>
                                  <label class="btn btn-outline-light">
                                      <input title="Apply Percentage Discount" value="percentage" data-discount-percent="<?= number_format((($orderData->order_discount/$orderData->overall_order_amount) * 100)) ?>" type="radio" name="discount_type" id="discount_type_2"> %
                                  </label>
                                  <?php } ?>
                                  <input title="Discount Amount / Percentage" data-toggle="tooltip" <?= ($productsQuantity == 1) ? "disabled" : null ?> style="border-radius: 0px;width:90px" type="text" value="<?= $orderData->order_discount ?>" name="discount_amount" id="discount_amount" onkeypress="return isNumber(event);" maxlength="12" class="form-control">
                              </div>
                            </div>

                          </div>
                          <hr class="mt-3">
                          <h3 class="font-weight-bold text-center">Total Amount Paid</h3>
                          <button class="<?=  $clientData->bg_color; ?> btn-block btn"><h2 class="text-white"><span class="font-16"><?= $clientData->default_currency ?></span> <span class="total-to-pay-amount" data-order-total='<?= number_format($orderData->order_amount_paid,2) ?>'><?= number_format($orderData->order_amount_paid, 2) ?></span></h2></button>

                        </div>

                        <div class="col-lg-5 col-md-12">
                          <div class="">
                            
                          </div>
                          <div style="display: flex;flex-direction: row;width: 100%;justify-content: space-between;">
                            <div>

                              <?php if($productsQuantity == 1) { ?>

                               <?php } ?> 

                            </div>
                            <div>
                              <button data-order-id="<?= $orderId ?>" class="btn <?=  $clientData->btn_outline; ?> return-all">
                                <i class="fa fa-reply"></i> Return All
                              </button>
                            </div>
                          </div>

                        </div>

                      </div>
                    <?php } ?>

                  </div>

                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="modal fade returnModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="form-content-loader" style="display: none;">
            <div class="offline-content text-center">
                <p><i class="fa fa-spin fa-spinner fa-3x"></i></p>
            </div>
          </div>
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">
              Return All Products
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body text-center" style="min-height: 200px; padding-top: 0px">
            Hello user, i understand you want to return a product. Don't worry this feature will be added pretty soon.
          </div>
        </div>
      </div>
    </div>

  </div><!--end row-->
<?php require_once 'foottags.php'; ?>
<script>
  <?php if($clientData->allow_product_return) { ?>
  <?php if(!$found) { ?>
  $(`input[name="order_id_search"]`).focus();
  $(`input[name="order_id_search"]`).on('keyup', async function(e) {
    let orderId = $(this).val();
    $(`div[class~="order-details"] tbody`).html(`<tr><td colspan="6">No sale found with the specified id</td></tr>`);
    if(orderId.length > 12) {
      $.ajax({
        url: `${baseUrl}api/returnOrderProcessor/searchOrder`,
        data: {searchOrder:true, orderId},
        dataType: `json`,
        type: `POST`,
        success: function(resp) {
          if(resp.count > 0) {
            var tData = ``;
            $.each(resp.orderDetails, function(i, e) {
              tData += `<tr data-content='${JSON.stringify(e)}' data-row="${e.order_id}">`;
              tData += `<td>${e.customer_fullname}<br>${e.customer_contact}</td>`;
              tData += `<td><strong data-toggle="tooltip" title="Click to return sale" data-return-id="${e.order_id}" class="cursor"><a href="${baseUrl}return/${e.order_id}" class="btn btn-sm btn-success">${e.order_id}</a></strong></td>`;
              tData += `<td>${e.overall_order_amount}</td>`;
              tData += `<td>${e.order_amount_paid}</td>`;
              tData += `<td>${e.order_date}</td>`;
              tData += `<td><a href="${baseUrl}return/${e.order_id}" data-toggle="tooltip" title="Click to return sale" class="btn btn-sm btn-success"><i class="fa fa-reply"></i></a></td>`;
              tData += `</tr>`;
            });
            $(`div[class~="order-details"] tbody`).html(tData);
          }
        }, complete: function(data) {
          $(`[data-toggle="tooltip"]`).tooltip();
        }
      });
    }
  });
  <?php } else { ?>
    <?php if($productsQuantity == 1) { ?>
      $(`button[class~="btn-outline-danger"]`).prop('disabled', true);
      $(`input[name^="product_quantity_"]`).prop('disabled', true);
    <?php } ?>
    $(`button[class~="return-all"]`).on('click', function() {
      $(`div[class~="returnModal"]`).modal('show');
    });
  <?php } ?>
  <?php } else { ?>
    $(`input[name="order_id_search"]`).prop('disabled', true).val('POS<?= time() ?>');
  <?php } ?>
</script>
</body>
</html>