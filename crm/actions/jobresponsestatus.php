<?php

# GET DATA
		$currURL = @$_REQUEST['currenturl'];
		$status = ucfirst(@$_REQUEST['st']);
		$status = ($status=='A'?'accepted':'rejected');
		$listingResponseID = (int) @$_REQUEST['listingResponseID'];

		$listingResponseObj = new listingresponse();
		$sql = "UPDATE listingResponses SET status='".$status."' WHERE listingResponseID=$listingResponseID"; 
		$listingResponseDAO = $listingResponseObj->executeQuery($sql);

		# UPDATE STATUS OF PARTICULAR JOB TO "COMPLETED" IF ITS ACCEPTED
		if($status=='accepted')
		{
			# GET LISTING DATA FOR PARTICULAR JOB
			$listingResponseObj = new listingresponse();
			$sql = "SELECT listingID FROM listingResponses WHERE listingResponseID=$listingResponseID";
			$listingResponseDAO = $listingResponseObj->getRecordsFromQuery($sql);
			$listingResponseDAO->fetch();
			$listingID=$listingResponseDAO->listingID;
			
			# UPDATE STATUS TO "accepted" FOR PARTICULAR JOB - it will omit this job in getting listed while searching jobs
			$listingObj = new listing();
			
			$listingDAO->getOneRecord($listingID);
			$listingDAO->status = 'accepted';
			$listingDAO->update();
			//$sql = "UPDATE listings SET status='accepted' WHERE listingID=$listingID";
			//$listingDAO = $listingObj->executeQuery($sql);
		}

		$str = $currURL;
		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$str); // remove msg=N from url - if exists
		$pattern = '/_a=myjobresponses/';
		if($status=='accepted')
			$url = preg_replace($pattern,'_a=myjobresponses&msg=1',$url); // add msg=1 to url - business accepted
		else
			$url = preg_replace($pattern,'_a=myjobresponses&msg=2',$url); // add msg=2 to url - business rejected

		httpRedirect($url);
		exit;

?>