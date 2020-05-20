<?php

//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{
		
	case "campaigns":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------

		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		$time=time();
		
		#++++ GET REQUEST PARAMETERS
		$msg = (int)$_REQUEST['msg'];
		$pg =  (int)$_REQUEST['pg'];

		# GENERATE CONDITION
		$cond  = "";
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT COUNT(*) AS tot FROM campaigns $cond";
		//print "<br>".$sql."<br>";
		$campaignObj = &new campaign();
		$campaignDAO = $campaignObj->getRecordsFromQuery($sql);
		$campaignDAO->fetch();
		$totRec =$campaignDAO->tot;

		#++++ PAGING PARAMS
		$page = $pg;
		$gap = 20;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT * FROM campaigns $cond ORDER BY startDate DESC LIMIT $start,$gap";
		//print "<br>".$sql."<br>";
		$campaignObj = &new campaign();
		$campaignDAO = $campaignObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;

	case "campaignadd":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		// :: elements
		$form = &new HTML_QuickForm('campaignadd','POST','index.php?_a=campaignadd');
		$form->addElement('text','campaignName',null, array('size'=>40));
		$form->addElement('text', 'description',null, array('size'=>70));
		$form->addElement('text', 'startDate',null, array('size'=>15));
		$form->addElement('text', 'endDate',null, array('size'=>15));
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -

		#++++ JS validation rules
		$form->addRule('campaignName', '"campaign name" is a required field.', 'required', null, 'client');
		$form->addRule('description', '"description" is a required field.', 'required', null, 'client');
		$form->addRule('startDate', '"start date" is a required field.', 'required', null, 'client');
		$form->addRule('endDate', '"end date" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateCampaignAddFields($fields)
			{
				$f1 = $fields['campaignName'];
				$f2 = $fields['description'];
				$f3 = $fields['startDate'];
				$f4 = $fields['endDate'];
				if (($f1 == '') && ($f2 == '') && ($f3 == '') && ($f4 == '')) {
					return array('campaignName' => 'campaign name, description, start date and end date are required fields');
				}
				return true;
			}
			$form->addFormRule('validateCampaignAddFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');

		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			// 1. insert data into table
			$formValues = $form->getSubmitValues();
			$campaignObj = &new campaign();
			$campaignID = $campaignObj->insertRecord($formValues);

			# REDIRECT TO CAMPAIGNS
			$url=  "index.php?_a=campaigns&msg=1";
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

	case "campaignedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$campaignID = $_REQUEST['campaignID'];

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		// :: elements
		$form = &new HTML_QuickForm('busedit','POST','index.php?_a=campaignedit&campaignID='.$campaignID);
		$form->addElement('text','campaignName',null, array('size'=>40));
		$form->addElement('text', 'description',null, array('size'=>70));
		$form->addElement('text', 'startDate',null, array('size'=>20));
		$form->addElement('text', 'endDate',null, array('size'=>20));
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		//get default values from business table
		$campaignObj = &new campaign();
		$campaignArray = $campaignObj->getOneRecordArray($campaignID);
		$form->setDefaults($campaignArray);
		
		#++++ JS validation rules
		$form->addRule('campaignName', '"campaign name" is a required field.', 'required', null, 'client');
		$form->addRule('description', '"description" is a required field.', 'required', null, 'client');
		$form->addRule('startDate', '"start date" is a required field.', 'required', null, 'client');
		$form->addRule('endDate', '"end date" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateCampaignEditFields($fields)
			{
				$f1 = $fields['campaignName'];
				$f2 = $fields['description'];
				$f3 = $fields['startDate'];
				$f4 = $fields['endDate'];
				if (($f1 == '') && ($f2 == '') && ($f3 == '') && ($f4 == '')) {
					return array('campaignName' => 'campaign name, description, start date and end date are required fields');
				}
				return true;
			}
			$form->addFormRule('validateCampaignEditFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$campaignObj = &new campaign();
			$campaignObj->updateRecord($campaignID, $formValues);
			
			# REDIRECT TO CAMPAIGNS
			$url=  "index.php?_a=campaigns&msg=2";
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

	case "campaigndelete":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID -
		$campaignID = (int)$_REQUEST['campaignID'];
		
		# DELETE RECORD FROM CAMPAIGN TABLE
		$campaignDAO = &new campaign();
		$campaignDAO->deleteRecord($campaignID);

		# REDIRECT 
		$url=  "index.php?_a=campaigns&msg=3";

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;		

	case "assigncampaign":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList('yes'); //get category list from functions
		// :: elements
		$form = &new HTML_QuickForm('campaignadd','POST','index.php?_a=assigncampaign');
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "1"));
		$form->addElement('text', 'postcode',null, array('size'=>4,'maxlength'=>4));
		$form->addElement('text', 'distance',null, array('size'=>4,'maxlength'=>5));
		$form->addElement('submit','submit_button',' Search Business ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		#++++ JS validation rules
		$form->addRule('distance', '"distance" requires only numbers.', 'numeric', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
			#++++ Validate Login :: Form level 
			function validateAssignCampaignFields($fields)
			{
				$f1 = $fields['catID'];
				$f2 = $fields['postcode'];
				$f3 = $fields['distance'];
				if ( ($f1 == 0) && ( ($f2 == '') || ($f3 == '') ) ) {
					return array('catID' => 'either category or postcode-distance are required fields');
				}
				return true;
			}
			$form->addFormRule('validateAssignCampaignFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');

		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
				#++++ get the form values
			$catID = $form->getSubmitValue('catID');
			$postcode = $form->getSubmitValue('postcode');
			$distance = $form->getSubmitValue('distance');

			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url=  "index.php?_a=buslist2&business_cat=$catID&business_postcode=$postcode&distance=$distance&pg=0";
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

	case "addbustocampaign":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$campaignOptions = getCampaignList(); //get category list from functions
		$url = $_SERVER['QUERY_STRING'];
		// :: elements
		$form = &new HTML_QuickForm('addbustocampaign','POST',"index.php?".$url);
		$form->addElement('select','campaignID',null, $campaignOptions);
		$form->addElement('submit','submit_button',' Add Businesses To This Campaign ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		#++++ JS validation rules
		$form->addRule('campaignID', '"campaign" is a required field.', 'required', null, 'client');
			#++++ Validate Login :: Form level 
			function validateAssignCampaignFields($fields)
			{
				$f1 = (int)$fields['campaignID'];
				if ($f1 == 0) {
					return array('campaignID' => 'campaign is a required fields');
				}
				return true;
			}
			$form->addFormRule('validateAssignCampaignFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');

		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ GET POST PARAMETERS
			$msg = (int)$_REQUEST['msg'];
			$catID = (int)$_REQUEST['business_cat'];
			$postcode = $_REQUEST['business_postcode'];
			$distance = $_REQUEST['distance'];
			$pg = (int)$_REQUEST['pg'];
			$campaignID = (int)$_REQUEST['campaignID'];
	
			#++++ GENERATE CONDITION
			$cond = " WHERE 1";
			if(trim($postcode)!="" && trim($distance)!="")
			{
				$sql = "SELECT * FROM postcodeProximity WHERE postcode=$postcode AND proximity<=$distance";
				//print "<br>$sql<br>";
				$postcodeObj = &new postcode();
				$postcodeDAO = $postcodeObj->getRecordsFromQuery($sql);
				while($postcodeDAO->fetch())
				{
					$postcodeArray[] =$postcodeDAO->postcode2;
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
	
				$cond .= " AND b.postcode IN ('" . $postcodeList . "')";
			}
	
			if($catID>0)
			{
				$cond .= " AND bc.categoryID=". $catID." AND bc.businessID=b.businessID";
			}

			# TOTAL RECORD COUNT FOR PAGING
			if($catID>0)
				$sql = "SELECT DISTINCT(b.businessID) FROM businesses b, business2category bc $cond";
			else
				$sql = "SELECT DISTINCT(b.businessID) FROM businesses b $cond";
			//print "<br>$sql<br><br>";
			
			$busObj = &new business();
			$busObj2 = &new business();
			$busDAO = $busObj->getRecordsFromQuery($sql);
			$tot=0;
			$charArray = create4CharsArray($campaignID); //generate 4 digits promoID array for a Campaign
			$i=0;
			while($busDAO->fetch())
			{
				$businessID = $busDAO->businessID;
				# CHECK WHETHER ITS ASSIGNED TO ANY CAMPAIGNED OR NOT
				$isBusAssignedToCampaign = isBusAssignedToCampaign($businessID); // RETURNS 0 - IF YES  - ELSE 1
				if($isBusAssignedToCampaign<1)
				{
					$promoID = $charArray[$i]; // assign dynamic promoId which we have generated for Campaign
					$sql = "INSERT INTO business2campaign (businessID, campaignID, promoID) VALUES ($businessID, $campaignID, '$promoID')";
					//print "<br>$sql<br>";
					$busObj2->executeQuery($sql);
					$i++;
				}
				else
				{
					$tot++;
				}
			}

			if($tot>0) // this tot var is used to display the proper msg
				$msg=2; // means few or all businesses are assigned
			else
				$msg=1; // not yet assigned
				
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url = "index.php?_a=buslist2&msg=$msg&business_cat=$catID&business_postcode=$postcode&distance=$distance&pg=0";
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
		
	case "campaigntocsv":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$campaignOptions = getCampaignList(); //get category list from functions
		$msg = (int)$_REQUEST['msg'];
		$fileName = $_REQUEST['file'];
		// :: elements
		$form = &new HTML_QuickForm('campaigntocsv','POST',"index.php?_a=campaigntocsv");
		$form->addElement('select','campaignID',null, $campaignOptions);
		$form->addElement('submit','submit_button',' Import Campaign Businesses to CSV ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		#++++ JS validation rules
		$form->addRule('campaignID', '"campaign" is a required field.', 'required', null, 'client');
			#++++ Validate Login :: Form level 
			function validateAssignCampaignFields($fields)
			{
				$f1 = (int)$fields['campaignID'];
				if ($f1 == 0) {
					return array('campaignID' => 'campaign is a required fields');
				}
				return true;
			}
			$form->addFormRule('validateAssignCampaignFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');

		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			// 1. insert data into table
			$formValues = $form->getSubmitValues();
			$campaignID = $formValues['campaignID'];
			$sql = "SELECT * FROM businesses b, business2campaign bc WHERE bc.campaignID=$campaignID AND b.businessID=bc.businessID";
			//print "<br>$sql<br>";
			$businessObj = &new business();
			$businessDAO = $businessObj->getRecordsFromQuery($sql);

			$time = time();
			$dir = "csv/";
			$file = "$time.csv";
			$filepath = $dir . $file;
			$fp = fopen($filepath, 'w');
			$seperator = ",";
			$str = "businessID $seperator accountID $seperator businessName $seperator address1 $seperator address2 $seperator";
			$str .= "city $seperator postcode $seperator phoneAreacode $seperator phoneNumber $seperator cellPhoneNumber $seperator";
			$str .= "faxNumber $seperator keywords $seperator stemmedKeywords $seperator url $seperator ranking\n";
			fwrite($fp, $str);
			while($businessDAO->fetch())
			{
				$str  = $businessDAO->businessID . "$seperator";
				$str .= $businessDAO->accountID . "$seperator";
				$str .= $businessDAO->businessName . "$seperator";
				$str .= $businessDAO->address1 . "$seperator";
				$str .= $businessDAO->address2 . "$seperator";
				$str .= $businessDAO->city . "$seperator";
				$str .= $businessDAO->postcode . "$seperator";
				$str .= $businessDAO->phoneAreacode . "$seperator";
				$str .= $businessDAO->phoneNumber . "$seperator";
				$str .= $businessDAO->cellPhoneNumber . "$seperator";
				$str .= $businessDAO->faxNumber . "$seperator";
				$str .= $businessDAO->url . "$seperator";
				$str .= $businessDAO->ranking . "$seperator";
				$str .= "\n";
				fwrite($fp, $str);
			}
			fclose($fp);
			
			$updateValues = array("status"=>'closed');
			$campaignObj = &new campaign();
			$campaignObj->updateRecord($campaignID, $updateValues);

			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url = "index.php?_a=campaigntocsv&msg=1&file=$file";
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
}
?>