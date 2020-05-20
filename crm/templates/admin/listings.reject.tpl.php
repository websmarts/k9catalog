<!-- listings.reject.tpl.php -->

<div align="center">
<table width="100%">
  <tr>
    <td align="right">Listing Type: </td>
    <td><?PHP echo $renderer->elementToHtml('listingType'); ?></td>
  </tr>
  <tr>
    <td align="right">Title:</td>
    <td><?PHP echo $renderer->elementToHtml('title'); ?></td>
  </tr>
  <tr>
    <td align="right">Short Description: </td>
    <td><?PHP echo $renderer->elementToHtml('shortDescription'); ?></td>
  </tr>
  <tr>
    <td align="right">Rejection Notes: </td>
    <td><?PHP echo $renderer->elementToHtml('adminNotes'); ?></td>
  </tr>
    <td>&nbsp;</td>
    <td>
		<?PHP echo $renderer->elementToHtml('submit_button'); ?>
		<input type="button" value="Cancel" class="inputbutton" onClick="javascript:history.back(-1);">
	</td>
  </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
</table>
</div>
