<?php

//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT
		//----------------------------------------------------

		# GET REQUIRED DATA

		$listingID = (int) @$_REQUEST['listingID'];

		$url = $_SERVER['HTTP_REFERER'];
		$str = $_SERVER['HTTP_REFERER'];
		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$str); // remove msg=N from url - if exists
		
		$pattern = '/&listingID=([0-9])*/';
		$url = preg_replace($pattern,'',$url); // remove listingID=N from url - if exists

		if($businessUser) // if its business user then add job to joblist
		{
			//check for unique business in myList table
			$isBusinessInJobList = isBusinessInJobList($accountID); // returns either listID or 0
			if($isBusinessInJobList<1) // if not in the table insert in myLists first
			{
				$myListObj = new mylist();
				$myListValues = array('accountID'=>$accountID,'listName'=>'myAlert');
				$listID = $myListObj->insertRecord($myListValues);
			}
			else if($isBusinessInJobList >= 1) // if not in the table insert in myLists first
			{
				$myListObj = new mylist();
				$mylistingArray = $myListObj->getOneRecordArray($isBusinessInJobList);
				print_r($mylistingArray);
				if($mylistingArray('listName')=="myAlert")
					$listID = $isBusinessInJobList;
				else
					{
						$myListObj = new mylist();
						$myListValues = array('accountID'=>$accountID,'listName'=>'myAlert');
						$listID = $myListObj->insertRecord($myListValues);
					}
			}
			else // if its there set listID
			{
				$listID = $isBusinessInJobList;
			}
		
			//check for unique job in table
			$isJobInJobList = isJobInJobList($accountID,$listingID);
			
			if($isJobInJobList<1) //if not in the table insert in myList first
			{		
				
				$list2mylistValues = array('listID'=>$listID,'listingID'=>$listingID,'status'=>'tagged','accountID'=>$accountID,'source'=>'auto');
				$list2mylistObj = new listings2mylist();
				$list2mylistObj->insertRecord($list2mylistValues);
				
				$pattern = '/_a=jobslist/';
				$url = preg_replace($pattern,"_a=jobslist&msg=2&listingID=$listingID",$url); // add msg=2 to url - successfully added to job list
			}
			else
			{
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