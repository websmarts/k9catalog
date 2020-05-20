<?php



#++++ FORMAT PAGING - SET PAGIN TEXT

function format_paging_text($totRec,$startRec,$endRec,$time, $recInfo,$pageInfo)

{

	print "Total " . $totRec . " search results found. " . 

	"Currently displaying records " . $startRec . " to " . $endRec . " within " . $time . " sec(s)<br>". 

	$pageInfo . "<br>";

}

#++++ GET PAGING FOR A PAGE VERSION 1

function paging($totRec,$start,$end,$page,$gap) // 1 2 3 4 5 6 ...

{

	$str='Pages: ';

	$totalPages = ceil($totRec/$gap);

	for($i=0;$i<$totalPages;$i++)

	{

		if($i!=$page)

		{

			$url = HOST1 . $_SERVER['REQUEST_URI'];

			$url = substr($url,0,strlen($url)-1);

			$url = $url.$i;

			$str .= "<a href='$url'>".($i+1) . "</a> ";

		}

		else

		{

			$str .= ($i+1) . " ";

		}

	}

	return $str;

}

#++++ GET PAGING FOR A PAGE VERSION 2

function paging1($totRec,$start,$end,$page,$gap) // [Start] [Prev] Page 1/9 [Next] [Last]

{

	$totalPages = ceil($totRec/$gap);

	$str="";



	$url = HOST1 . $_SERVER['REQUEST_URI'];



	// if pg=0 is not there in query string then add it

	$pattern = '/&pg=([0-9])*/';

	preg_match($pattern,$url,$matches);

	if (sizeof($matches)<1)

		$url .= "&pg=0";



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=0";

	$url_first = preg_replace($pattern, $replacement, $url);



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=".($page-1);

	$url_prev = preg_replace($pattern, $replacement, $url);



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=".($page+1);

	$url_next = preg_replace($pattern, $replacement, $url);



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=".($totalPages-1);

	$url_last = preg_replace($pattern, $replacement, $url);



	if ($page>0)

		$str .= " <a href='$url_first'>[First]</a> <a href='$url_prev'>[Prev]</a> ";

	if ($page==0)

		$str .= " [First] [Prev] ";

	$str.=" <b>Page: ".($page+1)."/$totalPages</b> ";

	if ($page<($totalPages-1))

		$str .= " <a href='$url_next'>[Next]</a> <a href='$url_last'>[Last]</a> ";

	if ($page==($totalPages-1))

		$str .= " [Next] [Last] ";



	return $str;

}



#++++ GET PAGING FOR A PAGE VERSION 3

function paging2($totRec,$start,$end,$page,$gap) // [first] [prev] Page 1/9 [next] [last]

{

	$totalPages = ceil($totRec/$gap);

	$str="";

	$url = HOST1 . $_SERVER['REQUEST_URI'];



	// if pg=0 is not there in query string then add it

	$pattern = '/&pg=([0-9])*/';

	preg_match($pattern,$url,$matches);

	if (sizeof($matches)<1)

		$url .= "&pg=0";



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=0";

	$url_first = preg_replace($pattern, $replacement, $url);



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=".($page-1);

	$url_prev = preg_replace($pattern, $replacement, $url);



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=".($page+1);

	$url_next = preg_replace($pattern, $replacement, $url);



	$pattern = "/pg=([0-9]+)/";

	$replacement = "pg=".($totalPages-1);

	$url_last = preg_replace($pattern, $replacement, $url);



	$str = "Results Page : ";

	if ($page>0)

		$str .= " <a href='$url_first'>first</a> <a href='$url_prev'>prev</a> ";

	if ($page==0)

		$str .= " first prev ";

	$str.=" <b>page: ".($page+1)."/$totalPages</b> ";

	if ($page<($totalPages-1))

		$str .= " <a href='$url_next'>next</a> <a href='$url_last'>last</a> ";

	if ($page==($totalPages-1))

		$str .= " next last ";



	return $str;

}



#++++ GET BUSINESS CATEGORY LIST

function getCatList($getMain)

