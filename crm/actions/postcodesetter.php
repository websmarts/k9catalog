<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}



// Get businessID
		$bus = &new business();
		$bus->_dao->accountID=$accountID;
		$numbus = $bus->_dao->find(true);
		$businessArray = $bus->_dao->toArray();
		$businessID = $businessArray['businessID'];
		

switch ($action)
{
	case "postcodesetter":
		
	
		$form = &new HTML_QuickForm('postcodesetter','POST','');
    
		$form->addElement('hidden', '_a','postcodesetter');	
		$form->addElement('hidden','city',null);
		
		$form->addElement('text', 'postcode',null, array('style'=>'width:50px;'));
		
		
		$form->addElement('submit', 'submit_button',' Set Your Postcode ', array('class'=>'inputbutton'));
		
		// set current postcoe as default
		$form->setDefaults( array('postcode'=>$systemPostcode) );
		
		if ($form->validate() ) {
			
			// check if they have submitted a POSTCODE or a CITY name
			
			
			# set in cookie
			setCookieValue("c_postcode",$form->exportValue('postcode') );
			
			
			
			
			// redirect to VIEW where location setting occurred
			$query = "_a=home";
			httpRedirect($query);
			// exit;
			
		} else {
		
			$view = $action;
			break;
		}
		
	
		
}
	
?>