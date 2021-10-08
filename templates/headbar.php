<div id="header">

    <a href="../cms/"><img border="0" src="images/logonew2.jpg"></a>

    <div id="controlbar">

        <?php if ($S->is_valid()) : ?>

            <?php if ($S->isInternalUser()) {

                // display internal main memnu block

                echo '<div id="internalusermenu">' . "\n";

                echo '&nbsp;[ ' . $S->rep_name . ' ]&nbsp;&nbsp;&nbsp;&nbsp;';



                // import my pet warehous orders

                if ($S->checkPrivileges('import_basket') && isset($S->client['name']) && preg_match('/my pet warehouse/i', $S->client['name'])) {

                    echo ' | <a href="?v=basket_import" >Import Basket</a> | ' . "\n";
                }

                if ($S->checkPrivileges('sales_analyser')) {

                    echo ' | <a target="_blank" href="/catalog/sales_analyser.php" >Sales Analyser</a> | ' . "\n";
                }



                if ($S->checkPrivileges('runsheet')) {

                    echo '<a href=?v=runsheet >Runsheet</a>&nbsp;|&nbsp';
                }


                echo '  <a href=?v=list_all_baskets >List my baskets</a>&nbsp;|&nbsp; <a href="?e=changeClient" >Select client</a>';


                if ($S->checkPrivileges('officeadmin')) {

                    //echo ' | <a href="?v=list_clients_orders" target="list_orders" >List orders</a>&nbsp;';

                    echo ' | <a href="' . fido_gateway_link("home") . '" target="fido">List orders</a>&nbsp;';
                }



                if ($S->checkPrivileges('stockadjust')) {

                    echo ' | <a target="_blank" href="admin/?m=product&a=stockadjust" >Stock Adjust</a>' . "\n";

                    echo ' | <a target="_blank" href="admin/?m=stock&a=lowstock" >Low Stock</a>' . "\n";
                }

                if (isset($S->client['client_id']) && $S->client['client_id'] > 0 && $S->checkPrivileges('officeadmin')) {

                    echo '|&nbsp;<a href="' . fido_gateway_link("client") . '" target="fido">Special prices</a>&nbsp;';

                    echo '|&nbsp;<a href="?v=basket_report"> Basket report</a>&nbsp;';
                }

                if ($S->recordmileage) { // does this k9user have to record mileage?

                    echo ' | <a href="?v=record_end_mileage" >End Odometer</a>' . "\n";
                }

                echo '</div>' . "\n";

                echo '<div id="clientinfomenu">' . "\n";

                //echo dumper($S->client) ;

                //echo dumper(get_freight_code($S->client['postcode']));

                if ($S->getClientId() > 0) {

                    // client is selected

                    //http://www2.k9homes.com.au/office/client/edit/1372

                    if ($S->checkPrivileges('editclientprofile')) { // allow some to edit client profile

                        //echo '<div style="border:0px solid red; overflow: hidden;"><h3> 

                        //<a target="_blank" href="../office/client/edit/'.$S->getClientId().'" >'.$S->client['name'].'</a></h3>'."\n";

                        echo '<div style="border:0px solid red; overflow: hidden;">';

                        echo '<h3><a target="fido" href="' . fido_gateway_link("client/" . $S->getClientId() . "/edit") . '" >' . $S->client['name'];

                        if ($S->client['parent'] > 0) {
                            echo '<br><span style="background:red; color:white"> ' . $S->client['parent_record']['name'] . ' </span>';
                        }

                        echo '</a></h3>' . "\n";
                    } else {

                        echo  '<div style="border:0px solid red; overflow: hidden;"><h3>' . $S->client['name'] . '</h3>' . "\n";
                    }


                    $phone = !empty($S->client['phone']) ? 'ph:' . $S->client['phone'] : '';

                    $phone .= !empty($S->client['mobile']) ? ' mob: ' . $S->client['mobile'] : '';


                    // add a flag to indicate if client is registered online - FAILED BECAUSE online status seems to be set to active even when no login_user or passwd

                    //$online_flag = $S->client['online_status'] =='active' ? '*':'';

                    $online_flag = !empty($S->client['login_user']) && !empty($S->client['login_pass']) ? '*' : '';

                    echo '<p>' . $online_flag . 'qrc: ' . generate_quickreg_code($S->client['myob_record_id']) . '</p>';

                    // client address, email and phone

                    echo '<p> email: ' . $S->client['login_user'] . '&nbsp;' . $phone . '</p>'; // login_user is email

                    $address = !empty($S->client['address1']) ? $S->client['address1'] : '';

                    $address .= !empty($S->client['address2']) ? ', ' . $S->client['address2'] : '';

                    $address .= !empty($S->client['address3']) ? ', ' . $S->client['address3'] : '';

                    $address .= !empty($S->client['city']) ? ', ' . $S->client['city'] : '';

                    $address .= !empty($S->client['postcode']) ? ', ' . $S->client['postcode'] : '';

                    echo '<p> ' . $address . '</p>';

                    echo !empty($S->client['contacts_3']) ? '<p style="float: none">Ordering Contact:' . $S->client['contacts_3'] . '</p>' : '';

                    //echo '<p>'.$phone.'</p>';

                    echo '</div>';


                    echo '<p>' . display_freight_code($S->client['postcode'], $S->client['custom_freight']) . '</p>';

                    echo  '<p><a href="../office/client/orderhistory/' .  $S->getClientId() . '" target="_self" >Order history</a></p>' . "\n";

                    echo  '<p><a  href="?e=SelectClient&client_id=' . $S->getClientId()

                        . '">Contact history</a></p>' . "\n";

                    //echo  '<p><a href="../office/client/contact/' .  $S->getClientId() . '" target="_self" >Contact data</a></p>'."\n";

                    echo  '<p><a href="../office/client/stockcount/' .  $S->getClientId() . '" target="_self" >Instore stock</a></p>' . "\n";

                    if ($S->basket_count() > 0) {

                        echo '<p>' . $S->basket_count()

                            . ' items:(' . number_format(basketMargin() * 100, 1) . ') <a href="?v=basket">View client basket</a></p>' . "\n";
                    }

                    if (!empty($S->client['freight_notes'])) {

                        echo '<p style="clear:both;float:none; padding:3px; color: red; overflow:visible;">' . $S->client['freight_notes'] . '</p>';
                    }
                } else {

                    // client not selected

                    //echo '<p><a href="?e=changeClient" >Select client</a></p>'."\n";

                }

                echo '</div>' . "\n";

            } elseif ($S->is_valid_client()) {

                if ($S->getClientId()) { // display client name if one is selected

                    // if it is the client logged in then just display their name

                    if ($S->role == 'client') {

                        echo '<h3>' . $S->client['name'] . '</h3>' . "\n";

                        echo '<p><a href="'.CMS_BASE_URL.'reseller_info">Product Specials</a> &nbsp;';

                        echo '<a href="?v=list_clients_orders">My orders</a></p><br />';

                        if ($S->basket_count() > 0) {

                            echo '<p>' . $S->basket_count()

                                . ' items: <a href="?v=basket">View basket</a></p>' . "\n";
                        }
                    } else {
                    }
                }
            }

            ?>


        <?php elseif ($S->nextview != 'login') : ?>


            <form method="post" action="" name="login">

                <table class="login_form">

                    <tr>

                        <td align="right">

                            Email:

                        </td>


                        <td align="left">

                            <input type=text size=12 name=username style="width:200px;">

                        </td>


                        <td rowspan="2" align="center">

                            <input tabindex="50" type="submit" name="e" value="Login">

                        </td>

                    </tr>



                    <tr>

                        <td align="right">

                            Password:

                        </td>


                        <td align="left">

                            <input type=password name=password size=13 style="width:200px;">

                        </td>

                    </tr>

                </table>

            </form>


        <?php endif; ?>

    </div>
    <!--end right float div-->

</div><!-- end header div-->