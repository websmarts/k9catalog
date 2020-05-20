<?php
#++++ GET POST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
	
		#++++ GENERATE CONDITION
		$cond = " WHERE accountID={$accountID} AND listingID={$listingID}";

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond";
		//echo "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->N;
		
		//echo dumper($listingDAO);exit;
		
		#++++ Find Top Business Categories that Match This Listing Placemeny
		$topCategories = make_array_rank($listingDAO);
		

		
		$view = $action;
 ?>