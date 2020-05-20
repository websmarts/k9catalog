<?php
include ("class.stemmer.inc");
$str = trim($_REQUEST['f_txt']);
if($str=="")
	$str = "connections";
	
$objStem = new Stemmer();
$word = $objStem->stem($str);
print "STEMMED WORD FOR \"" . $str . "\" IS \"" . $word ."\"";
?>
<form name="frm_test" method="post">
<input type="text" name="f_txt" value="<?= $str ?>">
<input type="submit" value="Stem it...">
</form>