<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController {

	public function register() {
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
	}


	public function enqueue() {
		// enqueue all our scripts
		wp_enqueue_style('evelynpos', $this->plugin_url. 'assets/css/style.css');
		wp_enqueue_script('evelynpos2', $this->plugin_url . 'assets/js/script.js', array('jquery'));
	}

	public function thankyou() {
		echo 'thank you';
	}
}