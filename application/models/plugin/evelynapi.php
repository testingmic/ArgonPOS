<?php
/**
 * @package EvelynApi
 *
 */
/*
Plugin Name: EvelynPOS Api
Plugin URI: https://www.evelynpos.com/plugins
Description: This Plugin connects the users EvelynPOS Account to their Wordpress Site for Data Syncing and efficient management.
Version: 1.0.1
Author: Emmanuel Obeng, Analitica Innovare Ltd.
Author URI: https://www.github.com/emmallob
*/

defined( 'ABSPATH' ) or die('Hey, what are you doing here? Silly human!');


if(file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
	require_once dirname(__FILE__).'/vendor/autoload.php';
}

use Inc\Base\Activate;
use Inc\Base\Deactivate;

function activate_evelyn_plugin() {
	Activate::activate();
}

function deactivate_evelyn_plugin() {
	Deactivate::deactivate();
}

register_activation_hook( __FILE__, 'activate_evelyn_plugin');
register_deactivation_hook( __FILE__, 'deactivate_evelyn_plugin');

if( class_exists('Inc\\Init')) {
	Inc\Init::register_services();
}
?>