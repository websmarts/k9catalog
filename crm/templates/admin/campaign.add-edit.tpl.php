<!-- business.add-edit.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td width="125" align="right">Campaign Name: </td>
    <td><?PHP echo $renderer->elementToHtml('campaignName'); ?></td>
  </tr>
  <tr>
    <td align="right">Description:</td>
    <td><?PHP echo $renderer->elementToHtml('description'); ?></td>
  </tr>
  <tr>
    <td align="right">Start Date:</td>
    <td><?PHP echo $renderer->elementToHtml('startDate'); ?> YYYY-MM-DD</td>
  </tr>
  <tr>
    <td align="right">End Date:</td>
    <td><?PHP echo $renderer->elementToHtml('endDate'); ?> YYYY-MM-DD</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('submit_button'); ?> <input type="button" value="Cancel" class="inputbutton" onClick="javascript:history.back(-1);"> </td>
  </tr>
</table>
</div>