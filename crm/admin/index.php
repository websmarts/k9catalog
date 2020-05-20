<?php
error_reporting(E_WARNING);
#++++ include necessary files
require_once('../inc/configure.php');
require_once('../inc/functions.php');
require_once('../inc/class.stemmer.php');
require_once(OBJ_PATH . '/_extlib/tplengine/AwesomeTemplateEngine.class.php');
require_once(OBJ_PATH . '/_extlib/tplengine/FormTemplateRenderer.class.php');
require_once ('HTML/QuickForm.php');
require_once ('HTML/QuickForm/Renderer/QuickHtml.php');	
require_once(OBJ_PATH.'/constants/Constants.php');
require_once(OBJ_PATH.'/storage/DataBank.php');
require_once(OBJ_PATH.'/authorize/AuthorizeAdmin.php');
require_once(OBJ_PATH.'/db/accounts.class.php');
require_once(OBJ_PATH.'/db/account2postcode.class.php');
require_once(OBJ_PATH.'/db/businesses.class.php');
require_once(OBJ_PATH.'/db/categories.class.php');
require_once(OBJ_PATH.'/db/business2category.class.php');
require_once(OBJ_PATH.'/db/listings.class.php');
require_once(OBJ_PATH.'/db/postcodes.class.php');
require_once(OBJ_PATH.'/db/postcodeproximity.class.php');
require_once(OBJ_PATH.'/db/users.class.php');
require_once(OBJ_PATH.'/db/campaigns.class.php');
require_once(OBJ_PATH.'/db/listing2category.class.php');
require_once(OBJ_PATH.'/db/myjobs.class.php');
require_once(OBJ_PATH.'/db/myalerts.class.php');
#++++ collect the action
$action = @$_REQUEST['_a'];
#++++ redirect to login page if no action specified
if($action=="")
{
	header ("Location: index.php?_a=login");
	exit;
}	
##########++++++++++ ACTION PART ++++++++++##########
// The action controllers make any CHANGES to the data models and selecting the next view
switch ($action)
{
	case "login":
	case "logout":
			include "actions/login.php";
			break;
	case "myaccount":
			include "actions/myaccount.php";
			break;
	case "bussearch":
	case "buslist":
	case "buslist2":
	case "busadd":
	case "busedit":
	case "busdelete":
	case "business_alert_list":
			include "actions/business.php";
			break;
	case "buscatlist":
	case "buscatadd":
	case "buscatedit":
	case "buscatdelete":
			include "actions/business_category.php";
			break;
	case "listinglist":
	case "listingapprove":
	case "listingapproved":
	case "listingreject":
			include "actions/listings.php";
			break;
	case "accountsearch":
	case "accountlist":
	case "accountdelete":
	case "changetrustlevel":
	case "accountsuspend":
	case "accountactive":
	case "usersuspend":
			include "actions/accounts.php";
			break;
	case "useredit":
			include "actions/users.php";
			break;
	case "campaigns":
	case "campaignadd":
	case "campaignedit":
	case "campaigndelete":
	case "assigncampaign":
	case "addbustocampaign":
	case "campaigntocsv":
			include "actions/campaigns.php";
			break;
	
	default:
		
		
}
##########++++++++++ MAIN VIEW PART ++++++++++##########
// The view controller sets the view elements (templates) and 
// is responsible for getting any data to support the view being generated
include "viewController.php";
######### Cleanup - save session, close databases, and files etc
exit;
?>