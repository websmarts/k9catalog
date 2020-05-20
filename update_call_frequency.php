<?php
// Initial setup //
require_once'adodb_lite/adodb.inc.php';
include_once('lib/db.inc');
include_once('lib/common.inc');




// Collect Request Vars 
//$req = http_request(); // Populate global var $req from $_GET and $_POST

$lines = file("clients.csv");

if (count($lines) > 0 ) {
	

	
	foreach ($lines as $client) {
		$c = explode(',',trim($client)); // remove pesky crap
		
		//print_r($c);
        
		
			// client already exists - reset status to active
			$sql = "update clients set call_frequency=".$c[1]." where client_id =".$c[0];
            //echo $sql;
           //exit;
			$res = do_query($sql);
			
		
		
	}
	
	
}

echo 'Done!';
?>
