<!-- account.search.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td width="125" align="right">Account Name: </td>
    <td><?PHP echo $renderer->elementToHtml('accountName'); ?></td>
  </tr>
    <td align="right">Post Code:</td>
    <td><?PHP echo $renderer->elementToHtml('postcode'); ?></td>
  </tr>
  <tr>
    <td align="right">Account Type:</td>
    <td><?PHP echo $renderer->elementToHtml('accountType'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('search_button'); ?></td>
  </tr>
</table>
</div>