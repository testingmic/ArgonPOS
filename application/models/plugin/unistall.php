<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

if( !defined('WP_UNINSTALL_PLUGIN') ) { 
	die;
}

// clear the database information
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name='evelynpos_api'");