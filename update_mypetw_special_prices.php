<?php
/**
 * Copy  MyPetWarehouse - South Melbourne prices to
 * all other clients with the same parent
 */

//echo "Safe mode - exiting... nothing done";exit;

// Initial setup //
require_once'adodb_lite/adodb.inc.php';
include_once('lib/db.inc');
include_once('lib/common.inc');

$db->debug = true;

// myPetWharehouse client_id;
$masterClientId = 1378;
$parent = 1377;


$specials = $db->getArray("select product_code,client_price from client_prices where client_id=".$masterClientId);



//$clientIds = $db->getArray("select  client_id from clients where `status`='active' and parent=".$parent ." and client_id !=".$masterClientId);
$clientIds[] = array('client_id'=>1377);
//echo pr($clientIds);

foreach($clientIds as $k => $v){

    $db->Execute("delete from client_prices where client_id=" . $v['client_id']); // delete all prices for this client

    // Now add the new special prices as per the master list
    foreach($specials as $kk=>$vv){
        $sql = "insert into  client_prices (client_id,product_code,client_price) ";
        $sql .= ' VALUES ('. $v['client_id'] .',"'.$vv['product_code'].'",'.$vv['client_price'] .')';
        $db->execute($sql);
        //echo pr($sql); exit;
    }


}
