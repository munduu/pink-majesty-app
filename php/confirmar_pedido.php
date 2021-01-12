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
if ($token == 'H424715433852') {

	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = '$tipo' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while ($ln = mysql_fetch_assoc($resultado)) {
		$c_verif = md5($ln['email'] . $ln['senha']);
		if ($user == $c_verif) {
			$linha++;
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
	$forma_pg           = $ln_a['forma_pg'];
	$valor              = $ln_a['valor'];

	//VALIDA CUPOM DE DESCONTO
	if ($cod_cupom == 'pink1vez') {
		$updateSQL = "
		UPDATE tb_cliente SET desconto='0' WHERE id = '$id_cliente'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
		salvaLog("Alterar tb_cliente ID $id", $_SESSION['email']);
	}

	//SE TIVE PAGSEGURO E SITUAÇÃO FOR AGENDADO ATUALIZA CODIGO
	if ($situacao == 'AGENDADO' and $cod_pagseguro) {
		$ins_pag = "cod_pagseguro = '$cod_pagseguro', situacao='AGENDADO'";

		$updateSQL = "UPDATE tb_agenda SET $ins_pag WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
		salvaLog("Alterar tb_agenda ID $id", $_SESSION['email']);

		$dados .= $cod_pagseguro . '-' . $agenda . '-' . $situacao;

		//SE NÃO TIVER PAGSEGURO E SITUAÇÃO FOR AGENDADO ATUALIZA SITUAÇÃO
	} elseif ($situacao == 'AGENDADO' and !$cod_pagseguro) {
		$ins_pag = "";

		$updateSQL = "UPDATE tb_agenda SET situacao='AGENDADO' WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
		salvaLog("Alterar tb_agenda ID $id", $_SESSION['email']);

		$dados .= $cod_pagseguro . '-' . $agenda . '-' . $situacao;
	} elseif ($situacao == 'ACEITO' and $cod_pagseguro) {

		$ins_pag = "cod_pagseguro = '$cod_pagseguro'";

		$updateSQL = "UPDATE tb_agenda SET $ins_pag WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
		salvaLog("Alterar tb_agenda ID $id", $_SESSION['email']);

		$dados .= $cod_pagseguro . '-' . $agenda . '-' . $situacao;
	}

	$error = 2;
	$dados .= $cod_pagseguro . '-' . $agenda . '-' . $situacao;
	//----------CONSULTA O PAGSEGURO--------------------//
	$email_destino = $ln['email'];
	$pagseguro     = 'ok';

	//--VERIFICAÇÃO DE STATUS
	if ($situacao == 'AGENDADO' and $pagseguro == 'ok') {

		//AVISA O PROFISSIONAL	
		$sqlE = "SELECT email FROM tb_colaborador WHERE id = '$id_colaborador_des' ";
		$qrE  = mysql_query($sqlE) or die(mysql_error());
		$lnE  = mysql_fetch_assoc($qrE);
		$email_destino = $lnE['email'];

		$sqlT = "SELECT token_id FROM tb_login WHERE id_colaborador = '$id_colaborador_des' ";
		$qrT  = mysql_query($sqlT) or die(mysql_error());
		$lnT  = mysql_fetch_assoc($qrT);
		$tokens = $lnT['token_id'];

		$msg_alert = 'Pedido agendado com sucesso! Acesse pedidos Agendados para mais detalhes.';

		//PUSH
		if (!empty($tokens)) {
			$title    = "Pink Majesty";
			$message  = "Pagamento confirmado! Programe-se e não se atrase!";
			sendMessage($message, $tokens);
		}

		//NOTIFICAÇÃO DE ALTERAÇÃO PROFISSIONAL	
		$updateSQL = "
		UPDATE tb_login SET notif='1' WHERE id_colaborador = '$id_colaborador_des'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

		require_once('notif_agendado_email.php');
	}


	if ($situacao == 'CONCLUIDO') {

		/*
		 ENVIAR REQUEST DE CAPTURAR O RESTANTE.
		*/
		$sql_forma_pg         	= "SELECT * FROM `tb_cartoes` WHERE `id` = '$forma_pg'";
		$resultado_forma_pg   	= mysql_query($sql_forma_pg) or die(mysql_error());
		$linha_forma_pg       	= mysql_num_rows($resultado_forma_pg);
		if ($linha_forma_pg > 0) {
			$ln_forma_pg          	= mysql_fetch_assoc($resultado_forma_pg);
			//die(var_dump($json));
			$valor  = floatval(str_replace(",",".",$valor));
			$valor  = intval(100*$valor);
			$amount = $valor - 200.00;
			// die(var_dump(array(
			// 	'action' => 'chargeWithCard',
			// 	'MerchantOrderId' => $agenda,
			// 	'Name' => $ln_forma_pg['nome_impresso'],
			// 	'CardNumber' => $ln_forma_pg['numero'],
			// 	'Holder' => $ln_forma_pg['nome_impresso'],
			// 	'ExpirationDate' => $ln_forma_pg['mes_val'].'/'.$ln_forma_pg['ano_val'],
			// 	'SecurityCode' => $ln_forma_pg['cod_seg'],
			// 	'Brand' => $bandeira,
			// 	'Amount' => $amount
			// )));
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://igestaoweb.com.br/pinkmajesty/app_new/php/cielo_app/api.php',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => 
				array(
					'action' => 'chargeWithCard',
					'MerchantOrderId' => $agenda,
					'Name' => $ln_forma_pg['nome_impresso'],
					'CardNumber' => $ln_forma_pg['numero'],
					'Holder' => $ln_forma_pg['nome_impresso'],
					'ExpirationDate' => "$ln_forma_pg[mes_val]/$ln_forma_pg[ano_val]",
					'SecurityCode' => $ln_forma_pg['cod_seg'],
					'Amount' => $amount
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
			$response = substr($response,3);
			$response = trim($response);
			$json = json_decode($response, TRUE);
			if(empty($json)){
				echo $response;
				die(json_last_error_msg());
			}
			if (!empty($json['Payment']['ReturnCode'])) {
				if (($json['Payment']['ReturnCode'] == "6") || ($json['Payment']['ReturnCode'] == "4")) {
					$message_pagamento = "Pagamento processado com sucesso";
				} else {
					$message_pagamento = "Erro ao processar o pagamento x1";
				}
			} else {
				$message_pagamento = "Erro ao processar o pagamento x2";
			}
		} else {
			$message_pagamento = "Erro ao processar o pagamento x3";
		}


		//AVISA O CLIENTE PARA AVALIAR

		$sqlE = "SELECT email FROM tb_cliente WHERE id = '$id_cliente_des' ";
		$qrE  = mysql_query($sqlE) or die(mysql_error());
		$lnE  = mysql_fetch_assoc($qrE);
		$email_destino = $lnE['email'];

		$sqlT = "SELECT token_id FROM tb_login WHERE id_cliente = '$id_cliente_des' ";
		$qrT  = mysql_query($sqlT) or die(mysql_error());
		$lnT  = mysql_fetch_assoc($qrT);
		$tokens = $lnT['token_id'];

		$updateSQL = "UPDATE tb_agenda SET situacao='CONCLUIDO' WHERE id = '$agenda'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
		salvaLog("Concluir tb_agenda ID $agenda", $_SESSION['email']);

		//PUSH
		if (!empty($tokens)) {

			$title    = "Pink Majesty";
			$message  = "Pedido #$agenda concluido ... avalie o atendimento! $message_pagamento";
			sendMessage($message, $tokens);
		}

		$msg_alert = "Pedido Finalizado com sucesso! " . $message_pagamento;
	}

	$dados .= $msg_alert;
} else {
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"' . $error . '","dados":"' . $dados . '","linha":"' . $linha . '"}';
