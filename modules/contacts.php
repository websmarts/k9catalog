<?php



// list.php

// get browse categories





//echo dumper($req);

// Hack to show contact notes for a client other than the selctected client
if(isSet($req['report_client_id']) ){
    $clientID = $req['report_client_id'];
} else {
    $clientID = $S->client['client_id'] ;
}


$client = do_query('select * from clients where client_id='.$clientID);




$sql=  'select 

        ch.call_type,

        ch.call_type_id,

        ch.call_datetime as calldate, 

        ch.note, c.contacts,

        u.name as callby, 

        ch.contacted 

        from contact_history as ch 

        join clients as c on ch.client_id=c.client_id

        left join users as u on u.id=ch.call_by

        where ch.client_id='. $clientID . ' 

        and ch.note >"" 

        order by call_datetime desc limit 20 ';

            

    $clientnotes=do_query($sql);

    

    

    // Get orders in the last 30 days to show in list

    $sql =' select * from system_orders where client_id='.$clientID .' AND DATE_SUB(NOW(),INTERVAL 1 YEAR) < system_orders.modified order by  `modified` desc ';

    
    $clientorders = do_query($sql);



    

    // Get a list of any instore stocktakes taken in last 30 days

    $sql = 'SELECT DISTINCT 

    users.name as repname,

    date_format(clientstock.datetime,"%Y-%m-%d") as stockcountdate, 

    clients.name AS client,

    clientstock.client_id as client_id, 

    users.id AS rep_id 

    FROM clientstock

    JOIN clients ON clients.client_id=clientstock.client_id

    JOIN users ON users.id = clientstock.user_id

    WHERE DATE_SUB(NOW(),INTERVAL 90 DAY) < clientstock.datetime 

    AND clientstock.client_id='.$clientID . ' ';

    

    

    

    

    if (isSet($req['k9user']) && (int) $req['k9user'] > 0){

        $sql .= ' and clientstock.user_id = '. (int) $req['k9user'];

    }

    $sql .= '  order by clientstock.datetime desc ';

    $clientstockcounts = do_query($sql);

    

    

    // Get any client notify mees that are now due

    // $sql = ' SELECT p.product_code,p.description,p.qty_instock,DATE_FORMAT(nme.submitted,"%e-%m-%Y")as requested from notify_me as nme

    //     JOIN products as p on p.product_code=nme.product_code

    //     WHERE nme.client_id='.$clientID. '

    //     and p.qty_instock > 0 order by p.product_code asc';

    $sql = ' SELECT p.product_code,p.description,p.qty_instock,DATE_FORMAT(nme.submitted,"%e-%m-%Y")as requested from notify_me as nme

    JOIN products as p on p.product_code=nme.product_code

    WHERE nme.client_id='.$clientID. '

    order by p.product_code asc';

    $products = do_query($sql);

    // echo dumper($products);

    $res = [];

 // if BOM check if bomQuantity Available
    foreach($products as $key => $product) {
        $qty = isBomAndAvailable($product['product_code']);

        if(isSet($qty['max_available']) && $qty['max_available'] > 0){
            $res[$product['product_code']] = $qty['max_available'];

            unset($products[$key]);

        }
            
    }

   

    // if not BOM then check product left in list for qty instock > 0
    foreach($products as $product) {
        if($product['qty_instock'] > 0) {
            $res[$product['product_code']] = $product['qty_instock'];
        }
            
    } 

// echo dumper($res);
// echo dumper(array_keys($res));
   
// Now get the product info we need for the avialable notifiy me's
 $sql = ' SELECT p.product_code,p.description,p.qty_instock,DATE_FORMAT(nme.submitted,"%e-%m-%Y")as requested from notify_me as nme

        JOIN products as p on p.product_code=nme.product_code

        WHERE nme.client_id='.$clientID. '

        and p.product_code in ("'. implode("\",\"",array_keys($res)) .'") > 0 order by p.product_code asc';


    //echo dumper($sql);

        

    $clientnotifymes = do_query($sql);
    
    // Update qty_instock for and BOMs found to be available
    foreach($clientnotifymes as &$n){
        if( isSet($res[$n['product_code']]) && $res[$n['product_code']] > 0 ){
            $n['qty_instock'] = $res[$n['product_code']];
        }
    }
    //echo dumper($clientnotifymes);  