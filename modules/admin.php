<?php

	
if ($user > "" ) {
	
// only administrators can access the main switch functions
	$a = strtolower($req['a']);
	switch($a) {
		
		default:
			$page = "admin1.inc";			
	}
	
	
}  else {
	$page = "default.inc";
	$error_msg .= "You need to be logged in as administrator to use admin functions<br>\n";
}

?>