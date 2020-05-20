<?php

require_once(OBJ_PATH . '/authorize/Authorize.php');

class AuthorizeClient extends Authorize
{

	function AuthorizeClient($currentPage='', $sessKey=SESSION_KEY_CLIENT_FORMS, $sessTimeOut=SESSION_TIMEOUT_CLIENT)
	{
		$this->idUsedInSession = 's_c_account_id';
		$this->currentPage = $currentPage;
		$this->sessionKey = $sessKey;
		$this->sessionTimeOut = $sessTimeOut;
	}


	function checkAuth($action){
				$dataBank =& new DataBank($this->sessionKey);
				$accountType = $dataBank->getVar('s_c_account_type');
				$accountSubType = strtoupper(substr($accountType,0,3));
				
		switch ($action) {
			case 'jobadd': // must be DOM or BUS user to add a JOB
				
				if ($accountSubType == 'DOM' or $accountSubType == 'BUS'){
					return true;
				} else {
					// Not currently authorized so go to login
					$dataBank->setVar('s_c_pending_query',$_SERVER['QUERY_STRING']);
					httpRedirect('_a=login');
				}
			break;
			
			case 'localadseecontact':	// must be logged in as BUS or DOM to see contact details for localads
				if ($accountSubType == 'DOM' or $accountSubType == 'BUS'){
					return true;
				} else {
					// Not currently authorized so go to login
					// set the pending query to the one before this one i.e. the one that caused the listing display
					$dataBank->setVar('s_c_pending_query',$dataBank->getVar('lastReqQuery'));
					httpRedirect('_a=login');
				}	
			
			default:
				log_error("checking using AuthorizeClient::checkAuth -  no trap set for action =$action -");
		}
	}
}
?>