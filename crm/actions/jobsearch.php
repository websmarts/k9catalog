<?php
// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$distanceArray = Constants::getDistanceArray();
		// This array defines what is displayed each TAB heading
		$tabs = Constants::getSearchTabsArray();
		// goTab selects the active tab
		$goTab = 'job';

		// :: elements
		$form = &new HTML_QuickForm('listingsearch','POST','index.php?_a=jobsearch');
		$form->addElement('text', 'searchText',null, array('size'=>20));
		$form->addElement('text', 'postcode',null, array('size'=>5));
		$form->addElement('select', 'distance',null, $distanceArray);
		$form->addElement('checkbox', 'search_in_title',null,'');
		$form->addElement('submit','search_button',' Search ',array('class'=>'inputbutton'));

		# GET SESSION DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$keywords = $dataBank->getVar('s_c_keywords');

		#++++ FORM DEFAULTS / CONSTANTS -
		$cookArray = array('postcode'=>$_COOKIE['c_postcode'],'distance'=>$_COOKIE['c_distance'],'searchText'=>$keywords);
		$form->setDefaults($cookArray);

		#++++ JS validation rules
		//$form->addRule('searchText', '"search text" is a required field.', 'required', null, 'client');
		$form->addRule('distance', '"distance" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" is a required field.', 'required', null, 'client');
		$form->addRule('postcode', '"post code" requires only numbers.', 'numeric', null, 'client');

			#++++ Validate Login :: Form level 
			function validateListingSearchFields($fields)
			{
				$f1 = $fields['searchText'];
				$f2 = $fields['distance'];
				$f3 = $fields['postcode'];
				if (($f1 == '') || ($f2 == '') || ($f3 == ''))
				{
					return array('searchText' => 'All the fields are required for a search');
				}
				return true;
			}
			$form->addFormRule('validateListingSearchFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$searchText = $form->getSubmitValue('searchText');
			$distance = $form->getSubmitValue('distance');
			$postcode = $form->getSubmitValue('postcode');
			
			# set in cookie
			setCookieValue("c_postcode",$postcode);
			setCookieValue("c_distance",$distance);
			
			# set in session
			$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
			$dataBank->setVar('s_c_keywords',$searchText);
			
			# HAVE TO REDIRECT DUE TO PAGING AND SEARCH RESULTS
			$url= "index.php?_a=jobslist&search_text=$searchText&post_code=$postcode&distance=$distance&pg=0";
			httpRedirect($url);
		}
		else
		{
			$view = 'comboSearchForm';
		}

?>