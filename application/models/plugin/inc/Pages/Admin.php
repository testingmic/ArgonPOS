<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Pages;

use \Inc\Api\Callbacks;
use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;

class Admin extends BaseController {

	public $settings;
	public $callbacks;
	public $pages = array();
	public $subpages = array();

	public function __construct() {
		
		$this->settings = new SettingsApi();
		$this->callbacks = new Callbacks();

		$this->pages = array(
			array(
				'page_title' => 'EvelynPOS Api',
				'menu_title' => 'EvelynPOS',
				'capability' => 'manage_options',
				'menu_slug' => 'evelynpos_api',
				'callback' => array($this->callbacks, 'adminDashboard'),
				'icon_url' => 'dashicons-store'
			)
		);

		$this->subpages = array(
			array(
				'parent_slug' => 'evelynpos_api',
				'page_title' => 'Inventory',
				'menu_title' => 'Manage Inventory',
				'capability' => 'manage_options',
				'menu_slug' => 'evelynpos_inventory',
				'callback' => array($this->callbacks, 'inventoryDashboard'),
			)
		);

	}

	public function register() {

		$this->settings->addPages($this->pages)->withSubPage('Dashboard')->addSubPages($this->subpages)->register();

	}

}