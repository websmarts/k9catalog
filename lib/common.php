<?php



/*

*

*  COMMONLY USED FUNCTIONS

*

*/

/*

    Function createthumb($name,$filename,$new_w,$new_h)

    creates a resized image

    variables:

    $name        Original filename

    $filename    Filename of the resized image

    $new_w        width of resized image

    $new_h        height of resized image

*/

function createthumb($name, $filename, $new_w, $new_h)

{



    //echo "Name: ".strtolower($name)."<br>\n";

    if (file_exists($name)) {

        if (preg_match("/jpg|jpeg/i", $name)) {

            $src_img = imagecreatefromjpeg($name);

        }



        if (preg_match("/png/i", $name)) {

            $src_img = imagecreatefrompng($name);

        }

        $old_x = imageSX($src_img);

        $old_y = imageSY($src_img);



        if ($old_x > $old_y) {

            $thumb_w = $new_w;

            $thumb_h = $old_y * ($new_h / $old_x);

        }



        if ($old_x < $old_y) {

            $thumb_w = $old_x * ($new_w / $old_y);

            $thumb_h = $new_h;

        }



        if ($old_x == $old_y) {

            $thumb_w = $new_w;

            $thumb_h = $new_h;

        }

        //      $destimg=ImageCreateTrueColor($new_width,$new_height) or die("Problem In Creating image");

        //    $srcimg=ImageCreateFromJPEG($image_name) or die("Problem In opening Source Image");

        //    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing");

        //    ImageJPEG($destimg,$dir."/".$thumb_dir."/".$file) or die("Problem In saving");



        $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);

        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);



        //    ImageCopyResized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

        if (preg_match("/png/", $system[1])) {

            imagepng($dst_img, strtolower($filename));

        }

        else

        {

            imagejpeg($dst_img, strtolower($filename));

        }

        imagedestroy($dst_img);

        imagedestroy($src_img);

    }

}



function directory($dir, $filter)

{

    $handle = opendir($dir);

    $files = array();



    if ($filter == "all") {

        while (($file = readdir($handle)) !== false)

        {

            $files[] = $file;

        }

    }



    if ($filter != "all") {

        while (($file = readdir($handle)) !== false)

        {

            //echo $file."<br>";

            if (preg_match("/$filter/i", $file)) {



                $files[] = $file;

            }

        }

    }

    closedir($handle);

    return $files;

}



function get_clients($repid)

{



    // retuns a list of clients for a particular rep - for now returns all clients

    $sql = "SELECT * from clients order by name asc";

    $r = do_query($sql);

    return $r;

}



function get_k9Users(){

   $sql = "SELECT id,name from users";

   $r = do_query($sql);

   $uaers = array();

   if($r){

       foreach($r as $u){

           $users[$u['id']]=$u['name'];

       }

   } 

   return $users; 

}



/* Serious error handler

*/

function abort($msg)

{

    echo "</option></select><pre>$msg</pre>\n";

    exit;

}





/* Process form string data for inserting into database

*/

function quote_str($str)

{



    return "'" . html_entity_decode(addslashes($str)) . "'";

}



/*

* function for making remote procedure calls

*/



function rpc($procedure, $params)

{

    return XMLRPC_request(RPCHOST, RPCSERVER, $procedure, array(XMLRPC_prepare($params)));

}



/* Returns the client details

*/

function get_client_details($client_id)

{

    return do_query("select * from clients where client_id=$client_id");

}



function get_types()

{

    $res = do_query("select typeid,name from type order by name asc");



    if (is_array($res) && count($res) > 0) {

        foreach ($res as $v)

        {

            $r[$v['typeid']] = $v['name'];

        }

    }



    return $r;

}



/* Retreive the http request vars and put into $req

*/

function http_request()

{

    $request = array();

    if ($_POST) {

        foreach ($_POST as $key => $value)

        {

            $request[$key] = $value;

        }

    }

    else

    {

        foreach ($_GET as $key => $value)

        {

            $request[$key] = $value;

        }

    }



    return $request;

}



function pr($a, $msg = "")

{

    return dumper($a, $msg);

}



function dumper($a, $msg = "")

{

    $html = "<pre>$msg\n";

    $html .= print_r($a, true); // set 'true' to return string rather than print it

    $html .= "</pre>\n";

    return $html;

}

function get_type_info_page_index()

