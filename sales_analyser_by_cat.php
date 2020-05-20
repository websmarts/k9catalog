<?php
    error_reporting(0);

    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $start_time = $time;

    /*Development environment */
    $dbhost = "localhost";
    $dbname = "k9";
    $dbuname = "root";
    $dbpass = "pass";

    /*		*/
    //Production environment 

  
    	
    $dbhost = "localhost";
    $dbname = "k9homes_db";
    $dbuname = "k9homes_dbuser";
    $dbpass = "Kh56D6en";

  

    // Connecting, selecting database
    $link = mysql_connect($dbhost, $dbuname, $dbpass)
    or die('Could not connect: ' . mysql_error());

    mysql_select_db($dbname) or die('Could not select database');

    // Performing SQL query

    $startMonth = $_GET['m'];
    $startYear = $_GET['y'];
    $endMonth = $_GET['m2'];
    $endYear = $_GET['y2'];
    $level = $_GET['level'];

    $end = (int) $endYear.$endMonth;
    $start = (int) $startYear.$startMonth;
    //echo $start .' to ' .$end .'<br />';
    $startDatetime=strtotime($startYear.'-'.$startMonth);
    $endDatetime=strtotime($endYear.'-'.$endMonth);

    //echo $startDatetime.'<br />';
    //echo $endDatetime.'<br />';



    if( $endDatetime < $startDatetime){
        echo '<h3>End date is before start date!!</h3><br />';
    } 

    if($startDatetime > 0 && $endDatetime > 0 ){

        $sql = "select category.name as catname,type.name,sum(products.cost * system_order_items.qty )   as cost,sum(system_order_items.qty * system_order_items.price) as total,(sum(system_order_items.qty * system_order_items.price)-sum(products.cost * system_order_items.qty )) as gp "; 
        $sql .=	"from system_orders " ;
        $sql .= "join clients on clients.client_id = system_orders.client_id ";
        $sql .=	"join system_order_items on system_orders.order_id = system_order_items.order_id " ;
        $sql .= "join products on system_order_items.product_code = products.product_code " ;
        $sql .= "join type on products.typeid = type.typeid ";
        $sql .= "join type_category on type_category.typeid=products.typeid ";
        $sql .= "join category on category.id = type_category.catid ";
        //$sql .=	"where DATE_FORMAT(system_orders.modified,'%Y%m') >= '".$startYear.$startMonth."' "   ;
        $sql .= 'Where system_orders.modified >= FROM_UNIXTIME('.$startDatetime.') ';
        //$sql .=	"and DATE_FORMAT(system_orders.modified,'%Y%m') <= '".$endYear.$endMonth."' "   ;
        $sql .=   'and system_orders.modified <= FROM_UNIXTIME('.$endDatetime.') ';

        if ($level != 'ALL'){
            $sql .= ' and clients.level like "'.$level.'%" ';
        }

        $sql .=	"Group By category.id order by gp desc" ; 


        //echo $sql;
        $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    }
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
    From start of:<select name="m">

        <option value="01" <?=$_GET['m'] == "01"?"selected":''?>>January</option>
        <option value="02" <?=$_GET['m'] == "02"?"selected":''?>>February</option>
        <option value="03" <?=$_GET['m'] == "03"?"selected":''?>>March</option>
        <option value="04" <?=$_GET['m'] == "04"?"selected":''?>>April</option>
        <option value="05" <?=$_GET['m'] == "05"?"selected":''?>>May</option>
        <option value="06" <?=$_GET['m'] == "06"?"selected":''?>>June</option>
        <option value="07" <?=$_GET['m'] == "07"?"selected":''?>>July</option>
        <option value="08" <?=$_GET['m'] == "08"?"selected":''?>>August</option>
        <option value="09" <?=$_GET['m'] == "09"?"selected":''?>>September</option>
        <option value="10" <?=$_GET['m'] == "10"?"selected":''?>>October</option>
        <option value="11" <?=$_GET['m'] == "11"?"selected":''?>>November</option>
        <option value="12" <?=$_GET['m'] == "12"?"selected":''?>>December</option>

    </select>
    <select name="y">
        <option value="2015" <?=$_GET['y'] == "2015"?"selected":''?>>2015</option>
        <option value="2014" <?=$_GET['y'] == "2014"?"selected":''?>>2014</option>
        <option value="2013" <?=$_GET['y'] == "2013"?"selected":''?>>2013</option>
        <option value="2012" <?=$_GET['y'] == "2012"?"selected":''?>>2012</option>
        <option value="2011" <?=$_GET['y'] == "2011"?"selected":''?>>2011</option>
        <option value="2010" <?=$_GET['y'] == "2010"?"selected":''?>>2010</option>
        <option value="2009" <?=$_GET['y'] == "2009"?"selected":''?>>2009</option>
        <option value="2008" <?=$_GET['y'] == "2008"?"selected":''?>>2008</option>
        <option value="2007" <?=$_GET['y'] == "2007"?"selected":''?>>2007</option>
        <option value="2006" <?=$_GET['y'] == "2006"?"selected":''?>>2006</option>
    </select>
    to start of: 
    <select name="m2">

        <option value="01" <?=$_GET['m2'] == "01"?"selected":''?>>January</option>
        <option value="02" <?=$_GET['m2'] == "02"?"selected":''?>>February</option>
        <option value="03" <?=$_GET['m2'] == "03"?"selected":''?>>March</option>
        <option value="04" <?=$_GET['m2'] == "04"?"selected":''?>>April</option>
        <option value="05" <?=$_GET['m2'] == "05"?"selected":''?>>May</option>
        <option value="06" <?=$_GET['m2'] == "06"?"selected":''?>>June</option>
        <option value="07" <?=$_GET['m2'] == "07"?"selected":''?>>July</option>
        <option value="08" <?=$_GET['m2'] == "08"?"selected":''?>>August</option>
        <option value="09" <?=$_GET['m2'] == "09"?"selected":''?>>September</option>
        <option value="10" <?=$_GET['m2'] == "10"?"selected":''?>>October</option>
        <option value="11" <?=$_GET['m2'] == "11"?"selected":''?>>November</option>
        <option value="12" <?=$_GET['m2'] == "12"?"selected":''?>>December</option>

    </select>
    <select name="y2">
        <option value="2015" <?=$_GET['y2'] == "2015"?"selected":''?>>2015</option>
        <option value="2014" <?=$_GET['y2'] == "2014"?"selected":''?>>2014</option>
        <option value="2013" <?=$_GET['y2'] == "2013"?"selected":''?>>2013</option>
        <option value="2012" <?=$_GET['y2'] == "2012"?"selected":''?>>2012</option>
        <option value="2011" <?=$_GET['y2'] == "2011"?"selected":''?>>2011</option>
        <option value="2010" <?=$_GET['y2'] == "2010"?"selected":''?>>2010</option>
        <option value="2009" <?=$_GET['y2'] == "2009"?"selected":''?>>2009</option>
        <option value="2008" <?=$_GET['y2'] == "2008"?"selected":''?>>2008</option>
        <option value="2007" <?=$_GET['y2'] == "2007"?"selected":''?>>2007</option>
        <option value="2006" <?=$_GET['y2'] == "2006"?"selected":''?>>2006</option>
    </select>
    Level 
    <select name="level"  >
        <option value="ALL" <?=$_GET['level'] == "ALL"?"selected":''?>>ALL</option>
        <option value="A" <?=$_GET['level'] == "A"?"selected":''?>>A</option>
        <option value="B" <?=$_GET['level'] == "B"?"selected":''?>>B</option>
        <option value="C" <?=$_GET['level'] == "C"?"selected":''?>>C</option>
        <option value="D" <?=$_GET['level'] == "D"?"selected":''?>>D</option>

    </select>


    <input type="submit" name="b" value="go">
