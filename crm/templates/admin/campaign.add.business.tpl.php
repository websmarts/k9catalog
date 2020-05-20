<!-- campaign.add.business.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td width="125" align="right">Campaign: </td>
    <td><?PHP echo $renderer->elementToHtml('campaignID'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
		<?PHP echo $renderer->elementToHtml('submit_button'); ?>
		<input type="button" value="Cancel" class="inputbutton" onClick="javascript:history.back(-1)">
	</td>
  </tr>
</table>
</div>