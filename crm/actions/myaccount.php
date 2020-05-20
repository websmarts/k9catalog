<?php

switch ($action)
{
	case 'myaccount':

		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------

		$view=$action;
		
		break;

	case 'home':
		$view=$action;
		
		break;

}
?>