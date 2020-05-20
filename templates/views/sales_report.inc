<?php

	if (is_array($new_orders) && count($new_orders)> 0 && TERMINALID=='T0' ) {
        echo " <hr><h4>Purchase Orders</h4>";
        echo "<table width=800 >";
				
    		foreach ($new_orders as $order) {
              
               
	        			echo "<tr>\n";
	        			echo "  <td width=10% class=row$n >".$order['order_id']."</td>\n";
	        			echo "  <td width=30% class=row$n >".$order['name']."</td>\n";
	        			echo "  <td width=20% class=row$n >".date('j-m-Y g:i a',strtotime($order['modified']) )."</td>\n";
	              echo "  <td width=20% class=row$n align=center ><a href=\"?v=orderview&order_id=".$order['order_id']."&print\" >view</a></td>";
	              echo "  <td width=10% >".$order['status']."</td>\n";
	              echo "  <td width=10% >".number_format(get_system_order_total($order['order_id'])/100,2)."</td>\n";
	
	        			echo "</tr>\n";
	        		
      	         
    		}
    		echo '</table><hr>';
    }
    else 
    {
    	echo '<h2 style="color:#f00"> No new orders to print</h2><hr>';
    }
    if (is_array($printed_orders) && count($printed_orders)> 0 && TERMINALID=='T0' ) {
    	echo "<h3>Recently printed orders</h3>";
    	 echo "<table width=800 >";
    		$n=0;
    		foreach ($printed_orders as $order) {
              $n++;
                
	        			echo "<tr>\n";
	        			echo "  <td width=10% class=row$n >".$order['order_id']."</td>\n";
	        			echo "  <td width=30% class=row$n >".$order['name']."</td>\n";
	        			echo "  <td width=20% class=row$n >".date('j-m-Y g:i a',strtotime($order['modified']) )."</td>\n";
	              echo "  <td width=20% class=row$n align=center ><a href=\"?v=orderview&order_id=".$order['order_id']."&print\" >view</a></td>";
	              echo "  <td width=10% >".$order['status']."</td>\n";
	              $value = get_system_order_total($order['order_id']);
	              $tvalue += $value;
	              echo "  <td width=10% >".number_format($value/100,2)."</td>\n";
	
	        			echo "</tr>\n";
	        		
	        		if ($n > 100) {
	        			echo "truncating list as there are more than 100 orders in list!";
	        			break;
	        		}
      	         
    		}

    }

?>
<tr><td colspan=6 align=right ><div style="font-weight:bold;border-top:1px solid #000;"><?=number_format($tvalue/100,2)?></div></td></tr>
</table>

