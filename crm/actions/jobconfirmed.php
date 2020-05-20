<?php
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		
		#++++ GET REQUEST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		if ($listingID < 1) {
			log_error("Error:: trying to do action=jobconfirmed without a valid value for listingID");
		} 
		
		// business categories selected by user for their job
		$catids = $_REQUEST['selected_cats'];

		// If user selected any business categories then we save then to the listings2category table
		if (is_array($catids) && count($catids) >0) { // if we have some cats then do this block
			$list2catObj = &new listing2category();
				foreach ($catids as $catid){					
					$values = array('listingID'=>$listingID,'categoryID'=>$catid);
					$test = $list2catObj->insertRecord( $values  );
				}
		}
			
		$sql = "SELECT * FROM accounts WHERE accountID=$accountID";
		$accountObj = &new account();
		$accountDAO = $accountObj->getRecordsFromQuery($sql);
		$accountDAO->fetch();
		

		#++++ GENERATE CONDITION
		
		#++++ SQL QUERY FOR LIST
		$cond = " WHERE accountID='$accountID' AND listingID='$listingID'";

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();

		// if user has sufficient trust level to approve then set status to APPROVED else 

		if($accountDAO->trustLevel == 'jobs' || $accountDAO->trustLevel=='all')
		{
			$time= time();// use for timestamp aprroval
			$status = 'approved';		
			$listingDAO->approveDate = time();
			
			// TO DO - need to send local alerts for job
			// get the list of businesses that match this listing
			
			send_local_alerts(&$listingDAO);
			
			
		}
		else
		{
			$status = 'pending';
		}
		// Update the record
		$listingDAO->status = $status;
		$listingDAO->update();
			
										
		# REDIRECT TO THANKS PAGE
		if($status=='approved')
			$query= "_a=thanks&t=jobapproved";
		else
			$query= "_a=thanks&t=jobpending";

		httpRedirect($query);
		exit;
?>