<!-- buscat.list.tpl.php -->
<script type="text/javascript" language="javascript">
function doDelete(url)
{
	isOkay = confirm("Are you sure to DELETE this record permanently?\n\n PLEASE NOTE: If you delete a parent category, all the child categories will be deleted.");
	if(isOkay)
	{
		window.location.href=url;
	}
}
</script>
<table width="100%">
<? if($data['totalRecord']>0) { ?>
	<tr>
		<td colspan="3" class="blue-bold">
		<? 
			$time = time()-$data['timeStart']; 
			if($time<'1')
				$time = '0.5';
			?>
			<?= format_paging_text($data['totalRecord'],$data['startRec'],$data['endRec'],$time, $data['pagingInfo']); ?>
			<a href="index.php?_a=buscatadd">Add New Category</a>
		</td>
	</tr>
	<tr class="tdhead">
	  <td>Category Name</td>
	  <td align="left">Parent Category </td>
	  <td align="center">Actions</td>
  </tr>
	<? 
	$i=0;
	while ($data['catDAO']->fetch()) { 
	?> 
	<tr <? print ($i%2==0?'bgcolor="#FFFFFF"':'bgcolor="#FFFFF5"') ?>>
		<td valign="top">
		  <b><? echo($data['catDAO']->categoryName); ?></b>
		</td>
		
		<td width="15%" align="center" valign="top"><input type="button" value="Edit" name="btnEdit" class="inputbutton" onClick="javascript:window.location='index.php?_a=buscatedit&businessCatID=<? echo($data['catDAO']->categoryID); ?>'">&nbsp;<input type="button" class="inputbutton" value="Delete" name="btnDelete" onClick="javascript:doDelete('index.php?_a=buscatdelete&businessCatID=<? echo($data['catDAO']->categoryID); ?>');"></td>
	</tr>	
	<? 
		$i++;
		} ?>
<? } else { ?>
<tr><td class="blue-bold" colspan="3" align="left">&bull;&nbsp;No categories available<br><br><a href="index.php?_a=buscatadd">Add New Category</a></td></tr>
<? } ?>
</table>
