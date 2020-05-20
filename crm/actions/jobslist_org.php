<?php
		//----------------------------------------------------	
		setCookieValue('HTTP_REF',$_SERVER['HTTP_REFERER']); // SET REDIRECT URL ON LOGIN
		//----------------------------------------------------

		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$msg = (int)@$_REQUEST['msg'];
		$distanceArray = Constants::getDistanceArray();
		

		// :: elements

		$form = &new HTML_QuickForm('jobslist','GET','index.php?_a=jobslist');
		$form->addElement('text', 'searchText',null, array('size'=>24,'class'=>'inputbox','id'=>'searchText'));
		$form->addElement('text', 'postcode',null, array('size'=>3,'maxlength'=>4,'class'=>'inputbox'));
		$form->addElement('select', 'distance',null, $distanceArray,array('class'=>'inputbox'));
		$form->addElement('hidden','_a','jobslist');
		$form->addElement('submit','search_button',' Find ',array('class'=>'inputbutton'));
	
		if(substr($accountType,0,8) == 'business'){
			$form->addElement('checkbox', 'search_in_service_area',null,' only jobs in my service area');
		}
		

		#++++ JS validation rules
		//$form->addRule('searchText', '"search text" is a required field.', 'required', null, 'client');
		//$form->addRule('distance', '"distance" is a required field.', 'required', null, 'client');
		//$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		//$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if (1==2 && $form->validate()) 
		{
			#++++ get the form values
			$searchText = $form->getSubmitValue('searchText');
			$distance = $form->getSubmitValue('distance');
			$postcode = $form->getSubmitValue('postcode');
			$searchInServiceArea = (int)@$form->getSubmitValue('search_in_service_area');
			
			# set in cookie
			setCookieValue("c_postcode",$postcode);
			setCookieValue("c_distance",$distance);
			setCookieValue('c_search_in_service_area',$searchInServiceArea);
			
			
			# set in session
			$dataBank->setVar('s_c_keywords',$searchText);
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			//$url= "_a=jobslist&search_text=$searchText&post_code=$postcode&distance=$distance&search_in_service_area=$searchInServiceArea&pg=0";
			//httpRedirect($url);
			
		}	
	
	
		// Now work out where the request was generated from ad set search params acourdingly
				
		$pg =  (int)$_REQUEST['pg'];
		$type = $_REQUEST['type'];
		
		#++++ GET POST PARAMETERS ### these are not set when selected from MAIN MENU
		if (!isSet($_REQUEST['search_button'])) { // not a search form submittal so use default values
			
			$keywords = $dataBank->getVar('s_c_keywords');
			$searchText=$keywords;
			$distance=$_COOKIE['c_distance'];
			$postcode=$_COOKIE['c_postcode'];
			$searchInServiceArea=$_COOKIE['c_search_in_service_area'];
		}
		else 
		{
			// search request came from search form so use its values
			$searchText = @$_REQUEST['searchText'];
			$distance = @$_REQUEST['distance'];
			$postcode = @$_REQUEST['postcode'];
			$searchInServiceArea = @$_REQUEST['search_in_service_area'];
		}
		

		# retrieve the last search criteria
		if($type=='lastsearch' && $accountID > 0) 
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
		
		// okay to set the form defaults now
		#++++ FORM DEFAULTS / CONSTANTS -
		$cookArray = array('postcode'=>$postcode,'distance'=>$distance,'searchText'=>$searchText,'search_in_service_area'=>$searchInServiceArea);
		
		$form->setDefaults($cookArray);
		
		
		
		
		
		
		
		
		//echo dumper($form);
		
		# IF SEARCH IS ONLY WITH SERVICE AREA THEN accoutID must be valid i.e > 0 and accounttype must be a BUSiness one
		if($searchInServiceArea==1 && $accountID > 0 && strtoupper(substr($accountType,0,3)) == 'BUS')
		{
			$postcodeArray = getMyServiceArea($accountID); // get all postcodes in his service area
		}
		# ELSE GET ALL THE POST CODES WITH RELEVANT DISTANCE FROM SPECIFIED POST CODE
		else
		{	
			
			if ( preg_match("/\b\d{4}\b/",$postcode) ) {// check for a four digit number at least 
				$sql = "SELECT * FROM postcodeProximity WHERE postcode=$postcode AND proximity<=$distance";
				$postcodeObj = &new postcode();
				$postcodeDAO = $postcodeObj->getRecordsFromQuery($sql);
				while($postcodeDAO->fetch())
				{
					$postcodeArray[] =$postcodeDAO->postcode2;
				}
			}
				
		}

		# GET POSTCODES IN COMMA SEPERATED VALUES
		if(count($postcodeArray) > 0)
		{
			$postcodeList = @implode(",",$postcodeArray);
				# add first postcode
			$postcodeList .=",$postcode";
				# add single quote for conditions
			$postcodeList = str_replace(",","','",$postcodeList);
		}
		else
		{
			$postcodeList = $postcode;
		}
		
		# MAKE STEMMED SEARCH STRING	
		$stemmedSearchText = getStemmedString($searchText);			

		#++++ GENERATE CONDITION
			//get with holding time for logged in user
		$approveTime = getWithHoldingPeriod($accountID);
		$cond = " WHERE listings.status='approved' AND listingType='job' ";
		//$cond .= " AND approveDate<=$approveTime ";
		if(trim($searchText)!="")
		{
			//$cond .= " AND ( MATCH(title,shortDescription) AGAINST ('".$searchText."')";
			//$cond .= " OR MATCH(stemmedTitle,stemmedShortDescription) AGAINST ('".$stemmedSearchText."') ) ";
			$cond .= " AND MATCH(stemmedTitle,stemmedShortDescription) AGAINST ('".$stemmedSearchText."')  ";
		}
		if(preg_match("/^\d\d\d\d$/",trim($postcode))) {
			$cond .= " AND postcode IN ('".$postcodeList."')";	
		} else {
			log_error("should make this msg 10 as postcode looks screwy");
			$msg = 10;
		}

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(DISTINCT(listingID)) AS tot FROM listings $cond";	
		//print "<br>$sql<br>";

		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec = $listingDAO->tot;

		# GET SPONSORED BUSINESS LISTINGS USING KEYWORDS
		//$sponsBusDAO = getBusinessByKeyword($postcodeList,$searchText);
		//$totSponsRec =$sponsBusDAO->N;

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
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
	
	
		$view = $action;
 ?>