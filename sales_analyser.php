<?php

error_reporting(0);



function pr($a)

{

    echo '<pre>';

    print_r($a);

    echo '</pre>';

}



$time = microtime();

$time = explode(' ', $time);

$time = $time[1] + $time[0];

$start_time = $time;

if ($_SERVER['HTTP_HOST'] == 'k9homes.com.au.test') {

    /*Development environment */

    $dbhost = "localhost";

    $dbname = "k9";

    $dbuname = "root";

    $dbpass = "";



} else {

    //Production environment



    $dbhost = "localhost";

    $dbname = "k9homes_db";

    $dbuname = "k9homes_dbuser";

    $dbpass = "Kh56D6en";



}



// Connecting, selecting database

$mysqli = mysqli_connect($dbhost, $dbuname, $dbpass, $dbname)

or die('Could not connect to database');



// Performing SQL query



$startMonth = $_GET['m'];

$startYear = $_GET['y'];

$endMonth = $_GET['m2'];

$endYear = $_GET['y2'];

$level = $_GET['level'];

$user = $_GET['user'];



//$end = (int) $endYear.$endMonth;

//$start = (int) $startYear.$startMonth;

//echo $start .' to ' .$end .'<br />';



$startDatetime = strtotime($startYear . '-' . $startMonth);

$endDatetime = strtotime($endYear . '-' . $endMonth);



if ($endDatetime < $startDatetime) {

    echo '<h3>End date is before start date!!</h3><br />';

}



if ($startDatetime > 0 && $endDatetime > 0) {

    $sql = "select type.name,sum(products.cost * system_order_items.qty )   as cost,sum(system_order_items.qty * system_order_items.price) as total,(sum(system_order_items.qty * system_order_items.price)-sum(products.cost * system_order_items.qty )) as gp ";

    $sql .= "from system_orders ";



    $sql .= "join clients on clients.client_id = system_orders.client_id ";

    $sql .= "join system_order_items on system_orders.order_id = system_order_items.order_id ";

    $sql .= "join products on system_order_items.product_code = products.product_code ";

    $sql .= "join type on products.typeid = type.typeid ";

    

    //$sql .=    "where DATE_FORMAT(system_orders.modified,'%Y%m') >= '".$startYear.$startMonth."' "   ;

    //$sql .=    "and DATE_FORMAT(system_orders.modified,'%Y%m') <= '".$endYear.$endMonth."' "   ;

    $sql .= ' Where system_orders.modified >= FROM_UNIXTIME(' . $startDatetime . ') ';

    $sql .= ' and system_orders.modified <= FROM_UNIXTIME(' . $endDatetime . ') ';

    if ($level != 'ALL') {

        $sql .= ' and clients.level like "' . $level . '%" ';

    }



    // Filter by client if client_id is set in request

    if (isset($_GET['client_id']) && $_GET['client_id'] > 0) {

        $sql .= ' and clients.client_id =' . $_GET['client_id'] . ' ';

    }



    if ($user != 'ALL') {

        $sql .= ' and system_orders.reference_id =' . $user . ' ';

    }



    $sql .= "Group By products.typeid order by  gp desc";



    //echo $sql;

    $result = mysqli_query($mysqli, $sql);

}

// SQL to get clients list

$sql2 = 'select client_id, `name` from clients order by `name` asc';

$clients = mysqli_query($mysqli, $sql2) or die('Clients Query failed: ');



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

