<!-- business.search.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td width="125" align="right">Business Title: </td>
    <td><?PHP echo $renderer->elementToHtml('businessName'); ?></td>
  </tr>
  <tr>
    <td align="right">Address:</td>
    <td><?PHP echo $renderer->elementToHtml('address1'); ?></td>
  </tr>
  <tr>
    <td align="right">City:</td>
    <td><?PHP echo $renderer->elementToHtml('city'); ?></td>
  </tr>
  <tr>
    <td align="right">Post Code:</td>
    <td><?PHP echo $renderer->elementToHtml('postcode'); ?></td>
  </tr>
  <tr>
    <td align="right">Category:</td>
    <td><?PHP echo $renderer->elementToHtml('catID'); ?></td>
  </tr>
  <tr>
    <td align="right">Phone Area Code: </td>
    <td><?PHP echo $renderer->elementToHtml('phoneAreacode'); ?></td>
  </tr>
  <tr>
    <td align="right">Phone No:</td>
    <td><?PHP echo $renderer->elementToHtml('phoneNumber'); ?></td>
  </tr>
  <tr>
    <td align="right">Cell No: </td>
    <td><?PHP echo $renderer->elementToHtml('cellPhoneNumber'); ?></td>
  </tr>
  <tr>
    <td align="right">Fax No:</td>
    <td><?PHP echo $renderer->elementToHtml('faxNumber'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('search_button'); ?>&nbsp;<?PHP echo $renderer->elementToHtml('add_bus_link'); ?></td>
  </tr>
</table>
</div>