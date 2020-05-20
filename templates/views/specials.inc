Customer Notes

<?php //echo dumper($clientnotes);?>

<?php if(is_array($clientnotes) && count($clientnotes)):?>
<table >
<tr>
<th>Call type</th>
<th>Contact</th>
<th>Call Date</th>
<th>Call Notes</th>
</tr>
    <?php foreach($clientnotes as $n):?>
    <tr>
    <td width="100" valign="top"><?php echo $n['call_type']?></td>
    <td width="100" valign="top"><?php echo $n['contacts']?></td>
    <td width="100" valign="top"><?php echo $n['calldate']?></td>
    <td width="400" valign="top"><?php echo $n['note']?></td>
    
    </tr>
    <?php endforeach;?>	
</table>
<?php else:?>
No client notes to display
<?php endif;?>
