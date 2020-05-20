<?php

//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{
	case "buscatlist":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		$view=$action;

		break;

	case "buscatadd":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$parentCatOptions = getCatList('yes'); //get category list from functions

		// :: elements
		$form = &new HTML_QuickForm('buscatadd','POST','index.php?_a=buscatadd');
		$form->addElement('text','categoryName',null, array('size'=>40));
		$form->addElement('select','parentCategoryID',null, $parentCatOptions);
		$form->addElement('submit','submit_button',' Add ', array('class'=>'inputbutton'));

		#++++ JS validation rules
		$form->addRule('categoryName', '"category name" is a required field.', 'required', null, 'client');
		$form->addRule('parentCategoryID', '"parent category" is a required field.', 'required', null, 'client');
		
			#++++ Validate Login :: Form level 
			function validateBizCatAddFields($fields)
			{
				$f1 = $fields['categoryName'];
				$f2 = $fields['parentCategoryID'];
				if (($f1 == '') || ($f2 == '')) {
					return array('categoryName' => 'category name and parent category are required field');
				}
				return true;
			}
			$form->addFormRule('validateBizCatAddFields');

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$catName = $form->getSubmitValue('categoryName');
			$pCatName = $form->getSubmitValue('parentCategoryID');

			// 1. update data into table
			$formValues = $form->getSubmitValues();
			$cat = &new category();
			$cat->insertRecord($formValues);

			//$url=  "index.php?_a=busedit&msg=1&businessID=".$businessID;
			$url=  "index.php?_a=buscatlist&msg=1&pg=0";
			//print $url;
			header('HTTP/1.1 301 Moved Permanently');
			header("Location: " . $url);
			header('Connection: close');
			exit;	
		}
		else
		{		
			$view = $action;
		}
		
		break;

	case "buscatedit":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS ID -
		$businessCatID = (int)$_REQUEST['businessCatID'];
	
		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: values
		$childCatOptions = getChildCategories(); //get ALL possible categoryChildren

		// :: elements
		$form = &new HTML_QuickForm('buscatedit','POST','index.php');
		
		$form->addElement('hidden','_a','buscatedit');
		$form->addElement('hidden','businessCatID',$businessCatID);
		$form->addElement('text','categoryName',null, array('size'=>40,'readonly'=>true));
		$form->addElement('select','childCategoryIDs',null, $childCatOptions,array('multiple'=>true,'size'=>'40'));
	
		$form->addElement('submit','submit_button',' Save ', array('class'=>'inputbutton'));

		#++++ FORM DEFAULTS / CONSTANTS -
		$cat = &new category();
		$catArray = $cat->getOneRecordArray($businessCatID);
		$form->setDefaults($catArray);
		
		
		$currentChildIDs = array_keys( getChildCategories($businessCatID) );
		$e =& $form->getElement('childCategoryIDs');
		$e->setSelected($currentChildIDs);
		
		//echo dumper($form);
				
	

		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{
			#++++ get the form values
			$parentID = $form->getSubmitValue('businessCatID');
			$subcats = $form->getSubmitValue('childCategoryIDs');
			
			if ( (is_array($subcats) && count($subcats)>0 ) ) {
					// for each child set its parentID
					
					// make all current children cats orphans first
					$cat = new category();
			
					$cat->_dao->parentCategoryID = 0; // sets orphan status
					$cat->_dao->whereAdd('parentCategoryID ='. $parentID);
					$cat->_dao->update(DB_DATAOBJECT_WHEREADD_ONLY);
			
					//echo dumper($subcats);
					// now set the correct parent for allsubcats
					foreach ($subcats as $id ) {
//	echo $id."<br>";
						$cat = new category();
//		

						
						$cat->_dao->get($id);						
						$cat->_dao->parentCategoryID = $parentID;
						$cat->_dao->update();
						
						
					}				
			}
	
				$url=  "index.php?_a=buscatlist&msg=2&pg=0";
				header('HTTP/1.1 301 Moved Permanently');
				header("Location: " . $url);
				header('Connection: close');
				exit;	
			} else {
				$view = $action;
			}
				
		break;

	case "buscatdelete":
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION -
		$auth = &new AuthorizeAdmin();
		$auth->userValid();
		$isValidUser = $auth->userRole('1,2');
		//----------------------------------------------------

		// *-*-*-*-*-*		- GET BUSINESS CAT ID -
		$businessCatID = (int)$_REQUEST['businessCatID'];

		# DELETE RECORD AS WELL AS RELATED SUB CATEGORIES FROM TABLE
		$sql = "DELETE FROM categories WHERE (categoryID=$businessCatID OR parentCategoryID=$businessCatID)";
		$catDAO = &new category();
		$catDAO->getRecordsFromQuery($sql);

		//this is used to delete after editing a record #++ to remove msg set at the time of edit
		$url = $_SERVER['HTTP_REFERER'];
		$pattern = "/&msg=([0-9]+)/";
		$replacement = "";
		$url = preg_replace($pattern, $replacement, $url);
		
		$url = str_replace("_a=buscatlist","_a=buscatlist&msg=3",$url);

		header('HTTP/1.1 301 Moved Permanently');
		header("Location: " . $url);
		header('Connection: close');
		exit;	
		break;
}
?>