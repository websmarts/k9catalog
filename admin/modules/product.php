<?php

function edit_product($req) {

	echo dumper($req);

}

function update_product($req) {
	global $db;
	//echo dumper($req);

	//Deal with pesky empty int values that screw up sql statement
	$price = isset($req['price']) && !empty($req['price']) ? (int) $req['price'] : 0;
	$qtyBreak = isset($req['qty_break']) && !empty($req['qty_break']) ? (int) $req['qty_break'] : 0;
	$qtyDiscount = isset($req['qty_discount']) && !empty($req['qty_discount']) ? (int) $req['qty_discount'] : 0;
	$qtyInstock = isset($req['qty_instock']) && !empty($req['qty_instock']) ? (int) $req['qty_instock'] : 0;
	$lowStockLevel = isset($req['low_stock_level']) && !empty($req['low_stock_level']) ? (int) $req['low_stock_level'] : 0;
	$cost = isset($req['cost']) && !empty($req['cost']) ? (int) $req['cost'] : 0;
	$displayOrder = isset($req['display_order']) && !empty($req['display_order']) ? (int) $req['display_order'] : 0;
	$shippingWeight = isset($req['shipping_weight']) && !empty($req['shipping_weight']) ? (float) $req['shipping_weight'] : 0;
	$shippingVolume = isset($req['shipping_volume']) && !empty($req['shipping_volume']) ? (float) $req['shipping_volume'] : 0;
	$width = isset($req['width']) && !empty($req['width']) ? (float) $req['width'] : 0;
	$height = isset($req['height']) && !empty($req['height']) ? (float) $req['height'] : 0;
	$length = isset($req['length']) && !empty($req['length']) ? (float) $req['length'] : 0;
	$rrp = isset($req['rrp']) && !empty($req['rrp']) ? (float) $req['rrp'] : 0;

	$sql = "UPDATE products set " .
	"description='" . $req['description'] . "'," .
	"size=" . quote_str($req['size']) . "," .
	"price=" . $price . "," .
	"rrp=" . $rrp . "," .
	"typeid=" . $req['typeid'] . "," .
	"qty_break=" . $qtyBreak . "," .
	"qty_discount=" . $qtyDiscount . "," .
	"qty_instock=" . $qtyInstock . "," .
	"low_stock_level=" . $lowStockLevel . "," .
	"special=" . $req['special'] . "," .
	"clearance=" . $req['clearance'] . "," .
	"new_product=" . $req['new_product'] . "," .
	"core_product=" . $req['core_product'] . "," .
	"can_backorder=" . quote_str($req['can_backorder']) . "," .
	"status=" . quote_str($req['status']) . "," .
	"cost=" . $cost . ", " .
	"last_costed_date='" . $req['last_costed_date'] . "'," .
	"supplier=" . quote_str($req['supplier']) . "," .
	"display_order=" . $displayOrder . "," .
	"color_name=" . quote_str($req['color_name']) . "," .
	"color_background_color=" . quote_str($req['color_background_color']) . "," .
	"color_background_image=" . quote_str($req['color_background_image']) . "," .
	"width=" . $width . "," .
	"height=" . $length . "," .
	"length=" . $height . "," .
	"shipping_weight=" . $shippingWeight . "," .
	"shipping_volume=" . $shippingVolume . "," .
	"shipping_container=" . quote_str($req['shipping_container']) . "," .
	"source=" . quote_str($req['source']) . "," .
	"notify_when_instock=" . quote_str($req['notify_when_instock'])
	;

	// ignore barcode update if empty or if it exists
	if (!empty($req['barcode'])) {
		$res = do_query('select barcode,product_code from products where barcode=' . quote_str($req['barcode']));
		if (!$res) {
			$sql .= ", barcode=" . quote_str($req['barcode']) . " ";
		} else {
			if ($res[0]['product_code'] != $req['product_code']) {
				echo 'BARCODE EXISTS :: Product code ' . $res[0]['product_code'] . ' is using barcode=' . $req['barcode'];
			}
		}

	}

	$sql .= " WHERE id=" . $req['id'];

	//$db->debug= true;
	$res = $db->Execute($sql);
	//$db->debug= false;
}

function get_max_sequence($typeid) {

	$max = 0; // init value
	// find the current number nthe sequence
	$files = directory("../source/", $typeid . "_"); // (DIRECTORY, FILTER)
	//echo dumper($files);
	if (is_array($files) && count($files) > 0) {
		foreach ($files as $file) {

			if (preg_match("/_([^_]*)\.jpg$/i", $file, $m)) {
				//echo dumper($m);
				$max = ($max < $m[1]) ? $m[1] : $max; // get the max
			}

		}
	}

	return $max;
}
function get_next_sequence($typeid) {

	$next = 0; // init value

	$opts = array(0 => '', 2 => '_2', 3 => '_3', 4 => '_4', 5 => '_5', 6 => '_6', 7 => '_7', 8 => '_8', 9 => '_9'); // up to 9 images supported
	// find the current number nthe sequence
	$path = "../source/" . $typeid; // path to img series
	foreach ($opts as $k => $n) {
		// echo $path . $n .".jpg <br>";
		if (!file_exists($path . $n . '.jpg')) {
			$next = $n;
			break;
		}
	}

	return $next;
}

