<?php 

include("templates/admin_header.inc");	

if ( file_exists("templates/adminviews/".$S->nextview.".inc") ) {
	include("templates/adminviews/".$S->nextview.".inc");
} else {
	abort("Template select error:"."templates/adminviews/".$S->nextview.".inc does not exist<br>\n") ;
}
include("templates/footer.inc");
						

?>