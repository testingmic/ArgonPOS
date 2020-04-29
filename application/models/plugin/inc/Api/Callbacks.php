<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Api;

use \Inc\Base\BaseController;

class Callbacks extends BaseController {

	public function __construct() {
		parent::__construct();
	}

	public function adminDashboard() {
		require_once $this->plugin_path.'templates/dashboard.php';
	}

	public function inventoryDashboard() {
		require_once $this->plugin_path.'templates/inventory.php';
	}

	public function customersDashboard() {
		require_once $this->plugin_path.'templates/customers.php';
	}

}