<h1>Your order has been saved and sent for processing.</h1>
<p>Orders are usually processed within 1-2 days.</p>
<p>We will contact you if any delay is expected</p>
<?php
    if(is_array($saved_orders) && count($saved_orders) > 0){
        $o =  '<h3>The following order/items on order and awaiting processing:</h3>';
        $o .=  '<table>';
        $o .= ' <tr>
                
                <th width="120">Product Code</th>
                <th width="80">Qty</th>
                <th width="400">Description</th>
                </tr>';
        $last = '';
        foreach($saved_orders as $so){
            
            if($so['order_id'] != $last ){
                $o .= '<tr height="34">
                        <td colspan="4" style="border-top:1px solid #333;">Order ref: &nbsp;<strong>'.$so['order_id'].'</strong> ('.$so['order_date'].')</td></tr>';
            } 
                
            $o .= '<tr>';
                //$o .=  '<td>'.$so['order_id'].'</td>';
                $o .=  '<td>'.$so['product_code'].'</td>';
                $o .=  '<td>'.$so['qty'].'</td>';
                $o .=  '<td>'.$so['description'].'</td>';
                $o .= '</tr>';
                
            $last = $so['order_id'];
        
        }
        $o .= '</table>';
    }
    echo $o;
?>
 