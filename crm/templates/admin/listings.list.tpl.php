<!-- listings.list.tpl.php -->
<script type="text/javascript" language="javascript">
function doConfirm(act,url,id)
{
	isOkay = confirm("Are you sure to "+act+" this record permanently?");
	if(isOkay)
	{
//		window.location.href=url;
		window.location.href="index.php?_a=listingreject&listingID="+id;
	}
}
</script>
<table width="100%">
<? if($data['totalRecord']>0) { ?>
	<tr>
		<td colspan="2" class="blue-bold">
			<? 
			$time = time()-$data['timeStart']; 
			if($time<'1')
				$time = '0.5';
			?>
			<?= format_paging_text($data['totalRecord'],$data['startRec'],$data['endRec'],$time, $data['recInfo'],$data['pagingInfo']); ?>
		</td>
	</tr>
	<tr class="tdhead">
	  <td>Listing Info</td>
	  <td align="center">Status<!-- Actions --> &nbsp;</td>
  </tr>
	<? 
	$i=0;
	while ($data['listingDAO']->fetch()) { ?> 
	<tr <? print ($i%2==0?'bgcolor="#FFFFFF"':'bgcolor="#FFFFF5"') ?>>
		<td valign="top">
		  <b>Title: </b><? echo($data['listingDAO']->title); ?><br>
		  <b>Keywords: </b><? echo($data['listingDAO']->keywords); ?><br>
		  <b>Post Code: </b><? echo($data['listingDAO']->postcode); ?><br>
		  <b>Short Description: </b><br><? echo($data['listingDAO']->shortDescription); ?><br>
		  <b>Description: </b><br><? echo($data['listingDAO']->fullDescription); ?>
		</td>
	  <td width="15%" align="center" valign="top">
	  <input type="button" value="Approve" name="btnApprove" class="inputbutton" onClick="window.location.href='index.php?_a=listingapprove&listingID=<? echo($data['listingDAO']->listingID); ?>'">
	  &nbsp;
	  <input type="button" class="inputbutton" value="Reject" name="btnReject" onClick="javascript:doConfirm('REJECT','index.php?_a=listingreject&listingID=<? echo($data['listingDAO']->listingID); ?>','<? echo($data['listingDAO']->listingID); ?>');">
	  <br>
  	  <? echo(ucfirst($data['listingDAO']->listingType)); ?>
	  </td>
	</tr>
	<?
		$i++;
	} ?>
<? } else { ?>
<tr><td class="blue-bold" colspan="2" align="left">&bull;&nbsp;No search results found matching with specified criteria</td></tr>
<? } ?>
</table>
