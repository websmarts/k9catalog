<?php
// Initial setup //
require_once'adodb_lite/adodb.inc.php';
include_once('lib/db.inc');
include_once('lib/common.inc');


function pr($a) {
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}



$lines = file("costs.csv");

foreach ($lines as $line) {
	//echo $line."<br>\n";
	list($product_code,$price) = explode(",",$line);
	
	
	if (strlen($product_code) < 1 || $price < 1) {
		continue;
	}
	
	// remove ant $ sign from price
	
	$price = $price * 100; // convert to cents
	
	
	
	$res = $db->getArray("select * from products where product_code ='".$product_code."' limit 1");
	//pr($res);
	if ($res[0]['product_code']) { // product exists
	
		$sql = "update products set (cost=$price where product_code='$product_code'";
	
		//echo $sql ."<br>\n"; exit;
		//$db->debug = true;
		$db->Execute("update products set cost=$price where product_code='$product_code'");
		//$db->debug = false;
	} 

}
echo "done";

?>