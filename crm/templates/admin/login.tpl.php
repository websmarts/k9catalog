<!-- login.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td width="125" align="right">User name: </td>
    <td><?PHP echo $renderer->elementToHtml('userName'); ?></td>
  </tr>
  <tr>
    <td align="right">Password:</td>
    <td><?PHP echo $renderer->elementToHtml('password'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('login_button'); ?></td>
  </tr>
</table>
</div>