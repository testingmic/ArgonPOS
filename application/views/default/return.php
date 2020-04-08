<?php
$PAGETITLE = "Return Sale";

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
  // print "<pre>";
  // print_r($orderData);
  if(!empty($orderData)) {
    $orderData = $orderData[0];
    $found = true;
  }
}
?>
<!-- Page Content-->
<!-- Header -->
<div class="header bg-primary pb-6">
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
                                    foreach($orderData->saleItems as $eachProduct) { $i++; ?>
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
                                        <label class="btn btn-outline-light active">
                                            <input title="Apply Cash Discount" value="cash" type="radio" name="discount_type" id="discount_type_1" checked=""> Cash
                                        </label>
                                        <label class="btn btn-outline-light">
                                            <input title="Apply Percentage Discount" value="percentage" data-discount-percent="<?= number_format((($orderData->order_discount/$orderData->overall_order_amount) * 100)) ?>" type="radio" name="discount_type" id="discount_type_2"> %
                                        </label>
                                        <input title="Discount Amount / Percentage" data-toggle="tooltip" style="border-radius: 0px;width:90px" type="text" value="<?= $orderData->order_discount ?>" name="discount_amount" id="discount_amount" onkeypress="return isNumber(event);" maxlength="12" class="form-control">
                                    </div>
                                  </div>

                                </div>
                                <hr class="mt-3">
                                <h3 class="font-weight-bold text-center">Total Amount Paid</h3>
                                <button class="btn-primary btn-block btn"><h2 class="text-white"><span class="font-16"><?= $clientData->default_currency ?></span> <span class="total-to-pay-amount" data-order-total='<?= number_format($orderData->order_amount_paid,2) ?>'><?= number_format($orderData->order_amount_paid,2) ?></span></h2></button>

                              </div>

                              <div class="col-lg-5 col-md-12">
                                
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

  </div><!--end row-->
<?php require_once 'foottags.php'; ?>
<script>
  $(() => {
    hideLoader();
    <?php if($clientData->allow_product_return) { ?>
    $(`input[name="order_id_search"]`).focus();
    $(`input[name="order_id_search"]`).on('keyup', async function(e) {
      let orderId = $(this).val();

      $(`div[class~="order-details"] tbody`).addClass(`text-center`).html(`<tr><td colspan="6">No sale found with the specified id</td></tr>`);

      if(orderId.length > 12) {
        $.ajax({
          url: `${baseUrl}ajax/returnOrderProcessor/searchOrder`,
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
    $(`input[name="order_id_search"]`).prop('disabled', true).val('POS<?= time() ?>');
  <?php } ?>
  })
</script>
</body>
</html>