<?php
error_reporting(E_WARNING);
session_start();

$count = $_SESSION['count'];
$count++;
$_SESSION['count']=$count;



$url ="index.php?c=$count";

$redirect = "_a=login";

include('views/redirect.php');
exit;
?>	