.topitem {background:#fc0}

.bottomitem {background:#ccf; color:#666}

</style>





<form method="get"  >

from start of: <select name="m">



	<option value="01" <?=$_GET['m'] == "01" ? "selected" : ''?>>January</option>

	<option value="02" <?=$_GET['m'] == "02" ? "selected" : ''?>>February</option>

	<option value="03" <?=$_GET['m'] == "03" ? "selected" : ''?>>March</option>

	<option value="04" <?=$_GET['m'] == "04" ? "selected" : ''?>>April</option>

	<option value="05" <?=$_GET['m'] == "05" ? "selected" : ''?>>May</option>

	<option value="06" <?=$_GET['m'] == "06" ? "selected" : ''?>>June</option>

	<option value="07" <?=$_GET['m'] == "07" ? "selected" : ''?>>July</option>

	<option value="08" <?=$_GET['m'] == "08" ? "selected" : ''?>>August</option>

	<option value="09" <?=$_GET['m'] == "09" ? "selected" : ''?>>September</option>

	<option value="10" <?=$_GET['m'] == "10" ? "selected" : ''?>>October</option>

	<option value="11" <?=$_GET['m'] == "11" ? "selected" : ''?>>November</option>

	<option value="12" <?=$_GET['m'] == "12" ? "selected" : ''?>>December</option>



</select>



<select name="y">

<?php

$lastYear = date('Y') ;

$currentMonth = date('m');
if($currentMonth == 12){
    $lastYear++;
}

$firstYear = 2006;



for ($n = $lastYear; $n >= $firstYear; $n--) {

    $selected = $_GET['y'] == $n ? "selected" : '';

    echo '<option value="' . $n . '"' . $selected . '>' . $n . '</option>' . "\r\n";

}

?>





</select>

 to start of

<select name="m2">



    <option value="01" <?=$_GET['m2'] == "01" ? "selected" : ''?>>January</option>

    <option value="02" <?=$_GET['m2'] == "02" ? "selected" : ''?>>February</option>

    <option value="03" <?=$_GET['m2'] == "03" ? "selected" : ''?>>March</option>

    <option value="04" <?=$_GET['m2'] == "04" ? "selected" : ''?>>April</option>

    <option value="05" <?=$_GET['m2'] == "05" ? "selected" : ''?>>May</option>

    <option value="06" <?=$_GET['m2'] == "06" ? "selected" : ''?>>June</option>

    <option value="07" <?=$_GET['m2'] == "07" ? "selected" : ''?>>July</option>

    <option value="08" <?=$_GET['m2'] == "08" ? "selected" : ''?>>August</option>

    <option value="09" <?=$_GET['m2'] == "09" ? "selected" : ''?>>September</option>

    <option value="10" <?=$_GET['m2'] == "10" ? "selected" : ''?>>October</option>

    <option value="11" <?=$_GET['m2'] == "11" ? "selected" : ''?>>November</option>

    <option value="12" <?=$_GET['m2'] == "12" ? "selected" : ''?>>December</option>



</select>

<select name="y2">

<?php

//$lastYear = date('Y');

$firstYear = 2006;



for ($n = $lastYear; $n >= $firstYear; $n--) {

    $selected = $_GET['y2'] == $n ? "selected" : '';

    echo '<option value="' . $n . '"' . $selected . '>' . $n . '</option>' . "\r\n";

}

?>

</select>

Level

<select name="level"  >

    <option value="ALL" <?=$_GET['level'] == "ALL" ? "selected" : ''?>>ALL</option>

    <option value="A" <?=$_GET['level'] == "A" ? "selected" : ''?>>A</option>

    <option value="B" <?=$_GET['level'] == "B" ? "selected" : ''?>>B</option>

    <option value="C" <?=$_GET['level'] == "C" ? "selected" : ''?>>C</option>

    <option value="D" <?=$_GET['level'] == "D" ? "selected" : ''?>>D</option>



</select>





Who

<select name="user"  >

   <option value="ALL" <?=$_GET['user'] == "ALL" ? "selected" : ''?>>ALL</option>

   <option value="6" <?=$_GET['user'] == "6" ? "selected" : ''?>>Darren</option>

   <option value="10" <?=$_GET['user'] == "10" ? "selected" : ''?>>Kerry</option>
   <option value="13" <?=$_GET['user'] == "13" ? "selected" : ''?>>Trudy</option>
   <option value="13" <?=$_GET['user'] == "9" ? "selected" : ''?>>Cathy</option>


   <!-- <option value="11" <?=$_GET['user'] == "11" ? "selected" : ''?>>Michael</option>

   <option value="9" <?=$_GET['user'] == "9" ? "selected" : ''?>>Jason</option> -->



</select>

Client filter

<select name="client_id">

    <option value="0">Select a client...</option>

<?php

while ($client = mysqli_fetch_assoc($clients)) {

    $selected = $_GET['client_id'] == $client['client_id'] ? ' selected="selected" ' : '';

    echo '<option ' . $selected . ' value="' . $client["client_id"] . '">' . $client['name'] . '</option>' . "\n";



}

?>

</select>



<input type="submit" name="b" value="go">

</form>

<?php



echo "<table id=listtable cellspacing=0 cellpadding=0 >\n";

echo "<tr><th></th><th>ProductType</th><th>Sales</th><th>Cost</th><th>GP($)</th><th>GP(%)</th><th>Sales(%)</th><th>Cum Sales(%)</th></tr>";



$total = 0;

$tcost = 0;

$linesCumTotal = 0;

while ($line = mysqli_fetch_assoc($result)) {



    $lines[] = $line;

    $total += $line['total'];

    $tcost += $line['cost'];



}



echo "<p class=summary >";

echo "Total sales = $" . ($total / 100) . " <br>";



if ($total < 1) {

    exit;

}

echo "Cost Of Goods = $" . ($tcost / 100) . " <br>";

echo "GP = " . number_format(($total - $tcost) * 100 / $total, 2) . "% <br>";

echo "</p>";

$n = 1;

$cumGPPercent = 0;

foreach ($lines as $line) {

    if ($cumGPPercent <= 0.8) {

        $rowClass = "topitem";

    } else {

        $rowClass = "bottomitem";

    }



    echo "\t<tr class=$rowClass >\n";

    echo "<td>" . $n++ . "</td>";



    //echo "<td>" . $line['catname'] . "</td>";

    echo "<td>" . $line['name'] . "</td>";

    echo "<td>" . number_format(($line['total'] / 100), 0) . "</td>";

    echo "<td>" . number_format(($line['cost'] / 100), 0) . "</td>";

    echo "<td>" . number_format(($line['gp'] / 100), 2) . "</td>";

    echo "<td>" . number_format(100 * ($line['total'] - $line['cost']) / $line['total'], 2) . "%</td>";

    $linesCumTotal += $line['total'];

    $cumGPPercent = $linesCumTotal / $total;

    echo "<td>" . number_format(100 * $line['total'] / $total, 2) . "%</td>";



    echo "<td>" . number_format(100 * $cumGPPercent, 2) . "%</td>";

    echo "\t</tr>\n";

}

echo "</table>\n";



// Free resultset

mysqli_free_result($result);



// Closing connection

mysql_close($link);



$time = microtime();

$time = explode(' ', $time);

$time = $time[1] + $time[0];

$finish = $time;

$total_time = round(($finish - $start_time), 4);

echo 'Page generated in ' . $total_time . ' seconds.';

?>