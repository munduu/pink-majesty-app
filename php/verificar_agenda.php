<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php");

$user       = anti_sql_injection(strip_tags(trim($_REQUEST['user'])));
$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
/*
$servico 	= anti_sql_injection(strip_tags(trim($_REQUEST['id_serv'])));
$tipo_resid	= anti_sql_injection(strip_tags(trim($_REQUEST['tipo_resid'])));
$detalhes 	= anti_sql_injection(strip_tags(trim($_REQUEST['detalhes'])));
$opcionais 	= anti_sql_injection(strip_tags(trim($_REQUEST['opcionais'])));
$data     	= anti_sql_injection(strip_tags(trim($_REQUEST['data'])));
$hora  		= anti_sql_injection(strip_tags(trim($_REQUEST['hora'])));
$duracao    = anti_sql_injection(strip_tags(trim($_REQUEST['duracao'])));
$id_colab   = anti_sql_injection(strip_tags(trim($_REQUEST['id_colab'])));
$id_endereco= anti_sql_injection(strip_tags(trim($_REQUEST['id_endereco'])));
$id_cartao	= anti_sql_injection(strip_tags(trim($_REQUEST['id_cartao'])));
$preco		= anti_sql_injection(strip_tags(trim($_REQUEST['preco'])));
*/
$data_atual = date("Y/m/d");
$hora_atual = date("H:i");
//data_agend = '$data_atual' AND 
$sql  = "SELECT * FROM tb_agenda WHERE status = 'ATIVO' AND situacao = 'PEDIDO' ORDER BY id";
$qr   = mysql_query($sql) or die (mysql_error());
$linha = mysql_num_rows($qr);
while($ln = mysql_fetch_assoc($qr)){
	$id				= $ln['id'];
	$data_agend 	= $ln['data_agend'];
	$hora_agend		= $ln['hora_agend'];
	$id_cliente 	= $ln['id_cliente'];
	$id_colaborador = $ln['id_colaborador'];
	
	$sql_cl= "SELECT * FROM tb_login WHERE id_cliente = '$id_cliente'";
	$qr_cl = mysql_query($sql_cl) or die (mysql_error());
	$ln_cl = mysql_fetch_assoc($qr_cl);
	
	$nome_cliente  = $ln_cl['nome'];
	$email_cliente = $ln_cl['email'];
	
	$sql_col= "SELECT * FROM tb_login WHERE id_colaborador = '$id_colaborador'";
	$qr_col = mysql_query($sql_col) or die (mysql_error());
	$ln_col = mysql_fetch_assoc($qr_col);
	
	$nome_colaborador  = $ln_col['nome'];
	$email_colaborador = $ln_col['email'];
	
	$hora_fim1 = date("Y/m/d H:i",  strtotime( "+15 minute", strtotime($data_agend.' '.$hora_agend) ));
	$hora_fim2 = date("Y/m/d H:i",  strtotime( "+30 minute", strtotime($data_agend.' '.$hora_agend) ));
	
	/*
	echo 'Hora Atual > Hora do Agendamento';
	echo '<br />';
	echo $data_atual.' '.$hora_atual.' > '.$hora_fim;
	echo '<br />';
	echo strtotime($data_atual.' '.$hora_atual).' > '.strtotime($hora_fim);
	*/
	if(strtotime($data_atual.' '.$hora_atual) > strtotime($hora_fim1)){
		$updateSQL = "UPDATE tb_agenda SET status_tempo='1' WHERE id = '$id'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
	}
	if(strtotime($data_atual.' '.$hora_atual) > strtotime($hora_fim2)){
		/*
		echo '<br />';
		echo 'Hora Atual maior';
		echo '<br />';
		*/
		$updateSQL = "UPDATE tb_agenda SET situacao='CANCELADO' WHERE id = '$id'";
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
			
		//e-mail
		/*
		<br><br>
		Acesse o seguinte link e troque sua senha: <a href='$link_sis/asn.php?eml=$email&tmp=$tmp'>$link_sis/asnc.php?eml=$email&tmp=$tmp</a>
		*/  			
		$msg = "
		<html xmlns=\"https://www.w3.org/1999/xhtml\">
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" /></head>
		<body>
			Ola,<br><br>
				O pedido feito em, ".$data_atual."as".$hora_atual." não foi aceito pelo prestador de serviço e por isso foi Cancelado.
			<br><br>--<br><br>
			$prog_nome $prog_versao - <a href='$prog_link'>$prog_link</a>	
		</body>
		</html>";

		$msg_body = "$texto";
		$msg_assunto = "DELLAS BELEZA DELIVERY - CANCELAMENTO DE PEDIDO";
				
		//salvaLog("Esq Senha tb_agenda $email",$email);

		$mail = new PHPMailer();

		$mail->IsSMTP(); // Define que a mensagem será SMTP
		$mail->Host     = "mail.igestaoweb.com.br"; // Endereço do servidor SMTP
		$mail->SMTPAuth = true; // Autenticação
		$mail->Username = 'allure@igestaoweb.com.br'; // Usuário do servidor SMTP
		$mail->Password = 'allure9s3rv3r'; // Senha da caixa postal utilizada
		$mail->From     = "allure@igestaoweb.com.br"; 
		$mail->FromName = "DELLAS BELEZA DELIVERY";
		//$mail->AddAddress($email_cliente, $nome_cliente);
		$mail->AddAddress('mardenmoreira1@gmail.com', 'Marden');
		$mail->SMTPDebug= 1;
		$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
		$mail->CharSet  = 'utf-8'; // Charset da mensagem (opcional)
		$mail->Subject  = "DELLAS BELEZA DELIVERY"; // Assunto da mensagem
		$mail->Body = $msg;
		$enviado = $mail->Send();
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		
		$msg2 = "
		<html xmlns=\"https://www.w3.org/1999/xhtml\">
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" /></head>
		<body>
			Ola,<br><br>
				O pedido feito em, ".$data_atual."as".$hora_atual." não foi aceito pelo prestador de serviço e por isso foi Cancelado.
			<br><br>--<br><br>
			$prog_nome $prog_versao - <a href='$prog_link'>$prog_link</a>	
		</body>
		</html>";

		$msg_body = "$texto";
		$msg_assunto = "DELLAS BELEZA DELIVERY - CANCELAMENTO DE PEDIDO";
				
		//salvaLog("Esq Senha tb_agenda $email",$email);

		$mail = new PHPMailer();

		$mail->IsSMTP(); // Define que a mensagem será SMTP
		$mail->Host     = "mail.igestaoweb.com.br"; // Endereço do servidor SMTP
		$mail->SMTPAuth = true; // Autenticação
		$mail->Username = 'allure@igestaoweb.com.br'; // Usuário do servidor SMTP
		$mail->Password = 'allure9s3rv3r'; // Senha da caixa postal utilizada
		$mail->From     = "allure@igestaoweb.com.br"; 
		$mail->FromName = "DELLAS BELEZA DELIVERY";
		//$mail->AddAddress($email_colaborador, $nome_colaborador);
		$mail->AddAddress('mardenmoreira1@gmail.com', 'Marden');
		$mail->SMTPDebug= 1;
		$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
		$mail->CharSet  = 'utf-8'; // Charset da mensagem (opcional)
		$mail->Subject  = "DELLAS BELEZA DELIVERY"; // Assunto da mensagem
		$mail->Body = $msg2;
		$enviado = $mail->Send();
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
			
		/*
		if ($enviado) {
			echo "ENVIADO COM SUCESSO!";
		} else {
			echo 'Não foi possível enviar o e-mail. Informações do erro:  '.$mail->ErrorInfo;
		}
		*/
	}
	/*else{
		echo '<br />';
		echo 'Hora Atual menor';
	}*/
}
	
/*	
$dur_p = explode(':',$duracao);

$horas = $dur_p[0] * 60;
$duracao = ($horas + $dur_p[1]);
	
$hora_fim = date("H:i",  strtotime( "+".$duracao." minute", strtotime($hora) ));

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
*/
?>