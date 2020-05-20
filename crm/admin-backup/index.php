<?php
#++++ include necessary files
require_once('../inc/configure.php');
require_once('../inc/functions.php');
require_once ('HTML/QuickForm.php');
require_once ('HTML/QuickForm/Renderer/QuickHtml.php');	
require_once(OBJ_PATH . '/_extlib/tplengine/AwesomeTemplateEngine.class.php');
require_once(OBJ_PATH . '/_extlib/tplengine/FormTemplateRenderer.class.php');
require_once(OBJ_PATH.'/storage/DataBank.php');
require_once(OBJ_PATH.'/authorize/AuthorizeAdmin.php');
require_once(OBJ_PATH.'/db/accounts.class.php');
require_once(OBJ_PATH.'/db/businesses.class.php');
require_once(OBJ_PATH.'/db/categories.class.php');
require_once(OBJ_PATH.'/db/business2category.class.php');

#++++ collect the action
$action = @$_REQUEST['_a'];

#++++ redirect to login page if no action specified
if($action=="")
{
	header ("Location: index.php?_a=login");
	exit;
}	

###NEW SWITCH
##########++++++++++ ACTION PART ++++++++++##########

switch ($action)
{
	case "login":
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: elements
		$form = &new HTML_QuickForm('login','POST','index.php?_a=login');
		$form->addElement('text','userName',null, array('size'=>30));
		$form->addElement('password', 'password',null, array('size'=>30));
		$form->addElement('submit','login_button',' Login ',array('class'=>'inputbutton'));
		
		#++++ JS validation rules
		$form->addRule('userName', '"user name" is a required field.', 'required', null, 'client');
		$form->addRule('password', '"password" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level
			function validateLoginFields($fields)
			{
					$f1 = $fields['userName'];
					$f2 = $fields['password'];
					if (($f1 == '') || ($f2 == '')) {
						return array('userName' => 'User name and Password required');
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
			$userName = $form->getSubmitValue('userName');
			$password = $form->getSubmitValue('password');

			#++++ Initialize Admin Model
			$account = &new account();

			if( $account->validLogin($userName,$password) ) 
			{
				#++++ Get DAO Reference
				$accountDAO = &$account->getDAOFetched();
		
				#++++ Save Admin in Session
				$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
				$dataBank->setVar('s_a_account_id', $accountDAO->accountID);
				$dataBank->setVar('s_a_user_name', $accountDAO->userName);
				$dataBank->setVar('s_a_start_time', time());
				$dataBank->setVar('s_a_account_type', $accountDAO->accountType);
				
				#++++ Redirect user to either home page or requested page before login page
				$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
				if(trim($dataBank->getVar('query_string'))!="")
				{
					$url = HOST1 . $dataBank->getVar('query_string');
					$dataBank->setVar('query_string','');
					//print $url;
					header('HTTP/1.1 301 Moved Permanently');
					header("Location: " . $url);
					header('Connection: close');
					exit;					

				}	
				else
				{
					$actionFlag = "login_success"; // no error in login
				}
			} 
			else 
			{
				$form->setElementError('userName', 'Invalid username/password combination'); // set the page message
				$actionFlag = "login_fail"; // error in login
			}
		}

		break;

	case "logout":
		#++++ 1. clear session data
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$dataBank->deleteData();
		
		#++++ 2. redirect to admin login page
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . REDIRECT_ADMIN_BASE . "/index.php?_a=login");
		header('Connection: close');
		exit;	  

		break;

	case "myaccount":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
		
		$actionFlag = "myaccount_success";
		
		break;

	case "bussearch":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList(''); //get category list from functions		
		//$parentCatOptions = array();
		// :: elements
		$form = &new HTML_QuickForm('bussearch','POST','index.php?_a=bussearch');
		$form->addElement('text','businessName',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>40));
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
		if ($form->validate()) 
		{
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
			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=buslist&business_name=$businessName&business_cat=$businessCat&business_address=$businessAdd&business_city=$businessCity&business_postcode=$businessPostCode&business_phone_area=$businessPhoneArea&business_phone=$businessPhone&business_cell=$businessCell&business_fax=$businessFax&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
		else
		{
			$actionFlag='bussearch_fail';
		}

		break;

	case "buslist":

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
		$businessName = $_REQUEST['business_name'];
		$businessCat = $_REQUEST['business_cat'];
		$catIDs = explode("-",$businessCat);
		$businessAdd = $_REQUEST['business_address'];
		$businessCity = $_REQUEST['business_city'];
		$businessPostCode = $_REQUEST['business_postcode'];
		$businessPhoneArea = $_REQUEST['business_phone_area'];
		$businessPhone = $_REQUEST['business_phone'];
		$businessCell = $_REQUEST['business_cell'];
		$businessFax = $_REQUEST['business_fax'];
		$pg =  (int)$_REQUEST['pg'];

		#++++ GENERATE CONDITION
		$cond = " WHERE 1";
		if(trim($businessName)!="")
			$cond .= " AND b.businessName like '%$businessName%'";
		if(trim($businessAdd)!="")
			$cond .= " AND b.address1 like '%$businessAdd%'";	
		if(trim($businessCity)!="")
			$cond .= " AND b.city like '%$businessCity%'";	
		if(trim($businessPostCode)!="")
			$cond .= " AND b.postcode like '%$businessPostCode%'";	
		if(trim($businessPhoneArea)!="")
			$cond .= " AND b.phoneAreacode like '%$businessPhoneArea%'";	
		if(trim($businessPhone)!="")
			$cond .= " AND b.phoneNumber like '%$businessPhone%'";	
		if(trim($businessCell)!="")
			$cond .= " AND b.cellPhoneNumber like '%$businessCell%'";	
		if(trim($businessFax)!="")
			$cond .= " AND b.faxNumber like '%$businessFax%'";	

		if(($catIDs[0])>0)
		{
			$catCondition = "(";
			for($i=0;$i<sizeof($catIDs);$i++)
			{
				$catCondition .= " bc.categoryID=" . $catIDs[$i] . " OR ";
			}
			$catCondition = substr($catCondition,0,(strlen($catCondition)-3));
			$catCondition .= ") AND bc.businessID=b.businessID ";

			$cond .= " AND $catCondition";	
		}
			
		# TOTAL RECORD COUNT FOR PAGING
		if(($catIDs[0])>0)
			$sql = "SELECT count(DISTINCT(b.businessID)) AS tot FROM businesses b , business2category bc $cond";
		else
			$sql = "SELECT count(DISTINCT(b.businessID)) AS tot FROM businesses b $cond";	

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
		if(($catIDs[0])>0)
			$sql = "SELECT DISTINCT b.* FROM businesses as b, business2category bc $cond ORDER BY businessName LIMIT $start,$gap";
		else
			$sql = "SELECT DISTINCT b.* FROM businesses as b $cond ORDER BY businessName LIMIT $start,$gap";

		$query = &new business();
		$busDAO = $query->getRecordsFromQuery($sql);
		
		$actionFlag='buslist_success';

		break;
		
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
			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=buslist&msg=1&business_name=$businessName&business_cat=$businessCat&business_address=$businessAdd&business_city=$businessCity&business_postcode=$businessPostCode&business_phone_area=$businessPhoneArea&business_phone=$businessPhone&business_cell=$businessCell&business_fax=$businessFax&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;

		}
		else
		{
			$actionFlag='busadd_fail';
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
		$form->addElement('static', 'businessCategory',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>40));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>40));
		$form->addElement('text', 'faxNumber',null, array('size'=>40));
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
	
		$busArray = array_merge($busArray,$buscatArray);
		$form->setDefaults($busArray);
		
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

			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$bus = &new business();
			$bus->updateRecord($businessID, $formValues);
			
			// 2. delete existing data and insert new data
			$buscatDAO = &new business2category();

			$sql = "DELETE FROM business2category WHERE businessID=$businessID";
			$buscatDAO->executeQuery($sql);

			for($i=0;$i<sizeof($catID);$i++)
			{
				$sql = "INSERT INTO business2category (businessID, categoryID) VALUES ($businessID,".$catID[$i].")";
				$buscatDAO->executeQuery($sql);
			}

			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=buslist&msg=2&business_name=$businessName&business_cat=$businessCat&business_address=$businessAdd&business_city=$businessCity&business_postcode=$businessPostCode&business_phone_area=$businessPhoneArea&business_phone=$businessPhone&business_cell=$businessCell&business_fax=$businessFax&pg=0";

			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;

		}
		else
		{
			$actionFlag='busedit_fail';
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
		$url = $_SERVER['HTTP_REFERER'];
		$pattern = "/&msg=([0-9]+)/";
		$replacement = "";
		$url = preg_replace($pattern, $replacement, $url);
		
		$url = str_replace("_a=buslist","_a=buslist&msg=3",$url);

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;

		break;

	case "buscatlist":

		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		$time=time();
		
		#++++ GET REQUEST PARAMETERS
		$msg = (int)$_REQUEST['msg'];
		$pg =  (int)$_REQUEST['pg'];

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT COUNT(*) AS tot FROM categories";
		$query = &new category();
		$catDAO = $query->getRecordsFromQuery($sql);
		$catDAO->fetch();
		$totRec =$catDAO->tot;

		#++++ PAGING PARAMS
		$page = $pg;
		$gap = 20;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT * FROM categories ORDER BY parentCategoryID,categoryName LIMIT $start,$gap";
		$query = &new category();
		$catDAO = $query->getRecordsFromQuery($sql);
		
		$actionFlag = "buscatlist";

		break;

	case "buscatadd":
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
		$form = &new HTML_QuickForm('buscatadd','POST','index.php?_a=buscatadd');
		$form->addElement('text','categoryName',null, array('size'=>40));
		$form->addElement('select','parentCategoryID',null, $parentCatOptions);
		$form->addElement('submit','submit_button',' Add ', array('class'=>'inputbutton'));

		#++++ JS validation rules
		$form->addRule('categoryName', '"category name" is a required field.', 'required', null, 'client');
		$form->addRule('parentCategoryID', '"parent category" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateBizCatAddFields($fields)
			{
				$f1 = $fields['categoryName'];
				$f2 = $fields['parentCategoryID'];
				if (($f1 == '') || ($f2 == '')) {
					return array('categoryName' => 'category name and parent category are required field');
				}
				return true;
			}
			$form->addFormRule('validateBizCatAddFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$catName = $form->getSubmitValue('categoryName');
			$pCatName = $form->getSubmitValue('parentCategoryID');

			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$cat = &new category();
			$cat->insertRecord($formValues);

			//$url= REDIRECT_ADMIN_BASE . "/index.php?_a=busedit&msg=1&businessID=".$businessID;
			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=buscatlist&msg=1&pg=0";
			//print $url;
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
		
		$actionFlag = 'buscatadd';
		
		break;

	case "buscatedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS ID -
		$businessCatID = (int)$_REQUEST['businessCatID'];
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList('yes'); //get category list from functions

		// :: elements
		$form = &new HTML_QuickForm('buscatedit','POST','index.php?_a=buscatedit&businessCatID='.$businessCatID);
		$form->addElement('text','categoryName',null, array('size'=>40));
		$form->addElement('select','parentCategoryID',null, $parentCatOptions);
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		$cat = &new category();
		$catArray = $cat->getOneRecordArray($businessCatID);
		$form->setDefaults($catArray);
				
		#++++ JS validation rules
		$form->addRule('categoryName', '"category name" is a required field.', 'required', null, 'client');
		$form->addRule('parentCategoryID', '"parent category" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateBizCatEditFields($fields)
			{
				$f1 = $fields['categoryName'];
				$f2 = $fields['parentCategoryID'];
				if (($f1 == '') || ($f2 == '')) {
					return array('categoryName' => 'category name and parent category are required field');
				}
				return true;
			}
			$form->addFormRule('validateBizCatEditFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$catName = $form->getSubmitValue('categoryName');
			$pCatName = $form->getSubmitValue('parentCategoryID');
			if($pCatName==$businessCatID)
			{
				$form->setElementError('categoryName', 'can not use same category as parent category'); // set the page message
			}
			else	 
			{
				// 1. update data into table
				$formValues = $form->getSubmitValues();
				$cat = &new category();
				$cat->updateRecord($businessCatID,$formValues);
	
				$url= REDIRECT_ADMIN_BASE . "/index.php?_a=buscatlist&msg=2&pg=0";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;	
			}
		}

		$actionFlag = 'buscatedit';
				
		break;

	case "buscatdelete":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS CAT ID -
		$businessCatID = (int)$_REQUEST['businessCatID'];

		# DELETE RECORD AS WELL AS RELATED SUB CATEGORIES FROM TABLE
		$sql = "DELETE FROM categories WHERE (categoryID=$businessCatID OR parentCategoryID=$businessCatID)";
		$catDAO = &new category();
		$catDAO->getRecordsFromQuery($sql);

		//this is used to delete after editing a record #++ to remove msg set at the time of edit
		$url = $_SERVER['HTTP_REFERER'];
		$pattern = "/&msg=([0-9]+)/";
		$replacement = "";
		$url = preg_replace($pattern, $replacement, $url);
		
		$url = str_replace("_a=buscatlist","_a=buscatlist&msg=3",$url);

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;	
		break;
				
	default:
		header("Location: index.php?_a=myaccount");
		exit;
}

###NEW SWITCH
##########++++++++++ VIEW SELECTION PART ++++++++++##########

// SELECT PROPER VIEW SWITCH
switch ($action) 
{
	case 'login':
		if($actionFlag=='login_success') #++ login successfull and if there is any requested view before login then select it
			$view = 'myaccount';
		else
			$view = "login";

		break;

	case 'myaccount':
		if($actionFlag=='myaccount_success')
			$view = 'myaccount';

		break;
		
	case 'bussearch':	
		if($actionFlag=='bussearch_fail') 
			$view = "bussearch";

		break;

	case 'buslist':
		if($actionFlag=='buslist_success')
			$view = 'buslist';

		break;

	
	case 'busadd':
		if($actionFlag=='busadd_fail')
			$view = 'busadd';

		break;

	case 'busedit':
		if($actionFlag=='busedit_fail')
			$view = 'busedit';

		break;

	case 'buscatlist':
		if($actionFlag=='buscatlist')
			$view = 'buscatlist';
			
		break;

	case 'buscatadd':
		if($actionFlag=='buscatadd')
			$view = 'buscatadd';
		
		break;

	case 'buscatedit':
		if($actionFlag=='buscatedit')
			$view = 'buscatedit';
		
		break;
		
	default:
		$view = "login";

		break;	

}	
// END OF SELECTING PROPER VIEW SWITCH

###NEW SWITCH
##########++++++++++ MAIN VIEW PART ++++++++++##########

// START OF VIEW CONTROLLER SWITCH
switch ($view) 
{
	case 'login':
	
			// *-*-*-*-*-*		- QUICK FORM RENDERER -
			$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
			$form->accept(&$renderer);
			
			// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
			$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
			$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/login.tpl.php');
	
			// *-*-*-*-*-*		- TEMPLATE VARIABLES -
			$data['title'] = "OLR Administration :: Login";
			$data['formErrors'] = &$form->_errors;
			$data['header'] = "Please Login";
			$data['left-panel'] = "";
			$data['bodyForm'] = $bodyForm;
			$data['body'] = '';
			
			// set the template to use
			$template = '/main.tpl.php';		
			
			break;

	case 'myaccount':
	
			// *-*-*-*-*-*		- GET SESSION VARIABLES -
			$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
			$userName = $dataBank->getVar('s_a_user_name');
	
			// *-*-*-*-*-*		- TEMPLATE VARIABLES -
			$data['title'] = "OLR Administration :: Home";
			$data['userName'] =  $userName;
			$data['header'] = "Home";
			$data['left-panel'] = "left-panel.php";
			$data['bodyForm'] = '';
			$data['body'] = TEMPLATE_PATH_ADMIN.'/welcome.tpl.php';
	
			// set the template to use
			$template = '/main.tpl.php';		
			
			break;

	case 'bussearch':
		
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');

		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/business.search.tpl.php');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Business Search";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Business Search";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		
		// set the template to use
		$template = '/main.tpl.php';	
		
		break;
				
	case 'buslist':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['keywords'] = @$_REQUEST['keywords'];
		$data['title'] = "OLR Administration :: Business Search Results";
		$data['userName'] =  $userName;
		$data['header'] = "Business Search Results";
		$data['left-panel'] = "left-panel.php";
		$data['busDAO'] = $busDAO;
		$data['timeStart'] = $time;
		$data['totalRecord'] = $totRec;
		$data['startRec'] = ($start+1);
		$data['endRec'] = $end;
		$data['pagingInfo'] = $pagingInfo;
		if($msg==1)
			$data['msgData'] = "Record Inserted Successfully.";
		if($msg==2)
			$data['msgData'] = "Record Updated Successfully.";	
		if($msg==3)
			$data['msgData'] = "Record Deleted Successfully.";

		$data['body'] = TEMPLATE_PATH_ADMIN.'/business.list.tpl.php';
		$data['bodyForm'] = '';
		
		// set the template to use
		$template = '/main.tpl.php';	
		
		break;

	case 'busadd':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');

		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/business.add-edit.tpl.php');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Add New Business";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Add New Business";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';

		// set the template to use
		$template = '/main.tpl.php';	

		break;			

	case 'busedit':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');

		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/business.add-edit.tpl.php');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Business edit";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Business Edit";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';

		// set the template to use
		$template = '/main.tpl.php';	
		
		break;

	case 'buscatlist':

		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['keywords'] = @$_REQUEST['keywords'];
		$data['title'] = "OLR Administration :: Business Categories";
		$data['userName'] =  $userName;
		$data['header'] = "Business Categories";
		$data['left-panel'] = "left-panel.php";
		$data['catDAO'] = $catDAO;
		$data['totalRecord'] = $totRec;
		$data['startRec'] = ($start+1);
		$data['endRec'] = $end;
		$data['timeStart'] = $time;
		$data['pagingInfo'] = $pagingInfo;
		if($msg==1)
			$data['msgData'] = "Record Inserted Successfully.";
		if($msg==2)
			$data['msgData'] = "Record Updated Successfully.";	
		if($msg==3)
			$data['msgData'] = "Record Deleted Successfully.";

		$data['body'] = TEMPLATE_PATH_ADMIN.'/buscat.list.tpl.php';
		$data['bodyForm'] = '';

		// set the template to use
		$template = '/main.tpl.php';

		break;
		
	case 'buscatadd':
			
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/buscat.add-edit.tpl.php');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Add New Business Category";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Add New Business Category";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';

		// set the template to use
		$template = '/main.tpl.php';			
		
		break;	
		
	case 'buscatedit':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/buscat.add-edit.tpl.php');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Edit Business Category";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Edit Business Category";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';

		// set the template to use
		$template = '/main.tpl.php';

		break;

}

// END OF VIEW CONTROLLER SWITCH

##########++++++++++ TEMPLATE PART ++++++++++##########

// NOW ACTUALLY RENDER THE SELECTED VIEW AND - DISPLAY MAIN TEMPLATE
$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
$aT->parseTemplate($data,$template);

?>