<?php



/*Development environment */

 if(APP_MODE == 'development') {

	$dbhost = "localhost";

	$dbname = "k9";

	$dbuname = "root";

	$dbpass = "";

} else {

	//www.k9homes.com.au environment

    $dbhost = "127.0.0.1";

    $dbname = "k9homes_db";

    $dbuname = "k9homes_dbuser";

    $dbpass = "Kh56D6en";

}



  

    

	

	$db = ADONewConnection('mysqli');

	if (!$db->Connect("$dbhost","$dbuname","$dbpass","$dbname")) {

		exit ("Cannot connect to the database");

	}



	

	



	

function do_query($query) {

	global $db;

	global $db_debug;

	$ret = false;

		// set $db->debug for debug info

        if($db_debug){

            $db->debug = true;

        } else {

            $db->debug = false;

        }

		

        

		//$db->debug = true;

		// if select query then get data

		//echo $query;

		if (preg_match("/^[\s]*select[\s]+/im",$query) ){

			

			// return assoc array

			 

			$ret = $db->GetArray($query);

			

			

		}

		

		if (preg_match("/^[ ]*insert /im",$query) ){

			

			$res = $db->Execute($query);

			

			$ret =  $db->Insert_ID();			

		}	

		

		if (preg_match("/^[ ]*delete /im",$query) ){

			

			$ret = $db->Execute($query);		

			

		}	

		if (preg_match("/^[ ]*update /im",$query) ){

			

			$ret = $db->Execute($query);

						

		}	

		

			

		return $ret;		

}



function query_to_array($res, $key='id'){

    $ret = array();

    if(is_array($res) && count($res) > 0){

        foreach ($res as $row){

            $ret[$row[$key]]=$row;

        }

    }

    return $ret;

}



?>