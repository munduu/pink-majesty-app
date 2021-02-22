<?php
require_once('../Connections/localhost.php');
function salva_log($path,$log){
    if($path == 'sqlError'){
        $backtrace = debug_backtrace();
        $response['sqlError'] = $log;
        $response['backtrace'] = $backtrace;
        $log = $response;
    }
    $log = json_encode($log,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
        if (!file_exists(__DIR__.'/log/'.$path)) {
            mkdir(__DIR__.'/log/'.$path, 0777, true);
        }
        $path = __DIR__.'/log/'.$path.'/'.date("Y m d H i s");
        $x = 0;
        while (file_exists($path)) {
            $path = $path."-$x";
            $x ++;
        } 
    $fp = fopen($path.".json", "x");
    $escreve = fwrite($fp, $log);
    return $log;
}

function sendMessage($title,$text,$id_cliente){
    $sqlc2 = "SELECT * FROM `tb_login` WHERE `token_id` IS NOT NULL AND `id_cliente`='$id_cliente' ORDER BY `id` ASC";
    $qrc2 = mysql_query($sqlc2) or die(mysql_error());
    if (mysql_num_rows($qrc2) > 0){
        while($ln2 = mysql_fetch_assoc($qrc2)){
            $token_id = $ln2['token_id'];
        }
    }
    if(!empty($token_id)){
        $content = array(
            "en" => $text,
            );
            
        $headings = array(
            "en" => $title,
            );
        
        $fields = array(
            'app_id' => "348352f1-636d-4bd7-8cd9-52c82c01c93e",
            //'included_segments' => array('All'),
            'include_player_ids' => array($token_id),
            'data' => array("foo" => "bar"),
            'big_picture' => "https://igestaoweb.com.br/pinkmajesty/app_new/php/images/logodellas2.png",
            'buttons' => array(array("id" => "id1", "text" => "Pedidos")),
            'contents' => $content,
            'headings' => $headings
        );
        
        $fields = json_encode($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                'Authorization: Basic YzA2ZGY0ZTQtMzNjYS00MzliLTkzNTAtN2U5NjQ0YzEyYzc0'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
    }
} 

define("API_Cielo_URL", 'https://api.cieloecommerce.cielo.com.br');
define("API_Cielo_MerchantId", 'c7944c4f-141e-45f9-b745-f12c92972dfd');
define("API_Cielo_MerchantKey", 'd8Msnfs4ueoHbOurODmkhgGPAchVvuQijgf7ePTM');

// define("API_Cielo_URL", 'https://apisandbox.cieloecommerce.cielo.com.br');
// define("API_Cielo_MerchantId", '2ba8079d-ca3a-49a4-8141-3fb3a2b384d2');
// define("API_Cielo_MerchantKey", 'LVJVSVKUFEXFKSGEEZCNONUAVXKXEMCFYPMGJWHG');
/*
    action:'checkout',
    MerchantOrderId: agenda,
    Name: lNmcartao,
    CardNumber: lNcartao,
    Holder: lNmcartao,
    ExpirationDate: lMesVenc+"/"+lAnoVenc,
    SecurityCode: lCodigoSeg,
    Brand: brand,
    Amount: Amount,
*/
header("Access-Control-Allow-Origin: *");

if($_REQUEST['action'] == 'chargeWithCard'){
    chargeWithCard($_REQUEST);
} elseif($_REQUEST['action'] == 'cancelar'){
    cancelarPagamento($_REQUEST);
} else {

}

function chargeWithCard($request = null)
{
    $log = array();
    if(!empty(valida_cartao($request['CardNumber']))){
        $request['Brand'] = valida_cartao($request['Brand']);
    }
    $curl = curl_init();
    
    $post                                            = array();
    $post['MerchantOrderId']                         = $request['MerchantOrderId'];
    $post['Customer']                                = array();
    $post['Customer']['Name']                        = $request['Name'];
    $post['Payment']                                 = array();
    $post['Payment']['Type']                         = 'CreditCard';
    $post['Payment']['Capture']                      = true;
    $post['Payment']['Amount']                       = (!empty($request['Amount']))?$request['Amount']:100;
    $post['Payment']['Installments']                 = 1;
   // $post['Payment']['SoftDescriptor']               = 'PINK_MAJESTY';
    $post['Payment']['Currency']                     = 'EUR';
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
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $log['request_info']['url'] =  API_Cielo_URL.'/1/sales';
    $log['request_info']['httpcode'] =  $httpcode;
   // echo $response;
   
    /*
        0- aguardando
        1 - taxa pg
        2 - total pg
        3 - taxa pg falha
        4 - total pg falha
        5 - cancelamento
        6 - estorno
    */	

    $json = json_decode($response,TRUE);
    echo json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    
    if(!empty($request['MerchantOrderId'])){
        $agenda         = $request['MerchantOrderId'];
        $sql_a         	= "SELECT * FROM tb_agenda WHERE id = '$agenda'";
        $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
        $linha_a       	= mysql_num_rows($resultado_a);
        if($linha_a > 0){
            $ln_a          	= mysql_fetch_assoc($resultado_a);
            $id_cliente     = $ln_a['id_cliente'];
            if(!empty($json['Payment']['Status'])){
                if(!empty($ln_a['payment_intent'])){
                    $PaymentId = $ln_a['payment_intent'].','.$json['Payment']['PaymentId'];
                } else {
                    $PaymentId =  $json['Payment']['PaymentId'];
                }
                if((intval($json['Payment']['Status']) == 1) || (intval($json['Payment']['Status']) == 2)){
                    if(empty($request['Amount'])){
                        $log['update']['sql'] = $sql_a = "UPDATE `tb_agenda` SET `pagamento`='1', `payment_intent`='$PaymentId', `situacao`='PEDIDO' WHERE `id`='$agenda'";
                        $log['update']['status'] = $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
                        sendMessage('Atualização do Pedido','Pagamento processado com sucesso...',$id_cliente);
                    } else {
                        $log['update']['sql'] =  $sql_a         	= "UPDATE `tb_agenda` SET `pagamento`='2', `payment_intent`='$PaymentId', `situacao`='CONCLUIDO' WHERE `id`='$agenda'";
                        $log['update']['status'] = $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
                        sendMessage('Atualização do Pedido','Pagamento processado com sucesso...',$id_cliente);
                    }
                } else {
                    if(empty($request['Amount'])){
                        $log['update']['sql'] =  $sql_a         	= "UPDATE `tb_agenda` SET `pagamento`='3', `payment_intent`='$PaymentId', `situacao`='CANCELADO' WHERE `id`='$agenda'";
                        $log['update']['status'] = $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
                        sendMessage('Atualização do Pedido','Erro ao processar o pagamento, verifique seu cartão.',$id_cliente);
                    } else {
                        $log['update']['sql'] =  $sql_a         	= "UPDATE `tb_agenda` SET `pagamento`='4', `payment_intent`='$PaymentId', `situacao`='CANCELADO' WHERE `id`='$agenda'";
                        $log['update']['status'] = $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
                        sendMessage('Atualização do Pedido','Erro ao processar o pagamento, verifique seu cartão.',$id_cliente);
                    }
                }
            } else {
                if(empty($request['Amount'])){
                    $log['update']['sql'] =  $sql_a         	= "UPDATE `tb_agenda` SET `pagamento`='3', `situacao`='CANCELADO' WHERE `id`='$agenda'";
                    $log['update']['status'] = $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
                    sendMessage('Atualização do Pedido','Erro ao processar o pagamento, verifique seu cartão.',$id_cliente);
                } else {
                    $log['update']['sql'] = $sql_a         	= "UPDATE `tb_agenda` SET `pagamento`='4', `situacao`='CANCELADO' WHERE `id`='$agenda'";
                    $log['update']['status'] = $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
                    sendMessage('Atualização do Pedido','Erro ao processar o pagamento, verifique seu cartão.',$id_cliente);
                }
            }
        } else {
            $log['response_info'][] =  "Error: no row in tb_agenda with id = $agenda";
        }
    } else {
        $log['response_info'][] =  "Error: no MerchantOrderId";
    }

    salva_log('chargeWithCard',array(
        'request'=>$post,
        'processing'=>$log,
        'response'=>$json
    ));
}
function valida_cartao($cartao, $cvc=false){
    $brands = array(
        'Visa'       => '/^4\d{12}(\d{3})?$/',
        'Master' => '/^(5[1-5]\d{4}|677189)\d{10}$/',
        'Diners'     => '/^3(0[0-5]|[68]\d)\d{11}$/',
        'Discover'   => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
        'Elo'        => '/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/',
        'Amex'       => '/^3[47]\d{13}$/',
        'JCB'        => '/^(?:2131|1800|35\d{3})\d{11}$/',
        'Aura'       => '/^(5078\d{2})(\d{2})(\d{11})$/',
        'Hipercard'  => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
        'Master'    => '/^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/',
    );
    $brand = 'undefined';
    foreach ( $brands as $_brand => $regex ) {
        if ( preg_match( $regex, $cartao ) ) {
            $brand = $_brand;
            break;
        }
    }
    return $brand;
    /*
	$cartao = preg_replace("/[^0-9]/", "", $cartao);
	if($cvc) $cvc = preg_replace("/[^0-9]/", "", $cvc);

	$cartoes = array(
			'Visa'		 => array('len' => array(13,16),    'cvc' => 3),
			'Master' => array('len' => array(16),       'cvc' => 3),
			'Diners'	 => array('len' => array(14,16),    'cvc' => 3),
			'Elo'		 => array('len' => array(16),       'cvc' => 3),
			'Amex'	 	 => array('len' => array(15),       'cvc' => 4),
			'Discover'	 => array('len' => array(16),       'cvc' => 4),
			'Aura'		 => array('len' => array(16),       'cvc' => 3),
			'JCB'		 => array('len' => array(16),       'cvc' => 3),
			'Hipercard'  => array('len' => array(13,16,19), 'cvc' => 3),
	);

	
	switch($cartao){
		case (bool) preg_match('/^(636368|438935|504175|451416|636297)/', $cartao) :
			$bandeira = 'Elo';			
		break;

		case (bool) preg_match('/^(606282)/', $cartao) :
			$bandeira = 'Hipercard';			
		break;

		case (bool) preg_match('/^(5067|4576|4011)/', $cartao) :
			$bandeira = 'Elo';			
		break;

		case (bool) preg_match('/^(3841)/', $cartao) :
			$bandeira = 'Hipercard';			
		break;

		case (bool) preg_match('/^(6011)/', $cartao) :
			$bandeira = 'Discover';			
		break;

		case (bool) preg_match('/^(622)/', $cartao) :
			$bandeira = 'Discover';			
		break;

		case (bool) preg_match('/^(301|305)/', $cartao) :
			$bandeira = 'Diners';			
		break;

		case (bool) preg_match('/^(34|37)/', $cartao) :
			$bandeira = 'Amex';			
		break;

		case (bool) preg_match('/^(36,38)/', $cartao) :
			$bandeira = 'Diners';			
		break;

		case (bool) preg_match('/^(64,65)/', $cartao) :
			$bandeira = 'Discover';			
		break;

		case (bool) preg_match('/^(50)/', $cartao) :
			$bandeira = 'Aura';			
		break;

		case (bool) preg_match('/^(35)/', $cartao) :
			$bandeira = 'JCB';			
		break;

		case (bool) preg_match('/^(60)/', $cartao) :
			$bandeira = 'Hipercard';			
		break;

		case (bool) preg_match('/^(4)/', $cartao) :
			$bandeira = 'Visa';			
		break;

		case (bool) preg_match('/^(5)/', $cartao) :
			$bandeira = 'Master';			
		break;
	}

	$dados_cartao = $cartoes[$bandeira];
	if(!is_array($dados_cartao)) return array(false, false, false);

	$valid     = true;
	$valid_cvc = false;

	if(!in_array(strlen($cartao), $dados_cartao['len'])) $valid = false;
	if($cvc AND strlen($cvc) <= $dados_cartao['cvc'] AND strlen($cvc) !=0) $valid_cvc = true;
	return $bandeira;*/
}
function cancelarPagamento($request = null){
    if(!empty($request['MerchantOrderId'])){
        $msg_return = NULL;
        $error = 0;
        $agenda         = $request['MerchantOrderId'];
        $sql_a         	= "SELECT * FROM tb_agenda WHERE id = '$agenda'";
        $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
        $linha_a       	= mysql_num_rows($resultado_a);
        if($linha_a > 0){
            $ln_a          	= mysql_fetch_assoc($resultado_a);
            $pagamentos = explode(",",$ln_a['payment_intent']);
            if(!empty($pagamentos)){
                for($i=0;$i<count($pagamentos);$i++){
                    $curl = curl_init();
                    $url = API_Cielo_URL.'/1/sales/'.$pagamentos[$i].'/void';
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $url,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'PUT',
                      CURLOPT_HTTPHEADER => array(
                        'MerchantId: '.API_Cielo_MerchantId,
                        'Content-Type: application/json',
                        'Content-Length: 0',
                        'MerchantKey: '.API_Cielo_MerchantKey
                        ),
                    ));
                    
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $response = json_decode($response,TRUE);
                    salva_log('cancelarPagamento', $response);
                    if(!empty($response['ReturnMessage'])){
                        $msg_return .= $response['ReturnMessage'].' ';
                    } else {
                        $msg_return .= $response[0]['Message'].' ';
                    }
                    $id_cliente     = $ln_a['id_cliente'];
                    $log['update']['sql'] =  $sql_a         	= "UPDATE `tb_agenda` SET `pagamento`='5', `situacao`='CANCELADO' WHERE `id`='$agenda'";
                    $log['update']['status'] = $resultado_a   	= mysql_query($sql_a) or die(mysql_error());
                    sendMessage('Atualização do Pedido','Erro ao processar o pagamento, verifique seu cartão.',$id_cliente);
                }
            } else {
                $msg_return = "Pagamento não encontrado";
                $error = 1;
            }
        } else {
            $msg_return = "Venda não encontrada";
            $error = 2;
        }
    } else {
        $msg_return = "Venda não encontrada";
        $error = 3;
    }
    echo json_encode(array('error' => $error, 'msg'=> $msg_return));
}
