<?php

/* 
*Controller used where the authenticated userRole = "client" - i.e sales rep
*/

// CRUD Controller


$e = isSet($req['e']) ? strtolower($req['e']) : '';

switch( $e ) {
    
    case 'import_basket': // import pet warehous order items into a basket
        if(($_POST) ){
            // check client is a mypetwarehouse clients
            //echo dumper($S->client);
            if(! preg_match('/my pet warehouse/i',$S->client['name'])){
                echo "Import function is only for My Pet Warehouse. You will need to select one of the My Pet Warehouse stores before you attempt the import."; 
                exit;
            }
            
             //echo dumper($_POST); exit;
             $lines = explode("\n",$_POST['data']);
             //echo dumper($lines); 
             $errors = '';
             foreach($lines as $l){
                 $fields=explode("\t",$l);
                 
                 $qty = trim($fields[0]);
                 $pcode = trim($fields[1]);
                 //$priceCents = ((float) substr($fields[4],1,10)) * 100; // convert to cents
                 
                 
                  
                 if(is_numeric($qty)){
                     //echo dumper($fields);
                     
                     // Check produict code exists and stock is available
                     $pexists = get_product_details($pcode);
                     
                     
                     if($pexists ){
                         // Product code exists - now check if we have enought stock
                         if($pexists['qty_instock'] < $qty){
                            $errors .= '<p>Insufficient stock for product code <strong>'.$pcode.'</strong> '.$qty .' items ordered and there are '.$pexists['qty_instock'].' available instock.</p>';    
                         } else {
                             //Add to basket
                             $S->basket[$pexists['product_code']] = $qty;// Note we use product code from DB not import (case sensitive)
                             //$S->basket_prices[$pcode] =   $priceCents ; 
                         }
                         
                        
                     } else {
                        $errors .= '<p>Product code <strong>'.$pcode.'</strong> Does not exist.</p>';
                     }
                     
                     
                     
                 }
                 
             }
             //echo dumper($errors);
             //echo dumper($S->basket);
             //echo dumper($S->basket_prices);
             if(!empty($errors)){
                 $_SESSION['messages']['error']='<div style="text-align: left"><h3>The following items have not been added to the basket:</h3>'.$errors.'</div>';
             }
             //echo dumper($_SESSION['messages']);
             //exit;
            
             
             $S->save();
             //session_write_close();
             //header('location: index.php?v=basket');
             //exit;
             //break;
             $S->nextview='basket';
             
        }
        break;
    case 'export_orders':
        if($_POST){
            // echo dumper($_POST);
            export_order($_POST['export_order_id']);
            exit;
        }
        break;
    case 'export_order':
        export_order($_REQUEST['order_id']);

        exit;

        break;
    case "nis":
        $product_code = $req['pcode'];
        $client = $S->getClientData();
        if($client['client_id'] > 0 && !empty($product_code)){

            if($S->isInternalUser()){
                $k9userID = $S->getK9UserId();
            } else {
                $k9userID = 0;
            }

            $sql = "INSERT INTO notify_me (client_id,submitted,product_code,k9userID) values(" . $client['client_id'].",'".date('Y-m-d H:i:s')."','" . $product_code . "'," . $k9userID . ")";
            $db->Execute($sql);
            $_SESSION['messages']['success'] = "We will notify you when stock for ".$product_code . ' becomes available';
        }

        //echo dumper($_SERVER['HTTP_REFERER']);
        $S->save();
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit;

    case "addtobasket":
    case "update": // Updates from eCat
        // add to basket is same as update basket !
        update_basket($req,false);// just the $S_basket - not the db basket

        $returnUrl = preg_replace('/\#.*/','', $_SERVER['REQUEST_URI']);
        $returnUrl .= '#'.$_POST['typeid'];
        // do a redirect to the page so we can go to the anchor
        $S->save();
        header('location:'.$returnUrl);
        exit;
        break;


    case "updatebasket": // updates from basket view
        //echo dumper($req);
        update_basket($req,true);// just the $S_basket - not the db basket

        $returnUrl = preg_replace('/\#.*/','', $_SERVER['REQUEST_URI']);
        $returnUrl .= '#'.$_POST['typeid'];
        // do a redirect to the page so we can go to the anchor
        $S->save();
        header('location:'.$returnUrl);
        exit;
        break;


    case "clearbasket";
        $S->basket = false;
        delete_basket($S->getClientId());		
        break;

    case "saveorder":
    case "save & send order":
        // Update basket before saving - it might have been changed on the basket form

        update_basket($req); // updates the session basket 

        // Now - Save the basket to an order and clear the basket

        // order_contact is mandatory so check it now
        if(!isSet($S->order_contact) || empty($S->order_contact)){

            $S->save();
            flashMessage('error','Please supply an Order Contact ');
            header('location: index.php?v=basket');
            exit; 
        }


        save_basket(); // updates the basket order in database


        save_order(); // sets the order status to saved

        // all saved so clear the basket now
        /*
        unset($S->basket);
        unset($S->basket_instructions);
        unset($S->basket_prices);// added 11-04-2014
        unset($S->order_contact);

        */
        $S->clearBasket();
        $S->save(); // save state to sesssion
        flashMessage('success','Your order has been saved');
        header('location: index.php?v=ordersaved');
        exit; 

        // force logout
        //unset($S->client_id);
        // unset($S->role);
        break;
    case "changeclient":
        // save the current client basket


        save_basket();
        $S->clearBasket();
        //unset($S->client_id);	// kill the client id
        unset($S->client);
        unset($S->order_contact);
        $S->nextview = isSet($req['v']) ? $req['v']:"select_client";
        break;



    case "selectclient":


        // check if a canclel notify me is include in request
        if(isSet($req['cancel_notify_me_for']) && !empty($req['cancel_notify_me_for'])){
            $sql = ' Delete from notify_me where client_id='.$S->client['client_id'].' and product_code="'.$req['cancel_notify_me_for'].'" ';
            do_query($sql);

        }


        save_basket();
        $S->clearBasket();
        $client = get_client_details($req['client_id']);
        $S->client = $client[0];
        //$S->client_id = $req['client_id'];
        restore_client_basket();

        //echo dumper($client); exit;


        $S->nextview = isSet($req['v']) ? $req['v']:"contact_notes"; // if new view specified with client change - use it or default to specials
        break;

    case 'update_contact_note':
        //echo dumper($_SERVER); exit;

        if($_POST['delete_contact_note']){
            if(!delete_contact_note($_POST['id'])){
                flashMessage('error','Error: failed to delete contact note');
            }
            flashMessage('success','contact note deleted');
            // return to runsheet
            $S->save();
            header('location: index.php?v=runsheet');
            exit;  

        } else {
            if(!update_contact_note($_POST)){ 
                flashMessage('error','Note Type, Talked To and Note fields must be filled out');
                // return to referer
                $S->save();
                header('location: index.php?v=edit_contact_note&id='.$_POST['id']);
                exit;

            };
            flashMessage('success','contact note updated');
            $S->save();
            header('location: index.php?v=edit_contact_note&id='.$_POST['id']);
            exit;
        }

        break;
    case "save_contact_note" :
        //echo dumper($_SERVER); exit;
        add_contact_note();
        $S->save();
        header('location: index.php?v=contact_notes');
        exit;   
    case 'update_client_email':

        update_client_email($_POST['login_user']);
        $client = get_client_details($S->getClientId());
        $S->client = $client[0];
        $S->nextview="default";
        break;

    case "logout":
        $S->logout(); // also saves client basket 
        break;

    case "updatestartmileage":
        update_start_mileage();
        if (!is_mileage_recorded_for_today()) {
            $S->nextview = "select_client";  
        } 

        break;
    case "updateendmileage":
        update_end_mileage();
        break;



}

/*
** REPS need to select a CLIENT_ID to work with so if  one is not selected - force the select client view
* Except for the view list_all_baskets which is displayed with NO client_id set
*/


if ($S->isInternalUser()) 
{   // check to see if rep needs to record traveldata and if they have entered it for TODAY
    // This will be an annyance if they are using computer around midnight changeover!!!
    
    if (!is_mileage_recorded_for_today()) {
        $S->nextview = 'record_start_mileage';
    } else if (!$S->getClientId() and ($S->nextview != "sales_report" and $S->nextview != "list_all_baskets" and $S->nextview !="list_clients_orders"  and $S->nextview !="orderview" and $S->nextview !="record_end_mileage" and $S->nextview !="runsheet") ) { // list baskets 
        
        $S->nextview = "select_client"; // force rep to first choose a client
    }
}
elseif ($S->role =="client") 
{ // this happens when client does logout
    if (!$S->is_valid_client() ) { // show public face 
        // This selects the view displayed after a client logs out
        $S->nextview = "default";
    }
}

// get list of notify_mes for the current client
$client_notifies = getClientNotifyMes();

//echo dumper($client_notifies);