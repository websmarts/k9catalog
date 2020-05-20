<?php
// echo dumper($S);

//echo dumper($printed_orders);
foreach ($sales_report as $order)
    {
    $sales_this_month+=$order['total'];
    $cost_of_sales+=$order['cost'];
    }

if (is_array($new_orders) && count($new_orders) > 0 && TERMINALID == 'T0')
    {
    echo "<h4>New  Orders be Processed</h4>";

     echo '<table width="100%" cellspacing="0" cellpadding="2" >';

    foreach ($new_orders as $order)
        {
            
        
       
        echo '<tr class="row'.($rowclass++ % 2 ),'" >'."\n";
        echo "  <td width=10% class=row$n >" . $order['order_id'] . "</td>\n";
        echo "  <td width=10% >";
        if (!$order['ordered_by']){
            echo $k9users[$order['reference_id']];
        } else {
            echo 'client';
        }
        
        echo "</td>\n";
        echo "  <td width=40% class=row$n >" . $order['name'] . "</td>\n";
        echo "  <td width=20% class=row$n >" . date('j-m-Y g:i a', strtotime($order['modified'])) . "</td>\n";
        echo "  <td width=5% class=row$n align=center ><a href=\"?v=orderview&order_id=" . $order['order_id']
            . "&print\" >view</a></td>";
        echo "  <td width=5% >" . $order['status'] . "</td>\n";
        echo "  <td width=10% >" . number_format(get_system_order_total($order['order_id']) / 100, 2) . "</td>\n";
        echo "</tr>\n";
        }

    echo '</table><hr>';
    }
else
    {
    echo '<h2 style="color:#f00"> No new orders to print</h2><hr>';
    }

if (is_array($printed_orders) && count($printed_orders) > 0 && TERMINALID == 'T0')
    {
    echo "<h3>Orders to be  Picked</h3>";

    echo '<table width="100%" cellspacing="0" cellpadding="2" >';
    $n=0;

    foreach ($printed_orders as $order)
        {
        $n++;

         echo '<tr class="row'.($rowclass++ % 2 ),'" >'."\n";

        echo "  <td width=10% valign=\"top\"  >" . $order['order_id'] . "</td>\n";
        echo "  <td width=10%  valign=\"top\">";
        if (!$order['ordered_by']){
            echo $k9users[$order['reference_id']];
        } else {
            echo 'client';
        }
        echo "</td>\n";
        echo "  <td width=40%   valign=\"top\">" . $order['name'] . "</td>\n";

        echo "  <td width=20%  valign=\"top\">" . date('j-m-Y g:i a', strtotime($order['modified'])) . "</td>\n";

        echo "  <td width=5% align=center  valign=\"top\"><a href=\"?v=orderview&order_id=" . $order['order_id']
            . "&print\" >view</a></td>";

        echo "  <td width=5%  valign=\"top\" >" . $order['status'] . "</td>\n";

        $value=get_system_order_total($order['order_id']);
        $tvalue+=$value;

        echo "  <td width=10%  valign=\"top\" >" . number_format($value / 100, 2) . "</td>\n";

        echo "</tr>\n";

        if (0 &&  $n > 100)
            {
            echo "truncating list as there are more than 100 printed orders in list!";
            break;
            }
        }
        
        
    }
?>

<tr>
    <td colspan = "7" align = "right">
        <div style = "font-weight:bold;border-top:1px solid #000;"><?= number_format($tvalue / 100, 2) ?></div>
    </td>
</tr>

</table>

<?php
if (is_array($picked_orders) && count($picked_orders) > 0 && TERMINALID == 'T0'){
        echo "<h3>Picked Orders but not yet exported to MYOB</h3>";
        echo '<form method="post" action="" target="export">';
        echo '<input style="float: right" type="submit" name="b" value="Export Orders" >';
    echo '<table style="clear: both" width="100%" cellspacing="0" cellpadding="2" >';
    $n=0;

    foreach ($picked_orders as $order)
        {
        $n++;

         echo '<tr class="row'.($rowclass++ % 2 ),'" >'."\n";

        echo "  <td width=10% valign=\"top\"  >" . $order['order_id'] . "</td>\n";
        echo "  <td width=10%  valign=\"top\">";
        if (!$order['ordered_by']){
            echo $k9users[$order['reference_id']];
        } else {
            echo 'client';
        }
        echo "</td>\n";
        echo "  <td width=40%   valign=\"top\">" . $order['name'] . "</td>\n";

        echo "  <td width=20%  valign=\"top\">" . date('j-m-Y g:i a', strtotime($order['modified'])) . "</td>\n";

        echo "  <td width=5% align=center  valign=\"top\"><a href=\"?v=orderview&order_id=" . $order['order_id']
            . "\" >view</a></td>";

        // add a * to the status if it has been exported
        $exportflag = !empty($order['exported']) ? '*':'';
        echo "  <td width=8%  valign=\"top\" ><input type=\"checkbox\" name=\"export_order_id[".$order['id']."]\" >  " .$exportflag. "</td>\n";

        $value=get_system_order_total($order['order_id']);
        $tvalue+=$value;

        echo "  <td width=10%  valign=\"top\" >" . number_format($value / 100, 2) . "</td>\n";

        echo "</tr>\n";

        if (0 &&  $n > 100)
            {
            echo "truncating list as there are more than 100 printed orders in list!";
            break;
            }
        }
        
        
    }
