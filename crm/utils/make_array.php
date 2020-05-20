<?php

include ("stemmer/class.stemmer.inc");
require_once('../inc/configure.php');
	
# local db connection
mysql_connect("localhost","ourloca","6TbosW95") or die("Couldn't connect");
mysql_select_db("ourloca_olrdb") or die("Couldn't select db");
	





function seperate_and_lowercase ($str) {
		$newstr= strtolower(substr($str,1,1));
		for ($p=2;$p < strlen($str);$p++) {
			
			$ch = substr($str,$p,1);
			if ($ch == strtoupper($ch) ) {
				// add a space
				$newstr .= " ";
			}
			$newstr .= strtolower($ch);		
		}
		return $newstr;
}

function stem_string ($str) {
	$objStem = new Stemmer();
	
	// convert string into words
	$words = explode(" ",$str);

	if (is_array($words) ) {
		foreach ($words as $word) {
			
			$sword = $objStem->stem($word);
			$newstr .= $sword." ";
		}
		
	} else {
		$sword = $objStem->stem($str);
		$newstr = $sword;
	}
	$newstr = trim($newstr);
	return $newstr;
}


$c = file_get_contents("category_options.txt");

$opts = explode(',',$c);
$j=1;
$m=0;

foreach($opts as $k=>$v) {
	
	$v = trim ($v,"'");
	
	$i= explode ('->',$v);

	// split words on uppercase
	//echo $i[1]." ".strlen($i[1])."<br>";
	
	$str = $i[1];
	$str2 = $i[0];

	$newstr = seperate_and_lowercase($i[1]);
	
	$stemmed_string = stem_string($newstr);
	
			
	$cats[$i[0]][] = $i[1];
	$cats2[$i[0]][] = $newstr;
	$cats3[$i[0]][] = $stemmed_string;
	
	if($str2==$str1){
		echo "";
		print $j."   -   ".$m."   -  ";
		$sql= "insert into categories(categoryID,categoryName,parentCategoryID,isEnable,keywords,stemmed_keywords)
		values ('','".$str."',".$m.",'1','".$newstr."','".$stemmed_string."') ";
		}
	else
	{
		$m=$j;
		print $j."   -   0   -  ";
		$sql= "insert into categories(categoryID,categoryName,parentCategoryID,isEnable,keywords,stemmed_keywords)
		values ('','".$str."','0','1','".$newstr."','".$stemmed_string."') ";

		}

		

print($str1."1  -  ".$str2."2  -  ".$newstr."  -  ".$stemmed_string);	

print "<br>";
//$sql= "insert into categories(categoryID,categoryName,parentCategoryID,isEnable,keywords,stemmed_keywords)		values ('','".$str."',".$m.",'y','".$newstr."','".$stemmed_string."') ";
print "<br>";

$res = mysql_query($sql) or die($sql.mysql_error());
	$str1 = $i[0];

/*	$n++;
	if ($n >3) {
		break;
	}
*/	

$j++;
//if($j==25)
//die;
}


echo '<pre>';
//print (sizeof($cats));
//print_r($cats);
echo'</pre>';

?>