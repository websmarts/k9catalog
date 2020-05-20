<!-- account.trustlevel.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td width="125" align="right">User Name: </td>
    <td><?PHP echo $renderer->elementToHtml('userName'); ?></td>
  </tr>
  <tr>
    <td align="right">Account Type:</td>
    <td><?PHP echo $renderer->elementToHtml('accountType'); ?></td>
  </tr>
  <tr>
    <td align="right">Trust Level:</td>
    <td><?PHP echo $renderer->elementToHtml('trustLevel'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('submit_button'); ?>&nbsp;<input type="button" value="Cancel" class="inputbutton" onClick="javascript:history.back(-1)"</td>
  </tr>
</table>
</div>