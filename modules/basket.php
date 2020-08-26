<?php



// basket.php

// get basket data



// DEBUGGING echo dumper ($req);











if( isSet($req['a']) && strtolower($req['a']) == "update" ) {

	//echo dumper($req);

	// merge session basket with req basket

	if (strtolower($req['b']) == "clear" ) {

		unset($S->basket);

        unset($S->order_contact);

		

		

	} elseif (strtolower($req['b']) == "saveorder" ){

		

		// Update basket before saving - it might have been changed on the basket form

		update_basket($req);

		

		// Now - Save the basket to an order and clear the basket

		save_basket("saved",$S->client_id);

				

		// all saved so clear the basket now

		unset($S->basket);

		unset($S->basket_instructions);

        unset($S->order_contact);

		

		// force logout

		unset($S->client_id);

		

	} else {

		

		update_basket($req);

	}



} 



// sort basket into Groups and Items

$basket = $S->basket; // shortcut





if (is_array($basket) ) {

	foreach ($basket as $item =>$qty) {

		$r = do_query("select * from products,type,type_category where products.typeid=type.typeid and products.typeid=type_category.typeid and  products.product_code='".$item."'");

		$products[$item] = $r[0];

		$products[$item]['qty'] = $qty;

	}		

}

 

	//	echo dumper($_SESSION);



// get any special prices

$special_prices = get_client_price_specials($S->getClientId());









?>