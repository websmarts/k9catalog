<div id="leftbar">

<?php

$_m = $req['m'] ?? null;

$_q = $req['q'] ?? null;
if ($_m != "order") : ?>

    <form id="searchform" name="search_form">

        find:

        <?php if ($_q == 'core' || $_q == 'special' || $_q == 'new product' || $_q == 'clearance') {

            $q = '';
        } else {

            $q = $_q;
        }

        ?>



        <input type="text " size="10" name="q" value="<?php echo $q ?>">

        <input type="submit" name="b" value="go">

        <input type="hidden" name="v" value="product_search">

    </form>









    <div id="catmenu">

        <ul class="alt">



            <?php if ($S->isInternalUser()) : ?>

                <li><a href="?q=core&b=go&v=product_search">Core</a></li>

            <?php endif; ?>



            <li><a href="?q=new%20product&b=go&v=product_search">New products</a></li>

            <li><a href="?q=clearance&b=go&v=product_search">Clearance Items</a></li>

            <li><a href="?q=special&b=go&v=product_search">Specials</a></li>

        </ul>

        <?php do_menu_row($categories, 0); ?>

    </div>



    <?php if ($S->isInternalUser()) : ?>

        <p><a href="?v=list_all_products">Show All</a></p>

        <p><a href="?v=list_products&amp;catid=48">Show Hidden</a></p>

    <?php endif; ?>



<?php endif; ?>

</div><!-- end cat menu sidebar div -->