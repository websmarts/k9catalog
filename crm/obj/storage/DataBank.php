<?

class DataBank
{
	var $dataArray;
	var $sessionKey;

	function DataBank($sessionKey)
	{
		$this->sessionKey = $sessionKey;
		
		if (!isSet($_SESSION) ) { // start the session if we have not already started it 
			session_start();
		}
	}
	
	/** SET **/
	function setVar ($var, $val)
	{
		$dataArray = $this->retrieveArray();		
		$dataArray[$var] = $val;
		$this->storeArray($dataArray);
	}
	/** UN SET **/
	function unSetVar ($var)
	{
		$dataArray = $this->retrieveArray();		
		unSet($dataArray[$var] );
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
		
		$_SESSION[$this->sessionKey ] = $dataArray;
	}
	
	/** RETRIEVE **/
	function retrieveArray()
	{	
		$sessKey = $this->sessionKey;
		if (isset( $_SESSION[$sessKey] ) ) {
			//echo "SET IN SESSION <br>";
			return $_SESSION[$sessKey];
		} else {
			//echo "NOT SET IN SESSION<BR>";
			return array();
		}
	}
	
	/** REMOVE SESSION KEY DATA **/
	function deleteData()
	{
		$this->sessionKey;
		unset($_SESSION[$this->sessionKey]);
		session_write_close();
	}
	
	/** REMOVE OTHER SESSION KEY DATA **/
	function deleteOtherData($sessKey)
	{
		unset($_SESSION[$sessKey]);
		session_write_close();
	}
	
	function writeClose()
	{
		session_write_close();
	}

}
?>