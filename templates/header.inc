<?php $title = 'K9Homes eCatalog Pet Products ';

$_catId = $req['catid'] ?? null;

if ($_catId) {

    //echo get_category_name($req['catid']);

    //echo dumper($categoryData);

    if ($categoryData[$req['catid']]['parent_id'] == 0) {

        $title .= $categoryData[$req['catid']]['name'] . " ";
    } else {
        $title .= $categoryData[$categoryData[$req['catid']]['parent_id']]['name'] . " " . $categoryData[$req['catid']]['name'];
    }
}
?>





    <title><?php echo $title; ?></title>



    <link rel="shortcut icon" href="/catalog/favicon.ico" type="image/x-icon" />

    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png" />

    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />

    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png" />

    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />

    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png" />

    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png" />

    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png" />



    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <meta name="description" content="K9Homes manufacture and distribute a wide range of high quality pet products" />



    <link rel="stylesheet" type="text/css" media="screen" href="site.css?b=refresh20200816" />

    <link rel="stylesheet" type="text/css" media="print" href="print.css?a=refresh20200816" />



    <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.18.custom.css" rel="Stylesheet" />

    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>

    <script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>

    <!--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->



    <?php if ($S->isInternalUser()) : ?>

        <!--<script src="http://code.google.com/apis/gears/gears_init.js" type="text/javascript"></script>-->

        <!--<script src="js/geo.js" type="text/javascript" ></script>-->



        <script src="js/typeahead.js/typeahead.jquery.min.js" type="text/javascript"></script>

    <?php endif; ?>



    <script type="text/javascript">
        function set_typeid(typeid) {

            document.forms['orderform'].elements["typeid"].value = 'type_' + typeid;

            return true;

        }
    </script>

