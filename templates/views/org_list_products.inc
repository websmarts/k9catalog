<?php
    function product_list_vertical($products)
    {
        global $special_prices, $S, $typeid, $client, $req, $client_notifies;

        //echo dumper($special_prices);

        echo '<table class="list_table" >';

        foreach ($products as $p)
        {
            // check if qty instock is zero and if backordering is allowed if it is
            //echo dumper($p);

            // dont show product if status =pending and user does not have stock_adjust privelege 
            if($p['status'] == 'pending' && !$S->checkPrivileges('updateparts')){
                continue;
            }

            if ( (1) > 0 || $p['can_backorder'] != 'n' || $S->checkPrivileges('updateparts'))
            {
                $n++; // increment for every product we display
                $highlight='';

                if ($p['product_code'] == $req['highlight'])
                {
                    $highlight=' highlight';
                }

                echo "<tr class=\"item\" >";

                // indicate if product code is pending status 
                $pendingClass = $p['status']=='pending' ? 'red' : '';
                echo '<td class="productcode '.$pendingClass.'" > '. $p['product_code'] .$pending . '</td>';

                // format description and size fields
                if(empty($p['description']) && !empty($p['size'])) {
                    $description = $p['size'];
                } elseif  (!empty($p['description']) && empty($p['size'])) {
                    $description = $p['description'];
                }  elseif (!empty($p['description']) && !empty($p['size'])){
                    $description =   $p['description'] .'<br />'   . $p['size']  ;
                } else {
                    $description = '';
                }
                // add in color name if not empty
                if(!empty($p['color_name'])){
                    $description .= ' - '.$p['color_name'];
                }

                echo "<td class=\"description\" >" . stripslashes($description) . "</td>";

                // check if special price exists
                if (isSet($special_prices[$p['product_code']]))
                {
                    $specialPrice=true;
                    echo "<td class=\"price\" >";
                    if($p['price'] > $special_prices[$p['product_code']]){
                        echo "$" . number_format($p['price'] / 100, 2);
                    }
                    echo "<span class=\"special_price\" ><br /> Your special price: $" . number_format($special_prices[$p['product_code']] / 100, 2).'</span>';
                }
                else
                {
                    $specialPrice = false;
                    echo "<td class=\"price\" >$" . number_format($p['price'] / 100, 2);
                    ////////////////echo "<br />your price $18.00";
                }

                if (!$specialPrice && $p['qty_break'] > 0)
                {
                    echo "<span class=\"qty_disc_price\"> (" . $p['qty_break'] . "+ less " . $p['qty_discount']
                    . "% )</span>";
                }

                echo "</td>";

                // check if product is BOM and max boms available
                $bom = isBomAndAvailable($p['product_code']);
                if(is_array($bom) ){ // its a bom
                    $p['qty_instock']  = $bom['max_available'] ;
                }

                // display the qty input box if we can accept orders for product
                if ($p['qty_instock'] > 0 or $p['can_backorder'] == 'y')
                {

                    echo "<td class=\"input \" ><input type=\"number\" class=\"input $highlight\" name=\"basket["
                    . $p['product_code'] . "][qty]\" value=" . $S->basket[$p['product_code']] . "  ></td>";
                }
                else
                {
                    if($p['notify_when_instock'] && !isSet($client_notifies[$p['product_code']])){
                        echo '<td class="input"><a title="notify me when stock for '.$p['product_code'].' becomes available" href="?e=nis&pcode='.urlencode($p['product_code']).'"><img src="images/notify_when_stock.jpg" /></a></td>';
                    } else {

                        if(isSet($client_notifies[$p['product_code']]) ){
                            echo "<td class=\"input\"><img src=\"images/notify_when_set.jpg\" /></td>"; 
                        }else{
                            echo "<td class=\"input\"> * </td>"; 
                        }
                    }

                }

                // if product can be backordered - DONT show QTY in stock
                // elseif product cannot be backordered show the qty_in_stock + 1000
                if ($p['can_backorder'] == 'y')
                {
                    if ($S->checkPrivileges('updateparts'))
                    {
                        echo '<td><a href="admin/?a=b&product_code=' . htmlentities(urlencode($p['product_code'])) . '&m=product&a=edit" target="admin">'
                        . (STOCK_QTY_OFFSET + ($p['qty_instock'])) . '</a></td></tr>' . "\n";
                    }
                    elseif ($S->isInternalUser())
                    {
                        echo '<td>' . (STOCK_QTY_OFFSET + ($p['qty_instock'])) . '</td></tr>' . "\n";
                    }
                    else 
                    {
                        echo '</tr>'."\n";
                    }
                }
                else
                {
                    if ($S->checkPrivileges('updateparts'))
                    {
                        echo '<td><a href="admin/?product_code=' . htmlentities(urlencode($p['product_code'])) . '&m=product&a=edit" target="admin">'
                        . (STOCK_QTY_OFFSET + ($p['qty_instock'])) . '</a></td></tr>' . "\n";
                    }
                    elseif ($S->isInternalUser())
                    {
                        echo '<td>' . (STOCK_QTY_OFFSET + ($p['qty_instock'])) . '</td></tr>' . "\n";
                    }
                    else 
                    {
                        echo '</tr>'."\n";
                    }
                }
            }
        }

        echo "</table>\n";
        return $n; // need to return something >0  else the ADDtoBasket button will be missing
    }

    function product_list_horizontal($products)
    { // displays a product with option displayed horizontally
        global $options, $stocked_products, $special_prices, $S, $req, $client_notifies;
        // echo dumper($special_prices);

        echo "<table  class=list_table >";

        //echo dumper($stocked_products);
        //echo dumper($special_prices);
        //echo dumper($products);

        // Put products into an array for easy notify me checking later
        $notify_me_products = array();
        foreach($products as $p){
            if(!empty($p['notify_when_instock'])){
                // add notify me
                $notify_me_products[$p['product_code']] = 1;
            }
        }

        //echo dumper($notify_me_products);
        //break;
        foreach ($products as $p)
        {
            //echo dumper($p);

            // we need to strip out the base product name i.e  make b210-R = b210 as the options
            // are added below horizontally. We only process the basename once s skip over repeats
            $pos = strrpos($p['product_code'], "-");

            if ($pos === false)
            {                             // note: three equal signs
                $basename=$p['product_code']; //SHOULD THIS BE AN ERROR!!!!
                //continue; // uncommnet to ignore
            }
            else
            {
                $basename=substr($p['product_code'], 0, $pos); //strrpos($p['product_code'],"-")
            }

            if ($done[$basename])
            {
                continue;
            }
            $counter++; // count the number of products listed

            if ($counter == 1)
            {       // the first row!
                // display the option colors at the head of the qty columns
                //echo dumper($options[$p['typeid']]);
                if (is_array($options[$p['typeid']]))
                {
                    echo "<tr><td colspan=3 >";

                    foreach ($options[$p['typeid']] as $opt)
                    {

                        echo "<td  class=\"input " . strtolower($opt['opt_class']) . "\">" . $opt['opt_code']
                        . "&nbsp;</td>";
                    }

                    echo "</tr>\n";
                }
            }

            echo "<tr  class=\"item\">";

            echo "<td class=\"productcode\" >" . $basename . "</td>";

            // format description and size fields
            if(empty($p['description']) && !empty($p['size'])) {
                $description = $p['size'];
            } elseif  (!empty($p['description']) && empty($p['size'])) {
                $description = $p['description'];
            }  elseif (!empty($p['description']) && !empty($p['size'])){
                $description =   $p['description'] .'<br />'   . $p['size']  ;
            } else {
                $description = '';
            }

            echo "<td class=\"description\" >" . stripslashes($description) . "</td>";

            // check if special price exists
            if (1 && isSet($special_prices[$basename . "-" . $opt['opt_code']]))
            {
                $specialPrice=true;
                echo "<td class=\"price\" >";
                if($p['price'] > $special_prices[$basename . "-" . $opt['opt_code']]){
                    echo "$" . number_format($p['price'] / 100, 2);
                }
                echo "<span class=\"special_price\" ><br /> Your special price: $" . number_format($special_prices[$basename . "-" . $opt['opt_code']] / 100, 2).'</span>';
            }
            else
            {
                $specialPrice = false;
                echo "<td class=\"price\" >$" . number_format($p['price'] / 100, 2);
                //////////////// echo "<br /><span style=\"color:red\">your price $18.00</span>";
            }

            if (!$specialPrice && $stocked_products[$basename . "-" . $opt['opt_code']]['qty_break'] > 0)
            {
                echo " <span class=\"discount\">(" . $stocked_products[$basename . "-" . $opt['opt_code']]['qty_break']
                . "+ less " . $stocked_products[$basename . "-" . $opt['opt_code']]['qty_discount'] . "% )</span>";
            }

            echo "</td>";

            if (is_array($options[$p['typeid']]))
            {
                foreach ($options[$p['typeid']] as $opt)
                {
                    //echo dumper($opt);

                    $highlight = '';

                    if ($basename . '-' . $opt['opt_code'] == $req['highlight'])
                    {
                        $highlight=' highlight ';
                    }

                    echo "<td class=\"input\" >";
                    // if this product is stocked (or if product qty is ) and backorders are not allowed )display qty box else dont!
                    $product_code=$basename . "-" . $opt['opt_code'];

                    // check if product is BOM and max boms available     
                    $bom = isBomAndAvailable($product_code);
                    if(is_array($bom) ){ // its a bom
                        $stocked_products[$basename . "-" . $opt['opt_code']]['qty_instock']  = $bom['max_available'] ;
                    }


                    if (isset($stocked_products[$product_code])
                        && ($stocked_products[$basename . "-" . $opt['opt_code']]['qty_instock']
                            > 0 or $stocked_products[$basename . "-" . $opt['opt_code']]['can_backorder'] == 'y'))
                    {


                        // if product has a special client price then make the title of the input box show
                        // the special price and set class to highlight option as having a special client price   
                        if(isSet($special_prices[$basename . "-" . $opt['opt_code']]) ) {
                            $sprice='title="$'.number_format($special_prices[$basename . "-" . $opt['opt_code']] /100, 2).'"  ';
                            $sclass= " sprice ";
                        }  else {
                            $sprice='';
                            $sclass= '';
                        }    


                        if ($S->checkPrivileges('updateparts'))
                        {
                            echo "<input $sprice type=\"number\"  class=\"input $highlight $sclass\" name=\"basket[" . $basename . "-"
                            . $opt['opt_code'] . "][qty]\" value=" . $S->basket[$basename . "-" . $opt['opt_code']]
                            . "  >" . '<br /><a href="admin/?b=c&product_code=' . $product_code
                            . '&m=product&a=edit" target="admin">';

                            echo "<span class=\"instock\">"
                            . ($stocked_products[$basename . "-" . $opt['opt_code']]['qty_instock'] + STOCK_QTY_OFFSET)
                            . "</span></a>";
                        }
                        else
                        {
                            echo "<input $sprice type=\"number\"  class=\"input $highlight $sclass\" name=\"basket[" . $basename . "-"
                            . $opt['opt_code'] . "][qty]\" value=" . $S->basket[$basename . "-" . $opt['opt_code']]
                            . "  >";

                            if($S->isInternalUser()){
                                echo "<br /><span class=\"instock\">"
                                . ($stocked_products[$basename . "-" . $opt['opt_code']]['qty_instock'] + STOCK_QTY_OFFSET)
                                . "</span>";
                            }



                        }

                    }
                    else
                    {
                        // No stock available and cant be backordered
                        //echo dumper($basename . "-" . $opt['opt_code']);
                        //echo dumper($notify_me_products);
                        //echo dumper($client_notifies);
                        $pcode = $basename . "-" . $opt['opt_code'];
                        if($notify_me_products[$pcode] && !isSet($client_notifies[$pcode])){
                            echo '<div class="input"><a title="notify me when stock for '.$pcode.' becomes available" href="?e=nis&pcode='.urlencode($pcode).'"><img src="images/notify_when_stock.jpg" /></a></div>';
                        } else {
                            if(isSet($client_notifies[$pcode])){
                                echo '<div class="input"><img src="images/notify_when_set.jpg" /></div>';
                            } else {
                                echo "<div class=\"input\"> * </div>";
                            }

                        }

                        if ($S->checkPrivileges('updateparts'))
                        {
                            echo '<a href="admin/?product_code=' . htmlentities(urlencode($p['product_code']))
                            . '&m=product&a=edit" target="admin">NS</a>';
                        }
                        else
                        {

                            echo 'NS';
                        }
                    }

                    echo "</td>";
                }
            }
            //echo "<td >&nbsp;</td></tr>\n";
            $done[$basename]=1; // Flag that we have  displayed this basename
        }

        echo "</table>\n";
        return 1; // need to return something else the ADDtoBasket button will be missing
    }

    function client_selector($clients)
    {
        foreach ($clients as $id => $v)
        {
            echo "<option value=$id >" . $v['name'] . "</option>\n";
        }
    }
