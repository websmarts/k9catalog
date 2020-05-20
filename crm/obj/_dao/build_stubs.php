<?php 
/**
/ This script looks at _db_dataobject.ini and gets	
	all the database information (username, password, host, table)
	and then builds the DataObjects classes and stores them
	in this same folder.
	
	To see these files (if you wish), run this script and
	then get a copy from the server locally to view.
	
	The classes constructed create Objects for each table
	so calls to the database (insert, update, delete) can
	be performed as if they are objects. The advantage of
	this is that you no longer have to worry about the db
	connection, or use as much sql.

**/ 
include "../../inc/configure.php";
//ini_set('include_path', '.;c:\Program Files\Apache Group\Apache\htdocs\olrdev\pear');
//define ('OBJ_PATH','c:\Program Files\Apache Group\Apache\htdocs\olrdev\obj');

//$_SERVER['argv'][1] = '/usr/home/subdomains/lenders/www/_dao/__ini/db_dataobject.ini'; 
$_SERVER['argv'][1] = OBJ_PATH.'/_dao/__ini/db_dataobject.ini'; 
require_once 'DB/DataObject/createTables.php';
?>