<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}
switch ($action)
{
	case "registerbusiness1":
		// *-*-*-*-*-* GET SESSION VALUE
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$registeredID = (int)$dataBank->getVar('s_c_reg_account_id');
		$registeredBusID = (int)$dataBank->getVar('s_c_reg_bus_id');
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		// :: elements
		$form = &new HTML_QuickForm('registerbusiness1','POST','index.php?_a=registerbusiness1');
        $form->addElement('static', 'pageTitle', null,'STEP 1 / STEP 6');		
		//$form->addElement('hidden', 'status','temp');
		$form->addElement('text','userName',null, array('size'=>40));
		$form->addElement('password', 'password',null, array('size'=>40));
		$form->addElement('text', 'email',null, array('size'=>40));
		if($registeredBusID=="")
			$form->addElement('text', 'promoID',null, array('size'=>40));
//		else
//			$form->addElement('text', 'promoID',null, array('size'=>40),readonly);
		$form->addElement('submit','submit_button',' Next >> ', array('class'=>'inputbutton'));
		#++++ FORM DEFAULTS / CONSTANTS -
		$accountObj = &new account();
		$accountArray = $accountObj->getOneRecordArray($registeredID);
		$businessObj = &new business();
		$businessArray = $businessObj->getOneRecordArray($registeredBusID);
		if($registeredID<1) //set business type by default
		{
			$defaultArr = array('status'=>'temp','accountType'=>'businessuser');
			$defaultArr = $defaultArr;
		}	
		else
		{
			$accountArray['status']='temp';
			$defaultArr = $accountArray;
/*			if($registeredBusID!=""){
				$sql= "Select b.promoID as promoID from businesses as a inner join business2campaign as b on a.businessID=b.businessID where a.businessID='$registeredBusID'";//Query for getting Business details using PromoID
				$res = mysql_query($sql) or die($sql.mysql_error());
				while ($row = mysql_fetch_array($res))
				{
					$promoID=$row['promoID'];
				}
			}
				
			$otherArr = array('promoID'=>$promoID);
			$defaultArr = @array_merge($defaultArr,$otherArr);
*/
		}	
		$form->setDefaults($defaultArr);
		#++++ JS validation rules
		$form->addRule('userName', '"user name" is a required field.', 'required', null, 'client');
		$form->addRule('password', '"password" is a required field.', 'required', null, 'client');
		$form->addRule('email', '"email" is a required field.', 'required', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateRegistrationFields1($fields)
			{
				$f1 = $fields['userName'];
				$f2 = $fields['password'];
				$f3 = $fields['email'];
				if (($f1 == '') || ($f2 == '') || ($f3 == '')) {
					return array('title' => 'user name, password and email are required fields');
				}
				return true;
			}
			$form->addFormRule('validateRegistrationFields1');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			$newValues = array('status'=>'temp','modified'=>time());
			$formValues = @array_merge($formValues,$newValues);
			
			// 1. insert data into accounts table
			$accountObj = &new account();
			$userName = $formValues['userName'];
			if($accountObj->uniqueAccount($userName,$registeredID))  //check unique username
			{ 
		/* New functionality is added here.
			Users do enter a PROMOID so we can look up the business details and populate the fields in STEP2 – then the user has to do is check that the details are correct.
		*/
				if($formValues['promoID']!=""){
					$sql= "Select a.businessID as registeredBusID,a.accountID as accounID from businesses as a inner join business2campaign as b on a.businessID=b.businessID where b.promoID='$formValues[promoID]'";//Query for getting Business details using PromoID
					$res = mysql_query($sql) or die($sql.mysql_error());
					while ($row = mysql_fetch_array($res))
					{
						$registeredBusID=$row['registeredBusID'];
						$accID=$row['accounID'];
					}
				}
				if($registeredID<1) //if record is not inserted yet then insert it
				{
					if($formValues['promoID']!=""){
						if($accID>0){
							$url= "index.php?_a=thanks&t=already_register";
							header('HTTP/1.1 301 Moved Permanently');
							header("Location: " . $url);
							header('Connection: close');
							exit;
						}
					}
					$accountObj = &new account();
					$accountID = $accountObj->insertRecord($formValues);
					//2. store account id in session to process the next and back functions
					$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
					$dataBank->setVar('s_c_reg_account_id',$accountID);
				if($registeredBusID!="" or $registeredBusID!=0){
						$dataBank->setVar('s_c_reg_bus_id',$registeredBusID);
						$promovalue = array('accountID'=>(int)$dataBank->getVar('s_c_reg_account_id'));
						$businessObj = &new business();
						$businessObj->updateRecord($registeredBusID,$promovalue);
					}
				}
				else  //if record is inserted then update data
				{
					if($formValues['promoID']!=""){
						if(($registeredBusID!="" or $registeredBusID!=0) and $accID==0){
							$dataBank->setVar('s_c_reg_bus_id',$registeredBusID);
							$promovalue = array('accountID'=>(int)$dataBank->getVar('s_c_reg_account_id'));
							$businessObj = &new business();
							$businessObj->updateRecord($registeredBusID,$promovalue);
						}
						if($accID>0){
							$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
							$dataBank->deleteOtherData('s_c_reg_bus_id');
						}
					}	
					$accountObj = &new account();
					$accountObj->updateRecord($registeredID,$formValues);
					
				}
				
				# REDIRECT TO NEXT PAGE
				$url= "index.php?_a=registerbusiness2";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;
			}
			else
			{
				$form->setElementError('userName', 'User Name Already Exist!'); // set the page message
			}
		}
		$view = $action;
		break;
		
	
	case "registerbusiness2":
		// *-*-*-*-*-* GET SESSION VALUE
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$registeredID = (int)$dataBank->getVar('s_c_reg_account_id');
		$registeredBusID = (int)$dataBank->getVar('s_c_reg_bus_id');
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$rankingTypeOptions = array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5');
		// :: elements
		$form = &new HTML_QuickForm('registerbusiness2','POST','index.php?_a=registerbusiness2');
        $form->addElement('static', 'pageTitle', null,'STEP 2 / STEP 6');
		$form->addElement('text', 'businessName',null, array('size'=>40));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'address2',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>40));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>40));
		$form->addElement('text', 'faxNumber',null, array('size'=>40));
		$form->addElement('text', 'url',null, array('size'=>40));
		$form->addElement('select', 'ranking',null, $rankingTypeOptions);
		
		$form->addElement('submit','submit_button',' Next >> ', array('class'=>'inputbutton'));
		$form->addElement('button','back_button',' << Back ', array('class'=>'inputbutton','onClick'=>'javascript:window.location.href=\'index.php?_a=registerbusiness1\''));
		#++++ FORM DEFAULTS / CONSTANTS -
		$businessObj = &new business();
		$businessArray = $businessObj->getOneRecordArray($registeredBusID);
		$form->setDefaults($businessArray);
		#++++ JS validation rules
		$form->addRule('businessName', '"first name" is a required field.', 'required', null, 'client');
		$form->addRule('address1', '"address line 1" is a required field.', 'required', null, 'client');
		$form->addRule('city', '"city" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
						
			#++++ Validate Login :: Form level 
			function validateRegistrationFields2($fields)
			{
				$f1 = $fields['businessName'];
				$f2 = $fields['address1'];
				$f3 = $fields['city'];
				$f4 = $fields['postcode'];				
				if (($f1 == '') || ($f2 == '') || ($f3 == '') || ($f4 == '')) {
					return array('title' => 'business name, address line 1, city and post code are required fields');
				}
				return true;
			}
			$form->addFormRule('validateRegistrationFields2');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			if($registeredBusID<1) //if record is not inserted yet then insert it
			{
				//1. insert data into businesses table
				$accountArray = array('accountID'=>$registeredID);
				$allValues = array_merge($accountArray,$formValues);
				$businessObj = &new business();
				$businessID = $businessObj->insertRecord($allValues);
	
				//2. store business id in session to process the next and back functions
				$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
				$dataBank->setVar('s_c_reg_bus_id',$businessID);
			}
			else  //if record is inserted then update data
			{
				$businessObj = &new business();
				$businessObj->updateRecord($registeredBusID,$formValues);
			}	
			
			# REDIRECT TO NEXT PAGE
			$url= "index.php?_a=registerbusiness3";
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
	
	case "registerbusiness3":
		// *-*-*-*-*-* GET SESSION VALUE
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$registeredID = (int)$dataBank->getVar('s_c_reg_account_id');
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		// :: elements
		$form = &new HTML_QuickForm('registerbusiness3','POST','index.php?_a=registerbusiness3');
        $form->addElement('static', 'pageTitle', null,'STEP 3 / STEP 6');		
		$form->addElement('radio','accountType',null,'Business User','businessuser');
		$form->addElement('radio','accountType',null,'Business User1','businessuser1');
		$form->addElement('radio','accountType',null,'Business User2','businessuser2');
		$form->addElement('submit','submit_button',' Next >> ', array('class'=>'inputbutton'));
		$form->addElement('button','back_button',' << Back ', array('class'=>'inputbutton','onClick'=>'javascript:window.location.href=\'index.php?_a=registerbusiness2\''));
		#++++ FORM DEFAULTS / CONSTANTS -
		$accountObj = &new account();
		$accountArray = $accountObj->getOneRecordArray($registeredID);
		if($accountArray['accountType']=='sysadmin')
			$accountArray['accountType']='businessuser';
		
		$form->setDefaults($accountArray);
		#++++ JS validation rules
		$form->addRule('accountType', '"account type" is a required field.', 'required', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateRegistrationFields5($fields)
			{
				$f1 = $fields['accountType'];
				if ($f1 == '') {
					return array('accountType' => 'account type is required fields');
				}
				return true;
			}
			$form->addFormRule('validateRegistrationFields5');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			$newValues = array('status'=>'temp','modified'=>time());
			$formValues = @array_merge($formValues,$newValues);
			
			// 1. insert data into accounts table
			$accountObj = &new account();
			$accountObj = &new account();
			$accountObj->updateRecord($registeredID,$formValues);
			# REDIRECT TO NEXT PAGE
			$url = "index.php?_a=registerbusiness4";
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
	case "registerbusiness4":
		// *-*-*-*-*-* GET SESSION VALUE
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$registeredID = (int)$dataBank->getVar('s_c_reg_account_id');
		$registeredBusID = (int)$dataBank->getVar('s_c_reg_bus_id');
		$distance = $dataBank->getVar('s_c_distance');
		$postcode = $dataBank->getVar('s_c_postcode');
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$accountObj = &new account();
		$accountArray = $accountObj->getOneRecordArray($registeredID);
		if($accountArray['accountType']=='businessuser')
			$distanceArray = array('25'=>25);
		else
			$distanceArray = Constants::getDistanceArray();	
		// :: elements
		$form = &new HTML_QuickForm('registerbusiness4','POST','index.php?_a=registerbusiness4');
        $form->addElement('static', 'pageTitle', null,'STEP 4 / STEP 6');
		$form->addElement('select', 'distance',null, $distanceArray);
		$form->addElement('text', 'postcode',null, array('size'=>4,'maxlength'=>4));
		$form->addElement('submit','submit_button',' Next >> ', array('class'=>'inputbutton'));
		$form->addElement('button','back_button',' << Back ', array('class'=>'inputbutton','onClick'=>'javascript:window.location.href=\'index.php?_a=registerbusiness3\''));
		#++++ FORM DEFAULTS / CONSTANTS -
		$businessObj = &new business();
		$businessArray = $businessObj->getOneRecordArray($registeredBusID);
		if ($postcode=='')
			$postcode = $businessArray['postcode'];
		$defaultArray = array('postcode'=>$postcode,'distance'=>$distance);
		$form->setDefaults($defaultArray);
		#++++ JS validation rules
		$form->addRule('distance', '"distance" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"postcode" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
						
			#++++ Validate Login :: Form level 
			function validateRegistrationFields4($fields)
			{
				$f1 = $fields['distance'];
				$f2 = $fields['postcode'];
				if (($f1 == '') || ($f2 == '')) {
					return array('distance' => 'distance and postcode are required fields');
				}
				return true;
			}
			$form->addFormRule('validateRegistrationFields4');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
		
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			//1. store distance in session to process the next and back functions
			$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
			$dataBank->setVar('s_c_distance',$formValues['distance']);
			$dataBank->setVar('s_c_postcode',$formValues['postcode']);
		
			/*
			//process goes here...
			$businessObj = &new business();
			$businessObj->updateRecord($registeredBusID,$defaultArr);
			*/
			# REDIRECT TO NEXT PAGE
			$url= "index.php?_a=registerbusiness5";
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
	case "registerbusiness5":
		// *-*-*-*-*-* GET SESSION VALUE
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$registeredID = (int)$dataBank->getVar('s_c_reg_account_id');
		$registeredBusID = (int)$dataBank->getVar('s_c_reg_bus_id');
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$maxCharsArray = Constants::getMaxCharsArray();			
		$accountObj = &new account();
		$accountArray = $accountObj->getOneRecordArray($registeredID);
		$accountType = $accountArray['accountType'];
		// set description limit base on the user type
		if($accountType == 'businessuser2')
			$descrCharsLimit = $maxCharsArray[255];
		elseif($accountType == 'businessuser1')
			$descrCharsLimit = $maxCharsArray[150];
		else
			$descrCharsLimit = $maxCharsArray[50];
		//get current keyword length
		$businessObj = &new business();
		$businessArray = $businessObj->getOneRecordArray($registeredBusID);
		$descrCharsLimit1 = ($descrCharsLimit - strlen($businessArray['keywords']) );
		// :: elements
		$form = &new HTML_QuickForm('registerbusiness5','POST','index.php?_a=registerbusiness5');
        $form->addElement('static', 'pageTitle', null,'STEP 5 / STEP 6');
		$form->addElement('static', 'descrCharsLimit1', null,$descrCharsLimit1);
		$form->addElement('static', 'descrCharsLimit2', null,$descrCharsLimit);
		$form->addElement('textarea', 'keywords',null, array('cols'=>40,'rows'=>5,'id'=>'keywords','onKeyUp'=>"javascript:calculateRaminingChars(".$descrCharsLimit.")"));
		
		$form->addElement('submit','submit_button',' Next >> ', array('class'=>'inputbutton'));
		$form->addElement('button','back_button',' << Back ', array('class'=>'inputbutton','onClick'=>'javascript:window.location.href=\'index.php?_a=registerbusiness4\''));
		#++++ FORM DEFAULTS / CONSTANTS -
		$form->setDefaults($businessArray);
		#++++ JS validation rules
		$form->addRule('keywords', '"keywords" is a required field.', 'required', null, 'client');
						
			#++++ Validate Login :: Form level 
			function validateRegistrationFields3($fields)
			{
				$f1 = $fields['keywords'];
				if (($f1 == '')) {
					return array('keywords' => 'keywords is a required field');
				}
				return true;
			}
			$form->addFormRule('validateRegistrationFields3');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
		
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			if(strlen($formValues['keywords'])>$descrCharsLimit)
			{
					$form->setElementError('keywords', 'keyword has more than required characters!'); // set the page message				
			}
			else
			{
				$stemmedKeywords = getStemmedString($formValues['keywords']);
				$newArr = array('stemmedKeywords'=>$stemmedKeywords);
				$defaultArr = @array_merge($formValues,$newArr);	
	
				//update keywords field
				$businessObj = &new business();
				$businessObj->updateRecord($registeredBusID,$defaultArr);
	
				# REDIRECT TO NEXT PAGE
				$url= "index.php?_a=registerbusinessconfirm";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;
			}
		}
		$view = $action;
		
		break;
		
	case "registerbusinessconfirm":
		// *-*-*-*-*-* GET SESSION VALUE
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$registeredID = (int)$dataBank->getVar('s_c_reg_account_id');
		$registeredBusID = (int)$dataBank->getVar('s_c_reg_bus_id');
		$distance = $dataBank->getVar('s_c_distance');
		$postcode = $dataBank->getVar('s_c_postcode');
		$accountObj = &new account();
		$accountArray = $accountObj->getOneRecordArray($registeredID);
		
		$businessObj = &new business();
		$businessArray = $businessObj->getOneRecordArray($registeredBusID);
		$view = $action;
		
		break;
	case "registerbusinessprocess":
		// *-*-*-*-*-* GET SESSION VALUE
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$registeredID = (int)$dataBank->getVar('s_c_reg_account_id');
		$registeredBusID = (int)$dataBank->getVar('s_c_reg_bus_id');
		$distance = $dataBank->getVar('s_c_distance');
		$postcode = $dataBank->getVar('s_c_postcode');
		
		$accountObj = &new account();
		$accountArray = array('modified'=>time(),'status'=>'pending');
		$accountObj->updateRecord($registeredID,$accountArray);
		
		$accountArray = $accountObj->getOneRecordArray($registeredID);
		
		$businessObj = &new business();
		$businessArray = $businessObj->getOneRecordArray($registeredBusID);
		$locationObj = &new location();
		$locationArray = array('parentID'=>'0','locationName'=>'businessServiceArea','locationType'=>'business');
		$locationID = $locationObj->insertRecord($locationArray);
		
		$acc2locObj = &new account2location();
		$acc2locArray = array('locationID'=>$locationID,'accountID'=>$registeredID);
		$acc2locID = $acc2locObj->insertRecord($acc2locArray);
		# GET ALL THE POST CODES WITH RELEVANT DISTANCE FROM SPECIFIED POST CODE
		$postcodeArray[] = $postcode;		
		$sql = "SELECT * FROM postcodeProximity WHERE postcode=$postcode AND proximity<=$distance";
		$postcodeObj = &new postcode();
		$postcodeDAO = $postcodeObj->getRecordsFromQuery($sql);
		while($postcodeDAO->fetch())
		{
			$postcodeArray[] = $postcodeDAO->postcode2;
		}
		
		$loc2pcObj = &new location2postcode();
		for($i=0;$i<sizeof($postcodeArray);$i++)
		{
			$loc2pcArray = array('locationID'=>$locationID,'postcode'=>$postcodeArray[$i]);
			$loc2pcID = $loc2pcObj->insertRecord($loc2pcArray);
		}
		# SEND ACTIVATION EMAIL WITH ACTIVATION KEY
		$activationKey = generateActivationKey($registeredID);
		$name = $accountArray['userName'];
		$to = $accountArray['email'];
		$activationLink = HOST."/index.php?_a=businessactivation&key=".htmlentities(urlencode($activationKey));
		$accountObj->sendActivationEmail($activationLink, $name, $to);
		# REMOVE ALL DATA FROM SESSION
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$dataBank->deleteData();
		# REDIRECT TO THANKS PAGE
		$url= "index.php?_a=thanks&t=business";
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;
		
		break;
		
	case "businessactivation":
		// *-*-*-*-*-* GET FORM VALUES
		$key = trim($_REQUEST['key']);
		if($key!='')
		{
			$accountID = decodeActivationKey($key);
			if ($accountID>0)
			{
				$sql = "SELECT * FROM accounts WHERE accountID=$accountID AND status='pending'";
				$accountObj = &new account();
				$accountDAO = $accountObj->getRecordsFromQuery($sql);
				if($accountDAO->N >0)
				{
					$accountArray = array('modified'=>time(),'status'=>'active');
					$accountObj->updateRecord($accountID,$accountArray);
					$flag = true;
				}
			}
			else
				$flag = false;	
		}
		# REDIRECT TO THANKS PAGE
		if($flag)
			$url= "index.php?_a=thanks&t=businessactivation";
		else
			$url= "index.php?_a=thanks&t=businessactivationproblem";	
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;
		
		break;
	case 'businesssearch':
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$distanceArray = Constants::getDistanceArray();
		// :: elements
		$form = &new HTML_QuickForm('businesssearch','POST','index.php?_a=businesssearch');
		$form->addElement('text', 'searchText',null, array('size'=>20));
		$form->addElement('text', 'postcode',null, array('size'=>5));
		$form->addElement('select', 'distance',null, $distanceArray);
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
			function validateBusinessSearchFields($fields)
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
			$form->addFormRule('validateBusinessSearchFields');
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
			$url= "index.php?_a=businesslist&search_text=$searchText&post_code=$postcode&distance=$distance&pg=0";
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
	case "businesslist":
		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		$time=time();
		
		#++++ GET POST PARAMETERS
		$searchText = $_REQUEST['search_text'];
		$distance = $_REQUEST['distance'];
		$postcode = $_REQUEST['post_code'];
		$pg =  (int)$_REQUEST['pg'];
		##+++SEARCH FORM STARTS
		##====================
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$distanceArray = Constants::getDistanceArray();
		// :: elements
		$form = &new HTML_QuickForm('businesslist','POST',"index.php?_a=businesslist&search_text=$searchText&post_code=$postcode&distance=$distance&pg=0");
		$form->addElement('text', 'searchText',null, array('size'=>20));
		$form->addElement('text', 'postcode',null, array('size'=>5));
		$form->addElement('select', 'distance',null, $distanceArray);
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
			function validateBusinessSearchFields($fields)
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
			$form->addFormRule('validateBusinessSearchFields');
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
			$url= "index.php?_a=businesslist&search_text=$searchText&post_code=$postcode&distance=$distance&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
		##====================
		##+++SEARCH FORM ENDS
		# GET ALL THE POST CODES WITH RELEVANT DISTANCE FROM SPECIFIED POST CODE
		$sql = "SELECT * FROM postcodeProximity WHERE postcode=$postcode AND proximity<=$distance";
		//print "<br>$sql<br>";
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
		$cond = "";
		if(trim($searchText)!="")
		{
			//$cond .= " WHERE ( MATCH(keywords) AGAINST ('".$searchText."')";
			$cond .= " WHERE ( MATCH(stemmedKeywords,businessName) AGAINST ('".$stemmedSearchText."')";
			$cond .= " OR MATCH(stemmedKeywords,businessName) AGAINST ('".$searchText."') )";
			//$cond .= " OR MATCH(businessName) AGAINST ('".$stemmedSearchText."') ) ";
		}
		if(trim($postcode)!="")
			$cond .= " AND postcode IN ('".$postcodeList."')";	
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(DISTINCT(businessID)) AS tot FROM businesses $cond";
		//print "<br>$sql<br>";
		$businessObj = &new business();
		$businessDAO = $businessObj->getRecordsFromQuery($sql);
		$businessDAO->fetch();
		$totRec = $businessDAO->tot;
		if($totRec<1) //IF NO RECORDS FOUND USING MATCH-CASE ON BUSINESS NAME TRY LIKE OPERATOR
		{
			#++++ GENERATE CONDITION AGAIN
			$cond = "";
			if(trim($searchText)!="")
			{
				$cond .= " WHERE ( MATCH(keywords) AGAINST ('".$searchText."')";
				$cond .= " OR MATCH(stemmedKeywords) AGAINST ('".$stemmedSearchText."')";
				$cond .= " OR businessName LIKE '".$searchText."%'";
				$cond .= " OR businessName LIKE '".$stemmedSearchText."%' )";
			}	
			if(trim($postcode)!="")
				$cond .= " AND postcode IN ('".$postcodeList."')";	
						
			# TOTAL RECORD COUNT FOR PAGING
			$sql = "SELECT count(DISTINCT(businessID)) AS tot FROM businesses $cond";
			//print "<br>$sql<br>";
			$businessObj = &new business();
			$businessDAO = $businessObj->getRecordsFromQuery($sql);
			$businessDAO->fetch();
			$totRec = $businessDAO->tot;
		}
		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);
		#++++ SQL QUERY FOR LIST
		$sql = "SELECT DISTINCT *,MATCH(stemmedkeywords,businessname) AGAINST ('$stemmedSearchText') AS score FROM businesses $cond ORDER BY score DESC LIMIT $start,$gap";
		//print "<br>$sql<br>";
		$businessObj = &new business();
		$businessDAO = $businessObj->getRecordsFromQuery($sql);
		
		$view = $action;
		break;
		
	case "mybuscategories":
		//DB_DataObject::debugLevel(1);
		
		$businessObj = &new business();
		$sql = "select * from businesses where accountID=".$accountID;	
		$businessDAO = $businessObj->getRecordsFromQuery($sql);
		$businessDAO->fetch();
		$businessArray = $businessDAO->toArray();
		$businessID = $businessArray['businessID'];
		
		$form = &new HTML_QuickForm('mybuscategories','POST','index.php');
   	$form->addElement('hidden', '_a','mybuscategories');
   	
		// Business categories
		$buscatids = getBusCatIDs($businessDAO->businessID);
		//echo dumper($buscatids);		
		//$catlist = getCatList('yes');
		
		$catlist = getTopCategories();
		

		$form->addElement('select','catids',null,$catlist,array('multiple'=>true,'size'=>24) );
		$e =& $form->getElement('catids');
		$e->setSelected($buscatids);
		
		//$form->setDefaults($businessArray);
		
		if ($form->validate() ) {
			
				//1. Update business2category
				//echo dumper($form);
				
				// delete existing categories
				$bus2cat = new business2category();
				$sql = "delete from {$bus2cat->_dao->__table} where businessID=".$businessID;
				
				$bus2cat->_dao->query($sql);
				
				// add new cats
				$cats = $form->getSubmitValue('catids');
				//echo dumper($cats);
				// DB_DataObject::debugLevel(1);
				if (is_array($cats) && count($cats) > 0) {
					foreach ($cats as $catID) {
						$bus2cat->_dao->businessID=$businessID;
						$bus2cat->_dao->categoryID=$catID;
						$bus2cat->_dao->insert();	
					}				
				}
				$query = "_a=mybuscategories";
				httpRedirect($query);				
		}
		
		$view=$action;
	
	break;
	case "mybusdetails":
	
		
		// Business details
		$businessObj = &new business();
		$sql = "select * from businesses where accountID=".$accountID;	
		$businessDAO = $businessObj->getRecordsFromQuery($sql);
		$businessDAO->fetch();
		$businessArray = $businessDAO->toArray();
	
		
		
		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		//$rankingTypeOptions = array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5');
		//$distanceArray = Constants::getDistanceArray();
		
		
		
		
		// :: elements
		$form = &new HTML_QuickForm('mybusdetails','POST','index.php');
   	$form->addElement('hidden', '_a','mybusdetails');
		$form->addElement('text', 'businessName',null, array('size'=>30));
		$form->addElement('text', 'address1',null, array('size'=>30));
		$form->addElement('text', 'address2',null, array('size'=>30));
		$form->addElement('text', 'city',null, array('size'=>30));
		$form->addElement('text', 'postcode',null, array('size'=>4));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>4));
		$form->addElement('text', 'phoneNumber',null, array('size'=>12));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>12));
		$form->addElement('text', 'faxNumber',null, array('size'=>12));
		$form->addElement('text', 'url',null, array('size'=>30));
		

		
		// Business categories
		//$data['buscatnames'] = getBusCatNames($businessDAO->businessID);
		
		
		//$catlist = getCatList('yes');

		//$form->addElement('select','catids',null,$catlist,array('multiple'=>true,'size'=>6) );
		//$e =& $form->getElement('catids');
		//$e->setSelected($buscatids);
	
		$form->setDefaults($businessArray);
		
		
		
		
		
		#++++ JS validation rules
		$form->addRule('businessName', '"Business name" is a required field.', 'required', null, 'client');
		$form->addRule('address1', '"address line 1" is a required field.', 'required', null, 'client');
		$form->addRule('city', '"city" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
						
			#++++ Validate Login :: Form level 
			function validateRegistrationFields2($fields)
			{
				$f1 = $fields['businessName'];
				$f2 = $fields['address1'];
				$f3 = $fields['city'];
				$f4 = $fields['postcode'];			
								
				if (($f1 == '') || ($f2 == '') || ($f3 == '') || ($f4 == '')) {
					return array('title' => 'business name, address line 1, city and post code are required fields');
				}
				return true;
			}
			$form->addFormRule('validateRegistrationFields2');
			
			
			// just tto stop negative integers being submitted for alertJobValues
			function checkNumValue($a) {
				if ($a < 0) {
					return 0;
				} else {
					return $a;
				}
			}
			
			
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		// should apply filter to remove $ signs from price values
		//$form->applyFilter('alertJobValueMin','checkNumValue');
		//$form->applyFilter('alertJobValueMax','checkNumValue');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			
			// stem the key and ignore words
			//$otherValues['stemmedKeywords'] = getStemmedString($formValues['keywords']);
			//$otherValues['stemmedNotKeywords'] = getStemmedString($formValues['notKeywords']);		

			if(1) //if record is not inserted yet then insert it
			{
				//1. update data into businesses table
				$otherValues['accountID']= $accountID;			
				$allValues = array_merge($otherValues,$formValues);
				
				$businessObj = &new business();
				$businessObj->_dao->accountID = $accountID;
				$businessObj->_dao->find(); // numrows should be one;
				$businessObj->_dao->fetch();
			
				$businessID = $businessObj->_dao->businessID;
			
				// DB_DataObject::debugLevel(5);
				
				if ($businessObj->_dao->N ==1 ) {	
					$businessObj->_dao->setFrom($allValues);
					$res = $businessObj->_dao->update();			
					if ($res > 1 || $res='' ) {
						log_error("Updating business table failed where action=$action - res=$res ");
					}
				} else {
					log_error("found ".$businessObj->_dao->N." businesses with accountID=$accountID when there should only be one!!!");
				}
			}	
			
			# REDIRECT TO NEXT PAGE
			$url= "index.php?_a=mybusdetails";
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
		
		case 'mybusservicearea':
		
			
			$form = &new HTML_QuickForm('myform','POST','index.php');
			$form->addElement('hidden','_a','mybusservicearea');	
			$form->addElement('textarea','serviceArea',null, array('rows'=>4,'cols'=>44));
			$serviceAreaArray= getMyServiceArea($accountID);	
			$sa =& $form->getElement('serviceArea');
			$sa->setValue(implode(",",$serviceAreaArray));
			$sa->setWrap('soft');
		
			if ($form->validate() ) {
				#++++ get the form values
				$formValues = $form->getSubmitValues();
				
				//1. Update Service Area
				preg_match_all ("/\b\d{4}\b/",$formValues['serviceArea'],$m);
				$postcodes = array_unique($m[0]);
				// delete existing enties
				$a2pObj = new account2postcode();
				$sql = "delete from {$a2pObj->_dao->__table} where accountID=$accountID";
				$a2pObj->_dao->query($sql);
				
				//2. Insert the new values
				if (is_array($postcodes) && count($postcodes) > 0 ) {	
						$a2pObj->_dao->accountID=$accountID;
					foreach ($postcodes as $postcode) {
						
						$a2pObj->_dao->postcode = $postcode;
						$a2pObj->_dao->insert();
					}
				}
				# REDIRECT TO NEXT PAGE
				$url= "index.php?_a=mybusservicearea";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;
				
				
			}
		
		
		$view = $action;
		break;
		
		case 'mybusleadmanager':
			// Business details
			$businessObj = &new business();
			$sql = "select * from businesses where accountID=".$accountID;	
			$businessDAO = $businessObj->getRecordsFromQuery($sql);
			$businessDAO->fetch();
			$businessArray = $businessDAO->toArray();
			
			$alertOptionsArray = Constants::getAlertOptionsArray();
		
			$form = &new HTML_QuickForm('myform','POST','index.php');
			$form->addElement('hidden','_a','mybusleadmanager');
			$form->addElement('textarea', 'keywords',null, array('rows'=>2,'cols'=>44));
			$form->addElement('textarea', 'notKeywords',null, array('rows'=>2,'cols'=>44));
			$form->addElement('text','alertJobValueMin',null);
			$form->addElement('text','alertJobValueMax',null);
			$form->addElement('select','sendAlerts',null,$alertOptionsArray);
			$e =& $form->getElement('sendAlerts');
		
		
		

		
			// Set sendAlerts value
			$e =& $form->getElement('sendAlerts');
			$e->setSelected($businessDAO->sendAlerts);
			
			// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
			$form->applyFilter('__ALL__', 'trim');
			$form->applyFilter('__ALL__', 'strip_tags');
			// should apply filter to remove $ signs from price values
			$form->applyFilter('alertJobValueMin','checkNumValue');
			$form->applyFilter('alertJobValueMax','checkNumValue');
			
			$form->setDefaults($businessArray);
			
			if ($form->validate() ) {
				#++++ get the form values
				$formValues = $form->getSubmitValues();
			
				// stem the key and ignore words
				$otherValues['stemmedKeywords'] = getStemmedString($formValues['keywords']);
				$otherValues['stemmedNotKeywords'] = getStemmedString($formValues['notKeywords']);
				
				if(1) //if record is not inserted yet then insert it
					{
						//1. update data into businesses table
						$otherValues['accountID']= $accountID;			
						$allValues = array_merge($otherValues,$formValues);
						
						$businessObj = &new business();
						$businessObj->_dao->accountID = $accountID;
						$businessObj->_dao->find(); // numrows should be one;
						$businessObj->_dao->fetch();
					
						$businessID = $businessObj->_dao->businessID;
					
						// DB_DataObject::debugLevel(5);
						
						if ($businessObj->_dao->N ==1 ) {	
							$businessObj->_dao->setFrom($allValues);
							$res = $businessObj->_dao->update();			
							if ($res > 1 || $res='' ) {
								log_error("Updating business table failed where action=$action - res=$res ");
							}
						} else {
							log_error("found ".$businessObj->_dao->N." businesses with accountID=$accountID when there should only be one!!!");
						}
					}	
					
					# REDIRECT TO NEXT PAGE
					$url= "index.php?_a=mybusleadmanager";
					header('HTTP/1.1 301 Moved Permanently');
					header("Location: " . $url);
					header('Connection: close');
					exit;	
				
			} else {
				$view = $action;
			}
		
}
?>