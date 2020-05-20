<?php
    // Initial setup //
    require_once'../adodb_lite/adodb.inc.php';
    include_once('../lib/db.inc');
    include_once('../lib/common.inc');





    $lines = file("mf.csv");

    foreach ($lines as $line) {
        //echo $line."<br>\n";
        list($product_code,$mf) = explode(",",$line);


        if (strlen($product_code) < 1 ) {
            continue;
        }

        $sql = "update products set source='$mf' where product_code='$product_code'";

        //echo $sql ."<br>\n"; 
        $db->debug = true;
        $db->Execute($sql);
        $db->debug = false;


    }
    echo "done";

?>