{

	$catArray = array();

	global $catArray;

	$catName = "";

	if($getMain=='yes')

		$catArray[0] = "@None";



	$cat = &new category();

	$sql = "SELECT * FROM categories WHERE parentCategoryID=0";

	$catDAO = $cat->getRecordsFromQuery($sql);

	while($catDAO->fetch())

	{

		$catID = (int)$catDAO->categoryID;

		$catName = $catDAO->categoryName;

		$catArray[$catID]=$catName;

		$arr = getChildCat($catID, $catName);

	}

	asort($catArray);

	return $catArray; // RETURN CAT ARRAY

}

#++++ GET BUSINESS CATEGORY LIST

function getChildCat($catID, $catName)

{



	global $catArray;

	$cat = &new category();

	$sql = "SELECT * FROM categories WHERE parentCategoryID=$catID";

	$catDAO = $cat->getRecordsFromQuery($sql);

	while($catDAO->fetch())

	{

		global $catArray;

		$catID = (int)$catDAO->categoryID;

		$catName1 = $catName . " > " . $catDAO->categoryName;

		$catArray[$catID]=$catName1;

		getChildCat($catID, $catName1);

	}

}



## GET PARENT CATEGORY COOKIE CRUMB

function getParentCatName($catID)

{

	global $catName;

	$catName = "";

	$cat = &new category();

	$sql = "SELECT * FROM categories WHERE categoryID=$catID";

	$catDAO = $cat->getRecordsFromQuery($sql);

	while($catDAO->fetch())

	{

		$catID = (int)$catDAO->parentCategoryID;

		$catName .= $catDAO->categoryName . ">";

		getParentCatName2($catID);

	}

	//make the string reverse

	$catName = substr($catName,0,(strlen($catName)-1));

	$catN = explode(">",$catName);

	for ($i=sizeof($catN)-1;$i>=0;$i--)

	{

		if($catN[$i]!='')

			$catN1 .= ">" .$catN[$i];

	}

	$catName = "Main Category" .$catN1;

	$catName = str_replace("Main Category>","",$catName);

	

	return $catName;

}

function getParentCatName2($catID)

{

	global $catName;

	$cat = &new category();

	$sql = "SELECT * FROM categories WHERE categoryID=$catID";

	$catDAO = $cat->getRecordsFromQuery($sql);

	while($catDAO->fetch())

	{

		$catID = (int)$catDAO->parentCategoryID;

		$catName .= $catDAO->categoryName . ">";

		getParentCatName2($catID);

	}

}



## GET BUSINESS CATEGORY LIST

function getBizCatName($bizID)

{

	$buscat = &new business2category();

	$sql = "SELECT * FROM business2category WHERE businessID=$bizID";

	$buscatDAO = $buscat->getRecordsFromQuery($sql);

	//print_r ($buscatDAO);

	//exit;

	while($buscatDAO->fetch())

	{

		$catID = (int)$buscatDAO->categoryID;

		$cat[] = getParentCatName($catID);

	}

	@sort($cat);

	$cat = @implode("<br>",$cat);

	return $cat;

}



## SET COOKIE FOR POST CODE

function setCookieValue($name,$value)

{

	session_start();

	setcookie("$name",'');

	setcookie("$name", "$value", time()+(3600*24*30));

	$_COOKIE["$name"]="$value";

//	setcookie(COOKIE-NAME, COOKIE-VALUE, EXPIRE-TIME, DIR, DOMAIN, SECURE);

}



## GET MATCHING BUSINESS BY LISTING ID

function getBusinessesFromListing($listingID,$limitRecords)

{

	$sql = "SELECT * FROM listings WHERE listingID=$listingID";

	//print "$sql <br>";



	$listingObj = &new listing();

	$listingDAO = $listingObj->getRecordsFromQuery($sql);

	$totRec = $listingDAO->N;

	$listingDAO->fetch();

	$title = $listingDAO->title;

	$description = $listingDAO->shortDescription;

	$keywords = $listingDAO->keywords;

	$postcode = $listingDAO->postcode;

	

	$stemmedTitle = $listingDAO->stemmedTitle;

	$stemmedShortDescription = $listingDAO->stemmedShortDescription;



	//stem keywords and use it in search

	$objStem = new Stemmer();

	$stemmedKeywordsArr = $objStem->stem_list($keywords);

	$stemmedKeywords = @implode(" ",$stemmedKeywordsArr);



	$sponsBusDAO = getBusinessFromCriteria($title,$description,$keywords,$postcode,$stemmedTitle,$stemmedShortDescription,$stemmedKeywords,$limitRecords);

	return $sponsBusDAO;

}



