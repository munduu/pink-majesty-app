<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$user   	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$tipo   	= anti_sql_injection(strip_tags(trim($_POST['tipo'])));
$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$agenda     = anti_sql_injection(strip_tags(trim($_REQUEST['agenda'])));
$forma_pg   = anti_sql_injection(strip_tags(trim($_REQUEST['forma_pg'])));


if($token=='H424715433852'){
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
			$email = $ln['email'];
		}
	}
	
	if($linha > 0){
		
		$updateSQL = "
		UPDATE tb_agenda SET forma_pg='$forma_pg' WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
  		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
    	salvaLog("Alterar tb_cliente ID $id",$_SESSION['email']);
		
		$error = 2;
		$dados = "CARTÃO ALTERADO COM SUCESSO!";
	}else{
		$error = 3;
		$dados = "FAÇA LOGIN PRIMEIRO!";
	}
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
?>