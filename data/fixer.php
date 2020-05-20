<?php

include_once("../lib/db.inc");
include_once("../lib/common.inc");


$lines = file("data2.txt");



foreach ($lines as $line) {
	
		list($product_code,$name,$catid) = split("\t",$line);

			$newtypeid = do_query("insert into type (name) VALUES ('".addslashes($name)."')");
				echo $newtypeid."<br>";
			
			$res = do_query("select typeid from products where product_code='$product_code'");
			$oldtypeid = $res[0]['typeid'];
			
			echo $oldtypeid."<br>";
			
			if ($newtypeid > 0 ) {
				// change product typeid
				$res = do_query("update products set typeid=$newtypeid where product_code='".$product_code."'");
				
				// add type_category
				$res = do_query("insert into type_category (catid,typeid) values ($catid,$newtypeid)");
				
				// delete any type options for these products
				$res = do_query("delete from type_options where typeid=$oldtypeid");
				
			}
}
			
		
		

echo "<br>\nDone<br>\n";	
?>	