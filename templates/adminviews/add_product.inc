<?php 
function typeid_options_html ($types) {
	foreach ($types as $typeid => $name) {
					$selected = $typeid == $field?" selected ":"";
					echo "<option value=$typeid $selected >$name </option>\n";
					
	}
}

?>

<p>add_product_inc:</p>
<?php //echo dumper($product); ?>



	 
		<form method=POST >
		<table>
		<tr><td width=200 align=right>description</td><td><input type=text name=description size=40 ></td></tr>
		<tr><td width=200 align=right>size</td><td><input type=text name=size size=40 ></td></tr>
		<tr><td width=200 align=right>price</td><td><input type=text name=price size=40 ></td></tr>
		<tr><td width=200 align=right>product_code</td><td><input type=text name=product_code size=40 ></td></tr>
		<tr><td width=200 align=right>typeid</td><td><select name=typeid ><?php typeid_options_html($_v['type']);?></select></td></tr>
		<tr><td width=200 align=right>aus_made</td><td><input type=text name=aus_made size=40 ></td></tr>
		<tr><td width=200 align=right>qty_break</td><td><input type=text name=qty_break size=40 ></td></tr>
		<tr><td width=200 align=right>qty_discount</td><td><input type=text name=qty_discount size=40 ></td></tr>
		<tr><td width=200 align=right>qty_instock</td><td><input type=text name=qty_instock size=40 ></td></tr>
		<tr><td width=200 align=right>qty_ordered</td><td><input type=text name=qty_ordered size=40 ></td></tr>
		<tr><td width=200 align=right>special</td><td><input type=text name=special size=40 ></td></tr>
		<tr><td width=200 align=right>clearance</td><td><input type=text name=clearance size=40 ></td></tr>
		<tr><td width=200 align=right>can_backorder</td><td><input type=text name=can_backorder size=2 ></td></tr>
		<tr><td width=200 align=right>status</td><td><input type=text name=status size=40 ></td></tr>
		</table>
		<input type=hidden name=m value=product>
		<input type=hidden name=a value=create>
		<input type=submit name=b value=save>
		</form>
			
