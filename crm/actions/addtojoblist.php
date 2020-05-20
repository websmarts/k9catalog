<?php

		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT
		//----------------------------------------------------

		# GET REQUIRED DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		$listingID = (int) @$_REQUEST['listingID'];

		if($businessUser) // if its business user then add job to joblist
		{
			//check for unique business in myList table
			$isBusinessInJobList = isBusinessInJobList($accountID,$listingID); // returns either listID or 0
			if($isBusinessInJobList<1) // if not in the table insert in myLists first
			{
/*				$myListObj = new mylist();
				$myListValues = array('accountID'=>$accountID);
				$listID = $myListObj->insertRecord($myListValues);
*/
				$myJobObj = new myJobs();
				$myJobValues = array('listingID'=>$listingID,'accountID'=>$accountID);
				$myJobObj->insertRecord($myJobValues);
				
				
				$query ='_a=jobslist&msg=2&listingID='.$listingID; // add msg=2 to url - successfully added to job list
			}
						else
			{
				$query ='_a=jobslist&msg=4&listingID='.$listingID; // add msg=4 to url - already job is there in joblist
			}



		}
		else // if its not business uer then redirect to joblist using and display proper msg
		{
			
			$query = "_a=jobslist&msg=1&listingID=".$listingID; // add msg=1 to url
		}
		
		$query .= "&type=lastsearch"; // preserve search params for next search
		httpRedirect($query);
		exit;
?>