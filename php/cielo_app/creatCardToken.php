<?php
header("Access-Control-Allow-Origin: *");

$curl = curl_init();
$post = "{\n   \"Customer\":{\n      \"Name\":\"$_POST[Name]\"\n   },\n   \"CreditCard\":{\n\t    \"CardNumber\":\"$_POST[CardNumber]\",\n\t    \"Holder\":\"$_POST[Holder]\",\n\t    \"ExpirationDate\":\"$_POST[ExpirationDate]\",\n\t    \"SecurityCode\":\"$_POST[SecurityCode]\",\n\t    \"Brand\":\"$_POST[Brand]\"\n\t}\n}";
//die($post);
$url = "https://igestaoweb.com.br/pinkmajesty/app_new/php/cielo/pink-majesty-integracao-cielo/public/api/createTokenCard";
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $post,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$http_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
if ($http_code != 200){
  $err = "\n\nHTTP CODE: ".$http_code."\n\n\n".$url."\n\n\n\n".$post;
}
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>