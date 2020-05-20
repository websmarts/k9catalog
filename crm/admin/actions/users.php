<?php

//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{

	case "useredit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		//GET QUERY STRING VARS FROM REFERER
		$str = $_SERVER['HTTP_REFERER'];
		parse_str($str,$output);
		$accountName = $output['account_name'];
		$accountType = $output['account_type'];
		$postcode = $output['post_code'];
	
		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$userID = $_REQUEST['userID'];

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList(''); //get category list from functions

		// :: elements
		$form = &new HTML_QuickForm('useredit','POST','index.php?_a=useredit&userID='.$userID . '&account_name='.$accountName.'&account_type='.$accountType.'&post_code='.$postcode);
		$form->addElement('text', 'firstName',null, array('size'=>40));
		$form->addElement('text', 'lastName',null, array('size'=>40));
		$form->addElement('text', 'address1',null, array('size'=>40));
		$form->addElement('text', 'address2',null, array('size'=>40));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>40));
		$form->addElement('text', 'phoneAreaCode',null, array('size'=>40));
		$form->addElement('text', 'phoneNumber',null, array('size'=>40));
		$form->addElement('text', 'cellPhoneNumber',null, array('size'=>40));
		$form->addElement('submit','submit_button',' Save ',array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		//get default values from business table
		$userObj = &new user();
		$userArray = $userObj->getOneRecordArray($userID);

		$form->setDefaults($userArray);
		
		#++++ JS validation rules
		$form->addRule('firstName', '"first name" is a required field.', 'required', null, 'client');
		$form->addRule('lastName', '"last name" is a required field.', 'required', null, 'client');
		$form->addRule('address1', '"address line 1" is a required field.', 'required', null, 'client');
		$form->addRule('city', '"city" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateUserRegistrationFields($fields)
			{
				$f1 = $fields['firstName'];
				$f2 = $fields['lastName'];
				$f3 = $fields['address1'];
				$f4 = $fields['city'];
				$f5 = $fields['postcode'];
				$f6 = $fields['phoneNumber'];
				$f7 = $fields['cellPhoneNumber'];
				if (($f1 == '') || ($f2 == '') || ($f3 == '') || ($f4 == '') || ($f5 == '') || ($f6 == '' && $f7 == '')) {
					return array('firstName' => 'first name, last name, address line 1, city, post code and phone/cell are required fields');
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

			//GET QUERY STRING VARS FROM QUERY STRING
			$str = $_SERVER['QUERY_STRING'];
			parse_str($str,$output);
			$accountName = $output['account_name'];
			$accountType = $output['account_type'];
			$postcode = $output['post_code'];

			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$userObj = &new user();
			$userObj->updateRecord($userID, $formValues);
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url=  "index.php?_a=accountlist&account_name=$accountName&account_type=$accountType&post_code=$postcode&pg=0&msg=2";

			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;

		}
		else
		{
			$view=$action;
		}
		
		break;

}
?>