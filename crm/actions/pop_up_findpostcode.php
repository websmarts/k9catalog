<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action)
{
	case "pop_up_findpostcode":
	/*
		//----------------------------------------------------
		//	- GLOBAL AUTHORIZATION - REDIRECT TO HOME 
		//----------------------------------------------------
	*/

		// *-*-*-*-*-*		- FORM ELEMENTS / VALIDATIONS -
		// :: elements
		$form = &new HTML_QuickForm('pop_up_findpostcode','POST','index.php?_a=pop_up_findpostcode');
		$form->addElement('text','city','required',null, 'client');
		$form->addElement('submit','submit_button',' lookup ',array('class'=>'inputbutton'));
	
		#++++ JS validation rules
		$form->addRule('city', 'Enter a City or Postcode', 'required', null, 'client');
		
		// *-*-*-*-*-*		- FILTER SUBMITTED DATA -
		$form->applyFilter('__ALL__', 'trim');
		$form->applyFilter('__ALL__', 'strip_tags');
		
		// *-*-*-*-*-*		- VALIDATE FORM -
		if ($form->validate()) 
		{	
			$cityname=$_REQUEST['city'];
			
			//make_postcode_metaphones();
			
			// Check if city is a CITY or a POSTCODE
			if (preg_match("/\b(\d{4})\b/",$cityname,$m)) { // its actually a postcode
				$type='postcode';
				$sql = "SELECT * FROM postcodes WHERE postcode = '{$m[1]}'";	
			} else { // its a city name
				$cityMetaphone= metaphone($cityname);
				$sql = "SELECT * FROM postcodes WHERE metaphone LIKE '{$cityMetaphone}%'";
				//$sql = "SELECT * FROM postcodes WHERE city LIKE '$cityname%'";		
			}
				
			
			$postcode = &new postcode();
						
			$postcodeDAO = $postcode->getRecordsFromQuery($sql);
			
			/*
			// if N = 0 and if it is a city (not a postcode we are looking up 
			// then do a soundex lkokup
			if ($postcodeDAO->N < 1 && $type != 'postcode') {
				//echo "looking up using metaphone<br>";
				$cityMetaphone= metaphone($cityname);
				$sql = "SELECT * FROM postcodes WHERE metaphone LIKE '{$cityMetaphone}%'";	
				//echo $sql;
				$postcodeDAO = $postcode->getRecordsFromQuery($sql);
			} 
			*/
			
		}		
		$view = $action;
	break;
	}
?>