<?php

include_once("../lib/db.inc");
include_once("../lib/common.inc");




$lines = file("prices.txt");



foreach ($lines as $line) {
	$n++;

	
	//echo $line ."<br>\n";
	
	list($crap,$product_code,$description,$price,$qty_break,$discount) = split("\t",$line);
	if ($product_code == "Item #") { 
		$start = 1;
		continue;
	}
	
	// if product code = "" we have finished the list but there may be stil lines to come
	if ($product_code =="") {
		$start = 0;
	}
	
	if ($start == 1) {
		$price = preg_replace("/[\$]/","",$price); // remove $ sign
		$price = $price * 100; // convert to cents
		$discount = intval(preg_replace("/%/","",$discount)) ; // convert to percent
		$qty_break += 0; // make sure it is a number
		if ( preg_match("/^\"(.*)?\"$/",$description,$matches) ) { // remove quotes
			$description = substr($description,1,strlen($description)-2); // remove the leading and trailing double quotes
			$description = preg_replace("/\"\"/",'"',$description);
			
		}
		echo "$product_code,$description,$price,$qty_break,$discount <br>\n";
		$sql = "update products set description='".addslashes($description)."',price=$price,qty_break=$qty_break,qty_discount=$discount WHERE product_code='".$product_code."'";
		echo $sql ."<br>\n";
		$res = do_query($sql);
		}
	
	
}
echo "<br>\nDone<br>\n";	
?>	