function delete_image($req) {

	//echo dumper ($_FILES);
	//echo dumper ($req);
	$image = $req['typeid'];
	if (isSet($req['img_number'])) {
		$img_number = $req['img_number'];
	}

	$max = get_max_sequence($image);

	//echo "MAX = $max <p>";
	//exit;

	if ($max == $img_number) {
		//echo "Just Deleteing the main image as that is all there is!<br>";
		// just delete the image files
		if (!isSet($img_number) || $img_number == 0) {
			// were deleteing the main image so dont use underscore or sequence number
			$ext = "";
		} else {
			$ext = "_" . $img_number; // were deleteing a seq image so use seq
		}

		if (file_exists('../source/' . $image . $ext . '.jpg')) {
			unlink('../source/' . $image . $ext . '.jpg');
		}
		if (file_exists('../source/tn_' . $image . $ext . '.jpg')) {
			unlink('../source/tn_' . $image . $ext . '.jpg');
		}

	} elseif ($img_number < $max) {
		// then move all images down one and delete the highest
		for ($n = $img_number; $n < $max; $n++) {
			//echo "N=$n  - MAX=$max <br>";
			if ($n == 1) {
				// main image
				$from = $image . "_2";
				$to = $image;

			} else {
				// seconadry image
				$x = $n + 1;
				$from = $image . "_" . $x;
				$to = $image . "_" . $n;

			}

			// move the main image
			//echo 'From:'.$from .' to: '.$to.'<br>';

			if (file_exists('../source/' . $from . '.jpg') && file_exists('../source/' . $to . '.jpg')) {
				copy('../source/' . $from . '.jpg', '../source/' . $to . '.jpg');
			}

			// move the thumbnail
			if (file_exists('../source/tn_' . $from . '.jpg') && file_exists('../source/tn_' . $to . '.jpg')) {
				copy('../source/tn_' . $from . '.jpg', '../source/tn_' . $to . '.jpg');
			}
		}

		// delete any trailing MAX image left after copy shuffle down
		if (file_exists('../source/' . $image . '_' . $max . '.jpg')) {
			unlink('../source/' . $image . '_' . $max . '.jpg');
		}
		if (file_exists('../source/tn_' . $image . '_' . $max . '.jpg')) {
			unlink('../source/tn_' . $image . '_' . $max . '.jpg');
		}

	}
}

function upload_image($typeid) {
	global $req;

	//echo dumper ($_FILES);
	//echo dumper ($req);

	// which file 1-6?
	if (!isSet($req['img_number'])) {
		$img_ext = ""; // do the main image
	} elseif ($req['img_number'] == 0) {
		// Create a new image at end of sequence

		//$max = get_max_sequence($req['typeid']);
		$max = get_next_sequence($req['typeid']); // update march 2010 to fill in any holes before max

		//echo "MAX= $max <br>";

		if ($max === 0) {
			// then we didnt find a sequence so use main image
			if (file_exists('../source/' . $req['typeid'] . '.jpg')) {

				$img_ext = "_2";
			} else {
				$img_ext = "";
			}
		} else {
			// $max = $max + 1; // make next seq number

			$img_ext = $max;
		}
	} else {
		if ($req['img_number'] == 1) {
			// first image has no seq

			$img_ext = '';
		} else {
			$img_number = $req['img_number']; //
			$img_ext = '_' . $img_number;
		}
	}

	$uploadfile = "../source/" . $req['typeid'] . $img_ext . '.jpg';
	$thumbfile = "../source/tn_" . $req['typeid'] . $img_ext . '.jpg';

	//echo "UPLOADING FILE: $uploadfile <br>";
	//echo "THUMB FILE: $thumbfile <br>";

	if ($_FILES['myfile']['error'] == 0) {
		if (move_uploaded_file($_FILES['myfile']['tmp_name'], $uploadfile)) {
			if (file_exists($thumbfile)) {
				unlink($thumbfile);
			}
			$new_w = 150;
			$new_h = 150;
			//echo "creating thumbfile for:uploaded file=$uploadfile, thumbfile=$thumbfile <p>";
			createthumb($uploadfile, $thumbfile, $new_w, $new_h);
			//print "File is valid, and was successfully uploaded. ";
			//print "Here's some more debugging info:\n";
			//print_r($_FILES);
		} else {
			print "Error!  Here's some debugging info:\n";
			print_r($_FILES);
		}

	}

}

/*
 * MAIN
 *
 *
 */

ini_set("memory_limit", "200M");

