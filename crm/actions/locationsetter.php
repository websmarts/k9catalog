<?php
//exit if its a direct request to the page
if(!defined('IN_SCRIPT')){header('HTTP/1.0 404 not found');exit;}

switch ($action) {
	case 'locationsetter':

		// Get businessID
				$bus = &new business();
				$bus->_dao->accountID=$accountID;
				$numbus = $bus->_dao->find(true);
				$businessArray = $bus->_dao->toArray();
				$businessID = $businessArray['businessID'];
				
		
				// check if postcode has been changed
				if ($_REQUEST['postcode'] == $_COOKIE['c_postcode'] and preg_match("/\d{4}/",$_COOKIE['c_postcode']) ) {
					// nothing to do so return to generating VIEW
					$query = $dataBank->getVar('lastReqQuery');
					// get rid of any location_errors in GET req
					$query = preg_replace("/&location_error=[^\b]*/","",$query);
					httpRedirect($query);
				}
				
				// check if it is a postcode or a city that has been entered
				$loc = trim($_REQUEST['postcode']);
				
				if (preg_match("/\d{4}/",$loc )) { // 4 digits so it looks like a postcode
					
					// Check that postcode submitted is valid
					$pcode = DB_DataObject::factory('Postcodes');
					$pcode->postcode = $loc;
					$n = $pcode->find();
					
					if ( $n) {
						# set in cookie
						setCookieValue("c_postcode",$loc );			
						// now redirect to the generating view
						$query = $dataBank->getVar('lastReqQuery');
						// get rid of any location_errors in GET req
						$query = preg_replace("/&location_error=[^\b]*/","",$query);
						httpRedirect($query);
					} else {
						// postcode is not valid
						$query = $dataBank->getVar('lastReqQuery');
						$query .= "&location_error=invalid postcode";
						httpRedirect($query);
					}
					
				} else { // not a postcode so assume city search
					
						// try for an exact match first
						$sql = "SELECT postcodes.*,SUBSTRING(postcodes.postcode,1,1) as STATE FROM postcodes WHERE city LIKE '{$loc}' ORDER BY STATE";
						//echo dumper($sql);
						$pcode = DB_DataObject::factory('Postcodes');
						$pcode->query($sql);
						while($pcode->fetch()) {
							$locations[] = $pcode->toArray();
							$n++;
						}
						
						//echo dumper($n);
						//echo dumper($locations);
						if ($n == 1) { // found one exact match so set the postcode
							# set in cookie
							setCookieValue("c_postcode",$locations[0]['postcode'] );			
							// now redirect to the generating view
							$query = $dataBank->getVar('lastReqQuery');
							// get rid of any location_errors in GET req
							$query = preg_replace("/&location_error=[^\b]*/","",$query);
							httpRedirect($query);
							
						} elseif ($n > 1) { // found more than one match so go with this list
							
						} else { // zero matcs found so try with metaphones
						
						
								// no exact match so try for partial match on metaphone
							
								$cityMetaphone= metaphone($loc);
								$sql = "SELECT postcodes.*,SUBSTRING(postcodes.postcode,1,1) as STATE FROM postcodes WHERE metaphone LIKE '{$cityMetaphone}%' ORDER BY STATE";
								$pcode = DB_DataObject::factory('Postcodes');
								$pcode->query($sql);
								while($pcode->fetch()) {
									$locations[] = $pcode->toArray();
									$n++;
								}
						}
						
						$view = $action;
				}
				
				break;
		case 'locationclarifier':
		
			$loc = $_REQUEST['postcode'];
			if (preg_match("/\d{4}/",$loc )) { // 4 digits so it looks like a postcode
					
					// Check that postcode submitted is valid
					$pcode = DB_DataObject::factory('Postcodes');
					$pcode->postcode = $loc;
					$n = $pcode->find();
					
					if ( $n) {
						# set in cookie
						setCookieValue("c_postcode",$loc );			
						// now redirect to the generating view
						$query = "_a=home";
						// get rid of any location_errors in GET req
						httpRedirect($query);
					} else {
						// postcode is not valid
						$query = "_a=home";
						$query .= "&location_error=invalid postcode";
						httpRedirect($query);
					}
				}
		
			break;
		
		
		
}
			
		
	
	
?>