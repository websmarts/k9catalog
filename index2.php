<?php
/**
*  user priviliges:
*  cosreport - view cost of sales report
*  printorders - can print orders
*  changeprices - can update prices in basket view
*/


error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(0);

define("TERMINALID", 'T0');       // legacy define
define("STOCK_QTY_OFFSET", 1000); // Fudge figure that is added to real stock quantity for display on reps screen

// Initial setup //
require_once 'adodb_lite/adodb.inc.php';
include_once('lib/db.inc');
include_once('lib/common.inc');
require_once 'lib/State.class.php';


// Collect Request Vars
$req=http_request(); // Populate global var $req from $_GET and $_POST

//echo dumper($req);
//exit;

// Start the session and retore sesson vars unless printing - session headers scrwe up printing
ini_set('session.use_cookies', 1);
ini_set('sesion.use_only_cookies', 1);
session_name('K9SESSION');
session_start();

// Dfeault template to use - controllers may override
$template="templates/main";


//echo "START SESSION<br>";
//echo dumper ($_COOKIE);
//echo dumper($_SESSION);

if (isSet($_SESSION['S']) && !empty($_SESSION['S']))
    {
    $S=unserialize($_SESSION['S']); // restore state object
    $S->setDB($db);                 // need to refresh the objects DB connection
    unset ($S->nextview);           // Next view  will be calculated make sure it starts off blank


    // if a view is requested then safe to set nextview to it - it may get altered later
    if (isSet($req['v']))
        {
        $S->nextview=$req['v'];
        }
    }
else
    {
    $S=new State($db); // need to pass database object for class to use
    }

$db->debug=false;      // set to true to see database querys


/*
 * Invoke the appropriate Controller
 * Options based on authenticated user status
 * Default controller used where no authenticated user role exists - i.e. public access
 * Client controller where authenticated user has a role of client
 * Rep controller where authenticated user has a rle of rep
 * Admin cntroller if authenticated users role is admin
 */

// use the client controller for both rep and client roles
//echo $S->getUserRole;

if ($S->getUserRole() == "rep" or $S->getUserRole() == "client" or $S->getUserRole() == 'manager')
    {

    include("controllers/client.controller.inc.php");
    }
elseif ($S->role == "admin")
    {
    include("controllers/admin.controller.inc.php");
    }
else
    {
    include("controllers/public.controller.inc.php");

    if (isSet($chain_to_controller))
        {
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

if ($S->nextview > "")
    {
    // use it
    }
elseif ($S->lastview > "")
    {
    $S->nextview=$S->lastview;
    }
else
    {
    $S->nextview="default";
    }

// GET THE VIEW DATA
$categories=get_list_hierarchy("category", "order by name asc");
$categoryData=get_category_list();

//echo dumper ($S);
switch ($S->nextview)
    {
    case "basket":
        include("modules/basket.php");
        break;

    case "list_products":
    case "public_list_products":
        if (isSet($req['catid']) && $req['catid'] > 0)
            {
            include("modules/list.php");
            }
        elseif (isSet($req['q']))
            {
            include("modules/product_search.php");
            }
        else
            {
            include("modules/list.php"); // we will show specials if no caitid and no search query string
            }
        break;

    case "select_client":
        if ($S->isInternalUser())
            {
            $clients=get_clients($S->id); // get this reps clients for client selector
            }
        break;

    case "list_all_baskets":
        $basket_orders=get_orders("basket");
        break;

    case "list_clients_orders":
    case "sales_report":
        include("modules/order.php");

        // change view from list_clients_orders if logged in user is a client
        if ($S->is_valid_client())
            {
            $S->nextview="list_client_orders";
            }

        break;

    case "orderview":
        if ($S->is_valid())
            {
            $order=get_order_detail($req['order_id']);
            $special_prices=get_client_price_specials($order[0]['client_id']);

            if (isSet($req['print']) && $S->checkPrivileges('printorders'))
                { // requesting printer friendly view
                // only set sttus to PRINTED if it is Darren or Kerry viewing the order
                update_order_status($req['order_id'], "printed");
                }
            else
                {
                //$error_msg = "you dont have permission to print orders";
                //$S->nextview = "default";
                }
            }
        break;

    case "product_search":
        include("modules/product_search.php");

        // check if someone logged in - k9user or client
        if ($S->is_valid())
            {
            $S->nextview="list_products"; // just use the same template for search or browse
            }
        else
            {
            $S->nextview="public_list_products"; // just use the same template for search or browse
            }
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
include($template . ".inc");


// Save the state
$S->lastview=$S->nextview; // remember the page we have just displayed

unset ($S->db);            // no point saving database connection info - re-establishe when restoring $S
unSet ($S->nextview);      // clear
unSet ($S->newLogin);      // Clear
unSet ($S->module);        // clear

$T=$S;                     // Hack because the following line if PHP5 causes $S to be serialised as well
$_SESSION['S']=serialize($S);

if ($T->id > 0 && !empty($T->role))
    {
    $_SESSION['PASS']['user']=$T->id;
    $_SESSION['PASS']['role']=$T->role;
    }
else
    {
    unset($_SESSION['PASS']);
    }
//echo dumper($S);
//echo dumper($_SESSION);
?>