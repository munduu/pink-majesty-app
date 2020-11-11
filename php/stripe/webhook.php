<?php
require_once('../Connections/localhost.php');
require_once('../function/log.php');
require_once('../function/function.php');
require_once('config.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
//error_reporting(E_ALL ^ E_NOTICE);

$endpoint_secret = 'whsec_anh594dRZVTC2dIOhydwHtyR9IKvBJYs';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$json = json_decode($payload);
salva_log('webhook',$json);
$log = json_encode($json,JSON_PRETTY_PRINT);
echo $json->object;
echo "\n";
if(!empty($json->object)){
    if($json->object == 'event'){
        echo $type = $json->type;
        echo "\n";
        echo $type2 = substr($type,0,14);
        echo "\n";
        if ($type2 == "payment_intent") {
            $charge = $json->data->object->charges->data[0];
            $payment_intent = $json->data->object->id;
            $amount = $charge->amount;
            $customer = $charge->customer;
            $payment_method = $charge->payment_method;
            $type = $status = ($charge->paid == true) ? 'paid' : $charge->status;
            http_response_code(200);
        } else {
            $object = $json->data->object;
            $customer = $object->customer;
            $amount = $object->amount;
            $status = $object->status;
            http_response_code(200);
        }
        $amount = $amount/100.0;
    } else {
        echo "Not Event";
        echo "\n";
        http_response_code(400);
    }
} else {
    echo "Empty";
    echo "\n";
    http_response_code(400);
}

$sql = "SELECT * FROM tb_login WHERE gateway_id = '$customer' ORDER BY id";
    $resultado 	= mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($resultado) > 0){
        while($ln = mysql_fetch_assoc($resultado)){
            $id_cliente = $ln['id_cliente'];
        }
    }
    
$sql = "INSERT INTO `tb_webhook`(`cliente_id`, `customer`,`payment_intent`, `payment_method`,`valor`, `status`, `log`) VALUES ('$id_cliente','$customer','$payment_intent', '$payment_method','$amount', '$type','$log')";
        $resultado 	= mysql_query($sql) or die(mysql_error());
        http_response_code(200);

/*try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
    if ($event->type == "charge.succeeded") {
        $charge = $event->data->object;
        $amount = $charge->amount;
        $customer = $charge->customer;
        $status = ($charge->paid)? 'paid':$charge->status;
        http_response_code(200);
        exit();
    } else {
        $charge = $event->data->object;
        $amount = $charge->amount;
        http_response_code(200);
    }
    echo $sql = "INSERT INTO `tb_webhook`(`valor`, `status`, `log`) VALUES ('$amount', '$status','$log')";
    $resultado 	= mysql_query($sql) or die(mysql_error());
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}
?>
