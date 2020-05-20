<?php



require_once(OBJ_PATH . '/authorize/Authorize.php');



class AuthorizeAdmin extends Authorize

{



	function AuthorizeAdmin($currentPage='', $sessKey=SESSION_KEY_ADMIN_FORMS, $sessTimeOut=SESSION_TIMEOUT_ADMIN)

	{

		$this->idUsedInSession = 's_a_account_id';

		//$this->redirectBase = REDIRECT_ADMIN_BASE;

		$this->currentPage = $currentPage;

		$this->sessionKey = $sessKey;

		$this->sessionTimeOut = $sessTimeOut;

	}

}

?>