<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/valida-cpf.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php");

//        
$text_periodo = array('.', '-', '/', '(', ')' , ' ');

$cpf 		= anti_sql_injection(strip_tags(trim($_REQUEST['cpf'])));
$nome       = anti_sql_injection(strip_tags(trim($_REQUEST['nome'])));
$tel1 		= anti_sql_injection(strip_tags(trim($_REQUEST['tel1'])));
$tel2     	= anti_sql_injection(strip_tags(trim($_REQUEST['tel2'])));
$sexo     	= anti_sql_injection(strip_tags(trim($_REQUEST['sexo'])));
$data_nasc  = anti_sql_injection(strip_tags(trim($_REQUEST['data_nasc'])));
$email     	= anti_sql_injection(strip_tags(trim($_REQUEST['email'])));
$senham		= anti_sql_injection(strip_tags(trim($_REQUEST['senham'])));

$tel2 = str_replace($text_periodo,'',$tel2);

$data_nasc = databar($data_nasc);

$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

$valido = 0;
if (!empty($cpf)) {
	if (valida_cpf($cpf)) {
		$valido = 0;
	}else{
		$valido = 1;
	}
}
if(empty($nome) or empty($tel2) or empty($sexo) or empty($data_nasc) or empty($email) or empty($senham) ){
	$error = 1;
	$dados = "CAMPO OBRIGATORIO VAZIO!";
}elseif($token=='H424715433852'){
	if($valido != 1){	
		$usuario = str_replace(' ','',strip_tags(trim($_POST['nome'])));
		$usuario = resumo_corta($usuario, 15);
	
		$sql = "SELECT * FROM tb_login WHERE email = '$email' AND ativo = 'ativo'";
		$resultado = mysql_query($sql) or die(mysql_error());
		$linha     = mysql_num_rows($resultado);
		if ($linha>=1) 	{
			$error = 3;
			$dados = "DADOS DE EMAIL JA CADASTRADOS!";
		}else{
			$insertSQL = "INSERT INTO tb_cliente (cpf, nome, tel1, tel2, email, sexo, data_nasc, desconto, status) VALUES ('$cpf', '$nome', '$tel1', '$tel2', '$email','$sexo','$data_nasc', '1', 'ativo')"
			;
			mysql_select_db($database_localhost, $localhost);
			$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error("erro!"));
			salvaLog("Add tb_cliente",$_SESSION['nomeu']);
				
			$sql = "SELECT id FROM tb_cliente ORDER BY id DESC LIMIT 1";
			$qr  = mysql_query($sql) or die (mysql_error());
			$ln  = mysql_fetch_assoc($qr);
					
			//INSERE USUARIO
			$senha = md5($senham);
		
			$insertSQL = "INSERT INTO tb_login (nome, usuario, email, tipo, senha, ativo, id_grupo, id_cliente) VALUES ('$nome', '$usuario','$email', 'Cliente', '$senha', 'ativo', '11', '$ln[id]')";
			mysql_select_db($database_localhost, $localhost);
			$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
			salvaLog("Add LOGIN CLIENTE $ln[id]",$_SESSION['email']);
			
		
			$email_cliente = $email;
			
			$msg = '';
			
			require("bemvindo.php");
			
			$error = 2;
			$dados = "Enviamos um email para que você ative seu cadastro, mas você ja pode acessar sua conta!";
		}
	}else{
		$error = 5;
		$dados = 'Erro: CPF Inválido!';
	}
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
?>