<? 

require_once("phpmail/class.phpmailer.php");


$msg .='

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

	<html>

	  <head>   

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<title>Pink Majesty <?=$titulo?></title>

		<style type="text/css">

		  /* Default CSS */

		  body,#body_style {margin: 0; padding: 0; background: #f6f6f6; font-size: 14px; color: #313a42;}

		  a {color: #09c;}

		  a img {border: none; text-decoration: none;}

		  table, table td {border-collapse: collapse;}

		  td, h1, h2, h3 {font-family: arial, sans-serif; color: #313a42;}

		  h1, h2, h3, h4 {color: #313a42 !important; font-weight: normal; line-height: 1.2;}

		  h1 {font-size: 24px;}

		  h2 {font-size: 18px;}

		  h3 {font-size: 16px;}

		  p {margin: 0 0 1.6em 0;}

		  

		  /* Preheader and webversion */

		  .igestaow_taskgo_geral {background-color: #ff5eec; border-bottom: 2px solid #dd44cb;}

		  .igestaow_taskgo_geralContent,.webversion,.webversion a {color: white; font-size: 10px;}

		  .igestaow_taskgo_geralContent{width: 440px;}

		  .igestaow_taskgo_geralContent,.webversion {padding: 5px 10px;}

		  .webversion {width: 200px; text-align: right;}

		  .webversion a {text-decoration: underline;}

		  .webversion,.webversion a {color: #ffffff; font-size: 10px;}

		  

		  /* Top header */

		  .topHeader {background: #ffffff;}

		  

		  /* Logo (branding) */

		  .logoContainer {padding: 10px 0px; width: 320px;}

		  .logoContainer a {color: #ffffff;}

		  

		  /* Whitespace */

		  .whitespace {font-family: 0px; line-height: 0px;}

		  

		  /* Big header image */

		  .featuredImage img{display: block;}

		  .featuredBackground {background-color: #ffffff;}

		  

		  /* Button */

		  .button {padding: 10px 10px 10px 10px; text-align: center; background-color: #5e6867}

		  .button a {color: #ffffff; text-decoration: none; display: block; font-size: 13px;}

		  

		  /* Section */

		  .sectionMainTitle{font-family: Tahoma, sans-serif; font-size: 14px; font-weight: bold; padding: 0px 0px 5px 0;}

		  

		  /* An article */

		  .sectionArticleTitle, .sectionMainTitle {color: #289b99;}

		  .sectionArticleTitle {font-size: 12px; font-weight: bold; padding: 10px 5px 0px 5px;}

		  .sectionArticleContent {font-size: 10px; line-height: 12px; padding: 0px 5px 10px 5px;}

		  .sectionArticleImage {padding: 0px 0px 0px 0px;}

		  .sectionArticleImage img {height: auto; -ms-interpolation-mode: bicubic; display: block;}

		  

		  /* Footer */

		  .footer {background-color: #edc4e4;}

		  .footNotes {padding: 0px 20px 0px 20px;}

		  .footNotes a {color: #ffffff; font-size: 14px;}

		  

		  /* Article card */

		  .card {background-color: #ffffff;}

		  

		  

		  /* CSS for specific screen width(s) */

		  @media only screen and (max-width: 480px) {

			body,table,td,p,a,li,blockquote {-webkit-text-size-adjust:none !important;}

			  body[yahoofix] table {width: 100% !important;}

			  body[yahoofix] .featuredImage img { width: 100%; height: auto !important; max-width: 100% !important;}

			  body[yahoofix] .webversion {display: none; font-size: 0; max-height: 0; line-height: 0; mso-hide: all;}

			  body[yahoofix] .logoContainer {text-align: center;}

			  body[yahoofix] .sectionArticleTitle, body[yahoofix] .sectionArticleContent {text-align: center; padding:0 5px 10px 5px;}

			  body[yahoofix] .sectionMainTitle, body[yahoofix] .sectionMainContent {text-align: center;}

			  body[yahoofix] .buttonContainer {padding: 0px 20px 0px 20px;}

			  body[yahoofix] .column {float: left; width: 100%; margin-bottom: 30px;}

			  body[yahoofix] .card {padding: 20px 0px;}

			}

		</style>

		

		<script type="text/javascript" src="../igestao/function/function.js"></script>

		<style>

        .loader {

            position: fixed;

            left: 0px;

            top: 0px;

            width: 100%;

            height: 100%;

            z-index: 9999;

            background: url("../igestao/img/Preloader_2.gif") 50% 50% no-repeat rgba(255, 255, 255, 1);

        }

        </style>

		

	  </head>

	  <body style="background:#f6f6f6;" yahoofix>

	  <div class="div-ajax-carregamento-pagina"><div class="loader"></div></div>

		<span id="body_style" style="display:block">

		  <table class="igestaow_taskgo_geral" cellpadding="0" cellspacing="0" width="100%">

			<tr>

			  <td>

				<table border="0" cellpadding="0" cellspacing="0" summary="" width="640" align="center">

				  <tr>

					<td class="igestaow_taskgo_geralContent"><?=$titlo?></td>

					<td class="webversion">Sem Imagem? <a href="#" title="Ver versão Web">Ver versão Web</a></td>

				  </tr>

				</table>

			  </td>

			</tr>

		  </table>

		  <table style="background:#edc4e4;" border="0" cellspacing="0" cellpadding="0" width="100%" summary="" class="topHeader">

			<tr>

			  <td>

				<table border="0" cellspacing="0" cellpadding="0" width="640" align="center" summary="">

				  <tr>

					<td class="logoContainer">

					  <a href="/" title="Lorem logo">

						<img class="logo" src="https://igestaoweb.com.br/pinkmajesty/img/logodella" width="100" alt="Lorem logo" />

					  </a>

					</td>

				  </tr>

				</table>

			  </td>

			</tr>

		  </table>

		  

		  <table border="0" cellpadding="0" cellspacing="0" width="100%" summary="" class="featuredBackground">

			<tr>

			  <td valign="top">

				<table border="0" cellpadding="0" cellspacing="0" width="640" align="center" summary="">

				  <tr>

					<td class="columnFeatured" valign="top">

				  <tr><td class="featuredImage"><h1 align="center" style="font-size:48px;">Pedido Agendado!</h2></td></tr>

					</td>

				  </tr>

				</table>

			  </td>

			</tr>

		  </table>

		  

		   <!-- A section -->

	<table border="0" cellspacing="0" cellpadding="0" summary="" width="100%">

	';

		$msg .='

						<table>

							<tr>

								<td>
								     <p>Pedido agendado!.</p>
									 <p>Código:'.$agenda.'</p>
									<br />
									<p>Acesse agora o aplicativo Pink Majesty, </p>
									<p>clique em "Agenda" e no filtro Agendados, se programe e não se atrase!</p>
									<p>Após realizar o atendimento, lembre-se de concluir o pedido pelo aplicativo!</p>
								    <p style="color:rgb(136,136,136);font-family:verdana,geneva,sans-serif;font-size:12.8px">*Esta e uma mensagem automatica, nao responda*</p>
									<br />					

						';			

						$f_name ='Pink Majesty ';
						$msg .='

						<p style="color:rgb(136,136,136);font-family:verdana,geneva,sans-serif;font-size:12.8px">

						Pink Majesty  <br />			

						Site: www.pinkmajesty.com <br />

						E-mail: contato@pinkmajesty.com </p>

							</td>

						</tr>				

						';		

			

					$msg .='

					</table>

					';

					$mail = new PHPMailer();
					$mail->IsSMTP(); // Define que a mensagem será SMTP
					$mail->Host     = $f_smtp; // Endereço do servidor SMTP
					$mail->SMTPAuth = true; // Autenticação
					$mail->Port     = 587;

					$mail->Username = $f_email; // Usuário do servidor SMTP
					$mail->Password = $f_senha; // Senha da caixa postal utilizada
					$mail->From     = $f_email;  
					$mail->FromName = $f_name;

					$mail->AddAddress($email_destino); 

					$mail->AddBCC('borishcs@gmail.com', 'Boris');
					$mail->AddBCC('iinf@pinkmajesty.com', 'Pink');
					
					
					$mail->SMTPDebug = 1;
					$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
					$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
					$mail->Subject  = "Pedido 0".$agenda." foi agendado, pagamento confirmado!"; // Assunto da mensagem
					$mail->Body = $msg_erro.$msg;//$msg;

					//$mail->AddAttachment("$arquivo", "$id_conta.pdf");
					$enviado = $mail->Send();
					$mail->ClearAllRecipients();
					$mail->ClearAttachments();	

$msg .='</table>

		  <!-- End a section -->

	

		  <table border="0" cellspacing="0" cellpadding="0" width="100%" summary="" class="footer">

			<tr>

			  <td>

				<table border="0" cellspacing="0" cellpadding="0" width="640" align="center" summary="">

				  <tr><td class="whitespace" height="10">&nbsp;</td></tr>

				  <tr>

					<td class="footNotes" align="left">

					  <a href="#" title="Contato">Contato</a>

					</td>

					<td class="footNotes" align="right">

					  <a href="https://www.facebook.com/sportbro" title="Facebook"><img src="https://sportbro.com.br/sportbro/img/faceb.png" width="29" alt="Facebook" /></a>

					</td>

				  </tr>

				  <tr><td class="whitespace" height="10">&nbsp;</td></tr>

				</table>

			  </td>

			</tr>

		</table>

		</span>

	  </body>

	</html>

'

?>