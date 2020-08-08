<?php

$sessionId = 'k9bravo';

$lifetime = 33200; //7200 = 2hr 14400:4hr  28800:8hr 

$maxCookieLifetimeInBrowser = 33200; // 12 hours



session_name($sessionId . "_" . $lifetime); // on production server

ini_set('session.gc_maxlifetime', $lifetime); //session.gc_maxlifetime

ini_set('session.cookie_lifetime', $maxCookieLifetimeInBrowser);

ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.

$time = $_SERVER['REQUEST_TIME'];

if (APP_MODE == 'production') {
    session_save_path('/home/k9homes/tmp/php/session');


    session_set_cookie_params($lifetime, '/', '.k9homes.com.au');
}



//echo phpinfo(); exit;


session_start();

/**
    
 * Here we look for the user’s LAST_ACTIVITY timestamp. If
    
 * it’s set and indicates our $timeout_duration has passed,
    
 * blow away any previous $_SESSION data and start a new one.
    
 */

if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $lifetime) {

    //echo "hello there"; exit;

    session_unset();

    session_destroy();

    session_start();
}

setcookie(session_name(), session_id(), time() + $lifetime, '/', '.k9homes.com.au');

$_SESSION['LAST_ACTIVITY'] = $time;
