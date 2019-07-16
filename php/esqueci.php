<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php");

$lEmail      = anti_sql_injection(strip_tags(trim($_REQUEST['login_email'])));
$token       = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){
	
	$bd        = anti_sql_injection('tb_login');
	$data_hoje = date('Y/m/d');
	
	$link_sis  = "http://www.igestaoweb.com.br/pinkmajesty";
	//$link_sis  = "http://192.168.1.200:8080/inova/dellas";
	
	$law = "email='$lEmail'";
		
	$sql       = "SELECT email FROM $bd WHERE $law AND ativo='ativo'";
	$resultado = mysql_query($sql) or die(mysql_error());
	$linha     = mysql_num_rows($resultado);
	$ln        = mysql_fetch_assoc($resultado);
		if ($linha>=1) 	{
			
			$cr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$max = strlen($cr)-1;
			$gera = null;
			for($i=0; $i < 16; $i++) {$gera .= $cr{mt_rand(0, $max)};}

			$gera = str_split($gera, 4);
			$tmp  = "$gera[0]$gera[1]$gera[2]$gera[3]";
			
			$email= $ln['email'];
			$nome = $ln['nome'];
				
			$updateSQL = "UPDATE $bd SET tmp='$tmp' WHERE email = '$email'";
			mysql_select_db($database_localhost, $localhost);
			$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
			
			//e-mail  			
			$msg = "
				<html xmlns=\"http://www.w3.org/1999/xhtml\">
				<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" /></head>

				<body>
					Ola,<br><br>
						Foi feito um pedido de redefinicao de senha.<br>
						Caso nao tenha pedido, desconsidere a mensagem e entre em contato com a Administracao.<br><br>
						Acesse o seguinte link e troque sua senha: <a href='$link_sis/asn.php?eml=$email&tmp=$tmp'>$link_sis/asnc.php?eml=$email&tmp=$tmp</a>
					<br><br>
						Dicas de Seguranca:<br>
						- Crie senhas dificeis de serem descobertas e as mude periodicamente;<br>
						- Nao Crie Senhas baseadas em sequencias;<br>
						- Nao use datas especiais, numero da placa do carro, nomes e afins<br>
						- Misture letras, simbolos especiais e numeros<br>
					<br><br>--<br><br>
					$prog_nome $prog_versao - <a href='$prog_link'>$prog_link</a>	
				</body>
				</html>";

				$msg_body = "$texto";
				$msg_assunto = "DELLAS DELIVERY - REQUERIMENTO DO SISTEMA";
				
				salvaLog("Esq Senha $bd $email",$email);

				$mail = new PHPMailer();

				$mail->IsSMTP(); // Define que a mensagem será SMTP
				$mail->Host     = "mail.pinkmajesty.com.br"; // Endereço do servidor SMTP
				$mail->SMTPAuth = true; // Autenticação
				$mail->Username = 'naoresponda@pinkmajesty.com.br'; // Usuário do servidor SMTP
				$mail->Password = 'dellas9mail'; // Senha da caixa postal utilizada
				$mail->From     = "naoresponda@pinkmajesty.com.br"; 
				$mail->FromName = "Pink Majesty ";
				$mail->AddAddress($email, $nome);
				$mail->SMTPDebug= 1;
				$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
				$mail->CharSet  = 'utf-8'; // Charset da mensagem (opcional)
				$mail->Subject  = "Dellas Beleza "; // Assunto da mensagem
				$mail->Body = $msg;
				$enviado = $mail->Send();
				$mail->ClearAllRecipients();
				$mail->ClearAttachments();
				
				if ($enviado) {
					$error = 1;
					$dados = "ENVIADO COM SUCESSO!";
				} else {
					
					$error = 2;
					$dados = 'Não foi possível enviar o e-mail. Informações do erro:  '.$mail->ErrorInfo;
				}
		}
	
}else{
	$error = 3;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","id_cliente":"'.$id_cliente.'"}';
?>