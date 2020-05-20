<?php
  echo "<h3>Update Client Prices</h3>";
  
  //echo dumper($clientprices);
  
  
?>
<form method="post" action="?v=client_special_prices">

<input type="submit" name="b" value="update" >
<table width="100%">
<tr>
<th>Product Code</th>
<th>Description</th>
<th>Price</th>
<th>Client Price</th>
<th>Discount</th>
<th>Remove</th>
</tr>

<tr>
<th colspan="3"><select name="new_product_code">
<option value="" >-- select product code --</option>
<?php foreach($products as $p){
    echo '<option value="'.$p['product_code'].'">'.$p['product_code'].' $'.number_format($p['price']/100,2).'</option>'."\n";
}
?>
</select></th>

<th>$<input size="4" type="text" name="price" /> </th>
<th>&nbsp;</th>
<th>&nbsp;</th>
</tr>
<tr><td>Bulk update:<br /><span style="font-family: courier; color:#800;">productcode  cents </span><br />(one entry per line)</td><td colspan="5"><textarea rows="1" cols="30" name="bulk" ></textarea></td></tr>
<?php if(is_array($clientprices) && count($clientprices)):?>
<?php foreach($clientprices as $cp):?>
<tr>
<td><?php echo $cp['product_code'];?></td>
<td><?php echo $cp['description'];?></td>
<td><?php 
if($cp['price'] != 0){
    echo number_format($cp['price']/100,2);
} else {
    echo '0';
}

?></td>
<td>
<?php 
if($cp['client_price'] != 0){
    echo number_format($cp['client_price']/100,2);
} else {
    echo '0';
}

?>
</td>
<td>
<?php 
$discount = 100 * ($cp['price'] - $cp['client_price'])/$cp['price'];
if($cp['client_price'] != 0){
    echo number_format($discount,2);
} else {
    echo '0';
}
?>
%
</td>
<td>
<input type="checkbox" name="delete[<?php echo $cp['product_code'];?>]" />
</td>
</tr>

<?php endforeach;?>
<?php endif;?>

</table>
</form>