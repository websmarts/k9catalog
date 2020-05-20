<?php

$timingStart = explode(' ', microtime()); 
//echo phpinfo();
error_reporting(E_ALL & ~ (E_NOTICE));
//error_reporting(0);
#++++ include necessary files
require_once('inc/configure.php');
require_once('inc/functions.php');

require_once ('HTML/QuickForm.php');
require_once ('HTML/QuickForm/Renderer/QuickHtml.php');	
require_once(OBJ_PATH.'/_extlib/tplengine/AwesomeTemplateEngine.class.php');
require_once(OBJ_PATH.'/_extlib/tplengine/FormTemplateRenderer.class.php');
require_once(OBJ_PATH.'/constants/Constants.php');
require_once(OBJ_PATH.'/storage/DataBank.php');
require_once(OBJ_PATH.'/authorize/AuthorizeClient.php');





# GET SESSION DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		
		
		if (empty($accountID) && isSet($_COOKIE['c_rememberme']) ) {// This may be a returning visitor who wants to be remembered
			// check the rememberme credential and do auto login if valid		
			$account =& new Account();		
			$rememberedAccountID= $account->validClientRememberMe($_COOKIE['c_rememberme']);
			if( $rememberedAccountID > 0 ) 
			{
				$accountobj = DB_DataObject::factory('Accounts');
				if ($accountobj->get($rememberedAccountID ) == 1) { // if accountID is valid we only get one row			
					$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
					$dataBank->setVar('s_c_account_id', $accountobj->accountID);
					$dataBank->setVar('s_c_user_name', $accountobj->userName);
					$dataBank->setVar('s_c_start_time', time());
					$dataBank->setVar('s_c_account_type', $accountobj->accountType);
					
					$data['flags']['rememberme_return'] = true; // so we can flag Welcome back msg
				}
			}	
		}


$action = @trim($_REQUEST['_a']); 



if($action=='') {
		$action = "home";
}




###NEW SWITCH
##########++++++++++ ACTION PART ++++++++++##########
$actionStatus = false; // assume actions are successful - actions that fail must set this to false to prevent chaining






$action = strtolower($action);
switch ($action)
{
	case 'newlogin':
	case "login":
	case "logout":
			include ("actions/login.php");
			break;
		
	default:
		
	
		// *-*-*-*-*-*		- TEMPLATE VARIABLES -
		$data['title'] = " :: Debug";
		$data['header'] = "Debug";
		$data['right-panel'] = '';
		$data['bodyForm'] = '';
		$data['body'] = TEMPLATE_PATH_CLIENT.'/debug.tpl.php';
		$data['action'] = $action;
		$template = '/main.tpl.php';		

}		
// END OF PRIMARY ACTION SWITCH





	
	
	
##########++++++++++ TEMPLATE PART ++++++++++##########
	// NOW ACTUALLY RENDER THE SELECTED VIEW AND - DISPLAY MAIN TEMPLATE
	$aT=&new AwesomeTemplateEngine(TEMPLATE_PATH_CLIENT_MAIN);
	
	$data['execution_time'] = get_current_time(); // record how long the script ran to get to this point
	$aT->parseTemplate($data,$template);


######### Cleanup - save session, close databases, and files etc

// should only be last GET

$dataBank->setVar('msg',''); // we got through the request so blow away any stored  redirect messages
$dataBank->setVar('lastView',$view);
exit;

?>