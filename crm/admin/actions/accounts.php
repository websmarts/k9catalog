<?php

//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{
	case "accountsearch":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$accountTypeOptions = Constants::getAccountTypeList(); 
		// :: elements
		$form = &new HTML_QuickForm('accountsearch','POST','index.php?_a=accountsearch');
		$form->addElement('text','accountName',null, array('size'=>20));
		$form->addElement('select','accountType',null, $accountTypeOptions);
		$form->addElement('text', 'postcode',null, array('size'=>5));
		$form->addElement('submit','search_button',' Find ',array('class'=>'inputbutton'));

		$form->addRule('accountType', '"accountType" is a required field.', 'required', null, 'client');

			#++++ Validate Login :: Form level 
			function validateAccountSearchFields($fields)
			{
				/*$f1 = $fields['accountName'];
				$f2 = $fields['accountType'];
				$f3 = $fields['postcode'];
				if ( ($f1 == '') && ($f3 == '')) {
					return array('accountName' => ' Either Account Name or Postcode is required for a search');
				}
				*/
				return true;
			}
			$form->addFormRule('validateAccountSearchFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		
		if ($form->validate()){
				#++++ get the form values
			$accountName = $form->getSubmitValue('accountName');
			$accountType = $form->getSubmitValue('accountType');
			$postcode = $form->getSubmitValue('postcode');

			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url=  "index.php?_a=accountlist&account_name=$accountName&account_type=$accountType&post_code=$postcode&pg=0";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		} else 
		{
			$view = $action;
		}
		
		break;
		
	case "accountlist":
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
		$accountName = trim($_REQUEST['account_name']);
		$accountType = trim($_REQUEST['account_type']);
		$postcode = trim($_REQUEST['post_code']);
		if($accountType=='businessuser')
		{
			$accountType1 = "('businessuser','businessuser1','businessuser2')";
		}
		else
			$accountType1 = "('" . $accountType . "')";	

		# GENERATE CONDITION
		$cond  = "";
		$cond .= " WHERE ( accounts.accountType IN " . $accountType1;
		if($accountName!='')
				$cond  .= " AND accounts.userName LIKE '". $accountName . "%'";
		
		if($accountType=='domesticuser')
			$table = 'users';
		else
			$table = 'businesses';
			
		if($postcode!='')
				$cond .= " AND $table.postcode='" . $postcode . "'";

		$cond .= " AND accounts.accountID=$table.accountID AND accounts.status<>'deleted' )";

		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT COUNT(*) AS tot FROM accounts,$table $cond";
		//print "<br>".$sql."<br>";
		$accountObj = &new account();
		$accountDAO = $accountObj->getRecordsFromQuery($sql);
		$accountDAO->fetch();
		$totRec =$accountDAO->tot;

		#++++ PAGING PARAMS
		$page = $pg;
		$gap = 20;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		#++++ SQL QUERY FOR LIST
		$sql = "SELECT * FROM accounts,$table $cond ORDER BY userName LIMIT $start,$gap";
		//print "<br>".$sql."<br>";
		$accountObj = &new account();
		$accountDAO = $accountObj->getRecordsFromQuery($sql);
		
		$view = $action;

		break;

	case "accountdelete":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------

		//GET QUERY STRING VARS FROM REFERER
		$str = $_SERVER['HTTP_REFERER'];
		parse_str($str,$output);
		$accountName = $output['account_name'];
		$accountType = $output['account_type'];
		$postcode = $output['post_code'];

		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$accountID = $_REQUEST['accountID'];

		$sql = "UPDATE accounts SET status='deleted' WHERE accountID=$accountID";
		$accountObj = &new account();
		$accountDAO = $accountObj->executeQuery($sql);

		# REDIRECT TO LISTING PAGE
		$url=  "index.php?_a=accountlist&account_name=$accountName&account_type=$accountType&post_code=$postcode&pg=0&msg=3";
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;
		
		break;		

	case "changetrustlevel":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();

		//----------------------------------------------------

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$trustLevelTypeOptions = Constants::getTrustLevelTypeList(); 
		$accountID=(int)@$_REQUEST['accountID'];
		$currentURL = @urlencode($_REQUEST['currenturl']);

		$sql = "SELECT * FROM accounts WHERE accountID=$accountID";
		$accountObj = &new account();
		$accountDAO = $accountObj->getRecordsFromQuery($sql);
		$accountDAO->fetch();

		// :: elements
		$form = &new HTML_QuickForm('changetrustlevel','POST',"index.php?_a=changetrustlevel&accountID=".$accountID."&currenturl=".$currentURL);
		$form->addElement('static','userName',null, $accountDAO->userName);
		$form->addElement('static','accountType',null, $accountDAO->accountType);
		$form->addElement('select','trustLevel',null, $trustLevelTypeOptions);
		$form->addElement('submit','submit_button',' Change Level ',array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		$accountArray = array('trustLevel'=>$accountDAO->trustLevel);
		$form->setDefaults($accountArray);
		
		#++++ JS validation rules
		$form->addRule('trustLevel', '"trust level" is a required field.', 'required', null, 'client');

			#++++ Validate Login :: Form level 
			function validateTrustLevelFields($fields)
			{
				$f1 = $fields['trustLevel'];
				if ( $f1 == '' ) {
					return array('trustLevel' => ' Trust level is a required field');
				}
				return true;
			}
			$form->addFormRule('validateTrustLevelFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		
		if ($form->validate()){
				#++++ get the form values
			$trustLevel = $form->getSubmitValue('trustLevel');
			$sql = "UPDATE accounts SET trustLevel='$trustLevel' WHERE accountID=$accountID";
			$accountObj = &new account();
			$accountDAO = $accountObj->executeQuery($sql);

			$currentURL = urldecode($currentURL);
			//print $currentURL;
			# HAVE TO REDIRECT DUE TO LAST PAGE
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $currentURL);
			header('Connection: close');
			exit;	
		} else 
		{
			$view = $action;
		}
		
		break;
	
}
?>