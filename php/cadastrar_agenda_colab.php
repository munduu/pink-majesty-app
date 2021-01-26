<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$user       = anti_sql_injection(strip_tags(trim($_REQUEST['user'])));
$agenda 	= anti_sql_injection(strip_tags(trim($_REQUEST['agenda'])));
$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Profissional' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_colaborador = $ln['id_colaborador'];
		}
	}


	
	$sql_ag 		= "SELECT * FROM tb_agenda WHERE id = '$agenda' AND id_colaborador IS NULL ORDER BY id";
	$resultado_ag 	= mysql_query($sql_ag) or die(mysql_error());
	$ln_ag			= mysql_fetch_assoc($resultado_ag);
	$linha_ag 		= mysql_num_rows($resultado_ag);
	$id_cliente		= $ln_ag['id_cliente'];
	$forma_pg		= $ln_ag['forma_pg'];

	
	
	if($linha_ag != 0){

		$hoje = date("Y-m-d H:i:s", strtotime($ln_ag['data']." ".$ln_ag['hora_ini']));
		$sql_bloqueio_agenda ="SELECT * FROM `tb_horario_colaborador` WHERE `bloqueado_inicio`> '$hoje' AND `bloqueado_fim`< '$hoje' AND status='0' AND `id_colaborador`='$id_colaborador'";
		$resultado_bloqueio_agenda   = mysql_query($sql_bloqueio_agenda) or die(mysql_error());
		$linha_bloqueio_agenda       = mysql_num_rows($resultado_bloqueio_agenda);
		if($linha_bloqueio_agenda > 0){
			$error = 3;
			$dados = "Infelizmente não foi possível aceitar esse pedido. Verifique seu horário de atendimento!";
			echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
			die();
		}  
		
		$sql_crt 		= "SELECT * FROM tb_cartoes WHERE id='$forma_pg'";
		$resultado_crt  = mysql_query($sql_crt) or die(mysql_error());
		$ln_crt 		= mysql_fetch_assoc($resultado_crt);
		$lTipo			= $ln_crt['tipo'];
		
		if($lTipo==1){ //cartao
			//$situacaoT = 'ACEITO';
			$situacaoT = 'AGENDADO';
		}elseif($lTipo==3){ //dinheiro
			$situacaoT = 'AGENDADO';
		} else {
			$situacaoT = 'AGENDADO';
		}
		
		$updateSQL = "UPDATE tb_agenda SET id_colaborador='$id_colaborador', situacao='$situacaoT' WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
		salvaLog("Alterar tb_agenda ID $id",$_SESSION['email']);
		
		$updateSQL2 = "UPDATE tb_login SET notif='1' WHERE id_cliente = '$id_cliente'";
		mysql_select_db($database_localhost, $localhost);
		$Result2 = mysql_query($updateSQL2, $localhost) or die(mysql_error()); 
		salvaLog("Alterar tb_agenda ID $id",$_SESSION['email']);
		
		$error = 2;
		$dados = "Pedido aceito com Sucesso, processando pagamento!";
		
			$sql_c2 		= "SELECT token_id FROM tb_login WHERE id_cliente='$ln_ag[id_cliente]' ";
			$resultado_c2	= mysql_query($sql_c2) or die(mysql_error());
			$ln_c2			= mysql_fetch_assoc($resultado_c2);
		    $token_id_user = $ln_c2['token_id'];
			
			$title    = "Dellas Beleza Delivery";
			
			//se for cartao
			if($lTipo==1){ 
				$message  = 'Seu pedido foi aceito, processando pagamento!';
				$ped      = 'Estamos processando o pagamento!';
			} else { 
				$message  = 'Seu pedido foi aceito!';
				$ped      = 'Pagamento escolhido em dinheiro.';
			}
			sendMessage($message,$token_id_user);		
							
		require_once('notif_aceito_email.php');
		
	}else{
		$error = 3;
		$dados = "=( Infelizmente este pedido ja foi aceito!";
	}
	
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
?>