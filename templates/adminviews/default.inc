<?php 
function do_cats_list ($cats,$depth) {
	
	//echo "depth=$depth<br>\n";
	//echo dumper($cats);
	if (is_array($cats) ) {
		foreach ($cats as $cat) {
			$spacer = str_repeat("-",($depth * 5) );
						
			echo 	"<tr>".
							"<td><a href=\"?m=cat&a=delete&v=&catid=".$cat[0]['id']."\">delete </a></td> ".
							"<td><a href=\"?m=cat&v=add&catid=".$cat[0]['id']."\">add </a></td> ".
							"<td><form><input type=hidden name=m value=cat >$spacer <input type=text name=catname size=".(strlen($cat[0]['name'])+2)." value='".htmlentities(stripslashes($cat[0]['name']),ENT_QUOTES)."'><input type=hidden name=catid value=".$cat[0]['id']." ><input type=hidden name=a value=update_catname ><input type=submit name=b value=update ></form> </td>".
						"</tr>";				

			// Check if this row has child cats and display if yes
			if (is_array($cat[1]) and count($cat[1]) > 0){
				$depth++;
				do_cats_list($cat[1],$depth);
				$depth--;
			}
			
		}		
	}
}


	
?>

<!-- MAIN PAGE CONTENT -->
page= default.inc<br>

<table style="background:#ccffcc;"><tr><td>Edit product code </td><td><form><input type=text name=product_code size=16><input type=hidden name=m value=product><input type=hidden name=a value=edit><input type=submit name=b value=edit></form></td></tr></table>
<table style="background:#ccffcc;">
	<tr>
		<td>Product Type </td>
			<td>
				<form>
					<input type=hidden name=m value=type >
					<input type=hidden name=a value=edit>
					<select name=typeid >
						<?php
							foreach($_v['ptypes'] as $type) {
									
								echo '<option value="'.$type['typeid'].'" >'.$type['name']."</option>\n";
								
							}
						
						?>
					<input type=submit name=b value=edit>
				</form>
			</td>
		</tr>
	</table>


<p class=head>List of Categories:</p>
<table border=1 width=500>
<tr><td width=50></td><td width=50>&nbsp;</td><td>Category</td></tr>
<tr><td width=100 colspan=2 bgcolor="#cccccc">new top category</td><td bgcolor="#cccccc"><form><input type=text name=catname size=12 ><input type=hidden name=m value=cat><input type=hidden name=a value=insert><input type=hidden name=parent_id value=0><input type=submit name=b value=add ></form></td></tr>
<?php do_cats_list($_v['cats'],0); ?>
</table>

