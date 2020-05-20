



<?php 

$callOptions = getContactCallTypeOptions(); 



if(!empty($S->client['client_note'])){

 echo '<p>'.$S->client['client_note'],'</p>';

}



?>

<?php
$bgcolor = isSet($req['report_client_id']) ? "#fcc": "#ccc";
?>

<?php //echo dumper($client) ?>


<h3 style="background-color: <?php echo $bgcolor ?>;margin:0;">&nbsp;Contact History 
<?php 
echo isSet($req['report_client_id']) ? '('.$client[0]['name'].')' : '';
?>

 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: normal"><?php echo date("g:i a, l jS F Y ")?></span></h3>



<?php if(!isSet($req['report_client_id']))?>

<form id="geoform" method="post" action="" style="border:1px solid #ccc; padding:5px;overflow:hidden; background-color:#fc8" onsubmit="document.getElementById('submit_button').disabled = 1;" >

<input type="hidden" name="longitude" />

<input type="hidden" name="latitude" />

<input type="hidden" name="b" value="Create Note" />



<div style="float:right;width:150px;font-size:20pt;text-align: left; margin-bottom:10px;color:#888">Note Form</div>

&nbsp;&nbsp; <input type="submit" id="submit_button" name="submit_button" value="Create Note"/>

<input type="hidden" name="e" value="save_contact_note" />

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


</form>




<?php //echo dumper($clientnotifymes);?>





<?php if(is_array($clientnotifymes) && count($clientnotifymes)):?>

<table width="100%" class="notifyme">

<tr height="80">

<th>Notify Me Request Date</th>

<th>Product Code</th>

<th>Description</th>

<th>Qty Available</th>

<th>&nbsp;</th>

</tr>



<?php foreach($clientnotifymes as $nme) :?>

<tr height="60">

<td><?php echo $nme['requested']?></td>

<td><?php echo '<a href="?b=go&v=product_search&q='.$nme['product_code'].'">'.$nme['product_code'].'</a>'; ?></td>

<td><?php echo $nme['description']?></td>

<td><?php echo $nme['qty_instock']?></td>

<td><?php echo '<a onclick="return confirm(\'Are you sure want to delete\');" href="?e=SelectClient&client_id='.$S->client['client_id'].'&cancel_notify_me_for='.$nme['product_code'].'">cancel</a>'; ?></td>

</tr>

<?php endforeach;?>



</table>

<?php endif; ?>





<?php //echo dumper($clientnotes);?>
<?php //echo dumper($clientorders);?>

<?php //echo dumper($sql);?>



<?php if(is_array($clientnotes) && count($clientnotes)):?>

<table >

<tr>

<!--<th>Old Call type</th>-->

<th>Call type</th>

<th>Who(k9)</th>

<th>Contact </th>

<th>Talked to </th>

<th>Call Date</th>

<th>Call Notes</th>

</tr>



