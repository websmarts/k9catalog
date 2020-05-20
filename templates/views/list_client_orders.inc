<h3> My Orders</h3>
<?php
// echo dumper($S);

//echo dumper($sales_report);
//echo dumper($picked_orders);
$rowclass=0;
foreach ($sales_report as $order)
    {
    $sales_this_month+=$order['total'];
    $cost_of_sales+=$order['cost'];
    }

if (is_array($new_orders) && count($new_orders) > 0 && TERMINALID == 'T0')
    {
    echo " <hr><h4>Pending Orders</h4>";

    echo '<table width="700" >';

    foreach ($new_orders as $order)
        {
         echo '<tr class="row'.($rowclass++ % 2 ),'" >'."\n";
        echo "  <td width=10% >" . $order['order_id'] . "</td>\n";
        //echo "  <td width=5% >".$order['ordered_by']."</td>\n";
        //echo "  <td width=30% class=row$n >" . $order['name'] . "</td>\n";
        echo "  <td width=20%  >" . date('j-m-Y g:i a', strtotime($order['modified'])) . "</td>\n";
        echo "  <td width=15% align=center ><a href=\"?v=orderview&order_id=" . $order['order_id']
            . "&print\" >view</a></td>";
        //echo "  <td width=10% >" . $order['status'] . "</td>\n";
        echo "  <td width=10% >$" . number_format(get_system_order_total($order['order_id']) / 100, 2) . "</td>\n";
        echo "</tr>\n";
        }

    echo '</table><hr>';
    }
else
    {
    //echo '<h2 style="color:#f00"> No pending orders </h2><hr>';
    }

if (is_array($picked_orders) && count($picked_orders) > 0 && TERMINALID == 'T0')
    {
    echo "<h3>Order history</h3>";

    echo '<table width="700" >';
    $n=0;

    foreach ($picked_orders as $order)
        {
        $n++;

         echo '<tr class="row'.($rowclass++ % 2 ),'" >'."\n";

        echo "  <td width=10%  >" . $order['order_id'] . "</td>\n";
       // echo "  <td width=5% >".$order['ordered_by']."</td>\n";
       // echo "  <td width=30% class=row$n >" . $order['name'] . "</td>\n";

        echo "  <td width=20%  >" . date('d-m-Y ', strtotime($order['modified'])) . "</td>\n";

        echo "  <td width=15%  align=center ><a href=\"?v=orderview&order_id=" . $order['order_id']
            . "&print\" >view</a></td>";

       // echo "  <td width=10% >" . $order['status'] . "</td>\n";

        $value=get_system_order_total($order['order_id']);
        $tvalue+=$value;

        echo "  <td width=10% >$" . number_format($value / 100, 2) . "</td>\n";

        echo "</tr>\n";

        if ($n > 100)
            {
            echo "truncating list as there are more than 100 orders in list!";
            break;
            }
        }
    }
?>

<tr>
    <td colspan = 4 align = right>
        <div style = "font-weight:bold;border-top:1px dotted #000;">$<?= number_format($tvalue / 100, 2) ?></div>
    </td>
</tr>

</table>
