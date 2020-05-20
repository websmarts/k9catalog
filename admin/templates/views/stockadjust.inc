<?php
  
?>
<h3>Stock Adjust</h3>




<table border="1" style="border: 1px solid #333" cellpadding="5" cellspacing="4" >
<tr>
<td valign="top">
<form action="" method="post" >
<input type="hidden" name="m" value="product" />
<input type="hidden" name="a" value="stockadjust" />

<p>Select Product Code </p>
<select name="product_code" >
<option value="" > ----- select product ------- </option>
<?php foreach ($products  as $p) {
    //$selected = $p['product_code'] == $selected_product[0]['product_code'] ? ' selected="selected" ' : '';
    echo '<option '.$selected.' value="'.$p['product_code'].'">'.$p['product_code'].'</option>';
}
?>

</select>

<p>Barcode:<br /><input type=text name="barcode" value="<?php //echo $selected_product[0]['barcode'];?>"/></p>
<div style="clear:both; margin-top:30px;text-align: center;"><input type="submit" name="b" value="Get product data" /></div>
</form>
</td>
<td valign="top">
<h3> Product data</h3><div style=" font-size:20px">
<?php // echo dumper($selected_product);?>
<p><label>Product code:</label> <?php echo $selected_product[0]['product_code'];?></p>
<p><label>Barcode:</label> <?php echo $selected_product[0]['barcode'];?></p>
<p><label>Description:</label> <?php echo nl2br($selected_product[0]['description']);?></p>

<p><label>Size:</label> <?php echo stripslashes($selected_product[0]['size']);?></p>

<p><label>Color:</label> <?php echo $selected_product[0]['color_name'];?></p>

<p><label>Current Stock:</label> <?php echo $selected_product[0]['qty_instock'];?></p>
</div>
<form action="" method="post"  style="padding: 5px; background:#eef">
<input type="hidden" name="m" value="product" />
<input type="hidden" name="a" value="stockadjust" />
<input type="hidden" name="selected_product_code" value="<?php echo $selected_product[0]['product_code'];?>" />
<p>QTY: <input type="text" name="entered_qty" /></p>
<input type="submit" name="b" value="Take QTY out of stock" />
<input type="submit" name="b" value="Add QTY to current stock" />
<input type="submit" name="b" value="Set current stock to QTY" />

</form>
</td></tr></table>


</form>

<?php //echo dumper($client_specialprices);?>

<?php //echo dumper($product_specialprices);?>

<?php 
if (isSet($client_specialprices) && is_array($client_specialprices) && count($client_specialprices))
{
    echo '<table  border="1" cellpadding="4" >';
    foreach($client_specialprices as $client => $items)
    {
        echo '<tr><td colspan="3">'.$client.'</td></tr>';
        echo '<tr><td>Product</td><td>Special price</td><td> Std Price</td></tr>';
        foreach ($items as $i)
        {
            echo '<tr><td>'.$i['product_code'] .'</td><td>$'.number_format($i['client_price']/100,2).'<td>$'.number_format($i['std_price']/100,2).'</td></tr>';
        }
    }
    
    echo '</table>';
}
?>

<?php
if (isSet($product_specialprices) && is_array($product_specialprices) && count($product_specialprices)){
    echo '<table border="1" cellpadding="4" >';
    foreach($product_specialprices as $pcode => $items)
    {
        echo '<tr><td colspan="3">'.$pcode.'</td></tr>';
        echo '<tr><td>Client</td><td>Special price</td><td> Std Price</td></tr>';
        foreach ($items as $i)
        {
            echo '<tr><td>'.$i['name'] .'</td><td>$'.number_format($i['client_price']/100,2).'</td><td>$'.number_format($i['std_price']/100,2).'</td></tr>';
        }
    }
    
    echo '</table>';    
}
echo $_SESSION['history'];

if(strlen($_SESSION['history']) > 200 ){
    $_SESSION['history'] = substr($_SESSION['history'],0,strrpos($_SESSION['history'],'<br />'));
}

echo '<hr>';