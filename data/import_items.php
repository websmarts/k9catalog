<?php

include_once("../lib/db.inc");
include_once("../lib/common.inc");


$lines = file("client_prices.txt");



foreach ($lines as $line) {
	$n++;

	
	list($cid,$product_code,$client_price) = split("\t",$line);
	
	$sql = "replace into client_prices (client_id,product_code,client_price) VALUES ($cid,'$product_code',$client_price)";
	$res = do_query($sql);
	
	
}
echo "<br>\nDone<br>\n";	
?>	