{

    global $db_debug;

    // looks in modx content table for pages with typeid tv set

    //$db_debug= true;

    $query = '  SELECT cv.* FROM modx_site_tmplvars AS tv

                JOIN modx_site_tmplvar_contentvalues AS cv ON tv.id=cv.tmplvarid

                WHERE tv.name="k9_typeid"';

    $res =  do_query($query);

    $index=array();

    if(is_array($res) && count($res)){

        foreach($res as $r){

            $index[$r['value']]=$r['contentid'];

        }

    }

    //$db_debug = false;

    return $index;

     

}

function get_list_hierarchy($table, $order = "")

{

    $query = "SELECT * FROM $table WHERE parent_id =0 and display_order >=0 order by display_order desc, name asc";



    $depth = 0;

    $list = do_query($query);



    if (is_array($list) && count($list > 0)) {

        foreach ($list as $key => $rec)

        {

            $nlist[$rec['id']] = array

            (

                $rec,

                get_hierarchy_children($rec['id'], $depth, $table, $order)

            );

        }

    }



    return $nlist;

}



function get_hierarchy_children($id, $depth, $table, $order = "")

{

    $query = "SELECT * FROM $table WHERE parent_id =" . $id . " " . $order;

    $nlist = array();

    $list = do_query($query);



    if (is_array($list) && count($list > 0)) {

        foreach ($list as $key => $rec)

        {

            $nlist[$rec['id']] = array

            (

                $rec,

                get_hierarchy_children($rec['id'], $depth, $table)

            );

        }

    }



    return $nlist;

}



function get_product_types()

{

    $sql = "select * from type order by name asc";

    $result = do_query($sql);



    if (is_array($result)) {

        foreach ($result as $v)

        {

            $list[$v['typeid']] = $v;

        }

    }

    return $list;

}



function get_type_options()

{

    $sql = "select distinct  * from type,type_options where type.typeid = type_options.typeid";

    $r = do_query($sql);



    //echo dumper($r);

    if (is_array($r)) {

        foreach ($r as $v)

        {

            $t[$v['typeid']][] = $v;

        }

    }

    //echo dumper($t);

    //exit;

    return ($t);

}



function get_category_name($id)

{

    $r = do_query("select name from category where id=$id");

    return $r[0]['name'];

}



function get_category_list()

{

    $r = do_query('select * from `category`');



    if (is_array($r)) {

        foreach ($r as $v)

        {

            $t[$v['id']] = $v;

        }

    }

    //echo dumper($t);

    //exit;

    return ($t);

}



function delete_basket($client_id)

{

    global $S;

    do_query("delete from system_orders where client_id=$client_id and status='basket'");

    //echo "doing delete basket ????<p>";

    $S->clearBasket();

}



function save_basket()

