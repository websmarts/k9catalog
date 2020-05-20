<?php // echo dumper($S->basket_prices)
?>
<script>

function set_e(str){
    elm = document.getElementById('e_value');
    elm.value= str;
    
}
//document.getElementById('submit_button').disabled = 1;
</script>

<form method = "post" action = "" id="geoform" onsubmit="document.getElementById('submit_button').disabled = 1;">
    <input type = hidden name = catid value = <?php echo $req['catid'];?>>
    <input type = hidden name = "update_source" value = "basket_view">
    <input type="hidden" name="longitude" />
    <input type="hidden" name="latitude" />
    <input type="hidden" name="e" id="e_value" />
    <table class = list_table>
        <?php
        if (is_array($products) && count($products)>0)
            {
            $n=0;

            foreach ($products as $product)
                {
        ?>

                <tr class="row<?php echo $n++%2?>">
                    <td>
                        <table border = "0" width = "100%" >
                            <tr>
                                <td valign = "top">
                                        <?php   echo $product['name'] . "<br>";
                                                echo "<i>" ;
                                                $str = $product['description'] ;
                                                if ( !empty($product['size'])){
                                                    $str .= ', '.$product['size'];
                                                }
                                                if (!empty($product['color_name'])){
                                                    $str .= ', '.$product['color_name'];
                                                }
                                                
                                                echo $str;
                                                
                                                echo "</i>"; 
                                        ?>
                                </td>

                                <td valign = "top" width = "170">
                                <?php // write links for the current display_mode
                                if($S->display_mode =='showall'){
                                    $vv = 'list_all_products';
                                } else {
                                    $vv = 'list_products';
                                }
                                ?>
                                    <a href = "?v=<?php echo $vv ?>&catid=<?php echo $product['catid']."&highlight=".$product['product_code']."#type_".$product['typeid'];?>">

                                    <?php echo $product['product_code']; ?></a>
                                </td>

                                

                            <?php // determine the price to use i.e standard or client special
                            if (isSet($special_prices[$product['product_code']]))
                                {
                                $price=$special_prices[$product['product_code']];
                                $price_class=" class=special_price ";
                                }
                            else
                                {
                                $price=$product['price'];

                                // check if qty discount applies
                                if ($product['qty_break'] > 0 && $product['qty'] >= $product['qty_break'])
                                    { // is qty break set and should it be applied?
                                    $price=$price * (1 - ($product['qty_discount'] / 100));
                                    $price_class=" class=discount ";
                                    }
                                else
                                    {
                                    $price_class="";
                                    }
                                }

                            if (isSet($S->basket_prices[$product['product_code']]) )
                                {
                                $basket_price=$S->basket_prices[$product['product_code']]; // set by basket update
                                $basket_price_display= number_format($basket_price/100,2);
                                $item_price = $basket_price; // rep entered custom price
                                }
                                else {
                                    $item_price = $price; // system price
                                    $basket_price_display ='';
                                }
                                
                            
                            
                                
                                
                            ?>

                            <td valign = "top" width = "90" <?php echo $price_class;?> >
                                
                                <?php if($S->isInternalUser() && $S->checkPrivileges('changeprices')):?>
                                <input type = text size = 3 name = "basket[<?php echo $product['product_code'];?>][price]"
                                    value = "<?php echo $basket_price_display;?>">
                                    
                                <?php endif; ?>
                                
                                
                                    <?php echo number_format(($price/100),2).
                                    '<br><span style="font-size:80%;color: #448;"><i>'.productMarginHintCode($product['cost'],$product['price']).'</i></span>'?>
                                
                                
                                
                            </td>

                            <td valign = top width = 50>
                                <input type = "text" name = "basket[<?php echo $product['product_code'];?>][qty]" size = "3"
                                    value = "<?php echo $basket[$product['product_code']]; ?>">
                                <td valign = "top" width = "70" <?php echo $price_class;?>>
                                    $ <?php
                                    $ext_cost=(($item_price / 100) * $product['qty']);
                                    $total+=$ext_cost;

                                    echo number_format($ext_cost, 2);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

        <?php
                }
                ?>
       

        <tr>
            <td align = right style = "border-top:1px solid black;">
                <span style = "padding-right:30px;border-bottom:1px solid black;font-weight:700;">

                <?php echo "$" . number_format($total, 2); ?></span>
            </td>
        </tr>

        <tr>
            <td>
                <p style = "width:40%; float: right; text-align: right">
                
                <input type = submit name = "b" value = UpdateBasket onclick="set_e('UpdateBasket');">
               
                

                <p style = "width:40%; float: left; text-align: right;">
                <input type = submit name = "b" value = ClearBasket onclick="set_e('ClearBasket');"></p> 
                <?php if($S->isInternalUser() && $S->checkPrivileges('changeprices')):?>
                <p style="clear: both; text-align: right; width: 50%; float: right;">NOTE if you need to set a custom price enter the custom price in the field next to the system price, otherwise the system calculated price will be used for the order. To apply a discount use a minus sign in front of the number you enter into the custom price box to indicate a percentage discount eg -20 would give a 20% discount.</p>
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <td align = "right">
                <table>
                    <tr>
                        <td align = right>
                        <p>&nbsp;</p>
                        Order Contact*<br>
                        <input type="text" name="order_contact" value="<?php echo $S->order_contact; ?>" size="30" />
                        <p>&nbsp;</p>
                            Special Instructions:

                            <br />

                            <textarea name = basket_instructions cols = 40 rows = 4><?php echo $S->basket_instructions; ?></textarea>
                            <p>&nbsp;</p>
                            Freight charge: (00.00 dollars)
                            <input type="text" name="freight_charge" value="<?php echo $S->freight_charge; ?>" size="30" />

                        </td>
                    </tr>

                    <tr>
                        <td align = "right">

                            <input type = "submit" id="submit_button" name = "b" value ="Save & Send Order" onclick="set_e('Save & Send Order');">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<?php
            } else {
                echo '<h1>Basket is empty</h1>';
            }
        ?>