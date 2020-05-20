<!-- campaign.assign.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td width="125" align="right">Business Category: </td>
    <td><?PHP echo $renderer->elementToHtml('catID'); ?></td>
  </tr>
  <tr>
    <td align="right">Search</td>
    <td><?PHP echo $renderer->elementToHtml('distance'); ?>  Kms around post code <?PHP echo $renderer->elementToHtml('postcode'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('submit_button'); ?></td>
  </tr>
</table>
</div>