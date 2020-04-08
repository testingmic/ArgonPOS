<?php $baseUrl = $config->base_url(); global $SITEURL; ?>
<!-- Footer -->
  <?php if(!confirm_url_id(0, 'point-of-sale')) { ?>
  <footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6">
        <div class="copyright text-center text-lg-left text-muted">
          &copy; <?= date("Y") ?> <a href="<?= config_item('base_url') ?>" class="font-weight-bold ml-1" target="_blank"><?= config_item('site_name') ?></a>
        </div>
      </div>
      <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="<?= config_item('site_url') ?>" class="nav-link" target="_blank"><?= config_item('developer') ?></a>
          </li>
        </ul>
      </div>
    </div>
  </footer>
  <?php } ?>
  </div>
  <div class="modal fade launchModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <?= form_loader(); ?>
                <div class="modal-header">
                    <h5 class="modal-title mt-0 show-modal-title" id="myLargeModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body show-modal-body" style="min-height: 250px; padding-top: 0px"></div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <?php if(in_array($SITEURL[0], ['orders', 'quotes', 'branches', 'product-types','customers'])) { ?>
  <div class="modal fade delete-modal" tabindex="-1" id="deleteData" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="exampleModalLabel">Delete Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-top:0px">
                <div class="card mb-0 p-3">
                    <form method="POST" class="submitThisForm" action="">
                        <div class="form-group mb-3 details-pane"></div><!--end form-group-->
                        <div class="form-group mb-3 text-right">
                          <input type="hidden" name="itemToDelete" class="itemToDelete">
                          <input type="hidden" name="itemId" class="itemId">
                          <button data-dismiss="modal" aria-label="Close" class="btn btn-danger">Cancel</button>
                          <button type="submit" class="btn btn-success">Yes Confirm</button>
                        </div>
                    </form>
                </div>
                <div class="form-result"></div>
            </div>
        </div>
    </div>
  </div>
  <?php } ?>
  
  <?php if(in_array($SITEURL[0], ['users'])) { ?>
  <div class="modal fade deleteModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom: 0px">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                  <div class="card mb-0 p-3">
                    <p class="show-delete-msg"></p>
                    <p class="show-delete-body">Are You Sure You Want To Delete This?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-success confirm-delete-btn" type="button">
                            <i class="fa fa-check"></i> Yes Confirm
                        </button>
                    </div>
                  </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php } ?>
  <!-- Argon Scripts -->
<!-- Core -->
<script src="<?= $baseUrl ?>assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/js-cookie/js.cookie.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/select2/dist/js/select2.min.js"></script>
<!-- Optional JS -->
<script src="<?= $baseUrl ?>assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
<?php if(in_array($SITEURL[0], ["reports-customers", "analytics", "dashboard", "index"])) { ?>
<script src="<?= $baseUrl ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<?php } ?>
<?php if(in_array($SITEURL[0], ["dashboard", "index"])) { ?>
<script src="<?= $baseUrl ?>assets/vendor/chart.js/dist/Chart.min.js"></script>
<script src="<?= $baseUrl ?>assets/vendor/chart.js/dist/Chart.extension.js"></script>
<?php } ?>
<script>
<?php if(in_array($SITEURL[0], ["point-of-sale", "requests"])) { ?>
  Cookies.set("sidenav-state", "unpinned");
<?php } else { ?>
  Cookies.set("sidenav-state", "pinned");
<?php } ?>
</script>
<!-- Argon JS -->
<script src="<?= $baseUrl ?>assets/js/argon.min9f1e.js?v=1.1.0"></script>
<script src="<?= $baseUrl ?>assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script type="text/javascript">var baseUrl = '<?= $baseUrl; ?>';</script>
<script src="<?= $baseUrl ?>assets/js/indexdb.js"></script>
<script src="<?= $baseUrl ?>assets/js/script.js"></script>
<?php if(!in_array($SITEURL[0], ["point-of-sale", "requests", "customers", "branches", "users", "inventory"])) { ?>
<script src="<?= $baseUrl ?>assets/js/reports.js"></script>
<?php } ?>