{

    global $S, $db;



    // get open order ids - should only be one!



    if (!$S->getClientId()) {

        return;

    }

    // Get list of 'baskets' for the current logged in user

    // if user is a client

    if ($S->is_valid_client()) {

        $sql = "select order_id from system_orders  where ordered_by=" . $S->getClientId() . " and status='basket'";

    } elseif ($S->isInternalUser()) {

        $sql = "select order_id from system_orders  where reference_id=" . $S->id . " and client_id=" . $S->getClientId() . " and status='basket'";

    }

    $basket_order_ids =

        $db->GetArray($sql);



    // Delete current order details before replacing with items from session basket

    if (is_array($basket_order_ids[0])) {

        foreach ($basket_order_ids as $k => $order)

        {

            $db->Execute("delete from system_order_items where order_id = '" . $order['order_id'] . "'");

        }



        // we already have an order so

        // now add in the session basket items to the order items table

        if (isSet($S->basket_instructions) || count($S->basket) > 0) {

            // okay we already have an order number so now save session basket to db

            if (is_array($S->basket) && count($S->basket) > 0) {

                // get the prices to save with the order

                // There may be special prices that override standard prices

                $special_prices = get_client_price_specials($S->getClientId());



                foreach ($S->basket as $product_code => $qty)

                {

                    if (isSet($special_prices[$product_code])) {

                        $price = $special_prices[$product_code];

                    }

                    else

                    { // use standard price - may be discounted if qty > qty_break

                        $price = get_standard_product_price($product_code,

                            $qty); // returns normal or qty_disc price if approp

                    }



                    // Now get the price that was on the order form if it has been updated

                    if (isSet($S->basket_prices[$product_code]))

                        $price = $S->basket_prices[$product_code]; // price hack



                    // paranoid price must be zero if empty

                    if (empty($price))

                        $price = 0;



                    // save items to order_items table

                    $db->Execute("INSERT INTO system_order_items (order_id,product_code,qty,price) values('"

                        . $order['order_id'] . "','" . $product_code . "'," . $qty . ",$price )");

                }

            }



            if (isSet($S->basket_instructions) && !empty($S->basket_instructions)) {

                $sql = "Update system_orders set instructions=" . quote_str($S->basket_instructions)

                    . " where order_id=" . quote_str($order['order_id']);

                

                $db->Execute($sql);

            }

        }

        else

        { // nothing to save - should we delete the ORDER NOW!!! could be dangerous if another user is using it

            // for now we will  delete the empty order

            $db->Execute("delete from system_orders where order_id = '" . $order['order_id'] . "'");

        }

    }

    else

    { // no order exists so we need to start from scratch and create order and save order items



        // only save this order if there are order instructions or order items to be saved

        if (isSet($S->basket_instructions) || count($S->basket) > 0) {

            // create the new open order



$sql = "INSERT INTO system_orders (client_id,status,instructions,reference_id,ordered_by,modified) VALUES ("

                . $S->getClientId() . ",'basket',". quote_str($S->basket_instructions). ",'" . $S->id . "','" . $S->is_valid_client() . "','". date("Y-m-j H:i:s")."')";



                

            $db->Execute($sql);

            $id = $db->Insert_ID();

            // now we need to update the order with its alph order_id

            $order_id = TERMINALID . "_" . $id;

            $db->Execute("Update system_orders set order_id ='" . $order_id . "' where id=$id");

        }





        // Only do the save items if there are some items to save

        if (is_array($S->basket) && count($S->basket)) {

            // get the prices to save with the order

            // There may be special prices that override standard prices

            $special_prices = get_client_price_specials($S->getClientId());



            foreach ($S->basket as $product_code => $qty)

            {

                if (isSet($special_prices[$product_code])) {

                    $price = $special_prices[$product_code];

                }

                else

                { // use standard price - may be discounted if qty > qty_break

                    $price = get_standard_product_price($product_code,

                        $qty); // returns normal or qty_disc price if approp

                }



                // Now get the price that was on the order form if it has been updated

                if (isSet($S->basket_prices[$product_code]))

                    $price = $S->basket_prices[$product_code]; // price hack



                // paranoid price must be zero if empty

                if (empty($price))

                    $price = 0;



                // save items to order_items table

                $db->Execute("INSERT INTO system_order_items (order_id,product_code,qty,price) values('" . $order_id

                    . "','" . $product_code . "'," . $qty . ",$price )");



                // update products.qty_ordered value and stock levels

                $db->Execute("UPDATE products set qty_instock=qty_instock - $qty where product_code='" . $product_code

                    . "'");



                // BOM support added Jan 2009

                // now if any product has a BOM we need to deduct

                // the BOM items.



                $bom_items = $db->getArray("select * from boms where parent_product_code ='" . $product_code . "'");



                if (count($bom_items) > 0) {

                    foreach ($bom_items as $item)

                    {

                        $item_qty = $qty * $item['item_qty']; // bom qty * number of items in bom

                        $db->Execute("UPDATE products set qty_instock=qty_instock - $item_qty where product_code='"

                            . $item['item_product_code'] . "'");

                    }

                }

            }

        }



        //$db->debug = false;



    }



    $db->debug = false; // turn of any db debugging

}



function get_type_catid($typeid)

{



    $sql = "select catid from type_category where typeid=$typeid";

    $res = do_query($sql);



    if (is_array($res) and count($res) > 0) {

        foreach ($res as $c)

        {

            $ids[] = $c['catid'];

        }

    }

    return $ids;

}



function get_product_details($product_code)

{

    $res = do_query("select * from products where product_code='" . $product_code . "'");

    return $res[0];

}



function getProductBOM($product_code)

{

    // gets list of BOM codes

    $res = do_query("select * from boms where parent_product_code='" . $product_code . "'");

    return $res;

}

