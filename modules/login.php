<?php
// login handler

//$db_debug = true;

if ($req['a'] == "select_client") {	
	$_SESSION['client_id'] = $req['client_id'];
	restore_client_basket();
}

if ($req['a'] == "logout") {
	// if the basket contains items then save them as a basket order
	if (isSet($_SESSION['basket']) && count($_SESSION['basket']) > 0) {	
		save_basket("basket");// saves an open order
		
	} else {
		// Basket may have been CLEARED but there may still be a basket in the database for this client - so delete if there is
		delete_basket($_SESSION['client_id']); 
		
	}
	unset ($_SESSION['client_id']) ;
	unset ($_SESSION['basket']) ;
	unset ($_SESSION); // kill everything !!
}

//$clients= do_query("select client_id,name from clients order by name asc");
$clients = $db->GetArray("select client_id,name from clients order by name asc");

//$db_debug = false;
?>