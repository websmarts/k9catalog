<?php
//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$listingID = $_REQUEST['listingID'];
		
		if ($listingID < 1 ) {
			echo "ERROR - trying to do action=jobedit with invalid  listingID<br>";
			echo "JOBEDIT: listingID=$listingID <br>";
			exit;
		}

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$priorityTypeArr = Constants::getPriorityTypeArr();
		$responseTypeArr = Constants::getResponseTypeArr();
		$ResponseTypeArr= Constants::getResponseArr();
		$EstimatedTypeArr= Constants::getEstimatedArr();

		// :: elements
		$form = &new HTML_QuickForm('jobform','POST','index.php');
		$form->addElement('hidden', '_a','jobedit');
		$form->addElement('hidden', 'listingID',$listingID);
		$form->addElement('hidden', 'status','temp');
		$form->addElement('hidden', 'listingType','job');
//		$form->addElement('text','title',null, array('size'=>40));
		$form->addElement('textarea', 'shortDescription',null, array('cols'=>40,'rows'=>5,style=>'width:350px;height:80px'));
//		$form->addElement('textarea', 'fullDescription',null, array('cols'=>30,'rows'=>6));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>6));
//		$form->addElement('text','startDate',null,'readonly', array('size'=>40));
//		$form->addElement('text','endDate',null,'readonly', array('size'=>40));
		$form->addElement('select', 'priority',null, $priorityTypeArr);
		$form->addElement('select', 'responseType',null, $responseTypeArr,array( 'onchange' =>'return review_form()') );
		$form->addElement('text','contactDetails',null, array('size'=>40));
		$form->addElement('select', 'responseLimit',null, $ResponseTypeArr);
		$form->addElement('select', 'estimatedValue',null, $EstimatedTypeArr);				
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		//get default values from business table
		$listingDAO = &new listing();
		$listingArray = $listingDAO->getOneRecordArray($listingID);
		
		$form->setDefaults($listingArray);
		
//		$form->addRule('title', '"listing title" is a required field.', 'required', null, 'client');
		$form->addRule('shortDescription', '"listing short description" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateListingEditFields($fields)
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
					return array('title' => 'Job details, post code and contact details are required fields');
				}
				
				if ( ($f4 == '') && ($f5=='direct') ) {
					return array('title' => 'contact details required with job type "Call Me"');
				}
				return true;
			}
			$form->addFormRule('validateListingEditFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
	//		$title = $form->getSubmitValue('title');
			$shortDescription = $form->getSubmitValue('shortDescription');
			$postcode = $form->getSubmitValue('postcode');

			# STEMMIG title and short description
			$objStem = new Stemmer();
	//		$stemmedTitleArr = $objStem->stem_list_z($title);
	//		$stemmedTitleStr = @implode(" ",$stemmedTitleArr);
			$stemmedShortDescrArr = $objStem->stem_list_z($shortDescription);
			$stemmedShortDescrStr = @implode(" ",$stemmedShortDescrArr);

			// 1. update data into table
			$formValues = $form->getSubmitValues();

			$otherValues = array('status'=>'pending','stemmedShortDescription'=>$stemmedShortDescrStr,'createdDate'=>time());
			
			// kill contact details if responseType is 'I will call'
			if ($formValues['responseType'] != 'direct' ) {
				$formValues['contactDetails'] = '';
			}
			
			$formValues = array_merge($formValues,$otherValues);
//echo dumper($formValues);
//DB_DataObject::debugLevel(1);
			$listingDAO = &new listing();
			$listingDAO->updateRecord($listingID, $formValues);
			# REDIRECT TO CONFIRMATION PAGE
			$url= "index.php?_a=jobconfirm&listingID=$listingID";

			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;

		}
		else
		{
			$view=$action;
		}
 ?>