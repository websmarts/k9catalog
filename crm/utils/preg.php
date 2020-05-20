<?php
function dumper ($a) {
 	$html = '<pre>';
  $html .=print_r($a,true);
	$html .='</pre>';
	return $html;
}


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
/////////////////////////////////////////////////////////////

	$jobstring = <<<EOD
(((PHP (recursive acronym for "PHP: Hypertext Preprocessor") is a widely-used Open Source'd general-purpose scripting language that is especially suited for Web development and can be embedded into HTML. 

Simple answer, but what does that mean? An example:)))
EOD;

$jobstring = remove_unwanted_chars($jobstring);

echo htmlentities($jobstring);

$jobstring = implode (" ", process_words($jobstring,'removeduplicates',0) ); // get UNIQUE list of words 
	
	
$cat_stemmed_keywords = " windows clean windows sill pane putty winder glazier word1 word2 word3 word4 word 5 word 6";
	
	
	
 
 
 
 $job_pattern = '/\b('. preg_replace ("/( )+/","|",trim($jobstring)).')\b/i';
 print "<p>";
 print 'cat_pattern='.$cat_pattern .'<br>';
 print 'job_pattern='.$job_pattern .'<br>';
 
 print 'JOBSTRING='.$jobstring.'<br>';


	
$timingStart = explode(' ', microtime()); 
$nrMatches2 += preg_match_all($job_pattern,$cat_stemmed_keywords ,$m);
print sprintf("%.6f seconds",get_current_time());

print "<br>".$nrMatches2."<br>";
 
 ?>
