<?php
		//----------------------------------------------------	
		setCookieValue('HTTP_REF',$_SERVER['HTTP_REFERER']); // SET REDIRECT URL ON LOGIN
		//----------------------------------------------------

		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$msg = (int)@$_REQUEST['msg'];
		$distanceArray = Constants::getDistanceArray();
		

		// :: elements

		$form = &new HTML_QuickForm('jobslist','GET','index.php?_a=jobslist');
		$form->addElement('text', 'searchText',null, array('size'=>24,'class'=>'inputbox','id'=>'searchText'));
		$form->addElement('text', 'postcode',null, array('size'=>3,'maxlength'=>4,'class'=>'inputbox'));
		$form->addElement('select', 'distance',null, $distanceArray,array('class'=>'inputbox'));
		
		$s =& $form->getElement('distance');
		$s->setSelected(100);
		
	
		
		/*$s =& $form->createElement('select','distance',null,$distanceArray,array('class'=>'inputbox'));
		//$s->loadArray($distanceArray);
		$s->setSelected(100);
		$form->addElement($s);
		*/
		
		
		$form->addElement('hidden','_a','jobslist');
		$form->addElement('submit','search_button',' Find ',array('class'=>'inputbutton'));
	
		
		$form->addElement('checkbox', 'search_in_service_area',null,' only jobs in my service area');
	
		$sa = & $form->getElement('search_in_service_area');
		$sa->setChecked(true);

		
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		
		#++++ FORM DEFAULTS / CONSTANTS -
		
		
		//$form->updateElementAttr('search_in_service_area','checked');
		
		
		
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ( $form->validate()) 
		{
			#++++ get the form values
			$searchText = $form->getSubmitValue('searchText');
			$distance = $form->getSubmitValue('distance');
			$postcode = $form->getSubmitValue('postcode');
			$searchInServiceArea = (int)@$form->getSubmitValue('search_in_service_area');
			
			# set in cookie
			setCookieValue("c_postcode",$postcode);
			setCookieValue("c_distance",$distance);
			setCookieValue('c_search_in_service_area',$searchInServiceArea);
			
			
			# set in session
			$dataBank->setVar('s_c_keywords',$searchText);
			
			
		}	
	
	
		
		

		
		
		
		
		
		
		
		
		
		
	
		$view = $action;
 ?>