<?php
/*Development environment */
	$dbhost = "localhost";
	$dbname = "k9";
	$dbuname = "root";
	$dbpass = "pass";
	
/*	*/	
//Production environment 

	
	$dbhost = "localhost";
	$dbname = "kho34148_k9";
	$dbuname = "kho34148_dbuser";
	$dbpass = "Kh56D6en";	

	
	
// Connecting, selecting database
$link = mysql_connect($dbhost, $dbuname, $dbpass)
    or die('Could not connect: ' . mysql_error());

mysql_select_db($dbname) or die('Could not select database');

// Performing SQL query

if(isSet($_GET['m'])) {
	
	$currentMonth = $_GET['m'];
	$currentYear = $_GET['y'];
} else {
	
	$currentMonth = date('m');
	$currentYear = date('Y');
}



$sql ='SELECT so.order_id,so.modified,c.name,COUNT(soi.order_id) AS itemcount FROM system_orders AS so
JOIN clients AS c ON so.client_id=c.client_id
LEFT JOIN users ON so.reference_id=users.id
LEFT JOIN system_order_items AS soi ON soi.order_id=so.order_id
WHERE 1
AND so.status="basket"
GROUP BY so.order_id';


//echo $sql;
$result = mysql_query($sql) or die('Query failed: ' . mysql_error());

// Printing results in HTML

?>
<style>
#listtable {
	font-family:arial;
	font-size: 10pt;
	}
#listtable th {text-align: left; background:#99cc22;padding:5px}
#listtable td {padding-right:25px;}
.summary {font-family:arial;font-size:120%;color:#339}
.odd {background:#fc0}
.even {background:#ccf; color:#666}
</style>

<?php

echo "<table id=listtable cellspacing=0 cellpadding=0 >\n";
echo "<tr><th>ORDER ID</th><th>CLIENT</th><th>Date</th><th>Line Items</th></tr>";

$rowClass ="odd";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
	
	
	
    echo "\t<tr class=$rowClass >\n";
    echo "<td>". $line['order_id']."</td>";
    echo "<td>".$line['name']."</td>";
    echo "<td>".$line['modified']."</td>";
    echo "<td>".$line['itemcount']."</td>";
   
    echo "\t</tr>\n";
    $rowClass = $rowClass =='odd' ? 'even' : 'odd';
}
echo "</table>\n";

// Free resultset
mysql_free_result($result);

// Closing connection
mysql_close($link);

?>