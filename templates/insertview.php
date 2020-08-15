<?php
if (file_exists("templates/views/" . $S->nextview . ".inc")) {

    include("templates/views/" . $S->nextview . ".inc");
} else {

    abort("Template select error:" . "templates/views/" . $S->nextview . ".inc does not exist<br>\n");
}
