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



// myPetWarehouse parent and masterbclient_id=3032;
// All 4 Pets parent and master client_id=1377;
// Mega Pet Warehous master client_id = 1649;
// Lypards master client_id= 1361;
// Provet master client_id = 1369;
$parentIds = array(3032,1377,1649,1361,1369);// Parents are the master for special prices

foreach ($parentIds as $parentId){
    $specials = $db->getArray("select product_code,client_price from client_prices where client_id=".$parentId);


    $clientIds = $db->getArray("select  client_id from clients where `status`='active' and parent=".$parentId ." and client_id !=".$parentId);

    //echo pr($clientIds);
    if (is_array($clientIds) && count($clientIds) > 0) {
        foreach ($clientIds as $k => $v) {

            $db->Execute("delete from client_prices where client_id=" . $v['client_id']); // delete all prices for this client

            // Now add the new special prices as per the master list
            if (is_array($specials) && count($specials) > 0) {
                foreach ($specials as $kk => $vv) {
                    $sql = "insert into  client_prices (client_id,product_code,client_price) ";
                    $sql .= ' VALUES (' . $v['client_id'] . ',"' . $vv['product_code'] . '",' . $vv['client_price'] . ')';
                    $db->execute($sql);
                    //echo pr($sql); exit;
                }
            }
        }
    }


}
