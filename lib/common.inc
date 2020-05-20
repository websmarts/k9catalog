<?php
function log_error($msg="Error:"){
    global $S,$req,$db;
    if(!LOG_ERRORS || LOG_ERROR_FILE < "" ){
        return;
    }
    $backtrace=debug_backtrace(false);
    $last = $backtrace[0];
    $err = "\n======================================================================================================\n";
    $err .="== ". date('Y-m-d H:i:s')."  ==============================================================================\n";
    $err .= "BACKTRACE:\n".print_r($last,true)."\n\n";
    $err .= "DB ErrorMsg: ".$db->ErrorMsg()." \n";
    $err .= "\n";
    $err .=  $msg."\n State Obasketbject:\n";
    $err .= print_r($S,true); 
    $err .= "\n--------------------------------------------------------------------------------------------------------\n";
    $err .= 'Request $req :'."\n";
    $err .= print_r($req,true);
    $err .= "\n*****************************************************************************************************\n\n";
    error_log($err,3,LOG_ERROR_FILE);
}

/// fido gateway link
function fido_gateway_link($path){
    global $S;

    // IF $S->loggedin as REP #########

    return FIDO_BASE_URL."ecatgw?u=".$S->rep_name."&url=".$path;
}

/*
 * Next two functions support a Safer json_encode 
 */
function safe_json_encode($value, $options = 0, $depth = 512){
    $encoded = json_encode($value, $options, $depth);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return $encoded;
        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';
        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_UTF8:
            $clean = utf8ize($value);
            return safe_json_encode($clean, $options, $depth);
        default:
            return 'Unknown error'; // or trigger_error() or throw new Exception()

    }
}

function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } else if (is_string ($mixed)) {
        return utf8_encode($mixed);
    }
    return $mixed;
}

/*
*
*  COMMONLY USED FUNCTIONS
*
*/
function flashMessage($key='error',$msg){
    $_SESSION['messages'][$key] .= '<p>'.$msg.'</p>';
}
function setFormData($data){
    $_SESSION['formdata']=$data;
}
function getFormData($key=''){
    if(isSet($_SESSION['formdata'][$key])){
        return $_SESSION['formdata'][$key];
    } else {
        return '';
    }
}
function clearTmpSessionVars(){
    clearFormdata();
    unset($_SESSION['messages']);
}

