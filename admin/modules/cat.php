<?php


function insert_cat($req){
//	echo dumper($req);
	return do_query("insert into category (name,parent_id) values('".addslashes($req['catname'])."',".$req['parent_id'].")" );	
}

function delete_cat($req) {
	global $error_msg;
	
	// ONLY delete if it has no children
	$res = do_query("select count(*) AS children from category where parent_id=".$req['catid']);
	$children = $res[0]['children'];
	//echo dumper($children);
	
	// check if category is used and dont delete if it is
	$res = do_query("select count(*) AS used from type_category where catid=".$req['catid']);
	$used = $res[0]['used'];
	
	//echo dumper($used); exit;
	
	
	if (($children + $used) < 1 ) { //its okay to delete
		$res = do_query("delete from category where id=".$req['catid']);
	} else {
		$error_msg .= "<P>Cannot delete category because it either has children categories under it or it us used by type_category table.<br>".
										"Number of Children=$children<br>Number of uses in type_category=$used</p>";
	}
	
}


function update_catname($req) {
	
	//echo dumper($req); exit;
	
	return do_query("update category set name='".html_entity_decode(addslashes($req['catname']),ENT_QUOTES)."' where id=".$req['catid']);
	
}
	
/*
 * MAIN 
 *
 *
 */
 
// echo "included cat.php<br>\n";



switch ($a) {
	
	case "insert":
		insert_cat($req);
		break;
	case "delete":
		delete_cat($req);
		break;
	case "update_catname":
		update_catname($req);
		break;	
		
	default:
}

// Get the display data for view
$_v['cats']= get_list_hierarchy("category","order by name asc");

// get product types for 
$_v['ptypes'] = get_product_types();

$_v['type_options'] = get_type_options();



//echo dumper($_v['cats']);

//echo dumper($_v['type_options']);


?>