?>

<tr>
    <td colspan = "7" align = "right">
        <div style = "font-weight:bold;border-top:1px solid #000;"><?= number_format($tvalue / 100, 2) ?></div>
    </td>
</tr>

</table>
<input type="hidden" name="e" value="export_orders">

</form>





<?php
if (is_array($completed_orders) && count($completed_orders) > 0 && TERMINALID == 'T0')
    {
    echo "<h3>Picked & Exported Orders in the last 7 days</h3>";
    
   

    echo '<table width="100%" cellspacing="0" cellpadding="2" >';
    $n=0;

    foreach ($completed_orders as $order)
        {
        $n++;

         echo '<tr class="row'.($rowclass++ % 2 ),'" >'."\n";

        echo "  <td width=10% valign=\"top\"  >" . $order['order_id'] . "</td>\n";
        echo "  <td width=8%  valign=\"top\">";
        if (!$order['ordered_by']){
            echo $k9users[$order['reference_id']];
        } else {
            echo 'client';
        }
        echo "</td>\n";
        echo "  <td width=39%   valign=\"top\">" . $order['name'] . "</td>\n";

        echo "  <td width=20%  valign=\"top\">" . date('j-m-Y g:i a', strtotime($order['modified'])) . "</td>\n";

        echo "  <td width=5% align=center  valign=\"top\"><a href=\"?v=orderview&order_id=" . $order['order_id']
            . "\" >view</a></td>";
            
            
        // add a * to the status if it has been exported
        $exportflag = !empty($order['exported']) ? '*':'';
        echo "  <td width=8%  valign=\"top\" ><a target=\"export\" href=\"?e=export_order&order_id=" . $order['id']
            . "\" >" . $order['status']."</a> " .$exportflag. "</td>\n";

        $value=get_system_order_total($order['order_id']);
        $tvalue+=$value;

        echo "  <td width=10%  valign=\"top\" >" . number_format($value / 100, 2) . "</td>\n";

        echo "</tr>\n";

        if ($n > 100)
            {
            echo "truncating list as there are more than 100 picked orders in list!";
            break;
            }
        }
        
        
    }
?>

<tr>
    <td colspan = "7" align = "right">
        <div style = "font-weight:bold;border-top:1px solid #000;"><?= number_format($tvalue / 100, 2) ?></div>
    </td>
</tr>

</table>



<?php
if ($S->checkPrivileges('cosreport') && is_array($sales_report) && count($sales_report))
    {
    echo " <hr><h4>Sales this Month = " . number_format($sales_this_month / 100, 2) . "</h4>";

    echo " <hr><h4>COS this Month = " . number_format($cost_of_sales / 100, 2) . "</h4>";

    echo " <hr><h4>gp = " . number_format((($sales_this_month - $cost_of_sales) / $sales_this_month), 2) . "</h4>";

    
    echo '<hr><h3>Sales Report</h3>';
     echo '<table width="100%" cellspacing="0" cellpadding="2" >';

    foreach ($sales_report as $order)
        {
         echo '<tr class="row'.($rowclass++ % 2 ),'" >'."\n";

        echo "  <td width=10% >" . $order['order_id'] . "</td>\n";

        echo "  <td width=30%  >" . $order['name'] . "</td>\n";

        echo "  <td width=20%  >" . date('j-m-Y g:i a', strtotime($order['modified'])) . "</td>\n";

        echo "  <td width=10%  align=center ><a href=\"?v=orderview&order_id=" . $order['order_id']
            . "\" >view</a></td>";

        echo "  <td width=10% >" . $order['status'] . "</td>\n";

        echo "  <td width=20% >" . number_format($order['total'] / 100, 2) . " - "
            . number_format($order['cost'] / 100, 2) . "</td>\n";

        echo "</tr>\n";
        }

    echo '</table><hr>';
    }
?>