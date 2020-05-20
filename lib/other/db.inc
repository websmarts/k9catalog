<?php


/*
	//Testing environment 
	$host = "localhost";
	$db = "k9catalog";
	$db_user = "root";
	$db_user_passwd = "";
*/
	//ausesolu environment 
	$host = "localhost";
	$db = "ausesolu_k9catalog";
	$db_user = "ausesolu_k9";
	$db_user_passwd = "k9passby";
	
	// Make the database connection because we are going to need it
	$mysql_link = mysql_connect($host,$db_user,$db_user_passwd);
	
	// Connect to the specific database
	mysql_select_db($db,$mysql_link);
	
	
function do_query($query) {
	global $db_debug;
		if ($db_debug) {
			echo $query."<br>\n";	
		}	
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		// if select query then get data
		//echo $query;
		if (preg_match("/^select /i",$query) ){
			if (mysql_num_rows($result) !== 0) {
	
				while ($row = mysql_fetch_assoc($result)) {
				    $rows[] = $row;
				}
			}
				
			$ret =  $rows;
		}
		
		if (preg_match("/^insert /i",$query) && $result ){
			$ret =  mysql_insert_id();			
		}		
		return $ret;		
}

?>