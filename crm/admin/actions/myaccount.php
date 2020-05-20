<?php

switch ($action)
{
case "myaccount":
		
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
		
		$view = $action;
		
		break;
}
?>