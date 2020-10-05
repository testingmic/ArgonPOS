<?php
// start the file
defined('BASEPATH') OR exit('No direct script access allowed');

// set thee default date/time for the php.ini to use
date_default_timezone_set('Europe/Lisbon');

# set the constants for the database connection
defined('DB_HOST')  OR define('DB_HOST', "localhost");
defined('DB_USER')  OR define('DB_USER', "root");
defined('DB_PASS')  OR define('DB_PASS', "");
defined('DB_NAME')  OR define('DB_NAME', "pos");

define('TIME_PERIOD', 60);
define('ATTEMPTS_NUMBER', 7);

define('ADMINISTRATOR', array(1000, 1001));
define('DEVELOPER', array(1001));

defined('SITE_DATE_FORMAT') 		OR define('SITE_DATE_FORMAT', 'd M Y H:iA');
defined('SITE_URL') 				OR define('SITE_URL', config_item('base_url'));
defined('F_SIZE') 					OR define("F_SIZE", "10Mb");
define('ACTIVE_RANGE', "3 months");
define('INACTIVE_RANGE', "6 months");