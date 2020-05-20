<?php

require_once(OBJ_PATH . '/storage/DataBank.php');

class Authorize
{
	var $sessionTimeOut;
	var $sessionKey;
	var $currentPage;
	var $idUsedInSession;
	var $redirectBase;

	function Authorize()
	{
	}
	
### ADMIN FUNCITONS

	/**
	 return true if session valid and not timed out
	 or redirect to login page in not valid
	**/
	function userValid()
	{
		if ( ! $this->_isAuthorized() ){
		
				// Store Query String - user will be redirected to this page (query_string)
				// after logging in - [if used in login.php]
				$dataBank =& new DataBank($this->sessionKey);
				$dataBank->setVar('s_c_pending_query',$_SERVER['QUERY_STRING']);
				
				// Not Auth - Redirect to admin login page
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: index.php?_a=login");
				header('Connection: close');
				exit;	
		}
	}
	// return true if session valid and not timed out return false if not
	
	function isLoggedIn()
	{
		if ( $this->_isAuthorized() ){	
			return true;
		} else return false;
	
	}
	
	function _isAuthorized()
	{
			// Session and timeout
			$dataBank =& new DataBank($this->sessionKey);

			// does id exists and time not greater than expire time
			if ( ($dataBank->getVar($this->idUsedInSession) != '') && 
							( (time() - $dataBank->getVar('s_a_start_time'))   < $this->sessionTimeOut )  )  { 
				
				// update the session time after each click in site
				$dataBank->setVar('s_a_start_time', time());
				return true;
				 
			} else {
				// unset session
				$dataBank->deleteData();
				return false;		
			}
			
	} 
	function userRole($role)
	{
			// Session and timeout
			$dataBank =& new DataBank($this->sessionKey);

			// if role exists return status true else false
			$pos = strpos($role,$dataBank->getVar('s_a_account_type'));

			if ($pos === false) { 
				return false;
			} else {
				return true;		
			}
	} 

### CLIENT FUNCITONS

	function isClientLoggedIn()
	{
		if ( $this->_isAuthorizedClient() ){	
			return true;
		} else return false;
	
	}
	function clientValid()
	{
		if ( ! $this->_isAuthorizedClient() ){
		
				// Store Query String - user will be redirected to this page (query_string)
				// after logging in - [if used in login.php]
				
			

				// Not Auth - Redirect to admin login page
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: index.php?_a=login");
				header('Connection: close');
				exit;	
		}
	}
	function _isAuthorizedClient()
	{
			// Session and timeout
			$dataBank =& new DataBank($this->sessionKey);

			// does id exists and time not greater than expire time
			if ( ($dataBank->getVar($this->idUsedInSession) != '') && 
							( (time() - $dataBank->getVar('s_c_start_time'))   < $this->sessionTimeOut )  )  { 
				
				// update the session time after each click in site
				$dataBank->setVar('s_c_start_time', time());
				return true;
				 
			} else {
				// unset session
				// $dataBank->deleteData();
				return false;		
			}
			
	}
	/** VALID BUSINESS USER **/
	function &validBusinessUser() {
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$userType = $dataBank->getVar('s_c_account_type');
		$userType = substr($userType,0,12);
		if ($userType == 'businessuser')
			return true;
		else 
			return false;
	}
}
?>