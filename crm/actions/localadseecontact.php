<?php

		// This is just a TRAP to cause a login for anyone who tries to see contact details for a local ad
		
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		//$auth->clientValid();// redirects to login if not
		$auth->checkAuth($action);
		//----------------------------------------------------
		
		// Should never get here as Auth should redirect to login which if successful will redirect to the request before the one that generated the localadseecontact action - probably localadslist

		
		// return to home
		httpRedirect('_a=localadslist');
?>