<?php
// Initial setup //
date_default_timezone_set('Australia/Melbourne');
    // Initial setup //
    require_once'../adodb_lite/adodb.inc.php';
    include_once('../lib/db.inc');
    include_once('../lib/common.inc');

$db_debug = 1;

$message ='';
if($_POST && !empty($_POST['order_id']) && (0 || $_POST['key']=='Kh56D6en')){
    $orderId = (int) $_POST['order_id'];
    if($orderId > 0 ){
        $sql = 'select * from system_order_items where order_id="T0_'.$orderId.'"';
        $items = do_query($sql);
        
        echo dumper($items);
        
        
        
        
        
        
        if(is_array($items) && count($items) > 0){
            foreach($items as $i)
            {
                // update the stock figure with the items deleted from order
                if ($i['qty'] > 0){
                     $sql = ' UPDATE products set qty_instock = qty_instock - '.$i['qty'].' where product_code="'.$i['product_code'].'" LIMIT 1';
                     $res3 =do_query($sql);
                }
               
            }
        }
        
    }
    


}

if(!empty($message)){
    echo $message;
}




    
?>
<form action="" method="post">
Order ID to adjust stock- ie deduct ordered items from  stock count:  eg 17654 <input name="order_id" />
<br />
Key (khx): <input type="password" name="key" /><br />
<input type="submit" name="b" value="Process" />
</form>