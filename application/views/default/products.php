<?php
$PAGETITLE = "Product Details";

$productsObj = load_class("Products", "controllers");

global $accessObject, $posClass;
// create a new object for the access level
$accessObject->userId = $session->userId;

// ensure an id has been parsed
if(!confirm_url_id(0, 'products') || !confirm_url_id(1)) {
    show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// access level check
if(!$accessObject->hasAccess('inventory_branches', 'products')) {
    $productId = (int) $SITEURL[1];
    $categories = $productsObj->getCategories();
    $branchId = $session->branchId;
    $product = $productsObj->getProduct($productId, null, $branchId, "id");
} else {
    $productId = (int) $SITEURL[1];
    $categories = $productsObj->getCategories();
    $branchId = $session->branchId;
    $product = $productsObj->getProduct($productId, null, null, "id");
}

// if the customer name was found
if(!empty($product)) {
    // include the important files
    require_once "headtags.php";

    // set this product in a session
    global $clientData, $branchData;
    $session->productId = $productId;

    // products insight analitics
    $stmt = $pos->prepare("
        SELECT 
            (SELECT GROUP_CONCAT(b.order_id) FROM sales b WHERE a.order_id = b.order_id) AS order_apperances,
            CASE WHEN SUM(a.product_total) IS NULL THEN 0 ELSE SUM(a.product_total) END AS product_total,
            CASE WHEN SUM(a.product_quantity) IS NULL THEN 0 ELSE SUM(a.product_quantity) END AS product_quantity,
            CASE WHEN SUM(a.product_cost_price*a.product_quantity) IS NULL THEN 0 ELSE SUM(a.product_cost_price*a.product_quantity) END AS product_cost_price,
            CASE WHEN SUM(a.product_unit_price*a.product_quantity) IS NULL THEN 0 ELSE SUM(a.product_unit_price*a.product_quantity) END AS product_selling_price,
            CASE WHEN SUM((a.product_unit_price*a.product_quantity)-(a.product_cost_price*a.product_quantity)) IS NULL THEN 0 ELSE SUM((a.product_unit_price*a.product_quantity)-(a.product_cost_price*a.product_quantity)) END AS product_revenue,
            (SELECT COUNT(DISTINCT order_id) FROM sales_details WHERE product_id = ?) AS orders_count
        FROM  
            sales_details a
        WHERE a.product_id = ?
    ");
    $stmt->execute([$productId, $productId]);
    $productInsight = $stmt->fetch(PDO::FETCH_OBJ);

    // expiry color model
    $expiry_color = '';
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
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>inventory/inventory-details/<?= $product->branchId ?>">Inventory</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $PAGETITLE ?></a></li>
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
    
    <div class="col-lg-12">
        <div class="card">
            <?= connectionLost(); ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="<?= $config->base_url($product->product_image) ?>" alt="" class=" mx-auto  d-block" height="300">                                           
                    </div><!--end col-->
                    <div class="col-lg-6 align-self-center">
                        <div class="single-pro-detail">
                            <p class="mb-1">In Stock</p>
                            <div class="custom-border mb-3"></div>
                            <h3 class="pro-title"><?= $product->product_title ?></h3>
                            <h4><strong>Product ID</strong>: <?= $product->product_id ?> </h4>
                            <hr class="mt-0 mb-2">
                            <p class="mb-0"><strong>Stock Quantity</strong>: <?= $product->quantity ?></p>
                            <?php if($accessObject->hasAccess('inventory_branches', 'products')) { ?>
                            <h5><strong>Cost Price</strong>: <span class="text-muted"><?= $clientData->default_currency ?> </span><?= number_format(floatval($product->cost_price), 2, '.', ',') ?> </h5>
                            <?php } ?>
                            <h5><strong>Retail Price</strong>: <span class="text-muted"><?= $clientData->default_currency ?> </span><?= number_format(floatval($product->product_price), 2, '.', ',') ?> </h5>
                            <h5 class="text-muted font-13">Description :</h5> 
                            <p><?= !empty($product->product_description) ? $product->product_description : "<span class='text-muted'>No description for product</span>" ?></p>
                            <div><strong>Product Expiry Date:</strong> <span class="<?= $expiry_color ?>"><?= date("jS F, Y", strtotime($product->expiry_date)) ?></span></div>

                            <?php if(!$session->accountExpired) { ?>
                              <?php if($accessObject->hasAccess('inventory_branches', 'products')) { ?>
                              <div class="quantity mt-3 ">
                                  <button type="button" class="btn <?= $clientData->bg_color; ?> waves-effect waves-light" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-lg">
                                  <i class="mdi mdi-pencil mr-2"></i> Edit product
                              </button>
                              </div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->

  </div><!--end row-->
  <!-- end page title end breadcrumb -->
  <div class="row">
      <div class="col-lg-3">
          <div class="card card-eco">
              <div class="card-body" style="padding: 20px">
                  <div class="row">
                    <div class="col">
                      <h2 class="title-text mt-0">Orders</h2>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <h3 class="font-weight-bold"><?= $productInsight->orders_count ?> <small>Orders</small> | <?= $productInsight->product_quantity ?> <small>Quantities Sold</small></h3>
                    </div>
                  </div>                                     
                  <p class="mb-0 text-muted text-truncate"></p>
              </div><!--end card-body-->
          </div><!--end card-->
      </div><!--end col-->
      <div class="col-lg-3">
          <div class="card card-eco">
              <div class="card-body" style="padding: 20px">
                  <div class="row">
                    <div class="col">
                      <h2 class="title-text mt-0">Cost</h2>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <h3 class="font-weight-bold"><?= $clientData->default_currency ?> <?= number_format($productInsight->product_cost_price, 2) ?></h3>
                    </div>
                  </div>                                     
                  <p class="mb-0 text-muted text-truncate"></p>
              </div><!--end card-body-->
          </div><!--end card-->
      </div><!--end col-->
      <div class="col-lg-3">
          <div class="card card-eco">
              <div class="card-body" style="padding: 20px">
                  <div class="row">
                    <div class="col">
                      <h2 class="title-text mt-0">Sold</h2>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <h3 class="font-weight-bold"><?= $clientData->default_currency ?> <?= number_format($productInsight->product_selling_price, 2) ?></h3>
                    </div>
                  </div>                                     
                  <p class="mb-0 text-muted text-truncate"></p>
              </div><!--end card-body-->
          </div><!--end card-->
      </div><!--end col-->
      <div class="col-lg-3">
          <div class="card card-eco">
              <div class="card-body" style="padding: 20px">
                  <div class="row">
                    <div class="col">
                      <h2 class="title-text mt-0">Revenue</h2>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <h3 class="font-weight-bold"><?= $clientData->default_currency ?> <?= number_format($productInsight->product_revenue, 2) ?></h3>
                    </div>
                  </div>                                     
                  <p class="mb-0 text-muted text-truncate"></p>
              </div><!--end card-body-->
          </div><!--end card-->
      </div><!--end col-->
  </div><!--end row-->

  <?php if($accessObject->hasAccess('inventory_branches', 'products') && !$session->accountExpired) { ?>
  <!--  Modal content for the above example -->
  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="form-content-loader" style="display: none">
                  <div class="offline-content text-center">
                      <p><i class="fa fa-spin fa-spinner fa-3x"></i></p>
                  </div>
              </div>
              <div class="modal-header">
                  <h5 class="modal-title mt-0" id="myLargeModalLabel">Edit Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                <div class="card mb-0 p-3">
                  <form id="updateProductForm" autocomplete="Off" enctype="multipart/form-data" class="needs-validation" novalidate="" method="post" action="<?= $config->base_url('api/inventoryManagement/editProduct'); ?>">
                      <div class="row">
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="Location">Product Category</label>
                                  <select class="form-control" value="" required="" name='category'>
                                      <option disabled selected>Select category...</option>
                                      <?php
                                      array_map(function($cat) use($product){
                                          $selected = ($product->category_id == $cat->category_id) ? 'selected' : '';
                                          echo "<option value='$cat->category_id' $selected>$cat->category</option>";
                                      }, $categories);
                                      ?>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="PhoneNo">Product Title</label>
                                  <input type="text" class="form-control" placeholder="Product Title" name="title" required value="<?= $product->product_title ?>">
                              </div>
                          </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="NewOppEmail">Cost Price</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><?= $clientData->default_currency ?></span>
                                      </div>
                                      <input type="number" name="cost" class="form-control" value="<?= $product->cost_price ?>">
                                  </div>
                              </div>
                          </div> 
                      </div>
                      <div class="row">
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="NewOppEmail">Retail Price</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><?= $clientData->default_currency ?></span>
                                      </div>
                                      <input type="number" name="price" class="form-control" value="<?= $product->product_price?>">
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="NewOppEmail">Product Threshold</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text">Threshold</span>
                                      </div>
                                      <input type="number" name="threshold" class="form-control" value="<?= $product->threshold?>">
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label class="mr-2">Description</label>
                                  <textarea class="form-control" name="description"><?= strip_tags($product->product_description) ?></textarea>
                              </div>
                          </div>                                   
                      </div> 
                      <div class="row">
                          <div class="col-md-4 mb-3">
                              <img  src="<?= $config->base_url($product->image) ?>" alt="" class=" mx-auto img-responsive product-image" width="250px">
                          </div> 
                          <div class="col-md-4 mb-3">
                              <label for="product_image">Product Image</label>
                              <input type="file" name="product_image" id="product_image" class="form-control">
                              <input type="hidden" name="editProduct" value="editProduct">
                          </div>                                
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="NewOppEmail">Product Expiry</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                      </div>
                                      <input type="date" name="expiry_date" class="form-control" value="<?= $product->expiry_date?>">
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-lg-12 text-right">
                              <input type="hidden" name="productId" value="<?= $productId ?>" class="productId">
                              <input type="hidden" name="branchId" value="<?= $product->branchId; ?>">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn <?= $clientData->bg_color; ?>"><i class="fa fa-save"></i> Save</button>  
                              
                          </div>
                      </div>
                  </form> 
                </div> 
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <?php } ?>
<?php require_once 'foottags.php'; ?>
</body>
</html>
<?php } else { ?>
    <?php show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server'); ?>
<?php } ?>