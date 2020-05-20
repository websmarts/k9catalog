<?php
   // including main script file:
   include_once "../../lib/rtf_class.php";
   require_once'../../../adodb_lite/adodb.inc.php';
   include_once('../../lib/db.inc');
   
  // echo "Hello there<p>\n";
   
   
   $sql = "select system_orders.*,clients.name from system_orders,clients where system_orders.client_id=clients.client_id and order_id='".$req['order_id']."'";
	//echo dumper($sql);
	$res = do_query($sql);
	$order=$res[0];
	
	$sql = "select system_order_items.*,products.description,products.size from system_order_items,products where system_order_items.product_code=products.product_code and order_id='".$req['order_id']."' order by product_code asc";
	$order_items = do_query($sql);
	
	// now we assume the print is successful so set the status to printed - date
	$sql = "update system_orders set status='printed' where order_id='".$req['order_id']."' and status='new'";
	$res = do_query($sql);
	
	
   
   // this will be the name of our RTF file:
   $file_rtf = "report.rtf";
   // HTTP headers saying that it is a file stream:
 //  Header("Content-type: application/octet-stream");
   // passing the name of the streaming file:
   Header("Content-Disposition: attachment; filename=$file_rtf");

   // creating class object and passing to it the path to configuration file:
//   $rtf = new RTF("rtf_config.inc");
   // passing the text to the object:
   
   // $markup = make_report(); //

    

$markup .= 	"<table><tr><td width=30 >Customer:</td>".
						"<td width=110 ><font size=16 face=arial><b>".$order['name']."</b></font></td>".
						"<td width=30><font size=9>Order ref</font><br>".$order['order_id']."</td></tr></table>";
						
$markup .= 	"<p>";

$markup .= 	"<table>";
$markup .= 	"<tr height=12 bgcolor=20% >".
						" <td>Product code</td>".
						" <td>Description</td>".
						" <td>Price($)</td>".
						" <td>Qty Orderd</td>".
						" <td>Ext Price($)</td>".
						"</tr>";

foreach ($order_items as $i) {
        $price = number_format($i['price']/100,2);
        $markup .= 	"<tr height=8 valign=middle >".
        						" <td width=30 ><p lindent=1><font size=8>".$i['product_code'] ."</font></td>".
        						" <td width=75 ><p lindent=1><font size=8>".$i['description']." : ".$i['size']."</font></td>".
        						" <td width=15 align=right ><p rindent=1 align=right>".$price." </td>".
        						" <td width=20  ><p rindent=1 align=right>".$i['qty']." </td>".
        						" <td width=20  ><p rindent=1 align=right>".number_format($price * $i['qty'],2) ." </td>".
        						"</tr>";
        $total_cost += $price * $i['qty'];
}
$markup .= 	"<tr height=12 valign=middle  >".
						" <td width=30>Instructions:</td>".
						" <td colspan=3 align=center>".$order['instructions']."</td>".
						" <td width=30 align=right ><p rindent=1 align=right><b>".number_format($total_cost,2)."<b> </td>".
						"</tr>";
$markup .= "</table>";

   $rtf->parce_HTML($markup);
   // getting RTF code:
   $fin = $rtf->get_rtf();
   // streaming the file to the user:
   echo $fin;
?>