## GET BUSINESSES MATCHING WITH title, descr and keywords

function getBusinessFromCriteria($title,$description,$keywords,$postcode,$stemmedTitle,$stemmedShortDescription,$stemmedKeywords,$limitRecords)

{

	//print "<br>" . $title . "<br>" . $description . "<br>" . $keywords . "<br>";

	//print "<br>" . $stemmedTitle . "<br>" . $stemmedShortDescription . "<br>" . $stemmedKeywords . "<br>";



	$searchText = addslashes($title) . " " . addslashes($description) . " " . addslashes($keywords);

	$stemmedSearchText = addslashes($stemmedTitle) . " " . addslashes($stemmedShortDescription) . " " . addslashes($stemmedKeywords);

	

	$sponserCond = " WHERE postcode=$postcode AND ( MATCH (keywords) AGAINST ('".$searchText."')";

	$sponserCond .=  " OR MATCH (stemmedKeywords) AGAINST ('".$stemmedSearchText."') )";

	$sql = "SELECT DISTINCT * FROM businesses $sponserCond ORDER BY businessName LIMIT 0,$limitRecords";	

	//print "<br>$sql<br>";

	

	$sponsBusObj = &new business();

	$sponsBusDAO = $sponsBusObj->getRecordsFromQuery($sql);

	return $sponsBusDAO;

}



function getStemmedString($str)

{

	$objStem = new Stemmer();

	$stemmedArr = $objStem->stem_list_z($str);

	$stemmedStr = @implode(" ",$stemmedArr);

//	print $stemmedStr;

	return $stemmedStr;

}



# GET SPONSORED BUSINESS LISTINGS USING KEYWORD ONLY

function getBusinessByKeyword($postcode,$keywords)

{

	# MAKE STEMMED SEARCH STRING

	$stemmedKeywords = getStemmedString($keywords);

	$sponserCond = " WHERE postcode IN ('".$postcode."') AND ( MATCH (keywords) AGAINST ('".$keywords."')";

	$sponserCond .=  " OR MATCH (stemmedKeywords) AGAINST ('".$stemmedKeywords."') )";

	$sql = "SELECT DISTINCT * FROM businesses $sponserCond ORDER BY businessName LIMIT 0,6";

	//print "<br>$sql<br>";

	$sponsBusObj = &new business();

	$sponsBusDAO = $sponsBusObj->getRecordsFromQuery($sql);

	return $sponsBusDAO;

}



# PRINTS 'on' OR 'off' - HELPS TO SELECT THE CORRECT CLASS OR IMAGE FOR TABBED SEARCH

function tab_state($goTab,$tab) {

	echo $goTab==$tab?'on':'off';

}



# GENERATE ACTIVATION KEY FOR ACTIVATION EMAIL

function generateActivationKey($accountID)

{

	$activationKey = base64_encode("accountID=".$accountID."&crc=".crc32($accountID));

	return $activationKey;

}



# DECODE ACTIVATION KEY

function decodeActivationKey($key)

{

	$dKey = base64_decode($key);

	$totChars = strpos($dKey,'&crc=');

	parse_str($dKey,$output);

	if (crc32($output['accountID']) == $output['crc'] )

		return $output['accountID'];

	else

		return 0;

}



function getCityListFromPostcode($postcode)

{

	if(trim($postcode)!='')

	{

		$sql = "SELECT * FROM postcodes WHERE postcode=$postcode ORDER BY city";

		$postcodeObj = &new postcode();

		$postcodeDAO = $postcodeObj->getRecordsFromQuery($sql);

		while($postcodeDAO->fetch())

		{

			$cityArray[] = $postcodeDAO->city;

		}

	}

	$cityStr = @implode(", ",$cityArray);

	return $cityStr;

}



# GET TOTAL NO OF RESPONSES TO A JOB

function getJobResponseNumber($listingID)

{

	$sql = "SELECT count(*) AS tot FROM listingResponses WHERE listingID=$listingID";

	$listingResponseObj = new listingresponse();

	$listingResponseDAO = $listingResponseObj->getRecordsFromQuery($sql);

	$listingResponseDAO->fetch();

	return $listingResponseDAO->tot;

}



