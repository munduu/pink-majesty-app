<?

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php");

$data_atual = date("Y/m/d");
$hora_atual = date("H:i");


   echo $sql_a 		= "SELECT * FROM tb_agenda WHERE situacao='PEDIDO' AND status='ativo' ORDER BY data, hora_ini ";
	$resultado_a= mysql_query($sql_a) or die(mysql_error());
	$linha_a    = mysql_num_rows($resultado_a);
	salvaLog("CRON NOTIF PEDIDO",'AUTOMATICO');

	if($linha_a > 0){

		$x=1; $verif=0;  $tokens =""; $destinatarios = array();

		while($ln_a = mysql_fetch_assoc($resultado_a)){

			$id	 			= $ln_a['id'];
			$id_cliente 	= $ln_a['id_cliente'];
			$data 			= $ln_a['data'];
			$servico 		= $ln_a['servico'];
			$local 			= $ln_a['local'];
			$hora_ini 		= $ln_a['hora_ini'];
			$hora_fim		= $ln_a['hora_fim'];
			$status	    	= $ln_a['status'];
			$situacao		= $ln_a['situacao'];
			$status_tempo 	= $ln_a['status_tempo'];
			$envio  		= $ln_a['envio'];
			$data_agend  		= $ln_a['data_agend'];
			$hora_agend  		= $ln_a['hora_agend'];
			

			$horat_ini 	= strtotime($hora_ini);
			$horat_fim 	= strtotime($hora_fim);
			
			$hora_fim1 = date("Y/m/d H:i",  strtotime( "+10 minute", strtotime($data_agend.' '.$hora_agend) ));
			$hora_fim2 = date("Y/m/d H:i",  strtotime( "+60 minute", strtotime($data_agend.' '.$hora_agend) ));	
			
			
			//inicio da verificação
			$notif = 'nao';
			$cancela = 'nao';
			
			echo "Data e hora Agendado: ".$data_agend.' '.$hora_agend;
			echo " </br>";
			echo "   Data e hora 15: ".$hora_fim1;
			echo " </br>";
			echo "   Data e hora 50: ".$hora_fim2;
			echo " </br>";
			echo "Data Solicitada: ".$data;
			echo " </br>";
			
			
			//VEJO SE FAZ MENOS DE 30 MINUTOS
			if(strtotime($data_atual.' '.$hora_atual) < strtotime($hora_fim2)) {
				   
				   //VEJO SE FAZ MENOS DE 15 MINUTOS E AINDA NAO ENVIOU
				   if((strtotime($data_atual.' '.$hora_atual) < strtotime($hora_fim1)) and ( $envio == 0 ) ){
					     
						 $notif = 'sim';
					
					  }
					  
					//VEJO SE FAZ MENOS DE 30 MINUTOS E SE SO ENVIOU 1 VEZ
				   if((strtotime($data_atual.' '.$hora_atual) > strtotime($hora_fim1)) and (strtotime($data_atual.' '.$hora_atual) < strtotime($hora_fim2)) and ( $envio == 1 )  ){
					     
						 $notif = 'sim';
					
					  }
				   
				
			}else{ $notif = 'nao'; $cancela = 'sim';  }		

				
			
						
			//atualizo o envio
			if(($notif =='sim') ){
			
				$sql_c 		= "UPDATE tb_agenda SET envio = envio+1 WHERE id = '$id' ";
				$resultado_c= mysql_query($sql_c) or die(mysql_error());
					
					
					//if($id_colaborador)

					$sql_e 		= "SELECT * FROM tb_enderecos WHERE id='$local' ORDER BY id ASC";
					$resultado_e= mysql_query($sql_e) or die(mysql_error());
					$ln_e 		= mysql_fetch_assoc($resultado_e);
			

					 $logradouro 	= $ln_e['logradouro'];
					$numero 		= $ln_e['numero'];
					$complemento 	= $ln_e['complemento'];
					$bairro 		= $ln_e['bairro'];
					$cidade 		= $ln_e['cidade'];
					$estado 		= $ln_e['estado'];
					$referencia 	= $ln_e['referencia'];

					
					$sql_b 		= "SELECT * FROM tb_bairro WHERE id = '$bairro' ORDER BY id ASC";
					$resultado_b= mysql_query($sql_b) or die(mysql_error());
					$ln_b 		= mysql_fetch_assoc($resultado_b);	
					$nome_bairro 	= $ln_b['nome'];
					
					$sql_ci 		= "SELECT * FROM tb_municipios WHERE id = '$cidade' ORDER BY id ASC";
					$resultado_ci	= mysql_query($sql_ci) or die(mysql_error());
					$ln_ci 			= mysql_fetch_assoc($resultado_ci);
					$cidade 	= $ln_ci['nome'];

					
					$sql_s 		= "SELECT * FROM tb_produto WHERE id = '$servico' ORDER BY id ASC";
					$resultado_s= mysql_query($sql_s) or die(mysql_error());
					$ln_s 		= mysql_fetch_assoc($resultado_s);
					$nome_servico = $ln_s['titulo'];

					
					$sql_cl 		= "SELECT * FROM tb_cliente WHERE id = '$id_cliente' ORDER BY id ASC";
					$resultado_cl	= mysql_query($sql_cl) or die(mysql_error());
					$ln_cl 			= mysql_fetch_assoc($resultado_cl);			

					$nome_cliente = $ln_cl['nome'];

					
					$sql_c2 		= "SELECT tb_colaborador.*, tb_login.token_id FROM tb_colaborador, tb_login WHERE tb_colaborador.status = 'ATIVO'  
										AND tb_colaborador.id=tb_login.id_colaborador ORDER BY id ASC";
					$resultado_c2	= mysql_query($sql_c2) or die(mysql_error());
					while($ln_c2 			= mysql_fetch_assoc($resultado_c2)){
						//echo 1;
						$areas 	= $ln_c2['area'];

						$area = explode(",", $areas);
						
						$libera = $_REQUEST['libera']; //libera para todos

						for($y=0; !empty($area[$y]); $y++){
								//echo $bairro ." <=> ";
								//echo $area[$y]." <br/>";
							//VERIFICA SE O BAIRRO É PROXIMO------------------------------------------------//
							if($bairro == $area[$y] or $libera==1){
								
								//SE SIM, VERIFICA SE ELE REALIZA AQUELE SERVIÇO---------------------------//
								$servico_c = explode(",",  $ln_c2['servico']);
								
								for($x=0; !empty($servico_c[$x]); $x++){
									 
									 //echo $servico ." <==> ";
									 //echo $servico_c[$x]." <br/>";
									if($servico == $servico_c[$x]){
										
										
									 //echo "entrei "." ID:".$ln_c2['id']." token".$ln_c2['token_id'];
											// SE SIM, VERIFICA SE ELE TA OCUPADO --------------------------//
											echo $sql_a2 		= "SELECT * FROM tb_agenda WHERE id_colaborador = '$ln_c2[id]' AND data='$data' AND status='ativo' ORDER BY data, hora_ini ASC";
											$resultado_a2	= mysql_query($sql_a2) or die(mysql_error());						
											while($ln_a2 	= mysql_fetch_assoc($resultado_a2)){
								
												$hora_ini2 	= $ln_a2['hora_ini'];				
												$hora_fim2	= $ln_a2['hora_fim'];
												$horat_ini2 	= strtotime($hora_ini2);					
												$horat_fim2 	= strtotime($hora_fim2);
												
											
												if(($horat_ini >= $horat_ini2 and $horat_ini <= $horat_fim2) or ($hora_ini <= $hora_ini2 and $hora_fim > $hora_ini2)){
													$block=1;
												}else{
													$block = 0;
												}
												$id_colaborador = $ln_c2['id'];
												$hoje = date("Y-m-d H:i:s", strtotime($ln_a2['data']." ".$ln_a2['hora_ini']));
												$sql_bloqueio_agenda ="SELECT * FROM `tb_horario_colaborador` WHERE `bloqueado_inicio`> '$hoje' AND `bloqueado_fim`< '$hoje' AND status='0' AND `id_colaborador`='$id_colaborador'";
												$resultado_bloqueio_agenda   = mysql_query($sql_bloqueio_agenda) or die(mysql_error());
												$linha_bloqueio_agenda       = mysql_num_rows($resultado_bloqueio_agenda);
												if($linha_bloqueio_agenda > 0){
												$block=1;
												} 
											}			
										
										if($block != 1){ //NAO ESTA OCUPADO
											
											if(!empty($ln_c2['token_id'])){
												
												$tokens.= $ln_c2['token_id'].',';
												//echo $ln_c2['token_id'].', <br/>';	
												
												$sql_c 		= "UPDATE tb_login SET notif = '1' WHERE id_colaborador='$ln_c2[id]' ";
												$resultado_c= mysql_query($sql_c) or die(mysql_error());
												
											}
											
											array_push($destinatarios, $ln_c2['email']);
											//echo $ln_c2['email'].' ';
											//echo $ln_c2['token_id'].', <br/>';							
										}
										
										//$tokens.= $ln_c2['token_id'].',';
										//$destinatarios .= $ln_c2['email'].',';		
										$verif = 1;
										break;
										
									 }						
													
								}
																				
							}
								
						   //if($verif == 1) {break; $verif = 0;}
						}

					}
					
			}else{ //FAZ MAIS DE 30 MIN OU JA EVIOU
						
						
						//avisa o cliente e cancela o pedido
						//echo "Agenda:".$id;
						//echo "Cliente:".$id_cliente;
						if($cancela=='sim'){
							
							$sql_c = "UPDATE tb_agenda SET situacao = 'CANCELADO' WHERE id = '$id' ";
							$resultado_c= mysql_query($sql_c) or die(mysql_error());
							
							require_once('push_cliente_cancela.php');
							
						}else{
							    echo "nada a fazer!";
								
							 }
						
						
				}
		}
	}
	
	    // echo $tokens;		 
		 echo $result = array_unique($destinatarios);
		 
		// print_r($result);		
		if(!empty($destinatarios)) {
			
			require_once('notif_pedido_email.php');
			echo "aqui email";
			
		}
		 
		 
		 if(!empty($tokens)){
			 //echo $tokens;
			require_once('teste_push.php'); 
			echo "aqui notif";
		 }else{ echo "Token vazio";}
							
	
			
?>
