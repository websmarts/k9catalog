<?php

function generate_quickreg_code($id,$prefix='A'){

    if($id > 0){

        $x = (string) $id;

        $total = ord($prefix);

        for($i=0; $i < strlen($x) ; $i++)

            $total += (int)$x[$i];

            

        return $prefix . $id . $total;  

    }

}

function do_menu_row($cats, $depth,$depthLimit=1,$current_catid=0)

    {

    global $S;

    

    if ($depth >=$depthLimit){

        return;

    }



    //echo dumper($cats);

    if (is_array($cats))

        {

        echo "\n".'<ul class="depth_'.$depth.'">';

        foreach ($cats as $cat)

            {

            $spacer = str_repeat("-", ($depth * 1));

            if ($current_catid == $cat[0]['id']){

                $class = ' class="active" ';

            }else {

                $class='';

            }

            echo  "<li><a ".$class." href=\"?v=list_products&amp;catid=" . $cat[0]['id'] . "\">" . $cat[0]['name']. "</a>";

            //echo dumper($cat);

            if (is_array($cat[1]) and count($cat[1]) > 0)

                {

                $depth++;

                do_menu_row($cat[1], $depth,$depthLimit,$current_catid);

                $depth--;

                }

                echo '</li>'."\n";

            }

            echo '</ul>'."\n";

        }

    }

?>



<?php 

//echo dumper($S->client)  ;

$title='K9Homes eCatalog Pet Products ';

$_catId = $req['catid'] ?? null;

if ($_catId )

                    {

                    //echo get_category_name($req['catid']);

                    //echo dumper($categoryData);

                    if ($categoryData[ $req['catid'] ]['parent_id'] == 0)

                        {

                        $title .= $categoryData[$req['catid']]['name']." ";

                        }else{

                            

                          $title .= $categoryData[ $categoryData[ $req['catid'] ]['parent_id'] ]['name']." ".$categoryData[ $req['catid'] ]['name'] ;  

                        }

                   

}?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns = "http://www.w3.org/1999/xhtml" xml:lang = "en" lang = "en">

<head>

        <title><?php echo $title;?></title>

     

  <link rel="shortcut icon" href="/catalog/favicon.ico" type="image/x-icon" />

  <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

  <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png" />

  <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />

  <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png" />

  <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />

  <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png" />

  <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png" />

  <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png" />

  

        <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>

        <meta name="description" content="K9Homes manufacture and distribute a wide range of high quality pet products" />



        <link rel="stylesheet" type="text/css" media="screen" href ="site.css?b=refresh" />

        <link rel="stylesheet" type="text/css" media="print" href="print.css?a=refresh" />

        

        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.18.custom.css" rel="Stylesheet" />    

        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>

        <script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>

        <!--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->

        

<?php if ($S->isInternalUser()):?>        

        <!--<script src="http://code.google.com/apis/gears/gears_init.js" type="text/javascript"></script>-->

        <!--<script src="js/geo.js" type="text/javascript" ></script>-->

        

        <script src="js/typeahead.js/typeahead.jquery.min.js" type="text/javascript"></script>

<?php endif;?>       

        

        <script type="text/javascript" >

        

        function set_typeid(typeid){

            document.forms['orderform'].elements["typeid"].value = 'type_' + typeid;

            return true;

        }

        </script>

</head>



<body>

<div id="pagecontainer">

<div id="topbar"><!--<p><a href = "?v=contactus">CONTACT US</a></p>-->

<?php if($S->is_valid()){

    echo '<p><a href="?e=logout" >LOGOUT</a></p>';

} 

?>

</div>  



