<?php

class DB {
	var $db;

	function DB()
	{
		//Testing environment 
	$dbhost = "localhost";
	$dbname = "k9catalog";
	$dbuname = "root";
	$dbpass = "";
	
	$this->db = ADONewConnection('mysql');
	$result = $this->db->Connect("$dbhost","$dbuname","$dbpass","$dbname");
	
	
	}
	


}

?>