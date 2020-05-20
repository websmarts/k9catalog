<?php

/* 
 *Controller used where the authenticated userRole = "client" - i.e sales rep
 */

// CRUD Controller
switch( strtolower($req['m']) ) {
	
		case "type":
			include("admin/modules/".$req['m'].".php");
			$S->nextview = "edit_type";
			break;
		case "cat":
			include("admin/modules/".$req['m'].".php");
			$S->nextview = "default";
			break;
		case "product":
			include("admin/modules/".$req['m'].".php");
			if ($req['a'] == 'edit' or $req['a']=='update'){
				$S->nextview = "edit_product";
			} elseif ($req['a'] == "add") {
				$S->nextview = "add_product";
			} else {
				$S->nextview = "default";	
			}
			break;
		
			

			
	    case "logout":
		    $S->logout(); // also saves client basket
		     
		    break;
            
		default:
            include("admin/modules/default.php");
            $S->nextview = "default";    
               
	
}

/*
 ** REPS need to select a CLIENT_ID to work with so if  one is not selected - force the select client view
 * Except for the view list_all_baskets which is displayed with NO client_id set
 */
 
 
 

if ($S->role == "rep") 
{
	
	if (!isSet($S->client_id) and ($S->nextview != "list_all_baskets" and $S->nextview !="list_clients_orders"  and $S->nextview !="orderview") ) { // list baskets 
		
		$S->nextview = "select_client"; // allow rep to first choose a client
	}
}
elseif ($S->role =="client") 
{ // this happens when client does logout
	if (!isSet($S->client_id) ) { // show public face 
		// This selects the view displayed after a client logs out
		$S->nextview = "default";
	}
}


	if ($S->role == "admin" ) {

		$template = "templates/admin_main"; // note ADMIN template
	} else {
		// we get here when admin logs out - switch back to std template
		$template = "templates/main"; 
	}
		
?>