<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php"); 

//

$user       = anti_sql_injection(strip_tags(trim($_REQUEST['user'])));
$agenda 	= anti_sql_injection(strip_tags(trim($_REQUEST['agenda'])));
$avaliacao  = anti_sql_injection(strip_tags(trim($_REQUEST['avaliacao'])));
$descricao  = anti_sql_injection(strip_tags(trim($_REQUEST['descricao'])));
$data 		= date('Y/m/d');

$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
/*if(empty($user) or empty($servico) or empty($local) or empty($data) or empty($hora) or empty($forma_pg)){
	$error = 1;
	$dados = "CAMPO OBRIGATORIO VAZIO!";
}else*/if($token=='H424715433852'){
	
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
	if($linha == 0){
		$error = 3;
		$dados = "FAÇA LOGIN PRIMEIRO!";
	}else{		
		$insertSQL = 
		"INSERT INTO tb_avaliacao (id_agenda, data, avaliacao, descricao) 
		VALUES ('$agenda', '$data', '$avaliacao', '$descricao')"
		;
		mysql_select_db($database_localhost, $localhost);
		$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error("erro!"));
		salvaLog("Add tb_avaliacao",$_SESSION['nomeu']);
		
		$error = 2;
		$dados = "Avaliado com sucesso, volte sempre!";
		
		
	/*		
			$mail = new PHPMailer();
		
						$f_email='naoresponda@dellasbeleza.com.br';
						$f_name ='Dellas Beleza ';
						$f_smtp ='mail.dellasbeleza.com.br';
						$f_senha='dellas9mail';
	 	$msg = "
			<html xmlns=\"https://www.w3.org/1999/xhtml\">
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
				</head>
				<body>
					Pedido $agenda avaliado e concluido com sucesso!:<br />
				
				</body>
			</html>";
			
		$mail->IsSMTP(); // Define que a mensagem será SMTP
		$mail->Host     = $f_smtp; // Endereço do servidor SMTP
		$mail->SMTPAuth = true; // Autenticação
		$mail->Username = $f_email; // Usuário do servidor SMTP
		$mail->Password = $f_senha; // Senha da caixa postal utilizada
		$mail->From     = $f_email; 
		$mail->FromName = "Dellas Beleza";
		$mail->AddAddress('contato@dellasbeleza.com.br', 'Contato');
		$mail->AddBCC('borishcs@gmail.com', 'Boris');
		$mail->SMTPDebug = 1;
		$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
		$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
		$mail->Subject = "Pedido $agenda avaliado"; // Assunto da mensagem
		$mail->Body = $msg;
		$enviado = $mail->Send();
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();*/

	}
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
?>