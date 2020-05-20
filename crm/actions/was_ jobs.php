<?php
//exit if its a direct request to the page

switch ($action)
{

	case 'jobsearch':
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$distanceArray = Constants::getDistanceArray();
		// This array defines what is displayed each TAB heading
		$tabs = Constants::getSearchTabsArray();
		// goTab selects the active tab
		$goTab = 'job';

		// :: elements
		$form = &new HTML_QuickForm('listingsearch','POST','index.php?_a=jobsearch');
		$form->addElement('text', 'searchText',null, array('size'=>20));
		$form->addElement('text', 'postcode',null, array('size'=>5));
		$form->addElement('select', 'distance',null, $distanceArray);
		$form->addElement('checkbox', 'search_in_title',null,'');
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
			$url= "index.php?_a=jobslist&search_text=$searchText&post_code=$postcode&distance=$distance&pg=0";
			httpRedirect($url);
		}
		else
		{
			$view = $action;
		}

		break;

	case "jobslist":
		//----------------------------------------------------	
		setCookieValue('HTTP_REF',$_SERVER['HTTP_REFERER']); // SET REDIRECT URL ON LOGIN
		//----------------------------------------------------

		# GET SESSION DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$keywords = $dataBank->getVar('s_c_keywords');
		$accountID = $dataBank->getVar('s_c_account_id');
		$accountType = $dataBank->getVar('s_c_account_type');

		if($keywords=='')
			$keywords='Enter Keywords Here';

		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$msg = (int)@$_REQUEST['msg'];
		$distanceArray = Constants::getDistanceArray();
		// This array defines what is displayed each TAB heading
		$tabs = Constants::getSearchTabsArray();
		// goTab selects the active tab
		$goTab = 'jobs';

		// :: elements

		$form = &new HTML_QuickForm('jobslist','POST','index.php?_a=jobslist');
		$form->addElement('text', 'searchText',null, array('size'=>18,'class'=>'inputbox','id'=>'searchText','onFocus'=>'javascript:removeDefValue();'));
		$form->addElement('text', 'postcode',null, array('size'=>3,'maxlength'=>4,'class'=>'inputbox'));
		$form->addElement('select', 'distance',null, $distanceArray,array('class'=>'inputbox'));
		$account = substr($accountType,0,8);
		if($account=='business')
			$form->addElement('checkbox', 'search_in_service_area',null,' only jobs in my service area');
		$form->addElement('submit','search_button',' Search ',array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		$cookArray = array('postcode'=>$_COOKIE['c_postcode'],'distance'=>$_COOKIE['c_distance'],'searchText'=>$keywords,'search_in_service_area'=>$_COOKIE['c_search_in_service_area']);
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
			$searchInServiceArea = (int)@$form->getSubmitValue('search_in_service_area');
			
			# set in cookie
			setCookieValue("c_postcode",$postcode);
			setCookieValue("c_distance",$distance);
			setCookieValue("c_search_in_service_area",$searchInServiceArea);
			
			# set in session
			$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
			$dataBank->setVar('s_c_keywords',$searchText);
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url= "index.php?_a=jobslist&search_text=$searchText&post_code=$postcode&distance=$distance&search_in_service_area=$searchInServiceArea&pg=0";
			httpRedirect($url);
		}	
	
		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		$time=time();
		
		#++++ GET POST PARAMETERS
		$searchText = $_REQUEST['search_text'];
		$distance = $_REQUEST['distance'];
		$postcode = $_REQUEST['post_code'];
		$searchInServiceArea = $_REQUEST['search_in_service_area'];
		$pg =  (int)$_REQUEST['pg'];
		$type = $_REQUEST['type'];

		# retrieve the last search criteria
		if($type=='lastsearch') 
		{
			# GET SESSION DATA
			$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
			$keywords = $dataBank->getVar('s_c_keywords');
	
			#++++ FORM DEFAULTS / CONSTANTS -
			$searchText=$keywords;
			$distance=$_COOKIE['c_distance'];
			$postcode=$_COOKIE['c_postcode'];
			$searchInServiceArea=$_COOKIE['c_search_in_service_area'];
		}
		
		# IF SEARCH IS ONLY WITH SERIVE AREA THEN
		if($searchInServiceArea==1)
		{
			$postcodeArray = getMyServiePostcodes($accountID); // get all postcodes in his service area
		}
		# ELSE GET ALL THE POST CODES WITH RELEVANT DISTANCE FROM SPECIFIED POST CODE
		else
		{	
			$sql = "SELECT * FROM postcodeProximity WHERE postcode=$postcode AND proximity<=$distance";
			$postcodeObj = &new postcode();
			$postcodeDAO = $postcodeObj->getRecordsFromQuery($sql);
			while($postcodeDAO->fetch())
			{
				$postcodeArray[] =$postcodeDAO->postcode2;
			}
		}

		# GET POSTCODES IN COMMA SEPERATED VALUES
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
			//get with holding time for logged in user
		$approveTime = getWithHoldingPeriod($accountID);
		$cond = " WHERE status='approved' AND listingType='job' AND approveDate<=$approveTime";
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
		$pagingInfo = paging2($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond ORDER BY listings.createdDate DESC LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;

	case "jobadd":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------

		#++++ get session data
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$priorityTypeArr = Constants::getPriorityTypeArr();
		$responseTypeArr = Constants::getResponseTypeArr();
		$ResponseTypeArr= Constants::getResponseArr();
		$EstimatedTypeArr= Constants::getEstimatedArr();

		// :: elements
		$form = &new HTML_QuickForm('jobadd','POST','index.php');
		$form->addElement('hidden', '_a','jobadd');
		$form->addElement('hidden', 'status','temp');
		$form->addElement('hidden', 'listingType','job');
		$form->addElement('text','title',null, array('size'=>40));
		$form->addElement('textarea', 'shortDescription',null, array('cols'=>30,'rows'=>3));
//		$form->addElement('textarea', 'fullDescription',null, array('cols'=>30,'rows'=>6));
		$form->addElement('text', 'postcode',null, array('size'=>40));
//		$form->addElement('text','startDate',null,'readonly', array('size'=>40));
//		$form->addElement('text','endDate',null,'readonly', array('size'=>40));
		$form->addElement('select', 'priority',null, $priorityTypeArr);
		$form->addElement('select', 'responseType',null, $responseTypeArr);
		$form->addElement('text','contactDetails',null, array('size'=>40));
		$form->addElement('select', 'responseLimit',null, $ResponseTypeArr);
		$form->addElement('select', 'estimatedValue',null, $EstimatedTypeArr);				
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		
		//Should change this to Get the users postcode from their profile instead of using CURRENT_POSTCODE
		if (isSet($_COOKIE['c_postcode']) ) {
			$cookArray = array('postcode'=>$_COOKIE['c_postcode']);
			$form->setDefaults($cookArray);
		}

		#++++ JS validation rules
		$form->addRule('title', '"listing title" is a required field.', 'required', null, 'client');
		$form->addRule('shortDescription', '"listing short description" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateListingAddFields($fields)
			{
				$f1 = $fields['title'];
				$f2 = $fields['shortDescription'];
				$f3 = $fields['postcode'];
				$f4 = $fields['contactDetails'];
				$f5 = $fields['responseType'];
				$f6 = $fields['priority'];
				$f7 = $fields['startDate'];
				$f8 = $fields['endDate'];
								
				if ( ($f1 == '') || ($f2 == '') || ($f3 == '')) {
					return array('title' => 'Job title, short description, post code and contact details are required fields');
				}
				if ( ($f6=='must start by start date') and $f7=="") {
					return array('title' => 'Start Date required with priority "must start by start date"');
				}
				if ( ($f6=='must finish by finish date') and $f8=="") {
					return array('title' => 'End Date required with priority "must finish by finish date"');
				}
				if ( ($f4 == '') && ($f5=='direct') ) {
					return array('title' => 'contact details required with job type "Call Me"');
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

			# STEMMING title and short description
			$objStem = new Stemmer();
			$stemmedTitleArr = $objStem->stem_list_z($title);
			$stemmedTitleStr = @implode(" ",$stemmedTitleArr);
			$stemmedShortDescrArr = $objStem->stem_list_z($shortDescription);
			$stemmedShortDescrStr = @implode(" ",$stemmedShortDescrArr);

			// 1. insert data into listing table
			$formValues = $form->getSubmitValues();
			$otherValues = array('accountID'=>$accountID,'stemmedTitle'=>$stemmedTitleStr,'stemmedShortDescription'=>$stemmedShortDescrStr,'createdDate'=>time());
			$formValues = array_merge($formValues,$otherValues);


		//	echo dumper ($formValues);exit;
			$listingObj = &new listing();
			$listingID = $listingObj->insertRecord($formValues);

			# REDIRECT TO CONFIRMATION PAGE
			
			$url= "index.php?_a=jobconfirm&listingID=$listingID";
			httpRedirect($url);			
		}
		else
		{
			$view = $action;
		}
		
		break;
		
	case "jobedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$listingID = $_REQUEST['listingID'];
		
		if ($listingID < 1 ) {
			echo "ERROR - trying to do action=jobedit with invalid  listingID<br>";
			echo "JOBEDIT: listingID=$listingID <br>";
			exit;
		}

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$priorityTypeArr = Constants::getPriorityTypeArr();
		$responseTypeArr = Constants::getResponseTypeArr();
		$ResponseTypeArr= Constants::getResponseArr();
		$EstimatedTypeArr= Constants::getEstimatedArr();

		// :: elements
		$form = &new HTML_QuickForm('jobedit','POST','index.php');
		$form->addElement('hidden', '_a','jobedit');
		$form->addElement('hidden', 'listingID',$listingID);
		$form->addElement('hidden', 'status','temp');
		$form->addElement('hidden', 'listingType','job');
		$form->addElement('text','title',null, array('size'=>40));
		$form->addElement('textarea', 'shortDescription',null, array('cols'=>30,'rows'=>3));
//		$form->addElement('textarea', 'fullDescription',null, array('cols'=>30,'rows'=>6));
		$form->addElement('text', 'postcode',null, array('size'=>40));
//		$form->addElement('text','startDate',null,'readonly', array('size'=>40));
//		$form->addElement('text','endDate',null,'readonly', array('size'=>40));
		$form->addElement('select', 'priority',null, $priorityTypeArr);
		$form->addElement('select', 'responseType',null, $responseTypeArr);
		$form->addElement('text','contactDetails',null, array('size'=>40));
		$form->addElement('select', 'responseLimit',null, $ResponseTypeArr);
		$form->addElement('select', 'estimatedValue',null, $EstimatedTypeArr);				
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		//get default values from business table
		$listingDAO = &new listing();
		$listingArray = $listingDAO->getOneRecordArray($listingID);
		
		$form->setDefaults($listingArray);
		
		$form->addRule('title', '"listing title" is a required field.', 'required', null, 'client');
		$form->addRule('shortDescription', '"listing short description" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateListingEditFields($fields)
			{
				$f1 = $fields['title'];
				$f2 = $fields['shortDescription'];
				$f3 = $fields['postcode'];
				$f4 = $fields['contactDetails'];
				$f5 = $fields['responseType'];
				$f6 = $fields['priority'];
				$f7 = $fields['startDate'];
				$f8 = $fields['endDate'];
								
				if ( ($f1 == '') || ($f2 == '') || ($f3 == '')) {
					return array('title' => 'Job title, short description, post code and contact details are required fields');
				}
				if ( ($f6=='must start by start date') and $f7=="") {
					return array('title' => 'Start Date required with priority "must start by start date"');
				}
				if ( ($f6=='must finish by finish date') and $f8=="") {
					return array('title' => 'End Date required with priority "must finish by finish date"');
				}
				if ( ($f4 == '') && ($f5=='direct') ) {
					return array('title' => 'contact details required with job type "Call Me"');
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
			$stemmedTitleArr = $objStem->stem_list_z($title);
			$stemmedTitleStr = @implode(" ",$stemmedTitleArr);
			$stemmedShortDescrArr = $objStem->stem_list_z($shortDescription);
			$stemmedShortDescrStr = @implode(" ",$stemmedShortDescrArr);

			// 1. update data into table
			$formValues = $form->getSubmitValues();

			$otherValues = array('status'=>'pending','stemmedTitle'=>$stemmedTitleStr,'stemmedShortDescription'=>$stemmedShortDescrStr,'createdDate'=>time());
			$formValues = array_merge($formValues,$otherValues);

			$listingDAO = &new listing();
			$listingDAO->updateRecord($listingID, $formValues);
			# REDIRECT TO CONFIRMATION PAGE
			$url= "index.php?_a=jobconfirm&listingID=$listingID";

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
				
	case "jobconfirm":
		#++++ GET POST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
	
		#++++ GENERATE CONDITION
		$cond = " WHERE accountID='$accountID' AND listingID='$listingID'";

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

		break;

	case "jobconfirmed":
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

		if($accountDAO->trustLevel=='jobs' || $accountDAO->trustLevel=='all')
		{
			$time= time();// use for timestamp aprroval
			$status = 'approved';		
			$listingDAO->approveDate = time();
			
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
			$url= "index.php?_a=thanks&t=jobapproved";
		else
			$url= "index.php?_a=thanks&t=jobpending";

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;
		break;

	case "jobdelete":
		#++++ GET POST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		
		#++++ GENERATE CONDITION
		$cond = " WHERE accountID='$accountID' AND listingID='$listingID'";

		#++++ SQL QUERY FOR DELETE
		$sql = "DELETE FROM listings $cond";

		$listingObj = &new listing();
		$listingDAO = $listingObj->executeQuery($sql);

		# REDIRECT TO THANKS PAGE
		$url= "index.php?_a=thanks&t=jobdelete";

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;
		
	case "jobclose":
		#++++ GET POST PARAMETERS
		$listingID = $_REQUEST['listingID'];
		
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		
		#++++ GENERATE CONDITION
		$cond = " WHERE accountID='$accountID' AND listingID='$listingID'";

		#++++ SQL QUERY FOR DELETE
		$sql = "UPDATE listings SET status='closed' $cond";

		$listingObj = &new listing();
		$listingDAO = $listingObj->executeQuery($sql);

		# REDIRECT TO THANKS PAGE
		$url= "index.php?_a=thanks&t=jobclose";

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;

	case "myjobs": // used for domestic users to get their job list

		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = (int)$dataBank->getVar('s_c_account_id');
		$pg = @$_REQUEST['pg'];

		# GENERATE CONDITION
		$cond = "";
		$cond .= " WHERE listingType='job' AND status!='closed' AND accountID=$accountID";
		
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(DISTINCT(listingID)) AS tot FROM listings $cond";	
		//print "<br>$sql<br>";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond ORDER BY createdDate DESC LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;

	case "myclosedjobs": // used for domestic users to get their job list

		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = (int)$dataBank->getVar('s_c_account_id');
		$pg = @$_REQUEST['pg'];

		# GENERATE CONDITION
		$cond = "";
		$cond .= " WHERE listingType='job' AND status='closed' AND accountID=$accountID";
		
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(DISTINCT(listingID)) AS tot FROM listings $cond";	
		//print "<br>$sql<br>";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT * FROM listings $cond ORDER BY createdDate LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;

	case 'addtojoblist':

		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT
		//----------------------------------------------------

		# GET REQUIRED DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
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
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;
		
	case 'alerttojoblist':
	
		// when bus user responds to a job in their alertlist this process moves the job to their joblist

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
			//check for unique business in myList table
			$isBusinessInJobList = isBusinessInJobList($accountID,$listingID); // returns either listID or 0
			if($isBusinessInJobList<1) // if not in the table insert in myLists first
			{
				$myJobObj = new myJobs();
				$myJobValues = array('listingID'=>$listingID,'accountID'=>$accountID);
				$myJobObj->insertRecord($myJobValues);
				
				# DELETE RECORD FROM MYALERT TABLE
				$alertDAO = &new myAlerts();
				$alertDAO->deleteRecord($myAlertID);
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
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;

	
	
	case 'addtojobalertlist':

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
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;

	case "myjobalertlist": // used for business users to get their job list

		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = (int)$dataBank->getVar('s_c_account_id');
		$msg = (int) @$_REQUEST['msg'];
		$pg = (int) @$_REQUEST['pg'];
		
		# GENERATE CONDITION
		$cond  = "";
		$cond .= " Where listings.listingID=myAlerts.listingID";
		$cond .= " And myAlerts.accountID=businesses.businessID";
		$cond .= " AND businesses.accountID=$accountID";
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(*) AS tot FROM listings, myAlerts,businesses $cond";	
		//print "<br>$sql<br>";


		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT * FROM listings, myAlerts,businesses $cond ORDER BY listings.createdDate LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;
		
	case "respondtojob":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT
		//----------------------------------------------------

		# GET REQUIRED DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		$listingID = (int) @$_REQUEST['listingID'];
		$message = trim(stripslashes(@$_REQUEST['msg']));
		$from = trim(@$_REQUEST['from']);
		if($from=='MJL')
			$pageurl = "myjoblist";
		else
			$pageurl = "jobslist";

		$currURL = @$_REQUEST['currenturl'];

		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$currURL); // remove msg=N from url - if exists
		
		$pattern = '/&listingID=([0-9])*/';
		$url = preg_replace($pattern,'',$url); // remove listingID=N from url - if exists
		
		// if its not business user then ask for login
		if(!$businessUser) // if its business user then add job to joblist
		{
			$pattern = "/$pageurl/";
			$url = preg_replace($pattern,"$pageurl&msg=1&listingID=$listingID",$url); // add msg=1 to url - login first
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;
		}

		$isJobInServiceArea = isJobInServiceArea($accountID,$listingID);
		if(!$isJobInServiceArea)
		{
			$pattern = "/$pageurl/";
			$url = preg_replace($pattern,"$pageurl&msg=7&listingID=$listingID",$url); // add msg=7 to url - add postcode to service area
			httpRedirect($url);		
		}

		// if job is already responded by business then give error msg
		$isJobResponded = isJobResponded($accountID,$listingID);
		if($isJobResponded>0)
		{
			$pattern = "/$pageurl/";
			$url = preg_replace($pattern,"$pageurl&msg=5&listingID=$listingID",$url); // add msg=5 to url - already responded to job
			httpRedirect($url);		
		}

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		//get listing details
		$sql = "SELECT * FROM listings WHERE listingID=$listingID";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		// :: elements
		$form = &new HTML_QuickForm('respondtojob','POST','index.php?_a=respondtojob&listingID='.$listingID.'&from='.$from.'&currenturl='.urlencode($currURL));
		$form->addElement('static','jobtitle',null, $listingDAO->title);
		$form->addElement('static','shortDescription',null, $listingDAO->shortDescription);
//		$form->addElement('static','fullDescription',null, $listingDAO->fullDescription);
		$form->addElement('static','postcode',null, $listingDAO->postcode);
		if($listingDAO->responseType=='direct')
			$form->addElement('static','contactDetails',null, $listingDAO->contactDetails);
		else	
			$form->addElement('static','contactDetails',null, 'N/A');	
		$form->addElement('textarea','message',null, array('rows'=>5,'cols'=>30));
		$form->addElement('submit','submit_button',' Submit ',array('class'=>'inputbutton'));
		
		#++++ JS validation rules
		$form->addRule('message', '"message" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level
			function validateLoginFields($fields)
			{
					$f1 = $fields['message'];
					if (($f1 == '')) {
						return array('message' => 'message is required field');
					}
					return true;
			}
			$form->addFormRule('validateLoginFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$message = $form->getSubmitValue('message');

			//check for unique business in myJobs table
			$isBusinessInJobList = isBusinessInJobList($accountID,$listingID); // returns either listID or 0
			if($isBusinessInJobList<1) // if not in the table insert in myJobs first
			{
				$myJobObj = new myJobs();
				$myJobValues = array('listingID'=>$listingID,'accountID'=>$accountID);
				$myJobObj->insertRecord($myJobValues);
							}
			// check for unique job in joblist

			// insert data in response table
			$listingResponseValues = array('listingID'=>$listingID,'accountID'=>$accountID,'message'=>$message,'responseDateTime'=>time(),'status'=>'contact');
			$listingResponseObj = new listingresponse();
			$listingResponseObj->insertRecord($listingResponseValues);

			$pattern = "/$pageurl/";
			$url = preg_replace($pattern,"$pageurl&msg=3&listingID=$listingID",$url); // add msg=3 to url - successfully responded to job
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
			
	case "respondtojob-old":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT
		//----------------------------------------------------
		
		# GET REQUIRED DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		$listingID = (int) @$_REQUEST['listingID'];
		$message = trim(stripslashes(@$_REQUEST['msg']));
		$from = trim(@$_REQUEST['from']);
		if($from=='MJL')
			$pageurl = "myjoblist";
		else
			$pageurl = "joblist";
		
		$url = $_SERVER['HTTP_REFERER'];
		$str = $_SERVER['HTTP_REFERER'];
		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$str); // remove msg=N from url - if exists

		if($businessUser) // if its business user then add job to joblist
		{
		
			$isJobResponded = isJobResponded($accountID,$listingID);
			if($isJobResponded<1)
			{
				//check for unique business in myList table
				$isBusinessInJobList = isBusinessInJobList($accountID); // returns either listID or 0
				if($isBusinessInJobList<1) // if not in the table insert in myLists first
				{
					$myListObj = new mylist();
					$myListValues = array('accountID'=>$accountID);
					$listID = $myListObj->insertRecord($myListValues);
				}
				else // if its there set listID
				{
					$listID = $isBusinessInJobList;
				}
				
				// check for unique job in joblist
				$isJobInJobList = isJobInJobList($accountID,$listingID);
				if($isJobInJobList<1) //if not in the table insert in myList first
				{
					
					$list2mylistValues = array('listID'=>$listID,'listingID'=>$listingID,'status'=>'tagged','accountID'=>$accountID,'source'=>'manual');
					$list2mylistObj = new listings2mylist();
					$list2mylistObj->insertRecord($list2mylistValues);
				}
				
				// insert data in response table
				$listingResponseValues = array('listingID'=>$listingID,'accountID'=>$accountID,'message'=>$message,'responseDateTime'=>time(),'status'=>'contact');
				$listingResponseObj = new listingresponse();
				$listingResponseObj->insertRecord($listingResponseValues);
				
				$pattern = "/_a=$pageurl/";
				$url = preg_replace($pattern,"_a=$pageurl&msg=3",$url); // add msg=3 to url - successfully responded to job
			}	
			else // if job is already responded then redirect to joblist using and display proper msg
			{
				$pattern = "/_a=$pageurl/";
				$url = preg_replace($pattern,"_a=$pageurl&msg=5",$url); // add msg=5 to url - already responded to job
			}
		}
		else // if its not business uer then redirect to joblist using and display proper msg
		{
			$pattern = "/_a=$pageurl/";
			$url = preg_replace($pattern,"_a=$pageurl&msg=1",$url); // add msg=1 to url - login first
		}

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;
			
		break;	
	
	case "jobresponses":
		$pg =  (int)$_REQUEST['pg'];
		$listingID= (int) @$_REQUEST['listingID'];

		# GENERATE CONDITION
		$cond  = " WHERE listingResponses.listingID=$listingID";
		$cond .= " AND listingResponses.accountID=businesses.accountID";

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(*) AS tot FROM listingResponses, businesses $cond";
		//print "<br>$sql<br>";
		
		$listingObj = new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging2($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT businesses.businessName, listingResponses.responseDateTime,listingResponses.message,listingResponses.status  FROM listingResponses, businesses $cond ORDER BY responseDateTime LIMIT $start,$gap";
		//print "<br>$sql<br>";

		$responseObj = &new listing();
		$responseDAO = $responseObj->getRecordsFromQuery($sql);

		$sql = "SELECT * FROM listings WHERE listingID=$listingID";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();

		$view = $action;
		
		break;		

	case "myjobresponses":
		# GET DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		$listingID= (int) @$_REQUEST['listingID'];
		$pg =  (int)$_REQUEST['pg'];
		$msg = (int) @$_REQUEST['msg'];

		# GENERATE CONDITION
		$cond  = '';
		$cond  = " WHERE listingResponses.accountID=businesses.accountID ";
		$cond .= " AND listingResponses.listingID=listings.listingID ";
		$cond .= " AND listingResponses.listingID=$listingID AND listings.accountID=$accountID ";

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(*) AS tot FROM listingResponses, businesses, listings $cond";
		//print "<br>$sql<br>";
		
		$listingObj = new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging2($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT businesses.businessName, listingResponses.listingResponseID, listingResponses.responseDateTime,listingResponses.message,listingResponses.status  FROM listingResponses, businesses, listings $cond ORDER BY responseDateTime LIMIT $start,$gap";
		//print "<br>$sql<br>";

		$responseObj = &new listing();
		$responseDAO = $responseObj->getRecordsFromQuery($sql);

		$sql = "SELECT * FROM listings WHERE listingID=$listingID";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();

		$view = $action;
		
		break;
		
	case "jobresponsestatus":		
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

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;

	case "myjoblist": // used for business users to get their job list

		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = (int)$dataBank->getVar('s_c_account_id');
		$msg = (int) @$_REQUEST['msg'];
		$pg = (int) @$_REQUEST['pg'];

		# GENERATE CONDITION
		$cond  = "";
		$cond .= " where listings.listingID=myJobs.listingID";
		$cond .= " AND myJobs.accountID=$accountID";
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(*) AS tot FROM listings, myJobs $cond";	
		//print "<br>$sql<br>";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT * FROM listings, myJobs $cond ORDER BY listings.createdDate LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;

	case "removejobfromlist":		
		# GET DATA
		$listingID = (int) @$_REQUEST['listingID'];
		$accountID = (int) @$_REQUEST['accountID'];
		
		$list2mylistObj = new listings2mylist();
		$sql = "DELETE FROM myJobs WHERE listingID=$listingID and accountID=$accountID"; 
		$list2mylistDAO = $list2mylistObj->executeQuery($sql);

		$url = $_SERVER['HTTP_REFERER'];
		$str = $_SERVER['HTTP_REFERER'];
		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$str); // remove msg=N from url - if exists
		$pattern = '/_a=myjoblist/';
		$url = preg_replace($pattern,'_a=myjoblist&msg=6',$url); // add msg=6 to url - business removed from list

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;


	case "removealertfromlist":
	
		$listingID = (int) @$_REQUEST['listingID'];
		$accountID = (int) @$_REQUEST['accountID'];
		$myAlertID = (int) @$_REQUEST['myAlertID'];
				
		$alertDAO = &new myAlerts();
		$alertDAO->deleteRecord($myAlertID);

		$url = $_SERVER['HTTP_REFERER'];
		$str = $_SERVER['HTTP_REFERER'];
		$pattern = '/&msg=([0-9])*/';
		$url = preg_replace($pattern,'',$str); // remove msg=N from url - if exists
		$pattern = '/_a=myjobalertlist/';
		$url = preg_replace($pattern,'_a=myjobalertlist&msg=6',$url); // add msg=6 to url - business removed from list

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;	

	case 'addpostcodetoservicearea':

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
			//if job is already in service area then dont postcode in table again
			$isJobInServiceArea = isJobInServiceArea($accountID,$listingID);
			if($isJobInServiceArea)
			{
				$pattern = "/$pageurl/";
				$url = preg_replace($pattern,"$pageurl&msg=9&listingID=$listingID",$url); // add msg=9 to url - already postcode in the service area
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;			
			}

			# GET POSTCODE LIMIT
			$postcodeLimit = getPostcodeLimit($accountID,$accountType);
			
			# GET LOCATION ID
			$sql = "SELECT locationID FROM account2location WHERE accountID=$accountID";
			//print "<br>$sql<br>";
			$listingObj = &new listing();
			$listingDAO = $listingObj->getRecordsFromQuery($sql);
			$listingDAO->fetch();
			$locationID = $listingDAO->locationID;

			# GET NO OF POSTCODES FOR AN ACCOUNT
			$sql = "SELECT * FROM location2postcode WHERE locationID=$locationID";
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

				$sql = "INSERT INTO location2postcode (locationID, postcode) VALUES ($locationID,$postcode)";	
				//print "<br>$sql<br>";
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
		else // if its not business uer then redirect to joblist using and display proper msg
		{
			$pattern = "/_a=$pageurl/";
			$url = preg_replace($pattern,"_a=$pageurl&msg=1&listingID=$listingID",$url); // add msg=1 to url - login required
		}

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;
}
?>