<div id="header">

    <a href="../cms/"><img border="0" src = "images/logonew2.jpg"></a>

    <div id="controlbar">

    <?php if ($S->is_valid()) :?>

    

        <?php if($S->isInternalUser())

        {

            // display internal main memnu block

            echo '<div id="internalusermenu">'."\n";

             echo '&nbsp;[ ' . $S->rep_name .' ]&nbsp;&nbsp;&nbsp;&nbsp;';

             

             // import my pet warehous orders

             if($S->checkPrivileges('import_basket') && isSet($S->client['name']) && preg_match('/my pet warehouse/i',$S->client['name'])){

                echo ' | <a href="?v=basket_import" >Import Basket</a> | '."\n";

                

            }

            if($S->checkPrivileges('sales_analyser') ){

                echo ' | <a target="_blank" href="/catalog/sales_analyser.php" >Sales Analyser</a> | '."\n";

                

            }





            if($S->checkPrivileges('runsheet')){

                echo '<a href=?v=runsheet >Runsheet</a>&nbsp;|&nbsp';

            }



            echo '  <a href=?v=list_all_baskets >List my baskets</a>&nbsp;|&nbsp;

                    <a href="?e=changeClient" >Select client</a>';



            if($S->checkPrivileges('officeadmin')){

                //echo ' | <a href="?v=list_clients_orders" target="list_orders" >List orders</a>&nbsp;';

                

                echo ' | <a href="'.fido_gateway_link("home").'" target="fido">List orders</a>&nbsp;';

            }

            

            if($S->checkPrivileges('stockadjust')){

                echo ' | <a target="_blank" href="admin/?m=product&a=stockadjust" >Stock Adjust</a>'."\n";

                echo ' | <a target="_blank" href="admin/?m=stock&a=lowstock" >Low Stock</a>'."\n";

            }

            if(isSet($S->client['client_id']) && $S->client['client_id'] > 0 && $S->checkPrivileges('officeadmin')){

                

                 

                 echo '|&nbsp;<a href="'.fido_gateway_link("client").'" target="fido">Special prices</a>&nbsp;';

                 echo '|&nbsp;<a href="?v=basket_report"> Basket report</a>&nbsp;';

            }

            if ($S->recordmileage){ // does this k9user have to record mileage?

                    echo ' | <a href="?v=record_end_mileage" >End Odometer</a>'."\n";

            }    

            echo '</div>'."\n";

            

            echo '<div id="clientinfomenu">'."\n";

            

            //echo dumper($S->client) ;

            //echo dumper(get_freight_code($S->client['postcode']));

            if($S->getClientId() > 0 ){

                // client is selected

                

                //http://www2.k9homes.com.au/office/client/edit/1372

                if($S->checkPrivileges('editclientprofile')){// allow some to edit client profile

                    //echo '<div style="border:0px solid red; overflow: hidden;"><h3> 

                    //<a target="_blank" href="../office/client/edit/'.$S->getClientId().'" >'.$S->client['name'].'</a></h3>'."\n";

                    echo '<div style="border:0px solid red; overflow: hidden;">';

                    echo '<h3><a target="fido" href="'.fido_gateway_link("client/".$S->getClientId()."/edit").'" >'.$S->client['name'];
                    
                    if($S->client['parent'] > 0){
                        echo '<br><span style="background:red; color:white"> '.$S->client['parent_record']['name'].' </span>';
                    }
                    
                    echo '</a></h3>'."\n";

                } else {

                    echo  '<div style="border:0px solid red; overflow: hidden;"><h3>'. $S->client['name'] .'</h3>'."\n";

                }

                

                $phone = !empty($S->client['phone']) ? 'ph:'.$S->client['phone'] :'';

                $phone .= !empty($S->client['mobile']) ? ' mob: '.$S->client['mobile'] :'';

                

                // add a flag to indicate if client is registered online - FAILED BECAUSE online status seems to be set to active even when no login_user or passwd

                //$online_flag = $S->client['online_status'] =='active' ? '*':'';

                $online_flag = !empty($S->client['login_user'] ) && !empty($S->client['login_pass']) ? '*':'';

                

                echo '<p>'.$online_flag.'qrc: '.generate_quickreg_code($S->client['myob_record_id']).'</p>';

                

                // client address, email and phone

                echo '<p> email: '.$S->client['login_user'].'&nbsp;'.$phone.'</p>';// login_user is email

                

                $address = !empty($S->client['address1']) ? $S->client['address1'] :'';

                $address .= !empty($S->client['address2']) ? ', '.$S->client['address2'] :'';

                $address .= !empty($S->client['address3']) ? ', '.$S->client['address3'] :'';

                $address .= !empty($S->client['city']) ? ', '.$S->client['city'] :'';

                $address .= !empty($S->client['postcode']) ? ', '.$S->client['postcode'] :'';            

                echo '<p> '.$address.'</p>';

                echo !empty($S->client['contacts_3']) ?'<p style="float: none">Ordering Contact:'.$S->client['contacts_3'].'</p>':'';

                

                

                //echo '<p>'.$phone.'</p>';

                echo '</div>';

               

                echo '<p>'.display_freight_code($S->client['postcode'],$S->client['custom_freight']).'</p>';



                echo  '<p><a href="../office/client/orderhistory/' .  $S->getClientId() . '" target="_self" >Order history</a></p>'."\n";

                echo  '<p><a  href="?e=SelectClient&client_id=' . $S->getClientId()

                        . '">Contact history</a></p>'."\n";

                //echo  '<p><a href="../office/client/contact/' .  $S->getClientId() . '" target="_self" >Contact data</a></p>'."\n";

               echo  '<p><a href="../office/client/stockcount/' .  $S->getClientId() . '" target="_self" >Instore stock</a></p>'."\n";

                if ($S->basket_count() > 0)

                {

               

                    echo '<p>'.$S->basket_count()

                    . ' items:('.number_format(basketMargin() *100,1).') <a href="?v=basket">View client basket</a></p>'."\n";

                }

                if(!empty($S->client['freight_notes'])){

                    echo '<p style="clear:both;float:none; padding:3px; color: red; overflow:visible;">'.$S->client['freight_notes'].'</p>';

                }



                



                        

            } else {

                // client not selected

                //echo '<p><a href="?e=changeClient" >Select client</a></p>'."\n";

                

                

                

            }

            

            echo '</div>'."\n";

        }

        elseif ($S->is_valid_client())

        {

            if ($S->getClientId())

            { // display client name if one is selected

            



            // if it is the client logged in then just display their name

            if ($S->role == 'client')

                {

                echo '<h3>'.$S->client['name'].'</h3>'."\n";

                

                echo '<p><a href="?v=list_clients_orders">My orders</a></p><br />';

                

                if ($S->basket_count() > 0)

                {

               

                echo '<p>'.$S->basket_count()

                    . ' items: <a href="?v=basket">View basket</a></p>'."\n";

                }

                }

            else

                {

                

                

                }

            }

        }

        ?>

   

    

    <?php elseif ($S->nextview != 'login'): ?>



            <form method = "post" action = "" name = "login">

                <table class = "login_form">

                    <tr>

                        <td align = "right">

                            Email:

                        </td>



                        <td align = "left">

                            <input type = text size = 12 name = username style = "width:200px;">

                        </td>



                        <td rowspan = "2" align="center">

                            <input tabindex = "50" type = "submit" name = "e" value = "Login">

                            </td>

                    </tr>



                    <tr>

                        <td align = "right">

                            Password:

                        </td>



                        <td align = "left">

                            <input type = password name = password size = 13 style = "width:200px;">

                        </td>

                    </tr>

                </table>

            </form>

          

            



    <?php endif; ?>

    

    </div><!--end right float div-->

