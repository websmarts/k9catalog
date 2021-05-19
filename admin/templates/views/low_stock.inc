<?php

// echo dumper($items);

//echo dumper($orders);

?>

<h3>Low Stock Report</h3>

<?php  

    //echo dumper($_SERVER) ;
    $url = '/catalog/admin/?m=stock&a=lowstock';
    
    if ($_REQUEST['sortorder'] == "" || $_REQUEST['sortorder'] == 'asc') {
        $sortorder = "desc";
    } elseif ($_REQUEST['sortorder'] == "desc" ) {
        $sortorder = "asc";
    }

    
    
    
    ?>

<p>Number of low stock items = <?php echo count($items) ?></p>



<form method="post" action="">

    <input type="hidden" name="m" value="stock" />

    <input type="hidden" name="a" value="lowstock" />



    <table border="1" style="border: 1px solid #333" cellpadding="2" cellspacing="0">

        <tr>

            <th><a href="<?php echo $url.'&sortby=product_code&sortorder='.$sortorder ?>">Product code</a></th>

            <th><a href="<?php echo $url.'&sortby=description&sortorder='.$sortorder ?>">Description</a></th>

            <th>Instock</th>

            <th>Add Stock</th>

            <th>Ordered by</th>

        </tr>

        <tr>
            <td colspan="3">&nbsp;</td>
            <td><input type="submit" name="b" value="Update" /></td>
        </tr>



        <?php if (is_array($items) && count($items) > 0) {

            foreach ($items as $i) {

                echo    '<tr>';

                echo    '<td>' . $i['product_code'] . '</td>';

                echo    '<td>' . $i['description'] . '</td>';

                echo    '<td>' . $i['qty_instock'] . '</td>';

                echo    '<td><input size="3" type="text" name="qty[' . $i['id'] . ']" /></td>';

                echo    '<td>';

                if (isset($orders[$i['product_code']]) && count($orders[$i['product_code']]) > 0) {

                    foreach ($orders[$i['product_code']] as $o) {

                        echo $o['name'] . ' (' . $o['qty'] . ')<br />';
                    }
                }

                echo    '</td>';

                echo    '</tr>';
            }
        }

        ?>

        <tr>
            <td colspan="3">&nbsp;</td>
            <td><input type="submit" name="b" value="Update" /></td>
        </tr>

    </table>

</form>