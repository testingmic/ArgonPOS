<?php
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Base;

class Activate {
	public static function activate() {
		flush_rewrite_rules();
	}
}