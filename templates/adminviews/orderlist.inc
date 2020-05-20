<?php //echo dumper($orders);?>
<?php //echo dumper($basket_orders);?>
<?php //echo dumper($new_orders);?>

<h4>Orders waiting to be submitted</h4>
<table width=600 >
	
<?php
	if (is_array($orders) && count($orders)> 0  ) {
      
    		echo "<form>\n<input type=hidden name=a value=process>\n<input type=hidden name=m value=order>";
    		foreach ($orders as $order) {
              $n++;
                if ($order['status']=='saved'){
        			echo "<tr>\n";
        			echo "  <td width=120 class=row$n >".$order['name']."</td>\n";
        			echo "  <td width=240 class=row$n >".$order['instructions']."</td>\n";
        			echo "  <td width=120 class=row$n >".$order['modified']."</td>\n";
        			//echo "  <td width=120>".$order['status']."</td>\n";
        			echo "  <td width=100 class=row$n ><input type=checkbox value=\"".$order['client_id']."\" name=\"do[".$order['order_id']."]\"></td>\n";
        			echo "</tr>\n";
      	         }
    		}

    		echo "<tr><td colspan=5 align=right><a href=\"?\" >Return to eCatalog</a> &nbsp;&nbsp;&nbsp;<input type=submit name=b value=Delete>&nbsp;&nbsp;&nbsp;<input type=submit name=b value=Send></td></tr>\n";
    		echo "</form>\n";
    } else {
        echo "<tr><td><h3>No pending orders to list</h3></td></tr>";
    }

?>
</table>

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
                    echo "  <td width=120 class=row$n >&nbsp;</td>\n";
        		
        			echo "</tr>\n";
      	         }
    		}

    } else {
        echo "<tr><td><h3>No basket orders to list</h3></td></tr>";
    }

?>
</table>




<?php
	if (is_array($new_orders) && count($new_orders)> 0 && TERMINALID=='T0' ) {
        echo " <hr><h4>New Purchase Orders</h4>";
        echo "<table width=800 >";

    		foreach ($new_orders as $order) {
              $n++;
                if ($order['status']=='new'){
        			echo "<tr>\n";
        			echo "  <td width=10% class=row$n >".$order['order_id']."</td>\n";
        			echo "  <td width=30% class=row$n >".$order['name']."</td>\n";
        			echo "  <td width=20% class=row$n >".$order['modified']."</td>\n";
              echo "  <td width=20% class=row$n align=center ><a href=\"?m=order&a=print&order_id=".$order['order_id']."\"  >print order</a></td>";
              echo "  <td width=10% >".$order['status']."</td>\n";
              echo "  <td width=10% >".number_format(get_system_order_total($order['order_id'])/100,2)."</td>\n";

        			echo "</tr>\n";
      	         }
    		}

    }

?>
</table>

<?php
	if (is_array($printed_orders) && count($printed_orders)> 0 && TERMINALID=='T0' ) {
        echo " <hr><h4>Printed Purchase Orders</h4>";
        echo "<table width=800 >";

    		foreach ($printed_orders as $order) {
              $n++;
                if ($order['status']=='printed'){
        			echo "<tr>\n";
        			echo "  <td width=10% class=row$n >".$order['order_id']."</td>\n";
        			echo "  <td width=30% class=row$n >".$order['name']."</td>\n";
        			echo "  <td width=20% class=row$n >".$order['modified']."</td>\n";
              echo "  <td width=20% class=row$n align=center ><a href=\"?m=order&a=print&order_id=".$order['order_id']."\"  >re-print order</a></td>";
              echo "  <td width=10%>".$order['status']."</td>\n";
							echo "  <td width=10% >".number_format(get_system_order_total($order['order_id'])/100,2)."</td>\n";
        			echo "</tr>\n";
      	         }
    		}

    }

?>
</table>
