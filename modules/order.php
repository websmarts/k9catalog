<?php

// order.php
// process order data


// DEBUGGING echo dumper ($req);



function delete_orders($order_ids) {
	//echo dumper($order_ids);
	foreach ($order_ids as $order_id=>$client_id) {
		// delete the order items
		do_query( "delete from order_items where order_id=$order_id ");
		// delete the order
		do_query("delete from orders where order_id=$order_id ");
		
	}
}

function send_orders ($order_ids) {	
	
	// we prefix system_order.order_id with  TERMINALID to ensure uniqueness
	
	
	
	// $order_ids is a list of local orders that are to be 'sent' to the system_orders table
	foreach ($order_ids as $order_id =>$client_id) {
		$order_items = do_query("select * from order_items where order_id=$order_id");
		$order_details = get_order_details($order_id);
		
		//echo dumper($order_details);
		//echo dumper($order_items);
		
		// if order status is already 'posted' then skip
		if ($order_details['status'] == 'posted') {
			continue;
		}
		
		
		if (count($order_items) > 0 || $order_details['instructions'] > "") { // ignore if no items and no instructions
			// transfer the order to system_order table
			$sql = 	"replace into system_orders (order_id,status,client_id,instructions,modified,reference_id) ".
							" VALUES (".
							"'".TERMINALID."_".$order_details['order_id']."',".
							"'new',".
							$order_details['client_id'].",".
							"'".addslashes($order_details['instructions'])."',".
							"'".$order_details['modified']."',".
							"'".$order_details['order_id']."')";
							
			
			$res = do_query($sql);			
			
		}
		
		
		
		
		// now add the items to the system_order_items table
		if (is_array($order_items) && count($order_items > 0)) { // dont do this if no order items!!
			foreach ($order_items as $v) {
				$sql = "insert into system_order_items (order_id,product_code,qty,price) VALUES ('".TERMINALID."_".$order_details['order_id']."','".$v['product_code']."',".$v['qty'].",".$v['price'].")";
				//echo $sql."<br>\n";
				
				// do a bit of houskeeping and changethe status of 'old' printed orders to 'archive' status
				$sql2 = "UPDATE system_orders set status='archived' where DATE_ADD(modified,INTERVAL 7 DAY ) < NOW()";							
				$res = do_query($sql);					
				// archive old printed orders
				do_query($sql2);			
				
								
				// add the qty ordered to the products.qty_ordered field
				$sql = "update products set qty_ordered=(qty_ordered+".$v['qty'].") where product_code='".$v['product_code']."'";
				//echo dumper($sql);
				
				
				$res = do_query($sql);			
				
			
			}
		}
		
		// now set the status of the local order to POSTED
		$sql = "update orders set status='posted' where order_id=".$order_details['order_id'];
		$res = do_query($sql);
		
		
		
	}			
}

/* END OF FUNCTIONS */

/*
* Main
*/
$categories= get_list_hierarchy("category","order by name asc");



if (strtolower($req['a']) == "process" ) { // the submit button was clicked	
	if (is_array($req['do']) ) { // some items were  checked on the form		
		if (strtolower($req['b']) == "delete") { // 			
			$result = delete_orders($req['do']);
		} elseif (strtolower($req['b']) == "send") {
			send_orders($req['do']);
		}
	}
}

if($S->isInternalUser()){
    $clientID = 0;
} else {
    $clientID = $S->getClientId();
}

// Get the data needed for the display view
$orders = get_orders("saved",$clientID);
$basket_orders = get_orders("basket",$clientID);
$sales_report = get_sales_report($clientID);

 
    
$new_orders = get_system_orders("saved"," order by system_orders.id desc, system_orders.order_id desc ",$clientID); // saved orders are new orders - waiting to be printed
$printed_orders = get_printed_system_orders("printed"," order by system_orders.id desc, system_orders.order_id desc ",300,$clientID);//printed orders are waiting to be picked
//$picked_orders = get_printed_system_orders("picked"," order by system_orders.id desc, system_orders.order_id desc ",8,$clientID); // picked 


$picked_orders = get_picked_orders(60); // picked but not exported yet in lat 30 days
$completed_orders = get_completed_orders(8); // param = days





//echo dumper($picked_orders);
/*
if (is_array($printed_orders) and is_array($new_orders) ){
	$all_orders = array_merge($new_orders,$printed_orders);
} elseif (is_array($new_orders) ) {
	$all_orders = $new_orders;
} else {
	$all_orders = $printed_orders;
}
if(is_array($picked_orders)){
    $all_orders = array_merge($all_orders,$picked_orders);
}
*/







?>