<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
include "vendor/autoload.php";
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
ob_start();
//GLOBAL API KEY
$stripe = \Stripe\Stripe::setApiKey("sk_test_2AItDw6QvoZr2UWxAlntETu500I2Mdm19B");
//$stripe = \Stripe\Stripe::setApiKey("rk_test_98I5zg9voEEYXMN75Uh18qNG003DQGrIkR");
function salva_log($path,$log){
    if($path == 'sqlError'){
        $backtrace = debug_backtrace();
        $response['sqlError'] = $log;
        $response['backtrace'] = $backtrace;
        $log = $response;
    }
    $log = json_encode($log,JSON_PRETTY_PRINT);
        if (!file_exists('./log/'.$path)) {
            mkdir('./log/'.$path, 0777, true);
        }
        $path = './log/'.$path.'/'.date("YmdHis");
        $x = 0;
        while (file_exists($path)) {
            $path = $path."-$x";
            $x ++;
        } 
    $fp = fopen($path, "x");
    $escreve = fwrite($fp, $log);
    return $log;
} 
?>
