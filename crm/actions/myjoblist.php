<?php

	// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = (int)$dataBank->getVar('s_c_account_id');
		$msg = (int) @$_REQUEST['msg'];
		$pg = (int) @$_REQUEST['pg'];

		# GENERATE CONDITION
		$cond  = "";
		$cond .= " where listings.listingID=myJobs.listingID";
		$cond .= " AND myJobs.accountID=$accountID";
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(*) AS tot FROM listings, myJobs $cond";	
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
		$sql = "SELECT * FROM listings, myJobs $cond ORDER BY listings.createdDate LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;

?>