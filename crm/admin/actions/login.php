<?php

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
				
				#++++ 2. redirect to admin login page
				#++++ Redirect user to either home page or requested page before login page
				$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
				if(trim($dataBank->getVar('query_string'))!="")
				{
					$url = HOST1 . $dataBank->getVar('query_string');
					$dataBank->setVar('query_string','');
					header('HTTP/1.1 301 Moved Permanently');
					header("Location: " . $url);
					header('Connection: close');
					exit;					
				}
				else
				{
					header('HTTP/1.1 301 Moved Permanently');
					header("Location: index.php?_a=myaccount");
					header('Connection: close');
					exit;	
				}
			} 
			else 
			{
				$form->setElementError('userName', 'Invalid username/password combination'); // set the page message
				$view = 'login';
			}
		}
		else 
		{
			$view= $action;
		}

		break;

	case "logout":
		#++++ 1. clear session data
		$dataBank =& new DataBank(SESSION_KEY_ADMIN_FORMS);
		$dataBank->deleteData();
		
		#++++ 2. redirect to admin login page
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: index.php?_a=login");
		header('Connection: close');
		exit;	  

		break;

	}
?>	