switch ($a) {

case "edit":
	$product = get_product_details($req['product_code']);
	$bom = getProductBOM($req['product_code']);
	//echo dumper($bom);
	break;
case "update":
	update_product($req);
	$product = get_product_details($req['product_code']);

	break;
case "uploadimage":
	upload_image($req['typeid']);
	$product = get_product_details($req['product_code']);
	break;
case "deleteimage":
	delete_image($req);
	$product = get_product_details($req['product_code']);
	break;

case "specialprices":
	switch ($req['sa']) {
// subaction

	case 'updateprice':
		// post update to update client price for product code
		$pcode = $req['product_code'];
		$price = (int) $req['special_price'];
		$clients = $req['clients'];

		//echo 'UPDATING PRICE FOR '.$pcode .' TO '.$price. ' FOR CLIENTs '.print_r($clients,1); exit;
		//
		if ((is_array($clients)) && !empty($pcode) && strtolower($req['b']) == 'save') {
			//$product_res = do_query('select product_code from products where id='.$req['pid']);

			foreach ($clients as $client_id) {
				// delete any current entry for this combo
				$query = 'delete from client_prices where client_id=' . $client_id . ' and product_code="' . $pcode . '"';
				do_query($query);
				if ($price > 0) {
					// now insert
					$query = 'insert into client_prices set client_id=' . $client_id . ',product_code="' . $pcode . '",client_price=' . $price;
					// echo $query ."<br />";
					do_query($query);

				}
			}

		} elseif (!empty($pcode) && strtolower($req['b']) == 'list product special prices') {

			$sql = 'select cp.*,c.name,p.price as std_price
                                from client_prices as cp
                                join clients as c on c.client_id= cp.client_id
                                join products as p on p.product_code=cp.product_code
                                where cp.product_code="' . $pcode . '" order by cp.product_code asc';
			$product_specialprices[$pcode] = do_query($sql);
		} elseif ((is_array($clients)) && strtolower($req['b']) == 'list client special prices') {
			$client_specialprices = array();
			foreach ($clients as $client) {
				$sql = 'select cp.*,c.name,p.price as std_price from client_prices as cp
                                    join clients as c on c.client_id= cp.client_id
                                    join products as p on p.product_code=cp.product_code
                                    where cp.client_id=' . $client;
				//echo $sql;

				$rows = do_query($sql);
				foreach ($rows as $row) {
					$client_specialprices[$row['name']][] = $row;
				}
			}

		}
		echo 'no action taken';

		break;

	default:
		if (!empty($req['pid']) && !empty($req['cid'])) {
//XHR product_id && client_id
			// std price
			$query = 'select price from products where id=' . $req['pid'];
			$std_price_res = do_query($query);

			// client price
			$query = 'select client_price from client_prices as cp
                                 join products as p on p.product_code = cp.product_code
                                 where p.id=' . $req['pid'] . ' and cp.client_id=' . $req['cid'];
			$client_price_res = do_query($query);

			echo $std_price_res[0]['price'] . '|' . $client_price_res[0]['client_price'];
			exit;
		}

	}
	break;
case 'stockadjust':

	// update actions
	$button = strtolower($req['b']);
	switch ($button) {
	case 'take qty out of stock':
		$sql = 'update products set `qty_instock`=(`qty_instock` - ' . $req['entered_qty'] . ') where product_code ="' . $req['selected_product_code'] . '"';
		do_query($sql);
		$_SESSION['history'] = '<br />' . $req['selected_product_code'] . ' take ' . $req['entered_qty'] . ' out of stock ' . $_SESSION['history'];
		break;
	case 'add qty to current stock':
		$sql = 'update products set `qty_instock`=(`qty_instock` + ' . $req['entered_qty'] . ') where product_code ="' . $req['selected_product_code'] . '"';
		do_query($sql);
		$_SESSION['history'] = '<br />' . $req['selected_product_code'] . ' add ' . $req['entered_qty'] . ' to current stock ' . $_SESSION['history'];
		break;
	case 'set current stock to qty':
		$sql = 'update products set `qty_instock`=' . $req['entered_qty'] . ' where product_code ="' . $req['selected_product_code'] . '"';
		do_query($sql);
		$_SESSION['history'] = '<br />' . $req['selected_product_code'] . ' set stock qty to ' . $req['entered_qty'] . '' . $_SESSION['history'];
		break;
	}

	// select product action
	if (strtolower($req['b']) == 'get product data' && (!empty($req['product_code']) || !empty($req['barcode']))) {
		if (!empty($req['product_code'])) {
			$sql = 'select * from products where product_code = "' . $req['product_code'] . '"';
		} elseif (!empty($req['barcode'])) {
			$sql = 'select * from products where barcode = "' . $req['barcode'] . '"';
		}

	} elseif (!empty($req['selected_product_code'])) {
		$sql = 'select * from products where product_code = "' . $req['selected_product_code'] . '"';
	}
	$selected_product = do_query($sql);

	break;
default:
}
$products = do_query('select id,product_code,price,cost from products where status != "inactive" order by products.product_code asc');
$clients = do_query('select client_id,name from clients where status="active" order by clients.name asc');
// Get the display data for view
$_v['cats'] = get_list_hierarchy("category", "order by name asc");

$_v['type'] = get_types();

//echo dumper($_v['cats']);

//echo dumper($_v['type']);

?>
