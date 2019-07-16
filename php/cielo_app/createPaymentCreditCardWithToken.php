<?php
header("Access-Control-Allow-Origin: *");

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://igestaoweb.com.br/pinkmajesty/app_new/php/cielo/pink-majesty-integracao-cielo/public/api/createPaymentCreditCardWithToken",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{  \r\n   \"MerchantOrderId\":\"$_POST[MerchantOrderId]\",\r\n   \"Customer\":{  \r\n      \"Name\":\"$_POST[Name]\"\r\n   },\r\n   \"Payment\":{\r\n     \"Amount\":$_POST[Amount],\r\n     \"CreditCard\":{  \r\n         \"CardToken\":\"$_POST[CardToken]\",\r\n         \"SecurityCode\":\"$_POST[SecurityCode]\",\r\n         \"Brand\":\"$_POST[Brand]\"\r\n     }\r\n   }\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>