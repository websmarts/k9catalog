<?php



// list.php

// get browse categories



if ($S->checkPrivileges('updateparts')) {

    // show pending products as well as active

    $status = ' `products`.`status` != "inactive" ';
} else {

    //only show active products

    $status = ' `products`.`status` = "active" ';
}



//echo dumper($req);

$_catId = isset($req['catid']) ? (int) $req['catid'] : false;
$_q = isset($req['q']) ? $req['q'] : '';



if (0 && $S->id > 0 && empty($_catid) && empty($_q)) {

    // show client notes if k9 person and no cat or search selected

    //NOTE THIS FUNCTIONALITY PUT INTO modules/contact.php

    $sql = 'select ch.call_type,DATE_FORMAT(ch.call_datetime,"%d %b %Y") as calldate, ch.note, c.contacts

            from contact_history as ch

            join clients as c on ch.client_id=c.client_id

            where ch.client_id=' . $req['client_id'] .

        ' and ch.note >""

            order by call_datetime desc limit 10 ';

    $clientnotes = do_query($sql);
} elseif ($_catId) {

    // were browsing a category



    // get group options

    $sql =

        "select * from type,type_options where type.typeid = type_options.typeid order by type.display_order desc, type_options.opt_code asc"; // select every type available in this category

    $r = do_query($sql);



    //echo dumper($r);

    foreach ($r as $option) {

        $options[$option['typeid']][] = $option;
    }

    //echo dumper($options);

    //exit;



    // get options for each type

    $sql =

        "SELECT  DISTINCT

        products.*,

        type.*

        FROM `products`

        JOIN `type` ON `products`.typeid=`type`.typeid

        JOIN `type_category` ON `type_category`.typeid = `products`.typeid

        WHERE

        1

        AND " . $status . " ";

    //AND products.clearance < 1 ";



    if ($_catId > 0) {

        // add category filter

        $sql .= " and `type`.typeid=type_category.typeid and type_category.catid=" . $_catId . " ";
    }

    //$sql .= " order by LEFT(type.name,8) asc, products.product_code asc";

    $sql .= " order by `type`.display_order desc, products.display_order desc, products.product_code asc";



    // echo $sql . "<br>\n";

    $g = do_query($sql);



    if (is_array($g) && count($g) > 0) {

        foreach ($g as $gg) {

            $group_products[$gg['typeid']][] = $gg;

            // capture actual  products in database - this may be less that all possible products X their options!

            $stocked_products[$gg['product_code']] = $gg; //['product_code'];

        }
    } else {

        // Category has no products to list

        // get first child category

        $sql = "select id from category where parent_id=" . $_catId . ' order by display_order desc, name asc limit 1';

        $g = do_query($sql);

        if ($g[0]['id'] > 0) {

            // redirect to first child category

            header('Location: ?v=list_products&catid=' . $g[0]['id']);

            exit;
        } else {

            // no products to list and no sub categories so just soldier on and see what happen!

        }
    }



    // Create an array of special prices for this client



    $special_prices = get_client_price_specials($S->getClientId());

    //echo "CLIENT_PRICES:" .dumper($special_prices);



    //echo dumper($group_products);

    //echo dumper($stocked_products);

    //exit;



}
