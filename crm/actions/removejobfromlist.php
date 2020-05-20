<?php

# GET DATA
		$listingID = (int) @$_REQUEST['listingID'];
		$accountID = (int) @$_REQUEST['accountID'];
		
		$list2mylistObj = new listings2mylist();
		$sql = "DELETE FROM myJobs WHERE listingID=$listingID and accountID=$accountID"; 
		$list2mylistDAO = $list2mylistObj->executeQuery($sql);
		
		// now set listingResponse-status = closed so we dont se this job in future search results
		$sql = "UPDATE listingResponses set status='closed' WHERE listingID=$listingID and accountID=$accountID"; 
		$list2mylistDAO = $list2mylistObj->executeQuery($sql);
		
		
		$query = '_a=myjoblist&msg=6'; // add msg=6 to url - business removed from list

		httpRedirect($query);
		exit;

?>