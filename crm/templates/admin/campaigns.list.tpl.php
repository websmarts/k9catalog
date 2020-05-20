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
		<td colspan="6" class="blue-bold">
			<? 
			$time = time()-$data['timeStart']; 
			if($time<'1')
				$time = '0.5';
			?>
			<?= format_paging_text($data['totalRecord'],$data['startRec'],$data['endRec'],$time, $data['recInfo'],$data['pagingInfo']); ?>
			<a href="index.php?_a=campaignadd">Add New Campaign</a>
		</td>
	</tr>
	<tr class="tdhead">
	  <td width="20%">Campaign Name</td>
	  <td width="35%">Description</td>
	  <td width="10%" align="center">Start Date</td>
	  <td width="10%" align="center">End Date</td>
	  <td width="10%" align="center"> Status</td>
	  <td width="15%" align="center">Actions</td>
  </tr>
	<? 
	$i=0;
	while ($data['campaignDAO']->fetch()) { ?> 
	<tr <? print ($i%2==0?'bgcolor="#FFFFFF"':'bgcolor="#FFFFF5"') ?>>
		<td valign="top">
		  <? echo($data['campaignDAO']->campaignName); ?>
		</td>
		<td valign="top">
			<? echo($data['campaignDAO']->description); ?>
		</td>
		<td align="center" valign="top">
			<? echo $data['campaignDAO']->startDate; ?>
		</td>
		<td align="center" valign="top">
			<? echo $data['campaignDAO']->endDate; ?>
		</td>
	    <td align="center" valign="top">
			<? echo($data['campaignDAO']->status); ?>
		</td>
      <td align="center" valign="top">
	  		<input type="button" value="Edit" name="btnEdit" class="inputbutton" onClick="javascript:window.location='index.php?_a=campaignedit&campaignID=<? echo($data['campaignDAO']->campaignID); ?>'">
			<input type="button" value="Delete" name="btnDelete" class="inputbutton" onClick="javascript:doDelete('index.php?_a=campaigndelete&campaignID=<? echo($data['campaignDAO']->campaignID); ?>');">
	  </td>
	</tr>	
	<? 
		$i++;
		} ?>
<? } else { ?>
<tr>
  <td class="blue-bold" colspan="6" align="left">&bull;&nbsp;No campaigns found<br><br><a href="index.php?_a=campaignadd">Add New Campaign</a></td></tr>
<? } ?>
</table>
