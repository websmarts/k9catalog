<?php
  
?>
<h2>Please Enter your End KM for Today</h2>
<form method="post"  action="" id="geoform" >
<input type="hidden" name="e" value="updateendmileage" />
<input type="hidden" name="longitude" />
<input type="hidden" name="latitude" />

<?php //echo dumper($lasttravelrecord);?>


<p>End KM for Today: <br /><input type="number" name="endkm" /></p>
<input type="submit" name="b" value="save" />
</form>