<?php
$postcode = (int)$_REQUEST['f_pc'];
$distance = (float)$_REQUEST['f_dist'];
if($postcode==0)
	$postcode='2006';

if($distance==0)
	$distance='25';

?>

<form name='frm_test' method='post'>
Find all Post Codes which are 
<input type='text' name='f_dist' size='5' value='<?= $distance ?>'> 
KMs far away from the Postcode 
<input type='text' name='f_pc' size='5' value='<?= $postcode ?>'>
<input type='submit' name='f_submit' value='Get Post Codes'>
</form>

<?php
require_once('../inc/configure.php');
	
# local db connection
#mysql_connect("linuxpc","root","") or die("Couldn't connect");
#mysql_select_db("olr") or die("Couldn't select db");

# live db connection
mysql_connect("localhost","ourloca_olruser","olrpass") or die("Couldn't connect");
mysql_select_db("ourloca_olrdb") or die("Couldn't select db");

$sql = "SELECT * FROM postcodeProximity WHERE postcode=$postcode AND proximity<=$distance";
$res = mysql_query($sql) or die($sql.mysql_error());

print "Query: ".$sql;
print "<br><br>Postcodes which are $distance kms far away from $postcode<br>";
while ($row = mysql_fetch_array($res))
{
	print $row['postcode2'] . ", ";
}

$locationName = $postcode.":".$distance;
$locationType = "radius";

print "<br><br>Location Type: $locationType";
print "<br>Location Name: $locationName";

?>
