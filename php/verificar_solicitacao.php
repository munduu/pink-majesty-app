<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$user   	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$tipo 			= $ln['tipo'];
			if($tipo == 'Profissional'){
				$id_colaborador = $ln['id_colaborador'];
			}
			if($tipo == 'Cliente'){
				$id_cliente = $ln['id_cliente'];
			}
			$notif			= $ln['notif'];
			
			$token_id_user  = $ln['token_id'];
			
		}
	}
		
	if($linha > 0){
		/*
		$updateSQL = "
		UPDATE tb_cliente SET tel1='$tel1', tel2='$tel2' WHERE id = '$id_cliente'";
		mysql_select_db($database_localhost, $localhost);
  		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
    	salvaLog("Alterar tb_cliente ID $id",$_SESSION['email']);
		*/
		
		$sql_con 		= "SELECT * FROM conversas WHERE destino = '$id_cliente' AND status = '0'";
		$resultado_con 	= mysql_query($sql_con) or die(mysql_error());
		$linha_con 		= mysql_num_rows($resultado_con);
		
		if($notif == 1){
			$error = 2;
			if($tipo == 'Profissional'){
				$dados = "NOVO PEDIDO!";
				
				/*$title    = "Dellas Beleza Delivery";
				$message  = 'Oba...Novo pedido recebido !'; 
				sendMessage($message,$token_id_user);*/
			}
			if($tipo == 'Cliente'){
				$dados = "PEDIDO AGUARDANDO PAGAMENTO!";
				
				/*$title    = "Dellas Beleza Delivery";
				$message  = 'PEDIDO AGUARDANDO PAGAMENTO!'; 
				sendMessage($message,$token_id_user);*/
			}
		}else{
			$error = 5;
		}
	}else{
		$error = 3;
		$dados = "FAÇA LOGIN PRIMEIRO!";
	}
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","chat":"'.$linha_con.'","tipo":"'.$tipo.'"}';
?>