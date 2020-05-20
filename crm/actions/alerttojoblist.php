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
		$myAlertID = (int) @$_REQUEST['myAlertID'];

		$url = $_SERVER['HTTP_REFERER'];
		$str = $_SERVER['HTTP_REFERER'];
		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$str); // remove msg=N from url - if exists
		
		$pattern = '/&listingID=([0-9])*/';
		$url = preg_replace($pattern,'',$url); // remove listingID=N from url - if exists

		if($businessUser) // if its business user then add job to joblist
		{
				//check if entry exists for this job
				
				
			
				$myJobOBJ = new myJobs();
				$myJobValues = array('listingID'=>$listingID,'accountID'=>$accountID);
				$myJobOBJ->_dao->setFrom($myJobValues);
				$myJobOBJ->_dao->find();
				
				if ($myJobOBJ->_dao->N < 1) 
				{
					
					$myJobOBJ->insertRecord($myJobValues);
				
					# DELETE RECORD FROM MYALERT TABLE
					$alertOBJ =& new myAlert();
					$alertOBJ->deleteRecord($myAlertID);
		
					$pattern = '/_a=jobslist/';
					$url = preg_replace($pattern,"_a=jobslist&msg=4&listingID=$listingID",$url); // add msg=4 to url - already job is there in joblist
				}
		}
		else // if its not business uer then redirect to joblist using and display proper msg
		{
			$pattern = '/_a=jobslist/';
			$url = preg_replace($pattern,"_a=jobslist&msg=1&listingID=$listingID",$url); // add msg=1 to url
		}
		
		httpRedirect($url);
		exit;
?>