<?php
$listingID = (int) @$_REQUEST['listingID'];
		$accountID = (int) @$_REQUEST['accountID'];
		$myAlertID = (int) @$_REQUEST['myAlertID'];
				
		$alertOBJ = &new myAlert;
		$alertOBJ->deleteRecord($myAlertID);

		$query = '_a=myjobalertlist&msg=6'; // add msg=6 to url - business removed from list

		
		httpRedirect($query);
		exit;

?>