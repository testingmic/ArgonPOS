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

defined('BASEPATH') OR exit('No direct script access allowed');

$config = require(BASEPATH . 'core/common.php');

load_file(
	array(
		'constants'=>'config',
		'security'=>'core'
	)
);

$dbconn = load_class('db', 'core');	
$pos = $dbconn->get_database();
$config = load_class('config', 'core');
$session = load_class('Session', 'libraries/Session');
load_helpers(
	ARRAY(
		'string_helper',
		'email_helper',
		'url_helper',
		'file_helper',
		'time_helper',
		'upload_helper'
	)
);

global $pos, $config, $session;