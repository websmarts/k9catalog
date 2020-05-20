<?php
// Initial setup //
require_once 'adodb_lite/adodb.inc.php';
include_once('lib/db.inc');
include_once('lib/common.inc');




// Collect Request Vars 
$req = http_request(); // Populate global var $req from $_GET and $_POST

$lines = file("customer_update_list.csv");

if (count($lines) > 0 ) {
	
	$sql = "update clients set status = concat('_',clients.status)";
	do_query($sql);
    
    // get the first line as header and field names
    $header = array_shift($lines);
    
    echo dumper($header);
    
	$found=array();
	foreach ($lines as $line) {
		$line = trim($line); // remove pesky crap
        $fields = explode(',',$line);
        //echo dumper($fields);
        
        
        $cardID=$fields[2];
        //echo dumper($cardID);
        
        if(!empty($cardID)){
            // see if there is a matching record in the clients table
            $sql = 'select client_id from clients where myob_card_id="'.$cardID.'"';
            $res = do_query($sql);
            if($res){
               $found[] = $res[0]['client_id'];
                
            }
             
        }
        
		
		
		
	}
	echo count($found);
	echo dumper($found);
               exit;   
}


?>
