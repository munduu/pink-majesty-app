<?php
header('Access-Control-Allow-Origin: *');

$email = 'atendimento@mundoinova.com.br';
$token = '86E90B2ED32B4C0692C2CAD4129A2293';
$urlb  = 'https://ws.pagseguro.uol.com.br/v2/sessions';

$url   = $urlb.'?email='.$email.'&token='.$token;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/xml; charset=ISO-8859-1'));
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
$xml= curl_exec($curl);

if($xml == 'Unauthorized'){
    $erro = 'Erro acesso';
    exit;
}

curl_close($curl);

$xml= simplexml_load_string($xml);

if(count($xml -> error) > 0){
    $erro = 'Erro acesso';
	exit;
}else{
	$id_session = $xml->id;
}

echo $id_session;
?>