<?php
#++++ include necessary files
require_once('../inc/configure.php');
require_once('../inc/functions.php');
require_once(OBJ_PATH . '/_extlib/tplengine/AwesomeTemplateEngine.class.php');
require_once(OBJ_PATH . '/_extlib/tplengine/FormTemplateRenderer.class.php');
require_once(OBJ_PATH.'/db/admin.class.php');
require_once(OBJ_PATH.'/db/business.class.php');
require_once(OBJ_PATH.'/db/categories.class.php');
require_once(OBJ_PATH.'/db/bizcat.class.php');
require_once(OBJ_PATH.'/storage/DataBank.php');
require_once(OBJ_PATH.'/authorize/AuthorizeAdmin.php');
require_once ('HTML/QuickForm.php');
require_once ('HTML/QuickForm/Renderer/QuickHtml.php');	

#++++ collect the action
$action = @$_REQUEST['_a'];

#++++ redirect to login page if no action specified
if($action=="")
{
	header ("Location: index.php?_a=login");
	exit;
}	
#++++ do the process according to the collected action
switch ($action)
{
	case "login":
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: elements
		$form = &new HTML_QuickForm('login','POST','index.php?_a=login');
		$form->addElement('text','user_name',null, array('size'=>30));
		$form->addElement('password', 'hashed_password',null, array('size'=>30));
		$form->addElement('submit','login_button',' Login ',array('class'=>'inputbutton'));
		
		#++++ JS validation rules
		$form->addRule('user_name', '"user name" is a required field.', 'required', null, 'client');
		$form->addRule('hashed_password', '"password" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level
			function validateLoginFields($fields)
			{
					$f1 = $fields['user_name'];
					$f2 = $fields['hashed_password'];
					if (($f1 == '') || ($f2 == '')) {
						return array('user_name' => 'User name and Password required');
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
			$userName = $form->getSubmitValue('user_name');
			$password = $form->getSubmitValue('hashed_password');

			#++++ Initialize Admin Model
			$admin = &new admin();

			if( $admin->validLogin($userName,$password) ) 
			{
				#++++ Get DAO Reference
				$adminDAO = &$admin->getDAOFetched();
		
				#++++ Save Admin in Session
				$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
				$dataBank->setVar('admin_id', $adminDAO->admin_id);
				$dataBank->setVar('s_a_user_name', $adminDAO->user_name);
				$dataBank->setVar('startTime', time());
				$dataBank->setVar('role', $adminDAO->role);

				#++++ Update Record with logged in time
				$values = array('last_login_TS'=>time() ) ;
				$admin->updateRecord($adminDAO->admin_id, $values);
		
				#++++ Redirect user to either home page or requested page before login page
				$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
				if(trim($dataBank->getVar('query_string'))!="")
				{
					$url = HOST1 . $dataBank->getVar('query_string');
					$dataBank->setVar('query_string','');
				}	
				else
				{
					$url= REDIRECT_ADMIN_BASE . "/index.php?_a=myaccount";
				}
				//print $url;
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;	
			} 
			else 
			{
				$form->setElementError('user_name', 'Invalid username/password combination'); // set the page message
			}
		}
		
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

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');				
		
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
		
		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');
		
		break;

	case "bizsearch":
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList(''); //get category list from functions		
		// :: elements
		$form = &new HTML_QuickForm('bizsearch','POST','index.php?_a=bizsearch');
		$form->addElement('text','biz_name',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'biz_address',null, array('size'=>40));
		$form->addElement('text', 'biz_city',null, array('size'=>40));
		$form->addElement('text', 'biz_post_code',null, array('size'=>40));
		$form->addElement('text', 'biz_phone_area',null, array('size'=>40));
		$form->addElement('text', 'biz_phone_no',null, array('size'=>40));
		$form->addElement('submit','search_button',' Find ',array('class'=>'inputbutton'));
		$form->addElement('link','add_biz_link',null,'index.php?_a=bizadd','Add New Business');
		
			#++++ Validate Login :: Form level 
			function validateBizSearchFields($fields)
			{
				$f1 = $fields['biz_name'];
				$f2 = $fields['catID'];
				$f3 = $fields['biz_address'];
				$f4 = $fields['biz_city'];
				$f5 = $fields['biz_post_code'];
				$f6 = $fields['biz_phone_area'];
				$f7 = $fields['biz_phone_no'];
				if (($f1 == '') && ($f2 == '') && ($f3 == '') && ($f4 == '') && ($f5 == '') && ($f6 == '') && ($f7 == '')) {
					return array('biz_name' => 'Atleast one field required for a search');
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
			$bName = $form->getSubmitValue('biz_name');
			$bCat = @implode("-",$form->getSubmitValue('catID'));
			$bAdd = $form->getSubmitValue('biz_address');
			$bCity = $form->getSubmitValue('biz_city');
			$bPostCode = $form->getSubmitValue('biz_post_code');
			$bPhoneArea = $form->getSubmitValue('biz_phone_area');
			$bPhone = $form->getSubmitValue('biz_phone_no');

			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=bizlist&bName=$bName&bCat=$bCat&bAdd=$bAdd&bCity=$bCity&bPostCode=$bPostCode&bPhoneArea=$bPhoneArea&bPhone=$bPhone&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
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

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');				
		
		break;
		
	case "bizlist":
	
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
		$bName = $_REQUEST['bName'];
		$bCat = $_REQUEST['bCat'];
		$catIDs = explode("-",$bCat);
		$bAdd = $_REQUEST['bAdd'];
		$bCity = $_REQUEST['bCity'];
		$bPostCode = $_REQUEST['bPostCode'];
		$bPhoneArea = $_REQUEST['bPhoneArea'];
		$bPhone = $_REQUEST['bPhone'];
		$pg =  (int)$_REQUEST['pg'];

		#++++ GENERATE CONDITION
		$cond = " WHERE 1";
		if(trim($bName)!="")
			$cond .= " AND b.businessName like '%$bName%'";
		if(trim($bAdd)!="")
			$cond .= " AND b.address1 like '%$bAdd%'";	
		if(trim($bCity)!="")
			$cond .= " AND b.city like '%$bCity%'";	
		if(trim($bPostCode)!="")
			$cond .= " AND b.postCode like '%$bPostCode%'";	
		if(trim($bPhoneArea)!="")
			$cond .= " AND b.phoneAreacode like '%$bPhoneArea%'";	
		if(trim($bPhone)!="")
			$cond .= " AND b.phoneNumber like '%$bPhone%'";	
		if(($catIDs[0])>0)
		{
			$catCondition = "(";
			for($i=0;$i<sizeof($catIDs);$i++)
			{
				$catCondition .= " bc.catID=" . $catIDs[$i] . " OR ";
			}
			$catCondition = substr($catCondition,0,(strlen($catCondition)-3));
			$catCondition .= ") AND bc.bizID=b.businessID ";

			$cond .= " AND $catCondition";	
		}
						
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT count(DISTINCT(b.businessID)) AS tot FROM businesses b , bizcats bc $cond";
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
		$recInfo = 'Currently displaying records '. ($start+1) .' to '.$end;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		if(($catIDs[0])>0)
			$sql = "SELECT DISTINCT b.* FROM businesses as b, bizcats bc $cond ORDER BY businessName LIMIT $start,$gap";
		else
			$sql = "SELECT DISTINCT b.* FROM businesses as b $cond ORDER BY businessName LIMIT $start,$gap";
		$query = &new business();
		$busDAO = $query->getRecordsFromQuery($sql);

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
		$data['startRec'] = $start;
		$data['endRec'] = $end;
		$data['recInfo'] = $recInfo;
		$data['pagingInfo'] = $pagingInfo;
		if($msg==1)
			$data['msgData'] = "Record Inserted Successfully.";
		if($msg==2)
			$data['msgData'] = "Record Updated Successfully.";	
		if($msg==3)
			$data['msgData'] = "Record Deleted Successfully.";

		$data['body'] = TEMPLATE_PATH_ADMIN.'/business.list.tpl.php';
		$data['bodyForm'] = '';

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');		
		
		break;

	case "bizadd":
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
		$form = &new HTML_QuickForm('bizadd','POST','index.php?_a=bizadd');
		$form->addElement('text','businessName',null, array('size'=>40));
		$form->addElement('static', 'businessCategory',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postCode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>40));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('submit','edit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		$bizArray = array('businessCategory'=>"NONE");
		$form->setDefaults($bizArray);		

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
			$bName = $form->getSubmitValue('businessName');
			$bCat = $form->getSubmitValue('businessCategory');
			$catID = $form->getSubmitValue('catID');
			$bAdd = $form->getSubmitValue('address1');
			$bCity = $form->getSubmitValue('city');
			$bPostCode = $form->getSubmitValue('postCode');
			$bPhoneArea = $form->getSubmitValue('phoneAreacode');
			$bPhone = $form->getSubmitValue('phoneNumber');

			// 1. insert data into table
			$formValues = $form->getSubmitValues();
			$biz = &new business();
			$bID = $biz->insertRecord($formValues);

			// 2. insert new data in bizcats
			$bizcatDAO = &new bizcat();
			for($i=0;$i<sizeof($catID);$i++)
			{
				$sql = "INSERT INTO bizcats (bizID, catID) VALUES ($bID,".$catID[$i].")";
				$bizcatDAO->executeQuery($sql);
			}

			// 3. redirect
			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=bizlist&msg=1&bName=$bName&bCat=$bCat&bAdd=$bAdd&bCity=$bCity&bPostCode=$bPostCode&bPhoneArea=$bPhoneArea&bPhone=$bPhone&pg=0";
			//print $url;
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;
		}
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

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');				
		
		break;
		
	case "bizedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$bID = $_REQUEST['bID'];

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList(''); //get category list from functions

		// :: elements
		$form = &new HTML_QuickForm('bizedit','POST','index.php?_a=bizedit&bID='.$bID);
		$form->addElement('text','businessName',null, array('size'=>40));
		$form->addElement('static', 'businessCategory',null, array('size'=>40));
		$form->addElement('select','catID',null, $parentCatOptions, array("size" => "6", "multiple"));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postCode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreacode',null, array('size'=>40));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('submit','edit_button',' Save ',array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		//get default values from business table
		$biz = &new business();
		$bizArray = $biz->getOneRecordArray($bID);
		
		//get default catids from bizcat table
		$catIDs='';
		$bizcat = &new bizcat();
		$sql = "SELECT * FROM bizcats WHERE bizID=$bID";
		$bizcatDAO = $bizcat->getRecordsFromQuery($sql);
		while ($bizcatDAO->fetch())
		{
			$catIDs .= $bizcatDAO->catID;
			$catIDs .= ",";
		}
		$bizcatArray = array('catID'=>"$catIDs");
	
		$bizArray = array_merge($bizArray,$bizcatArray);
		$form->setDefaults($bizArray);
		
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
			$bName = $form->getSubmitValue('businessName');
			$bCat = $form->getSubmitValue('businessCategory');
			$catID = $form->getSubmitValue('catID');
			$bAdd = $form->getSubmitValue('address1');
			$bCity = $form->getSubmitValue('city');
			$bPostCode = $form->getSubmitValue('postCode');
			$bPhoneArea = $form->getSubmitValue('phoneAreacode');
			$bPhone = $form->getSubmitValue('phoneNumber');

			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$biz = &new business();
			$biz->updateRecord($bID, $formValues);
			
			// 2. delete existing data and insert new data
			$bizcatDAO = &new bizcat();

			$sql = "DELETE FROM bizcats WHERE bizID=$bID";
			$bizcatDAO->executeQuery($sql);

			for($i=0;$i<sizeof($catID);$i++)
			{
				$sql = "INSERT INTO bizcats (bizID, catID) VALUES ($bID,".$catID[$i].")";
				$bizcatDAO->executeQuery($sql);
			}

			// 3. redirect
			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=bizlist&msg=2&bName=$bName&bCat=$bCat&bAdd=$bAdd&bCity=$bCity&bPostCode=$bPostCode&bPhoneArea=$bPhoneArea&bPhone=$bPhone&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
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

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');				
		
		break;

	case "bizdelete":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID -
		$bID = (int)$_REQUEST['bID'];

		# DELETE RECORD FROM BUSINESS TABLE
		$busDAO = &new business();
		$busDAO->deleteRecord($bID);

		# DELETE RECORD FROM BIZCAT TABLE
		$bizcatDAO = &new bizcat();
		$sql = "DELETE FROM bizcats WHERE bizID=$bID";
		$bizcatDAO->executeQuery($sql);

		//this is used to delete after editing a record #to remove msg set at the time of edit
		$url = $_SERVER['HTTP_REFERER'];
		$pattern = "/&msg=([0-9]+)/";
		$replacement = "";
		$url = preg_replace($pattern, $replacement, $url);
		
		$url = str_replace("_a=bizlist","_a=bizlist&msg=3",$url);

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;	
		break;
		
	case "bizcatlist":

		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		
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
		$recInfo = 'Currently displaying categories '. ($start+1) .' to '.$end . '.';
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT * FROM categories ORDER BY parentCategory LIMIT $start,$gap";
		$query = &new category();
		$catDAO = $query->getRecordsFromQuery($sql);

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
		$data['startRec'] = $start;
		$data['endRec'] = $end;
		$data['recInfo'] = $recInfo;
		$data['pagingInfo'] = $pagingInfo;
		if($msg==1)
			$data['msgData'] = "Record Inserted Successfully.";
		if($msg==2)
			$data['msgData'] = "Record Updated Successfully.";	
		if($msg==3)
			$data['msgData'] = "Record Deleted Successfully.";

		$data['body'] = TEMPLATE_PATH_ADMIN.'/bizcat.list.tpl.php';
		$data['bodyForm'] = '';

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');		
	
		break;

	case "bizcatadd":
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
		$form = &new HTML_QuickForm('bizcatadd','POST','index.php?_a=bizcatadd');
		$form->addElement('text','categoryName',null, array('size'=>40));
		$form->addElement('select','parentCategory',null, $parentCatOptions);
		$form->addElement('submit','submit_button',' Add ', array('class'=>'inputbutton'));

		#++++ JS validation rules
		$form->addRule('categoryName', '"category name" is a required field.', 'required', null, 'client');
		$form->addRule('parentCategory', '"parent category" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateBizCatAddFields($fields)
			{
				$f1 = $fields['categoryName'];
				$f2 = $fields['parentCategory'];
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
			$pCatName = $form->getSubmitValue('parentCategory');

			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$cat = &new category();
			$cat->insertRecord($formValues);

			//$url= REDIRECT_ADMIN_BASE . "/index.php?_a=bizedit&msg=1&bID=".$bID;
			$url= REDIRECT_ADMIN_BASE . "/index.php?_a=bizcatlist&msg=1&pg=0";
			//print $url;
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/bizcat.add-edit.tpl.php');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Add New Business Category";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Add New Business Category";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');				
		
		break;
		
	case "bizcatedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS ID -
		$bCatID = (int)$_REQUEST['bCatID'];
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList('yes'); //get category list from functions

		// :: elements
		$form = &new HTML_QuickForm('bizcatedit','POST','index.php?_a=bizcatedit&bCatID='.$bCatID);
		$form->addElement('text','categoryName',null, array('size'=>40));
		$form->addElement('select','parentCategory',null, $parentCatOptions);
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		$cat = &new category();
		$catArray = $cat->getOneRecordArray($bCatID);
		$form->setDefaults($catArray);
				
		#++++ JS validation rules
		$form->addRule('categoryName', '"category name" is a required field.', 'required', null, 'client');
		$form->addRule('parentCategory', '"parent category" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateBizCatEditFields($fields)
			{
				$f1 = $fields['categoryName'];
				$f2 = $fields['parentCategory'];
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
			$pCatName = $form->getSubmitValue('parentCategory');
			if($pCatName==$bCatID)
			{
				$form->setElementError('categoryName', 'can not use same category as parent category'); // set the page message
			}
			else	 
			{
				// 1. update data into table
				$formValues = $form->getSubmitValues();
				$cat = &new category();
				$cat->updateRecord($bCatID,$formValues);
	
				$url= REDIRECT_ADMIN_BASE . "/index.php?_a=bizcatlist&msg=2&pg=0";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;	
			}
		}
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/bizcat.add-edit.tpl.php');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Edit Business Category";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Edit Business Category";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';

		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');				
		
		break;

	case "bizcatdelete":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS CAT ID -
		$bCatID = (int)$_REQUEST['bCatID'];

		# DELETE RECORD AS WELL AS RELATED SUB CATEGORIES FROM TABLE
		$sql = "DELETE FROM categories WHERE (categoryID=$bCatID OR parentCategory=$bCatID)";
		$catDAO = &new category();
		$catDAO->getRecordsFromQuery($sql);

		//this is used to delete after editing a record #to remove msg set at the time of edit
		$url = $_SERVER['HTTP_REFERER'];
		$pattern = "/&msg=([0-9]+)/";
		$replacement = "";
		$url = preg_replace($pattern, $replacement, $url);
		
		$url = str_replace("_a=bizcatlist","_a=bizcatlist&msg=3",$url);

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;	
		break;
		
	case "users":

		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,4');
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['title'] = "OLR Administration :: Admin Users";
		$data['userName'] =  $userName;
		$data['header'] = "Admin Users";
		$data['left-panel'] = "left-panel.php";
		
		$data['bodyForm'] = '';
		$data['body'] = TEMPLATE_PATH_ADMIN.'/user.tpl.php';
		
		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');
		
		break;		

	default:

		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
		
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');

		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['title'] = "OLR Administration";
		$data['userName'] =  $userName;
		$data['header'] = "Home";
		$data['left-panel'] = "left-panel.php";
		$data['bodyForm'] = 'Invalid request... Please try again... <a href=\'index.php?_a=myaccount\'>Home</a>';
		$data['body'] = '';
		
		// *-*-*-*-*-*		- DISPLAY TEMPLATE -
		$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
		$aT->parseTemplate($data,'/main.tpl.php');
		break;		
}
?>