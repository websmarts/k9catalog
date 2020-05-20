<?php

// order.php
// process order data

// include xmlrpc class so we can communicate with remote system_server
include_once("../config.inc"); 
include_once("../XMLRPC.php");
include_once('../lib/db.inc');
include_once('../lib/common.inc');

define("XMLRPC_DEBUG",0); // debiugging off

// DEBUGGING echo dumper ($req);

function cleanup ($d) {
	return addslashes(html_entity_decode($d));
}


function get_myproducts() {
	 		// Get the remote products table and update the local table
 		// get the current modified date from the products table
 		$sql = "select max(modified)as local_timestamp from products";
 		$r = do_query($sql);
 		$local_timestamp = $r[0]['local_timestamp'];
 		
 		
 		
 		
		$sql = "select * from products where modified > '".$local_timestamp."'  ";
		list($success,$result) = rpc('do_select_query',$sql);
		//XMLRPC_debug_print();	

		if ($success) {
			
			if (is_array($result)) {
				foreach ($result as $p) {
					$sql = "replace into products (id,name,description,`option`,`size`,price,product_code,typeid,aus_made,qty_break,qty_discount,qty_instock,qty_ordered,special,clearance,modified)".
								 " VALUES ".
								 "(".$p['id'].",'".cleanup($p['name'])."','".cleanup($p['description'])."','".$p['option']."','".$p['size']."',".$p['price'].",'".cleanup($p['product_code'])."',".$p['typeid'].",".$p['aus_made'].",".$p['qty_break'].",".$p['qty_discount'].",".$p['qty_instock'].",".$p['qty_ordered'].",".$p['special'].",".$p['clearance'].",'".$p['modified']."')" ;
											 
					echo "p";
					$n++;
					if ($n > 70) {
						$n = 0;
						echo"<br>\n";
					}
					
					$res = do_query($sql);				
				}			
			}		
		}
}

function get_myclients() {

		// Get the remote clients table and update the local clients table
		// get the current modified date from the clients table
 		$sql = "select max(modified)as local_timestamp from clients";
 		$r = do_query($sql);
 		$local_timestamp = $r[0]['local_timestamp'];
		$sql = "select * from clients where modified > '".$local_timestamp."'";
		list($success,$result) = rpc('do_select_query',$sql);
		if ($success) {
			
			if (is_array($result)) {
				foreach ($result as $c) {
					$sql = "replace into clients (client_id,name,status,modified) VALUES (".$c['client_id'].",'".cleanup($c['name'])."','".$c['status']."','".$c['modified']."')";
					$res = do_query($sql);
					
					echo "c";
					$n++;
					if ($n > 70) {
						$n = 0;
						echo"<br>\n";
					}			
				}
			}
		}
		// to cleanup where clients have been deleted we should delete any clienst where modified < max(modified)
		// or maybe we should introduce a status field and not ever delete
}

function get_mycategories() {
	$sql = "select * from category";
	list($success,$result) = rpc('do_select_query',$sql);
	if($success){
		foreach ($result as $cat) {
			$sql = "replace into category (id,name,description,parent_id) VALUES (".$cat['id'].",'".cleanup($cat['name'])."','".cleanup($cat['description'])."',".$cat['parent_id'].")";
			$res= do_query($sql);
		}
	}
}
	
function get_mytypes() {
	$sql = "select max(modified)as local_timestamp from type";
 		$r = do_query($sql);
 		$local_timestamp = $r[0]['local_timestamp'];
 		
	$sql = "select * from type where modified > '".$local_timestamp."'";
	list($success,$result) = rpc('do_select_query',$sql);
	if($success){
		foreach ($result as $t) {
			$sql = "replace into type (typeid,name,display_format,aus_made,modified) VALUES (".$t['typeid'].",'".cleanup($t['name'])."','".$t['display_format']."','".$t['aus_made']."','".$t['modified']."')";
			//echo dumper($sql);
			$res= do_query($sql);
		}
	} else {
		echo "Error geting TYPE table data.<br>\n";
	}
}	

function get_mytype_options() {
	$sql = "select max(modified)as local_timestamp from type_options";
 		$r = do_query($sql);
 		$local_timestamp = $r[0]['local_timestamp'];
 		
	$sql = "select * from type_options where modified > '".$local_timestamp."'";
	list($success,$result) = rpc('do_select_query',$sql);
	if($success){
		foreach ($result as $t) {
			$sql = "replace into type_options (typeid,opt_code,opt_desc,opt_class,modified) VALUES (".$t['typeid'].",'".cleanup($t['opt_code'])."','".cleanup($t['opt_desc'])."','".cleanup($t['opt_class'])."','".$t['modified']."')";
			//echo dumper($sql);
			$res= do_query($sql);
		}
	} else {
		echo "Error geting TYPE_OPTIONS table data.<br>\n";
	}
}	

function get_mytype_category() {
	$sql = "select max(modified)as local_timestamp from type_category";
 		$r = do_query($sql);
 		$local_timestamp = $r[0]['local_timestamp'];
 		
	$sql = "select * from type_category where modified > '".$local_timestamp."'";
	list($success,$result) = rpc('do_select_query',$sql);
	if($success){
		foreach ($result as $t) {
			$sql = "replace into type_category (catid,typeid,modified) VALUES (".$t['catid'].",".$t['typeid'].",'".$t['modified']."')";
			//echo dumper($sql);
			$res= do_query($sql);
		}
	} else {
		echo "Error geting TYPE_CATEGORY table data.<br>\n";
	}
}	

function get_myclient_prices() {
	$sql = "select max(modified)as local_timestamp from client_prices";
 		$r = do_query($sql);
 		$local_timestamp = $r[0]['local_timestamp'];
 		
	$sql = "select * from client_prices where modified > '".$local_timestamp."'";
	list($success,$result) = rpc('do_select_query',$sql);
	if($success){
		foreach ($result as $t) {
			$sql = "replace into client_prices (client_id,product_code,client_price,modified) VALUES (".$t['client_id'].",'".cleanup($t['product_code'])."',".$t['client_price'].",'".$t['modified']."')";
			//echo dumper($sql);
			$res= do_query($sql);
		}
	} else {
		echo "Error geting CLIENT_PRICES table data.<br>\n";
	}
}					
	
		
		


/* END OF FUNCTIONS */

/*
* Main
*/

	get_myproducts();

	get_myclients();
	
	get_myclient_prices();
	
	get_mycategories();

	get_mytypes();
	
	get_mytype_options();
	
	get_mytype_category();




?>