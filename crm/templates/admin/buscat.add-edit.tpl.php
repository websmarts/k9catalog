<!-- buscat.add-edit.tpl.php -->
<div align="center">
<table width="100%">
  <tr>
    <td align="right">Top Category :</td>
    <td><?PHP echo $renderer->elementToHtml('categoryName'); ?></td>
  </tr>
  <tr>
    <td width="125" align="right">Sub Categories: </td>
    <td><?PHP echo $renderer->elementToHtml('childCategoryIDs'); ?></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td><?PHP echo $renderer->elementToHtml('submit_button'); ?></td>
  </tr>
</table>
</div>