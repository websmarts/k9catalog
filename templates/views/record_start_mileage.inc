<?php
  
?>
<h2>Please Enter your Start KM for Today</h2>
<form method="post"  action="" id="geoform">
<input type="hidden" name="e" value="updatestartmileage" />
<input type="hidden" name="longitude" />
<input type="hidden" name="latitude" />

<?php //echo dumper($lasttravelrecord);?>


<p>Start KM for Today: <br /><input type="number" name="startkm" /></p>
<input type="submit" name="b" value="save" />
</form>