?>

<!-- MAIN PAGE BELOW -->
<!-- list_products.inc -->

<?php //echo dumper($typeInfoPageIndex); // index of typeid's 'to modx contentids?>
<?php //echo dumper($group_products) ?>
<?php //echo pr($stocked_products);?>

<form method = "post" action = "" name = "basket">
    <input type = hidden name = catid value = "<?php echo $req['catid'];?>">
    <input type = hidden name = q value = "<?php echo $req['q'];?>">
    <table width = "100%" class = "section_header">
        <tr>
            <td class = "subcatmenu">

                <?php
                    if (isSet($req['catid']))
                    {
                        //echo get_category_name($req['catid']);
                        //echo dumper($categoryData);
                        if ($categoryData[$req['catid']]['parent_id'] == 0)
                        {
                            $catid=$req['catid'];
                        }
                        else
                        {
                            $catid=$categoryData[$req['catid']]['parent_id'];
                        }

                        do_menu_row(array($categories[$catid]), 0, 2, $req['catid']);
                    }
                    elseif (isSet($req['q']) )
                    {
                        if($req['q'] == 'core'){
                            echo "<p>Core Items</p>";    
                        } elseif($req['q'] == 'new product'){
                            echo "<p>New Products</p>";     
                        } elseif($req['q'] == 'clearance'){
                            echo "<p>Clearance Items</p>";     
                        } elseif($req['q'] == 'special'){
                            echo "<p>Product Specials</p>";    
                        } else {
                            echo "<p>Search results for '" . $req['q'] . "'</p>";   
                        } 

                    }
                ?>
            </td>
        </tr>
    </table>

    <?php
        // Check for page insert - hack for putting in Darens XMAS coming soon page
        $insert='category_info/' . $req['catid'] . '/insert.php';

        if ($req['catid'] && file_exists($insert))
        {
            include($insert);
        }
    ?>

    <input type="hidden" name="catid" value="<?php echo $req['catid'];?>">
    <table width="100%">
        <tr>
        <td>
        <?php
            if (is_array($group_products))
            {
                foreach ($group_products as $typeid => $products)
                {
                    // Display the group image

                    echo
                    '<table class="product_group_table" ><tr><td class="imgcell" valign=top width=150 >';

                    //echo "<p>".$products[0]['name']."</p>\n";
                    if (file_exists("source/tn_" . strtolower($products[0]['typeid']) . '.jpg'))
                    {
                        echo "<a href=\"source/" . strtolower($products[0]['typeid'])
                        . ".jpg\" target=_blank ><img src=\"source/tn_"
                        . strtolower($products[0]['typeid']) . ".jpg\" border=0  title=\""
                        . $products[0]['typeid'] . "\" ></a>\n";
                        // could also look for asociated images and display
                        $n=2;

                        while ($n < 60)
                        {
                            if (file_exists("source/tn_" . strtolower($products[0]['typeid']) . "_" . $n
                                . '.jpg'))
                            {
                                echo "<a href=\"source/" . strtolower($products[0]['typeid']) . "_" . $n
                                . ".jpg\" target=_blank ><img src=\"source/tn_"
                                . strtolower($products[0]['typeid']) . "_" . $n
                                . ".jpg\" border=0  title=\"" . $products[0]['typeid'] . "_"
                                . $n . "\" ></a>\n";
                            }
                            else
                            {
                                break; // gap in seq so end early
                            }
                            $n++;
                        }
                    }
                    else
                    {
                        echo "<center><span class=\"no_image_available_text\">" . $products[0]['typeid']
                        . " no image available</span></center>";
                    }

                    // check for modx_page for this typeid
                    if(isSet($typeInfoPageIndex[$typeid])){
                        echo '<a class="moreinfo" href="/cms/product-info?pid='.$typeInfoPageIndex[$typeid].'&ref='.base64_encode($_SERVER['QUERY_STRING']).'">more info</a>';
                    }

                    echo "</td>";

                    echo "<td valign=top> \n";
                    //echo dumper($products[0]);
                    $aus_made_image = $products[0]['aus_made'] > 0 ? ' <img style="float: left;" src="images/ausmade.jpg" />' : '';
                    echo "<p class=product_name ><a name=\"type_".$products[0]['typeid']."\" >";
                    echo "" . $products[0]['name'] . "</a>".$aus_made_image."</p>\n";


                    if(!empty($products[0]['type_description'])){
                        echo '<p class="type_description">'.nl2br(stripslashes($products[0]['type_description'])).'</p>';
                    }
                    $inTable=1;

                    if ($products[0]['display_format'] == 'h')
                    {
                        $n=product_list_horizontal($products);
                    }
                    else
                    {
                        $n=product_list_vertical($products);
                    }

                    if ($n > 0)
                    {
                        echo
                        '<span class="basketupdatebutton"><input type=submit name="e" value="Update" ></span>';
                    }

                    echo '</td></tr></table>' . "\n";
                }
            }
        ?>
    </table>

    </td>

    </tr>

    </table>
</form>
