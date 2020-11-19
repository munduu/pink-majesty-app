<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');	
require_once('function/valida-cpf.php');
require_once('function/log.php');

$user       = anti_sql_injection(strip_tags(trim($_REQUEST['user'])));
$servico 	= anti_sql_injection(strip_tags(trim($_REQUEST['servico'])));
$local     	= anti_sql_injection(strip_tags(trim($_REQUEST['local'])));
$data     	= anti_sql_injection(strip_tags(trim($_REQUEST['data'])));
$hora  		= anti_sql_injection(strip_tags(trim($_REQUEST['hora'])));
$forma_pg   = anti_sql_injection(strip_tags(trim($_REQUEST['forma_pg'])));
$cupom		= anti_sql_injection(strip_tags(trim($_REQUEST['cupom'])));
$cpf		= anti_sql_injection(strip_tags(trim($_REQUEST['cpf'])));
$valor		= anti_sql_injection(strip_tags(trim($_REQUEST['s_valor'])));
$payment_intent	= anti_sql_injection(strip_tags(trim($_REQUEST['payment_intent'])));


$data_atual = date("Y/m/d");
$hora_atual = date("H:i");

$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
if(empty($user) or empty($servico) or empty($local) or empty($data) or empty($hora) or empty($forma_pg)){
	$error = 1;
	$dados = "CAMPO OBRIGATORIO VAZIO!";
}elseif($token=='H424715433852'){
	
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

	if(valida_cpf($cpf) or empty($cupom)){
		
		$sql_cli 		= "SELECT * FROM tb_cliente WHERE id='$id_cliente'";
		$resultado_cli  = mysql_query($sql_cli) or die(mysql_error());
		$ln_cli 		= mysql_fetch_assoc($resultado_cli);
		$cpf_cliente	 		= $ln_cli['cpf'];
	
		if(empty($cpf_cliente)){
			$updateSQL = "
			UPDATE tb_cliente SET cpf='$cpf' WHERE id='$id_cliente'";
			mysql_select_db($database_localhost, $localhost);
  			$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
    		salvaLog("Alterar tb_cliente ID $id_cliente",$_SESSION['email']);
		}	
		
		$sql 		= "SELECT * FROM tb_produto WHERE id = '$servico' AND categoria = 'SERVICOS' AND status = 'ativo'";
		$resultado 	= mysql_query($sql) or die(mysql_error());
		$ln    		= mysql_fetch_assoc($resultado);
		
		$tempo = $ln['tempo']+60;
		
		$hora_fim = date("H:i",  strtotime( "+".$tempo." minute", strtotime($hora) ));
		
		$insertSQL = 
		"INSERT INTO tb_agenda (id_cliente, cpf, servico, local, data, hora_ini, hora_fim, forma_pg, cupom, valor, valor_semdesconto, status, situacao, data_agend, hora_agend, payment_intent) 
		VALUES ('$id_cliente', '$cpf', '$servico', '$local', '$data','$hora','$hora_fim', '$forma_pg', '$cupom', '$valor', '$ln[venda]', 'ativo', 'PEDIDO', '$data_atual', '$hora_atual','$payment_intent')"
		;
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
		salvaLog("Add tb_agenda",$_SESSION['nomeu']);

		$sqla        = "SELECT id FROM tb_agenda WHERE id_cliente='$id_cliente' AND servico='$servico' AND local='$local' AND  data='$data' AND  hora_ini='$hora' AND  hora_fim='$hora_fim' AND forma_pg='$forma_pg' ORDER BY id DESC LIMIT 1";
		$resulta     = mysql_query($sqla) or die(mysql_error());
		$linha       = mysql_num_rows($resulta);
		$lna         = mysql_fetch_assoc($resulta);
		$agenda      = $lna['id'];

		$sqlfg       = "SELECT * FROM tb_cartoes WHERE id_cliente='$id_cliente' AND id='$forma_pg'";
		$resultfg    = mysql_query($sqlfg) or die(mysql_error());
		$linhafg     = mysql_fetch_assoc($resultfg);
		$lNcartao    = $linhafg['numero'];
		$lNmcartao   = $linhafg['nome_impresso'];
		$lMesVenc    = $linhafg['mes_val'];
		$lAnoVenc    = $linhafg['ano_val'];
		$lCodigoSeg  = $linhafg['cod_seg'];
		
		$updateSQL = "
		UPDATE tb_cupom SET n_utilizacoes = n_utilizacoes - 1 WHERE cod='$cupom'";
		mysql_select_db($database_localhost, $localhost);
  		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
    	salvaLog("Alterar tb_cliente ID $id_cliente",$_SESSION['email']);
		
		$error = 2;
		$dados = "Seu Pedido foi recebido, aguarde até que um profissional aceite o agendamento!";
		
		//require_once('notif_pedido.php');
		 
	}else{
		$error = 5;
		$dados = 'Erro: CPF Inválido!';
	}
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'","agenda":"'.$agenda.'","lNcartao":"'.$lNcartao.'","lNmcartao":"'.$lNmcartao.'","lMesVenc":"'.$lMesVenc.'","lAnoVenc":"'.$lAnoVenc.'","lCodigoSeg":"'.$lCodigoSeg.'"}';
?>