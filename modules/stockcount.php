<?php

// 
// get stockcount details


if(!empty($req['date']) && !empty($req['client_id']) && !empty($req['rep_id'])){
    // Get a list of any instore stocktakes taken today
    $sql = ' SELECT 
    clientstock.*, 
    clients.name as clientname, 
    users.name as repname, 
    products.color_name as pcolor, 
    products.size as psize, 
    products.description as pdescription 
    FROM clientstock
    JOIN clients ON clients.client_id=clientstock.client_id
    JOIN users ON users.id = clientstock.user_id
    JOIN products on products.product_code = clientstock.product_code 
    WHERE 
    DATE(clientstock.datetime)="'.$req['date'] .'" 
    AND clientstock.client_id='.$req['client_id'] .'
    AND clientstock.user_id='.$req['rep_id'] .'
    ORDER BY clientstock.product_code asc';

$stockcount = do_query($sql);
}


// Order history
$sql = "select  
            orders.order_id,
            items.product_code, 
            products.description as description,
            type.name as type, 
            sum(qty)as tqty,orders.client_id 
            from 
            system_order_items as items
            join system_orders as orders on items.order_id = orders.order_id
            join products on items.product_code = products.product_code
            join `type` on products.typeid = type.typeid 
            where orders.client_id =".$req['client_id'] ."
            and DATE_SUB(NOW(),INTERVAL 12 MONTH) < orders.modified
            group by product_code ";
$res = do_query($sql);
$orderhistory = array();
if(is_array($res) && count($res)){
    foreach($res as $k=>$v){
        $orderhistory[$v['product_code']] = $v['tqty'];
    }
}

    

