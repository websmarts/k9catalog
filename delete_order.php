<?php

// Initial setup //
require_once './adodb_lite/adodb.inc.php';
include_once('./lib/db.inc');
include_once('./lib/common.inc');

$db_debug = false;

$message ='';
if($_POST && !empty($_POST['order_id']) && $_POST['key']=='Kh56D6en'){
    $orderId = (int) $_POST['order_id'];
    if($orderId > 0 ){
        $sql = 'select * from system_order_items where order_id="T0_'.$orderId.'"';
        echo dumper($sql);
        $items = do_query($sql);
        
        //echo dumper($items);
        
        
        
        $sql1 = "delete from system_orders where id=".$orderId .' LIMIT 1';
        echo dumper($sql1);
        $res1 = do_query($sql1);
        
        
        
        
        $sql2 = 'delete from system_order_items where order_id="T0_'.$orderId .'"';
        echo dumper($sql2);
        $res2 = do_query($sql2);

        $message = '<p>Order '.$orderId.  ' has been deleted </p>';
        
        
        if(is_array($items) && count($items) > 0){
            foreach($items as $i)
            {
                // update the stock figure with the items deleted from order
                if ($i['qty'] > 0){
                     $sql = ' UPDATE products set qty_instock = qty_instock + '.$i['qty'].' where product_code="'.$i['product_code'].'" LIMIT 1';
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
Order ID to delete eg 17654 <input name="order_id" value="<?php echo ++$orderId;?>" />
<br />
Key (khx): <input type="password" name="key" /><br />
<input type="submit" name="b" value="Delete Order" />
</form>