# CHECK WHETHER A BUSINESS IS IN HIS JOB LIST OR NOT

function isBusinessInJobList($accountID,$listingID) // returns either listID or 0

{

	//check for unique entry for a business in myList table

	$myJobsObj = new myJobs();

	$sql = "SELECT * FROM myJobs WHERE accountID=$accountID and listingID=$listingID"; 

	$myJobDAO = $myJobsObj->getRecordsFromQuery($sql);

	if($myJobDAO->N>0)

	{

		$myJobDAO->fetch();

		return 1;

	}

	else

		return 0;

}



function isBusinessInJobalertList($accountID) // returns either listID or 0

{

	//check for unique entry for a business in myList table

	$myListObj = new mylist();

	$sql = "SELECT * FROM myLists WHERE accountID=$accountID and listName=jobAlert"; 

	$myListDAO = $myListObj->getRecordsFromQuery($sql);

	if($myListDAO->N>0)

	{

		$myListDAO->fetch();

		return $myListDAO->listID;

	}

	else

		return 0;

}



# CHECK WHETHER A JOB IS IN HIS JOB LIST OR NOT

function isJobInJobList($accountID,$listingID)

{

	//check for unique entry for a business in myList table

	$list2mylistObj = new listings2mylist();

	$sql = "SELECT * FROM myJobs WHERE accountID=$accountID AND listingID=$listingID"; 

	$list2mylistDAO = $list2mylistObj->getRecordsFromQuery($sql);

	return $list2mylistDAO->N;

}



# CHECK WHETHER A JOB IS IN HIS JOB LIST OR NOT

function isJobalertInJobList($accountID,$listingID,$listID)

{

	//check for unique entry for a business in myList table

	$list2mylistObj = new listings2mylist();

	$sql = "SELECT * FROM listings2myList WHERE accountID=$accountID AND listingID=$listingID AND listID=$listID"; 

	$list2mylistDAO = $list2mylistObj->getRecordsFromQuery($sql);

	return $list2mylistDAO->N;

}



# CHECK WHETHER A BUSINESS HAS RESPONDED TO A JOB OR NOT

function isJobResponded($accountID,$listingID)

{

	$listingResponseObj = new listingresponse();

	$sql = "SELECT * FROM listingResponses WHERE accountID=$accountID AND listingID=$listingID"; 

	$listingResponseDAO = $listingResponseObj->getRecordsFromQuery($sql);

	return $listingResponseDAO->N;

}



# GET RESPONSE FOR A JOB

function getMyJobResponse($accountID,$listingID)

{

	$listingResponseObj = new listingresponse();

	$sql = "SELECT * FROM listingResponses WHERE accountID=$accountID AND listingID=$listingID"; 

	$listingResponseDAO = $listingResponseObj->getRecordsFromQuery($sql);

	$listingResponseDAO->fetch();

	return $listingResponseDAO->message;

}





# GET RESPONSE STATUS FOR A JOB

function getJobResponseStatus($accountID,$listingID)

{

	$listingResponseObj = new listingresponse();

	$sql = "SELECT * FROM listingResponses WHERE accountID=$accountID AND listingID=$listingID"; 

	$listingResponseDAO = $listingResponseObj->getRecordsFromQuery($sql);

	$listingResponseDAO->fetch();

	return $listingResponseDAO->status;

}



# SHOW LIMITED NO OF CHARACTERS FOR SHORT DESCRIPTION 

function showLimitedDescription($descr) 

{

	$descr = trim($descr);

	$length=50; // chars limit

	$strLength = strlen($descr);

	if (strlen($descr)>$length)

	{

		$str = trim(substr($descr,0,$length));

		print $str;

		return true;

	}	

	else

	{

		$str = $descr;

		print $str;

		return false;

	}	

}



# GET WITHHOLDING PERIOD FOR AN ACCOUNT FOR JOB LISTING

function getWithHoldingPeriod($accountID)

