<?php
require_once'../../adodb_lite/adodb.inc.php';
include_once("../lib/common.inc");

	$dbhost = "localhost";
	$dbname = "k9catalog";
	$dbuname = "root";
	$dbpass = "";
	
/*	
	//Production environment 
	$dbhost = "localhost";
	$dbname = "ausesolu_k92";
	$dbuname = "ausesolu_k9";
	$dbpass = "k9pass";
	
	*/
	
	
	
	$db = ADONewConnection('mysql');
	if (!$db->Connect("$dbhost","$dbuname","$dbpass","$dbname")) {
		exit ("Cannot connect to database");
	}



$lines = file("cust2.txt");



foreach ($lines as $name) {
	$n++;
	
	
		

			$name=rtrim($name);
			
			
			
			$sql = "select * from clients where name='$name'";
			$res = $db->GetArray($sql);
			
			 if ($res[0]['client_id'] <1 ) {
			 	
			 	// import the record into the database
			 	$m++;
			 
			 	
			 	$sql = "insert into clients (name) VALUES ('".addslashes($name)."')";
			 	$res = $db->Execute($sql);
			 	echo $m." ::".$name ." >>> added<br>\n";
			 	
			 }
		

}	
echo "<br>\nDone<br>\n";	
?>	