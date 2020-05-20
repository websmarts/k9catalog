<!-- account.list.tpl.php -->
<? $currentURL= urlencode(HOST."/admin/index.php?".$_SERVER['QUERY_STRING']); ?>
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
		<td colspan="5" class="blue-bold">
			<? 
			$time = time()-$data['timeStart']; 
			if($time<'1')
				$time = '0.5';
			?>
			<?= format_paging_text($data['totalRecord'],$data['startRec'],$data['endRec'],$time, $data['recInfo'],$data['pagingInfo']); ?>
		</td>
	</tr>
	<tr class="tdhead">
	  <td width="25%">Account  Info</td>
	  <td width="25%"><? print ($data['accountType']=='domesticuser'?'User':'Business') ?> Info</td>
	  <td width="20%">Contact Info </td>
	  <td width="15%"align="center">Trust Level</td>
	  <td align="center">Actions</td>
  </tr>
	<? 
	$i=0;
	while ($data['accountDAO']->fetch()) { ?> 
	<tr <? print ($i%2==0?'bgcolor="#FFFFFF"':'bgcolor="#FFFFF5"') ?>>
		<td valign="top">
		  <b><? echo($data['accountDAO']->userName); ?> / <? echo($data['accountDAO']->password); ?></b><br>
		  <? echo($data['accountDAO']->email); ?><br>
		</td>
		<td valign="top">
		<b><? 
			 if($data['accountType']=='domesticuser')
			 	print $data['accountDAO']->firstName . " " . $data['accountDAO']->lastName;
			 else
			 	print $data['accountDAO']->businessName
			?></b><br>
			<?= $data['accountDAO']->address1 ?> <?= $data['accountDAO']->address2?><br>
			<?= $data['accountDAO']->city ?>-<?= $data['accountDAO']->postcode?>
		</td>
		<td valign="top">
			Phone: <? echo($data['accountDAO']->phoneAreaCode); ?> <? echo($data['accountDAO']->phoneNumber); ?><br>
			Cell: <? echo($data['accountDAO']->cellPhoneNumber); ?><br>
			Fax: <? echo($data['accountDAO']->faxNumber); ?><br>
		</td>
	    <td width="15%" align="center" valign="top">
			<?= $data['accountDAO']->trustLevel ?><br>
			<a href="index.php?_a=changetrustlevel&accountID=<? echo($data['accountDAO']->accountID); ?>&currenturl=<?= htmlentities($currentURL)?>">Change Trust Level</a>
		</td>
      <td align="center" valign="top">
	  	<?
			if($data['accountType']=='domesticuser')
				$editURL = "index.php?_a=useredit&userID=".$data['accountDAO']->userID;
			else
				$editURL = "index.php?_a=busedit&businessID=".$data['accountDAO']->businessID;
		?>
	  	<input type="button" value="Edit" name="btnEdit" class="inputbutton" onClick="javascript:window.location='<?= $editURL ?>'">
		<input type="button" class="inputbutton" value="Delete" name="btnDelete" onClick="javascript:doDelete('index.php?_a=accountdelete&accountID=<? echo($data['accountDAO']->accountID); ?>');">
		<br><b><? echo(ucfirst($data['accountDAO']->status)); ?></b>
		</td>
	</tr>	
	<? 
		$i++;
		} ?>
<? } else { ?>
<tr><td class="blue-bold" colspan="5" align="left">&bull;&nbsp;No search results found matching with specified criteria</td></tr>
<? } ?>
</table>
