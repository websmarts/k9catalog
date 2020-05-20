<?php //echo dumper($order);?>
<?php //echo dumper($special_prices);?>
<?php //echo dumper($basket_orders);?>
<?php //echo dumper($new_orders);?>


<?php
    // refresh client details - need to know if they have custom frieght or freight notes
    $Client=get_client_details($order[0]['client_id']);
    //echo dumper($Client);
?>

<?php if (is_array($order)  && TERMINALID=='T0' ) { ?>
	
 <?php $rowclass = 1; ?>  
      <table  style="border:0px solid #000; width: 100%">
        <tr>
        <td colspan="7" height="60" style="border-bottom:1px solid #000;"><h3>Client Purchase Order: <?php echo $order[0]['order_id']." -  ".$order[0]['name'];?></h3>
        
        <p>
        <?php 
        // get address and phone details
        $address = $Client[0]['address1'];
        $address .= !empty($Client[0]['address2']) ? ', '.$Client[0]['address2'] : '';
        $address .= !empty($Client[0]['address3']) ? ', '.$Client[0]['address3'] : ''; 
        $address .= !empty($Client[0]['city']) ? ', '.$Client[0]['city'] : ''; 
        $address .= !empty($Client[0]['postcode']) ? ', '.$Client[0]['postcode'] : ''; 
        
        $address .= !empty($Client[0]['phone']) ? ',  Ph  '.$Client[0]['phone'] : ''; 
        $address .= !empty($Client[0]['phone2']) ? ',  Ph2  '.$Client[0]['phone2'] : ''; 
        $address .= !empty($Client[0]['mobile']) ? ',  Mob  '.$Client[0]['mobile'] : ''; 
        
        
         echo $address;?>
        </p>
        <?php 
       
        echo 'Order received: '.date("g:i a, l jS F Y",strtotime($order_detail[0]['modified'] ));
        
        echo '<div style="height: 5px;">&nbsp;</div>';
        ?> 
        </td>
        </tr>
        <tr>
        	<th width="20" align="left">#</th>
        	<th  align="left">Product Code</th>
        	
            <th width="80" align="left">Color</th>
           <th width="140" align="left">Size</th>
            <th width="40" align="left">Qty</th>
        	<th width="70" align="left">Unit Price</th>
        	<!-- <th width="70" align="right">Ext Price</th> -->
            <th>&nbsp;</th>
        </tr>
        
<?php       $running_cost = 0;
            $running_total=0;
            
    		foreach ($order as $k => $v) {
              $n++;
                
        			echo '<tr class="row'.($rowclass++ % 2 ).'" >'."\n";
        			echo '  <td valign="top" width="20"  >'.($k + 1)."</td>\n";                  
        			echo '  <td valign="top"   >'.$v['product_code']." - ".$v['description']."</td>\n";
                    echo '  <td valign="top" width="80" >'.$v['color_name']."</td>\n";
                    echo '  <td valign="top" width="140"  >'.$v['size']."</td>\n";
        			echo '  <td valign="top" width="40"  >'.$v['qty']."</td>\n";
        			
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
                    
        			// $extended_price= number_format($v['price']/100 * $v['qty'],2);
                    
                    $running_total += $v['price'] * $v['qty'];
                    $running_cost += $v['cost'] * $v['qty'];
                    
              echo '  <td width="70" valign="top" class=row$n align=center >'.$display_price." ".$special_price_flag."</td>";
              // echo '  <td width="70" align="right" valign="top">'.$extended_price." ".$extended_price_flag."</td>\n";
              
              echo '<td></td>'."\n";
              
             
        			echo "</tr>\n";
        			$n = $n >1?0:$n ;    	         
    		}
    		 //echo '  <tr><td colspan="5"  align="left" ><br>Order Contact: <br><span style="color: #00f">'.$v['order_contact'].'</span><br>Order instructions:<br><span class="orderinstructions">'.nl2br($v['instructions']).'</span></td><td >&nbsp;Order total $</td><td><p class="total" width="100" align="right"> '.number_format(get_system_order_total($v['order_id'])/100,2)." ".$totals_flag1.$totals_flag2."</p></td></tr>\n";
             
             

    }

?>
</table>




