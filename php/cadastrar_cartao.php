<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/valida-cpf.php');
require_once('function/log.php');

$user   			= anti_sql_injection(strip_tags(trim($_POST['user'])));
$numero_c       	= strip_tags(trim(str_replace(' ','',$_POST['numero_c'])));
$cod_seg			= strip_tags(trim($_POST['cod_seg']));
$mes 				= strip_tags(trim($_POST['mes']));
$ano 				= strip_tags(trim($_POST['ano']));
$nome_impresso 		= strip_tags(trim($_POST['nome_impresso']));
$data_nasc_cartao 	= strip_tags(trim($_POST['data_nasc_cartao']));
$cpf_titular 		= strip_tags(trim($_POST['cpf_titular']));
$tipo_cartao 		= strip_tags(trim($_POST['tipo_cartao']));
$cartao      		= strip_tags(trim($_POST['cartao']));
$bancodd      		= strip_tags(trim($_POST['banco']));

$token        = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

/*if($tipo_cartao==1){
	if (valida_cpf($cpf_titular)) {
		$valido_fim = 1;
	}else{
		$valido_fim = 0;
	}
}else{
	$valido_fim = 1;
}*/

//if($valido_fim==1){

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
			if(($tipo_cartao == 0) || empty($tipo_cartao)){
				if(empty($numero_c)){
					$tipo_cartao = 3;
				} else {
					$tipo_cartao =1;
				}
			}
			
			$insertSQL = 
			"INSERT INTO tb_cartoes (id_cliente, numero, cod_seg, mes_val, ano_val, nome_impresso, data_nasc, cpf, tipo, banco)
			VALUES ('$id_cliente', '$numero_c', '$cod_seg', '$mes', '$ano', '$nome_impresso', '$data_nasc_cartao', '$cpf_titular', '$tipo_cartao', '$bancodd')"
			;
			mysql_select_db($database_localhost, $localhost);
			$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
			$error = 2;
			$dados = "CARTÃO CADASTRADO COM SUCESSO!";
		}else{
			$error = 3;
			$dados = "FAÇA LOGIN PRIMEIRO!";
		}
	}else{
		$error = 4;
		$dados = 'Erro: Token inválido!';
	}
/*}else{
	$error = 5;
	$dados = 'Erro: CPF Inválido!';
}*/

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
?>