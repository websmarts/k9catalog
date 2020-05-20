<?php
// Initial setup //

include_once('lib/db.inc');
include_once('lib/common.inc');






$lines = file("imports/stocklist_tab.txt");

foreach ($lines as $line) {
	//echo $line."<br>\n";
	list($crap,$product_code,$description,$price) = explode("\t",$line);
	
	if (strlen($product_code) < 1) {
		continue;
	}
	
	// remove ant $ sign from price
	$price = str_replace("$","",$price) ;
	$price = $price * 100; // convert to cents
	
	// get rid of ms quoting on description 
	
	if ( strpos($description,'"')===0 ) {
		$description = substr($description,1,strlen($description) -2); // remove leading and trailing quotes
		$description = preg_replace('/""/','"',$description);
	}
		
	
	
	$res = $db->getArray("select * from products where product_code ='$product_code' limit 1");
	if ($res[0]['product_code']) { // product exists
	
		$sql = "update products set (price=$price,description='$description' where product_code='$product_code'";
	
		//echo $sql ."<br>\n";
		//$db->debug = true;
		$db->Execute("update products set price=$price,description='$description' where product_code='$product_code'");
		//$db->debug = false;
	} else {
		echo $product_code." does not exist in database<br>\n";
		
		$db->Execute ("INSERT INTO products  (product_code,description,price,status) VALUES ('$product_code',".$db->qstr($description).",$price,'pending')");
		
	}

}

$res = $db->getArray("select product_code,id,description from products where status='pending'");
if ($res) {
	foreach($res as $p) {
		echo $p['product_code']." - ".$p['id']." - ".$p['description']."<br>\n";
		
		
	}
}

// Check if there is a delete list
echo "<p> Now checking for items to delete by reading delete_items.txt file </p>";
$lines = file("delete_items.txt");

foreach ($lines as $pcode) {
	$pcode= substr($pcode,0,strlen($pcode)-2); // remove pesky \r\n characters
	
	$db->debug=true;
	$db->Execute("Update products set status='inactive' where product_code = '$pcode'");
	$db->debug=false;
}


?>