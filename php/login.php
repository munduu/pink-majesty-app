<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$putConteudo = $_POST['putConteudo'];

$lEmail      = anti_sql_injection(strip_tags(trim($_REQUEST['login_email'])));
$lSenha      = md5(anti_sql_injection(strip_tags(trim($_REQUEST['login_senha']))));
$c_acesso    = anti_sql_injection(strip_tags(trim($_REQUEST['c_acesso'])));
$token       = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$token_id    = anti_sql_injection(strip_tags(trim($_REQUEST['token_id'])));


if($token=='H424715433852'){

	if(empty($c_acesso)){
		$c_acesso = md5($lEmail.$lSenha);

		$linha = 0;
		$sql 		= "SELECT * FROM tb_login WHERE email = '$lEmail' AND senha = '$lSenha' AND ativo='ativo'";
		$resultado 	= mysql_query($sql) or die(mysql_error());
		while($ln 	= mysql_fetch_assoc($resultado)){
			$c_verif 	= md5($ln['email'].$ln['senha']);
			$tipo 		= $ln['tipo'];
			if($c_acesso == $c_verif){
				$linha ++;
				$nome	 	= $ln['nome'];
				$id_cliente = $ln['id_cliente'];
				$id_login 	= $ln['id'];
			}
		}
	}else{//$c_acesso;
		$sql 		= "SELECT * FROM tb_login ORDER BY id";
		$resultado 	= mysql_query($sql) or die(mysql_error());
		while($ln 	= mysql_fetch_assoc($resultado)){
			$c_verif 	= md5($ln['email'].$ln['senha']);
			$tipo 		= $ln['tipo'];
			if($c_acesso == $c_verif){
				$linha ++;
				$nome	 	= $ln['nome'];
				$id_cliente = $ln['id_cliente'];
				$id_login 	= $ln['id'];
			}
		}
	}		
	
	if ($linha>=1) 	{
		$error = 1;
		$dados = "LOGADO COM SUCESSO!";
		$id_cliente = $c_acesso;
		
		if(!empty($token_id)){
			$updateSQL = "UPDATE tb_login SET token_id='$token_id' WHERE id = '$id_login'"; /*inserindo dados no banco*/
			mysql_select_db($database_localhost, $localhost);
			$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
		}
		
	}else{
		$error = 2;
		$dados = "Dados inválidos ou você não ativou seu cadastro!";
	}
	
}else{
	$error = 3;
	$dados = 'Erro: Token inválido!';
}
//$dados .= 'teste';
echo '{"erro":"'.$error.'","dados":"'.$dados.'","id_cliente":"'.$id_cliente.'","tipo":"'.$tipo.'","nome":"'.$nome.'"}';
?>