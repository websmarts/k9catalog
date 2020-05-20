<?php

include_once("../lib/db.inc");
include_once("../lib/common.inc");


$lines = file("CUST.TXT");



foreach ($lines as $line) {
	$n++;
	if ($n == 1 ) { // ignore header line
		continue;
	}
	

	//echo $line.".<br>\n";
	$fields =   split("\t",$line);
	

	
	if ( preg_match("/^[A-Z]/",$fields[0] ) ) {
		
			
			//echo $fields[0]."<br>\n";
			$sql = "insert into clients (name) Values('".addslashes($fields[0])."')";
			$res = do_query($sql);
		
	}
	
}	
echo "<br>\nDone<br>\n";	
?>	