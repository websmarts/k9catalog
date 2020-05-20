<?php 

function product_list_vertical ($products){
	echo "<table class=list_table >";
	foreach($products as $p) {
		echo "<tr>";
		echo "<td width=70 >".$p['product_code']."</td>";
		echo "<td width=200 >".$p['size']."</td>";
		//echo "<td width=120 >$".number_format($p['price']/100,2);
		//if ($p['qty_break'] > 0 ) {
		//	echo "<span class=discount> (".$p['qty_break']."+ less ".$p['qty_discount']."% )</span>";
		//}
		//echo "</td>";
		//echo "<td width=50 ><input type=text size=2 class=input name=basket[".$p['product_code']."][qty] value=".$_SESSION['basket'][$p['product_code']]."  ></td>";
		//echo "<td >".$p['qty_instock']." avail</td></tr>\n";
	}
	echo "</table>\n";	
}

function product_list_horizontal($products) { // displays a product with option displayed horizontally 
	global $options,$stocked_products;
	echo "<table  class=list_table >";
	//echo dumper($stocked_products);
	foreach($products as $p) {
		//echo dumper($p);
		
		// we need to strip out the base product name i.e make b210-R = b210 as the options
		// are added below horizontally. We only process the basename once s skip over repeats
		$pos = strrpos($p['product_code'], "-");
		
		if ($pos === false) { // note: three equal signs
		    $basename =$p['product_code'] ; //SHOULD THIS BE AN ERROR!!!!
		    //continue; // uncommnet to ignore
		} else {
	  	$basename = substr($p['product_code'],0,$pos);//strrpos($p['product_code'],"-")
	  }
	  if ($done[$basename]) {
	   	continue;
	  }
	  $counter++; // count the number of products listed
	  if ($counter == 1) { // the first row!
	  	// display the option colors at the head of the qty columns
	  	//echo dumper($options[$p['typeid']]);
	  	if (is_array($options[$p['typeid']])) {
	  		echo "<tr><td colspan=3>";
	  		foreach ($options[$p['typeid']] as $opt) {
	  			echo "<td width=25 class=\"".strtolower($opt['opt_class'])."\">-".$opt['opt_code']."&nbsp;</td>";
	  		}
	  		echo "</tr>\n";
	  	}
	  }
	  
	  
		
		//echo "<tr>";
		//echo "<td width=70 >".$basename."</td>";
		//echo "<td width=200 >".$p['size']."</td>";
		//echo "<td width=120 >$".number_format($p['price']/100,2);
		
		//if ($stocked_products[$basename."-".$opt['opt_code']]['qty_break'] > 0 ) {
		//	echo " <span class=discount>(".$stocked_products[$basename."-".$opt['opt_code']]['qty_break']."+ less ".$stocked_products[$basename."-".$opt['opt_code']]['qty_discount']."% )</span>";
		//}
		echo "</td>";
		/*
		if (is_array($options[$p['typeid']])) {
			foreach ($options[$p['typeid']] as $opt) {
				//echo dumper($opt);
				echo "<td width=45 >";
				// if this product is stocked display qty box else dont!
				$product_code = $basename."-".$opt['opt_code'];
				if (isset($stocked_products[$product_code])) {
					echo "<input type=text size=3 class=input name=basket[".$basename."-".$opt['opt_code']."][qty] value=".$_SESSION['basket'][$basename."-".$opt['opt_code']]."  >";
				} else {
					echo "NS";
				}
				echo "<span class=instock>".$stocked_products[$basename."-".$opt['opt_code']]['qty_instock']."</span></td>";
			}
		}
		*/
		echo "<td >&nbsp;</td></tr>\n";	
		$done[$basename]= 1; // Flag that we have  displayed this basename
	}
	echo "</table>\n";		
}
?>

<!-- MAIN PAGE BELOW -->

			<table>
				<tr>
					<td width=50%><?php echo "<h3> Current Specials </h3>"; ?></td>
					<td width=50%>
					<?php
						// check if there are any category images to display
						
						
					?>
					</td>
				</tr>
			</table>
<form>
<input type=hidden name=u value=update_basket>
<input type=hidden name=a value=browse>
<input type=hidden name=catid value=<?php echo $req['catid'];?> >


<TABLE style="border:2px solid silver;" width=100%>
 <TR>
 	<TD>
			<?php 
			if (is_array($products) ) {
				//echo dumper($products);			
				foreach($products as $typeid =>$product) {
					// Display the group image
						
						echo "<table border=1 width=100%><TR><TD valign=top width=150 >";
						echo "<p>".$product['name']."</p>\n";
						if (file_exists("source/tn_".strtolower($product['image']).'.jpg')) {
							echo "<a href=\"source/".strtolower($product['image']).".jpg\" target=_blank ><img src=\"source/tn_".strtolower($product['image']).".jpg\" border=0 width=150 ></a>\n";
							// could also look for asociated images and display
							$n = 2;
							while ($n < 6 ) {
								if (file_exists("source/tn_".strtolower($product['image']).$n.'.jpg')) {
									echo "<img src=\"source/tn_".strtolower($product['image']).$n.".jpg\" border=0 width=150 >\n";
								} else {
									$n=6; // gap in seq so end early
								}
								$n++;
							}						
						} 
						
						echo "</td></tr></table>\n";
					}
				
			} else {
				echo "<p>No Product Specials or Product Clearnace Items available</p>";
			}
			?>
		</TD>
	</TR>
</TABLE>	
			
	
	
</form>			
