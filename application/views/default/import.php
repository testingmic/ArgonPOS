<?php
// initializing
$PAGETITLE = "Import Data";

// call the global variable
global $accessObject, $config, $posClass;

// if expired then exit the page
if($session->accountExpired) {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
    exit;
}

// create a new object for the access level
$accessObject->userId = $session->userId;

// run this page if the user has the required permissions
if($accessObject->hasAccess('update', 'settings')) {

// data initializing
$baseUrl = $config->base_url();
$currentData = null;
$valid = false;
$branchId = null;

// set the valid requests that can be made
$validRequests = [
    "customer" => [
        "fa fa-users",
        "Import Customers List in Bulk"
    ],
    "product" => [
        "fa fa-shopping-cart",
        "Import Products List in Bulk"
    ],
    "user" => [
        "fa fa-user",
        "Import Admin Users into the Database"
    ]
];

// confirm the dataset that the user is requesting
if(confirm_url_id(1)) {
    // assign the variable
    $currentData = strtolower(xss_clean($SITEURL[1]));
    // confirm that it is a valid request
    if(in_array($currentData, array_keys($validRequests))) {
        // validate
        $valid = true;
        $branchId = $session->curBranchId;
    }
}

// run this section if valid
if($valid) {

    // columns to use for the query
    if($currentData == "customer") {
        // accepted column names
        $acpCols = [
            "data" => [ 
                "Customer Code", "Title", "Firstname",  "Lastname", "Contact Number", "Email Address", "Date of Birth", "Residence", "City", "Gender"
            ], "instructions" => [
                "The <strong>Date of Birth</strong> should be in the format <strong>YYYY-MM-DD</strong> eg 1990-10-03"
            ]
        ];
    } elseif($currentData == "product") {
        // accepted column names for the products
        $acpCols = [
             "data" => [
                "Product Code", "Product Category", "Product Title", 
                "Product Description", "Retail Price", "Product Cost Price",
                "Product Threshold", "Product Quantity", "Expiry Date"
            ], "instructions" => [
                "The <strong>Product Categories</strong> can be added under <strong><a href='{$baseUrl}settings/prd'>Settings</a></strong>. Hence Set the <strong>Category ID</strong> here only.",
                "The <strong>threshold</strong> is the quantity at which you would want to receive an alert of <strong>shortage</strong> for this particular product.",
                "The <strong>Expiry Date</strong> should be in the format <strong>YYYY-MM-DD</strong> eg ".date("Y-m-d", strtotime("today +6 month"))
            ]
        ];
    } elseif($currentData == "user") {
        // accepted column names for the products
        $acpCols = [
            "data" => [ 
                "User ID", "Fullname", "Gender", "Email Address", 
                "Contact Number", "username","Password"
            ], "instructions" => [
                "The <strong>Access Levels</strong> can be set under the <a href='{$baseUrl}users'>Users Section</a> of the Portal once the upload has been made.",
                "The <strong>User ID</strong> and <strong>Email Address</strong> should be Unique. A duplicate will result in the inability of the user to login to the system.",
                "The Password must be plain text but can be alphanumerics and can also include special characters.",
                "Gender should either be <strong>Male</strong> or <strong>Female</strong>."
            ]
        ];
    }

}

// include the important files
require_once "headtags.php";
?>
<!-- Page Content-->
<?= connectionLost(); ?>
<div class="header <?= $clientData->bg_color ?> pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>"><i class="fas fa-home"></i> Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/settings"><i class="ni ni-ungroup"></i> Settings</a></li>
              <li class="breadcrumb-item"><a href="<?= $config->base_url('import') ?>">Import Data</a></li>
              <?php if($valid) { ?>
              <li class="breadcrumb-item active"><?= ucfirst($currentData); ?></li>
              <?php } ?>
            </ol>
          </nav>
        </div>
        <?php if($accessObject->hasAccess('category_add', 'products')) { ?>
        <div class="col-lg-6 col-5 text-right">
          <a href="javascript:void(0)" class="btn btn-sm add-category btn-neutral"><i class="fa fa-plus"></i> New Product Type</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  
  <div class="row">
      
      <div class="col-lg-12 col-sm-12">
          
          <div class="card-body">
            <?php if($valid && !empty($session->curBranchId)) { ?>
            <form method="post" action="<?= $config->base_url('aj/importManager/loadCSV'); ?>" class="csvDataImportForm" enctype="multipart/form-data">
                <div class="row">
                    <div id="dropify-space" class="col-md-8  mt-5 pb-4 text-center m-auto border bg-white pt-4 border-white">
                        <?= form_loader(); ?>
                        <h2>Upload a CSV to import <strong><?= ucfirst($currentData); ?>s data</strong>, to the <strong><?= $posClass->quickData("branch_name", "branches", "id='{$session->curBranchId}'") ?></strong> <small><a href="javascript:void(0)" data-branch-id="<?= $session->curBranchId ?>" data-href="<?= $baseUrl ?>import/<?= $currentData ?>" class="change-branch text-success">Change Branch?</a></small></h2>
                        <hr>
                        <a href="javascript:void(0)" class="read-instructions">Read Instructions</a>
                        <div class="form-controls col-md-4 m-auto">
                            <hr>
                            <div class="form-group text-center">
                                <input style="height: 50px; line-height: 25px" accept=".csv" type="file" name="csv_file" id="csv_file" class="form-control btn <?= $clientData->bg_color; ?> text-white cursor">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php } else { ?>

            <div class="row justify-content-center">
            <?php foreach($validRequests as $tKey => $tValue) { ?>
            <div class="col-lg-3 col-md-4">
                <div class="card">
                    <a title="<?= $tValue[1] ?>" data-toggle="tooltip" data-href="<?= $config->base_url("import/{$tKey}"); ?>" href="javascript:void(0)" class="select-branch">
                        <div class="card-body import-cards">
                            <div class="float-right text-right">
                                <i class="<?= $tValue[0]; ?> report-main-icon"></i><br>
                                <strong></strong>
                            </div> 
                            <h5 class="my-3 total-sales"><?= ucfirst($tKey) ?></h5>
                            <p class="mb-0 text-muted text-truncate total-sales-trend">
                                <?= $tValue[1] ?>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <?php } ?>
            </div>
            <?php } ?>

            <?php if($valid) { ?>
              <div class="row justify-content-center p-4 text-center">
                  <div class="col-lg-8 p-3 upload-text bg-white hidden">
                      <h2>Upload <strong><?= ucfirst($currentData); ?>s</strong>, to the <strong><?= $posClass->quickData("branch_name", "branches", "id='{$session->curBranchId}'") ?></strong> <small><a href="javascript:void(0)" data-branch-id="<?= $session->curBranchId ?>" data-href="<?= $baseUrl ?>import/<?= $currentData ?>" class="change-branch text-success">Change Branch?</a></small> or <small class="text-success"><a class="read-instructions" href="javascript:void(0);">Read Instructions?</a></small>
                          <hr>
                      </h2>
                      <div class="csv-rows-counter text-success font-16"></div>
                  </div>
                  <div class="col-lg-8 bg-white file-checker"></div>
              </div>                
              <div class="mt-4 csv-rows-content border slim-scroll" style="overflow-x: auto;display: flex; flex: 1; padding-top: 20px; flex-direction: row; max-height: 450px; background: none;">
                  <div class="col-md-4" style="display: none;" data-row="1">
                      <div class="form-row">
                          <select class="form-control">
                              <option value="null">Please Select</option>
                              <?php foreach($acpCols["data"] as $opts) { ?>
                                  <?= "<option value=\"{$opts}\">".ucfirst($opts)."</option>" ?>
                              <?php } ?>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row mt-4 upload-buttons">
                  <div class="col-lg-12 text-center">
                      <button type="cancel" class="btn hidden cancel-button btn-outline-danger">
                          Cancel Upload
                      </button>
                      <button style="display: none;" type="submit" class="btn upload-button btn-outline-success">
                          <i class="fa fa-upload"></i> Continue Data Import
                      </button>
                  </div>
              </div>
              <?php } ?>
          </div>


      </div>     

  </div><!--end row-->
<div class="modal fade importModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <?= form_loader(); ?>
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Branch Selection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body row justify-content-center">
                <?php 
                // query for the list of branches
                $branchesList = $pos->prepare("
                    SELECT * 
                    FROM branches 
                    WHERE clientId = ? AND deleted=? ORDER BY id
                ");
                if ($branchesList->execute([$session->clientId, 0])) {
                    $i = 0;
                    // count the number of rows found
                    if($branchesList->rowCount() > 0) {
                        // loop through the list of branches
                        while ($data = $branchesList->fetch(PDO::FETCH_OBJ)) {
                            $i++;
                            $branch_type = "<span class='".(($data->branch_type == 'Store') ? "text-white badge-primary" : "badge-info")."'>{$data->branch_type}</span>";
                            ?>
                            <div data-branch-id="<?= $data->id ?>" data-toggle="tooltip" class="col-lg-6 col-md-6 complete-branch-selection" title="Choose to import data to the <?= $data->branch_name ?>">
                                <div class="card">
                                    <div <?= ($session->curBranchId == $data->id) ? "style='border: solid 1px {$clientData->bg_color_code}'" : null; ?> class="card text-center branch-select">
                                        <h3><?= $data->branch_name ?></h3>
                                        <?= $branch_type ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // print error message
                        ?>
                        <div class="col-lg-8 col-md-8">
                            <div class="card">
                                <div class="card-body text-center branch-select">
                                    <h3>No Available Branches</h3>
                                    <p>Please <a href="<?= $baseUrl ?>branches"><strong>Click Here</strong></a> to add a new Branch to your Store.</p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="redirection-href" data-href></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php if($valid) { ?>
  <div class="modal fade instructionsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0 show-modal-title" id="myLargeModalLabel">Upload Instructions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body pt-0">
                    <div class="form-group mt-2 text-center">
                        <?php
                        // loop through the instructions and print here
                        for($i = 0; $i < count($acpCols["instructions"]); $i++) {
                            print ($i+1).". {$acpCols["instructions"][$i]}<br>";
                        }
                        ?>
                        <hr>
                        Dont worry, we will guide you through as you upload the data set.
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php require_once 'foottags.php'; ?>
<script>
<?php if(empty($session->curBranchId) && $valid) { ?> 
  $(`div[class~="importModal"]`).modal('show');
<?php } ?>
<?php if(!empty($session->curBranchId) && $valid) { ?>
var currentData = "<?= $currentData ?>";
var acceptedArray = <?= json_encode($acpCols["data"]); ?>;
<?php } ?>
</script>
</body>
</html>
<?php 
} else {
  show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
}
?>