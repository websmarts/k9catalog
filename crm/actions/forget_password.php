<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{
	case "forget_password":
	/*
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION - REDIRECT TO HOME 
		//----------------------------------------------------
	*/		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: elements
		$form = &new HTML_QuickForm('forgetpassword','POST','index.php?_a=forget_password');
		$form->addElement('text','email',null, array('size'=>30));
		$form->addElement('submit','button',' Submit ',array('class'=>'inputbutton'));
	
		#++++ JS validation rules
		$form->addRule('email', '"Email" is a required field.', 'required', null, 'client');
		
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$email = $form->getSubmitValue('email');

			#++++ Initialize Admin Model
			$account = &new account();

			if( $account->validemail($email) ) 
			{
	
			#++++ SQL QUERY FOR LIST
				$sql = "SELECT * FROM accounts WHERE email=$email";
				//print "<br>$sql<br>";
				$accountDAO = $account->getRecordsFromQuery($sql);
				while($accountDAO->fetch())
				{
					if($user=="")
						$user=$accountDAO->userName;
					else
						$user=$user.",".$accountDAO->userName;
					if($pass=="")
						$pass=$accountDAO->password;
					else
						$pass=$pass.",".$accountDAO->password;
				}
				$account->forget_password($user, $pass, $email);

				$view = 'forget_password'; // 
				$url= "index.php?_a=thanks&t=forget_password";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;
			} 
			else 
			{
				$form->setElementError('email', 'Invalid email address'); // set the page message
				$view = $action; // error 
			}
		}
		else
			$view = $action; // 

		break;
	}
?>