function isBomAndAvailable($product_code){

    // Check if product is BOM 

    

    $res = getProductBOM($product_code);

    $numboms=array();

    if (!empty($res)){// it is a BOM

      //echo dumper($res); 

      // check if  bom items and qty are instock

      

      foreach($res as $item){

          $res2 = do_query('select qty_instock from products where product_code="'.$item['item_product_code'].'" and `status` != "inactive" ');

         // echo dumper($res2);

          if ($res2  ) {

             

              if($item['item_qty'] > 0){

                

                if($res2[0]['qty_instock'] != 0 ){

                    $numboms[] =  (int) ($res2[0]['qty_instock'] /  $item['item_qty']) ;

                } else {

                    $numboms[] = 0;

                } 

                //echo dumper($numboms);

                

              } 

              

              

          }

      }

      //echo dumper($numboms);

      return array('max_available'=> min($numboms));

    } else {// it is not a bom

      return false;

    }

}



function get_standard_product_price($product_code, $qty)

{



    $res = get_product_details($product_code);



    if ($res['qty_break'] > 0 && $qty >= $res['qty_break']) { // apply qty_discount to price

        $price = $res['price'] * (1 - ($res['qty_discount'] / 100));

    }

    else

    {

        $price = $res['price'];

    }

    return $price;

}



function update_basket($req)

{

    global $S;

    



    if (is_array($req['basket'])) { // transfer req basket to session basket



        //echo dumper($req);

        //echo dumper($S->basket);

        //echo dumper($S->basket_prices);



        foreach ($req['basket'] as $product_code => $item)

        {

            if ($item['qty'] > 0) {

                $S->basket[$product_code] = $item['qty'];





                // Check if update is being done from basket view where

                // prices can be updated

                if ($req['update_source'] == 'basket_view' && $S->isInternalUser()) {

                    if (!empty($item['price'])) {

                        //echo dumper($item);

                        $S->basket_prices[$product_code] = $item['price'] * 100; // price hack

                    }

                    else

                    {

                        unset($S->basket_prices[$product_code]); // reset back to standard pricing

                    }

                }

            }

            else

            {

                // unset the basket key

                unset($S->basket[$product_code]);

                unset($S->basket_prices[$product_code]); // price hack

            }

        }

        // update the basket instructions

        if (isSet($req['basket_instructions'])) {

            $S->basket_instructions = $req['basket_instructions'];

        }



    }

}



function restore_client_basket()

{

    global $S;



    if ($S->getClientId()) {

        $qry = "select order_id,instructions from system_orders where status='basket' and client_id=" . $S->getClientId();



        if ($S->id > 0) { // this is a rep not a client

            // exclue client created baskets = eg where reference_id=0

            $qry .= ' and reference_id=' . $S->id;

        }



        $order_id = do_query($qry);

        //echo "ORDER_IDS =".dumper($order_id);



        if (is_array($order_id)) {

            $basket = do_query("select product_code,qty,price from system_order_items where order_id='"

                . $order_id[0]['order_id'] . "'");



            //echo "RESTORING BASKET with".dumper($basket);

            foreach ($basket as $k => $item)

            {

                $S->basket[$item['product_code']] = $item['qty'];

                $S->basket_prices[$item['product_code']] = $item['price'];

            }

            // restore basket instructions

            $S->basket_instructions = $order_id[0]['instructions'];

        }

    }

}



function get_client_price_specials($client_id)

{

    $client_prices =

        do_query(

            "select products.product_code,client_price from products,client_prices where products.product_code=client_prices.product_code and client_prices.client_id="

                . $client_id);



    if (is_array($client_prices)) {

        foreach ($client_prices as $k => $v)

        {

            $special_prices[$v['product_code']] = $v['client_price'];

        }

    }

    return $special_prices;

}



function get_order_details($order_id)

{



    $res = do_query("select * from orders where order_id=$order_id");

    return $res[0];

}



function get_system_order_details($order_id)

{

    global $db;

    $sql = "select * from system_orders where order_id='$order_id'";



    $res = $db->GetArray($sql);



    return $res;

}



function get_orders($status, $clientID = 0)

{

    global $S;

    $sql =

        " select system_orders.*,clients.name from system_orders,clients ".

        " where system_orders.client_id = clients.client_id ";

        if ($S->id != 6){  // Darren sees all baskets

            $sql .= "system_orders.reference_id=" . $S->id;

        }        

        $sql .= " and system_orders.status='$status'";

    if ($clientID > 0) {

        $sql .= ' and clients.client_id=' . $clientID . ' ';

    }

    $res = do_query($sql);

    return $res;

}



function get_sales_report($clientID = 0)

