<?php

echo "util currently disabled"; 
exit;
date_default_timezone_set('Australia/Melbourne');
    // Initial setup //
    require_once'../adodb_lite/adodb.inc.php';
    include_once('../lib/db.inc');
    include_once('../lib/common.inc');

function qt($str){
    global $db;
    if (substr($str,0,1) =='"'){
        // it looks like an excel extra quoting situation so remove extraeous quote
        $str= trim($str,'"');
        // replave "" with " inside str
        $str = preg_replace('/\"\"/','"',stripslashes($str));
    }
    return $db->qstr($str);
}
   


    $lines = file("products.csv");
    // get first line for field names
    $fieldnames =  $lines[0];
    unset ($lines[0]);
$n =0;
      foreach ($lines as $line) {
        echo $line."<br>\n";
        $data  = explode(",",$line);


        if (strlen($data[4]) < 1 ) {
            continue;
        }

        $sql = 'update products 
                set 
                
                `size`='.qt($data[2]).',
                `price`='. (int) $data[3].',
                `product_code`='.qt($data[4]).',
                `typeid`='. (int) $data[5].',
                `qty_break`='. (int) $data[6].',
                `qty_discount`='. (int) $data[7].',
                `qty_instock`='. (int) $data[8].',
                `qty_ordered`='. (int) $data[9].',
                `special`='. (int) $data[10].',
                `clearance`='. (int) $data[11].',
                `can_backorder`='.qt($data[12]).',
                `status`='.qt($data[13]).',
                `cost`='. (int) $data[14].',
                `last_costed_date`='.qt(date('Y-m-d',strtotime(str_replace('/','-',$data[15])))).',
                `display_order`='. (int) $data[16].',
                `barcode`='.qt($data[17]).',
                `color_name`='.qt($data[18]).',
                `color_background_color`='.qt($data[19]).',
                `shipping_weight`='. (int) $data[21].',
                `shipping_volume`='. (int) $data[22].',
                
                `shipping_container`='. (int) $data[23].',
                `notify_when_instock`='.qt($data[24]).',
                `source`='.qt($data[25]).',
                `new_product`='. (int) $data[26].',
                `core_product`='. (int) $data[27].'
                
                where `id` = '. $data[0];

        //echo "<pre>".$sql ."</pre><br>\n"; 
        //$db->debug = true;
        $db->Execute($sql);
        if($db->ErrorNo() > 0){
            echo $db->ErrorMsg();
        } else {
            $n++;
        }
        //$db->debug = false;
        
        if (0 && $n > 3){
            break;
        }


    }
    echo "done $n records";

?>