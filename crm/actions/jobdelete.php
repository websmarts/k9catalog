<?php
		#++++ GET POST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		
		#++++ GENERATE CONDITION
		$cond = " WHERE accountID='$accountID' AND listingID='$listingID'";

		#++++ SQL QUERY FOR DELETE
		$sql = "DELETE FROM listings $cond";

		$listingObj = &new listing();
		$listingDAO = $listingObj->executeQuery($sql);

		# REDIRECT TO THANKS PAGE
		$url= "index.php?_a=thanks&t=jobdelete";

		httpRedirect($url);
		exit;
 ?>