{



    $currentMonthYear = date('Y-m');

    $sql =

        "select sum(products.cost * system_order_items.qty )   as cost,system_orders.*,clients.name, sum(system_order_items.qty * system_order_items.price) as total ";

    $sql .= "from system_orders ";

    $sql .= "join clients on system_orders.client_id = clients.client_id ";

    $sql .= "join system_order_items on system_orders.order_id = system_order_items.order_id ";

    $sql .= "join products on system_order_items.product_code = products.product_code ";

    $sql .= "where system_orders.modified > '" . $currentMonthYear . "' ";



    if ($clientID > 0) {

        $sql .= ' and clients.client_id=' . $clientID . ' ';

    }

    $sql .= " Group By system_orders.order_id";



    $res = do_query($sql);



    return $res;

}

function return_order_items_to_stock($order_id){

    $items = do_query('select * from system_order_items where order_id="'.$order_id);

    if($items && is_array($items) && count($items)){

        foreach($items as $i) {

            do_query('update products set qty_instock=qty_instock + '.$i['qty'].' where product_code ="'.$i['product_code'].'"');

        }

    }

    // delete order_items

    do_query('delete system_order_items where order_id="'.$order_id.'"');

}



function take_order_items_out_of_stock($ORDER_ID){

    $items = do_query('select * from system_order_items where order_id="'.$order_id);

    if($items && is_array($items) && count($items)){

        foreach($items as $i) {

            do_query('update products set qty_instock=qty_instock - '.$i['qty'].' where product_code ="'.$i['product_code'].'"');

        }

    }

    

}



function get_order_detail($order_id)

{

    $sql =

        "select 

        clients.name,

        clients.client_id,

        system_orders.id,

        system_orders.order_id,

        system_orders.instructions,

        system_orders.reference_id,

        system_order_items.product_code,

        system_order_items.qty,

        system_order_items.price, 

        products.description,

        products.price as standard_price,

        products.qty_break,

        products.qty_discount, 

        products.size, 

        products.color_name 

        from 

        system_orders,

        system_order_items,

        clients,

        products 

        where 

        system_orders.order_id='$order_id' 

        and system_order_items.order_id='$order_id' 

        and system_orders.client_id=clients.client_id 

        and system_order_items.product_code=products.product_code ";

    return do_query($sql);

}



function get_system_orders($status, $orderby = "order by modified desc", $clientID)

{



    $sql =

        "select system_orders.*,clients.name from system_orders,clients where system_orders.client_id = clients.client_id and system_orders.status='$status'  ";

    if ($clientID > 0) {

        $sql .= ' and clients.client_id=' . $clientID . ' ';

    }



    $sql .= $orderby;

    $res = do_query($sql);

    return $res;

}



function get_printed_system_orders($status, $orderby = "order by modified desc", $day = 8, $clientID)

{



    $sql =

        "select system_orders.*,clients.name from system_orders,clients where system_orders.client_id = clients.client_id and system_orders.status='$status' ";

    if ($clientID > 0) {

        $sql .= ' and clients.client_id=' . $clientID . ' ';

    } else {

        $sql .= ' and DATE_SUB(NOW(),INTERVAL ' . $day . ' DAY) < system_orders.modified  ';

    }



    $sql .= $orderby;



    $res = do_query($sql);

    return $res;

}



function get_system_order_total($order_id)

{



    $sql = "select sum(qty*price) as total from system_order_items where order_id='" . $order_id . "'";

    $res = do_query($sql);

    return $res[0]['total'];

}



function get_product_codes_for_typeid($typeid)

{

    return do_query("select * from products where typeid=$typeid");

}



function save_order()

{

    global $db, $S,$req;

    //set the order status from basket to 'saved' and update modified timestamp

    $datetime = date("Y-m-j H:i:s");

    

    $longitude = isSet($req['longitude']) ?  (float)$req['longitude'] : 0;

    $latitude = isSet($req['latitude'])  ?  (float)$req['latitude'] : 0; 



    if ($S->isInternalUser()) {

        $sql = "update system_orders set longitude=".$longitude.", latitude=".$latitude.", status='saved',modified='$datetime'  where status='basket' and reference_id=" . $S->id . " and client_id=" . $S->getClientId();

        //echo $sql;

    } elseif ($S->is_valid_client()) {

        $sql = "update system_orders set status='saved',modified='$datetime'  where status='basket' and reference_id='0' and client_id=" . $S->getClientId();

    }



    $db->Execute($sql);

}



function update_order_status($order_id, $status)