<?php $rowclass = 1; ?>

    <?php foreach($clientnotes as $n):?>

    

   

    

     <?php // check if any stockcounts before the call date

    

    if(is_array($clientstockcounts) && count($clientstockcounts) >0 ){

        foreach ($clientstockcounts as $q => $count){

            if (strtotime($count['stockcountdate'])  > strtotime($n['calldate']) ){

                

                echo '<tr class="row'.($rowclass++ % 2 ).'"><td>Stock Count</td><td>'.$count['repname'].'</td><td  colspan="4" align="right">'.date("j-m-Y",strtotime($count['stockcountdate'])).' - <a href="index.php?v=stockcountview&date='.$count['stockcountdate'].'&client_id='.$count['client_id'].'&rep_id='.$count['rep_id'].'">view</a></td></tr>';

                unset($clientstockcounts[$q]); // remove from list as now displayed

                

            }

        }   

    } 

    ?>

    

     <?php // check if any orders before the call date

    

    if(is_array($clientorders) && count($clientorders) >0 ){

        foreach ($clientorders as $k => $order){

            if (strtotime($order['modified'])  > strtotime($n['calldate']) ){

                

                echo '<tr class="row'.($rowclass++ % 2 ).'"><td>Order</td><td colspan="5" align="right">'.date("j-m-Y",strtotime($order['modified'])).' - <a href="index.php?v=orderview&order_id=T0_'.$order['id'].'">view</a></td></tr>';

                unset($clientorders[$k]); // remove from list as now displayed

                

            }

        }

        

    }

    

    

    ?>

    

    

    <tr  class="row<?php echo $rowclass++ % 2 ?>">

    <!--<td width="100" valign="top"><?php echo $n['call_type']?></td>-->

    <td width="100" valign="top"><?php echo $callOptions[$n['call_type_id']]['call_type']?></td>

    <td width="70" valign="top"><?php echo $n['callby']?></td>

    <td width="70" valign="top"><?php echo $n['contacts']?> </td>

    <td width="70" valign="top"><?php echo $n['contacted']?> </td>

    <td width="200" valign="top"><?php echo date("g:i a, D jS M Y", strtotime($n['calldate']))?></td>

    <td width="400" valign="top"><?php echo $n['note']?><p>&nbsp;</p></td>

    

    </tr>

    <?php endforeach;?>	

    

    

    <?php // there may be stockcounts BEFORE earliest note date shown

     if(is_array($clientstockcounts) && count($clientstockcounts) >0 ){       

            foreach ($clientstockcounts as $q => $count){

                echo '<tr class="row'.($rowclass++ % 2 ).'"><td>Stock Count</td><td>'.$count['repname'].'</td><td  colspan="4" align="right">'.date("j-m-Y",strtotime($count['stockcountdate'])).' - <a href="index.php?v=stockcountview&date='.$count['stockcountdate'].'&client_id='.$count['client_id'].'&rep_id='.$count['rep_id'].'">view</a></td></tr>';

            }

            

     }

    ?>

    <?php // there may be orders BEFORE earliest note date shown

     if(is_array($clientorders) && count($clientorders) >0 ){       

            foreach ($clientorders as $k => $order){

                echo '<tr class="row'.($rowclass++ % 2 ).'"><td>Order</td><td  colspan="5" align="right">'.date("j-m-Y",strtotime($order['modified'])).' - <a href="index.php?v=orderview&order_id=T0_'.$order['id'].'">view</a></td></tr>';

            }

            

     }

    ?>

</table>

<?php else:?>

<p>No client notes to display</p>



 <?php // there may be stockcounts 

     if(is_array($clientstockcounts) && count($clientstockcounts) >0 ){ 
         echo ' <p>There is no contact history but there is the following stock count history</p>';  
            echo '<table><tr><td colspan="2">Stock counts</td></tr>';    
            foreach ($clientstockcounts as $q => $count){

                echo '<tr class="row'.($rowclass++ % 2 ).'"><td>Stock Count</td><td>'.$count['repname'].'</td><td   align="right">'.date("j-m-Y",strtotime($count['stockcountdate'])).' - <a href="index.php?v=stockcountview&date='.$count['stockcountdate'].'&client_id='.$count['client_id'].'&rep_id='.$count['rep_id'].'">view</a></td></tr>';

            }
            echo '</table>';

            

     }

    ?>

    <?php // there may be orders 

     if(is_array($clientorders) && count($clientorders) >0 ){ 
            echo ' <p>There is no contact history but there is the following order history</p>'; 
            echo '<table><tr><td colspan="2">Order history</td></tr>';      
            foreach ($clientorders as $k => $order){

                echo '<tr class="row'.($rowclass++ % 2 ).'"><td>Order</td><td  colspan="5" align="right">'.date("j-m-Y",strtotime($order['modified'])).' - <a href="index.php?v=orderview&order_id=T0_'.$order['id'].'">view</a></td></tr>';

            }
            echo '</table>';


            

     }

    ?>



<?php endif;?>



<?php //echo dumper($clientstockcounts);?>