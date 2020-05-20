<?php 

function color_options_html() {?>
	<option value="opt_default" style="background:#ffffff;">---</option>
	<option value="opt_0000ff" style="background:#0000ff;">blu</option>
	<option value="opt_ff0000" style="background:#ff0000;">red</option>
	<option value="opt_ff00ff" style="background:#ff00ff;">pur</option>
	<option value="opt_ffff00" style="background:#ffff00;">yel</option>
	<option value="opt_00ff00" style="background:#00ff00;">grn</option>
	<option value="opt_ffff40" style="background:#ffff40;">gld</option>
	<option value="opt_663300" style="background:#663300;">brn</option>
	<option value="opt_ff8000" style="background:#ff8000;">org</option>
<?php }

function make_options($cats,$selected_catid,$depth) {
	$spacer = str_repeat("-",($depth * 5) );
	foreach ($cats as $cat) {
		$selected = in_array($cat[0]['id'],$selected_catid)?" selected ":"";
		echo '<option value="'.$cat[0]['id'] .'" '.$selected.' >'.$spacer.$cat[0]['name'].'</option>'."\n";
		// check if there are any children to do
		if (is_array($cat[1]) && count($cat[1]) > 0) {
			$depth++;
			make_options($cat[1],$selected_catid,$depth);
			$depth--;
		}
	}
}

function list_type_options(){
	global $_v,$req;
	//echo dumper($_v);
	
	$opts = $_v['type_options'];
	if (is_array($opts[$req['typeid']]) ) {
		//echo dumper($opts[$req['typeid']]);
		foreach ($opts[$req['typeid']] as $o) {			
			if (is_array($o)) {
				
					echo 	"<tr>".
								"<td>".$o['name']."</td> ".
								"<td><a href=\"?m=type&a=delete_opt&typeid=".$req['typeid']."&opt_code=".$o['opt_code']."\" >".$o['opt_code']."</a></td> ".
								"<td>".$o['opt_desc']."</td> ".
								"<td>".$o['opt_class']."</td> ".
							"</tr>";
					
			}
		}	
				
	}		
}	

function show_thumbs($typeid){
			if (file_exists("source/tn_".strtolower($typeid).'.jpg')) {
				echo "<a href=\"source/".strtolower($typeid).".jpg\" target=_blank ><img src=\"source/tn_".strtolower($typeid).".jpg\" border=0 width=150 title=\"".$typeid."\" ></a>\n";
				// could also look for asociated images and display
				$n = 2;
                $nmax = 10;
				while ($n < $nmax ) {
					if (file_exists("source/tn_".strtolower($typeid)."_".$n.'.jpg')) {
						echo "<a href=\"source/".strtolower($typeid)."_".$n.".jpg\" target=_blank ><img src=\"source/tn_".strtolower($typeid)."_".$n.".jpg\" border=0 width=150 title=\"".$typeid."_".$n."\" ></a>\n";
					} else {
						$n=$nmax; // gap in seq so end early
					}
					$n++;
				}						
			} else {
				
				$n=0;
				while ($n< $nmax) {
					$n++;
					if ($n == 1){
						$img = "source/".strtolower($typeid).".jpg";
					} elseif ($n>1) {
						$img = "source/".strtolower($typeid)."_".$n.'.jpg';
					}
					
					if (file_exists($img)) {
						
						echo '<p><a href="'.$img.'" target=_blank >Large image exists -(view)  '.$img ."</a></p>\n";
					}
				}
				echo "<center><span style=\"color:green;\">".$typeid." no thumb image available</span></center>";
			}
}	

function list_type_products($typeid) {
	
	$res = get_product_codes_for_typeid($typeid);
	if (is_array($res) ) {
		$html = "<ul>";
		foreach ($res as $p) {
			$html .= "<li>".$p['product_code']."</li>\n";
		}
		$html .="</ul>\n";
	}
	return $html;
	
}
?>

adminviews/edit_type.inc
				<form>
					<input type=hidden name=m value=type >
					<input type=hidden name=a value=edit>
					<select name=typeid >
						<?php
							foreach($_v['ptypes'] as $type) {
								$selected = $type['typeid']==$req['typeid']?" selected ":" ";
								echo '<option value="'.$type['typeid'].'"'.$selected.' >'.$type['name']."</option>\n";
								
							}
						
						?>
					<input type=submit name=b value=edit>
				</form>
<hr>				
				
				
<form action=index.php method=get>

<input type=hidden name=m value=type >
<input type=hidden name=typeid value="<? echo $req['typeid'];?>">
<table>
	<tr>
		<td>
			<table>
				<tr>
					<td>Type:</td><td><input type=text size=60 name=typename value="<? echo $_v['ptypes'][$req['typeid']]['name']; ?>"></td>
				</tr>
				<tr>
					<td>Category:</td><td>
					  <select name=catid[] multiple >
					  <option>select one</option>
						<?php 			
						// get the current category id for tyepeid= $req['typeid']
						$selected_catid = get_type_catid($req['typeid']);
									
						make_options($_v['cats'],$selected_catid,0); 
								
						?>
						</select>
					</td
				</tr>			
				<tr><td colspan=2><input type=submit name=a value="update_type">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=a value="delete_type"></td></tr>
			</table>
		</td>
		<td width=120>
		 <?php show_thumbs($req['typeid']); ?>
		 <?php echo list_type_products($req['typeid']) ;?>
		</td>
	</tr>
</table>
</form>


<form action=index.php method=post>
<input type=hidden name=a value=add_options>
<input type=hidden name=m value=type >
<input type=hidden name=v value=editopts>
<input type=hidden name=typeid value="<? echo $req['typeid'];?>">

<p>List of options for product:</p>
<table border=1 width=500 >
<tr><td colspan=4 >Product Options</td></tr>
<tr><td>Product type</td><td>opt_code</td><td>opt_desc</td><td>opt_class</td></tr>
<?php list_type_options(); ?>
<tr>
	<td>&nbsp;</td>
	<td><input type=text size=3 name=opt[1][<? echo $req['typeid'];?>][opt_code] ></td>
	<td><input type=text size=20 name=opt[1][<? echo $req['typeid'];?>][opt_desc] ></td>
	<td><select name=opt[1][<? echo $req['typeid'];?>][opt_class] ><?php color_options_html();?></select></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type=text size=3 name=opt[2][<? echo $req['typeid'];?>][opt_code] ></td>
	<td><input type=text size=20 name=opt[2][<? echo $req['typeid'];?>][opt_desc] ></td>
	<td><select name=opt[2][<? echo $req['typeid'];?>][opt_class] ><?php color_options_html();?></select></td>
</tr>
<tr>
	<td colspan=4 ><input type=submit name=b value=Add ></td></tr>
</table>
</form>