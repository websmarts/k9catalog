<?php

function product_list_vertical ($products){
	global $special_prices, $S, $typeid, $client, $req, $client_notifies;
	echo "<table class=\"list_table\" >";
	foreach($products as $p) {
		// dont show product if status =pending and user does not have stock_adjust privelege 
            if($p['status'] == 'pending' && !$S->checkPrivileges('updateparts')){
                continue;
            }
		echo "<tr  class=\"item\">";
		echo "<td width=70 >".$p['product_code']."</td>";
        
        // format description and size fields
            if(empty($p['description']) && !empty($p['size'])) {
                $description = $p['size'];
            } elseif  (!empty($p['description']) && empty($p['size'])) {
                $description = $p['description'];
            }  elseif (!empty($p['description']) && !empty($p['size'])){
                $description =   $p['description'] .'<br />'   . $p['size']  ;
            } else {
                $description = '';
            }
            // add in color name if not empty
            if(!empty($p['color_name'])){
                $description .= ' - '.$p['color_name'];
            }
		echo "<td width=\"200\"  >".stripslashes($description)."</td>";
		

		echo '<td width=50 >';
		if ($p['qty_instock'] > 0 ) {
			echo '<img src="images/tick.jpg">';
		} else {
			 echo '-'; 
		}
		echo '</td>';

		
		$rrp='';
		if($p['rrp'] > 0 ){
        	$rrp=  'RRP $'.number_format($p['rrp'] / 100, 2);
        }
		echo "<td width=120 >".$rrp."</td>\n";
		
		
		
		

		echo "<td >&nbsp;</td></tr>\n";
	}
	echo "</table>\n";	
}

function product_list_horizontal($products) { // displays a product with option displayed horizontally 
	global $options,$stocked_products,$special_prices;
	echo "<table  class=list_table >";
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
	  		echo "<tr><td colspan=3>";
	  		foreach ($options[$p['typeid']] as $opt) {
	  			echo "<td width=25 class=\"".strtolower($opt['opt_class'])."\">".$opt['opt_code']."&nbsp;</td>";
	  		}
	  		echo "</tr>\n";
	  	}
	  }
	  
	  
		
		echo "<tr class=\"item\" >";
		echo "<td width=70 >".$basename."</td>";
        // format description and size fields
            if(empty($p['description']) && !empty($p['size'])) {
                $description = $p['size'];
            } elseif  (!empty($p['description']) && empty($p['size'])) {
                $description = $p['description'];
            }  elseif (!empty($p['description']) && !empty($p['size'])){
                $description =   $p['description'] .'<br />'   . $p['size']  ;
            } else {
                $description = '';
            }
		echo "<td width=200 >".stripslashes($description)."</td>";
		// Note the RRP displayed is from the first product in the group
		// If products in the group (different colors) have different prices then
		// RRP will be incorrectly shown
		
		$rrp='';
		if($p['rrp'] > 0 ){
        	$rrp=  'RRP $'.number_format($p['rrp'] / 100, 2);
        }
		echo "<td width=120 >".$rrp."</td>\n";
		
	
		
		if (is_array($options[$p['typeid']])) {
			foreach ($options[$p['typeid']] as $opt) {
				//echo dumper($opt);
				echo "<td width=45 >";
				// if this product is stocked display tick icon else dont!
				$product_code = $basename."-".$opt['opt_code'];
				if (isset($stocked_products[$product_code])) {
					if ($stocked_products[$product_code]['qty_instock'] >0 ) {
						echo '<img src="images/tick.jpg">';
					} else {
						echo '-';
					}
				} else {
					echo "-";
				}
				echo "</td>";
			}
		}
		echo "<td >&nbsp;</td></tr>\n";	
		
		$done[$basename]= 1; // Flag that we have  displayed this basename
	}
	echo "</table>\n";		
}
?>

<!-- MAIN PAGE BELOW -->
<!-- public_list_products.inc -->
<form method="post" action="">
			<table width="100%" class="section_header">
                <tr>
                    <td class = "subcatmenu">

                <?php
                if (isSet($req['catid']))
                    {
                    //echo get_category_name($req['catid']);
                    //echo dumper($categoryData);
                    if ($categoryData[$req['catid']]['parent_id'] == 0)
                        {
                        $catid=$req['catid'];
                        }
                    else
                        {
                        $catid=$categoryData[$req['catid']]['parent_id'];
                        }

                    do_menu_row(array($categories[$catid]), 0, 2, $req['catid']);
                    }
                elseif (isSet($req['q']))
                    {
                    echo "Search results for '" . $req['q'] . "' ";
                    }
                ?>
            </td>        
                </tr>
            </table>

<?php
// Check for page insert
$insert = 'category_info/'.$req['catid'].'/insert.php';
if($req['catid'] && file_exists($insert)){
    include($insert);
}

?>

<input type=hidden name=catid value=<?php echo $req['catid'];?> >


	<table  width="100%">
	 <tr><td>
			<?php 
			if (is_array($group_products) ) {			
				foreach($group_products as $typeid =>$products) {
					// Display the group image
						
						echo "<table class=\"product_group_table\" ><tr><td valign=\"top\" width=\"150\" >";
						//echo "<p>".$products[0]['name']."</p>\n";
						if (file_exists("source/tn_".strtolower($products[0]['typeid']).'.jpg')) {
							echo "<a href=\"source/".strtolower($products[0]['typeid']).".jpg\" target=\"_blank\" ><img src=\"source/tn_".strtolower($products[0]['typeid']).".jpg\" border=0  title=\"".$products[0]['typeid']."\" ></a>\n";
							// could also look for asociated images and display
							$n = 2;
							while ($n < 60) {
								if (file_exists("source/tn_".strtolower($products[0]['typeid'])."_".$n.'.jpg')) {
									echo "<a href=\"source/".strtolower($products[0]['typeid'])."_".$n.".jpg\" target=_blank ><img src=\"source/tn_".strtolower($products[0]['typeid'])."_".$n.".jpg\" border=0  title=\"".$products[0]['typeid']."_".$n."\" ></a>\n";
								} else {
									break; // gap in seq so end early
								}
								$n++;
							}						
						} else {
							echo "<center><span style=\"color:green;\">".$products[0]['typeid']." no image available</span></center>";
						}
                        
						// check for modx_page for this typeid
                        if(isSet($typeInfoPageIndex[$typeid])){
                            echo '<a class="moreinfo" href="/cms/product-info?pid='.$typeInfoPageIndex[$typeid].'&ref='.base64_encode($_SERVER['QUERY_STRING']).'">more info</a>';
                        }
                        
						echo "</td>";
						echo "<td valign=top> \n";
						echo "<p class=product_name >".$products[0]['name']."</p>\n"; 
                        if(!empty($products[0]['type_description'])){
                            echo '<p class="type_description">'.nl2br(stripslashes($products[0]['type_description'])).'</p>';
                        }

						$inTable =1;
						if ($products[0]['display_format'] =='h') {
							product_list_horizontal($products);
						} else {
							product_list_vertical($products);
						} 
						echo '</td></tr></table>'."\n";
					}
				
			}
			?>
			</table></td></tr>	
			
	</table>
	
</form>			
