<?php
exit;
    // Initial setup //
    require_once'../adodb_lite/adodb.inc.php';
    include_once('../lib/db.inc');
    include_once('../lib/common.inc');


   


    $lines = file("special_prices.csv");

    // remove all current special prices
    $db->Execute("delete from client_prices");

    foreach ($lines as $line) {
        //echo $line."<br>\n";
        list($client_id, $product_code,$client_price) = explode(",",trim($line));




        if (strlen($product_code) < 1 ) {
            continue;
        }



        $sql = "insert into client_prices (`client_id`,`product_code`,`client_price`,`modified`) VALUES ($client_id , '$product_code',$client_price,'2013-04-17')";

       // echo $sql ."<br>\n"; //exit;
        //$db->debug = true;
        $db->Execute($sql);
        //$db->debug = false;

    }
    echo "done";

?>