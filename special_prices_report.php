<?php
/**
 * Copy  MyPetWarehouse - South Melbourne prices to
 * all other clients with the same parent
 */

//echo "Safe mode - exiting... nothing done";exit;

// Initial setup //
require_once'adodb_lite/adodb.inc.php';
include_once('lib/db.inc');
include_once('lib/common.inc');

$db->debug = false;



// get a list of of all special prices$sql = '
$sql ='
SELECT C.name, CP.client_price, P.product_code, P.price, P.cost, ((CP.client_price - P.cost)/CP.client_price)*100 AS markup
FROM client_prices CP
LEFT JOIN clients C ON C.client_id=CP.client_id
LEFT JOIN products P ON CP.product_code=P.product_code
ORDER BY markup asc
';

$specials = $db->getArray($sql);
//echo pr($specials); exit;
$o = '<table>';
$o .='<tr style="font-weight: bold">';
$o .= '<td>Client</td>';
$o .= '<td width="100" >Product</td>';
$o .= '<td width="100" >Client Price</td>';
$o .= '<td width="100" >STD Price</td>';
$o .= '<td width="100" >Cost</td>';
$o .= '<td width="100" >Markup</td>';
$o .= '</tr>'."\n";

foreach ($specials as $r) {
    if (!empty($r['product_code'])) {
        $o .= '<tr>';
        $o .= '<td>' . $r['name'] . '</td>';
        $o .= '<td>' . $r['product_code'] . '</td>';
        $o .= '<td>' . $r['client_price'] . '</td>';
        $o .= '<td>' . $r['price'] . '</td>';
        $o .= '<td>' . $r['cost'] . '</td>';
        $o .= '<td>' . number_format($r['markup'], 2) . '%</td>';
        $o .= '</tr>' . "\n";
    }
}
$o .= '</table>';
echo $o;
