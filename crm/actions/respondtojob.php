<?php

//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeClient();
		$businessUser = $auth->validBusinessUser(); // CHECK WHETHER USER TYPE IS BUSINESS OR NOT
		//----------------------------------------------------

		# GET REQUIRED DATA
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = $dataBank->getVar('s_c_account_id');
		$listingID = (int) @$_REQUEST['listingID'];
		$returnView =  @$_REQUEST['_v'];
		
		//Get the query for sourceView
		// and remove refs to listingID and  msg 
		$req_query = $dataBank->getVar('lastReqQuery'); 	
		$pattern[0] = "/&msg=[0-9]+/";
		$pattern[1] = "/&listingID=[0-9]+/";
		$req_query = preg_replace($pattern,'',$req_query);
		
		
		
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		//get listing details
		$sql = "SELECT * FROM listings WHERE listingID=$listingID";
		$listingObj = &new listing();
		$listingDAO = $listingObj->getRecordsFromQuery($sql);
		
		if ($listingDAO->N != 1) {
			
				$req_query .='&msg=x'; // listing does not exist - someone playing or coding logic error!!
				httpRedirect($req_query);			
				exit;
			
		}
		
		$listingDAO->fetch();
		// :: elements
		$form = &new HTML_QuickForm('respondtojob','POST','index.php?_a=respondtojob&listingID='.$listingID.'&from='.$from.'&currenturl='.urlencode($currURL));
		$form->addElement('static','jobtitle',null, $listingDAO->title);
		$form->addElement('static','shortDescription',null, $listingDAO->shortDescription);
//		$form->addElement('static','fullDescription',null, $listingDAO->fullDescription);
		$form->addElement('static','postcode',null, $listingDAO->postcode);
		$form->addElement('static','city',null, $listingDAO->city);
		if($listingDAO->responseType=='direct')
			$form->addElement('static','contactDetails',null, $listingDAO->contactDetails);
		else	
			$form->addElement('static','contactDetails',null, 'N/A');	
		$form->addElement('textarea','message',null, array('rows'=>5,'cols'=>30));
		$form->addElement('hidden','_v',$returnView);
		$form->addElement('submit','submit_button',' Submit ',array('class'=>'inputbutton'));
		
		#++++ JS validation rules
		$form->addRule('message', '"message" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level
			function validateLoginFields($fields)
			{
					$f1 = $fields['message'];
					if (($f1 == '')) {
						return array('message' => 'message is required field');
					}
					return true;
			}
			$form->addFormRule('validateLoginFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		
		// if its not business user then ask for login
		if(!$businessUser) // if its not a business user display an error
		{			

			$req_query .='&msg=1';
			httpRedirect($req_query);			
			exit;	
		}
		else 
		{

			$isJobInServiceArea = isJobInServiceArea($accountID,$listingID);
			if(!$isJobInServiceArea)
			{			
				$req_query .= '&msg=7&listingID='.$listingID;
			
				httpRedirect($req_query);		
				exit;
			} 
			else 
			{
	
				// if job is already responded by business then give error msg
				
				
				if(isJobResponded($accountID,$listingID) )
				{
					
					// delete from myalerts - just in case its there - Maybe we should just set status to DELETE hmm...
						$sql = "delete from myalerts where listingID=$listingID and accountID=$accountID";
						$myJobObj = new myJobs();
						$myJobObj->_dao->query($sql);
					
					$req_query .= '&msg=5';
					httpRedirect($req_query);
				
					exit;	
				}
				else 
				{
		
					
					
					// *-*-*-*-*-*		- VALIDATE FORM -
					if ($form->validate()) 
					{
						#++++ get the form values
						$message = $form->getSubmitValue('message');
			
						//check for unique business in myJobs table
						$isJobInBusinessJobList = isBusinessInJobList($accountID,$listingID); // returns either listID or 0
						if($isJobInBusinessJobList<1) // if not in the table insert in myJobs first
						{
							$myJobObj = new myJobs();
							$myJobValues = array('listingID'=>$listingID,'accountID'=>$accountID);
							$myJobObj->insertRecord($myJobValues);
						}
						
						// delete from myalerts - just in case its there - Maybe we should just set status to DELETE hmm...
						$sql = "delete from myalerts where listingID=$listingID and accountID=$accountID";
						$myJobObj->_dao->query($sql);
						
						
						
			
						// insert data in response table
						$listingResponseValues = array('listingID'=>$listingID,'accountID'=>$accountID,'message'=>$message,'responseDateTime'=>time(),'status'=>'open');
						$listingResponseObj = new listingresponse();
						$listingResponseObj->insertRecord($listingResponseValues);
			
						// Need to check which VIEW called us so we can return to the correct view
						if (strtolower($returnView) == 'mjl')
						{
							// return to MyJobList
							$req_query = '_a=myjoblist&msg=3';// add msg=3 to url - successfully responded to job
						}
						else 
						{
							// return to Main JobList
							$req_query = '_a=jobslist&msg=3';// add msg=3 to url - successfully responded to job
						}
						
						httpRedirect($req_query);
						exit;
			
					}
					$view = $action; // view same as action
				}
			}
		}
		
			
			

?>