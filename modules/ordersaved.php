<?php

$saved_orders = array();

$clientId = $S->getClientId();

if ($clientId > 0) {
    $sql = '

    SELECT SOI.*, P.`description`,SO.modified as order_date

    FROM system_orders SO

    JOIN system_order_items SOI on SOI.order_id=SO.order_id

    JOIN products P on P.product_code=SOI.product_code

    WHERE SO.`status`="saved" 

    AND SO.client_id=' . $clientId .'

    ORDER BY SO.id asc';

    

    $saved_orders = $db->GetArray($sql);

    if( (is_array($saved_orders) && count($saved_orders) < 1) || !$saved_orders){

        log_error();

    }

}













?>