{

	$accountObj = new account();

	$sql = "SELECT * FROM accounts WHERE accountID=$accountID"; 

	$accountDAO = $accountObj->getRecordsFromQuery($sql);

	$accountDAO->fetch();

	$accountType = $accountDAO->accountType;

	if($accountType=='businessuser') {

		$withHoldingTime = time()-86400; // 24 hours back // 86400 = 24 hours; 3600 = 1 hour

	}

	elseif($accountType=='businessuser1') {

		$withHoldingTime = time()-43200; // 12 hours back // 43200 = 12 hours; 3600 = 1 hour

	}

	elseif($accountType=='businessuser2') {

		$withHoldingTime = time(); // current time // 0= 0 hours; 3600 = 1 hour

	}

	else {

		$withHoldingTime = time()-86400; // 24 hours back // 86400 = 24 hours; 3600 = 1 hour

	}	

		

	return 	$withHoldingTime;

}



# GET LIST OF ALL SERVICE POSTCODES

function getMyServiePostcodes($accountID)

{

	$sql = "select * from account2location,location2postcode WHERE account2location.accountID=".$accountID." AND " .

			"account2location.locationID=location2postcode.locationID";

	//print "<br>$sql<br>";

	$test_value=0;

	$accountObj = &new account();

	$accountDAO = $accountObj->getRecordsFromQuery($sql);

	while ($accountDAO->fetch())

	{

		$test_value=1;

		$postcodeArray[] =$accountDAO->postcode;

	}

	if($test_value==1){

		return $postcodeArray;}

	else

	{

		$postcodeArray[] = 0;

		return $postcodeArray;

	}



}



# CHECK WHETHER JOB IS IN THE SERVICE AREA OR NOT

function isJobInServiceArea($accountID,$listingID)

{

	# GET SERVICE AREA POSTCODES

	$postcodeArray = getMyServiePostcodes($accountID);

	# GET POSTCODES IN COMMA SEPERATED VALUES

	if((int)$postcodeArray[0]>0)

	{

		$postcodeList = @implode(",",$postcodeArray);

		$postcodeList .=",$postcode"; # add first postcode

		$postcodeList = str_replace(",","','",$postcodeList); # add single quote for conditions

	}



	# GET LISTING POSTCODE

	$sql = "SELECT * FROM listings WHERE listingID=$listingID";

	//print "$accountID<br>$sql <br>";

	$listingObj = &new listing();

	$listingDAO = $listingObj->getRecordsFromQuery($sql);

	$listingDAO->fetch();

	$postcode = $listingDAO->postcode;

	//print $postcode . "<br>";

	//print_r ($postcodeArray);

	if (in_array($postcode,$postcodeArray))

		return true;

	else

		return false;

}



# GET POSTCODE LIMIT

function getPostcodeLimit($accountID,$accountType)

{

	//print $accountID . " " . $accountType;

	if($accountType=='businessuser2')

		return 25;

	if($accountType=='businessuser1')

		return 10;

	if($accountType=='businessuser')

		return 1;

}

function getinvresponsearray($response)

{

	//print $accountID . " " . $accountType;

	if($response=='olr')

		return 'Call You';

	if($response=='direct')

		return 'Call Me';

}





# GET CAMPAIGN LIST

function getCampaignList()

{

	$sql = "SELECT * FROM campaigns WHERE status='open'";

	//print "<br>$sql<br>";

	$campaignObj = &new campaign();

	$campaignDAO = $campaignObj->getRecordsFromQuery($sql);

	while ($campaignDAO->fetch())

	{

		$campaignArray[$campaignDAO->campaignID]=$campaignDAO->campaignName;

	}

	return $campaignArray;

}



# CHECK WHETHER BUSINESS IS ASSIGNED TO ANY CAMPAIGNED OR NOT

function isBusAssignedToCampaign($businessID)

{

	$sql = "SELECT * FROM business2campaign WHERE businessID=$businessID";

	//print "<br>$sql<br>";

	$campaignObj = &new campaign();

	$campaignDAO = $campaignObj->getRecordsFromQuery($sql);

	//$campaignDAO->fetch();

	return $campaignDAO->N;



}



function create4CharsArray($no)

{

	$charArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	$newArray= array();

	for($i=0;$i<count($charArray);$i++)

		for($j=0;$j<count($charArray);$j++)

			for($k=0;$k<count($charArray);$k++)

				$newArray[]=$charArray[$i].$charArray[$j].$charArray[$k].$no;



	return $newArray;

}

