<?php

    if (isset($_SESSION['messages'])) {

        $error_msg .= isset($_SESSION['messages']['error']) ? $_SESSION['messages']['error'] : null;

        $flash_msg .= isset($_SESSION['messages']['success']) ? $_SESSION['messages']['success'] : null;

        if (!empty($error_msg)) {

            echo '<div class="error_msg">' . $error_msg . '</div>' . "\n";
        }

        if (!empty($flash_msg)) {

            echo '<div class="flash_msg">' . $flash_msg . '</div>' . "\n";
        }
    }
