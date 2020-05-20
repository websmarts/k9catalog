<?php



// search_products.php

// get browse categories



if ($S->checkPrivileges('updateparts')) {

    // show pending products as well as active

    $status = ' `products`.`status` != "inactive" ';

} else {

    //only show active products

    $status = ' `products`.`status` = "active" ';

}



//DEBUGING echo dumper($req);



// get product options

$sql = "select * from `type`,type_options where type.typeid = type_options.typeid order by type.display_order desc, type_options.opt_code asc"; // select every type available in this category

$r = do_query($sql);

//echo dumper($r);

foreach ($r as $option) {

    $options[$option['typeid']][] = $option;

}



if ($req['q'] == 'core') {

    $sql = "select * from products,`type` where products.typeid=type.typeid" .

        "     and  products.core_product > 0 and " . $status . " order by product_code asc";

    //$req['q']='';

} elseif ($req['q'] == 'new product') {

    $sql = "select * from products,`type` where products.typeid=type.typeid" .

        "     and  products.new_product > 0 and " . $status . " order by product_code asc";

    //$req['q']='';

} elseif ($req['q'] == 'special') {

    $sql = "select * from products,`type` where products.typeid=type.typeid" .

        "     and  products.special > 0 and " . $status . " order by product_code asc";

    //$req['q']='';

} elseif ($req['q'] == 'clearance') {

    $sql = "select 
    
            category.id as catid,

            products.id,

            products.product_code,

            products.description,

            products.size,

            products.cost,

            products.barcode,

            products.color_name,

            products.color_background_color,

            products.typeid,

            products.qty_instock,

            products.can_backorder,

            products.price,

            products.status,

            type.name,

            type.type_description,

            type.display_format,

            type.aus_made

            from products

            join `type` on type.typeid = products.typeid

            join type_category on type_category.typeid = type.typeid

            join category on type_category.catid=category.id

            where products.typeid=type.typeid

            and  products.clearance > 0 and " . $status . "

            order by category.display_order desc,

            type.display_order desc,

            products.display_order desc,

            products.product_code asc";

    //$req['q']='';

} elseif ($req['q'] == 'all') {

    $sql = "   select



                category.id as catid,

                products.id,

                products.product_code,

                products.description,

                products.size,

                products.cost,

                products.barcode,

                products.color_name,

                products.color_background_color,

                products.typeid,

                products.qty_instock,

                products.can_backorder,

                products.price,

                products.status,

                type.name,

                type.type_description,

                type.display_format,

                type.aus_made

                from products

                join `type` on type.typeid = products.typeid

                join type_category on type_category.typeid = type.typeid

                join category on type_category.catid=category.id

                where " . $status . " and clearance < 1

                order by category.display_order desc, type.display_order desc, products.display_order desc, price asc ";



} else {

    $sql = "select products.* from products,`type`

        where products.typeid=type.typeid

        and  (product_code like '" . $req['q'] . "%'

        or description like '%" . $req['q'] . "%'

        or name like '%" . $req['q'] . "%')

        and " . $status . "

        order by type.display_order desc,

        products.display_order desc,

        products.product_code asc";

}



//echo $sql;

// $db->debug = true;

$g = $db->getArray($sql);

// echo dumper($g);

// $db->debug = false;



if (is_array($g)) {

    foreach ($g as $gg) {

        $group_products[$gg['typeid']][] = $gg;

        // capture actual  products in database - this may be less that all possible products X their options!

        $stocked_products[$gg['product_code']] = $gg; //['product_code'];

    }

}

