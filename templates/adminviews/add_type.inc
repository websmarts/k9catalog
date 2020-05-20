<?php 
function cats_selector ($cats,$depth) {
	global $req;
	
	//echo "depth=$depth<br>\n";
	//echo dumper($cats);
	if (is_array($cats) ) {
		foreach ($cats as $cat) {
			$spacer = str_repeat("-",($depth * 5) );
			
			// check if option was selected
			if (in_array($req['catid'],$cat[0]['id'])) {
				$selected = " selected ";				
			} else {
				$selected = "";
			}
						
			echo 	"<option value=\"".$cat[0]['id']."\" $selected >".$spacer.$cat[0]['name']."</option>\n";
										

			// Check if this row has child cats and display if yes
			if (is_array($cat[1]) and count($cats[1]) > 0){
				$depth++;
				cats_selector($cat[1],$depth);
				$depth--;
			}
			
		}		
	}
}

function list_product_types(){
	global $_v;
	
	$ptypes = $_v['ptypes'];
	if (is_array($ptypes) ) {
		foreach ($ptypes as $t) {
			echo 	"<tr>".
							"<td><a href=\"?m=type&a=delete&v=&typeid=".$t['typeid']."\">delete </a></td> ".
							"<td><a href=\"?m=type&v=edit&typeid=".$t['typeid']."\">".$t['name']."</a></td>".
						"</tr>";				
		}		
	}	
}

function form_error($field) {
	global $_v;
	
	if ($_v['form_errors'][$field] > 0 ) {
		echo "<span style=\"color:red;\">*</span>";
	}	
}
	
?>

<!-- MAIN PAGE CONTENT -->
Add Type<p>
<form>
<input type=hidden name=a value=create_type >
<input type=hidden name=m value=type >
<input type=hidden name=v value=add >
<table border=1>
	<tr>
		<td>Type name:<?php form_error('typename') ?></td>
		<td><input class=input type=text name=typename size=40 value="<?php echo $req['typename']; ?>"></td>
	</tr>
	<tr>
		<td>Type categories:</td>
		<td> <?php form_error('catid'); ?>
			<select name=catid[] multiple >
			<?php cats_selector($_v['cats'],0); ?>
			</select>
		</td>		
	</tr>
	<tr>
		<td>Image name:</td>
		<td><input class=input type=text name=typeimagename size=40 >.jpg</td>
	</tr>
	<tr>
		<td>Austalian made:</td>
		<td><input class=input name=ausmade type=checkbox > Australian made</td>
	</tr>
	<tr>
		<td>Display format:</td>
		<td><input type=radio name=displayformat value=v >Vertical &nbsp;<input type=radio name=displayformat value=h >Horizontal</td>
	</tr>
	<tr><td colspan=2 align=center><input class=input type=submit name=b value=Add ></td></tr>
</table>	

</form>

<p>List of Product Types:</p>
<table border=1 width=500 >
<tr><td width=50><?php echo "<a href=\"?m=type&v=add&typeid=".$t['typeid']."\">add </a> ";?></td><td>Product Type</td></tr>
<?php list_product_types(); ?>
</table>