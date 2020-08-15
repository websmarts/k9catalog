<?php

function generate_quickreg_code($id, $prefix = 'A')
{

    if ($id > 0) {

        $x = (string) $id;

        $total = ord($prefix);

        for ($i = 0; $i < strlen($x); $i++)

            $total += (int)$x[$i];



        return $prefix . $id . $total;
    }
}

function do_menu_row($cats, $depth, $depthLimit = 1, $current_catid = 0)

{

    global $S;



    if ($depth >= $depthLimit) {

        return;
    }



    //echo dumper($cats);

    if (is_array($cats)) {

        echo "\n" . '<ul class="depth_' . $depth . '">';

        foreach ($cats as $cat) {

            $spacer = str_repeat("-", ($depth * 1));

            if ($current_catid == $cat[0]['id']) {

                $class = ' class="active" ';
            } else {

                $class = '';
            }

            echo  "<li><a " . $class . " href=\"?v=list_products&amp;catid=" . $cat[0]['id'] . "\">" . $cat[0]['name'] . "</a>";

            //echo dumper($cat);

            if (is_array($cat[1]) and count($cat[1]) > 0) {

                $depth++;

                do_menu_row($cat[1], $depth, $depthLimit, $current_catid);

                $depth--;
            }

            echo '</li>' . "\n";
        }

        echo '</ul>' . "\n";
    }
}