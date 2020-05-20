<?
/*
 *
 * Timing function - timingStart is done at the start of Request
 * then call this function any time to get the elapsed time
 *
 */
function get_current_time () {
	global $timingStart;
	$stop_time = explode(' ', microtime());
	$current = $stop_time[1] - $timingStart[1];
	$current += $stop_time[0] - $timingStart[0];
	return $current;
}


/*
 * This function handles duplicate WORDS in a string
 * It has two modes of operation
 * If mode='getduplicates' then it retuns an array that contains any duplicated words
 * and if mode != 'getduplicates' it returns an array of unique words in string
 */
function process_words ($str,$mode='getduplicates',$minStrLength=0) {
		
	$found_words = array(); // we will return this array populated with any duplicated words
	
	$str = trim_and_single_space_string($str);
	
	
//	echo "STR=$str and MODE=$mode minStrLength=$minStrLength <br>";
	
	
	// remove any leading, trailing or duplicate spaces
	$str = preg_replace ("/( )+/"," ",trim($str));
	
	$words = explode(" ",$str);
	if (is_array($words) )
	{
		foreach ($words as $word) {
			$count[$word]++;
		}


		if ($mode == 'getduplicates') 
		{		
			// now retrieve the duplicates i.e. where count > 1
			foreach ($count as $word =>$num) {				
				if ($num > 1) {
					if (strlen($word) > $minStrLength) {
						for (--$num;$num>0;$num--) {
							$found_words[] = $word;
						}
					}
				}
			}
				
		} 
		else 
		{ // strip duplicates and return unique words
			
			foreach ($count as $word=>$num) {
				
				// word length filter could be put here 
				if (strlen($word) > $minStrLength ) {					
							$found_words[] = $word;
				}
			}
		}
		return $found_words;
	}	
	return false; // only if there were NO words in string
}

/*
 * utility function that removes space for left and right of string
 * also replaces multiple spaces with a single space throughout the string
 */
function trim_and_single_space_string($str) {
	return preg_replace ("/( )+/"," ",trim($str));
}

/*
 * Utility function that removes uwanted characters form the string
 * Used by category match ranking to cleanup the user enetered data
 */
function remove_unwanted_chars($string) {
	
	// replace annoying characters characters with a space:
	//
		$string = preg_replace ("/\W+ /"," ",$string); //remove  non word characters at end of word
		$string = preg_replace ("/ \W+/"," ",$string); //remove  non word characters at start of word
		$string = preg_replace ("/^\W+/","",$string); //remove  non word characters at start of string
		$string = preg_replace ("/\W+$/","",$string); //remove  non word characters at end of string
		$string = preg_replace ("/'/","",$string); // remove single quotes
		$string = preg_replace ("/-/"," ",$string); // convert hyphen to space
		$string = preg_replace ("/\s/"," ",$string); // convert whitespace characters to definite space
		
	
	return $string;

}

/*
 * This function is used to rank each business category against the contents of a job listing
 */
function make_array_rank($listingID) {

	$sql = "SELECT * FROM listings WHERE listingID=$listingID";
	$listingObj = &new listing();
	$listingDAO = $listingObj->getRecordsFromQuery($sql);
	$totRec = $listingDAO->N;
	$listingDAO->fetch();
	$title = $listingDAO->title;
	$description = $listingDAO->shortDescription;
	$postcode = $listingDAO->postcode;
	$stemmedTitle = $listingDAO->stemmedTitle;
	$stemmedShortDescription = $listingDAO->stemmedShortDescription;
	

	$job_match_string = $title." ".$description;

	$job_match_words = process_words($jobstring,'removeduplicates',3); // get array of UNIQUE words of min length
	
	// stem the unique words
	$objStem = new Stemmer();
	if (is_array($job_match_words) ) {
		foreach ($job_match_words as $word) {
			$stemmed_job_words[$word] = $objStem->stem($word);
		}
	}
	$job_stemmed_string = implode(" ",$stemmed_job_words); 
	
	
	
	// This is the PATTERN we will use to test against each category
	$job_pattern = '/\b('. preg_replace ("/( )+/","|",trim($job_stemmed_string)).')\b/i';
	
	// now use our PATTERN to scan through and rank each category
	$sql = "select categoryID,categoryName,stemmed_keywords from categories";
	$catObj = &new category();
	$catDAO = $catObj->getRecordsFromQuery($sql);
	while ($catDAO->fetch) {
		 
		 // count the matches
		 $nrMatches = preg_match_all($job_pattern,$catDAO->stemmed_keywords ,$m);
		 
		 // save the category match data
		 $rank_arry[$catDAO->categoryID] = array($catDAO->categoryID,$catDAO->categoryName,$numMatches);
		 $sort[$catDAO->categoryID]= $numMatches; // tis aray is used for multisort below
	}	 
	
	// sort array by ranking	
	
	array_multisort($sort, SORT_DESC, $rank_arry);	
	return array($rank_arry);
}
?>