<?php
$page_header = "Home";
require "header.php"
?>
<!-- Main content -->
<div class="main-content">
 <!-- Header -->
 <div class="header bg-primary pt-5 pb-7">
   <div class="container">
     <div class="header-body">
       <div class="row align-items-center">
         <div class="col-lg-6">
           <div class="pr-5">
             <h1 class="display-2 text-white font-weight-bold mb-0"><?= config_item('site_name') ?></h1>
             <h2 class="display-4 text-white font-weight-light">Covid-19 is affecting retail globally. It's more important than ever to have a multi-channel retail business. Prepare for the retail resurgence with <strong><?= config_item('site_name') ?></strong>.</h2>
             <p class="text-white mt-4"><strong><?= config_item('site_name') ?></strong> perfectly makes life pretty simple by allowing you fully manage your stores and all your personnels.</p>
             <div class="mt-5">
               <a href="#features" class="btn btn-neutral my-2">Explore Features</a>
               <a href="#pricing" class="btn btn-default my-2">Purchase now</a>
             </div>
           </div>
         </div>
         <div class="col-lg-6">
           <div class="row pt-5">
             <div class="col-md-6">
               <div class="card">
                 <div class="card-body">
                   <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow mb-4">
                     <i class="ni ni-active-40"></i>
                   </div>
                   <h5 class="h3">Easy POS</h5>
                   <p>We designed a user-friendly interface that’s fast to use and easy to learn, minimizing training time. All your data is synced to the cloud and accessible from anywhere.</p>
                 </div>
               </div>
               <div class="card">
                 <div class="card-body">
                   <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow mb-4">
                     <i class="ni ni-active-40"></i>
                   </div>
                   <h5 class="h3">Inventory Management</h5>
                   <p>Manage inventory across multiple outlets with a centralised product catalog, accessible from your POS, back-office, or on the road. Edit products in bulk. Automatically reorder stock.</p>
                 </div>
               </div>
             </div>
             <div class="col-md-6 pt-lg-5 pt-4">
               <div class="card mb-4">
                 <div class="card-body">
                   <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow mb-4">
                     <i class="ni ni-active-40"></i>
                   </div>
                   <h5 class="h3">Multiple Outlets</h5>
                   <p><?= config_item('site_name') ?> makes it easy to add new outlets  as your business grows. All of <?= config_item('site_name') ?>’s key features including POS, inventory, and reporting are designed for multiple outlets so your business operates as one cohesive operation.</p>
                 </div>
               </div>
               <div class="card mb-4">
                 <div class="card-body">
                   <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow mb-4">
                     <i class="ni ni-active-40"></i>
                   </div>
                   <h5 class="h3">Analytics</h5>
                   <p><?= config_item('site_name') ?> takes the guesswork out of decision-making with powerful sales and inventory reporting that’s live and accurate to the minute. .</p>
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
   <div class="separator separator-bottom separator-skew zindex-100">
     <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
       <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
     </svg>
   </div>
 </div>

 <section class="py-6 pb-9 bg-default">
   <div class="row justify-content-center text-center">
     <div class="col-md-6">
       <h2 class="display-3 text-white">A complete POS solution</h3>
         <p class="lead text-white">
           <strong><?= config_item('site_name') ?></strong> is a completly new product built on our newest re-built from scratch framework structure that is meant to make work more intuitive, more adaptive and, needless to say, so much easier to customize. Let <strong><?= config_item('site_name') ?></strong> amaze you with its cool features and build tools and get your <strong>Store</strong> to a whole new level.
         </p>
     </div>
   </div>
 </section>
 <section class="section section-lg pt-lg-0 mt--7">
   <div class="container">
     <div class="row justify-content-center" id="features">
       <div class="col-lg-12">
         <div class="row">
           <div class="col-lg-4">
             <div class="card card-lift--hover shadow border-0">
               <div class="card-body py-5">
                 <div class="icon icon-shape bg-gradient-primary text-white rounded-circle mb-4">
                   <i class="ni ni-check-bold"></i>
                 </div>
                 <h4 class="h3 text-primary text-uppercase">User Accounts</h4>
                 <p class="description mt-3">Create individual staff accounts and track sales. Easily customize permissions to restrict what users can see and do.</p>
               </div>
             </div>
           </div>
           <div class="col-lg-4">
             <div class="card card-lift--hover shadow border-0">
               <div class="card-body py-5">
                 <div class="icon icon-shape bg-gradient-success text-white rounded-circle mb-4">
                   <i class="ni ni-istanbul"></i>
                 </div>
                 <h4 class="h3 text-success text-uppercase">Argon Works Offline</h4>
                 <p class="description mt-3">Continue selling even when the internet goes down, Argon will automatically resync your sales when you’re back online.</p>
               </div>
             </div>
           </div>
           <div class="col-lg-4">
             <div class="card card-lift--hover shadow border-0">
               <div class="card-body py-5">
                 <div class="icon icon-shape bg-gradient-warning text-white rounded-circle mb-4">
                   <i class="ni ni-planet"></i>
                 </div>
                 <h4 class="h3 text-warning text-uppercase">CUSTOM PAYMENT TYPES</h4>
                 <p class="description mt-3">Create custom buttons to accept cash, credit, debit, check, gift cards or any other payment type you need.</p>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>
 
 <section class="py-6">
   <div class="container">
     <div class="row row-grid align-items-center">
       <div class="col-md-6">
         <img height="400px" src="<?= $config->base_url('assets/img/theme/payment.jpg'); ?>" class="img-flui">
       </div>
       <div class="col-md-6">
         <div class="pr-md-5">
           <h1>Multiple Payment Options</h1>
           <p>Almost half of today’s consumers prefer credit card payment over cash. So important is the issue of payment choice, that customers are known to halt their decision purchase unless you have their preferred payment method in your store. <br><br> With that in mind, we have made it possible for you to choose the payment options that applies to your store.
           </p>
           <ul class="list-unstyled mt-2">
             <li class="py-2">
               <div class="d-flex align-items-center">
                 <div>
                   <div class="badge badge-circle badge-success mr-3">
                     <i class="ni ni-chart-bar-32"></i>
                   </div>
                 </div>
                 <div>
                   <h4 class="mb-0">Mobile Money (All Networks)</h4>
                 </div>
               </div>
             </li>
             <li class="py-2">
               <div class="d-flex align-items-center">
                 <div>
                   <div class="badge badge-circle badge-success mr-3">
                     <i class="fa fa-money-bill"></i>
                   </div>
                 </div>
                 <div>
                   <h4 class="mb-0">Visa Cards</h4>
                 </div>
               </div>
             </li>
             <li class="py-2">
               <div class="d-flex align-items-center">
                 <div>
                   <div class="badge badge-circle badge-success mr-3">
                     <i class="fa fa-money-bill-alt"></i>
                   </div>
                 </div>
                 <div>
                   <h4 class="mb-0">Master Card</h4>
                 </div>
               </div>
             </li>
           </ul>
         </div>
       </div>
     </div>
   </div>
 </section>
 <section class="py-6">
   <div class="container">
     <div class="row row-grid align-items-center">
       <div class="col-md-6 order-md-2">
         <img src="<?= $config->base_url('assets/img/theme/landing-1.png'); ?>" class="img-fluid">
       </div>
       <div class="col-md-6 order-md-1">
         <div class="pr-md-5">
           <h1>Awesome features</h1>
           <p>The dashboard comes with super cool summary of sales that has been recorded for the day. A chart available shows the summary of sales recorded for the week grouped by each day. Stock Levels and Product Expiry Notices are also available to you on the dashboard.</p>
           <ul class="list-unstyled mt-5">
             <li class="py-2">
               <div class="d-flex align-items-center">
                 <div>
                   <div class="badge badge-circle badge-success mr-3">
                     <i class="ni ni-chart-bar-32"></i>
                   </div>
                 </div>
                 <div>
                   <h4 class="mb-0">Carefully calculated Summary</h4>
                 </div>
               </div>
             </li>
             <li class="py-2">
               <div class="d-flex align-items-center">
                 <div>
                   <div class="badge badge-circle badge-success mr-3">
                     <i class="ni ni-basket"></i>
                   </div>
                 </div>
                 <div>
                   <h4 class="mb-0">Stock Level Alerts</h4>
                 </div>
               </div>
             </li>
           </ul>
         </div>
       </div>
     </div>
   </div>
 </section>
 <section class="py-6">
   <div class="container">
     <div class="row row-grid align-items-center">
       <div class="col-md-6">
         <img src="<?= $config->base_url('assets/img/theme/landing-3.png'); ?>" class="img-fluid">
       </div>
       <div class="col-md-6">
         <div class="pr-md-5">
           <h1>Custom Sales Report</h1>
           <p>Keep your finger on the pulse by accessing your key sales metrics from your phone anytime, anywhere.</p>
           <p>We pride ourselves with the detailed insight on sales reports generated under the analytics section of the App.</p>
           <p>Set daily, weekly and monthly sales targets for each of your staff, and see how they are performing against those objectives.</p>
         </div>
       </div>
     </div>
   </div>
 </section>

 <div class="row justify-content-center" id="pricing">
    <div class="col-lg-12 text-center mb-4">
      <h1>Pricing</h1>
      <hr>
    </div>
    <div class="col-lg-7">
      <div class="pricing card-group flex-column flex-md-row mb-3">
        <div class="card card-pricing border-0 text-center mb-4">
          <div class="card-header bg-transparent">
            <h4 class="text-uppercase ls-1 text-primary py-3 mb-0">Basic pack</h4>
          </div>
          <div class="card-body px-lg-7">
            <div class="display-2">GH&cent;100<small style="font-size: 20px">/mon</small></div>
            <span class="text-muted">billed annually</span><br>
            <span class="text-muted">or GH&cent;120 billed monthly</span>
            <ul class="list-unstyled my-4">
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-gradient-primary shadow rounded-circle text-white">
                      <i class="fas fa-terminal"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2">Intuitive Point of Sale</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-gradient-primary shadow rounded-circle text-white">
                      <i class="fas fa-shopping-basket"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2">2 Sale Outlets</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-gradient-primary shadow rounded-circle text-white">
                      <i class="fas fa-pen-fancy"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2">Inventory Management</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-gradient-primary shadow rounded-circle text-white">
                      <i class="fas fa-hdd"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2">24/7hr Support</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-gradient-primary shadow rounded-circle text-white">
                      <i class="fas fa-chart-bar"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2">Basic Reporting</span>
                  </div>
                </div>
              </li>
            </ul>
            <a href="<?= $config->base_url('app/register?v=basic') ?>" type="button" class="btn btn-primary mb-3">Get Started</a>
          </div>
        </div>
        <div class="card card-pricing bg-gradient-success zoom-in shadow-lg rounded border-0 text-center mb-4">
          <div class="card-header bg-transparent">
            <h4 class="text-uppercase ls-1 text-white py-3 mb-0">Alpha pack</h4>
          </div>
          <div class="card-body px-lg-7">
            <div class="display-2 text-white">GH&cent;130<small style="font-size: 20px">/mon</small></div>
            <span class="text-white">billed annually</span><br>
            <span class="text-white">or GH&cent;160 billed monthly</span>

            <ul class="list-unstyled my-4">
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-white shadow rounded-circle text-muted">
                      <i class="fas fa-terminal"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2 text-white">Intuitive Point of Sale</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-white shadow rounded-circle text-muted">
                      <i class="fas fa-pen-fancy"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2 text-white">Multi Outlet Management</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-white shadow rounded-circle text-muted">
                      <i class="fas fa-hdd"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2 text-white">24/7hr Support</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-white shadow rounded-circle text-muted">
                      <i class="fas fa-download"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2 text-white">Customized Data Onboarding</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-white shadow rounded-circle text-muted">
                      <i class="fas fa-wrench"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2 text-white">Customized Theme Color</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-white shadow rounded-circle text-muted">
                      <i class="fas fa-money-check"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2 text-white">Unlimited Turnover</span>
                  </div>
                </div>
              </li>
              <li>
                <div class="d-flex align-items-center">
                  <div>
                    <div class="icon icon-xs icon-shape bg-white shadow rounded-circle text-muted">
                      <i class="fas fa-chart-bar"></i>
                    </div>
                  </div>
                  <div>
                    <span class="pl-2 text-white">Advanced Analytics Filter</span>
                  </div>
                </div>
              </li>
            </ul>
            <a href="<?= $config->base_url('app/register?v=alpha') ?>" class="btn btn-secondary mb-3">Get Started</a>
          </div>
        </div>
      </div>
    </div>
 </div>


</div>

<?php require "footer.php"; ?>