<?php
  

$header = 'Co./Last Name,First Name,Addr 1 - Line 1,           - Line 2,           - Line 3,           - Line 4,Inclusive,Invoice #,Date,Customer PO,Ship Via,Delivery Status,Item Number,Quantity,Description,Price,Inc-Tax Price,Discount,Total,Inc-Tax Total,Job,Comment,Journal Memo,Salesperson Last Name,Salesperson First Name,Shipping Date,Referral Source,Tax Code,Non-GST Amount,GST Amount,LCT Amount,Freight Amount,Inc-Tax Freight Amount,Freight Tax Code,Freight Non-GST Amount,Freight GST Amount,Freight LCT Amount,Sale Status,Currency Code,Exchange Rate,Terms - Payment is Due,           - Discount Days,           - Balance Due Days,           - % Discount,           - % Monthly Charge,Amount Paid,Payment Method,Payment Notes,Name on Card,Card Number,Expiry Date,Authorisation Code,BSB,Account Number,Drawer/Account Name,Cheque Number,Category,Location ID,Card ID,Record ID'."\r\n";



require_once'../adodb_lite/adodb.inc.php';
include_once('../lib/db.inc');
//include_once('lib/common.inc');


function pr($a) {
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}

function qc($str){ // quote commas in csv
    return str_replace(',','","',$str);
}

function format_line($l){
  
    $o ='';
    $o .= qc($l['Co./Last Name']).',';// Co./Last Name
    $o .= ',';// First Name
    $o.=',';// Addr 1 - line 1
    $o.=',';// Addr 1 - line 2
    $o.=',';// Addr 1 - line 3
    $o.=',';// Addr 1 - line 4
    $o .= ',';// Inclusive
    $o .= ',';// Invoice #
    $o .= $l['order_date'].',';// Date - order date dd/mm/yyyy
    $o .= ',';// Customer PO
    $o .= ',';// Ship
    $o .= ',';// Delivery Statu
    $o.=qc(strtoupper($l['Item Number'])).',';// Item Number
    $o.=qc($l['Quantity']).',';// Quantity
    $o.=',';// Description
    $o.= '$'.number_format($l['Price']/100,2,'.','').',';// Price in dollars and cents MUST have $ sign eg $23.45 - ex gst
    
    $extprice = 1.1 *  ($l['Stdprice'] * $l['Quantity'])/$l['Quantity']; // cents
    $priceplusgst = number_format($extprice/100,2,'.','');
    $o.= '$'.$priceplusgst.',';// Price inc gst    
    $o .= ',';// Discount
    
    $invprice =$l['Quantity'] * $l['Invprice'];
    $invprice_plusgst = 1.1 * $invprice;
    $o.= '$'.number_format($invprice/100,2,'.','').',';// Total Price ex gst
    $o.= '$'.number_format($invprice_plusgst/100,2,'.','').',';// Total Price inc gst
    $o .= ',';// Job
    $o .= ',';// Comment
    $o .= ',';// Journal Memo
    $o.=qc($l['Sales Person Last Name']).',';// Sales Person First Name
    $o.=qc($l['Sales Person First Name']).',';// Sales Person Last Name
    $o .= ',';// Shipping Date
    $o .= ',';// Referral Source
    $o .= 'GST,';// Tax code
    $o .= ',';// Non GST Amount
    $o .= '$'.number_format(($invprice_plusgst - $invprice)/100,2,'.','').',';// GST Amount
    $o .= ',';// LCT Amount
    $o .= ',';// Freight Amount
    $o .= ',';// Inc Tax Freight Amount
    $o .= ',';// Freight Tax Code
    $o .= ',';// Freight Non Gst Amount
    $o .= ',';// Freight GST Amount
    $o .= ',';// Freight LCT Amount
    $o .= 'O,';// Sales Status O for order
    $o .= ',';// Currency Code
    $o .= ',';// Exchange Rate
    $o .= ',';// Terms payment due
    $o .= ',,,,,,,,,,,,,,,,,,'."\r\n"; // last 22 blank fields
    
    
    
    
    
    
    
    
    return $o;
}

if($_POST && !empty($_POST['order_id'])){
    
    // get the order details and export to csv
    $orderId = (int) $_POST['order_id'];
    if($orderId > 0 ){
        $sql = '    
        SELECT 
        so.id AS order_id,
        DATE_FORMAT(so.modified,"%d-%m-%Y") AS order_date,
        users.firstname AS `Sales Person First Name`,
        users.lastname AS `Sales Person Last Name`,
        soi.product_code AS `Item Number`,
        soi.qty AS Quantity,
        p.price AS Stdprice,
        soi.price as Invprice,
        c.name as `Co./Last Name`,
        c.address1,
        c.address2,
        c.city,
        c.postcode

        FROM system_orders AS so
        JOIN system_order_items AS soi ON so.order_id=soi.order_id
        LEFT JOIN clients AS c ON so.client_id = c.`client_id`
        JOIN users ON users.id=so.`reference_id`
        JOIN products as p on p.product_code=soi.product_code
        WHERE so.id='.$_POST['order_id'];
        
        
        $orderlines = do_query($sql);
        
        //pr($orderlines);
        
        $o = $header;
        
        foreach ($orderlines as $l){
            $o .= format_line($l);
        }
        
       // pr($o);exit;
        // output as csv file.
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="TO_'.$orderId.'.txt"');
        echo $o;
        exit;
    }
    
    
}
?>


<h1>Order Export to MYOB Tester</h1>
<form action="" method="post">
Order ID to export eg 17654 <input name="order_id" />
<br />

<input type="submit" name="b" value="Export Order" />
</form>

