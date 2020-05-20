<?php
//exit if its a direct request to the page

switch ($action)
{



	case "localadsedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
		//----------------------------------------------------

		#++++ get session data
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$adsCategoriesArr = Constants::getAdsCategoriesArray();

		// :: elements
		$form = &new HTML_QuickForm('adsadd','POST','index.php');
		$form->addElement('hidden', '_a','localadsedit');
		$form->addElement('hidden', 'status','approved');
		$form->addElement('hidden', 'localadID','');
		$form->addElement('text','title',null, array('size'=>40));
		$form->addElement('textarea', 'description',null, array('cols'=>30,'rows'=>3));
		$form->addElement('text', 'city',null, array('size'=>40));
		$form->addElement('text', 'postcode',null, array('size'=>4));
		$form->addElement('text','contactDetails',null, array('size'=>40));	
		$form->addElement('text', 'price',null, array('size'=>4));	
		$form->addElement('text','pricenote',null, array('size'=>40));
		$form->addElement('select', 'adCategory',null, $adsCategoriesArr);			
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

	
		
		

		#++++ JS validation rules
		$form->addRule('title', '"Item" is a required field.', 'required', null, 'client');
		$form->addRule('description', '"Item description" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');
		$form->addRule('price', '"price" requires only numbers.', 'numeric', null, 'client');
				
			#++++ Validate Login :: Form level 
			function validateFields($fields)
			{
				$f1 = $fields['title'];
				$f2 = $fields['description'];
				$f3 = $fields['postcode'];
				$f4 = $fields['contactDetails'];
				
								
				if ( ($f1 == '') || ($f2 == '') || ($f3 == '')) {
					return array('title' => 'Ad title, description, post code and contact details are required fields');
				}
				

				return true;
			}
			$form->addFormRule('validateFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		
		
		
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			
			

			// 1. insert data into localads table
			$formValues = $form->getSubmitValues();
			$otherValues = array('accountID'=>$accountID,'created'=>time(),'status'=>'approved');
			$formValues = array_merge($formValues,$otherValues);


		  //echo dumper ($formValues);
		  //DB_DataObject::debugLevel(1);

			$localad = DB_DataObject::factory('localads');
			$localad->get($_REQUEST['localadID']);
			$localad->setFrom($formValues);
			$localad->update();

			
			
			# REDIRECT TO CONFIRMATION PAGE
			
			$query ="_a=mylocalads";
			httpRedirect($query);			
		}
		else
		{
			
				#++++ FORM DEFAULTS / CONSTANTS -
		
		//Should change this to Get the users postcode from their profile instead of using CURRENT_POSTCODE
		if (isSet($_COOKIE['c_postcode']) ) {
			$cookArray = array('postcode'=>$_COOKIE['c_postcode']);
			$form->setDefaults($cookArray);
		}
		
		// get the localad to edit
		$localad = DB_DataObject::factory('Localads');
		$localad->localadID = $_REQUEST['localadID'];
		// set the accountID to make sure it belongs to current account
		$localad->accountID = $accountID;
		$n = $localad->find();
		echo "N=$n <br>\n";
		if ($n !=1 ) { // okay
			
			// redirect to mylocalads
			$query = '_a=mylocalads';
			httpRedirect($query);
		}
		
		$localad->fetch();
		$recValues = $localad->toArray();
		unSet($recValues['status']);// dont want to set status from stored record
		$form->setDefaults($recValues);
			
			
			$view = $action;
		}
		
		break;
}