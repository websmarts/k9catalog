<?php

# GET DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		$listingID= (int) @$_REQUEST['listingID'];
		$pg =  (int)$_REQUEST['pg'];
		$msg = (int) @$_REQUEST['msg'];

		# GENERATE CONDITION
		$cond  = '';
		$cond  = " WHERE listingResponses.accountID=businesses.accountID ";
		$cond .= " AND listingResponses.listingID=listings.listingID ";
		$cond .= " AND listingResponses.listingID=$listingID AND listings.accountID=$accountID ";

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(*) AS tot FROM listingResponses, businesses, listings $cond";
		//print "<br>$sql<br>";
		
		$listingObj = new listing();
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
		$pagingInfo = paging2($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT businesses.businessName, listingResponses.listingResponseID, listingResponses.responseDateTime,listingResponses.message,listingResponses.status  FROM listingResponses, businesses, listings $cond ORDER BY responseDateTime LIMIT $start,$gap";
		//print "<br>$sql<br>";

		$responseObj = &new listing();
		$responseDAO = $responseObj->getRecordsFromQuery($sql);

		$sql = "SELECT * FROM listings WHERE listingID=$listingID";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();

		$view = $action;

?>