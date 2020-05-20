<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}
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
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
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
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		
		// set the template to use
		$template = '/main.tpl.php';
		
		break;
				
	case 'buslist':
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
			$cond .= " AND b.postCode like '%$businessPostCode%'";	
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
		
		
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['keywords'] = @$_REQUEST['keywords'];
		$data['title'] = "OLR Administration :: Business Search Results";
		$data['userName'] =  $userName;
		$data['header'] = "Business Search Results";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
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
	case 'buslist2':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['keywords'] = @$_REQUEST['keywords'];
		$data['title'] = "OLR Administration :: Business Search Results";
		$data['userName'] =  $userName;
		$data['header'] = "Business Search Results";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['busDAO'] = $busDAO;
		$data['timeStart'] = $time;
		$data['totalRecord'] = $totRec;
		$data['startRec'] = ($start+1);
		$data['endRec'] = $end;
		$data['pagingInfo'] = $pagingInfo;
		if($msg==1)
			$data['msgData'] = "Businesses Added To The Campaign Successfully.";
		if($msg==2)
			$data['msgData'] = "Either A Few or All Businesses Are Already Assigned To a Campaign.";	
		if($msg==3)
			$data['msgData'] = "All Businesses Are Already Assigned To a Campaign.";
		$data['body'] = TEMPLATE_PATH_ADMIN.'/business.campaign.list.tpl.php';
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
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
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
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';	
		
		break;
	case 'buscatlist':
	//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------
		#++++ GET FIRST TIME TO CALCULATE TIME FOR DISPLAYING RESULTS
		$time=time();
		
		#++++ GET REQUEST PARAMETERS
		$msg = @(int)$_REQUEST['msg'];
		$pg =  @(int)$_REQUEST['pg'];
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT COUNT(*) AS tot FROM categories where parentCategoryID=-1";
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
		$sql = "SELECT * FROM categories where parentCategoryID=-1 ORDER BY parentCategoryID,categoryName  LIMIT $start,$gap";
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
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
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
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
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
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';
		break;
	case 'listinglist':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['keywords'] = @$_REQUEST['keywords'];
		$data['title'] = "OLR Administration :: Listings";
		$data['userName'] =  $userName;
		$data['header'] = "Listings";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['listingDAO'] = $listingDAO;
		$data['totalRecord'] = $totRec;
		$data['startRec'] = ($start+1);
		$data['endRec'] = $end;
		//$data['timeStart'] = $time;
		$data['pagingInfo'] = $pagingInfo;
		if($msg==1)
			$data['msgData'] = "Listing Approved Successfully.";
		if($msg==2)
			$data['msgData'] = "Listing Rejected Successfully.";	
		$data['body'] = TEMPLATE_PATH_ADMIN.'/listings.list.tpl.php';
		$data['bodyForm'] = '';
		// set the template to use
		$template = '/main.tpl.php';
		break;
		
	case 'listingapprove':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/listings.confirm.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Confirm Listing";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Confirm Listing";
		//$data['totalSponsRecord'] = $totSponsRec;
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		// set the template to use
		$template = '/main.tpl.php';
		break;
	
	case 'listingreject':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/listings.reject.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Confirm Rejection";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Confirm Rejection";
		//print_r ($sponsBusDAO);
		$data['sponsBusDAO'] = $sponsBusDAO;
		//print $totSponsRec;
		$data['totalSponsRecord'] = $totSponsRec;
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		// set the template to use
		$template = '/main.tpl.php';
		break;
	case 'accountsearch':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/account.search.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Account Search";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Account Search";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		
		// set the template to use
		$template = '/main.tpl.php';
		
		break;
	case 'accountlist':
		
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['title'] = "OLR Administration :: Account Search Results - ". substr(ucfirst($accountType),0,-4);
		$data['userName'] =  $userName;
		$data['header'] = "Account Search Results - ". substr(ucfirst($accountType),0,-4);
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['accountDAO'] = $accountDAO;
		$data['accountType']=$accountType;
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
		if($msg==4)
			$data['msgData'] = "Account Suspended Successfully.";
		if($msg==5)
			$data['msgData'] = "Account Activated Successfully.";
		$data['body'] = TEMPLATE_PATH_ADMIN.'/account.list.tpl.php';
		$data['bodyForm'] = '';
		
		// set the template to use
		$template = '/main.tpl.php';	
		
		break;
	case 'business_alert_list':
		
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['title'] = "OLR Administration :: Business Alert Results ";
		$data['userName'] =  $userName;
		$data['header'] = "Business Alert Results";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['listingDAO'] = $listingDAO;
		$data['totalRecord'] = $totRec;
		$data['totalSponsRecord'] = $totSponsRec;
		$data['sponsBusDAO'] = $sponsBusDAO;
		$data['list2catDAO'] = $list2catarray;
		$data['totlist2cat'] = $totlist2catRec;
		$data['body'] = TEMPLATE_PATH_ADMIN.'/alert.list.tpl.php';
		$data['bodyForm'] = '';
		
		// set the template to use
		$template = '/main.tpl.php';	
		
		break;
		
	case 'useredit':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/users.add-edit.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: User edit";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "User Edit";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';	
		
		break;
	case 'usersuspend':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/users.suspend.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: User Suspension";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "User Suspension";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';	
		
		break;
	case 'changetrustlevel':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/account.trustlevel.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Change Trust Level";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Change Trust Level";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		
		// set the template to use
		$template = '/main.tpl.php';
		
		break;
	case 'campaigns':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['title'] = "OLR Administration :: Campaigns";
		$data['userName'] =  $userName;
		$data['header'] = "Campaigns";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['campaignDAO'] = $campaignDAO;
		$data['totalRecord'] = $totRec;
		$data['startRec'] = ($start+1);
		$data['endRec'] = $end;
		$data['timeStart'] = $time;
		$data['pagingInfo'] = $pagingInfo;
		if($msg==1)
			$data['msgData'] = "Campaign Added Successfully.";
		if($msg==2)
			$data['msgData'] = "Campaign Updated Successfully.";	
		if($msg==3)
			$data['msgData'] = "Campaign Deleted Successfully.";	
		$data['body'] = TEMPLATE_PATH_ADMIN.'/campaigns.list.tpl.php';
		$data['bodyForm'] = '';
		// set the template to use
		$template = '/main.tpl.php';
		break;
	case 'campaignadd':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/campaign.add-edit.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Add New Campaign";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Add New Campaign";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';			
		
		break;	
	case 'campaignedit':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/campaign.add-edit.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Edit Campaign";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Edit Campaign";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';
		break;
	case 'assigncampaign':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/campaign.assign.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Assign Campaign";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Assign Campaign";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';
		break;
	case 'addbustocampaign':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/campaign.add.business.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Select Campaign To Add Businesses";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Select Campaign To Add Businesses";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		// set the template to use
		$template = '/main.tpl.php';
		break;
	case 'campaigntocsv':
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
		
		// *-*-*-*-*-*		- QUICK FORM RENDERER -
		$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
		$form->accept(&$renderer);
		
		// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
		$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_ADMIN);
		$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/campaign.csv.tpl.php');
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
    	$data['title'] = "OLR Administration :: Select Campaign For CSV Data";
		$data['formErrors'] = &$form->_errors;
		$data['userName'] =  $userName;
		$data['header'] = "Select Campaign For CSV Data";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = $bodyForm;
		$data['body'] = '';
		if($msg==1)
			$data['msgData'] = "Campaign Businesses Added To The CSV File Successfully. <br>File Name: $fileName";
		// set the template to use
		$template = '/main.tpl.php';
		break;
	default:
		// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$userName = $dataBank->getVar('s_a_user_name');
	
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['title'] = "OLR Administration :: Debug";
		$data['userName'] =  $userName;
		$data['header'] = "Debug";
		$data['left-panel'] = TEMPLATE_PATH_ADMIN.'/left-panel.tpl.php';
		$data['bodyForm'] = '';
		$data['body'] = TEMPLATE_PATH_ADMIN.'/debug.tpl.php';
		$data['view'] = $view;
		$data['action'] = $action;
		$template = '/main.tpl.php';
}
// END OF VIEW CONTROLLER SWITCH
##########++++++++++ TEMPLATE PART ++++++++++##########
// NOW ACTUALLY RENDER THE SELECTED VIEW AND - DISPLAY MAIN TEMPLATE
$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_ADMIN_MAIN);
$aT->parseTemplate($data,$template);
?>