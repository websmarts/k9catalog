<?php 
//echo dumper($product);

?>
<script type="text/javascript" src="jscolor/jscolor.js"></script>

<div style="width:500px;border:ipx solid #008;background:#ccf;">
<form >
Enter product code to edit: <input type=text name=product_code size=20 value="<?php echo $_REQUEST['product_code'];?>">
<input type=hidden name=a value="edit" >
<input type="hidden" name="m" value="product">
<input type=submit name=b value="get">
</form>
</div>

<?php if(is_array($bom) && count($bom)):?>
<div>BOM details.</div>
<table width="360" style="border:1px solid #333; background: #eee; font-size: 9pt">
<tr style="background:orange"><td width="120">Item</td><td width="120">QTY</td><td width="120">Price*</td></tr>
    <?php foreach($bom as $item):?>
    <tr><td><?=$item['item_product_code']?></td><td><?=$item['item_qty']?></td><td><?=$item['item_price']?></td></tr>
    <?php endforeach;?>

</table>
<p>&nbsp;</p>
<?php endif;?>

<?php // display product notes from typeid
$type = do_query('select type_product_notes from `type` where typeid='.$product['typeid']);
if(!empty($type[0]['type_product_notes'])){
    echo '<p><strong>Product notes : </strong> '.nl2br($type[0]['type_product_notes']).'</p>';
}
?>
<p>Enter shipping - width, length and height in cm<br>Source is either F=finished product or M=manufactured product<br>Shipping weight is in Kg</p>

