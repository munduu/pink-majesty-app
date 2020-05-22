<?php
include "vendor/autoload.php";
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
ob_start();
//GLOBAL API KEY
$stripe = \Stripe\Stripe::setApiKey("sk_test_2AItDw6QvoZr2UWxAlntETu500I2Mdm19B");

?>
