<!-- listings.confirm.tpl.php -->

<div align="center">
<table>
<tr>
<td valign=top width=400 >
<table width="100%">
	 
	  <tr>
	    <td align="right">Title:</td>
	    <td><?PHP echo $renderer->elementToHtml('title'); ?></td>
	  </tr>
	  <tr>
	    <td align="right">Short Description: </td>
	    <td><?PHP echo $renderer->elementToHtml('shortDescription'); ?></td>
	  </tr>
	  
	  <tr>
	    <td align="right">Post Code:</td>
	    <td><?PHP echo $renderer->elementToHtml('postcode'); ?></td>
	  </tr>
	  
	    <td>&nbsp;</td>
	    <td>
			<input type=submit name="submit_button" value="confirm" class="inputbutton">
			<input type=submit name="submit_button" value="update" class="inputbutton">
			<?PHP echo $renderer->elementToHtml('hiddenalert'); ?>
			
		</td>
	  </tr>
	    <tr>
	      <td colspan="2">&nbsp;</td>
	    </tr>
	</table>
</td>

<td valign=top width=200 >
Category list<br>
<? 
global $catids,$selectedCats;

if (is_array($catids) ) {
	
	foreach($catids as $cat){
		$checked = $selectedCats[$cat[0]];
		
		echo '<input type=checkbox name=catids['.$cat[0].'] '.$checked.' >'.$cat[1].'<br>';
	}
}


?>





</td>

<td valign=top>
Business matching list<br>
<?
global $businesses;

if (is_array($businesses) && count($businesses)> 0) {
	foreach ($businesses as $id => $vals){
		echo "<li>".$vals['businessName']."<br>";
	}
}

?>
</td>
</tr>

	
</table>
</div>
