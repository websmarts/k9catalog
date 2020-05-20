<?php

class DataBaseBank
{
	var $dataArray;
	var $sessionKey;

	function DataBank($sessionKey)
	{
		$this->sessionKey = $sessionKey;
		session_start();
		//session_write_close();
	}
	
	/** SET **/
	function setVar ($var, $val)
	{
		$dataArray = $this->retrieveArray();		
		$dataArray[$var] = $val;
		$this->storeArray($dataArray);
	}
	
	/** GET **/
	function getVar($var)
	{
		$dataArray = $this->retrieveArray();
		if ( isset($dataArray) && isset($dataArray[$var]) ) {
			//echo "SET1<br>";
			return $dataArray[$var];
		} else {
			//echo "NOT SET1<br>";
			return '';
		}
	}
	
	
	// SESSION TOOLS - These can change to store in other places (cookies, db,etc)
	/** STORE **/
	function storeArray($dataArray)
	{
		$_SESSION[$this->sessionKey] = $dataArray;
	}
	
	/** RETRIEVE **/
	function retrieveArray()
	{	
		$sessKey = $this->sessionKey;
		if (isset( $_SESSION[$sessKey] ) ) {
			//echo "SET2 <br>";
			return $_SESSION[$sessKey];
		} else {
			//echo "NOT SET2<BR>";
			return array();
		}
	}
	
	/** REMOVE DATA **/
	function deleteData()
	{
		unset($_SESSION[$this->sessionKey]);
	}
	
	function writeClose()
	{
		session_write_close();
	}

}
?>