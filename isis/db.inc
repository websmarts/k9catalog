<?php
/*require_once'../adodb_lite/adodb.inc.php';*/
/*Development environment */
/*
	$dbhost = "localhost";
	$dbname = "k9catalog";
	$dbuname = "root";
	$dbpass = "";
*/	
	
	//Production environment 
	$dbhost = "localhost";
	$dbname = "k9homes_k9";
	$dbuname = "k9homes_k9user";
	$dbpass = "k9pass";
	

	
	
	
	$db = ADONewConnection('mysql');
	if (!$db->Connect("$dbhost","$dbuname","$dbpass","$dbname")) {
		exit ("Cannot connect to database");
	}

	
	

	
function do_query($query) {
	global $db;
	global $db_debug;
	
		// set $db->debug for debug info
		
		
		// if select query then get data
		//echo $query;
		if (preg_match("/^select /i",$query) ){
			
			// return assoc array
			$ret = $db->GetArray($query);
			
		}
		
		if (preg_match("/^insert /i",$query) ){
			
			$res = $db->Execute($query);
			
			$ret =  $db->Insert_ID();			
		}	
		
		if (preg_match("/^delete /i",$query) ){
			
			$ret = $db->Execute($query);		
			
		}	
		if (preg_match("/^update /i",$query) ){
			
			$ret = $db->Execute($query);
						
		}	
		
			
		return $ret;		
}

?>