</div><!-- end header div-->

            



           

                        

<div id="leftbar" >

    <?php 
    
    $_m = $req['m'] ?? null;

    $_q = $req['q'] ?? null;
    if ($_m != "order") : ?>              

            <form  id="searchform" name="search_form">

                find:

                <?php if($_q == 'core' || $_q == 'special' || $_q == 'new product' || $_q == 'clearance'){

                    $q = '';

                } else {

                    $q = $_q;

                }

                ?>



                <input type = "text "size = "10" name = "q" value = "<?php echo $q ?>">

                <input type = "submit" name = "b" value = "go" >

                <input type = "hidden" name = "v" value = "product_search" >

            </form>



            

            



            <div id="catmenu">

            <ul class="alt">

            

            <?php if($S->isInternalUser()) :?>

            <li><a href = "?q=core&b=go&v=product_search">Core</a></li>

            <?php endif; ?>

            

            <li><a href = "?q=new%20product&b=go&v=product_search">New products</a></li>

            <li><a href = "?q=clearance&b=go&v=product_search">Clearance Items</a></li>

            <li><a href = "?q=special&b=go&v=product_search">Specials</a></li>

            </ul>

            <?php do_menu_row($categories, 0); ?>

            </div> 

            

            <?php if($S->isInternalUser()) : ?>

            <p><a href = "?v=list_all_products">Show All</a></p>

            <p><a href = "?v=list_products&amp;catid=48">Show Hidden</a></p>

            <?php endif;?>

                     

    <?php endif; ?>

</div><!-- end cat menu sidebar div -->              





 <div id="content"><!-- start of content cell-->

 <?php

    //$error_msg .= "NEXTVIEW=".$S->nextview." ROLE=".$S->role;

    //echo dumper($_SESSION['messages']);
    if(isSet($_SESSION['messages'])){

        $error_msg .= isSet($_SESSION['messages']['error']) ? $_SESSION['messages']['error'] : null;

        $flash_msg .= isSet($_SESSION['messages']['success']) ? $_SESSION['messages']['success'] : null;

        if (!empty($error_msg))

            {

            echo '<div class="error_msg">'.$error_msg.'</div>'."\n";

            }

        if (!empty($flash_msg))

            {

            echo '<div class="flash_msg">'.$flash_msg.'</div>'."\n";

            }

    }

    

?>

