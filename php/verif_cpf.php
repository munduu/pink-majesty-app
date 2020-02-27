<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){

	$user    	= anti_sql_injection(strip_tags(trim($_REQUEST["user"])));
	
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
		}
	}
	
	$sql_cli 		= "SELECT * FROM tb_cliente WHERE id='$id_cliente'";
	$resultado_cli  = mysql_query($sql_cli) or die(mysql_error());
	$ln_cli 		= mysql_fetch_assoc($resultado_cli);
	$cpf	 		= $ln_cli['cpf'];
	
	if(empty($cpf)){
		$error = 1;
	}else{
		$error = 2;
		$dados = $cpf;
	}	
}else{
	$error = 4;
	//$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'"}';


?>