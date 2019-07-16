<?

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php");

$data_atual = date("Y/m/d");
$hora_atual = date("H:i");


	$sql_a 		= "SELECT * FROM tb_agenda WHERE situacao='AGENDADO' AND aviso='0' ORDER BY data, hora_ini ";
	$resultado_a= mysql_query($sql_a) or die(mysql_error());
	$linha_a    = mysql_num_rows($resultado_a);

	if($linha_a > 0){

		$x=1; $verif=0;  $tokens =""; $destinatarios = array();

		while($ln_a = mysql_fetch_assoc($resultado_a)){

			$id	 			= $ln_a['id'];
			$id_cliente 	= $ln_a['id_cliente'];
			$data 			= $ln_a['data'];
			$servico 		= $ln_a['servico'];
			$id_colaborador	= $ln_a['id_colaborador'];
			$hora_ini 		= $ln_a['hora_ini'];
			$hora_fim		= $ln_a['hora_fim'];
			$status	    	= $ln_a['status'];
			$situacao		= $ln_a['situacao'];
			$status_tempo 	= $ln_a['status_tempo'];
			$aviso  		= $ln_a['aviso'];
			$data_agend  	= $ln_a['data_agend'];
			$hora_agend  	= $ln_a['hora_agend'];
			

			$horat_ini 	= strtotime($hora_ini);
			$horat_fim 	= strtotime($hora_fim);
			
			$hora_fim1 = date("Y/m/d H:i",  strtotime( "-2 hours", strtotime($data_agend.' '.$hora_agend) ));
			
			
			//inicio da verificação
			$notif = 'nao';
			$cancela = 'nao';
			
			echo "Data e hora Agendado: ".$data_agend.' '.$hora_agend;
			echo "   Data e hora 2 horas antes: ".$hora_fim1."<br/>";
			
			
			
			//VEJO SE FAZ 2 HORAS OU MENOS
			if(strtotime($data_atual.' '.$hora_atual) > strtotime($hora_fim1)) {
				   				 
				$notif = 'sim';
				
			}else{ $notif = 'nao';  }		
			
			
			if($notif=='sim'){
				     
					 		// TOKEN COLABORADOR
					       $sql_c 		= "SELECT token_id FROM tb_login WHERE id_colaborador='$id_colaborador' ";
					       $resultado_c= mysql_query($sql_c) or die(mysql_error());
					  	   $ln_c = mysql_fetch_assoc($resultado_c);
					 					
										 if(!empty($ln_c['token_id'])){
												
												echo $token = $ln_c['token_id'];
												//echo $ln_c2['token_id'].', <br/>';	
												
												 //echo $tokens;
												$title    = "Dellas Beleza Delivery";
												$message  = 'Pedido '.$id.' agendado para hoje, não se atrase!'; 
												sendMessage($message,$token);										
											}
											
						 //TOKEN CLIENTE
						   $sql_c2 		= "SELECT token_id FROM tb_login WHERE id_cliente='$id_cliente' ";
					       $resultado_c2= mysql_query($sql_c2) or die(mysql_error());
					  	   $ln_c2		= mysql_fetch_assoc($resultado_c2);
					 					
										 if(!empty($ln_c2['token_id'])){
												
												echo $token = $ln_c2['token_id'];
												//echo $ln_c2['token_id'].', <br/>';												
												 //echo $tokens;
												$title    = "Dellas Beleza Delivery";
												$message  = 'Pedido '.$id.' agendado para hoje!'; 
												sendMessage($message,$token);
												
											}
						 
						 
						 
						 
				   $sql_c 		= "UPDATE tb_agenda SET aviso = '1' WHERE id='$id' ";
				   $resultado_c = mysql_query($sql_c) or die(mysql_error());
				   
				}


		}
	}

		/*
		 if(!empty($tokens)){
			
			 //echo $tokens;
			$title    = "Dellas Beleza Delivery";
			$message  = 'Lembrete de Serviço Agendado Hoje !'; 
			sendMessage($message,$token_id_user);
			
			echo "aqui notif";
			
		 }*/
							

	
			
?>
