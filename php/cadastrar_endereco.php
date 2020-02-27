<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$user   	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$cep        = anti_sql_injection(strip_tags(trim($_POST['cep'])));
$logradouro = anti_sql_injection(strip_tags(trim($_POST['logradouro'])));
$numero     = anti_sql_injection(strip_tags(trim($_POST['numero'])));
$bairro     = anti_sql_injection(strip_tags(trim($_POST['bairro'])));
$estado     = anti_sql_injection(strip_tags(trim($_POST['estado'])));
$cidade     = anti_sql_injection(strip_tags(trim($_POST['cidade'])));
$complemento= anti_sql_injection(strip_tags(trim($_POST['complemento'])));
$referencia = anti_sql_injection(strip_tags(trim($_POST['referencia'])));

$token        = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
		}
	}
		
	if($linha > 0){
		
		if (!is_numeric($cidade)) {
			$sql_cid = "SELECT id FROM tb_municipios WHERE nome = '$cidade'";
			$qr_cid  = mysql_query($sql_cid) or die (mysql_error());
			$ln_cid  = mysql_fetch_assoc($qr_cid);
			$cidade  = $ln_cid['id'];
		}
		if (!is_numeric($estado)) {
			$sql_e = "SELECT iduf FROM tb_estados WHERE uf = '$estado'";
			$qr_e  = mysql_query($sql_e) or die (mysql_error());
			$ln_e  = mysql_fetch_assoc($qr_e);
			$estado = $ln_e['iduf'];
		}
		
		$insertSQL = "INSERT INTO tb_enderecos (id_cliente, cep, logradouro, numero, complemento, bairro, cidade, estado, referencia) 
		VALUES ('$id_cliente', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$referencia')";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
		
		$sql_end = "SELECT id FROM tb_enderecos WHERE id_cliente = '$id_cliente' AND cep = '$cep' AND logradouro = '$logradouro' AND numero = '$numero'";
		$qr_end  = mysql_query($sql_end) or die (mysql_error());
		$ln_end  = mysql_fetch_assoc($qr_end);
		$id_end  = $ln_end['id'];
		
		$error = 2;
		$dados = "ENDEREÇO CADASTRADO COM SUCESSO!";
	}else{
		$error = 3;
		$dados = "FAÇA LOGIN PRIMEIRO!";
	}
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'","id":"'.$id_end.'"}';
?>