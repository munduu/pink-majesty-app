<?php

		$sql_c2 		= "SELECT id,token_id,email FROM tb_login WHERE id_cliente='$id_cliente' ";
		$resultado_c2	= mysql_query($sql_c2) or die(mysql_error());
		$ln_c2 			= mysql_fetch_assoc($resultado_c2);
			
			//$id            = $ln_c2['id'];
			$token_id_user = $ln_c2['token_id'];
			$email_cliente = $ln_c2['email'];
			
			$title    = "Pink Majesty";
			$message  = 'Que pena parece que nenhum profissional esta disponivel neste horário =( , tente novamente!'; 
			sendMessage($message,$token_id_user);
			
			
			$titulo_email = "=( Pedido $id Cancelado.";
			
			$corpo_email ='<p>Que pena, infelizmente nenhuma de nossas profissionais pode atender neste horário, </p>
						   <p>tente criar um novo pedido em um novo horário ou entre em contato conosco pelo chat!</p>
						   <p style="color:rgb(136,136,136);font-family:verdana,geneva,sans-serif;font-size:12.8px">*Esta e uma mensagem automatica, nao responda*</p>';
			$destino = $email_cliente;
			$assunto = "Pedido $id cancelado";
			
			
			
			require_once('email_geral.php');

		
?>