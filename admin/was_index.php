<?php
// Initial setup //

require_once'../adodb_lite/adodb.inc.php';
include_once('../lib/db.inc');
include_once('../lib/common.inc');

// Collect Request Vars 
$req = http_request(); // Populate global var $req from $_GET and $_POST

$user = $_COOKIE['admin']; // if $user is somthing then the admin is logged in!


if ($req['a'] == "logout") {
	$user = "";
	setcookie("admin","" ,time()+2); // expire after 2 seconds
}
if ($req['a'] == "login") {
	if (try_login($req['user'])) {// success
			// set admin cookie
			setcookie("admin", $req['user'] , time()+3600);  /* expire in 1 hour */
			$user = $req['user']; 
	} else {
		$user = "";
		setcookie("admin","" ,time()+2); // expire after 2 seconds
	}
}
//echo dumper ($req);
//echo dumper($_COOKIE);
//echo "USER=$user <br>\n";

session_start();

/*
*******************************************************************************************
*																																													*
* Main Switch - selects the data 'module' to be loaded and then the view to be used       *
*																																													*
*																																													*
*******************************************************************************************
*/

$template = "templates/admin_main.inc"; // default - modules can change if need be


//echo dumper($req);

$m = $req['m']; // module to use
$a = $req['a']; // action request
$v = $req['v']; // next view requested

//Actions
switch ( $m ){
	
	case "type":
		$module="type";
		break;
	case "cat":
		$module="cat";
		break;
	case "product":
		$module="product";
		break;
	default:
		$module= "default";
		
}
//echo "Using module=".$module.".php<br>\n";
if (file_exists("modules/".$module.".php")) {
	include("modules/".$module.".php");
} else {
	$error_msg .= "<p>Module '"."modules/".$module.".php"." is not available</p>\n";
}

// Views
switch($m) {
	
	case "cat":
		$_view ="add_category";
		break;
	case "type":
		$_view = "edit_type";
		break;
	case "product":
		if ($req['a'] == 'edit'){
			$_view = "edit_product";
		} else {
			$_view = "edit_product";	
		}
		break;
			
	
	default:
		$_view = "default";	
}
//echo "Using template=$template<br>\n";
include_once($template);



?>