



<?php 

$callOptions = getContactCallTypeOptions(); 

//echo dumper($callOptions);

//echo dumper($k9users);

?>

<h3 style="background-color: #ccc;margin:0;">&nbsp;Runsheet  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: normal">

<form method="get" action="" >

<input type="hidden" name="v" value="runsheet" />

Show runsheet for (date) : <input type="date" name="date" value="<?=$date ?>"/><input type="submit" name="b" value="Go" />

For :<select name="k9user" >

<option value="0">All</option>

<?php foreach($k9users as $k=>$u){

    

    if($k == $req['k9user']){

        $selected = ' selected=selected ';

    } else {

        $selected = ' ';

    }

    echo '<option value="'.$k.'" '.$selected. '>'.$u.'</option>'."\n";

}

?>



</select>

</form>





<?php if(is_array($travelstarts) && count($travelstarts)){

    echo'<table>';

    foreach($travelstarts as $t){

        echo '<tr>';

        echo '<td>'.ucfirst($k9users[$t['sales_rep_id']]).'</td>';

        echo '<td>'.$t['startkm'].'</td>';

        echo '<td>'.date('H:i a',strtotime($t['startkm_timestamp'])).'</td>';

        echo '</tr>';

        

    }

    

    echo '</table>';

}

?>



<?php //echo dumper($clientnotes);?>



<?php if(is_array($clientnotes) && count($clientnotes)):?>

<table width="100%" >

<tr>

<th>Time</th>

<th>Call type</th>

<th>Who(k9)</th>

<th>Client</th>

<th>Talked to </th>



<th>Call Notes</th>

<?php if($S->checkPrivileges('edit_contact_note')):?>

<th></th>

<?php endif;?>

</tr>



<?php $rowclass = 1; ?>

    <?php foreach($clientnotes as $n):?>

    

    <?php

     if(is_array($clientstockcounts) && count ($clientstockcounts)) {

        foreach ($clientstockcounts as $k=>$scount){

            // list any orders that are prior to the client notes datetime

            if($scount['stockcountdate'] < $n['call_datetime']) {

                echo '<tr class="row'.$rowclass++ % 2 .' countrow">

                        <td>'.date('h:i a',strtotime($scount['stockcountdate'])).'</td>

                        <td>Stock count</td>

                        

                        <td>'.$k9users[$scount['rep_id']].'</td>

                        <td>'.$scount['client'].'</td>

                        <td colspan="3"></td>

                        ';

                if($S->checkPrivileges('edit_contact_note')){

                    echo '<td></td>';

                }

                echo '</tr>';

                unset($clientstockcounts[$k]);

            }

        }

    }

    

     

    if(is_array($reporders) && count ($reporders)) {

        foreach ($reporders as $k=>$order){

            // list any orders that are prior to the client notes datetime

            if(substr($order['clientname'],0,3) == '///'){

                continue;

            }

            if($order['modified'] < $n['call_datetime']) {

                $who = $order['client_id'] == $order['ordered_by'] ? "client" : $k9users[$order['reference_id']] ;

                echo '<tr class="row'.$rowclass++ % 2 .' orderrow">

                        <td>'.date('h:i a',strtotime($order['modified'])).'</td>

                        <td><a href="?v=orderview&order_id='.$order['order_id'].'">'.$order['order_id'].'</a></td>

                        

                        <td>'.$who.'</td>

                        <td>'.$order['clientname'].'</td>

                        <td colspan="3">'.$order['instructions'].'</td>

                        ';

                if($S->checkPrivileges('edit_contact_note')){

                    echo '<td></td>';

                }

                echo '</tr>';

                unset($reporders[$k]);

            }

        }

    }

    

    ?>



    <tr  class="row<?php echo $rowclass++ % 2 ?>">

    <td width="70" valign="top"><?php echo date('h:i a',strtotime($n['call_datetime']))?></td>

    <td width="100" valign="top"><?php echo $callOptions[$n['call_type_id']]['call_type']?></td>

    <td width="70" valign="top"><?php echo $n['callby']?></td>

    <td width="120" valign="top"><?php echo '<a href="?v=contact_report&report_client_id='.$n['client_id'].'">'.$n['client'].'</a>'?> </td>

    <td width="70" valign="top"><?php echo $n['contacted']?> </td>

    <td  valign="top"><?php echo $n['note']?><p>&nbsp;</p></td>

        <?php if($S->checkPrivileges('edit_contact_note')):?>

        <td><a href="?v=edit_contact_note&id=<?php echo $n['id'];?>"><img src="images/edit_icon.jpg" width="38" height="38" /></a></td>

        <?php endif;?>

    

    </tr>

    <?php endforeach;?>	

   <?php 

   // list any remainsing stockcounts

   if(is_array($clientstockcounts) && count ($clientstockcounts)) {

        foreach ($clientstockcounts as $k=>$scount){

            // list any orders that are prior to the client notes datetime

            

                echo '<tr class="row'.$rowclass++ % 2 .' countrow">

                        <td>'.date('h:i a',strtotime($scount['stockcountdate'])).'</td>

                        <td>Stock count</td>

                        

                        <td>'.$k9users[$scount['rep_id']].'</td>

                        <td>'.$scount['client'].'</td>

                        <td colspan="3"></td>

                        ';

                if($S->checkPrivileges('edit_contact_note')){

                    echo '<td></td>';

                }

                echo '</tr>';

                unset($clientstockcounts[$k]);

            

        }

    }

   // list any remaining orders

    if(is_array($reporders) && count ($reporders)) {





        foreach ($reporders as $k=>$order){

            // list any orders that are prior to the client notes datetime

            if(substr($order['clientname'],0,3) == '///'){

                continue;

            }

            $who = $order['client_id'] == $order['ordered_by'] ? "client" : $k9users[$order['reference_id']] ;

                echo '<tr class="row'.$rowclass++ % 2 .' orderrow">

                        <td>'.date('h:i a',strtotime($order['modified'])).'</td>

                        <td><a href="?v=orderview&order_id='.$order['order_id'].'">'.$order['order_id'].'</a></td>

                        

                        <td>'. $who .'</td>

                        <td>'.$order['clientname'].'</td>

                        <td colspan="3">'.$order['instructions'].'</td>

                        ';

                if($S->checkPrivileges('edit_contact_note')){

                    echo '<td></td>';

                }

                echo '</tr>';

                unset($reporders[$k]);

            

        }

    }

    

    ?>

