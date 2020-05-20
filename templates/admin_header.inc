<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html >
	<HEAD>
	<TITLE>K9_Catalog</TITLE>
	<link rel="stylesheet" type="text/css" href="site.css" >
		
		
		
</HEAD>
	<body>
	
	<!--<div style="background:#ff7700;"><?php echo $S->show_current_state();?>	</div>-->
	<TABLE WIDTH="100%"BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<TR>
		<TD HEIGHT=40 width=300 ><img src="images/logo.jpg" width=131 height=40 ></TD>
		<TD HEIGHT="40"  ><span style="color:green;font-weight:600;font-size:150%;"><i>e</i></span></i><span style="font-size:240%;font-weight:700;letter-spacing:5px;"> Catalog</span></TD>
		
		<TD HEIGHT="40" width="150" align=right >
		&nbsp;<span style="font-size:80%;"><? echo date("j M Y");?></span><p>
		<?php 
			$basket_items = count($S->basket);
			if ( $basket_items > 0 ) {
				$ss = $basket_items ==1?"":"s";
				echo "[ $basket_items item$ss ] <a href=\"?m=basket&page=basket&catid=".$req['catid']."\" >View basket</a>";
			}
		?>
		
		</TD>
	</TR>
	<TR><TD colspan="2"  BGCOLOR="#ffffff">
	<?php if ($S->getClientId() ) { // display client name if one is selected
			$client = get_client_details($S->getClientId());
			echo '<span style="font-weight:600;color:orange;font-size:120%;">'. $client[0]['name'].'</span>';
		}
	?>	
		</td>
		<td align=right>
	<?php
				if ($S->getClientId() || $S->role == "admin") {
					echo '<a href=?&m=logout >logout</a>';
				} 
		?>	
		</td>
	</tr>
	
	<TR><TD colspan="3" height=5 BGCOLOR="#ffffff"><span style="font-size:10%;">&nbsp;</span></td></tr>
	
	
	<TR><TD colspan="3" height=5 BGCOLOR="#00aa4f"><span style="font-size:10%;">&nbsp;</span></td></tr>
	<?php 
	if ($error_msg > "" ) {
		echo "<TR><TD colspan=3>$error_msg</TD></TR>\n";
		}
	?>		
	</TABLE>
	
	
<?php	
	function do_menu_row ($cats,$depth) {
	
	//echo "depth=$depth<br>\n";
	//echo dumper($cats);
	if (is_array($cats) ) {
		foreach ($cats as $cat) {
			$spacer = str_repeat("-",($depth * 1) );
			echo "$spacer <a href=\"?m=list&catid=".$cat[0]['id']."\">".$cat[0]['name']."</a><br>\n";
			//echo dumper($cat);
			if (is_array($cat[1]) and count($cat[1]) > 0){
				$depth++;
				do_menu_row($cat[1],$depth);
				$depth--;
			}
		}
		
	}
}

?>


<TABLE width=100% border=1>
<TR>
<!-- dont insert the category menu on the order page -->

		<td valign=top class=admin_menu >
			
			<a href="?">Admin home</a><br/>

			<p><a href="?m=product&a=add">Add a new product</a><br/></P>


	</TD>
	<TD  valign=top >