<?php 	

	if (is_array($product) && count($product)>0) { 
		echo '<form method="post" action="" ><table >'."\n";
		foreach($product as $k => $field) {
    
			if ($k =='product_code') {
				$product_code = $field;
			}
			if ($k =='qty_ordered') {
				$qty_ordered = $field;
			}
			if ($k =='typeid') {
				$imageid = $field;
			}
			
			if ($k =='id' || $k=='modified'|| $k=='product_code' || $k=='qty_ordered' ) {
				$hidden_fields .= "<input name=$k type=hidden value=\"".htmlentities(stripslashes($field))."\">\n";
			} elseif ($k =='typeid') {				
				$html .= '<tr><td><a href="?m=type&a=edit&typeid='.$field.'&b=edit">'.$k."</td><td><select name=$k >\n";
				foreach ($_v['type'] as $typeid => $name) {
					$selected = $typeid == $field?" selected ":"";
					$html .=  "<option value=$typeid $selected >$name </option>\n";					
				}
				$html .=  "</select>\n";
				
			} elseif($k == 'description' || $k=='notes'){
                $html .=  "<tr><td>$k</td><td><textarea name=$k cols=36 rows=3 >".htmlentities(stripslashes($field))."</textarea></td></tr>\n";
            } elseif ($k == 'aus_made' || $k == 'special' || $k == 'clearance' || $k == 'new_product' || $k == 'core_product') {
				$html .=  '<tr><td>'.$k.'</td><td><select name='.$k.' >';
				$selected = $field ==0?"selected":"";
				$html .= '<option value=0 '.$selected.' >No</option>';
				$selected = $field ==1?"selected":"";
				$html .=  '<option value=1 '.$selected.' >Yes</option>';
				$html .=  '</select>';
				$html .=  "</td></tr>\n";
			} elseif ($k == 'can_backorder') {
				$html .=  '<tr><td>'.$k.'</td><td><select name='.$k.' >';
				$selected = $field =='n'?"selected":"";
				$html .=  '<option value=n '.$selected.' >No backorders allowed</option>';
				$selected = $field =='y'?"selected":"";
				$html .=  '<option value=y '.$selected.' >Okay to backorder</option>';
				$html .=  '</select>';
				$html .=  "</td></tr>\n";
			} elseif ($k == 'status') {
				$html .=  '<tr><td>'.$k.'</td><td><select name='.$k.' >';
				$selected = $field !='inactive'?"selected":"";
				$html .=  '<option value=active '.$selected.' >active</option>';
				$selected = $field =='inactive'?"selected":"";
				$html .=  '<option value=inactive '.$selected.' >inactive</option>';
                $selected = $field =='pending'?"selected":"";
                $html .=  '<option value=pending '.$selected.' >pending</option>';
				$html .=  '</select>';
				$html .=  "</td></tr>\n";
			} elseif($k=='color_background_color') {
                $html .=  '<tr><td>'.$k.'</td><td><input type="text" name="'.$k.'" value="'.$field.'" size="8" class="color" ></td></tr>';
                
                
            } elseif($k=='price') {
                $html .=  '<tr><td>'.$k.'(cents)</td><td><input type="text" name="'.$k.'" value="'.$field.'" size="8"  >&nbsp; <a target="prices" href="?m=product&a=specialprices&pcode='.$_REQUEST['product_code'].'"> special prices</a></td></tr>';

            } elseif($k=='last_costed_date') {
				$html .=  '<tr><td>'.$k.' (YYYY-MM-DD)</td><td><input type="text" name="'.$k.'" value="'.$field.'" size="8"  ></td></tr>';

			} elseif($k=='width' || $k=='height' || $k=='length') {
				$v= number_format((float) ($field),1);
				$html .=  '<tr><td>'.$k.' (cm)</td><td><input type="text" name="'.$k.'" value="'.$v.'" size="8"  ></td></tr>';
				$$k = $field;

			} elseif($k=='shipping_volume') {
				$cbm=(float) ($width * $length * $height/1000000);
				$html .=  '<tr><td>'.$k.' (cbm)</td><td><input type="text"  value="'.$cbm.'" size="8"  > calculated field</td></tr>';
				$html .=  '<tr><td>'.$k.' (cbm/kg)</td><td><input type="text" value="'. number_format($cbm *250 ,2) .'" size="8"  > calculated field</td></tr>';
			} elseif($k=='shipping_weight') {
				$v= number_format((float) ($field),3);
				$html .=  '<tr><td>'.$k.' (kg)</td><td><input type="text" name="'.$k.'" value="'.$v.'" size="8"  ></td></tr>';
			} elseif($k=='source') {
				$html .=  '<tr><td>'.$k.'</td><td><select name='.$k.' >';
				$selected = $field =='B'?"selected":"";
				$html .=  '<option value=B '.$selected.' >B</option>';
				$selected = $field =='F'?"selected":"";
				$html .=  '<option value=inactive '.$selected.' >F</option>';
				$selected = $field =='M'?"selected":"";
				$html .=  '<option value=M '.$selected.' >M</option>';
				$html .=  '</select>';
				$html .=  "</td></tr>\n";
			} else {
				$html .=  "<tr><td>$k</td><td><input type=text name=$k size=40 value=\"".htmlentities(stripslashes($field))."\"></td></tr>\n";
			}
		}
		$html .=  	"</table>\n";
		$html .=  	"<input type=hidden name=m value=product>".
					"<input type=submit name=a value=update>".
					$hidden_fields.
					"</form>\n";
	}
	echo '<div style="text-align:center;width:500px;background:#f93;color:white;font-size:14pt;font-weight:bold;">'.$product_code.'<span style="font-size:10pt">'."  [$qty_ordered on order]</span></div>";
	echo $html;	
	
	echo '<form enctype="multipart/form-data" action="" method="post">';
	echo '<table style="border:1px solid #000;"  ><TR><TD valign=top width=500 style="border-right: 1px solid #999">';
						
						if (file_exists("../source/tn_".strtolower($imageid).'.jpg')) {
							echo "<a href=\"../source/".strtolower($imageid).".jpg\" target=_blank ><img src=\"../source/tn_".strtolower($imageid).".jpg\" border=0  title=\"".$imageid."\" ></a><br>\n";
							// could also look for asociated images and display
							$n = 2;
                            $nmax=10;
							while ($n < $nmax ) {
								if (file_exists("../source/tn_".strtolower($imageid)."_".$n.'.jpg')) {
									echo "<a href=\"../source/".strtolower($imageid)."_".$n.".jpg\" target=_blank ><img src=\"../source/tn_".strtolower($imageid)."_".$n.".jpg\" border=0 width=150 title=\"".$imageid."_".$n."\" ></a><br>\n";
									$f = $n;
								
								} else {
									$n=$nmax; // gap in seq so end early
								}
								$n++;
							}						
						} else {
							echo "<center><span style=\"color:green;\">".$imageid." no image available</span></center>";
						}
						
						echo '<p>UPLOAD IMAGE: <input type=file name=myfile><input type="hidden" name="MAX_FILE_SIZE" value="500000" />';
						echo ' <input type=hidden name=m value=product><input type=hidden name=product_code value="'.$product_code.'"><input type=hidden name=typeid value="'.$imageid.'" > <br><input type=submit name=a value=uploadimage>&nbsp;<input type=submit name=a value=deleteimage><br>';

	if ($f > 0 ) { // we have at least one image
        echo "image0<input type=radio name=img_number value=0 > &nbsp;<br />";
		for ($y =2;$y <= $f;$y++) {
			//echo $y."<p>";
			echo "image$y<input type=radio name=img_number value=$y > &nbsp;<br />";
		}
	}
	echo "add new image <input type=radio name=img_number value=0 >";
	echo '</form>';
	echo '</td></tr></table>';		
?>
<small><font color="#cccccc">edit_product_inc</font></small>