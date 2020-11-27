<?php
header("Access-Control-Allow-Origin: *");
ob_start();
require_once('../Connections/localhost.php');
require_once('../function/log.php');
require_once('../function/function.php');
require_once('config.php');

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);

$user   	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$token        = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
//die(md5('teste@teste1.com.br'.'202cb962ac59075b964b07152d234b70'));
if($token == 'H424715433852'){
	
    $linha = 0;
    $error = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
            $linha ++;
            $id = $ln['id'];
            $id_cliente = $ln['id_cliente'];
            $email = $ln['email'];
            $gateway_id = $ln['gateway_id'];
		}
	}
	if($linha > 0){
        $PaymentMethod = \Stripe\PaymentMethod::all([
          'customer' => $gateway_id,
          'type' => 'card',
        ]);
        if (!empty($PaymentMethod)){
          //SELECIONA OS CARTOES NO BANCO
          $db_cards = array();
          $sql 		= "SELECT * FROM tb_cartoes WHERE id_cliente=$id_cliente";
          $resultado 	= mysql_query($sql) or die(mysql_error());
          if (mysql_num_rows($resultado)>0){
            while($ln = mysql_fetch_assoc($resultado)){
              $cardDetails = array();
              $cardDetails['id']     = $ln['id_cartao'];
              $cardDetails['number'] = $ln['numero'];
              $db_cards[] = $cardDetails;
            }
          }
          
          //SELECIONA OS CARTOES NO STRIPE
          $lista = json_decode(json_encode($PaymentMethod),1);
          $cards = $lista["data"];
          $lista = array();
          foreach($cards as $card){
            $cardDetails = array();
            $cardDetails['id']    = $card["id"];
            $cardDetails['brand'] = $card["card"]["brand"];
            $cardDetails['last4'] = $card["card"]["last4"];

            //VERIFICA SE O CARTÃO ESTA NO BANCO
            $saved = false; 
            foreach($db_cards as $db_card){
              if($db_card['number']=="000000000000".$cardDetails['last4']){
                if($db_card['id']==$cardDetails['id']){
                  $saved = true;
                } else {
                  //ATUALIZA O ID DO CARTAO
                  $id_cartao  = $cardDetails['id'];
                  $numero     = "000000000000".$cardDetails['last4'];
                  $sql 		    = "UPDATE `tb_cartoes` SET `id_cartao`='$id_cartao' WHERE `numero`='$numero'";
                  $resultado 	= mysql_query($sql) or die(mysql_error());
                  $log        = "UPDATE ID CARD:".$cardDetails['id'];
                  salvaLog($log,$email);
                }
              }
            }
            if (!$saved){
                  $id_cartao  = $cardDetails['id'];
                  $numero     = "000000000000".$cardDetails['last4'];
                  $banco      = $cardDetails['brand'];
                  $sql 		    = "INSERT INTO `tb_cartoes` (`id_cliente`, `numero`, `id_cartao`, `banco`) VALUES ('$id_cliente', '$numero', '$id_cartao', '$banco')";
                  $resultado 	= mysql_query($sql) or die(mysql_error());
                  salvaLog($sql,$email);
                  
            }
            $lista[] = $cardDetails;
          }
          $dados = $lista;
          //$dados = "Updated Cards on database";
          //$log = "New SetupIntentClient_Secret:".$client_secret;
          //salvaLog($log,$email);
      } else {
        $error = 2;
        $dados = $PaymentMethod;
      } 
    }else{
		$error = 3;
		$dados = "FAÇA LOGIN PRIMEIRO!";
	}
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}
    $response = array();
    $response['erro'] = $error;
    $response['dados'] = $dados;
    echo json_encode($response);
?>