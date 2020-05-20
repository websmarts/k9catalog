<?php

$sql = 'select c.name,so.* from system_orders so
join clients c on c.client_id=so.client_id
where so.`status`="basket"  ';
$res = $db->GetArray($sql);

//echo dumper($res);

if(count($res)> 0 ) {
    echo '<table>' ;
    echo '<tr>';
    echo '<th>Order ID</th>';
    echo '<th>Client</th>';
    echo '<th>K9 Rep</th>';
    echo '<th>Date time</th>';
    echo '<th>No Items</th>';
    echo '<th>&nbsp;</th>';   
    echo '</tr>' ;
    
    foreach($res as $r) {
       echo '<tr>';
       echo '<td>'.$r['order_id'] .'</td>';
       echo '<td>'.$r['name'] .'</td>';
       echo '<td>'.$k9users[$r['reference_id']] .'</td>';
       echo '<td>'.$r['modified'] .'</td>';
       $items = get_order_items($r['order_id']);
       echo '<td align="right">';
       echo count($items);
       echo '</td>';
       echo '<td width="100" align="right"><a href="?view_basket_report&basket_id='.$r['order_id'].'"> view </a></td>';
       echo '</tr>';
    }
    echo '</table>';
    
    if(!empty($req['basket_id'])){
         $items = get_order_items($req['basket_id']);
         if(count($items) > 0 ){
             echo '<h3>Basket items for '.$req['basket_id'].'</h3>';
             echo '<table>
             <tr>
             <th>Product_code</th>
             <th>Qty</th>
             </tr>';
             foreach($items as $i){
                 echo '<tr>';
                 echo '<td>'.$i['product_code'].'</td>';
                 echo '<td>'.$i['qty'].'</td>';                               
                 echo '</tr>';
             }
             
             echo '</table>';
             
         } else {
             echo '<p style="color:red">Basket '.$req['basket_id'] . ' has no items in basket</p>';
         }
    }
    
}  else {
    echo '<p>No Baskets found</p>';
}

?>