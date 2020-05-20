<?php
//error_reporting(E_WARNING);
$word = $_REQUEST['word'];

if ($word > '') {
	echo 'Metaphone='.metaphone($word)."<p>\n";
}



?>	
Enter a word to see its metaphone;
<form>
	<input type=text name=word size=20 value=<?=$word?>>
</form>