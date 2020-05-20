<?php

//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT
		//----------------------------------------------------

		# GET REQUIRED DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		$accountType = $dataBank->getVar('s_c_account_type');
		$listingID = (int) @$_REQUEST['listingID'];

		$from = trim(@$_REQUEST['from']);

		if($from=='MJL')
			$pageurl = "myjoblist";
		else
			$pageurl = "jobslist";

		$currURL = @$_SERVER['HTTP_REFERER'];
		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$currURL); // remove msg=N from url - if exists

		//remove duplicate listingID
		$pattern = '/&listingID=([0-9])*/';
		$url = preg_replace($pattern,'',$url); // remove listingID=N from url - if exists

		if($businessUser) // if its business user then add job to joblist
		{
			//if job is already in service area then dont put postcode in table again
			$isJobInServiceArea = isJobInServiceArea($accountID,$listingID);
			
			if($isJobInServiceArea)
			{
				// application logic should not let this happen- so log error
				log_error("trying to add a duplicate postcode to service area");
			}

			# GET POSTCODE LIMIT
			$postcodeLimit = getPostcodeLimit($accountID,$accountType);
			
			
			
			
			# GET NO OF POSTCODES FOR AN ACCOUNT
			$sql = "SELECT * FROM account2postcode WHERE accountID=$accountID";
			//print "<br>$sql<br>";
			$listingObj = &new listing();
			$listingDAO = $listingObj->getRecordsFromQuery($sql);
			$totalPostcodes = $listingDAO->N;
			
			

			# ADD NEW POST CODE IN SERVICE AREA
			if($totalPostcodes<$postcodeLimit)
			{
				$sql = "SELECT * FROM listings WHERE listingID=$listingID";	
				//print "<br>$sql<br>";
				$listingDAO = $listingObj->getRecordsFromQuery($sql);
				$listingDAO->fetch();
				$postcode = $listingDAO->postcode;

				$sql = "INSERT INTO account2postcode (accountID, postcode) VALUES ($accountID,$postcode)";	
				
				$listingDAO = $listingObj->executeQuery($sql);

				$pattern = "/_a=$pageurl/";
				$url = preg_replace($pattern,"_a=$pageurl&msg=8&listingID=$listingID",$url); // add msg=8 to url - added postcode to service area
			}
			# RETURN TO LAST PAGE WITH ERROR - POSTCODE LIMIT EXCEEDED
			else
			{
				$pattern = "/_a=$pageurl/";
				$url = preg_replace($pattern,"_a=$pageurl&msg=9&listingID=$listingID",$url); // add msg=9 to url - postcode limit exceeded
			}
		}
		else // if its not business user then redirect to joblist using and display proper msg
		{
			$pattern = "/_a=$pageurl/";
			$url = preg_replace($pattern,"_a=$pageurl&msg=1&listingID=$listingID",$url); // add msg=1 to url - login required
		}

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

?>