#	Insert business job alert entries related to new jobs.

function check_data($accountID,$businessID)

{

	//----------------------------------------------------

		//	- GLOBAL AUTHORIZATION -

//		$auth = &new AuthorizeClient();

//		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT

		//----------------------------------------------------



		# GET REQUIRED DATA

		$listingID = (int) @$_REQUEST['listingID'];

		$businessUser=$businessID;

		if($businessUser) // if its business user then add job to joblist

		{



				$myAlertObj = new myAlerts();

				$myAlertValues = array('accountID'=>$businessID,'listingID'=>$listingID,'source'=>'auto','created'=>time());

				print_r($myAlertValues);

				$myAlertID = $myAlertObj->insertRecord($myAlertValues);

		

		}

}

function alertCount($listingID,$accountID)

{

	$alertObj = new myAlerts();

	$sql = "SELECT * FROM myAlerts as a inner join listings as b on a.listingID=b.listingID WHERE b.accountID=$accountID AND a.listingID=$listingID"; 

	$alertDAO = $alertObj->getRecordsFromQuery($sql);

	return $alertDAO->N;

}	

function check_data_old($accountID,$businessID)

{

	//----------------------------------------------------

		//	- GLOBAL AUTHORIZATION -

		$auth = &new AuthorizeClient();

		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT

		//----------------------------------------------------



		# GET REQUIRED DATA

		$listingID = (int) @$_REQUEST['listingID'];

		$businessUser=$businessID;

		if($businessUser) // if its business user then add job to joblist

		{

			//check for unique business in myList table

			$isBusinessInJobList = isBusinessInJobList($businessID); // returns either listID or 0

			if($isBusinessInJobList<1) // if not in the table insert in myLists first

			{

				$myListObj = new mylist();

				$myListValues = array('accountID'=>$businessID,'listName'=>'myAlert');

				$listID = $myListObj->insertRecord($myListValues);

			}

			else if($isBusinessInJobList >= 1) // if not in the table insert in myLists first

			{

				$myListObj = new mylist();

				$mylistingArray = $myListObj->getOneRecordArray($isBusinessInJobList);

				

					

				if($mylistingArray['listName']=="myAlert"){

					$listID = $isBusinessInJobList;

					}

				else

					{

						$myListObj = new mylist();

						$myListValues = array('accountID'=>$businessID,'listName'=>'myAlert');

						$listID = $myListObj->insertRecord($myListValues);

					}

			}

			else // if its there set listID

			{

				$listID = $isBusinessInJobList;

			}

		

			//check for unique job in table

			$isJobInJobList = isJobalertInJobList($accountID,$listingID,$listID);



			if($isJobInJobList<1) //if not in the table insert in myList first

			{		

				

				$list2mylistValues = array('listID'=>$listID,'listingID'=>$listingID,'status'=>'tagged','accountID'=>$accountID,'source'=>'auto');

				$list2mylistObj = new listings2mylist();

				$list2mylistObj->insertRecord($list2mylistValues);

				

			}

		}	



}

	function category_matching_process()

	{

		

	}	

## GET MATCHING CATEGORIES BY LISTING ID

function getCategoriesFromListing($listingID,$limitRecords)

{

	$sql = "SELECT * FROM listings WHERE listingID=$listingID";



	$listingObj = &new listing();

	$listingDAO = $listingObj->getRecordsFromQuery($sql);

	$totRec = $listingDAO->N;

	$listingDAO->fetch();

	$title = $listingDAO->title;

	$description = $listingDAO->shortDescription;

	$postcode = $listingDAO->postcode;

	$stemmedTitle = $listingDAO->stemmedTitle;

	$stemmedShortDescription = $listingDAO->stemmedShortDescription;



	//stem keywords and use it in search



	$sponsBusDAO = getCategoriesFromCriteria($title,$description,$postcode,$stemmedTitle,$stemmedShortDescription,$limitRecords);

	return $sponsBusDAO;

}



## GET BUSINESSES MATCHING WITH title, descr and keywords

function getCategoriesFromCriteria($title,$description,$postcode,$stemmedTitle,$stemmedShortDescription,$limitRecords)

