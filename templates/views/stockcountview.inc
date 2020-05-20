<?php //echo dumper($sql);?>
<?php //echo dumper($stockcount);?>
<?php //echo dumper($orderhistory);?>



<?php if (is_array($stockcount)  ) { ?>
	
 <?php $rowclass = 1; ?>  
      <table  style="border:0px solid #000; width: 100%">
        <tr>
        <td colspan="6" height="60" style="border-bottom:1px solid #000;"><h3>Stock Count: <?php echo $stockcount[0]['clientname']." -  ".$stockcount[0]['repname'];?></h3>
        <?php 
       
       
        echo 'Stack count date: '.date("g:i a, l jS F Y",strtotime($stockcount[0]['datetime'] ));
        if($S->isInternalUser()){
            echo ' [ counted by : '.$k9users[$stockcount[0]['user_id']].']';
        } 
        echo '<div style="height: 5px;">&nbsp;</div>';
        ?> 
        </td>
        </tr>
        <tr>
        	<th width="20" align="left">#</th>
        	<th  align="left">Product Code</th>
        	
            <th width="80" align="left">Color</th>
           <th width="140" align="left">Size</th>
           <th>Ord<br />History</th>
            <th width="40" align="left">Instore Qty</th>
        	<th width="70" align="left">Suggested Qty</th>
       
        </tr>
        
<?php
    		foreach ($stockcount as $k => $v) {
              $n++;
                
        			echo '<tr class="row'.($rowclass++ % 2 ).'" >'."\n";
        			echo '  <td valign="top" width="20"  >'.($k + 1)."</td>\n";                  
        			echo '  <td valign="top"   >'.$v['product_code']."</td>\n";
                    echo '  <td valign="top" width="80" >'.$v['pcolor']."</td>\n";
                    echo '  <td valign="top" width="140"  >'.$v['psize']."</td>\n";
                    echo '  <td valign="top" width="40"  >'.$orderhistory[$v['product_code']]."</td>\n";
        			echo '  <td valign="top" width="40"  >'.$v['stock_count']."</td>\n";
        			echo '  <td valign="top" width="40"  >'.$v['suggested_order_qty']."</td>\n";
        			// check if this item has a special price or qty discount applies
                   
        			echo "</tr>\n";
        			$n = $n >1?0:$n ;    	         
    		}
    		 

    }

?>
</table>



