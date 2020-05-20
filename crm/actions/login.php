<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{
	
	
	case "login":
		
		// if login is direct - ie someone selected login - then clear the pending stuff
		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: elements
		$form = &new HTML_QuickForm('login','POST','index.php?_a=login');
		$form->addElement('text','userName',null, array('size'=>20));
		$form->addElement('password', 'password',null, array('size'=>20));
		$form->addElement('checkbox', 'rememberme',null,'remember me');
		$form->addElement('submit','login_button',' Login ',array('class'=>'inputbutton'));
			
		
		#++++ JS validation rules
		//$form->addRule('userName', '"Account name" is a required field.', 'required', null, 'client');
		//$form->addRule('password', '"Password" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level
			function validateLoginFields($fields)
			{
					$f1 = $fields['userName'];
					$f2 = $fields['password'];
					if (($f1 == '') || ($f2 == '')) {
						return array('userName' => 'Please enter your Account name and password');
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
			$rememberMe = $form->getSubmitValue('rememberme');
			
			

			
			#++++ Initialize Admin Model
			$account = &new account();

			if( $account->validClientLogin($userName,$password) ) 
			{
				#++++ Get DAO Reference
				$accountDAO = &$account->getDAOFetched();
				
				#++++ Save Admin in Session
				$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
				$dataBank->setVar('s_c_account_id', $accountDAO->accountID);
				$dataBank->setVar('s_c_user_name', $accountDAO->userName);
				$dataBank->setVar('s_c_start_time', time());
				$dataBank->setVar('s_c_account_type', $accountDAO->accountType);
				
				if ($rememberMe == 1) {
					
					$hashedAccountID = generateActivationKey($accountDAO->accountID);
					
					setcookie('c_rememberme',$hashedAccountID,time()+60*60*24*90 );
					//echo "Setting COOKIE c_rememberme = $hashedAccountID <br>";
				}
				
				
				
				
				#++++ Redirect user to either home page or requested page before login page
					// check if there is a pending request and redirect to that else go to newlogin
			
					
						// decide action to do after successful login
						// if there is a pendinguri then do that else do default
						$pendingQuery = $dataBank->getVar('s_c_pending_query');
						// remove the pending action from session now that we have logged in okay
						$dataBank->unSetVar('s_c_pending_query');
						
						
						if ( $pendingQuery > '' ) {
							httpRedirect($pendingQuery);	
						} else {
							if (strtoupper(substr($accountType,0,3)) == 'DOM') {
								$action='myjobs';
								
							} elseif (strtoupper(substr($accountType,0,3)) == 'BUS') {
								$action='myjoblist';
							} else {
								$action = 'home';
							}
							httpRedirect($action);
						}											
			} 
			else 
			{
				// check if account status is 'pending' - maybe they just registered!
				if( $account->validClientLogin($userName,$password,'pending') ) {
					$form->setElementError('userName', 'This account is not currently active - it needs to be activated - see help section'); // set the page message
				} else {
				
				$form->setElementError('userName', 'Invalid username/password combination'); // set the page message
				}	
				
			}
		}
			// *-*-*-*-*-*		- QUICK FORM RENDERER -
			$renderer = &new HTML_QuickForm_Renderer_QuickHtml(); 
			$form->accept(&$renderer);
			
			// *-*-*-*-*-*		- RENDER FORM COMPONENTS -
			$ftr = &new FormTemplateRenderer(TEMPLATE_PATH_CLIENT);
			$bodyForm = $ftr->getProcessedFormTemplate(&$renderer,'/login.tpl.php');
	
			// *-*-*-*-*-*		- TEMPLATE VARIABLES -
			$data['title'] = "OurLocalRag.com.au :: Login";
			$data['formErrors'] = &$form->_errors;
			$data['header'] = "Please Login";
			$data['right-panel'] = "";
			$data['bodyForm'] = $bodyForm;
			$data['body'] = '';
			
			$data['view_template'] = '/login.tpl.php';
			
			// set the template to use
			$template = '/main.tpl.php';

		break;

	case "logout":
		#++++ 1. clear session data
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$dataBank->deleteData();
		setCookieValue('HTTP_REF','');
		
		// Kill some local app vars
		unSet($accountID );
		unSet($accountType); 
		unSet($accountSubType); 
		
		setcookie('c_rememberme',0,time()- (60*60*24*90));
		unSet($_COOKIE['rememberme']);

		#++++ 2. redirect to admin login page
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: index.php?_a=home");
		header('Connection: close');
		exit;	  

		break;

	}
?>