</form>
<p><a href="sales_analyser.php">Std Sales analyser</a></p>
<?php

    echo "<table id=listtable cellspacing=0 cellpadding=0 >\n";
    echo "<tr><th>Category</th><th>Sales</th><th>Cost</th><th>PF</th><th>GP($)</th><th>GP(%)</th><th>GP Contrib(%)</th><th>Cum gp contrib</th></tr>";


    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

        $lines[] = $line;	
        $total += $line['total'];
        $tcost += $line['cost'];

    }


    echo "<p class=summary >";
    echo "Total sales = $".($total/100)." <br>";

    if ($total < 1) { 
        exit;
    }
    echo "Cost Of Goods = $".($tcost/100)." <br>";
    echo "GP = ".number_format(100*($total - $tcost)/$total,2)."% <br>";
    echo "</p>";
    $n = 1;
    $cumGPPercent=0;
    foreach($lines as $line) {
        if($cumGPPercent <= 0.8) {
            $rowClass= "topitem";
        } else {
            $rowClass = "bottomitem";
        }

        echo "\t<tr class=$rowClass >\n";
        echo "<td>".$n++." ". $line['catname']."</td>";
        echo "<td>".number_format(($line['total']/100),0)."</td>";
        echo "<td>".number_format(($line['cost']/100),0)."</td>";
        echo "<td>".number_format(($line['total']/$line['cost']),2)."</td>";
        echo "<td>".number_format(($line['gp']/100),2)."</td>";
        echo "<td>".number_format(100 * ($line['total']-$line['cost'])/$line['total'],2)."%</td>";
        $linesCumTotal += $line['total'];
        $cumGPPercent = $linesCumTotal/$total;
        echo "<td>".number_format(100 * $line['total']/$total,2)."%</td>";

        echo "<td>".number_format(100 * $cumGPPercent,2)."%</td>"; 
        echo "\t</tr>\n";
    }
    echo "</table>\n";

    // Free resultset
    mysql_free_result($result);

    // Closing connection
    mysql_close($link);
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start_time), 4);
    echo 'Page generated in '.$total_time.' seconds.';

?>