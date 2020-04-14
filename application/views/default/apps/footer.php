 <footer class="py-5" id="footer-main">
   <div class="container">
     <div class="row align-items-center justify-content-xl-between">
       <div class="col-xl-6">
         <div class="copyright text-center text-xl-left text-muted">
           &copy; 2019 <a href="<?= config_item('site_url') ?>" class="font-weight-bold ml-1" target="_blank"><?= config_item('developer') ?></a>
         </div>
       </div>
     </div>
   </div>
 </footer>
 <script src="<?= $config->base_url('assets/vendor/jquery/dist/jquery.min.js'); ?>"></script>
 <script src="<?= $config->base_url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
 <script src="<?= $config->base_url('assets/vendor/js-cookie/js.cookie.js'); ?>"></script>
 <script src="<?= $config->base_url('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js'); ?>"></script>
 <script src="<?= $config->base_url('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js'); ?>"></script>
 <script src="<?= $config->base_url('assets/vendor/onscreen/dist/on-screen.umd.min.js'); ?>"></script>
 <script src="<?= $config->base_url('assets/js/argon.min9f1e.js'); ?>?v=1.1.0"></script>
 <script>
 	$(`form[class="setup"]`).on('submit', function(e) {
 		e.preventDefault();
 		var formData = $(this).serialize();
 		$(`div[class~="form-result"]`).html(``);
 		$.post(`<?= $config->base_url('setup/letsBegin') ?>`, formData, (resp) => {
 			$(`div[class~="form-result"]`).html(`<div class='alert alert-${resp.status}'>${resp.result}</div>`);
 			if(resp.status == 'success') {
 				$(`form[class="setup"]`)[0].reset();
 			}
 		}, 'json');
 	});
 </script>
</body>
</html>