<?php

// order.php
// process order data


// DEBUGGING echo dumper ($req);


if($_POST){
   // echo pr($req);
   // exit;

    return_order_items_to_stock($req['order_id']);

    

    //echo dumper($currentItems);



    $doneCodes = array();
    if(is_array($req['product_code']) && count($req['product_code'])){
        foreach($req['product_code'] as $key => $pcode){
            $qty = $req['qty'][$key] + 0;


            $price = 100 * (float) $req['price'][$key] ;


            // check product code exists
            $product = do_query('SELECT product_code from products where product_code="'.$pcode.'"');


            if(!empty($product[0]['product_code']) &&  $qty > 0 && !empty($pcode) && !in_array($pcode,$doneCodes)){
                $sql = 'insert into system_order_items (order_id,product_code,qty,price,qty_supplied) VALUES ("'.$req['order_id'].'","'.$pcode.'",'.$qty.','.$price.',0)';
                // echo dumper($sql);
                do_query($sql); 
                
                $doneCodes[$pcode]=$pcode; // flag we have done this pcode in case duplicate NEW pcode entered
            }




        }

    }
    // Update instructions
    do_query('update system_orders set instructions="'.$req['instructions'].'" where order_id="'.$req['order_id'].'"');
    
    take_order_items_out_of_stock($req['order_id']);

    if($req['b'] =='Save and Close'){
        update_order_status($req['order_id'],"picked");
        header('Location: ?v=list_clients_orders');

        // redirect to order veiw list
    }


}

$products = do_query('select product_code from products where `status`="active" order by product_code asc');


?>