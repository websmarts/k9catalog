<?php 


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
								"<td><a href=\"?m=type&v=edit_opt&typeid=".$req['typeid']."&opt_code=".$o['opt_code']."\" >".$o['opt_code']."</a></td> ".
								"<td>".$o['opt_desc']."</td> ".
								"<td>".$o['opt_class']."</td> ".
                                "<td><a onclick=\"return confirm('Are you sure you want to delete?')\" href=\"?m=type&v=edit_opt&a=delete_opt&typeid=".$req['typeid']."&opt_code=".$o['opt_code']."\" >delete</a></td> ".
							"</tr>";
					
			}
		}	
				
	}		
}	

	
?>



<form action=index.php method="post" >
<input type=hidden name=a value=update_type>
<input type=hidden name=m value=type >
<input type=hidden name=typeid value="<? echo $req['typeid'];?>">
<table>
	<tr>
		<td>Type (<? echo $req['typeid'];?>):</td><td><input type=text size=60 name=typename value="<? echo $_v['ptypes'][$req['typeid']]['name']; ?>"></td>
	</tr>
    <tr><td>Type Description:</td><td><textarea cols="40" rows="8" name="type_description" ><? echo stripslashes($_v['ptypes'][$req['typeid']]['type_description']); ?></textarea></td></tr>
    
    <tr><td>Product notes:</td><td><textarea cols="40" rows="8" name="type_product_notes" ><? echo stripslashes($_v['ptypes'][$req['typeid']]['type_product_notes']); ?></textarea></td></tr>
    
    <tr><td>Type Display_order:</td><td><input type=text size="10" name="display_order" value="<? echo $_v['ptypes'][$req['typeid']]['display_order']; ?>"></td></tr>
	<tr>
		<td>Category:</td><td>
		  <select name=catid[] multiple >
		  <option>select one</option>
			<?php 
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
			
			// get the current category id for tyepeid= $req['typeid']
			$selected_catid = get_type_catid($req['typeid']);
			
			
			
			make_options($_v['cats'],$selected_catid,0); 
				
			
			?>
			</select>
        
            
		</td
	</tr>	
    <tr>
        <td>Aus made?:</td><td>
          <select name="aus_made" >
          <?php $selected = $_v['ptypes'][$req['typeid']]['aus_made'] > 0 ? '' : ' selected="selected" ';?>
          <option <?php echo $selected;?> value="0">No</option>
          
          <?php $selected = $_v['ptypes'][$req['typeid']]['aus_made'] > 0 ? ' selected="selected" ' :'' ;?>
          <option <?php echo $selected;?> value="1">Yes</option>
          </select>        
        </td
    </tr>    		
	<tr><td colspan=2><input type=submit name=b value="update"></td></tr>
</table>
</form>

<p><a onclick="return confirm('Are you sure you want to delete?')" href="?m=type&v=edit_type&a=delete_type&typeid=<?php echo $req['typeid']?>"> Delete Type Completely and including type_options and Products of this type</a></p>


<form action=index.php method=post>
<input type=hidden name=a value=add_options>
<input type=hidden name=m value=type >
<input type=hidden name=v value=editopts>
<input type=hidden name=typeid value="<? echo $req['typeid'];?>">

<p>List of options for product:</p>
<table border=1 width=500 >
<tr><td colspan=5 >Product Options</td></tr>
<tr><td>Product type</td><td>opt_code</td><td>opt_desc</td><td>opt_class</td><td>action</td></tr>
<?php list_type_options(); ?>
<tr>
	<td>&nbsp;</td>
	<td><input type=text size=3 name=opt[1][<? echo $req['typeid'];?>][opt_code] ></td>
	<td><input type=text size=20 name=opt[1][<? echo $req['typeid'];?>][opt_desc] ></td>
	<td><input type=text size=8 name=opt[1][<? echo $req['typeid'];?>][opt_class] ></td>
    <td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type=text size=3 name=opt[2][<? echo $req['typeid'];?>][opt_code] ></td>
	<td><input type=text size=20 name=opt[2][<? echo $req['typeid'];?>][opt_desc] ></td>
	<td><input type=text size=8 name=opt[2][<? echo $req['typeid'];?>][opt_class] ></td>
    <td>&nbsp;</td>
</tr>
<tr>
	<td colspan=5 ><input type=submit name=b value=Add ></td></tr>
</table>
</form>