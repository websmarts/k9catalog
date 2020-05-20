<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}
switch ($action)
{
	case "bussearch":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList(''); //get category list from functions		
		// :: elements
		$form = &new HTML_QuickForm('bussearch','POST','index.php?_a=bussearch');
		$form->addElement('text','businessName',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>4));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>40));
		$form->addElement('text', 'faxNumber',null, array('size'=>40));
		$form->addElement('submit','search_button',' Find ',array('class'=>'inputbutton'));
		$form->addElement('link','add_bus_link',null,'index.php?_a=busadd','Add New Business');
		
			#++++ Validate Login :: Form level 
			function validateBizSearchFields($fields)
			{
				$f1 = $fields['businessName'];
				$f2 = $fields['catID'];
				$f3 = $fields['address1'];
				$f4 = $fields['city'];
				$f5 = $fields['postcode'];
				$f6 = $fields['phoneAreacode'];
				$f7 = $fields['phoneNumber'];
				$f8 = $fields['cellPhoneNumber'];
				$f9 = $fields['faxNumber'];
				if (($f1 == '') && ($f2 == '') && ($f3 == '') && ($f4 == '') && ($f5 == '') && ($f6 == '') && ($f7 == '') && ($f8 == '') && ($f9 == '')) {
					return array('businessName' => 'Atleast one field required for a search');
				}
				return true;
			}
			$form->addFormRule('validateBizSearchFields');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		
		if ($form->validate()){
				#++++ get the form values
			$businessName = $form->getSubmitValue('businessName');
			$businessCat = @implode("-",$form->getSubmitValue('catID'));
			$businessAdd = $form->getSubmitValue('address1');
			$businessCity = $form->getSubmitValue('city');
			$businessPostCode = $form->getSubmitValue('postcode');
			$businessPhoneArea = $form->getSubmitValue('phoneAreacode');
			$businessPhone = $form->getSubmitValue('phoneNumber');
			$businessCell = $form->getSubmitValue('cellPhoneNumber');
			$businessFax = $form->getSubmitValue('faxNumber');
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url=  "index.php?_a=buslist&business_name=$businessName&business_cat=$businessCat&business_address=$businessAdd&business_city=$businessCity&business_postcode=$businessPostCode&business_phone_area=$businessPhoneArea&business_phone=$businessPhone&business_cell=$businessCell&business_fax=$businessFax&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		} else 
		{
			$view = $action;
		}
		
		break;
	case "buslist":
		$view=$action;
		break;
		
	case "buslist2":	
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
		
		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		$time=time();
		
		#++++ GET POST PARAMETERS
		$msg = (int)$_REQUEST['msg'];
		$catID = (int)$_REQUEST['business_cat'];
		$postcode = $_REQUEST['business_postcode'];
		$distance = $_REQUEST['distance'];
		$pg =  (int)$_REQUEST['pg'];
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
			$sql = "SELECT count(DISTINCT(b.businessID)) AS tot FROM businesses b , business2category bc $cond";
		else
			$sql = "SELECT count(DISTINCT(b.businessID)) AS tot FROM businesses b $cond";	
		//print "<br>$sql<br>";
		//flush(); 
		$query = &new business();
		$busDAO = $query->getRecordsFromQuery($sql);
		$busDAO->fetch();
		$totRec =$busDAO->tot;
		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 20;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);
		#++++ SQL QUERY FOR LIST
		if($catID>0)
			$sql = "SELECT DISTINCT b.* FROM businesses as b, business2category bc $cond ORDER BY businessName LIMIT $start,$gap";
		else
			$sql = "SELECT DISTINCT b.* FROM businesses as b $cond ORDER BY businessName LIMIT $start,$gap";
		//print "<br>$sql<br>";
		//flush();
		//exit;
		$query = &new business();
		$busDAO = $query->getRecordsFromQuery($sql);
		
	case "busadd":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList(''); //get category list from functions
		// :: elements
		$form = &new HTML_QuickForm('busadd','POST','index.php?_a=busadd');
		$form->addElement('text','businessName',null, array('size'=>40));
		$form->addElement('static', 'businessCategory',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>40));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>40));
		$form->addElement('text', 'faxNumber',null, array('size'=>40));
		$form->addElement('submit','edit_button',' Save ', array('class'=>'inputbutton'));
		#++++ FORM DEFAULTS / CONSTANTS -
		$busArray = array('businessCategory'=>"NONE");
		$form->setDefaults($busArray);		
		#++++ JS validation rules
		$form->addRule('businessName', '"business title" is a required field.', 'required', null, 'client');
		$form->addRule('address1', '"address" is a required field.', 'required', null, 'client');
		$form->addRule('city', '"city" is a required field.', 'required', null, 'client');
		$form->addRule('catID', '"category" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateBizAddFields($fields)
			{
				$f1 = $fields['businessName'];
				$f2 = $fields['businessCategory'];
				$f3 = $fields['address1'];
				$f4 = $fields['city'];
				if (($f1 == '') && ($f2 == '') && ($f3 == '') && ($f4 == '')) {
					return array('businessName' => 'Business name, category, address and city are required fields');
				}
				return true;
			}
			$form->addFormRule('validateBizAddFields');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$businessName = $form->getSubmitValue('businessName');
			$businessCat = $form->getSubmitValue('businessCategory');
			$catID = $form->getSubmitValue('catID');
			$businessAdd = $form->getSubmitValue('address1');
			$businessCity = $form->getSubmitValue('city');
			$businessPostCode = $form->getSubmitValue('postcode');
			$businessPhoneArea = $form->getSubmitValue('phoneAreacode');
			$businessPhone = $form->getSubmitValue('phoneNumber');
			$businessCell = $form->getSubmitValue('cellPhoneNumber');
			$businessFax = $form->getSubmitValue('faxNumber');
			// 1. insert data into table
			$formValues = $form->getSubmitValues();
			$bus = &new business();
			$businessID = $bus->insertRecord($formValues);
			// 2. insert new data in business2category
			$buscatDAO = &new business2category();
			for($i=0;$i<sizeof($catID);$i++)
			{
				$sql = "INSERT INTO business2category (businessID, categoryID) VALUES ($businessID,".$catID[$i].")";
				$buscatDAO->executeQuery($sql);
			}
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url=  "index.php?_a=buslist&msg=1&business_name=$businessName&business_cat=$businessCat&business_address=$businessAdd&business_city=$businessCity&business_postcode=$businessPostCode&business_phone_area=$businessPhoneArea&business_phone=$businessPhone&business_cell=$businessCell&business_fax=$businessFax&pg=0";
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
	case "busedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$businessID = $_REQUEST['businessID'];
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList(''); //get category list from functions
		// :: elements
		$form = &new HTML_QuickForm('busedit','POST','index.php?_a=busedit&businessID='.$businessID);
		$form->addElement('text','businessName',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>6));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>4));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>40));
		$form->addElement('text', 'faxNumber',null, array('size'=>40));
		$form->addElement('text', 'serviceArea',null, array('size'=>40));
		$form->addElement('text', 'keywords',null, array('size'=>40));
		$form->addElement('text', 'notKeywords',null, array('size'=>40));
		$form->addElement('submit','edit_button',' Save ',array('class'=>'inputbutton'));
		#++++ FORM DEFAULTS / CONSTANTS -
		//get default values from business table
		$bus = &new business();
		$busArray = $bus->getOneRecordArray($businessID);
		
		//get default catids from business2category table
		$catIDs='';
		$buscat = &new business2category();
		$sql = "SELECT * FROM business2category WHERE businessID=$businessID";
		$buscatDAO = $buscat->getRecordsFromQuery($sql);
		while ($buscatDAO->fetch())
		{
			$catIDs .= $buscatDAO->categoryID;
			$catIDs .= ",";
		}
		$buscatArray = array('catID'=>"$catIDs");
		
		// set some defaults
		$defaults = array_merge($busArray,$buscatArray);
		
		// get the account Service Area Postcodes
		$serviceArea =& new account2postcode;
		$postcodes = $serviceArea->getAccountServiceArea($busArray['accountID']);
		
		
	
		// set service area defaults
		$defaults['serviceArea'] = trim($postcodes);
		
		$form->setDefaults($defaults);
		
		#++++ JS validation rules
		$form->addRule('businessName', '"business title" is a required field.', 'required', null, 'client');
		$form->addRule('address1', '"address" is a required field.', 'required', null, 'client');
		$form->addRule('city', '"city" is a required field.', 'required', null, 'client');
		$form->addRule('catID', '"category" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateBizEditFields($fields)
			{
				$f1 = $fields['businessName'];
				$f2 = $fields['catID'];
				$f3 = $fields['address1'];
				$f4 = $fields['city'];
				if (($f1 == '') && ($f2 == '') && ($f3 == '') && ($f4 == '')) {
					return array('businessName' => 'Business name, category, address and city are required fields');
				}
				return true;
			}
			$form->addFormRule('validateBizEditFields');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			
			
			
			// 1. update data into table
			$formValues = $form->getSubmitValues();
			
			// stem the keywords and NotKeywords
			$formValues['stemmedKeywords'] = getStemmedString($formValues['keywords']);
			$formValues['stemmedNotKeywords'] = getStemmedString($formValues['notKeywords']);
			
			
			$bus = &new business();
			$bus->updateRecord($businessID, $formValues);
			$busDAO = $bus->getOneRecord($businessID);
			
			// 2. delete existing data and insert new data
			
			$catID = $form->getSubmitValue('catID');
			$buscatDAO = &new business2category();
			$sql = "DELETE FROM business2category WHERE businessID=$businessID";
			$buscatDAO->executeQuery($sql);
			for($i=0;$i<sizeof($catID);$i++)
			{
				$sql = "INSERT INTO business2category (businessID, categoryID) VALUES ($businessID,".$catID[$i].")";
				$buscatDAO->executeQuery($sql);
			}
			
			
			// 3. update the accountPostcodes table
			
			$accountID = $busDAO->accountID;
			$businessID = $busDAO->businessID;
			
			
			
			// Delete current entries
			$serviceArea->deleteAccountPostcodes($accountID);
			
			// add the new entries
			$serviceAreaPostcodes_str =$formValues['serviceArea'];
			$serviceAreaPostcodes = explode(" ",$serviceAreaPostcodes_str);
			
			$serviceArea->_dao->accountID=$accountID;
			$serviceArea->addAccountPostcodes($serviceAreaPostcodes);
			
			
			
			
			
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url=  "index.php?_a=bussearch";
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
	case "busdelete":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID -
		$businessID = (int)$_REQUEST['businessID'];
		
		# GET RECORD DATA
		$bus = &new business();
		$busArray = $bus->getOneRecordArray($businessID);
		$businessName = $busArray['businessName'];
		$businessCat = $busArray['businessCategory'];
		$catID = $busArray['catID'];
		$businessAdd = $busArray['address1'];
		$businessCity = $busArray['city'];
		$businessPostCode = $busArray['postcode'];
		$businessPhoneArea = $busArray['phoneAreacode'];
		$businessPhone = $busArray['phoneNumber'];
		$businessCell = $busArray['cellPhoneNumber'];
		$businessFax = $busArray['faxNumber'];
		# DELETE RECORD FROM BUSINESS TABLE
		$busDAO = &new business();
		$busDAO->deleteRecord($businessID);
		# DELETE RECORD FROM BIZCAT TABLE
		$buscatDAO = &new business2category();
		$sql = "DELETE FROM business2category WHERE businessID=$businessID";
		$buscatDAO->executeQuery($sql);
		# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
		$url=  "index.php?_a=bussearch";
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;
		break;
		
	case 'business_alert_list':
	//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		
		
		#++++ GET REQUEST PARAMETERS
		$msg = (int)$_REQUEST['msg'];
		$pg =  (int)$_REQUEST['pg'];
		$listingID = $_REQUEST['listingID'];
//		$keyword = $_REQUEST['keyword'];
		$shortDescription=$_REQUEST['shortDescription'];		
		$postcode = $_REQUEST['postcode'];
		$test = $_REQUEST['test'];
		
			$cond = " WHERE listingID='$listingID'";
		# New code for admin added categories - This code is run when you click on "TEST".
			if($test=="set"){
					$sql = "DELETE FROM listing2category $cond";
					//print "<br>$sql<br>";
					//mysql_query($sql);
					$list2catObj = &new listing2category();
					$listingcatDAO = $list2catObj->executeQuery($sql);
					$catids = $_REQUEST['cat_id'];
					$catid_array = split(",",$catids);
				
						for($i=0;$i<sizeof($catid_array);$i++){
							$list2catObj = &new listing2category();
							$catValues = array('listingID'=>$listingID,'categoryID'=>$catid_array[$i]);
							$test = $list2catObj->insertRecord($catValues);
							
							}?>
						<script language ="javascript">
							window.location = "index.php?_a=business_alert_list&listingID="+<?php echo $listingID;?>;
						</script>
				<?	}
		#new code ends
		//  - CODE FOR GETTING CATEGORY LIST
		
				#++++ SQL QUERY FOR LIST
				$sql = "SELECT DISTINCT * FROM listings $cond";
				//print "<br>$sql<br>";
				$listingObj = &new listing();
				$listingDAO = $listingObj->getRecordsFromQuery($sql);
				$totRec = $listingDAO->N;
				$listingDAO->fetch();
		
//				$sponsBusDAO = make_array_rank($listingDAO);
				$sponsBusDAO = make_array_rank($listingDAO);
				$totSponsRec =sizeof($sponsBusDAO);
				
				$sql = "SELECT DISTINCT * FROM listing2category $cond";
				//print "<br>$sql<br>";
				$list2catObj = &new listing2category();
				$list2catDAO = $list2catObj->getRecordsFromQuery($sql);
				$list2catarray=creat_array_list2category($list2catDAO);
//				$totRec = $list2catDAO->N;
				$list2catDAO->fetch();
//				$totlist2catRec = $list2catDAO->tot;
		//  - CODE ENDS HERE
		
		$listingDAO = get_businesses($listingID);
		
		
		$view = $action;
		break;
}
?>