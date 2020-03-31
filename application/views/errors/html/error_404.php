<?php global $config; ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title>Pages Not Found :: <?php print config_item('site_name'); ?></title>
<link class="main-stylesheet" href="<?php print $config->base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link class="main-stylesheet" href="<?php print $config->base_url(); ?>assets/css/light.css" rel="stylesheet" type="text/css" />
</head>
<body class="fixed-header error-page">
<div class="d-flex justify-content-center full-height full-width align-items-center">
<div class="error-container text-center">
<h1 class="error-number">404</h1>
<h2 class="semi-bold">Sorry but we couldnt find this page</h2>
<p class="p-b-10">This page you are looking for does not exsist <a href="<?php print $config->base_url(); ?>">Go Back to Dashboard</a>
</p>

</div>
</div>
</body>
</html>