<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php");
//
$text_periodo = array('.', '-', '/', '(', ')' , ' ');

$nome           = anti_sql_injection(strip_tags(trim($_REQUEST['nome'])));
$tel1           = anti_sql_injection(strip_tags(trim($_REQUEST['tel1'])));
$tel2           = anti_sql_injection(strip_tags(trim($_REQUEST['tel2'])));
$data_nasc      = anti_sql_injection(strip_tags(trim($_REQUEST['data_nasc'])));
$email          = anti_sql_injection(strip_tags(trim($_REQUEST['email'])));
$logradouro_c   = anti_sql_injection(strip_tags(trim($_REQUEST['logradouro'])));
$numero_c       = anti_sql_injection(strip_tags(trim($_REQUEST['numero'])));
$cidade_c       = anti_sql_injection(strip_tags(trim($_REQUEST['cidade'])));
$estado_c       = anti_sql_injection(strip_tags(trim($_REQUEST['estado'])));
$bairro_c       = anti_sql_injection(strip_tags(trim($_REQUEST['bairro'])));
$complemento_c  = anti_sql_injection(strip_tags(trim($_REQUEST['complemento'])));
$referencia_c   = anti_sql_injection(strip_tags(trim($_REQUEST['referencia'])));
$sexo           = anti_sql_injection(strip_tags(trim($_REQUEST['sexo'])));
$servico        = anti_sql_injection(strip_tags(trim($_REQUEST['servico'])));
$experiencia    = anti_sql_injection(strip_tags(trim($_REQUEST['experiencia'])));

$tel2 = str_replace($text_periodo,'',$tel2);

$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
if(empty($nome) or empty($tel2) or empty($data_nasc) or empty($email)){
	$error = 1;
	$dados = "CAMPO OBRIGATORIO VAZIO!";
}elseif($token=='H424715433852'){
	
	$msg = "
		<html xmlns=\"https://www.w3.org/1999/xhtml\">
			<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
			</head>
			<body>
				Informações de Possivel Colaborador:<br />
				- Nome: ".$nome."<br />
				- Telefone: ".$tel1."<br />
				- Celular: ".$tel2."<br />
				- Data Nascimento: ".$data_nasc."<br />
				- Email: ".$email."<br />
				- Qual serviço você realiza?: ".$servico."<br />
				- Quanto tempo você tem nos serviços acima?: ".$experiencia."<br />
				<br /><br />--<br /><br />
				$prog_nome $prog_versao - <a href='$prog_link'>$prog_link</a>	
			</body>
		</html>";

	$mail = new PHPMailer();

	$mail->IsSMTP(); // Define que a mensagem será SMTP
	$mail->Host     = $f_smtp; // Endereço do servidor SMTP
	$mail->SMTPAuth = true; // Autenticação
	$mail->Username = $f_email; // Usuário do servidor SMTP
	$mail->Password = $f_senha; // Senha da caixa postal utilizada
	$mail->From     = $f_email; 
	$mail->FromName = $f_name;
	$mail->AddAddress('contato@pinkmajesty.com.br', 'Pink Majesty');
	$mail->AddBCC('otaviollneto@gmail.com', 'Otavio');
	$mail->SMTPDebug = 1;
	$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
	$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
	$mail->Subject = "Pink Majesty "; // Assunto da mensagem
	$mail->Body = $msg;
	$enviado = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	
	$error = 2;
	$dados = "INFORMAÇÕES ENVIADAS COM SUCESSO!";
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';
?>