<?php
function create_type() {
	global $req,$_v;
	
	//echo dumper ($req);
	
	// check we have everything we need
	
	$cats = $req['catid'];
	if (count($cats) < 1 ) {
		$msg .= "no actegory selected<br>";
		$_v['form_errors']['catid'] = 1;
	}
	if (trim($req['typename']) < " "){
		$msg .= "No type name supplied <br>";
		$_v['form_errors']['typename'] = 1;
	}
	
	
	$aus_made = isset($req['aus_made'])&& !empty($req['aus_made'] ) ? (int) $req['aus_made'] : 0;
	
	$sql = "INSERT INTO type ".
				 "SET name='".$req['typename']."'".
				 ",image='".$req['typeimagename']."'".
				 ",aus_made=".$aus_made. 
				 ",display_format='".$req['displayformat']."'";
				 
	//echo $sql ; 
				 
	$typeid = do_query($sql);
	
	// now get typeid and insert entries into type category
	foreach ($cats as $catid) {
		$sql ="INSERT into type_category (typeid,catid) values ($typeid,$catid) ";
		
		//echo $sql ."<br>\n";
		$r = do_query($sql);
	}	
}

function add_options () {
	global $req;
	
	$options = $req['opt'];
	if (is_array($options) ) {
		foreach ($options as $option) {
			foreach ($option as $typeid =>$opts) {
				
				$sql = "insert into type_options set typeid=$typeid,";
				foreach ($opts as $k => $v) {
					//echo "k=$k and v=$v <br>\n";
					if ($k == "opt_code") {
						 if( $v >""){
							$sql .= " $k='$v',";
						} else {
							$skipflag = 1; // set so we ignore blank data and dont insert into db
						}
					} else {
						$sql .= " $k='$v',";
					}						
				}
			}
			
			// remove trailing comma
			$pattern = "/,$/";
			$string = $sql;
			$replacement="";
			$sql = preg_replace($pattern, $replacement, $string);
			
			//echo $sql."<br>skipflag=$skipflag <br>\n";
			if ($skipflag == 0){ // ignore any blank data submitted - 
				$r = do_query($sql);
			} else {
				$skipflag = 0;
			}
		}		
	}
	
	
}
function update_type($req) {
 
	if ($req['typeid'] && $req['typename'] > "") {
        // pesky int values
        $displayOrder = isset($req['display_order'])&& !empty($req['display_order'] ) ? (int) $req['display_order'] : 0;
        $aus_made = isset($req['aus_made'])&& !empty($req['aus_made'] ) ? (int) $req['aus_made'] : 0;
        
		$sql =  "update type set name='".addslashes($req['typename']).
                "',type_description='".addslashes($req['type_description']).
                "',type_product_notes='".addslashes($req['type_product_notes']).
                "',display_order=".$displayOrder.
                ",aus_made=".$aus_made . 
                " WHERE typeid=".$req['typeid'];
		//echo dumper($sql);	
        //exit;
        //$db->debug = true;
		do_query($sql);
        //$db->debug = false;
	}	
	
	// now update the type category
	$sql = "delete from type_category where typeid=".$req['typeid']; // get rid of current entries
	//echo dumper($sql);
	$res = do_query($sql);
	
	// now add in the updated type_category entries
	if(is_array($req['catid']) and count($req['catid']) > 0) {
		foreach($req['catid'] as $catid) {
			$sql = "insert into type_category (catid,typeid) VALUES ($catid,".$req['typeid'].")";
			//echo dumper($sql);
			do_query($sql);
		}		
	}
}

function delete_type ($typeid) {
	if($typeid > 0){
        do_query("delete from type where typeid=$typeid");
        do_query("delete from type_options where typeid=$typeid");
        do_query("delete from type_category where typeid=$typeid");
        do_query("delete from products where typeid=$typeid"); 
    }
}

	
/*
 * MAIN 
 *
 *
 */
 
//echo "included type.php<br>\n";



switch ($a) {
	case "create_type":
		create_type();
				break;
	case "delete_type":
    
    echo "DELETING TYPE";
		delete_type($req['typeid']);
				break;
	case "add_options":
		add_options();
		break;
	case "update_type":
		update_type($req);
		break;
	case "delete_opt":
		//echo dumper($req);	
        
        if (!empty($req['opt_code'])){
            $sql1 = "delete from type_options where typeid=".$req['typeid']."  and opt_code=".quote_str($req['opt_code'])." LIMIT 1";
            $sql2 = "delete from products where typeid=".$req['typeid']."  and product_code like '%-".$req['opt_code']."%' " ;    
           
           
            echo $sql1 .$sql2;
                
            $db->Execute($sql1);
            $db->Execute($sql2);
        }
        	
		break;
		
		
	default:
}

// Get the display data for view
$_v['cats']= get_list_hierarchy("category","order by name asc");

// get product types for 
$_v['ptypes'] = get_product_types();

$_v['type_options'] = get_type_options();

//echo dumper ($_v['ptypes']);

//echo dumper($_v['cats']);
//echo dumper($_v['type_options']);


?>