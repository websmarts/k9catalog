<?php

include_once("../lib/db.inc");
include_once("../lib/common.inc");


$lines = file("products.txt");



foreach ($lines as $line) {
	$n++;
	if ($n == 1 ) { // ignore header line
		continue;
	}
	if ($n > 5000) {
		break;
	}
	//echo $line.".<br>\n";
	list($product_code,$description,$price,$current_product_code,$typeid,$catid) = split("\t",$line);
	
	$res = do_query("replace into type_category (typeid,catid) VALUES ($typeid,$catid)");
	
}
echo "<br>\nDone<br>\n";	
?>	