{

	print "<br>" . $title . "<br>" . $description . "<br>";

	//print "<br>" . $stemmedTitle . "<br>" . $stemmedShortDescription . "<br>" . $stemmedKeywords . "<br>";



	$searchText = addslashes($title) . " " . addslashes($description);

	print $searchText;

	$stemmedSearchText = addslashes($stemmedTitle) . " " . addslashes($stemmedShortDescription);

	

/*	$sponserCond = " WHERE ( MATCH (keywords) AGAINST ('".$searchText."')";

	$sponserCond .=  " OR MATCH (stemmedKeywords) AGAINST ('".$stemmedSearchText."') )";

	$sql = "SELECT *  FROM categories $sponserCond ORDER BY categoryName LIMIT 0,$limitRecords";	

*/

	$sponserCond = " WHERE ( MATCH (keywords) AGAINST ('".$searchText."')";

	$sponserCond .=  " OR MATCH (stemmedKeywords) AGAINST ('".$stemmedSearchText."') )";

	$sql = "SELECT *  FROM categories $sponserCond ORDER BY categoryName LIMIT 0,$limitRecords";	



	print "<br>$sql<br>";

	

	$sponsBusObj = &new category();

	$sponsBusDAO = $sponsBusObj->getRecordsFromQuery($sql);

	return $sponsBusDAO;

}		

function make_array_rank_old($str) {



	$listingID = (int) @$_REQUEST['listingID'];

	$sql = "SELECT * FROM listings WHERE listingID=$listingID";



	$listingObj = &new listing();

	$listingDAO = $listingObj->getRecordsFromQuery($sql);

	$totRec = $listingDAO->N;

	$listingDAO->fetch();

	$title = $listingDAO->title;

	$description = $listingDAO->shortDescription;

	$postcode = $listingDAO->postcode;

	$stemmedTitle = $listingDAO->stemmedTitle;

	$stemmedShortDescription = $listingDAO->stemmedShortDescription;

	



	$sql = "select categoryID,categoryName,stemmedKeywords from categories";

	$sponsBusObj = &new category();

	$sponsBusDAO = $sponsBusObj->getRecordsFromQuery($sql);



	$job_match_string = $title." ".$description;



	$title_arr=split(" ",$job_match_string);



//	$title_arr[1]=$description;

	

	$objStem = new Stemmer();	



	// convert string into words

	$j=-1;



	while ($sponsBusDAO->fetch()) { 

		$cat_keyword = split(" ",$sponsBusDAO->stemmedKeywords); 

		

	//	if(sizeof($cat_keyword)==0)

//				$cat_keyword[0]=$sponsBusDAO->keywords;

	

			foreach ($title_arr as $value){ // test each WORD from Job title (if not in stop list and word is >2 characters)

					$flag = "set";			

			

					//print_r($cat_keyword);

				$k=1;			

				for($i=0;$i<sizeof($cat_keyword);$i++){

			

						$value = $objStem->stem($value);

//						$sword = $objStem->stem($cat_keyword[$i]);

						$sword = $cat_keyword[$i];

//						print $value."  -  ".$sword."<br>";

//						print $sword."<br>";

				//       $rank[$sponsBusDAO->categoryID] += preg_match_all(job stemmed keyword, category stemmed keywords) // count the matches

				//		$rank[$sponsBusDAO->categoryID]  = preg_match("/".$value."/i", $sponsBusDAO->keywords);

				///print $value."  ".$sponsBusDAO->keywords."<br>";

//				print $flag ." ".$value."  -  ".$cat_keyword[$i]. "<br>";

						if(strcmp($value,$sword)==0){

								if($flag=="set")

										{

											$j++;

											$spons_array[$j][0] = $sponsBusDAO->categoryID;

											$spons_array[$j][1] = $sponsBusDAO->categoryName;

											$spons_array[$j][2] = $k++;

											$flag="unset";

										}

								else

										{ 

											$spons_array[$j][2] = $k++;

										}

							

						}

				}

	

			}

	

		

} // foreach



/*    foreach (job stemmedkeyword) // test each WORD from Job (if not in stop list and word is >2 characters)

        $rank[category] += preg_match_all(job stemmed keyword, category stemmed keywords) // count the matches





	if (is_array($words) ) {

		foreach ($words as $word) {

			

			$sword = $objStem->stem($word);

			$newstr .= $sword." ";

		}

		

	} else {

		$sword = $objStem->stem($str);

		$newstr = $sword;

	}

	$newstr = trim($newstr);

	return $newstr;

	*/



/*	asort($spons_array);



	$second_array = $spons_array;

//	print_r($second_array);

	

	for($count=0;$count<sizeof($second_array);$count++){

	$flg_check="other";

//	print $count;

		if($count==0)

			{

//				print $count."  ".$second_array[$count][1]."<br>";

					$final_array[$count][0] = $second_array[$count][0];

					$final_array[$count][1] = $second_array[$count][1];

					$final_array[$count][2] = $second_array[$count][2] ;

					$flg_spec = "set";

//					print_r($final_array);

//					print "<br>";



			}



			if($second_array[$count][0]==$second_array[$count+1][0])

			{

			if($flg_spec == "set"){

					$flg_spec = "unset";

					$cnt = $count;

				}

				$flg_check="another";

//				print $cnt;

//			print $count."  ".$second_array[$count][1]."<br>";

					$final_array[$cnt][2] = $final_array[$cnt][2] + $second_array[$count+1][2];

//					print_r($final_array)."<br>";

//					print "<br>";



//			$count++;

			}

			else

			{



		if($flg_check=="another")

			$count++;

	//		print $count;

					$flg_spec = "set";

					$flg_check="other";

				print $count."  ".$second_array[$count][1]."<br>";

					$final_array[$count][0] = $second_array[$count][0];

					$final_array[$count][1] = $second_array[$count][1];

					$final_array[$count][2] = $second_array[$count][2] ;

//			print_r($final_array)."<br>";

			}



		}

*/	

	

		if(sizeof($spons_array)>0)

		 {

			foreach ($spons_array as $key => $row) {

				   $volume[$key]  = $row[2];

					}

						array_multisort($volume, SORT_DESC, $spons_array);

		}

	 

	return array($spons_array);

}

