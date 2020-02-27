<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

//

$user       	= anti_sql_injection(strip_tags(trim($_REQUEST['user'])));
$agenda 		= anti_sql_injection(strip_tags(trim($_REQUEST['agenda'])));
$situacao 		= anti_sql_injection(strip_tags(trim($_REQUEST['situacao'])));
$tipo 			= anti_sql_injection(strip_tags(trim($_REQUEST['tipo'])));
$cod_pagseguro 	= anti_sql_injection(strip_tags(trim($_REQUEST['cod_pagseguro'])));

$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
/*if(empty($user) or empty($servico) or empty($local) or empty($data) or empty($hora) or empty($forma_pg)){
	$error = 1;
	$dados = "CAMPO OBRIGATORIO VAZIO!";
}else*/
if($token=='H424715433852'){
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = '$tipo' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];					
		}
	}
	
	$sql_a         	= "SELECT * FROM tb_agenda WHERE id = '$agenda'";
	$resultado_a   	= mysql_query($sql_a) or die(mysql_error());
	$linha_a       	= mysql_num_rows($resultado_a);
	$ln_a          	= mysql_fetch_assoc($resultado_a);
	$cod_cupom 	   		= $ln_a['cupom'];
	$id_cliente_des		= $ln_a['id_cliente'];
	$id_colaborador_des	= $ln_a['id_colaborador'];
			
	//VALIDA CUPOM DE DESCONTO
	if($cod_cupom == 'pink1vez'){
		$updateSQL = "
		UPDATE tb_cliente SET desconto='0' WHERE id = '$id_cliente'";
		mysql_select_db($database_localhost, $localhost);
  		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
    	salvaLog("Alterar tb_cliente ID $id",$_SESSION['email']);
	}
	
	//SE TIVE PAGSEGURO E SITUAÇÃO FOR AGENDADO ATUALIZA CODIGO
	if($situacao=='AGENDADO' AND $cod_pagseguro){
		$ins_pag = "cod_pagseguro = '$cod_pagseguro', situacao='AGENDADO'";
		
		$updateSQL = "UPDATE tb_agenda SET $ins_pag WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
		salvaLog("Alterar tb_agenda ID $id",$_SESSION['email']);
		
		$dados.= $cod_pagseguro.'-'.$agenda.'-'.$situacao;

	//SE NÃO TIVER PAGSEGURO E SITUAÇÃO FOR AGENDADO ATUALIZA SITUAÇÃO
	}elseif($situacao=='AGENDADO' AND !$cod_pagseguro){
		$ins_pag = "";
		
		$updateSQL = "UPDATE tb_agenda SET situacao='AGENDADO' WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
		salvaLog("Alterar tb_agenda ID $id",$_SESSION['email']);
		
		$dados.= $cod_pagseguro.'-'.$agenda.'-'.$situacao;
		
	}elseif($situacao=='ACEITO' AND $cod_pagseguro){
		
		$ins_pag = "cod_pagseguro = '$cod_pagseguro'";
		
		$updateSQL = "UPDATE tb_agenda SET $ins_pag WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
		salvaLog("Alterar tb_agenda ID $id",$_SESSION['email']);
		
		$dados.= $cod_pagseguro.'-'.$agenda.'-'.$situacao;
	}
	
	$error = 2;
	$dados.= $cod_pagseguro.'-'.$agenda.'-'.$situacao;
	//----------CONSULTA O PAGSEGURO--------------------//
	$email_destino = $ln['email'];
	$pagseguro     = 'ok';
	
	//--VERIFICAÇÃO DE STATUS
	if($situacao=='AGENDADO' and $pagseguro == 'ok'){ 
	
		//AVISA O PROFISSIONAL	
		$sqlE = "SELECT email FROM tb_colaborador WHERE id = '$id_colaborador_des' ";
		$qrE  = mysql_query($sqlE) or die (mysql_error());
		$lnE  = mysql_fetch_assoc($qrE);			
		$email_destino = $lnE['email'];
		
		$sqlT = "SELECT token_id FROM tb_login WHERE id_colaborador = '$id_colaborador_des' ";
		$qrT  = mysql_query($sqlT) or die (mysql_error());
		$lnT  = mysql_fetch_assoc($qrT);			
		$tokens = $lnT['token_id'];
		
		$msg_alert = 'Pedido agendado com sucesso! Acesse pedidos Agendados para mais detalhes.';
		
		//PUSH
		if(!empty($tokens)){					
			$title    = "Pink Majesty";
			$message  = "Pagamento confirmado! Programe-se e não se atrase!";
			sendMessage($message,$tokens);
		}
		
		//NOTIFICAÇÃO DE ALTERAÇÃO PROFISSIONAL	
		$updateSQL = "
		UPDATE tb_login SET notif='1' WHERE id_colaborador = '$id_colaborador_des'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
		
			require_once('notif_agendado_email.php');
	   
	}
	 
	 
	if($situacao=='CONCLUIDO'){ 
	
		//AVISA O CLIENTE PARA AVALIAR

		$sqlE = "SELECT email FROM tb_cliente WHERE id = '$id_cliente_des' ";
		$qrE  = mysql_query($sqlE) or die (mysql_error());
		$lnE  = mysql_fetch_assoc($qrE);			
		$email_destino = $lnE['email'];
		
		$sqlT = "SELECT token_id FROM tb_login WHERE id_cliente = '$id_cliente_des' ";
		$qrT  = mysql_query($sqlT) or die (mysql_error());
		$lnT  = mysql_fetch_assoc($qrT);			
		$tokens = $lnT['token_id'];
		
		$updateSQL = "UPDATE tb_agenda SET situacao='CONCLUIDO' WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
		salvaLog("Concluir tb_agenda ID $agenda",$_SESSION['email']);
		
		//PUSH
		if(!empty($tokens)){
			
			$title    = "Pink Majesty";
			$message  = "Pedido $agenda concluido ... avalie o atendimento!"; 
			sendMessage($message,$tokens);
		}
		
		$msg_alert = 'Pedido Finalizado com sucesso!';	   
	}
	
	$dados.= $msg_alert;
	
	
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
?>