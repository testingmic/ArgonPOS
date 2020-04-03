<?php
// Page Header
$PAGETITLE = "Dashboard";

// include the important files
require_once "headtags.php";

// Global functions
global $posClass, $baseUrl;

?>	
<!-- Header -->
<div class="sales-overview-data"></div>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#"><?= $PAGETITLE ?></a></li>
            </ol>
          </nav>
        </div>
      </div>
      <!-- Card stats -->
      <div class="row">
        <div class="dashboard-reports"></div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Total Sales</h5>
                  <span class="h2 font-weight-bold mb-0 total-sales">0.00</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                    <i class="ni ni-active-40"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2 total-sales-trend"><i class="fa fa-arrow-up"></i> 0.00%</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Products Cost</h5>
                  <span class="h2 font-weight-bold mb-0 total-cost">0.00</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                    <i class="ni ni-money-coins"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2 total-cost-trend"><i class="fa fa-arrow-up"></i> 0.00%</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Revenue</h5>
                  <span class="h2 font-weight-bold mb-0 total-profit">0.00%</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                    <i class="ni ni-chart-bar-32"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2 total-profit-trend"><i class="fa fa-arrow-up"></i> 0.00%</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Total Orders</h5>
                  <span class="h2 font-weight-bold mb-0 total-served">0</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                    <i class="ni ni-chart-pie-35"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2 total-served-trend"><i class="fa fa-arrow-up"></i> 0.00%</span>
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
              <h5 class="h3 mb-0">Sales value</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
          <!-- Chart -->
          <div class="chart">
            <!-- Chart wrapper -->
            <div id="dashboard-sales" class="chart-div"></div>
            <div class="chart-sales">
                <div id="chart-sales" class="apex-charts"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      
      <div class="col-xl-12 col-md-12">
        <div class="card card-stats">
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Average Sales</h5>
                <span class="h2 font-weight-bold mb-0 average-sales">0.00</span>
              </div>
              <div class="col-auto">
                <div class="icon icon-shape bg-gradient-purple text-white rounded-circle shadow">
                  <i class="ni ni-shop"></i>
                </div>
              </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
              <span class="text-success mr-2 average-sales-trend"><i class="fa fa-arrow-up"></i> 0.00%</span>
            </p>
          </div>
        </div>
      </div>

      <div class="col-xl-12 col-md-12">
        <div class="card card-stats">
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Sales On Credit</h5>
                <span class="h2 font-weight-bold mb-0 total-credit-sales">0.00</span>
              </div>
              <div class="col-auto">
                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                  <i class="ni ni-archive-2"></i>
                </div>
              </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
              <span class="text-success mr-2 total-credit-sales-trend"><i class="fa fa-arrow-up"></i> 0.00%</span>
            </p>
          </div>
        </div>
      </div>

      <div class="col-xl-12 col-md-12">
        <div class="card card-stats">
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Sale Discounts</h5>
                <span class="h2 font-weight-bold mb-0 total-discounts">0.00</span>
              </div>
              <div class="col-auto">
                <div class="icon icon-shape bg-gradient-gray-dark text-white rounded-circle shadow">
                  <i class="ni ni-money-coins"></i>
                </div>
              </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
              <span class="text-success mr-2 total-discounts-trend"><i class="fa fa-arrow-up"></i> 0.00%</span>
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
  
  
  <div class="row">
    <div class="col-xl-12 col-md-12">
      <div class="card">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="mb-0">Sales History</h3>
            </div>
            <div class="col text-right">
              <a href="<?= $baseUrl ?>sales" class="btn btn-sm btn-primary">See all</a>
            </div>
          </div>
        </div>
        <div class="s-responsive">
          <!-- Projects table -->
            <table class="table salesLists table-flush datatable-buttons">
            <thead class="thead-light">
              <tr>
                <th>ID</th>
                <th>Transaction ID</th>
                <th>Customer</th>
                <th>Contact</th>
                <th>Date</th>
                <th width="10%">Sales Value</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php require_once 'foottags.php'; ?>
</body>
</html>