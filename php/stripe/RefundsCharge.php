<?php
header("Access-Control-Allow-Origin: *");
ob_start();
require_once('../Connections/localhost.php');
require_once('../function/log.php');
require_once('../function/function.php');
require_once('config.php');

$user   	             = anti_sql_injection(strip_tags(trim($_POST['user'])));
$token                 = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$id_agenda             = anti_sql_injection(strip_tags(trim($_POST['id_agenda'])));
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
    $sql 		= "SELECT `payment_intent`,`payment_intent_remaining` FROM `tb_agenda` WHERE `id`='$id_agenda'";
	  $resultado 	= mysql_query($sql) or die(mysql_error());
	  while($ln = mysql_fetch_assoc($resultado)){
      if(!empty($ln['payment_intent'])){
        $payment_intent = $ln['payment_intent'];
        $sql2 		= "SELECT `charge` FROM `tb_webhook` WHERE `payment_intent` = '$payment_intent' AND `charge` IS NOT NULL ORDER BY `id` DESC LIMIT 1";
        $resultado2 	= mysql_query($sql2) or die(mysql_error());
        while($ln2 = mysql_fetch_assoc($resultado2)){
          $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);
          $dados[] = $stripe->refunds->create([
            'charge' => $ln2['charge'],
          ]);
          $dados['message'][] = "Pedido de estorno realizado para pagamento de taxa";
        }
      } else {
        $error += 5;
		    $dados['message'][] = "Não há pagamento de taxa associado.";
      }
      if(!empty($ln['payment_intent_remaining'])){
        $payment_intent = $ln['payment_intent_remaining'];
        $sql2 		= "SELECT `charge` FROM `tb_webhook` WHERE `payment_intent` = '$payment_intent' AND `charge` IS NOT NULL ORDER BY `id` DESC LIMIT 1";
        $resultado2 	= mysql_query($sql2) or die(mysql_error());
        while($ln2 = mysql_fetch_assoc($resultado2)){
          $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);
          $dados[] = $stripe->refunds->create([
            'charge' => $ln2['charge'],
          ]);
          $dados['message'][] = "Pedido de estorno realizado para pagamento remanescente";
        }
      } else {
        $error += 6;
		    $dados['message'][]= "Não há pagamento remanescente associado";
      }
    }
    
  }else{
		$error = 3;
		$dados['message'][] = "FAÇA LOGIN PRIMEIRO!";
	}
}else{
	$error = 4;
	$dados['message'][] = 'Erro: Token inválido!';
}
    $response = array();
    $response['erro'] = $error;
    $response['dados'] = $dados;
    echo json_encode($response);
?>