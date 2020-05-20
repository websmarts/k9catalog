<?php
// Initial setup //
require_once'../adodb_lite/adodb.inc.php';
include_once('../lib/db.inc');
include_once('../lib/common.inc');


function prx($a) {
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}



$lines = file("freightzones.csv");

foreach ($lines as $line) {
	//echo $line."<br>\n";
	$f = explode(",",$line);
	
	//prx($f);
    $sql = 'insert into freight_zones (`pcode`,`zone`) VALUES ('.$f[2].',"'.trim($f[4]).'")';
    
    //echo $sql;
    //exit;
    
    $db->Execute($sql);
    
	
	
	// remove ant $ sign from price
	
	
	

}
echo "done";

?>