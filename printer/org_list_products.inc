<?php

function product_list_vertical ($products){
	global $special_prices,$S,$typeid,$client,$markup;
	
	$markup .= "<table >";
	foreach($products as $p) {
		// check if qty instock is zero and if backordering is allowed if it is
		//echo dumper($p);
		
		
		if (($p['qty_instock']) > 0  || $p['can_backorder'] !='n' || $S->rep_name=="darren") {
		
			$n++; // increment for every product we display
		

        // Do the BUNNINGS&nbsp;HACK
        // IF $typeid =106 then were doing drum style kennels
         if ($typeid == 106) {
            // if client is a Bunnings
            if (eregi("bunnings",$client[0]['name'] )) {
                //  if product code has NOT a B in itthen skip it
                if (!ereg("B",$p['product_code'] )) {
                  continue;
                }
            } else { // it is not bunnings so only skip if product code has a B
               if (ereg("B",$p['product_code'] )) {
                  continue;
                }
            }
         }


          		$markup .= "<tr>";
          		$markup .= "<td width=70 >".$p['product_code']."</td>";
          		$markup .= "<td width=200 >".$p['description']."</td>";

          		// check if special price exists
          		if (isSet($special_prices[$p['product_code']]) ){
          			$markup .= "<td width=120 >$".number_format($special_prices[$p['product_code']]/100,2);
          		} else {
          			$markup .= "<td width=120 >$".number_format($p['price']/100,2);
          		}


          		if ($p['qty_break'] > 0 ) {
          			$markup .= " (".$p['qty_break']."+ less ".$p['qty_discount']."% )";
          		}
          		$markup .= "</td>";
                  // display the qty input box if we can accept orders for product
          		if ($p['qty_instock'] > 0 or $p['can_backorder'] =='y') {
          			$markup .= "<td width=50 >&nbsp;</td>";
          		} else {
          			$markup .= "<td width=50> * </td>";
          		}

                 
				}

	}
	$markup .= "</table>\n";
	return $n;	// need to return something >0  else the ADDtoBasket button will be missing
}

function product_list_horizontal($products) { // displays a product with option displayed horizontally 
	global $options,$stocked_products,$special_prices,$S,$markup;
	$markup .= "<table>";
	//echo dumper($stocked_products);
	//echo dumper($special_prices);
	//break;
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
	  		$markup .= "<tr><td colspan=3>";
	  		foreach ($options[$p['typeid']] as $opt) {
	  			$markup .= "<td width=25>".$opt['opt_code']."&nbsp;</td>";
	  		}
	  		$markup .= "</tr>\n";
	  	}
	  }
	  
	  
		
		$markup .= "<tr>";
		$markup .= "<td width=70 >".$basename."</td>";
		$markup .= "<td width=200 >".$p['description'].$p['size']."</td>";
		
		// check if special price exists
		if (isSet($special_prices[$basename."-".$opt['opt_code']]) ){
			$markup .= "<td width=120 >$".number_format($special_prices[$basename."-".$opt['opt_code']]/100,2);
		} else {
			$markup .= "<td width=120 >$".number_format($p['price']/100,2);
		}
		
		if ($stocked_products[$basename."-".$opt['opt_code']]['qty_break'] > 0 ) {
			$markup .= " <span >(".$stocked_products[$basename."-".$opt['opt_code']]['qty_break']."+ less ".$stocked_products[$basename."-".$opt['opt_code']]['qty_discount']."% )</span>";
		}
		$markup .= "</td>";
		
		if (is_array($options[$p['typeid']])) {
			foreach ($options[$p['typeid']] as $opt) {
				//echo dumper($opt);
				$markup .= "<td width=45 >";
				// if this product is stocked (or if product qty is ) and backorders are not allowed )display qty box else dont!
				$product_code = $basename."-".$opt['opt_code'];
				if (isset($stocked_products[$product_code]) ) {
					
          	$markup .= "-";
            
          
        } else {
        	
						$markup .= 'NS';
					
				}
				$markup .= "</td>";
			}
		}
		$markup .= "<td >&nbsp;</td></tr>\n";
		$done[$basename]= 1; // Flag that we have  displayed this basename
	}
	$markup .= "</table>\n";	
	return 1;	// need to return something else the ADDtoBasket button will be missing
}






			
			if (is_array($group_products) ) {			
				foreach($group_products as $typeid =>$products) {
					// Display the group image
						
						$markup .= '<table width=100%><TR><TD valign=top width=150 >';
						//echo "<p>".$products[0]['name']."</p>\n";
						if (file_exists("../source/tn_".strtolower($products[0]['typeid']).'.jpg')) {
							$markup .= "<img src=\"../source/tn_".strtolower($products[0]['typeid']).".jpg\" border=0  >\n";
							// could also look for asociated images and display
							$n = 2;
							while ($n < 6 ) {
								if (file_exists("../source/tn_".strtolower($products[0]['typeid'])."_".$n.'.jpg')) {
									$markup .= "<img src=\"../source/tn_".strtolower($products[0]['typeid'])."_".$n.".jpg\" border=0 width=150 >\n";
								} else {
									$n=6; // gap in seq so end early
								}
								$n++;
							}						
						} 
						
						$markup .= "</TD></tr></table>";
						
						
						$markup .= "<p>".$products[0]['name']."</p>\n"; 
						$inTable =1;
						if ($products[0]['display_format'] =='h') {
							$n = product_list_horizontal($products);
						} else {
							$n = product_list_vertical($products);
						}
						
						$markup .= ''."\n";
					}
				
			}
			



//echo $markup;
//echo htmlentities($markup);
//exit;
?>