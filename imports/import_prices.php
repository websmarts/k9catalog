<?php
// Initial setup //

include_once('../lib/db.inc');
include_once('../lib/common.inc');






$lines = file("stocklist_tab.txt");

foreach ($lines as $line) {
	//echo $line."<br>\n";
	list($crap,$product_code,$description,$price) = explode("\t",$line);
	
	// remove ant $ sign from price
	$price = (ereg_replace("$","",$price)) * 100;
	
	
	$sql = "update products set (price=$price,description='$description' where product_code='$product_code'";
	
	echo $sql ."<br>\n";
	//$db->Execute("update products set (price=$price,description='$description' where product_code='$product_code'");
	

}





?>