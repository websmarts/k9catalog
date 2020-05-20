<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}



// Get businessID

		//DB_DataObject::debugLevel(1);
		$bus = DB_DataObject::factory('Businesses');
		$bus->accountID=$accountID;
		$numbus = $bus->find(true);
		$businessID = $bus->businessID;
	

switch ($action)
{
	case "couponadd":
			//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
	
		$form = &new HTML_QuickForm('coupon','POST','');
    
		$form->addElement('hidden', '_a','couponadd');	
		$form->addElement('text','couponName',null, array('size'=>40));
		$form->addElement('textarea', 'couponDetail',null, array('cols'=>40,'rows'=>4));
		$form->addElement('text', 'startDateYear',null, array('style'=>'width:40px;'));
		$form->addElement('text', 'startDateMonth',null, array('style'=>'width:20px;'));
		$form->addElement('text', 'startDateDay',null, array('style'=>'width:20px;'));
		
		$form->addElement('text', 'endDateYear',null, array('style'=>'width:40px;'));
		$form->addElement('text', 'endDateMonth',null, array('style'=>'width:20px;'));
		$form->addElement('text', 'endDateDay',null, array('style'=>'width:20px;'));
		
		$form->addElement('submit', 'submit_button',' Save ', array('class'=>'inputbutton'));
		
		if ($form->validate() ) {
			
			// save the coupon
			$coupon = new coupon();
			$coupon->_dao->businessID = $businessID;
			$coupon->_dao->couponName = $form->exportValue('couponName');
			$coupon->_dao->couponDetail = $form->exportValue('couponDetail');
			$coupon->_dao->startDateTime = $form->exportValue('startDateYear')."-".$form->exportValue('startDateMonth')."-".$form->exportValue('startDateDay');
			$coupon->_dao->endDateTime = $form->exportValue('endDateYear')."-".$form->exportValue('endDateMonth')."-".$form->exportValue('endDateDay');
			
			$coupon->_dao->status = 'approved';// NOTE: Should be PENDING to implement coupon approvals
			
		//	echo dumper($coupon);
			
			$coupon->_dao->insert();
			
			
			
			
			// redirect to list coupons
			$query = "_a=couponadd";
			httpRedirect($query);
			// exit;
			
		} else {
		
			$view = $action;
			break;
		}
		
		case "couponedit":
			//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$auth->clientValid();
	
		$form = &new HTML_QuickForm('coupon','POST','');
    
		$form->addElement('hidden', '_a','couponedit');	
		$form->addElement('hidden', 'couponid',$_REQUEST['couponid']);
		
		$form->addElement('text','couponName',null, array('size'=>40));
		$form->addElement('textarea', 'couponDetail',null, array('cols'=>40,'rows'=>6));
		$form->addElement('text', 'startDateYear',null, array('style'=>'width:40px;'));
		$form->addElement('text', 'startDateMonth',null, array('style'=>'width:20px;'));
		$form->addElement('text', 'startDateDay',null, array('style'=>'width:20px;'));
		
		$form->addElement('text', 'endDateYear',null, array('style'=>'width:40px;'));
		$form->addElement('text', 'endDateMonth',null, array('style'=>'width:20px;'));
		$form->addElement('text', 'endDateDay',null, array('style'=>'width:20px;'));
		
		$form->addElement('submit', 'submit_button',' Update ', array('class'=>'inputbutton'));
		
		// get the coupon detail to populate the form
		$coupon = new coupon();
		
	//	echo dumper($_REQUEST);
		$coupon->_dao->get($_REQUEST['couponid']);
		$coupon->_dao->fetch();
		
		$values = $coupon->_dao->toArray();
		// split up the dates
		list($date, $time) = split(" ",$values['startDateTime']);	
		list($year,$month,$day) = explode ("-",$date);
		$values['startDateYear'] = $year;
		$values['startDateMonth'] = $month;
		$values['startDateDay'] = $day;
		
		list($date, $time) = split(" ",$values['endDateTime']);	
		list($year,$month,$day) = explode ("-",$date);
		$values['endDateYear'] = $year;
		$values['endDateMonth'] = $month;
		$values['endDateDay'] = $day;
		
		
		
		$form->setDefaults($values);
		
		if ($form->validate() ) {
			
			// save the coupon
			
			
		//	echo dumper($coupon->_dao);
			
			//$coupon->_dao->businessID = $businessID;
			$coupon->_dao->couponName = $form->exportValue('couponName');
			$coupon->_dao->couponDetail = $form->exportValue('couponDetail');
			$coupon->_dao->startDateTime = $form->exportValue('startDateYear')."-".$form->exportValue('startDateMonth')."-".$form->exportValue('startDateDay');
			$coupon->_dao->endDateTime = $form->exportValue('endDateYear')."-".$form->exportValue('endDateMonth')."-".$form->exportValue('endDateDay');
			
			$coupon->_dao->status = 'approved';// NOTE: Should be PENDING to implement coupon approvals
			
		//	echo dumper($coupon);
			
			$coupon->_dao->update();
			
			
			
			
			// redirect to list coupons
			$query = "_a=couponadd";
			httpRedirect($query);
			// exit;
			
		} else {
		
			$view = $action;
			break;
		}
		
		case 'couponindex';
			$view = $action;
			break;
		
}
	
?>