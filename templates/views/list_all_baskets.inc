<?php //echo dumper($orders);?>
<?php //echo dumper($basket_orders);?>
<?php //echo dumper($new_orders);?>
<?php //echo dumper($S->id);?>



<hr><h4> Basket Orders</h4>
<table width=600 >
<?php
	if (is_array($basket_orders) && count($basket_orders)> 0  ) {


    		foreach ($basket_orders as $order) {
              $n++;
                if ($order['status']=='basket'){
        			echo "<tr>\n";
        			echo "  <td width=120 class=row$n >".$order['name']."</td>\n";
                    
                
        		
        			echo "  <td width=120 class=row$n >".$order['modified']."</td>\n";
                    echo "  <td width=120 class=row$n ><a href=\"?e=selectclient&v=basket&client_id=".$order['client_id']."\">view</a></td>\n";
        		
        			echo "</tr>\n";
      	         }
    		}

    } else {
        echo "<tr><td><h3>No basket orders to list</h3></td></tr>";
    }

?>
</table>




