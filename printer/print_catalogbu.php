<?php
   // including main script file:
   include_once ("rtf_class.php");
   require_once'../../adodb_lite/adodb.inc.php';
   include_once('../lib/db.inc');
   
  function dumper($a) {
  	$h= '<pre>';
  	$h .= print_r($a,true);
  	$h.='</pre>';
  	return $h;
  }
   
   
 	// get group options
	$sql = "select * from type,type_options where type.typeid = type_options.typeid order by type_options.opt_code asc"; // select every type available in this category
	$r = do_query($sql);
	//echo dumper($r);
	foreach ($r as $option) {
		$options[$option['typeid']][] = $option;		
	}
	//echo dumper($options);
	//exit;
	
	// get options for each type
	$sql = "select DISTINCT products.*,type.* from products,type,type_category where type.typeid = products.typeid and products.status!='inactive' ";	
	if ($req['catid'] > 0 ) { // add category filter
		 $sql .= " and type.typeid=type_category.typeid and type_category.catid=".$req['catid']." "; 
	}
	//$sql .= " order by LEFT(type.name,8) asc, products.product_code asc";
	$sql .= " order by products.product_code asc";
	
	
	
	//echo $sql."<br>\n";
	$g = do_query($sql);
	if (is_array($g)) {
		foreach ($g as $gg) {
			$group_products[$gg['typeid']][] = $gg;
			// capture actual  products in database - this may be less that all possible products X their options!
			$stocked_products[$gg['product_code']] = $gg;//['product_code'];	
		}
	}
		
		//echo dumper ($stocked_products);
		
		//include ("list_products.inc");
		
		
	
	
   
   // this will be the name of our RTF file:
   $file_rtf = "report.rtf";
   // HTTP headers saying that it is a file stream:
  Header("Content-type: application/octet-stream");
   // passing the name of the streaming file:
   Header("Content-Disposition: attachment; filename=$file_rtf");

   // creating class object and passing to it the path to configuration file: 
   $rtf = new RTF("rtf_config.inc"); // passing the text to the object:
   
   // $markup = make_report(); //

    


   $rtf->parce_HTML($markup);
   // getting RTF code:
   $fin = $rtf->get_rtf();
   // streaming the file to the user:
   echo $fin;
?>