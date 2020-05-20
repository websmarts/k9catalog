<?php
		//----------------------------------------------------	
		setCookieValue('HTTP_REF',$_SERVER['HTTP_REFERER']); // SET REDIRECT URL ON LOGIN
		//----------------------------------------------------

		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$msg = (int)@$_REQUEST['msg'];
		$distanceArray = Constants::getDistanceArray();
		

		// :: elements

		$form = &new HTML_QuickForm('localadslist','GET','');
		$form->addElement('text', 'searchText',null, array('size'=>24,'class'=>'inputbox','id'=>'searchText'));
		$form->addElement('text', 'postcode',null, array('size'=>3,'maxlength'=>4,'class'=>'inputbox'));
		$form->addElement('select', 'distance',null, $distanceArray,array('class'=>'inputbox'));
		$form->addElement('hidden','_a','localadslist');
		$form->addElement('submit','search_button',' Search ',array('class'=>'inputbutton'));
	
		
		

		#++++ JS validation rules
		//$form->addRule('searchText', '"search text" is a required field.', 'required', null, 'client');
		//$form->addRule('distance', '"distance" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
		
		function validateForm() {
			
			return preg_match("/\d{4}/",$_REQUEST['postcode']);
		}

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
			
		}
		
		// okay to set the form defaults now
		#++++ FORM DEFAULTS / CONSTANTS -
		$formDefaults = array('postcode'=>$postcode,'searchText'=>$searchText);
		
		$form->setDefaults($formDefaults);
		$s =& $form->getElement('distance');
		$s->setSelected($distance);
		
		
			
			if ( preg_match("/\b\d{4}\b/",$postcode) ) {// check for a four digit number at least 
				//DB_DataObject::debugLevel(1);
				$proxobj = DB_DataObject::factory('PostcodeProximity');
				$proxobj->whereAdd("(postcode=$postcode or postcode2=$postcode) AND proximity<=$distance");
				$proxobj->find();
				while($proxobj->fetch())
				{
					if ($proxobj->postcode == $postcode) {
						$postcodeArray[] =$proxobj->postcode2;
					} elseif ($proxobj->postcode2 == $postcode) {
						$postcodeArray[] = $proxobj->postcode;
					}
				}
			}
			
			
	

		# GET POSTCODES IN COMMA SEPERATED VALUES
		//echo dumper($postcodeArray);
		if(count($postcodeArray) > 0)
		{
			$postcodeList = @implode(",",$postcodeArray);
			
			# add single quote for conditions;
			$postcodeList = str_replace(",","','",$postcodeList);
	
		}
		else
		{
			$postcodeList = $postcode;
		}
		
		# MAKE STEMMED SEARCH STRING	
			

		#++++ GENERATE CONDITION
			//get with holding time for logged in user
	
		$cond = " localads.status='approved' ";
		
		if(trim($searchText)!="")
		{
		
			$cond .= " AND (title like'%".$searchText."%' or description like '%".$searchText."%')";
		}
		if(preg_match("/^\d\d\d\d$/",trim($postcode))) {
			$cond .= " AND localads.postcode IN ('".$postcodeList."')";	
		} else {
			//echo "postcode looks screwy:$postcode ";
			$msg = 10;
		}

		# TOTAL RECORD COUNT FOR PAGING
		//DB_DataObject::debugLevel(1);
		$localadobj = DB_DataObject::factory('localads');
	  $accountobj = DB_DataObject::factory('accounts');
		$localadobj->whereAdd($cond);
		
		$totRec = $localadobj->find();
		
		#++++ PAGING PARAMETERS
		
		$gap = 10;
		
			
			$page = $pg;
			$start = $pg*$gap;

			$end = $start + $gap;
			if($end>$totRec)
				$end = $totRec;
			$pagingInfo = paging2($totRec,$start,$end,$page,$gap);
		 
		
		$sql = 	" SELECT localads.*,accounts.accountType,businesses.businessName ".
						" FROM localads ".
						" LEFT JOIN accounts on accounts.accountID = localads.accountID ".
						" LEFT JOIN businesses on businesses.accountID = localads.accountID ".
						" WHERE $cond ".
						" ORDER BY localads.created DESC ".
						" LIMIT ${start} , $gap ";
						
	//	DB_DataObject::debugLevel(1);
		$localadobj->query($sql);
		

	//	DB_DataObject::debugLevel(0);
		
		// finished action part
		$view = $action;
 ?>