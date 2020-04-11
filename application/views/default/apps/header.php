<?php 
global $page_header, $config;
$baseUrl = $config->base_url('app/');
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="Creative Tim">
   <title><?= $page_header ?> - <?= config_item('site_name') ?></title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <link rel="icon" href="<?= $config->base_url('assets/img/brand/favicon.png'); ?>" type="image/png">
   <!-- Fonts -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
   <!-- Icons -->
   <link rel="stylesheet" href="<?= $config->base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css">
   <link rel="stylesheet" href="<?= $config->base_url('assets/vendor/fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">
   <!-- Page plugins -->
   <!-- Argon CSS -->
   <link rel="stylesheet" href="<?= $config->base_url('assets/css/argon.min9f1e.css?v=1.1.0'); ?>" type="text/css">
 </head>
 <body>
	<nav id="navbar-main" class="navbar navbar-horizontal navbar-main navbar-expand-lg navbar-dark bg-primary">
     <div class="container">
       <a class="navbar-brand" href="<?= $baseUrl ?>">
         <img src="<?= $config->base_url('assets/img/brand/white.png'); ?>">
       </a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
       </button>
       <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
         <div class="navbar-collapse-header">
           <div class="row">
             <div class="col-6 collapse-brand">
               <a href="<?= $baseUrl ?>">
                 <img src="<?= $config->base_url('assets/img/brand/blue.png'); ?>">
               </a>
             </div>
             <div class="col-6 collapse-close">
               <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                 <span></span>
                 <span></span>
               </button>
             </div>
           </div>
         </div>
         <ul class="navbar-nav mr-auto">
           <li class="nav-item">
             <a href="<?= $baseUrl ?>" class="nav-link">
               <span class="nav-link-inner--text">Dashboard</span>
             </a>
           </li>
           <li class="nav-item">
             <a href="<?= confirm_url_id(1, 'register') ? $baseUrl : null; ?>#features" class="nav-link">
               <span class="nav-link-inner--text">Features</span>
             </a>
           </li>
           <li class="nav-item">
             <a href="<?= confirm_url_id(1, 'register') ? $baseUrl : null; ?>#pricing" class="nav-link">
               <span class="nav-link-inner--text">Pricing</span>
             </a>
           </li>
           <li class="nav-item">
             <a href="<?= $baseUrl ?>register" class="nav-link">
               <span class="nav-link-inner--text">Free Trial</span>
             </a>
           </li>
         </ul>
         <hr class="d-lg-none" />
         <ul class="navbar-nav align-items-lg-center ml-lg-auto">
           <li class="nav-item d-none d-lg-block ml-lg-4">
             <a href="<?= $config->base_url() ?>" target="_blank" class="btn btn-neutral btn-icon">
               <span class="btn-inner--icon">
                 <i class="fas fa-shopping-cart mr-2"></i>
               </span>
               <span class="nav-link-inner--text">Login to Store</span>
             </a>
           </li>
         </ul>
       </div>
     </div>
   </nav>