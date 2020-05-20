<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{
	case "listinglist":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
		#++++ GET REQUEST PARAMETERS
		$msg = @(int)$_REQUEST['msg'];
		$pg =  @(int)$_REQUEST['pg'];
		# GENERATE CONDITION
		$cond = " WHERE status='pending'";
		# TOTAL RECORD COUNT FOR PAGING
		$sql = "SELECT COUNT(*) AS tot FROM listings $cond";
		$query = &new listing();
		$listingDAO = $query->getRecordsFromQuery($sql);
		$listingDAO->fetch();
		$totRec =$listingDAO->tot;
		#++++ PAGING PARAMS
		$page = $pg;
		$gap = 20;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);
		#++++ SQL QUERY FOR LIST
		$sql = "SELECT * FROM listings  $cond ORDER BY title LIMIT $start,$gap";
		$query = &new listing();
		$listingDAO = $query->getRecordsFromQuery($sql);
		
		$view = $action;
		break;
	case "listingapprove":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
	
		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$listingID = $_REQUEST['listingID'];
		//$keyword = @$_REQUEST['keyword'];
		//$pcode = @$_REQUEST['postcode'];
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$listingTypeArr = Constants::getListingTypeArray();
		// :: elements
		$form = &new HTML_QuickForm('listingapprove','POST','index.php?_a=listingapprove&listingID='.$listingID);
		$form->addElement('hidden', 'hiddenalert');
		$form->addElement('hidden','_a','listingapprove');
		
		$form->addElement('text', 'title', null, array('size'=>40));
		$form->addElement('textarea', 'shortDescription', null, array('cols'=>35,'rows'=>3));
		$form->addElement('text', 'postcode', null, array('size'=>40));
		
		//$form->addElement('submit','confirm_button','Confirm', array('class'=>'inputbutton'));
		//$form->addElement('submit','update_button','Update', array('class'=>'inputbutton'));
		
		#++++ FORM DEFAULTS / CONSTANTS -
		$listingOBJ = &new listing();
		$listingDAO = $listingOBJ->getDAO();
		$listingDAO->get($listingID);
		
			
		$form->setDefaults($listingDAO->toArray());
		
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		function validateFields() {
			return true;
		}
		
		$form->addFormRule('validateFields');
		
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate() ) 
		{
			// 1. update data into table
			$formValues = $form->getSubmitValues();
			
				
			
			// check if CONFIRM or UPDATE button was pressed
			if (trim(strtolower($_REQUEST['submit_button'])) == 'confirm') {
				$otherValues['status'] = 'approved';
			} else {
				$otherValues['status'] = 'pending';
			}
			
			
			
			$otherValues['stemmedTitle'] = getStemmedString($formValues['title']);
			$otherValues['stemmedShortDescription'] = getStemmedString($formValues['shortDescription']);
			$otherValues['approveDate'] = time();
			$otherValues['isEditable'] = 'y';
					
			$recordValues = array_merge($formValues,$otherValues);				
			$listingDAO->setFrom($recordValues);
			$listingDAO->update();
			
				
			
			
			// update the listings2category table with the current cat values
			$cond = " WHERE listingID='$listingID'";
			$sql = "DELETE FROM listing2category $cond";
			$list2catObj = &new listing2category();
			//$listingcatDAO = $list2catObj->executeQuery($sql);
			$list2catObj->query($sql);
			
			
			
			if(is_array(@$_REQUEST['catids']) ) 
			{
				$list2catObj = &new listing2category();
				foreach($_REQUEST['catids'] as $catid=>$val)
				{
					$catValues = array('listingID'=>$listingID,'categoryID'=>$catid);
					$result = $list2catObj->insertRecord($catValues);
				}
			}
						
			
			
			if (strtoupper($_REQUEST['submit_button']) =='CONFIRM')
			{
				
				send_local_alerts(&$listingDAO);
				
				
				// do a redirect to approve pending lists
				$url = "?_a=listinglist&pg=0";
				httpRedirect($url);				
				exit;		
					 
			}
		}
		
			// Get the categories that match this listing
			$catids = make_array_rank($listingDAO) ;
			
			// get the currently selected categories for this listing
			$list2catObj =& new listing2category();
			$list2catObj->_dao->listingID=$listingID;
			$list2catObj->_dao->find();
			
			while ($list2catObj->_dao->fetch())
			{
				$selectedCats[$list2catObj->_dao->categoryID] = ' checked ';
			}
			
				
			// get the list of businesses that match this listing
			$businesses = & get_businesses(&$listingDAO);
					
				
			$view=$action;
		
		
		break;
	case "listingreject":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		//----------------------------------------------------
		// *-*-*-*-*-*		- GET BUSINESS ID & MSG PARAMETER -
		$listingID = $_REQUEST['listingID'];
		$form = &new HTML_QuickForm('listingreject','POST','index.php?_a=listingreject&listingID='.$listingID);
		$form->addElement('text', 'listingType','readonly', null);
		$form->addElement('text', 'title','readonly', null);
		$form->addElement('textarea', 'shortDescription','readonly', null, array('cols'=>35,'rows'=>3));
		$form->addElement('textarea', 'adminNotes', null, array('cols'=>35,'rows'=>5));
		$form->addElement('submit','submit_button',' Confirm ', array('class'=>'inputbutton'));
		#++++ FORM DEFAULTS / CONSTANTS -
		$listingDAO = &new listing();
		$listingArray = $listingDAO->getOneRecordArray($listingID);
		
		$form->setDefaults($listingArray);
		
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$listingDAO = &new listing();
			$listingDAO->updateRecord($listingID, $formValues);
			
		$sql = "UPDATE listings SET status='rejected' WHERE listingID=$listingID";
		$listingObj = &new listing();
		$listingOBJ->query($sql);
			# REDIRECT TO CONFIRMATION PAGE
			$url= "index.php?_a=listinglist&msg=2";
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;
		}
		$view=$action;
		break;		
}
?>