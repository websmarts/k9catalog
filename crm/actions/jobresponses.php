<?php

$pg =  (int)$_REQUEST['pg'];
		$listingID= (int) @$_REQUEST['listingID'];

		# GENERATE CONDITION
		$cond  = " WHERE listingResponses.listingID=$listingID";
		$cond .= " AND listingResponses.accountID=businesses.accountID";

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(*) AS tot FROM listingResponses, businesses $cond";
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
		$sql = "SELECT businesses.businessName, listingResponses.responseDateTime,listingResponses.message,listingResponses.status  FROM listingResponses, businesses $cond ORDER BY responseDateTime LIMIT $start,$gap";
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