<?php
  
?>
<h3>Edit Special Prices</h3>

<form action="" method="post" >
<input type="hidden" name="m" value="product" />
<input type="hidden" name="a" value="specialprices" />
<input type="hidden" name="sa" value="updateprice" />


<table style="border: 1px solid #333" cellpadding="5" cellspacing="4" >
<tr>
<td valign="top">
Clients:<br />
<?php 
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';
?>
<select name="clients[]" multiple="multiple" size="20">
<option> ----- select clients(s) ------- </option>
<?php foreach ($clients as $c) {
   $selected = in_array($c['client_id'],$_POST['clients']) ?' selected = "selected" ' : '';
    echo '<option '.$selected.' value="'.$c['client_id'].'">'.$c['name'].'</option>';
}
?>

</select>
<div style="clear:both; margin-top:30px;text-align: center;"><input type="submit" name="b" value="List Client Special Prices" /></div>
</td>
<td valign="top">
<div style="clear:both; margin:10px 0 10px 0;text-align: right;">

<input type="submit" name="b" value="List Product Special Prices" /></div>
<div style="padding:5px; background:#ddf" >
<label style="float:left; width: 160px; margin-right: 10px;">Product</label><select name="product_code" >
<option> ----- select product ------- </option>
<?php foreach ($products  as $p) {
    $selected = $p['product_code'] == $_GET['pcode'] ?' selected = "selected" ' : '';
    echo '<option '.$selected.' value="'.$p['product_code'].'">'.$p['product_code'].' | '.$p['price'].' | '.$p['cost'].'</option>';
}
?>

</select><br /><br />
<label  style="float:left; width: 160px; margin-right: 10px;">Special Price (cents)</label><input type="text" name="special_price" />

<div style="clear:both; margin-top:30px;text-align: center;"><input type="submit" name="b" value="Save" /></div>
</div>

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
