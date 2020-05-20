<?php 
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found'); exit;}

switch ($action)
{

	case 'classifiedssearch':
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$distanceArray = Constants::getDistanceArray();
		// This array defines what is displayed each TAB heading
		$tabs = Constants::getSearchTabsArray();
		// goTab selects the active tab
		$goTab = 'classifieds';

		// :: elements
		$form = &new HTML_QuickForm('listingsearch','POST','index.php?_a=classifiedssearch');
		$form->addElement('text', 'searchText',null, array('size'=>20));
		$form->addElement('text', 'postcode',null, array('size'=>5));
		$form->addElement('select', 'distance',null, $distanceArray);
		$form->addElement('checkbox', 'search_in_descr',null,'');
		$form->addElement('submit','search_button',' Search ',array('class'=>'inputbutton'));

		# GET SESSION DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$keywords = $dataBank->getVar('s_c_keywords');

		#++++ FORM DEFAULTS / CONSTANTS -
		$cookArray = array('postcode'=>$_COOKIE['c_postcode'],'distance'=>$_COOKIE['c_distance'],'searchText'=>$keywords);
		$form->setDefaults($cookArray);

		#++++ JS validation rules
		$form->addRule('searchText', '"search text" is a required field.', 'required', null, 'client');
		$form->addRule('distance', '"distance" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');

			#++++ Validate Login :: Form level 
			function validateListingSearchFields($fields)
			{
				$f1 = $fields['searchText'];
				$f2 = $fields['distance'];
				$f3 = $fields['postcode'];
				if (($f1 == '') || ($f2 == '') || ($f3 == ''))
				{
					return array('searchText' => 'All the fields are required for a search');
				}
				return true;
			}
			$form->addFormRule('validateListingSearchFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$searchText = $form->getSubmitValue('searchText');
			$distance = $form->getSubmitValue('distance');
			$postcode = $form->getSubmitValue('postcode');
			
			# set in cookie
			setCookieValue("c_postcode",$postcode);
			setCookieValue("c_distance",$distance);
			
			# set in session
			$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
			$dataBank->setVar('s_c_keywords',$searchText);
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url= "index.php?_a=listinglist&search_text=$searchText&post_code=$postcode&distance=$distance&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
		else
		{
			$view = $action;
		}

		break;

	case 'listingsearchcommunity':
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$distanceArray = Constants::getDistanceArray();
		// This array defines what is displayed each TAB heading
		$tabs = Constants::getSearchTabsArray();
		// goTab selects the active tab
		$goTab = 'community';

		// :: elements
		$form = &new HTML_QuickForm('listingsearch','POST','index.php?_a=listingsearchcommunity');
		$form->addElement('text', 'searchText',null, array('size'=>20));
		$form->addElement('text', 'postcode',null, array('size'=>5));
		$form->addElement('select', 'distance',null, $distanceArray);
		$form->addElement('checkbox', 'search_in_keywords',null,'');
		$form->addElement('submit','search_button',' Search ',array('class'=>'inputbutton'));

		# GET SESSION DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$keywords = $dataBank->getVar('s_c_keywords');

		#++++ FORM DEFAULTS / CONSTANTS -
		$cookArray = array('postcode'=>$_COOKIE['c_postcode'],'distance'=>$_COOKIE['c_distance'],'searchText'=>$keywords);
		$form->setDefaults($cookArray);

		#++++ JS validation rules
		$form->addRule('searchText', '"search text" is a required field.', 'required', null, 'client');
		$form->addRule('distance', '"distance" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');

			#++++ Validate Login :: Form level 
			function validateListingSearchFields($fields)
			{
				$f1 = $fields['searchText'];
				$f2 = $fields['distance'];
				$f3 = $fields['postcode'];
				if (($f1 == '') || ($f2 == '') || ($f3 == ''))
				{
					return array('searchText' => 'All the fields are required for a search');
				}
				return true;
			}
			$form->addFormRule('validateListingSearchFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$searchText = $form->getSubmitValue('searchText');
			$distance = $form->getSubmitValue('distance');
			$postcode = $form->getSubmitValue('postcode');
			
			# set in cookie
			setCookieValue("c_postcode",$postcode);
			setCookieValue("c_distance",$distance);
			
			# set in session
			$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
			$dataBank->setVar('s_c_keywords',$searchText);
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url= "index.php?_a=listinglist&search_text=$searchText&post_code=$postcode&distance=$distance&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
		else
		{
			$view = $action;
		}

		break;

	case "listinglist":

		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		$time=time();
		
		#++++ GET POST PARAMETERS
		$searchText = $_REQUEST['search_text'];
		$distance = $_REQUEST['distance'];
		$postcode = $_REQUEST['post_code'];
		$pg =  (int)$_REQUEST['pg'];

		# GET ALL THE POST CODES WITH RELEVANT DISTANCE FROM SPECIFIED POST CODE
		$sql = "SELECT * FROM postcodeProximity WHERE postcode=$postcode AND proximity<=$distance";
		$postcodeObj = &new postcode();
		$postcodeDAO = $postcodeObj->getRecordsFromQuery($sql);
		while($postcodeDAO->fetch())
		{
			$postcodeArray[] =$postcodeDAO->postcode2;
		}
		
		if((int)$postcodeArray[0]>0)
		{
			$postcodeList = @implode(",",$postcodeArray);
				# add first postcode
			$postcodeList .=",$postcode";
				# add single quote for conditions
			$postcodeList = str_replace(",","','",$postcodeList);
		}
		else
			$postcodeList = $postcode;

		# MAKE STEMMED SEARCH STRING	
		$stemmedSearchText = getStemmedString($searchText);			

		#++++ GENERATE CONDITION
		$cond = " WHERE status='approved'";
		if(trim($searchText)!="")
		{
			$cond .= " AND ( MATCH(title,shortDescription) AGAINST ('".$searchText."')";
			$cond .= " OR MATCH(stemmedTitle,stemmedShortDescription) AGAINST ('".$stemmedSearchText."') ) ";
		}
		if(trim($postcode)!="")
			$cond .= " AND postcode IN ('".$postcodeList."')";	

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(DISTINCT(listingID)) AS tot FROM listings $cond";	
		//print "<br>$sql<br>";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		# GET SPONSORED BUSINESS LISTINGS USING KEYWORDS
		$sponsBusDAO = getBusinessByKeyword($postcodeList,$searchText);
		$totSponsRec =$sponsBusDAO->N;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond ORDER BY title LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;

	case "classifiedadd":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$listingTypeArr = Constants::getListingTypeArray();
		// :: elements
		$form = &new HTML_QuickForm('classifiedadd','POST','index.php?_a=classifiedadd');
		$form->addElement('hidden', 'status','temp');
		$form->addElement('hidden', 'listingType','classified');
		$form->addElement('text','title',null, array('size'=>40));
		$form->addElement('textarea', 'shortDescription',null, array('cols'=>30,'rows'=>3));
		$form->addElement('textarea', 'fullDescription',null, array('cols'=>30,'rows'=>6));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		$cookArray = array('postcode'=>$_COOKIE['c_postcode']);
		$form->setDefaults($cookArray);

		#++++ JS validation rules
		$form->addRule('listingType', '"listing type" is a required field.', 'required', null, 'client');
		$form->addRule('title', '"listing title" is a required field.', 'required', null, 'client');
		$form->addRule('shortDescription', '"listing short description" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateListingAddFields($fields)
			{
				$f1 = $fields['listingType'];
				$f2 = $fields['title'];
				$f3 = $fields['shortDescription'];
				$f4 = $fields['postcode'];
				if (($f1 == '') || ($f2 == '') || ($f3 == '') || ($f4 == '')) {
					return array('title' => 'Listing type, title, short description and post code are required fields');
				}
				return true;
			}
			$form->addFormRule('validateListingAddFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$title = $form->getSubmitValue('title');
			$shortDescription = $form->getSubmitValue('shortDescription');
			$postcode = $form->getSubmitValue('postcode');

			#++++ get session data
			$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
			$accountID = $dataBank->getVar('s_c_account_id');

			# STEMMIG title and short description
			$objStem = new Stemmer();
			$stemmedTitleArr = $objStem->stem_list($title);
			$stemmedTitleStr = @implode(" ",$stemmedTitleArr);
			$stemmedShortDescrArr = $objStem->stem_list($shortDescription);
			$stemmedShortDescrStr = @implode(" ",$stemmedShortDescrArr);

			// 1. insert data into listing table
			$formValues = $form->getSubmitValues();
			$otherValues = array('accountID'=>$accountID,'stemmedTitle'=>$stemmedTitleStr,'stemmedShortDescription'=>$stemmedShortDescrStr,'createdDate'=>time());
			$formValues = array_merge($formValues,$otherValues);

			$listingObj = &new listing();
			$listingID = $listingObj->insertRecord($formValues);

			# REDIRECT TO CONFIRMATION PAGE
			$url= "index.php?_a=classifiedconfirm&listingID=$listingID";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;				
		}
		else
		{
			$view = $action;
		}
		
		break;
	
	case "classifiededit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$listingID = $_REQUEST['listingID'];

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$listingTypeArr = Constants::getListingTypeArray();

		// :: elements
		$form = &new HTML_QuickForm('classifiededit','POST','index.php?_a=classifiededit&listingID='.$listingID);
		$form->addElement('hidden', 'status','temp');
		$form->addElement('hidden', 'listingType','classified');
		$form->addElement('text','title',null, array('size'=>40));
		$form->addElement('textarea', 'shortDescription',null, array('cols'=>30,'rows'=>3));
		$form->addElement('textarea', 'fullDescription',null, array('cols'=>30,'rows'=>6));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		//get default values from business table
		$listingDAO = &new listing();
		$listingArray = $listingDAO->getOneRecordArray($listingID);
		
		$form->setDefaults($listingArray);
		
		$form->addRule('listingType', '"listing type" is a required field.', 'required', null, 'client');
		$form->addRule('title', '"listing title" is a required field.', 'required', null, 'client');
		$form->addRule('shortDescription', '"listing short description" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateListingEditFields($fields)
			{
				$f1 = $fields['listingType'];
				$f2 = $fields['title'];
				$f3 = $fields['shortDescription'];
				$f4 = $fields['postcode'];
				if (($f1 == '') || ($f2 == '') || ($f3 == '') || ($f4 == '')) {
					return array('title' => 'Listing type, title, short description and post code are required fields');
				}
				return true;
			}
			$form->addFormRule('validateListingEditFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$title = $form->getSubmitValue('title');
			$shortDescription = $form->getSubmitValue('shortDescription');
			$postcode = $form->getSubmitValue('postcode');

			# STEMMIG title and short description
			$objStem = new Stemmer();
			$stemmedTitleArr = $objStem->stem_list($title);
			$stemmedTitleStr = @implode(" ",$stemmedTitleArr);
			$stemmedShortDescrArr = $objStem->stem_list($shortDescription);
			$stemmedShortDescrStr = @implode(" ",$stemmedShortDescrArr);

			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$otherValues = array('stemmedTitle'=>$stemmedTitleStr,'stemmedShortDescription'=>$stemmedShortDescrStr,'createdDate'=>time());
			$formValues = array_merge($formValues,$otherValues);

			$listingDAO = &new listing();
			$listingDAO->updateRecord($listingID, $formValues);
			
			# REDIRECT TO CONFIRMATION PAGE
			$url= "index.php?_a=classifiedconfirm&listingID=$listingID";

			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;

		}
		else
		{
			$view=$action;
		}
		
		break;

	case "classifiedconfirm":
		#++++ GET POST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		
		#++++ GENERATE CONDITION
		$cond = " WHERE accountID='$accountID' AND listingID='$listingID'";

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$totRec = $listingDAO->N;

		#++++ SPONSORED BUSINESS LISTING
		$sponsBusDAO = getBusinessesFromListing($listingID,6); // [listingID, no of records to get (record limit) ]
		$totSponsRec = $sponsBusDAO->N;

		$view = $action;

		break;		

	case "classifiedconfirmed":
		#++++ GET POST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		
		#++++ GENERATE CONDITION
		$cond = " WHERE accountID='$accountID' AND listingID='$listingID' AND listingType='classified'";

		#++++ SQL QUERY FOR LIST
		$sql = "UPDATE listings SET status='pending' $cond";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		# REDIRECT TO THANKS PAGE
		$url= "index.php?_a=thanks&t=classified";

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;		


}
?>