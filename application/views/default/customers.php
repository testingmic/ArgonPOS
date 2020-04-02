<?php
$PAGETITLE = "Customers List";

// include the important files
require_once "headtags.php";
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
                <div class="table-responsive">
                  <table data-content="non-filtered" class="table nowrap datatable-buttons customersList" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead class="thead-light">
                          <tr>
                              <th width="7%">#</th>
                              <th>Fullname</th>
                              <th>Email</th>
                              <th>Contact</th>
                              <th>Log Date</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                </div>
              </div>
          </div>
      </div>     

  </div><!--end row-->
<div class="modal fade" id="newCustomerModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="content-loader"><i class="fa fa-spinner fa-pulse fa-3x"></i></div>
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Update Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <form autocomplete="Off" class="form py-0" id="updateCustomerForm">
                <div class="form-row mb-2">
                  <div class="form-group col-md-2">
                    <label for="inputState">Title</label>
                    <select id="newCustomer_title" name="nc_title" class="form-control selectpicker">
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
                  <div class="form-group col-md-4">
                    <label for="newCustomer_primarycontact">Primary Phone No.</label>
                    <input type="text" class="form-control" name="nc_contact" id="newCustomer_primarycontact">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="newCustomer_seccontact">Email Address</label>
                    <input type="text" class="form-control" name="nc_email" id="newCustomer_seccontact">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="newCustomer_residence">Place of Residence</label>
                    <input type="text" class="form-control" name="residence" id="newCustomer_residence">
                  </div>
                </div>
                <input type="hidden" name="customer_id">
              </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="updateCustomerForm" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php require_once 'foottags.php'; ?>
</body>
</html>