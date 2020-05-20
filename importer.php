<?php
// Initial setup //

include_once('lib/db.inc');
include_once('lib/common.inc');


function check_type_exists($type_name) {
	$r = do_query("select typeid from type where name='$type_name'");
	if (!$r[0]) { // create the type as it does not exist yet
		$res=do_query ("INSERT INTO TYPE (name) VALUES('".addslashes($type_name)."')");
		// now do the query to get the typeid
		$r = do_query("select typeid from type where name='$type_name'");
	}
	
	return $r[0]['typeid'];
	
}
function check_category_exists($cat_name) {
	$r = do_query("select * from category where name='$cat_name'");
	
	
	return $r[0]['id'];
	
}

// Collect Request Vars 
$req = http_request(); // Populate global var $req from $_GET and $_POST

$lines = file("import_items.csv");

foreach ($lines as $line) {
	//echo $line."<br>\n";
	$f = explode(",",$line);
	
	
	$typeid = check_type_exists($f[1]);
	$catid = check_category_exists($f[2]);
	

	
	
	if ($typeid > 0 && $catid > 0) {
		$res = do_query("delete from products where product_code ='".$f[0]."'");
		$res = do_query("select * from products where product_code ='".$f[0]."'");

		if (!$res[0]) { // product code does not exist yet
			$sql = "INSERT into products (product_code,description,typeid) VALUES ('".addslashes($f[0])."','".Addslashes($f[3])."',$typeid)";
			
			do_query($sql);
			
			$res = do_query("select * from type_category where typeid=$typeid and catid=$catid");
			if(!$res[0]) {// entry does not exist in type_category yet
				
				$res = do_query("insert into type_category (catid,typeid) VALUES($catid,$typeid)");
			}
			
			// now add option to type_options table if needed
			$type= explode("-",$f[0]);
			if ($type[1] > "") { //we have an option to add
				// check if it already exists
				$res = do_query("select * from type_options where typeid=$typeid and opt_code='".$type[1]."'");
				if (!$res[0]) { //Option does not exist yet so add it
					
					do_query("insert into type_options (typeid,opt_code) VALUES ($typeid,'".$type[1]."')");
				}
			}
		}
		
	} else {
		echo "CATEGORY ".$f[2]." ($catid) TYPE ".$f[1]." ($typeid) - DOES NOT EXIST YET<br>\n";
	} 

}


// Check if there is a delete list
echo "<p> Now checking for items to delete by reading delete_items.txt file </p>";
$lines = file("delete_items.txt");

foreach ($lines as $pcode) {
	
	$db->debug=true;
	$db->Execute("Update products set status='inactive' where product_code = '$pcode'");
	$db->debug=false;
}

?>
