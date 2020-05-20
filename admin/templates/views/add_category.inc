<?php 
function do_cats_list ($cats,$depth) {
	global $req;
	
	// $req['catid'] is the parent to the new cat we are looking to add
	$parent_catid = $req['catid'];
	
	//echo "depth=$depth<br>\n";
	//echo dumper($cats);
	if (is_array($cats) ) {
		foreach ($cats as $cat) {
			$spacer = str_repeat("-",($depth * 5) );
						
			echo 	"<tr>".
							"<td>$spacer <a href=\"?a=edit&catid=".$cat[0]['id']."\">".htmlentities(stripslashes($cat[0]['name']),ENT_QUOTES)."</a></td>".
						"</tr>";	
						
			// Insert the new cat form if this is the parentid
			if($cat[0]['id'] == $parent_catid) {
				echo "<tr>".
								"<td>".
								"<form>\n".
								"<input type=hidden name=a value=insert >".
								"<input type=hidden name=m value=cat >".
								"$spacer : ".
								"<input type=text name=catname size=40 >".
								"<input type=hidden name=parent_id value=$parent_catid >".
								"<input type=submit name=b value=Add ></form>".
								"</td>".
							"</tr>";
			}
													

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
Add Category
<table border=1 width=500>
<tr><td>Category</td></tr>
<?php do_cats_list($_v['cats'],0); ?>
</table>