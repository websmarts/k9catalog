<?php
// Initial setup //
include_once('lib/common.inc');
include_once('lib/db.inc');

include_once('config.inc');
include_once('terminalid.inc');
require_once'lib/State.class.php';

$res = $db->Execute("select * from category");
echo dumper($res);
exit;




// Collect Request Vars 
$req = http_request(); // Populate global var $req from $_GET and $_POST



// Start the session and retore sesson vars unless printing - session headers scrwe up printing
session_start();    


//echo "START SESSION<br>";
//echo dumper ($_SESSION);



if (isSet($_SESSION['S']) ) {
	$S = $_SESSION['S']; // restore state object
	$S->setDB($db); // need to refresh the objects DB connection
	unset($S->nextview); // Next view  will be calculated make sure it starts off blank
	
	
	// if a view is requested then safe to set nextview to it - it may get altered later 
	if (isSet($req['v']) ) {
		$S->nextview = $req['v'];
	}
	
} else {
	$S = new State($db); // need to pass database object for class to use	
}

//echo dumper($S);


//echo "index.php line 38 lastview=".$S->lastview."<br>\n";




$db->debug = false; // set to true to see database querys


/*
 * Invoke the appropriate Controller
 * Options based on authenticated user status
 * Default controller used where no authenticated user role exists - i.e. public access
 * Client controller where authenticated user has a role of client
 * Rep controller where authenticated user has a rle of rep
 * Admin cntroller if authenticated users role is admin
 */
 
 // use the client controller for both rep and client roles
 if ($S->role =="rep" or $S->role=="client" ) {
 	include("controllers/client.controller.inc.php");
 } elseif ($S->role == "admin" ) {
 	include("controllers/admin.controller.inc.php");
 }else {
 	include("controllers/public.controller.inc.php");
 	if (isSet($chain_to_controller) ) {
 		include($chain_to_controller);
 	}
}

/*
 * View Controller
 * if $S->nextview has been set then use that value
 * else  if $req['v'] has been set then use that
 * else if $S->lastview is set use that
 * else just default to the default
 */
 
if ($S->nextview > "" ) {
	// use it
} elseif  ( $S->lastview > "") {
	$S->nextview= $S->lastview;
} else {
	$S->nextview = "default";
}

// GET THE VIEW DATA
$categories= get_list_hierarchy("category","order by name asc");


	switch ($S->nextview) {
		case"basket":
			include("modules/basket.php");
			break;
		case"list_products":
		case"public_list_products":
			include("modules/list.php");
			break;
		case "select_client":
			$clients = get_clients($S->id); // get this reps clients for client selector
			break;
		case "list_all_baskets":
			$basket_orders = get_orders("basket");
			break;
		case "list_clients_orders":
			include("modules/order.php");
			break;
		case "orderview":
			$order = get_order_detail($req['order_id']);
			break;
		case "product_search":
			include("modules/product_search.php");
			$S->nextview = "list_products"; // just use the same template for search or browse
			break;
		case "specials":
			include("modules/list.php");
			break;
		case "default":
			include("modules/list.php");	
			break;
		default:
		
	}
 



/*
 * Now Render the View
 */
 	//echo dumper($S);
 	
 	
// 	echo "index.php 124 Displaying view ::=".$S->nextview."<br>\n";
 
 
  include($template.".inc");

        
// Save the state
$S->lastview = $S->nextview; // remember the page we have just displayed

unset ($S->db); // no point saving database connection info - re-establishe when restoring $S
unSet($S->nextview ); // clear 
unSet($S->newLogin); // Clear 
unSet($S->module); // clear


$_SESSION['S'] = $S;

?>
