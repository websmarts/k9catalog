<?php


$_v['cats']= get_list_hierarchy("category","order by name asc");

// get product types for 
$_v['ptypes'] = get_product_types();

//echo dumper ($_v['ptypes']);

//echo dumper($_v['cats']);



?>