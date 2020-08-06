<?php
header("Access-Control-Allow-Origin: *");
ob_start();
require_once('../Connections/localhost.php');
require_once('../function/log.php');
require_once('../function/function.php');
require_once('config.php');

$user            	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$token            = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$payment_intent   = anti_sql_injection(strip_tags(trim($_POST['payment_intent'])));
//die(md5('teste@teste1.com.br'.'202cb962ac59075b964b07152d234b70'));
if($token == 'H424715433852'){
  $linha = 0;
  $error = 0;
  $dados =array();
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
      $sql 		= "SELECT * FROM `tb_webhook` WHERE `payment_intent`='$payment_intent' AND `cliente_id`='$id_cliente' AND `status`='paid' ORDER BY `id` DESC LIMIT 1";
      $resultado 	= mysql_query($sql) or die(mysql_error());
      while($ln = mysql_fetch_assoc($resultado)){
        $dados['payment_intent'] = $ln['payment_intent'];
        $dados['valor'] = $ln['valor'];
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