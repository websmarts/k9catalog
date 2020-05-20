<?php
//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		//$auth->clientValid();// redirects to login if not
		$auth->checkAuth($action);
		//----------------------------------------------------

		#++++ get session data
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$priorityTypeArr = Constants::getPriorityTypeArr();
		$responseTypeArr = Constants::getResponseTypeArr();
		$ResponseTypeArr= Constants::getResponseArr();
		$EstimatedTypeArr= Constants::getEstimatedArr();

		// :: elements
		$form = &new HTML_QuickForm('jobform','POST','index.php');
			
		$form->addElement('hidden', '_a','jobadd');
		$form->addElement('hidden', 'listingType','job');
		//$form->addElement('text','title',null, array('size'=>40));
		$form->addElement('textarea', 'shortDescription',null, array('cols'=>40,'rows'=>5,style=>'width:350px;height:80px'));
//		$form->addElement('textarea', 'fullDescription',null, array('cols'=>30,'rows'=>6));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>6));
//		$form->addElement('text','startDate',null, array('size'=>20));
//		$form->addElement('text','endDate',null, array('size'=>20));
		$form->addElement('select', 'priority',null, $priorityTypeArr);
		$form->addElement('select', 'responseType',null, $responseTypeArr,array( 'onchange' =>'return review_form()') );
		$form->addElement('textarea','contactDetails',null, array('cols'=>30,'rows'=>2));
		$form->addElement('select', 'responseLimit',null, $ResponseTypeArr);
		$form->addElement('select', 'estimatedValue',null, $EstimatedTypeArr);				
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		
		//Should change this to Get the users postcode from their profile instead of using CURRENT_POSTCODE
		$userOBJ =& new user;
		$userDAO = $userOBJ->getDAO();
		
		$userDAO->accountID = $accountID;
		$userDAO->find();
		$userDAO->fetch();

		if ($userDAO->N )  { // default to the users profile postcode, and contact details
			
			$contactDetails = 'Call '.$userDAO->firstName . ' on ';
			if ($userDAO->cellPhoneNumber ) 
				$contactDetails .= 'mobile:'.$userDAO->cellPhoneNumber.' ';
			
			$contactDetails .= 'Ph:'.$userDAO->phoneAreaCode.' '.$userDAO->phoneNumber;
						
			$defaults = array(
												'postcode'=>$userDAO->postcode,
												'contactDetails'=>$contactDetails
												);			
			$form->setDefaults($defaults);	
		}	
		
		

		#++++ JS validation rules
	//	$form->addRule('title', '"Job name" is a required field.', 'required', null, 'client');
		$form->addRule('shortDescription', '"Job details" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateListingAddFields($fields)
			{
			//	$f1 = $fields['title'];
				$f2 = $fields['shortDescription'];
				$f3 = $fields['postcode'];
				$f4 = $fields['contactDetails'];
				$f5 = $fields['responseType'];
				$f6 = $fields['priority'];
				$f7 = $fields['startDate'];
				$f8 = $fields['endDate'];
								
				if ( ($f2 == '') || ($f3 == '')) {
					return array('shortDescription' => ' Job details and  Post code are required fields');
				}
			
				if ( ($f4 == '') && ($f5=='direct') ) {
					return array('title' => 'contact details are required when you want businesses to "Call Me"');
				}

				return true;
			}
			$form->addFormRule('validateListingAddFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
		//	$title = $form->getSubmitValue('title');
			$shortDescription = $form->getSubmitValue('shortDescription');
			$postcode = $form->getSubmitValue('postcode');

			# STEMMING title and short description
			$objStem = new Stemmer();
		//	$stemmedTitleArr = $objStem->stem_list_z($title);
		//	$stemmedTitleStr = @implode(" ",$stemmedTitleArr);
			$stemmedShortDescrArr = $objStem->stem_list_z($shortDescription);
			$stemmedShortDescrStr = @implode(" ",$stemmedShortDescrArr);

			// 1. insert data into listing table
			$formValues = $form->getSubmitValues();
			
			
			$otherValues = array(
														'accountID'=>$accountID,
														'stemmedShortDescription'=>$stemmedShortDescrStr,
														'createdDate'=>time(),
														'status'=>'temp'
														);
														
			// kill contact details if responseType is 'I will call'
			if ($formValues['responseType'] != 'direct' ) {
				$formValues['contactDetails'] = '';
			}
			
				
			$formValues = array_merge($formValues,$otherValues);
		

		//	echo dumper ($formValues);exit;
			$listingObj = &new listing();
			$listingID = $listingObj->insertRecord($formValues);

			# REDIRECT TO CONFIRMATION PAGE
			
			$query= "_a=jobconfirm&listingID=$listingID";
			httpRedirect($query);			
		}
		else
		{
			$view = $action;
		}
 ?>