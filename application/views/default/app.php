<?php
global $functions, $db, $admin_user;
//using the switch case to get the right file to display
if(isset($SITEURL[1])):
	//set a variable for the file
	$file = $SITEURL[1];
	if(file_exists(config_item('default_view_path')."apps/{$file}.php"))
		include_once config_item('default_view_path')."apps/{$file}.php";
	else
		show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
else:
	include_once config_item('default_view_path')."apps/index.php";
endif;