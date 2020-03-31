<?php
/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		POS
 * @subpackage	POS Super Class
 * @category	Core Functions
 * @author		VisamiNetSolutions Dev Team
 */

class Log {

	public function __construct() { }

	public function write_log($level, $msg) { }

	protected function _format_line($level, $date, $message) { }

	protected static function strlen($str) { }

	protected static function substr($str, $start, $length = NULL){ }
}
