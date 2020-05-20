<!-- business.list.tpl.php -->
<script type="text/javascript" language="javascript">
function doDelete(url)
{
	isOkay = confirm("Are you sure to DELETE this record permanently?");
	if(isOkay)
	{
		window.location.href=url;
	}
}
</script>
<table width="100%">
<? if($data['totalRecord']>0) { ?>
	<tr>
		<td colspan="4" class="blue-bold">
			<? 
			$time = time()-$data['timeStart']; 
			if($time<'1')
				$time = '0.5';
			?>
			<?= format_paging_text($data['totalRecord'],$data['startRec'],$data['endRec'],$time, $data['recInfo'],$data['pagingInfo']); ?>
			<a href="index.php?_a=busadd">Add New Business</a>
		</td>
	</tr>
	<tr class="tdhead">
	  <td>Business Info</td>
	  <td>Category</td>
	  <td>Contact No </td>
	  <td align="center">Actions</td>
  </tr>
	<? 
	$i=0;
	while ($data['busDAO']->fetch()) { ?> 
	<tr <? print ($i%2==0?'bgcolor="#FFFFFF"':'bgcolor="#FFFFF5"') ?>>
		<td valign="top">
		  <b><? echo($data['busDAO']->businessName); ?></b><br>
		  <? echo($data['busDAO']->address1); ?><br>
		  <? echo($data['busDAO']->city); ?>-<? echo($data['busDAO']->postcode); ?>
		</td>
		<td width="40%" valign="top">
			<? 
				$cat = getBizCatName($data['busDAO']->businessID); 
				if (trim($cat) == "")
					$cat = $data['busDAO']->businessCategory; 
				print $cat;
			?>
		</td>
		<td width="15%" valign="top">
			Phone: <? echo($data['busDAO']->phoneAreacode); ?>-<? echo($data['busDAO']->phoneNumber); ?><br>
			Cell: <? echo($data['busDAO']->cellPhoneNumber); ?><br>
			Fax: <? echo($data['busDAO']->faxNumber); ?><br>
		</td>
	  <td width="15%" align="center" valign="top"><input type="button" value="Edit" name="btnEdit" class="inputbutton" onClick="javascript:window.location='index.php?_a=busedit&businessID=<? echo($data['busDAO']->businessID); ?>'">&nbsp;<input type="button" class="inputbutton" value="Delete" name="btnDelete" onClick="javascript:doDelete('index.php?_a=busdelete&businessID=<? echo($data['busDAO']->businessID); ?>');"></td>
	</tr>	
	<? 
		$i++;
		} ?>
<? } else { ?>
<tr><td class="blue-bold" colspan="4" align="left">&bull;&nbsp;No search results found matching with specified criteria<br><br><a href="index.php?_a=busadd">Add New Business</a></td></tr>
<? } ?>
</table>