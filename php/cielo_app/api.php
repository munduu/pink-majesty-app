<?php

// define("API_Cielo_URL", 'https://apisandbox.cieloecommerce.cielo.com.br');
// define("API_Cielo_MerchantId", '2ba8079d-ca3a-49a4-8141-3fb3a2b384d2');
// define("API_Cielo_MerchantKey", 'LVJVSVKUFEXFKSGEEZCNONUAVXKXEMCFYPMGJWHG');

define("API_Cielo_URL", 'https://apisandbox.cieloecommerce.cielo.com.br');
define("API_Cielo_MerchantId", '2ba8079d-ca3a-49a4-8141-3fb3a2b384d2');
define("API_Cielo_MerchantKey", 'LVJVSVKUFEXFKSGEEZCNONUAVXKXEMCFYPMGJWHG');
/*
    action:'checkout',
    MerchantOrderId: agenda,
    Name: lNmcartao,
    CardNumber: lNcartao,
    Holder: lNmcartao,
    ExpirationDate: lMesVenc+"/"+lAnoVenc,
    SecurityCode: lCodigoSeg,
    Brand: brand
*/
header("Access-Control-Allow-Origin: *");

if($_REQUEST['action'] == 'chargeWithCard'){
    chargeWithCard($_REQUEST);
} elseif($_REQUEST['action'] == 'chargeWithCard2'){

} else {

}

function chargeWithCard($request = null)
{
    $curl = curl_init();
    
    $post                                            = array();
    $post['MerchantOrderId']                         = $request['MerchantOrderId'];
    $post['Customer']                                = array();
    $post['Customer']['Name']                        = $request['Name'];
    $post['Payment']                                 = array();
    $post['Payment']['Type']                         = 'CreditCard';
    $post['Payment']['Capture']                      = true;
    $post['Payment']['Amount']                       = 200;
    $post['Payment']['Installments']                 = 1;
    $post['Payment']['SoftDescriptor']               = 'PINK_MAJESTY';
    $post['Payment']['CreditCard']                   = array();
    $post['Payment']['CreditCard']['CardNumber']     = $request['CardNumber'];
    $post['Payment']['CreditCard']['Holder']         = $request['Holder'];
    $post['Payment']['CreditCard']['ExpirationDate'] = $request['ExpirationDate'];
    $post['Payment']['CreditCard']['SecurityCode']   = $request['SecurityCode'];
    $post['Payment']['CreditCard']['Brand']          = $request['Brand'];
    curl_setopt_array($curl, array(
        CURLOPT_URL => API_Cielo_URL.'/1/sales',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($post,JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => array(
            'MerchantId: '.API_Cielo_MerchantId,
            'Content-Type: application/json',
            'MerchantKey: '.API_Cielo_MerchantKey
        ),
    ));
    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
}