</table>

<?php else:?>

<p>No client notes to display</p>

<?php endif;?>







<?php //echo dumper($clientstockcounts);?>







<?php 

   // echo dumper($k9users);

   

   $today = isSet($req['date']) ? $req['date'] : date('y-m-d');

   $data = repSalesOrders($today); 

   //echo dumper($data);

    $reps = array(6,10,9,12); // The reps that are reported on

    if(is_array($data) && count($data)){

        echo '  <table width="100%">

                <tr >

                    <th width="120">Who</th>

                    <th  width="120">Calls</th>

                    <th  width="120">Orders</th>

                    <th  width="120">Sales</th>

                    <th >Conversion</th>

                    <th width="100">Average sale</th>

                </tr>';

        foreach($data as $user_id=>$d){

            if (!in_array($user_id,$reps) ){

                continue;

            }

            $sales = number_format( ($d['sales']/100),2);

            

            $sales_total += $d['sales'];

            $calls_total += $d['calls'];

            $orders_total += $d['orders'];

            

            $cvrate = $d['calls'] > 0 ? number_format((100 * $d['orders']/$d['calls']),0).'%' :'-';

            $avgsale = $d['orders'] > 0 ? '$' .number_format( ($d['sales']/($d['orders'] * 100)),2) : '-';

            

            echo '<tr class="row'.$rowclass++ % 2 .'">

                    <td>'.$k9users[$user_id].'</td>

                    <td>'.$d['calls'].'</td>

                    <td>'.$d['orders'].'</td>

                    <td>$'. $sales .'</td>

                    <td>' . $cvrate .'</td>

                    <td>'. $avgsale .'</td>

                </tr>';

        }

        $sales_total = $sales_total > 0 ? number_format($sales_total/100,2) : '';

        echo'<tr class="row'.$rowclass++ % 2 .'"><td>TOTALS</td><td>'.$calls_total.'</td><td>'.$orders_total.'</td><td>$'.$sales_total.'</td><td colspan="2"></td></tr>';

        echo '</table>';

    }

    

?>