{

    global $db, $S;



    switch ($status)

    {

        case "printed":

        case "saved":

            $datetime = date("Y-m-j H:i:s");

            $sql = "UPDATE system_orders set status='$status' ";



            if ($status == 'saved') {

                $sql .= ", modified='$datetime' ";

            }

            $sql .= " where order_id='$order_id'";



            $db->Execute($sql);

            break;

    }

}

/** 

* the call_type options for Contact History

*/

function getContactCallTypeOptions() {

    global $db, $S;

    $sql = 'select * from call_type_options order by display_order asc';

    $res = do_query($sql);

    return query_to_array($res, 'id');

}

/**

* Add a new contact note to client

*/

function add_contact_note(){

    global $db, $S, $req;

    $repID = $S->getK9UserId();

    $clientID = $S->getClientId();

    $datetime = date("Y-m-j H:i:s");

    

    $longitude = isSet($req['longitude']) ?  (float)$req['longitude'] : 0;

    $latitude = isSet($req['latitude'])  ?  (float)$req['latitude'] : 0;  

    

    if ($repID > 0 && $clientID > 0 && $req['call_type_id'] > 0){

        

        $callOptions = getContactCallTypeOptions(); 

        

        

        if ($callOptions[$req['call_type_id']]['adjust_call_cycle'] > 0){// check if call type updates the last_contaced field

            $sql = '    INSERT INTO `contact_history` 

                        ( client_id, call_datetime, contacted, note, call_type_id, call_by,last_contacted_datetime, longitude, latitude ) 

                        VALUES 

                        ('.$clientID .',"'.$datetime.'","'.$req['contacted'].'","'.$req['note'].'",'.$req['call_type_id'].','.$repID.',"'.$datetime.'",'.$longitude.','.$latitude.')

                        ';

            

        } else { // dont update last_contacted field

             $sql = '    INSERT INTO `contact_history` 

                        ( client_id, call_datetime, contacted, note, call_type_id, call_by,created , longitude, latitude) 

                        VALUES 

                        ('.$clientID .',"'.$datetime.'","'.$req['contacted'].'","'.$req['note'].'",'.$req['call_type_id'].','.$repID.',"'.$datetime.'",'.$longitude.','.$latitude.')

                        ';

        }

        

       

        //$db->debug = true;       

        $db->Execute($sql);

        //$db->debug = false;exit;

    }

    

    

}

/**

    * Checks if rep has recorded mileage for TODAY

    * 

    */

    function is_mileage_recorded_for_today() {

        global $db, $S;

        if($S->isInternalUser() && $S->recordmileage){

            $sql = "select * from travel where sales_rep_id=".$S->id .' and traveldate="'.date('Y-m-d').'"';

            $res = do_query($sql);

            //echo dumper($res);

            if ($res[0]['startkm'] > 0){

                

                return true;

            } else {

                return false;

            }

        } else {

            return true;

        }

        

    }

    function update_start_mileage() {

        global $db, $S, $req ;

       

        if($req['startkm'] > 0){

            // update todays startkm

            do_query("delete from travel where sales_rep_id=".$S->id.' and traveldate="'.date("Y-m-d").'"'); // remove any other record for today - there should not be one

            $timestamp=time();

            $longitude = isSet($req['longitude']) ?  (float)$req['longitude'] : 0;

            $latitude = isSet($req['latitude'])  ?  (float)$req['latitude'] : 0;

            

            $sql = ' INSERT into travel (sales_rep_id, startkm, traveldate,startkm_timestamp,start_longitude,start_latitude) VALUES ('.$S->id.','.$req['startkm'].',"'.date('Y-m-d').'","'.date('Y-m-d H:i:s').'",'.$longitude.','.$latitude.')';

            do_query($sql);

        }

    }

    function update_end_mileage() {

        global $db, $S, $req ;

        

            $longitude = isSet($req['longitude']) ?  (float)$req['longitude'] : 0;

            $latitude = isSet($req['latitude'])  ?  (float)$req['latitude'] : 0;

        

          if (!empty($req['endkm']) ){// update last entry endkm if last entry is not today            

                $sql = 'UPDATE travel set end_longitude='.$longitude.', end_latitude='.$latitude.', endkm='.$req['endkm'] .',endkm_timestamp="'.date('Y-m-d H:i:s').'" where traveldate="'.date("Y-m-d").'"  and sales_rep_id='.$S->id;   

                //echo $sql;  exit;     

                do_query($sql) ;   

        }

    }

    

?>