function clearFormdata() {
    unset($_SESSION['formdata']);
}
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

    if ($repid==20){
        $sql = "SELECT * from clients where `salesrep`='".$repid."' order by name asc";
    } else {
        $sql = "SELECT * from clients order by name asc";
    }
    //echo $sql;
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
    $res =  do_query("select * from clients where client_id=$client_id");
   
    if($res[0]['parent'] > 0){
        
        $parentRes = do_query("select * from clients where client_id=".$res[0]['parent']);
       
        if($parentRes){
            $res[0]['parent_record']=$parentRes[0];
        }
        
    }
    //echo dumper($res[0]['parent_record']);
    return $res;
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

    if (is_array($list) && count($list) >0) {
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

function get_hierarchy_children($id, $depth, $table, $order = " order by display_order desc, name asc")
{
    $query = "SELECT * FROM $table WHERE parent_id =" . $id . " " . $order;
    $nlist = array();
    $list = do_query($query);

   // echo dumper($list);

    if (is_array($list) && count($list) > 0 ) {
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
function basketMargin() {
    global $S, $db;

    if (is_array($S->basket) && count($S->basket)) {

        $basketCost = 0;
        $basketPrice =0;
            
            $special_prices = get_client_price_specials($S->getClientId());

            foreach ($S->basket as $product_code => $qty)
            {
                $qty = (int) $qty; // paranoid maybe but clean up stupid qty entered
                
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

                // Get product cost
                $p = get_product_details($product_code);
                $basketCost += $p['cost'] * $qty;

                $basketPrice += $price * $qty;

            }

            return ($basketPrice - $basketCost)/$basketPrice;
        }
}

function productMarginHintCode($cost,$price)
{
    if($price != 0){
        return $cost."-".number_format(100 * ($price-$cost)/$price,0);
    }
    
}

function save_basket()
{
    global $S, $db;

    // get open order ids - should only be one!

    if (!$S->getClientId()) {
        //log_error('No ClientId selected in save_basket()');
        return;
    }
    // Get list of 'baskets' for the current logged in user
    // if user is a client
    if ($S->is_valid_client()) {
        $sql = "select order_id from system_orders  where ordered_by=" . $S->getClientId() . " and status='basket'";
    } elseif ($S->isInternalUser()) {
        $sql = "select order_id from system_orders  where reference_id=" . $S->id . " and client_id=" . $S->getClientId() . " and status='basket'";
    }
    $basket_order_ids = $db->GetArray($sql);

    // Delete current order details before replacing with items from session basket
    if (is_array($basket_order_ids[0]) && count($basket_order_ids)> 0) {
        foreach ($basket_order_ids as $k => $order)
        {
            $db->Execute("delete from system_order_items where order_id = " . $db->qstr( $order['order_id'] ) );
            $S->orderId = $order['order_id'];
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
                    $sql= "INSERT INTO system_order_items (order_id,product_code,qty,price) values('"
                    . $order['order_id'] . "'," . $db->qstr( $product_code ) . "," . $qty . ",$price )";

                    $rs = $db->Execute($sql);
                    if(!$rs){
                        log_error($sql);
                    }
                }
            }

            if (isSet($S->basket_instructions) && !empty($S->basket_instructions)) {
                $sql = "Update system_orders set instructions=" . quote_str($S->basket_instructions)
                . " where order_id=" . $db->qstr($order['order_id']);
                $rs = $db->Execute($sql);
                if(!$rs){
                    log_error($sql);
                }

            }
            if (isSet($S->order_contact) && !empty($S->order_contact)) {
                $sql = "Update system_orders set order_contact=" . quote_str($S->order_contact)
                . " where order_id=" . $db->qstr($order['order_id']);
                $rs = $db->Execute($sql);
                if(!$rs){
                    log_error($sql);
                }
            }
            if (isSet($S->freight_charge) ) {
                $sql = "Update system_orders set freight_charge=" . (float) $S->freight_charge
                . " where order_id=" . $db->qstr($order['order_id']);
                $rs = $db->Execute($sql);
                if(!$rs){
                    log_error($sql);
                }
            }
        }
        else
        { // nothing to save - should we delete the ORDER NOW!!! could be dangerous if another user is using it
            // for now we will  delete the empty order
            $rs = $db->Execute("delete from system_orders where order_id = " . $db->qstr( $order['order_id'] ) );
            if(!$rs){
                log_error($sql);
            }
        }
    }
    else
    { // no order exists so we need to start from scratch and create order and save order items

        // only save this order if there are order instructions or order items to be saved
        if (isSet($S->basket_instructions) || count($S->basket) > 0) {
            // create the new open order

            $sql = "INSERT INTO system_orders (client_id,`status`,instructions,order_contact,freight_charge,reference_id,ordered_by,modified) VALUES ("
            . $S->getClientId() . ",'basket'," .quote_str($S->basket_instructions)  . "," . $db->qstr( $S->order_contact ) .",".(float) $S->freight_charge.",'" . $S->id . "','" . $S->is_valid_client() . "','". date("Y-m-j H:i:s")."')";

            // echo $sql;  exit; 
            $rs = $db->Execute($sql);
            if(!$rs){
                log_error($sql);
            }

            $id = $db->Insert_ID();
            // now we need to update the order with its alph order_id
            $order_id = TERMINALID . "_" . $id;
            $S->orderId = $order_id;
            $sql = "Update system_orders set order_id ='" . $order_id . "' where id=".$id;
            $rs = $db->Execute($sql);
            if(!$rs){
                log_error($sql);
            }
        }
        // echo dumper($S->basket); exit;

        // Only do the save items if there are some items to save
        if (is_array($S->basket) && count($S->basket)) {
            // get the prices to save with the order
            // There may be special prices that override standard prices
            $special_prices = get_client_price_specials($S->getClientId());

            foreach ($S->basket as $product_code => $qty)
            {
                $qty = (int) $qty; // paranoid maybe but clean up stupid qty entered
                
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
                $sql = "INSERT INTO system_order_items (order_id,product_code,qty,price) values('" . $order_id
                    . "'," . $db->qstr( $product_code ) . "," . $qty . ",$price )";
                    
                $rs = $db->Execute($sql);
                if(!$rs){
                    log_error($sql);
                }

            }
        }

        $db->debug = false;

    }

    $db->debug = false; // turn off any db debugging
}
function return_bom_item_to_stock($item,$qty)
{
    global $S, $db;
    $item_qty = $qty * $item['item_qty']; // bom qty * number of items in bom

    // Check if item is also a bom??
    $bom_bom_items = get_bom_items($item['item_product_code']);
    if(count($bom_bom_items) > 0)
    {
        foreach ($bom_bom_items as $bom_item)
        {
            update_bom_item($bom_item,$item_qty);
        }
    } 
    else 
    {
        $db->Execute("UPDATE products set qty_instock=qty_instock + ".$item_qty." where product_code="
            . $db->qstr( $item['item_product_code'] ) );
    }

}

function return_order_items_to_stock($order_id){
    $items = do_query('select * from system_order_items where order_id="'.$order_id.'"');
    if($items && is_array($items) && count($items)){
        foreach($items as $i) {
            // check if item is a BOM
            $bom_items = get_bom_items($i['product_code']);
            if(count($bom_items) > 0){
                foreach($bom_items as $bi){
                    return_bom_item_to_stock($bi,$i['qty']);// note minus puts items back in stock
                }

            } else {
                // not a BOM so just update the product
                do_query('update products set qty_instock=qty_instock + '.$i['qty'].' where product_code ="'.$i['product_code'].'"');
            }

        }
    }
    // delete order_items
    do_query('delete from system_order_items where order_id="'.$order_id.'"');
}

function take_order_items_out_of_stock($order_id){
    $items = do_query('select * from system_order_items where order_id="'.$order_id.'"');
    if($items && is_array($items) && count($items)){
        foreach($items as $i) {
            // check if item is a BOM
            $bom_items = get_bom_items($i['product_code']);
            if(count($bom_items) > 0){
                foreach($bom_items as $bi){
                    update_bom_item($bi,$i['qty']);// takes  bom items out of stock
                }

            } else {
                do_query('update products set qty_instock=(qty_instock - '.$i['qty'].') where product_code ="'.$i['product_code'].'"');
            }
        }
    }

}

function get_bom_items($product_code)
{
    global $S, $db;
    return $db->getArray("select * from boms where parent_product_code ='" . $product_code . "'"); 
}
function update_bom_item($item,$qty)
{
    global $S, $db;
    $item_qty = $qty * $item['item_qty']; // bom qty * number of items in bom

    // Check if item is also a bom??
    $bom_bom_items = get_bom_items($item['item_product_code']);
    if(count($bom_bom_items) > 0)
    {
        foreach ($bom_bom_items as $bom_item)
        {
            update_bom_item($bom_item,$item_qty);
        }
    } 
    else 
    {
        if ($item_qty < 0){// adding items back into stock
            $db->Execute("UPDATE products set qty_instock=qty_instock + " .($item_qty * -1)." where product_code='"
                . $item['item_product_code'] . "'");
        } else {// taking items out of stock
            $db->Execute("UPDATE products set qty_instock=qty_instock - ".$item_qty." where product_code="
                . $db->qstr( $item['item_product_code'] ) );  
        }
    }

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

        $maxAvailable = count($numboms) > 0 ? min($numboms) : 0;
        return array('max_available'=> $maxAvailable);
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

function clean_string($string){
    return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $string);
}

function update_basket($req,$priceClear = false)
{
    global $S;

    if (is_array($req['basket'])) { // transfer req basket to session basket

        //echo dumper($req);
        //echo dumper($S->basket);
        //echo dumper($S->basket_prices); exit;

        foreach ($req['basket'] as $product_code => $item)
        {
            if ($item['qty'] > 0) {
                $S->basket[$product_code] = $item['qty'];




                // Check if update is being done from basket view where
                // prices can be updated
                if ($S->isInternalUser()) {
                    if (strlen($item['price']) > 0) {
                        //echo dumper($item);

                        // check if a minus sign has been added to indicate the price figure is actually a discount
                        $priceFigure = (float)$item['price'];
                        if($priceFigure < 0 ){
                            //then it is a discount NOT a price
                            // Get the system price
                            $product = get_product_details($product_code);
                            $item['price'] = (float) $product['price']/100 * (1 + ($priceFigure/100) );
                        }


                        $S->basket_prices[$product_code] = ( (float) $item['price'] ) * 100; // price hack
                    }
                    elseif($priceClear)
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
            $S->basket_instructions = clean_string($req['basket_instructions']);
        }
        // update the order contact
        if (isSet($req['order_contact'])) {
            $S->order_contact = $req['order_contact'];
        }
         // update the freight charge
        if (isSet($req['freight_charge'])) {
            $S->freight_charge = $req['freight_charge'];
        }

    }
}

function restore_client_basket()
{
    global $S;

    //echo dumper($S);

    if ($S->getClientId()) {
        $qry = "select order_id,instructions,order_contact from system_orders where status='basket' and client_id=" . $S->getClientId();

        if ($S->id > 0) { // this is a rep not a client
            // exclue client created baskets = eg where reference_id=0
            $qry .= ' and reference_id=' . $S->id;
        }

        $order_id = do_query($qry);
        //echo "ORDER_IDS =".dumper($order_id);

        if (is_array($order_id) && count($order_id) > 0) {
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
            // restore basket instructions
            $S->order_contact = $order_id[0]['order_contact'];
        }
    }
}

function get_client_price_specials($client_id)
{
    $client_prices =
    do_query(
        "select products.product_code,client_price 
        from 
        products,
        client_prices 
        where 
        products.product_code=client_prices.product_code 
        and client_prices.client_id="
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

function get_order_items($order_id){
    global $db;
    $sql = "select * from system_order_items  where order_id='$order_id'";

    $res = $db->GetArray($sql);

    return $res;
}

function get_system_order_details($order_id)
{
    global $db;
    $sql = "select * from system_orders where order_id='$order_id'";
    //$sql .= " order by product_code DESC";

    $res = $db->GetArray($sql);

    return $res;
}

function get_orders($status, $clientID = 0)
{
    global $S;
    $sql =
    "select system_orders.*,clients.name from system_orders,clients where system_orders.reference_id=" . $S->id . " and system_orders.client_id = clients.client_id and system_orders.status='$status'";
    if ($clientID > 0) {
        $sql .= ' and clients.client_id=' . $clientID . ' ';
    }
    $res = do_query($sql);
    return $res;
}

function get_sales_report($clientID = 0)
{

    $currentMonthYear = date('Y-m').'-00';
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
    //echo dumper($sql);

    $res = do_query($sql);

    return $res;
}

function get_order_detail($order_id)
{
    $sql =
    "select 
    clients.name,
    clients.client_id,
    clients.postcode,
    system_orders.id,
    system_orders.order_id,
    system_orders.instructions,
    system_orders.order_contact,
    system_orders.reference_id,
    system_order_items.product_code,
    system_order_items.qty,
    system_order_items.price, 
    products.description,
    products.price as standard_price,
    products.cost as cost,
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
    and system_order_items.product_code=products.product_code 
    order by system_order_items.product_code desc";
    return do_query($sql);
}

function get_completed_orders($day) {
    // COMPLETED means order has been PICKED status and has exported=yes
    $sql ="select system_orders.*,clients.name from system_orders,clients where system_orders.client_id = clients.client_id and system_orders.status='picked' and `exported`='yes' ".' and DATE_SUB(NOW(),INTERVAL ' . $day . ' DAY) < system_orders.modified  order by system_orders.id desc  ';

    return do_query($sql);
}

function get_picked_orders($day) {
    // PICKED but not EXPORTED  orders
    $sql ="select system_orders.*,clients.name from system_orders,clients where system_orders.client_id = clients.client_id and system_orders.status='picked' and `exported`!='yes' ".' and DATE_SUB(NOW(),INTERVAL ' . $day . ' DAY) < system_orders.modified order by system_orders.id desc ';

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

function update_client_email($email){
    global $db,$S;
    if ($S->isInternalUser() && $S->getClientId() > 0 && !empty($email)) {
        $sql = 'update `clients` set `login_user`="'.$email.'" where client_id='.$S->getClientId();
        $db->Execute($sql);
    }
}

function save_order()
{
    global $db, $S,$req;
    //set the order status from basket to 'saved' and update modified timestamp
    $datetime = date("Y-m-j H:i:s");

    $longitude = isSet($req['longitude']) ?  (float)$req['longitude'] : 0;
    $latitude = isSet($req['latitude'])  ?  (float)$req['latitude'] : 0; 



    if ($S->isInternalUser()) { // Internal generated order
        $sql = "update system_orders set longitude=".$longitude.", latitude=".$latitude.", status='saved',modified='$datetime'  where status='basket' and reference_id=" . $S->id . " and client_id=" . $S->getClientId();

        //echo $sql;
    } elseif ($S->is_valid_client()) {// client generated order
        $sql = "update system_orders set status='saved',modified='$datetime'  where status='basket' and reference_id='0' and client_id=" . $S->getClientId();
    }

    $db->Execute($sql);


    // Updated to remove stock once order is saved
    // NOW REMOVE ITEMS FROM STOCK 
    remove_order_items_from_stock($S->orderId);    

}

function remove_order_items_from_stock($orderId){
    global $db, $S;
    $items = get_order_items($orderId);
    if(is_array($items) && count($items) > 0){
        foreach ($items as $item){


            // Check if item is a bom
            $bom_items = $db->getArray("select * from boms where parent_product_code ='" . $item['product_code'] . "'");
            if (is_array($bom_items) && count($bom_items) > 0) 
            {
                foreach ($bom_items as $bom_item)
                {
                    $item_qty = $item['qty'] * $bom_item['item_qty']; // bom qty * number of items in bom
                    $db->Execute("UPDATE products set qty_instock=qty_instock - $item_qty where product_code='"
                        . $bom_item['item_product_code'] . "'");
                }
            } 
            else 
            {
                $db->Execute('UPDATE products set qty_instock=qty_instock -'.$item['qty'] .' where product_code='.quote_str($item['product_code']) );
            }
        }
    }

}

function update_order_status($order_id, $status)
{
    global $db, $S;

    switch ($status)
    {
        case "printed":
        case "saved":
        case "picked":
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
function getClientNotifyMes(){
    global $db, $S;
    $clientId= $S->getClientId();
    if($clientId){
        $sql = 'select * from notify_me where client_id='.$clientId;
        $res = do_query($sql);
        return query_to_array($res, 'product_code');
    }

}

function delete_contact_note($id=0){
    global $db;
    if ($id){
        $db->execute('delete from contact_history where id='.$id);
        return true;
        flashMessage('success',"Contact record has been deleted");
    }
    return false;
}

function update_contact_note($data){
    global $db,$req;
    $datetime = date("Y-m-j H:i:s"); // not actualy correct but the best we can do for now without showing this on form

    // hack to allow empty contacted and notes fields for skip_cycle 


    if ( $data['call_type_id'] > 0 && !empty($data['contacted']) && !empty($data['note'])){
        $sql = 'Update contact_history 
        set call_type_id ='.$data['call_type_id'] .',
        contacted = '.$db->qstr($data['contacted']) .',
        note = '. $db->qstr($data['note']) ;

        $callOptions = getContactCallTypeOptions();
        // echo dumper($req) ;
        // echo dumper($callOptions);exit;


        if ($callOptions[$req['call_type_id']]['adjust_call_cycle'] > 0){// check if call type updates the last_contaced field
            $sql .= ',  last_contacted_datetime="'.$datetime .'" ';

        } else { // dont update last_contacted field
            $sql .= ',  last_contacted_datetime=null ';
        }

        $sql .= ' where id='.$data['id'] . ' limit 1 ';
        // echo $sql; exit;
        $db->execute($sql);
        return true;
    } 
    return false;
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

    $data['call_type_id'] = $req['call_type_id'];
    $data['contacted'] = $req['contacted'];
    $data['note'] = $req['note'];
    setFormData($data);




    if ($repID > 0 && $clientID > 0 && $req['call_type_id'] > 0 && !empty($req['contacted']) && !empty($req['note'])){

        $callOptions = getContactCallTypeOptions(); 

        if ($callOptions[$req['call_type_id']]['adjust_call_cycle'] > 0){// check if call type updates the last_contacted field
            $sql = '    INSERT INTO `contact_history` 
            ( client_id, call_datetime, contacted, note, call_type_id, call_by,created,last_contacted_datetime, longitude, latitude ) 
            VALUES 
            ('.$clientID .',"'.$datetime.'",'.$db->qstr($req['contacted']).','.$db->qstr($req['note']).','.$req['call_type_id'].','.$repID.',"'.$datetime.'"'.',"'.$datetime.'",'.$longitude.','.$latitude.')
            ';

        } else { // dont update last_contacted field
            $sql = '    INSERT INTO `contact_history` 
            ( client_id, call_datetime, contacted, note, call_type_id, call_by,created , longitude, latitude) 
            VALUES 
            ('.$clientID .',"'.$datetime.'",'.$db->qstr($req['contacted']).','.$db->qstr($req['note']).','.$req['call_type_id'].','.$repID.',"'.$datetime.'",'.$longitude.','.$latitude.')
            ';
        }

        // echo $sql; exit;
        // $db->debug = true;       
        $db->Execute($sql);
        // $db->debug = false;exit;
        clearFormdata();
    } else {
        flashMessage('error','Note Type, Talked To and Note fields must be filled out');
    }


}
/**
* Checks if rep has recorded mileage for TODAY
* 
*/
function is_mileage_recorded_for_today() {
    global $db, $S;
    //echo dumper($S);exit;
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

function repSalesOrders($today) {
    global $db,$S,$req;

    // get the sales for the period
    $sql =     "    SELECT SUM(system_order_items.qty * system_order_items.price)
    AS sales,reference_id
    FROM system_orders   
    JOIN system_order_items ON system_order_items.order_id = system_orders.order_id
    where DATE(modified) = '".$today ."' AND system_orders.status != 'basket'
    GROUP BY system_orders.reference_id";
    //echo dumper($sql);
    $result = array();

    $rows = do_query($sql);
    if($rows) {
        foreach($rows as $row) {
            $result[$row['reference_id']]['sales'] = $row['sales'];
        }    
    }
    // if($query = mysqli_query($sql)){
    //     while ( $row = mysql_fetch_assoc($query) ) {
    //         $result[$row['reference_id']]['sales'] = $row['sales'];
    //     }
    // }
    // get the number of orders
    $sql = "select 
    count(system_orders.id) as orders,  system_orders.reference_id 
    from system_orders  

    where  system_orders.reference_id > 0 and DATE(modified) = '".$today ."'

    Group by system_orders.reference_id";
    //echo dumper($sql);
    
    $rows = do_query($sql);
    if($rows) {
        foreach($rows as $row) {
             $result[$row['reference_id']]['orders'] = $row['orders'];
        }    
    }
    // if($query = mysql_query($sql)){
    //     while ( $row = mysql_fetch_assoc($query) ) {
    //         $result[$row['reference_id']]['orders'] = $row['orders'];
    //     }
    // }




    // get calls made in period
    $sql = " select count(*) as calls,call_by from contact_history 
    join call_type_options on call_type_options.id=contact_history.call_type_id 
    where call_type_options.adjust_call_cycle = 1 
    and  DATE(call_datetime) = '".$today ."'
    Group by call_by";

    //echo dumper($sql); 
    $rows = do_query($sql);
    if($rows) {
        foreach($rows as $row) {
             $result[$row['call_by']]['calls'] = $row['calls'];
        }    
    }
    // if($query = mysql_query($sql)){
    //     while ( $row = mysql_fetch_assoc($query) ) {
    //         $result[$row['call_by']]['calls'] = $row['calls'];
    //     }
    // } 


    return $result;

}

function get_order_export_data($orderId){
    $o ='';
    if($orderId > 0 ){
        $sql = '    
        SELECT 
        so.id AS order_id,
        DATE_FORMAT(so.modified,"%d-%m-%Y") AS order_date,
        users.firstname AS `Sales Person First Name`,
        users.lastname AS `Sales Person Last Name`,
        soi.product_code AS `Item Number`,
        soi.qty AS Quantity,
        p.price AS Stdprice,
        soi.price AS Invprice,

        c.name AS `Co./Last Name`,
        c.address1,
        c.address2,
        c.address3,
        c.city,
        c.postcode,
        cp.name AS parent_company


        FROM system_orders AS so
        JOIN system_order_items AS soi ON so.order_id=soi.order_id
        LEFT JOIN clients AS c ON so.client_id = c.`client_id`
        LEFT JOIN clients AS cp ON c.parent=cp.client_id
        LEFT JOIN users ON users.id=c.`salesrep`
        JOIN products AS p ON p.product_code=soi.product_code
        WHERE so.id='.$orderId .' ORDER BY soi.product_code asc';


        $orderlines = do_query($sql);

        //echo pr($orderlines);



        foreach ($orderlines as $l){
            $o .= format_line($l);
        }

        // mark the order as exported
        $sql = 'update system_orders set `exported`="yes" where id='.$orderId;
        do_query($sql);
    }

    return $o;

}
/**
* Emails a csv file to office for importing into myob
* */
function export_order($orderId){
    $o ='';
    $header = 'Co./Last Name,First Name,Addr 1 - Line 1,           - Line 2,           - Line 3,           - Line 4,Inclusive,Invoice #,Date,Customer PO,Ship Via,Delivery Status,Item Number,Quantity,Description,Price,Inc-Tax Price,Discount,Total,Inc-Tax Total,Job,Comment,Journal Memo,Salesperson Last Name,Salesperson First Name,Shipping Date,Referral Source,Tax Code,Non-GST Amount,GST Amount,LCT Amount,Freight Amount,Inc-Tax Freight Amount,Freight Tax Code,Freight Non-GST Amount,Freight GST Amount,Freight LCT Amount,Sale Status,Currency Code,Exchange Rate,Terms - Payment is Due,           - Discount Days,           - Balance Due Days,           - % Discount,           - % Monthly Charge,Amount Paid,Payment Method,Payment Notes,Name on Card,Card Number,Expiry Date,Authorisation Code,BSB,Account Number,Drawer/Account Name,Cheque Number,Category,Location ID,Card ID,Record ID'."\r\n";

    if( is_array($orderId) ){
        //$orderId is an array
        $n=0;
        foreach($orderId as $k=>$v){
            if($n > 0 ){
                // insert blank line
                $o .= ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,\r\n";
            }
            $o .= get_order_export_data($k);
            $n++;
            $filename="multiple_".time();
        }

    } else {
        // $orderId is a scalar
        $o = get_order_export_data($orderId);
        $filename=$orderId;
    }

    $o = $header . $o;

     //echo pr($o);exit;
    // output as csv file.
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="TO_'.$filename.'.txt"');
    echo $o;
    exit;


}

function qc($str){ // quote commas in csv
    $str = trim($str); //remove any newlines
    $str = preg_replace('/\s+/',' ',$str);
   // return str_replace(',','","',$str); // changed 21/09/2015 because commas in addresses were screwing things up on export.
    return str_replace(',',';',$str);
}

function format_line($l){

    $o ='';

    if(!empty($l['parent_company'])){
        $o .= qc($l['parent_company']).',';// Co./Last Name
        $o .= ',';// First Name
        $o.=qc($l['Co./Last Name']).',';// Addr 1 - line 1
        $o.=qc($l['address1'].' '.$l['address2'] ).',';// Addr 1 - line 2
        $o.=qc($l['city']).' '.$l['postcode'].',';// Addr 1 - line 3
        $o.=',';// Addr 1 - line 4


    } else {
        $o .= qc($l['Co./Last Name']).',';// Co./Last Name 
        $o .= ',';// First Name
        $o .= ','; // Addr 1 - line 1
        $o .= ','; // Addr 1 - line 2
        $o .= ','; // Addr 1 - line 3
        $o .= ','; // Addr 1 - line 4
    }



    $o .= ',';// Inclusive
    $o .= ',';// Invoice #
    $o .= ',';// Date - order date dd/mm/yyyy, leave blank and myob put 
    $o .= ',';// Customer PO
    $o .= ',';// Ship
    $o .= ',';// Delivery Statu
    $o.=qc($l['Item Number']).',';// Item Number
    $o.=qc($l['Quantity']).',';// Quantity
    $o.=',';// Description
    $o.= '$'.number_format($l['Stdprice']/100,2,'.','').',';// Price in dollars and cents MUST have $ sign eg $23.45 - ex gst

    $extprice = 1.1 *  ($l['Stdprice'] * $l['Quantity'])/$l['Quantity']; // cents
    $priceplusgst = number_format($extprice/100,2,'.','');
    $o.= '$'.$priceplusgst.',';// Price inc gst    
    $o .= ',';// Discount

    $invprice =$l['Quantity'] * $l['Invprice'];
    $invprice_plusgst = 1.1 * $invprice;
    $o.= '$'.number_format($invprice/100,2,'.','').',';// Total Price ex gst
    $o.= '$'.number_format($invprice_plusgst/100,2,'.','').',';// Total Price inc gst
    $o .= ',';// Job
    $o .= 'Thank you for your order. We appreciate your business.'.',';// Comment
    $o .= ',';// Journal Memo
    $o.=qc($l['Sales Person Last Name']).',';// Sales Person First Name
    $o.=qc($l['Sales Person First Name']).',';// Sales Person Last Name
    $o .= ',';// Shipping Date
    $o .= ',';// Referral Source
    $o .= 'GST'.',';// Tax code
    $o .= ',';// Non GST Amount
    $o .= '$'.number_format(($invprice_plusgst - $invprice)/100,5,'.','').',';// GST Amount provide at least 3 decimal places
    $o .= ',';// LCT Amount
    $o .= ',';// Freight Amount
    $o .= ',';// Inc Tax Freight Amount
    $o .= 'GST,';// Freight Tax Code
    $o .= ',';// Freight Non Gst Amount
    $o .= ',';// Freight GST Amount
    $o .= ',';// Freight LCT Amount
    $o .= 'O,';// Sales Status O for order
    $o .= ',';// Currency Code
    $o .= ',';// Exchange Rate
    $o .= ',';// Terms payment due
    $o .= ',,,,,,,,,,,,,,,,,,'."\r\n"; // last 22 blank fields








    return $o;
}

function get_freight_code($postcode){

    $sql = 'select `zone` from freight_zones where `pcode`="'.$postcode.'" limit 1';
    $res = do_query($sql);

    // echo dumper ($res);

    $local = do_query('select * from freight_localzones where `zone` ="'.$res[0]['zone'].'"');



    return array('zone'=>$res[0]['zone'],'local'=>!empty($local));
}
function display_freight_code($postcode,$custom_freight = 0){
    if(! $custom_freight){
        $fc = get_freight_code($postcode);
        $local = $fc['local'] == 1 ? ' Local ': '';
        return $fc['zone'].$local;
    } else { 
        return 'Custom Freight';
    }

}

?>