<?php //echo dumper($order);?>
<?php //echo dumper($special_prices);?>
<?php //echo dumper($basket_orders);?>
<?php //echo dumper($new_orders);?>



<form method="post"action="" >
<input type="hidden" name="order_id" value="<?php echo  $req['order_id'] ?>" />
<input type="hidden" name="v" value="edit_clients_order" />
<?php if (is_array($order)  && TERMINALID=='T0' ) { ?>
	
 <?php $rowclass = 1; ?>  
      <table  style="border:0px solid #000; width: 100%">
        <tr>
        <td colspan="7" height="60" style="border-bottom:1px solid #000;"><h3>Purchase Order: <?php echo $order[0]['order_id']." -  ".$order[0]['name'];?></h3>
        <?php 
       
       
        echo 'Order received: '.date("g:i a, l jS F Y",strtotime($order_detail[0]['modified'] ));
        if($S->isInternalUser()){
            echo ' [ ordered by : '.$k9users[$order_detail[0]['reference_id']].']';
        } 
        echo '<div style="height: 5px;">&nbsp;</div>';
        ?> 
        </td>
        </tr>
        <tr>
        	<th width="20" align="left">#</th>
        	<th  align="left">Product Code</th>
        	<th  align="left">Description</th>
            <th width="80" align="left">Color</th>
           <th width="140" align="left">Size</th>
            <th width="40" align="left">Qty</th>
        	<th width="70" align="left">Unit Price</th>
        	<th width="70" align="right">Ext Price</th>
            <th>&nbsp;</th>
        </tr>
        
<?php       $running_cost = 0;
            $running_total=0;
            
    		foreach ($order as $k => $v) {
              $n++;
                
        			echo '<tr class="row'.($rowclass++ % 2 ).'" >'."\n";
        			echo '  <td valign="top" width="20"  >'.(++$k)."</td>\n";                  
        			echo '  <td valign="top"   ><input type="hidden" name="product_code['.$k.']" value="'.$v['product_code'].'">'.$v['product_code']."</td>\n";
                    echo '  <td valign="top"   >'.$v['description']."</td>\n";
                    echo '  <td valign="top" width="80" >'.$v['color_name']."</td>\n";
                    echo '  <td valign="top" width="140"  >'.$v['size']."</td>\n";
        			echo '  <td valign="top" width="40"  ><input size="3" type="text" name=qty['.$k.'] value="'.$v['qty'].'">'."</td>\n";
        			
        			// check if this item has a special price or qty discount applies
                    
                    $std_discount_price =  round(get_standard_product_price($v['product_code'], $v['qty'])); 
                    
                    $special_price_flag ='';
        			if( $S->isInternalUser() && $v['standard_price'] != $v['price']){
                        $special_price_flag = '#';
                        // check if price is the clients special price
                        if ($v['price'] == $special_prices[$v['product_code']]){
                            $special_price_flag .='s';
                        } elseif ($v['qty'] >= $v['qty_break'] &&  $v['price'] ==  $std_discount_price){
                            // check if price is the standard qty discount price
                            $special_price_flag .='q';
                            //$special_price_flag .= 'std'.$std_discount_price.'<br>'.$v['price'];
                        } else {
                            // must be a custom price
                            $special_price_flag .='c';
                            //$special_price_flag .= 'std'.$std_discount_price.'<br>'.$v['price'];
                        }
                        // must therefore be a custom price entered by the rep when placing order
                    }
                    
        			   			        			
        			$display_price = number_format($v['price']/100,2);
                    if($v['price'] > 0){
                      $markup = number_format(($v['price'] - $v['cost'])*100/$v['price'],1);  
                    } else {
                        $markup ='';
                    }
                    
        			$extended_price= number_format($v['price']/100 * $v['qty'],2);
                    
                    $running_total += $v['price'] * $v['qty'];
                    $running_cost += $v['cost'] * $v['qty'];
                    
              echo '  <td width="70" valign="top" class=row$n align=center ><input size="4" type="text" name=price['.$k.'] value="'.$display_price.'"/> '.$special_price_flag."</td>";
              echo '  <td width="70" align="right" valign="top">'.$extended_price." ".$extended_price_flag."</td>\n";
              if($S->isInternalUser()){
                  echo '<td  align="right" valign="top">'.$markup. '% </td>'."\n";
              } else {
                   echo '<td>%nbsp;</td>'."\n";
              }
             
        			echo "</tr>\n";
        			$n = $n >1?0:$n ;    	         
    		}
            ?>
            
            <tr>
            <td>-</td>
           <!-- <td colspan="4"><input size="8" type="text" name="product_code[0]" /></td>-->
           <td colspan="4">
           <select name="product_code[0]" >
           <option value=""> -- select new product code -- </option>
           <?php foreach($products as $p){
               echo '<option value="'.$p['product_code'].'">'.$p['product_code'].'</option>'."\n";
           }
           ?>
           
           </select>
           
           </td>
            <td><input size="3" type="text" name="qty[0]" /></td>
            <td colspan="3"><input size="6" type="text" name="price[0]" /></td>
            
            
            </tr>
            
            
            
            <?php
    		 echo '  <tr><td colspan="6"  align="left" ><br>Order instructions:<br><textarea name="instructions">'.$v['instructions'].'</textarea></td><td >&nbsp;Order total $</td><td><p class="total" width="100" align="right"> '.number_format(get_system_order_total($v['order_id'])/100,2)." ".$totals_flag1.$totals_flag2."</p></td></tr>\n";
             
             if($S->isInternalUser()){
                 if($running_total > 0){
                     $orderMarkup = ($running_total - $running_cost)*100/$running_total;
                 } else {$order_Markup =0;}
                 
                 echo '<tr><td align="right" colspan="7">'.number_format($orderMarkup,1).'%<br /><i>note:<br /> #q = qty discount<br /> #s = Special client price <br /> #c Custom price entered </i> </td></tr>';
             }

    }

?>
</table>
<input type="submit" name="b" value="Update" />  &nbsp; &nbsp; <input type="submit" name="b" value="Save and Close" /></form>



