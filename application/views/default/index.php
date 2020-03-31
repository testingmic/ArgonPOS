<?php
global $functions, $db, $admin_user;
if($admin_user->logged_InControlled()):
	//using the switch case to get the right file to display
	if(isset($SITEURL[1])):
		//set a variable for the file
		$file = $SITEURL[1];
		if(file_exists(config_item('default_view_path')."{$file}.php"))
			include_once config_item('default_view_path')."{$file}.php";
		else
			show_error('Page Not Found', 'Sorry the page you are trying to view does not exist on this server');
	else:
		include_once config_item('default_view_path')."dashboard.php";
	endif;
else:
	require config_item('default_view_path')."dashboard.php";
endif;