function make_array_rank($listingID) {



	$sql = "SELECT * FROM listings WHERE listingID=$listingID";



	$listingObj = &new listing();

	$listingDAO = $listingObj->getRecordsFromQuery($sql);

	$totRec = $listingDAO->N;

	$listingDAO->fetch();

	$title = $listingDAO->title;

	$description = $listingDAO->shortDescription;

	$postcode = $listingDAO->postcode;

	$stemmedTitle = $listingDAO->stemmedTitle;

	$stemmedShortDescription = $listingDAO->stemmedShortDescription;

	

	$sql = "select categoryID,categoryName,stemmed_keywords from categories";

	$sponsBusObj = &new category();

	$sponsBusDAO = $sponsBusObj->getRecordsFromQuery($sql);



	$job_match_string = $title." ".$description;



	$title_arr=split(" ",$job_match_string);

	$title_arr = array_unique($title_arr);



	$inc=0;



	$objStem = new Stemmer();	

	// convert string into words



	$count=0;

	while ($sponsBusDAO->fetch()) { 

		$cat_keyword = split(" ",$sponsBusDAO->stemmed_keywords); 

				$flag_set = "set";		

			foreach ($title_arr as $value){ // test each WORD from Job title (if not in stop list and word is >2 characters)

				$value = $objStem->stem($value);			

				#THIS nrMatches WILL GET RANK FOR JOB STRING MATCH.

						$nrMatches = preg_match_all ("/\b$value\b/",$sponsBusDAO->stemmed_keywords,$m);

						if($nrMatches>0){

									$spons_array[$count][0] = $sponsBusDAO->categoryID;

									$spons_array[$count][1] = $sponsBusDAO->categoryName;

									if($flag_set=="unset")

										{

											$spons_array[$count][2] = $spons_array[$count][2] + $nrMatches;											

										}

									else

										{

											$spons_array[$count][2] = $nrMatches;											

										}



									$flag_set="unset";

								}

				}

			$count++;

		} 

		if(sizeof($spons_array)>0)

		 {

			foreach ($spons_array as $key => $row) {

				   $volume[$key]  = $row[2];

					}

						array_multisort($volume, SORT_DESC, $spons_array);

		}

	return array($spons_array);

}

?>