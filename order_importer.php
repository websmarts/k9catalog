<?php
die('beam me up scottie');
define('LOG_ERRORS', true); // set true to log errors to following file
define('LOG_ERROR_FILE', 'k9.errors.log');

/**
 *  user priviliges:
 *  cosreport - view cost of sales report
 *  printorders - can print orders
 *  changeprices - can update prices in basket view
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(0);

date_default_timezone_set('Australia/Melbourne');

define("TERMINALID", 'T0'); // legacy define
define("STOCK_QTY_OFFSET", 0); // Fudge figure that is added to real stock quantity for display on reps screen

// Initial setup //
//require_once 'adodb_lite/adodb-errorhandler.inc.php';
require_once 'adodb_lite/adodb.inc.php';
include_once 'lib/db.inc';
include_once 'lib/util_common.inc';
require_once 'lib/State.class.php';

$S = new State($db); // need to pass database object for class to use

// *******CONFIG ********
$orderDate = '2017-09-04 00:00:00'; // Orders will be saved with this orderdate
$salesRep = 6; // saved with Darren as salesRep
$checkSpecialPrices = false; //use import prices - set to true if you want to look up special prices

/**
 * $sheets[0] = array(
'datafile'=>'./data/bulk_orders.csv',
'priceSet'=>true,
'stdPrices'=>false
);
$sheets[1] = array(
'datafile'=>'./data/bulk_orders2.csv',
'priceSet'=>true,
'stdPrices'=>false
);
$sheets[2] = array(
'datafile'=>'./data/bulk_orders3.csv',
'priceSet'=>false,
'stdPrices'=>true
);

$sheets[3] = array(
'datafile'=>'./data/bulk_orders4.csv',
'priceSet'=>false,
'stdPrices'=>true
);
 **/
// ***** END CONFIG *****

$sheets[0] = array(
    'datafile' => './data/bulk_orders_2017.csv',
    'priceSet' => true,
    'stdPrices' => false,
);

$numOrders = 0;
foreach ($sheets as $sheet) {
    echo 'Importing from ...' . $sheet['datafile'] . '<br/>';

// Import data from spreadsheet
    $datafile = $sheet['datafile'];
    $priceSet = $sheet['priceSet'];
    $stdPrices = $sheet['stdPrices'];

    $lines = file($datafile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $clients = array_slice(preg_split('/,/', array_shift($lines)), 2);
    //echo pr($clients);exit;

    $orders = array();
    $n = 1;
    foreach ($clients as $clientName) {

        if (empty($clientName)) {
            continue;
        }
        $client = get_client_by_name($clientName);

        if (!$client) {
            continue;
        }

        $cid = $client[0]['client_id'];

        if (!$cid) {
            continue;
        }
        $n++;
        foreach ($lines as $line) {
            $record = preg_split('/,/', $line);
            //echo pr($record);exit;
            if ($record[$n] > 0) {

                $price = substr($record[1], 2, 9);
                $orders[$cid][$record[0]] = array('qty' => $record[$n], 'price' => $price);

            }

        }
    }

// Foreach order
    $req = array();
    foreach ($orders as $cid => $items) {
        //echo $cid.',';
        $client = get_client_details($cid);
        $S->client = $client[0];

        //echo pr($S); exit;

        $req['basket'] = $items;
        $req['basket_instructions'] = 'Bulk Import';
        $req['order_contact'] = 'Bulk Import';
        //echo pr($req);exit;
        update_basket($req, $priceSet);

        //echo pr($S);exit;

        save_basket($orderDate, $salesRep, $stdPrices);

        save_order($orderDate, $salesRep);
        $S->clearBasket();

        $numOrders++;

    }

}

echo 'done, imported ... ' . $numOrders . ' Orders';
