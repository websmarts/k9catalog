

<?php 
$callOptions = getContactCallTypeOptions(); 
//echo dumper($callOptions);

?>
<?php //echo dumper($clientnote);?>
<h3 style="background-color: #ccc;margin:0;">&nbsp;Edit Contact Note  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: normal">
<form  method="post" action="" style="border:1px solid #ccc; padding:5px;overflow:hidden; background-color:#fc8" >


<div style="float:right;width:150px;font-size:20pt;text-align: left; margin-bottom:10px;color:#888">Note Form</div>
&nbsp;&nbsp; <input type="submit" name="b" value="Update Note"/>
<input type="hidden" name="e" value="update_contact_note" />
<input type="hidden" name="id" value="<?php echo getFormData('id');?>" />
<div style="float:left">Note type: <select style="" name="call_type_id">
<option value="0">--- select note type ---</option>
<?php foreach($callOptions as $id => $arr) :?>
<?php $selected = getFormData('call_type_id') == $id ? ' selected="selected" ' : ' ';?>

<option <?php echo $selected;?> value="<?php echo $id;?>"><?php echo $arr['call_type'];?></option>
<?php endforeach;?>
</select></div>

<div style="float:left;">&nbsp;&nbsp;&nbsp;Talked to: </div><input type="text" name="contacted" value="<?php echo getFormData('contacted');?>" style="float:left" />
<div style=" clear: both;float:left;" >Note:&nbsp;</div>
<textarea name="note" style="float: left;width:480px; height: 50px"><?php echo getFormData('note');?></textarea>

<p style="clear: both"><input type="checkbox" name="delete_contact_note" > Delete contact note</p>

</form>



