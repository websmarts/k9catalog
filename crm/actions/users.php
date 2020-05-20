<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}
switch ($action)
{
	case "registeruser":
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		// :: elements
		$form = &new HTML_QuickForm('registeruser','POST','index.php?_a=registeruser');
		$form->addElement('hidden','accountType',"domesticuser");
		$form->addElement('text','userName',null, array('size'=>40));
		$form->addElement('password', 'password',null, array('size'=>20));
		$form->addElement('text', 'email',null, array('size'=>40));
		$form->addElement('text', 'firstName',null, array('size'=>40));
		$form->addElement('text','lastName',null, array('size'=>40));
		$form->addElement('text','address1',null, array('size'=>40));
		$form->addElement('text', 'address2',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>4));
		$form->addElement('text', 'phoneAreaCode',null, array('size'=>2));
		$form->addElement('text', 'phoneNumber',null, array('size'=>20));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>20));
		$form->addElement('submit','submit_button',' Register ', array('class'=>'inputbutton'));
		#++++ FORM DEFAULTS / CONSTANTS -
		#++++ JS validation rules
		$form->addRule('userName', '"user name" is a required field.', 'required', null, 'client');
		$form->addRule('password', '"password" is a required field.', 'required', null, 'client');
		$form->addRule('email', '"email" is a required field.', 'required', null, 'client');
		$form->addRule('firstName', '"first name" is a required field.', 'required', null, 'client');
		$form->addRule('lastName', '"last name" is a required field.', 'required', null, 'client');
		$form->addRule('address1', '"address line 1" is a required field.', 'required', null, 'client');
		$form->addRule('city', '"city" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateUserRegistrationFields($fields)
			{
				$f1 = $fields['userName'];
				$f2 = $fields['password'];
				$f3 = $fields['email'];
				$f4 = $fields['firstName'];
				$f5 = $fields['lastName'];
				$f6 = $fields['address1'];
				$f7 = $fields['city'];
				$f8 = $fields['postcode'];
				$f9 = $fields['phoneNumber'];
				$f10 = $fields['cellPhoneNumber'];
				if (($f1 == '') || ($f2 == '') || ($f3 == '') || ($f4 == '') || ($f5 == '') || ($f6 == '') || ($f7 == '') || ($f8 == '') || ($f9 == '' && $f10 == '')) {
					return array('title' => 'user name, password, email, first name, last name, address line 1, city, post code and phone/cell are required fields');
				}
				return true;
			}
			$form->addFormRule('validateUserRegistrationFields');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
		
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			$newValues = array('status'=>'pending','modified'=>time());
			$formValues = @array_merge($formValues,$newValues);
			
			$userName = $formValues['userName'];
			$accountObj = &new account();
			if($accountObj->uniqueAccount($userName,0))  //check unique username
			{			
				// 1. insert data into accounts table
				$accountObj = &new account();
				$accountID = $accountObj->insertRecord($formValues);
	
				//2. insert data into users table
				$accountArray = array('accountID'=>$accountID);
				$allValues = array_merge($accountArray,$formValues);
				$userObj = &new user();
				$userID = $userObj->insertRecord($allValues);
				# SEND ACTIVATION EMAIL WITH ACTIVATION KEY
				$activationKey = generateActivationKey($accountID);
				
				$name = $allValues['userName'];
				$to = $allValues['email'];
				$activationLink = HOST."/index.php?_a=useractivation&key=".htmlentities(urlencode($activationKey));
				$accountObj->sendUserActivationEmail($activationLink, $name, $to);
				# REDIRECT TO THANKS PAGE
				$url= "index.php?_a=thanks&t=registeruser";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;
			}
			else
			{
				$form->setElementError('userName', 'User Name Already Exist!'); // set the page message
				$view = $action;
			}
			
		}
		else
		{
			$view = $action;
		}
		
		break;
		
	case "useractivation":
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
			{
				$flag = false;	
			}
		}
		# REDIRECT TO THANKS PAGE
		if($flag) {
			$url= "index.php?_a=thanks&t=useractivation";
		}
		else {
			$url= "index.php?_a=thanks&t=useractivationproblem";	
		}
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;
		
		break;		
		
	case "myprofile":	
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------
		#++++ get session data
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		#++++ SQL QUERY FOR LIST
		$cond = " WHERE a.accountID=u.accountID and a.accountID=$accountID";
		$sql = "SELECT * FROM accounts a , users u $cond";
		//print "<br>$sql<br>";
		$accountObj = &new account();
		$accountDAO = $accountObj->getRecordsFromQuery($sql);
		$accountDAO->fetch();
		$view = $action;
		break;
	case "editmyprofile":
	
		#++++ get session data
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		// :: elements
		$form = &new HTML_QuickForm('editmyprofile','POST','index.php?_a=editmyprofile');
		$form->addElement('hidden','original_email',null);
		
		$form->addElement('password', 'password1',null, array('size'=>20));
		$form->addElement('password', 'password2',null, array('size'=>20));
		$form->addElement('text', 'email',null, array('size'=>40));
		$form->addElement('text', 'firstName',null, array('size'=>40));
		$form->addElement('text','lastName',null, array('size'=>40));
		$form->addElement('text','address1',null, array('size'=>40));
		$form->addElement('text', 'address2',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('style'=>'width:4em'));
		$form->addElement('text', 'phoneAreaCode',null, array('style'=>'width:2em'));
		$form->addElement('text', 'phoneNumber',null, array('size'=>20));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>20));
		$form->addElement('submit','submit_button',' Update Profile ', array('class'=>'inputbutton'));
		
	
		
		#++++ FORM DEFAULTS / CONSTANTS -
		#++++ SQL QUERY FOR LIST
		$cond = " WHERE a.accountID=u.accountID and a.accountID=$accountID";
		$sql = "SELECT * FROM accounts a , users u $cond";
		//print "<br>$sql<br>";
		$accountObj = &new account();
		$accountDAO = $accountObj->getRecordsFromQuery($sql);
		$accountDAO->fetch();
		# Getting data for Updation
		//$accountUpdate['userName']=$accountDAO->userName;
		$accountUpdate['password']=$accountDAO->password;
		$accountUpdate['email']=$accountDAO->email;
		$accountUpdate['original_email']=$accountDAO->email;
		$accountUpdate['firstName']=$accountDAO->firstName;
		$accountUpdate['lastName']=$accountDAO->lastName;
		$accountUpdate['address1']=$accountDAO->address1;
		$accountUpdate['address2']=$accountDAO->address2;
		$accountUpdate['city']=$accountDAO->city;
		$accountUpdate['postcode']=$accountDAO->postcode;
		$accountUpdate['phoneAreaCode']=$accountDAO->phoneAreaCode;
		$accountUpdate['phoneNumber']=$accountDAO->phoneNumber;
		$accountUpdate['cellPhoneNumber']=$accountDAO->cellPhoneNumber;
		$userID = $accountDAO->userID;
		$form->setDefaults($accountUpdate);
		#++++ JS validation rules
		//$form->addRule('userName', '"user name" is a required field.', 'required', null, 'client');
		//$form->addRule('password', '"password" is a required field.', 'required', null, 'client');
		$form->addRule('email', '"email" is a required field.', 'required', null, 'client');
		$form->addRule('firstName', '"first name" is a required field.', 'required', null, 'client');
		$form->addRule('lastName', '"last name" is a required field.', 'required', null, 'client');
		$form->addRule('address1', '"address line 1" is a required field.', 'required', null, 'client');
		$form->addRule('city', '"city" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateMyProfileFields($fields)
			{
			
				//echo dumper ($fields);
			
				//$f1 = $fields['userName'];
				//$f2 = $fields['password'];
				$f3 = $fields['email'];
				$f4 = $fields['firstName'];
				$f5 = $fields['lastName'];
				$f6 = $fields['address1'];
				$f7 = $fields['city'];
				$f8 = $fields['postcode'];
				$f9 = $fields['phoneNumber'];
				$f10 = $fields['cellPhoneNumber'];
				if ( ($f3 == '') || ($f4 == '') || ($f5 == '') || ($f6 == '') || ($f7 == '') || ($f8 == '') || ($f9 == '' && $f10 == '')) {
					return array('title' => ' email, first name, last name, address line 1, city, post code and phone/cell are required fields');
				}
				
				// // check if new password fields have been filled in and they match
				if (strlen($fields['password1']) > 3 && ($fields['password1'] != $fields['password2'])) {
					return array('title' =>'Password entries do not match or are less than 4 characters long');
				} 
				return true;
			}
			$form->addFormRule('validateMyProfileFields');
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
		
			#++++ get the form values
			$formValues = $form->getSubmitValues();
			$newValues = array('userID'=>$userID, 'modified'=>time());
			// check if password has been changed
			if (strlen($formValues['password1']) > 3 ) { // okay we are changing password here
				
				$newValues['password'] = encrypt_password($formValues['password1']);
				$passwd_changed = 1;
			} 
			if ($formValues['email'] != $formValues['original_email']) {
					$newValues = array('status'=>'pending','modified'=>time());
					$reactivate_user = true;
			}
				
			$formValues = @array_merge($formValues,$newValues);
			

			$accountObj = &new account();
			if(1)  //check unique username
			{
				// 1. update data for accounts table
				$accountObj = &new account();
				$accountObj->updateRecord($accountID,$formValues);
				//2. update data for users table
				$userObj = &new user();
				
				
				$userID = $userObj->updateRecord($userID, $formValues);
				# SEND ACTIVATION EMAIL WITH ACTIVATION KEY
				# ONLY NEED TO DO IF EMAIL ADDRESS HAS CHANGED
				if (!$reactivate_user) {		
					
					# Redirect to editmyprofile page
					$query = "_a=editmyprofile&msg=".(1+$passwd_changed);
					httpRedirect($query);
				} else {
					$activationKey = generateActivationKey($accountID);
					$name = $formValues['userName'];
					$to = $formValues['email'];
					$activationLink = HOST."/index.php?_a=useractivation&key=".htmlentities(urlencode($activationKey));
					$accountObj->sendUserActivationEmail($activationLink, $name, $to);
					# REDIRECT TO THANKS PAGE
					$query= "_a=thanks&t=edituser";
					httpRedirect($query);
				}
			}
			else
			{
				// this should be impossible because users cannot edit their userName 
				$form->setElementError('userName', 'User Name Already Exist!'); // set the page message
				$view = $action;
			}
			
		}
		else
		{
			$view = $action;
		}
		
		break;
}
?>