<?php
// used for domestic users to get their job list

		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = (int)$dataBank->getVar('s_c_account_id');
		$pg = @$_REQUEST['pg'];

		# GENERATE CONDITION
		$cond = "";
		$cond .= " WHERE listingType='job' AND status='closed' AND accountID=$accountID";
		
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(DISTINCT(listingID)) AS tot FROM listings $cond";	
		//print "<br>$sql<br>";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond ORDER BY createdDate LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;
?>