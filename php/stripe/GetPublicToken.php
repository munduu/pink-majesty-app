<?php
require_once('../Connections/localhost.php');
require_once('../function/log.php');
require_once('../function/function.php');
require_once('config.php');

$user   	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$token        = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
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
        $dados = array('token'=> 'pk_test_HwOzl6pIove5P3TZopMpaOsg001qQzh3P6');
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