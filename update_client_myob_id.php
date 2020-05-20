<?php
// Initial setup //
require_once'adodb_lite/adodb.inc.php';
include_once('lib/db.inc');
include_once('lib/common.inc');




// Collect Request Vars 
$req = http_request(); // Populate global var $req from $_GET and $_POST

$lines = file("myob_ids.tab");
$headers = array_shift($lines);

if (count($lines) > 0 ) {
	
	//$sql = "update clients set status = concat('_',clients.status)";
	//do_query($sql);
	
	foreach ($lines as $line) {
		$line = trim($line); // remove pesky crap
        list($myid,$phone,$name) = preg_split('/\t/',$line);
        
		echo $myid ."<br>".$phone."<br>".$name; exit;
		//echo $client."<br>";
		$sql = "select * from clients where name ='".$client."'";
		//echo "$sql <br>";
		
		//$res = do_query($sql);
		
		if (count($res) < 1) {
			
			//echo dumper($res);
			echo "Insert client:$client <br>";
			
			$sql = "INSERT INTO clients (name,status) VALUES ('".addslashes($client)."','active')";
			//echo $sql."<br>";
			$db->debug = true;
			do_query($sql);
			$db->debug = false;
			
		} else {
			// client already exists - reset status to active
			$sql = "update clients set status='active' where name ='".$client."'";
			$res = do_query($sql);
			
		}
		
	}
	
	
}


?>
