<?php
# set the names of the directories
$system_folder = "system";
$application_folder = "application";

#display errors
error_reporting(E_ALL);
// ini_set("display_errors", 0);
#set new places for my error recordings
ini_set("log_errors","1");
ini_set("error_log", "errors_log");

# Path to the system directory
DEFINE('BASEPATH', $system_folder.DIRECTORY_SEPARATOR);
DEFINE('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
DEFINE('VIEWPATH', $application_folder.DIRECTORY_SEPARATOR);

function forceHttps() {
	if($_SERVER["SERVER_PORT"] !==433 && (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"]=="off")) {
		// header("Location: https://dev.".$_SERVER["HTTP_HOST"].'.com'.$_SERVER["REQUEST_URI"]."");
	}
}

forceHttps();

/*
	replace array indexes:
	1) fix windows slashes
	2) strip up-tree ../ as possible hack attempts
*/
$URL = STR_REPLACE( ARRAY( '\\', '../'), ARRAY( '/',  '' ), $_SERVER['REQUEST_URI'] );

#strip all forms of get data
IF ($offset = STRPOS($URL, '?')) { $URL = SUBSTR($URL, 0, $offset); } ELSE IF ($offset = STRPOS($URL, '#')) {
	$URL = SUBSTR($URL, 0, $offset);
}

# call the main core function and start processing your document
REQUIRE "system/core/pos.php";

/*
	the path routes below aren't just handy for stripping out
	the REQUEST_URI and looking to see if this is an attempt at
	direct file access, they're also useful for moving uploads,
	creating absolute URI's if needed, etc, etc
*/
$chop = -STRLEN(BASENAME($_SERVER['SCRIPT_NAME']));
DEFINE('DOC_ROOT', SUBSTR($_SERVER['SCRIPT_FILENAME'], 0, $chop));
DEFINE('URL_ROOT', SUBSTR($_SERVER['SCRIPT_NAME'], 0, $chop));

# strip off the URL root from REQUEST_URI
IF (URL_ROOT != '/') $URL = SUBSTR($URL, STRLEN(URL_ROOT));

# strip off excess slashes
$URL = TRIM($URL, '/');

# 404 if trying to call a real file
IF ( FILE_EXISTS(DOC_ROOT.'/'.$URL) && ($_SERVER['SCRIPT_FILENAME'] != DOC_ROOT.$URL) && ($URL != '') && ($URL != 'Index.php') )
	DIE(show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server'));

/*
	If $url is empty of default value, set action to 'default'
	otherwise, explode $URL into an array
*/
$SITEURL = (($URL == '') || ($URL == 'index.php') || ($URL == 'index.html')) ? ARRAY('index') : EXPLODE('/', html_entity_decode($URL));

/*
	I strip out non word characters from $SITEURL[0] as the include
	which makes sure no other oddball attempts at directory
	manipulation can be done. This means your include's basename
	can only contain a..z, A..Z, 0..9 and underscore!
	
	for example, as root this would make:
	pages/default.php
*/

// call the user logged in class
$posClass = load_class('Pos', 'models');
$admin_user = load_class('Users', 'controllers');
$accessObject = load_class('Accesslevel', 'controllers');

$includeFile = config_item('default_view_path').strtolower(PREG_REPLACE('/[^\w_]-/','',$SITEURL[0])).'.php';

# Check the site status
GLOBAL $SITEURL, $session;

# Run some checks on the URL
// set the dashboard main file
$dashboard = config_item('default_view_path').strtolower(PREG_REPLACE('/[^\w_]-/','', "dashboard")).'.php';

// set the main file to display
$parent_file = config_item('default_view_path').strtolower(PREG_REPLACE('/[^\w_]-/','',$SITEURL[0])).'.php';

// confirm if the first index has been parsed
if(isset($SITEURL[1])) {
	// set the next url id
	$file_to_include = config_item('default_view_path').strtolower(PREG_REPLACE('/[^\w_]-/','',$SITEURL[1])).'.php';
	
	// confirm that the first index parsed is an existing file
	if(is_file($file_to_include) and file_exists($file_to_include)) {
		// include the file on the page
		include($file_to_include);
	} elseif(!file_exists($file_to_include) and is_file($parent_file) and file_exists($parent_file)) {
		// then include the parent file instead
		include($parent_file);
	} elseif(is_dir($SITEURL[0])) {
		show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
	} else {
		// then include the dashboad file
		include($dashboard);
	}
} else {
	if(is_file($includeFile) and file_exists($includeFile)) {
		include($includeFile);
	} else {
		show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
	}
}
?>