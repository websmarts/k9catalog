<!-- users.add-edit.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td align="right">First Name:</td>
    <td><?PHP echo $renderer->elementToHtml('firstName'); ?></td>
  </tr>
  <tr>
    <td width="125" align="right">Last Name: </td>
    <td><?PHP echo $renderer->elementToHtml('lastName'); ?></td>
  </tr>
  <tr>
    <td align="right">Address Line1:</td>
    <td><?PHP echo $renderer->elementToHtml('address1'); ?></td>
  </tr>
  <tr>
    <td align="right">Address Line2:</td>
    <td><?PHP echo $renderer->elementToHtml('address2'); ?></td>
  </tr>
  <tr>
    <td align="right">City:</td>
    <td><?PHP echo $renderer->elementToHtml('city'); ?></td>
  </tr>
  <tr>
    <td width="125" align="right">Post Code: </td>
    <td><?PHP echo $renderer->elementToHtml('postcode'); ?></td>
  </tr>
  <tr>
    <td align="right">Phone Area Code:</td>
    <td><?PHP echo $renderer->elementToHtml('phoneAreaCode'); ?></td>
  </tr>
  <tr>
    <td align="right">Phone Number:</td>
    <td><?PHP echo $renderer->elementToHtml('phoneNumber'); ?></td>
  </tr>
  <tr>
    <td align="right">Cell Number:</td>
    <td><?PHP echo $renderer->elementToHtml('cellPhoneNumber'); ?></td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('submit_button'); ?> <input type="button" value="Cancel" class="inputbutton" onClick="javascript:history.back(-1);"> </td>
  </tr>
</table>
</div>