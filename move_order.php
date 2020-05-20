<?php
// Initial setup //
require_once './adodb_lite/adodb.inc.php';
include_once('./lib/db.inc');
include_once('./lib/common.inc');

$db_debug = 1;

$message ='';
if($_POST && !empty($_POST['order_id']) && $_POST['key']=='Kh56D6en' && !empty($_POST['client_id'])){
    $orderId = (int) $_POST['order_id'];
    $clientId = (int) $_POST['client_id'];
    if($orderId > 0 && $clientId > 0){
        $sql1 = "update system_orders set client_id=$clientId where id=".$orderId .' LIMIT 1';
        echo $sql1;
        $res1 = do_query($sql1);
        
        
    }
    


}

$clients = $db->getArray("select * from clients order by name asc");
//echo pr($clients);

if(!empty($message)){
    echo $message;
}




	
?>
<form action="" method="post">
Order ID to move eg 17654 <input name="order_id" />
<br />
Select Client to move order to: <select  name="client_id" >
<?php foreach ($clients as $c){
    echo '<option value="'.$c['client_id'].'">'.$c['name'].'</option>';
}
?>

</select>
<br />
Key (khx): <input type="password" name="key" /><br />
<input type="submit" name="b" value="Move Order" />
</form>