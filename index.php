<?php


include_once('../config.php');


define('LOG_ERRORS', true); // set true to log errors to following file

define('LOG_ERROR_FILE', 'k9.errors.log');


define("TERMINALID", 'T0'); // legacy define

define("STOCK_QTY_OFFSET", 0); // Fudge figure that is added to real stock quantity for display on reps screen



// Initial setup //

//require_once 'adodb_lite/adodb-errorhandler.inc.php';

require_once 'adodb_lite/adodb.inc.php';

include_once 'lib/db.inc';

include_once 'lib/common.inc';

require_once 'lib/State.class.php';

include_once 'lib/session.php';


// Collect Request Vars
$req = http_request(); // Populate global var $req from $_GET and $_POST

// if($_POST){
//     echo dumper($_POST);
//     exit;
// }


// Default template to use - controllers may override
$template = "templates/main";


if (isset($_SESSION['S']) && !empty($_SESSION['S'])) {

    $S = $_SESSION['S'];

    $S->setDB($db); // need to refresh the objects DB connection

    $nextview = '';

} else {

    $S = new State($db); // need to pass database object for class to use

}

// if a view is requested then safe to set nextview to it - it may get altered later

if (isset($req['v'])) {

    $S->nextview = $req['v'];
}


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



if ($S->getUserRole() == "rep" or $S->getUserRole() == "client" or $S->getUserRole() == 'manager') {



    include "controllers/client.controller.inc.php";
} elseif ($S->role == "admin") {

    include "controllers/admin.controller.inc.php";
} else {

    include "controllers/public.controller.inc.php";



    if (isset($chain_to_controller)) {

        include $chain_to_controller;
    }
}



/* Hack to force the add email address to client if not set */

if ($S->isInternalUser() && isset($S->client['client_id']) && empty($S->client['login_user'])) {

    $S->nextview = 'get_client_email';
}



/*

 * View Controller

 * if $S->nextview has been set then use that value

 * else  if $req['v'] has been set then use that

 * else if $S->lastview is set use that

 * else just default to the default

 */



if ($S->nextview > "") {

    // use it

} elseif ($S->lastview > "") {

    $S->nextview = $S->lastview;
} else {

    $S->nextview = "default";
}



// GET THE VIEW DATA

$categories = get_list_hierarchy("category", "order by display_order desc, name asc");

$categoryData = get_category_list();



// is there a modx page for this TYPE

$typeInfoPageIndex = get_type_info_page_index();



$k9users = get_k9users(); // so we can put a name to a reference_id on reports;



//echo $S->nextview;

//exit;



switch ($S->nextview) {

    case "basket":

        include "modules/basket.php";

        break;



    case "list_products":

    case "public_list_products":

        $S->display_mode = 'catid';

        if (isset($req['catid']) && $req['catid'] > 0) {

            include "modules/list.php";
        } elseif (isset($req['q'])) {

            include "modules/product_search.php";
        } else {

            include "modules/list.php"; // we will show specials if no caitid and no search query string

        }

        break;



    case "select_client":

        if ($S->isInternalUser()) {

            $clients = get_clients($S->id); // get this reps clients for client selector

            //echo dumper($clients);

        }

        break;



    case "list_all_baskets":

        $basket_orders = get_orders("basket");

        break;



    case "list_clients_orders":

    case "sales_report":



        // change view from list_clients_orders if logged in user is a client

        if ($S->is_valid_client()) {

            include "modules/order_for_client.php";

            $S->nextview = "list_client_orders";
        } else {

            include "modules/order.php";
        }



        break;

    case "client_orderview":

        // Check if $REQUEST has a order param (if yes it is base64 encoded)

        // Need top extract order_id from order param

        if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {

            $query = base64_decode($_REQUEST['order']);

            $params = array();

            parse_str($query, $params);

            // set the params for below

            $orderId = $params['order_id'];



            $order = get_order_detail($orderId);

            $special_prices = get_client_price_specials($order[0]['client_id']);

            $order_detail = get_system_order_details($order[0]['order_id']);
        } else {

            die('invalid request');
        }

        //echo $S->nextview;



        break;



    case "orderview":



        if ($S->is_valid()) {

            $order = get_order_detail($req['order_id']);

            $special_prices = get_client_price_specials($order[0]['client_id']);

            $order_detail = get_system_order_details($order[0]['order_id']);



            if (isset($req['print']) && $S->checkPrivileges('printorders')) {

                // requesting printer friendly view

                // only set sttus to PRINTED if it is Darren or Kerry viewing the order

                update_order_status($req['order_id'], "printed");
            } else {

                //$error_msg = "you dont have permission to print orders";

                //$S->nextview = "default";

            }
        }

        break;

    case 'edit_clients_order':

        // process the update if a POST

        include 'modules/edit_clients_order.php';



        //redisplay the edit_clients_order view

        $order = get_order_detail($req['order_id']);

        $special_prices = get_client_price_specials($order[0]['client_id']);

        $order_detail = get_system_order_details($order[0]['order_id']);



        break;

    case 'list_all_products':

        $S->display_mode = 'showall';



        $req['q'] = 'all'; // causes product_search to return all products for darrens showall option

        include "modules/product_search.php";

        $S->nextview = "list_all_products";

        break;



    case "product_search":



        include "modules/product_search.php";



        // check if someone logged in - k9user or client

        if ($S->is_valid()) {

            if ($req['q'] == 'all') {

                $S->display_mode = 'showall';

                $S->nextview = "list_all_products"; // Darrens new showall mode

            } else {

                $S->display_mode = 'catid';

                $S->nextview = "list_products"; // just use the same template for search or browse

            }
        } else {

            $S->nextview = "public_list_products"; // just use the same template for search or browse

        }

        break;



    case "contact_notes":
    case "contact_report":

        include "modules/contacts.php";

        break;



    case "edit_contact_note":

        include "modules/edit_contact_note.php";

        break;



    case "record_start_mileage":

        include "modules/mileage.php";

        break;

    case "record_end_mileage":



        break;

    case "runsheet":

        include "modules/runsheet.php";

        break;

    case 'stockcountview':

        include "modules/stockcount.php";

        break;

    case 'get_client_email':



        break;

    case 'client_special_prices':

        include "modules/clientprices.php";

        break;

    case 'ordersaved':

        include "modules/ordersaved.php";

        break;

    case "default":

        include "modules/list.php";

        break;



    default:
}



/*

 * Now Render the View

 */

// echo dumper($S->nextview);



include $template . ".inc";



// Save the state

$S->lastview = $S->nextview; // remember the page we have just displayed



unset($S->db); // no point saving database connection info - re-establishe when restoring $S

unset($S->nextview); // clear

unset($S->newLogin); // Clear

unset($S->module); // clear



$T = $S; // Hack because the following line if PHP5 causes $S to be serialised as well

//$_SESSION['S']=$S; // for php4 = serialise($S)

$S->save();



if ($T->id > 0 && !empty($T->role)) {

    $_SESSION['PASS']['user'] = $T->id;

    $_SESSION['PASS']['role'] = $T->role;

    $_SESSION['PASS']['privileges'] = $T->privileges;
} else {

    unset($_SESSION['PASS']);
}



clearTmpSessionVars();

//echo dumper($S);

//echo dumper($_SESSION);
