<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){

	$user    = anti_sql_injection(strip_tags(trim($_REQUEST["user"])));
	$bsc     = anti_sql_injection(strip_tags(trim($_REQUEST["bsc"])));
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
			
			$updateSQL = "
			UPDATE tb_login SET notif='0' WHERE id_cliente = '$id_cliente'";
			mysql_select_db($database_localhost, $localhost);
  			$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error()); 
    		
		}
	}
	/*
	if($bsc == 1){
		$busca = "id_cliente='$id_cliente' AND (situacao='AGENDADO' OR situacao='ACEITO')";	
	}else{
		$busca = "id_cliente='$id_cliente' AND situacao='CONCLUIDO'";
	}*/
	if($bsc == 1){
		$busca = "id_cliente='$id_cliente' AND situacao='AGENDADO' ";	
	}elseif($bsc == 3){
		$busca = "id_cliente='$id_cliente' AND ( ((id_colaborador is NULL OR id_colaborador='0') AND situacao='PEDIDO') OR (situacao='ACEITO') ) ";
	}elseif($bsc == 2){
		$busca = "id_cliente='$id_cliente' AND situacao='CONCLUIDO'";
	}
	
	$dados.='
			<div class="paginaservico">
		';
	$sql_a 		= "SELECT * FROM tb_agenda WHERE $busca AND status='ativo' ORDER BY data, hora_ini ASC";
	$resultado_a= mysql_query($sql_a) or die(mysql_error());
	$linha_a    = mysql_num_rows($resultado_a);
	if($linha_a > 0){
		
		$x=1;
		while($ln_a = mysql_fetch_assoc($resultado_a)){
			$id	 			= $ln_a['id'];
			$id_cliente 	= $ln_a['id_cliente'];
			$id_colaborador = $ln_a['id_colaborador'];
			$data 			= $ln_a['data'];
			$servico 		= $ln_a['servico'];
			$local 			= $ln_a['local'];
			$hora_ini 		= $ln_a['hora_ini'];
			$hora_fim		= $ln_a['hora_fim'];
			$status	   		= $ln_a['status'];
			$situacao		= $ln_a['situacao'];
			$forma_pg		= $ln_a['forma_pg'];
			$valor			= $ln_a['valor'];
			$cod_pagseguro	= $ln_a['cod_pagseguro'];
			
			$horat_ini 		= strtotime($hora_ini);
			$horat_fim 		= strtotime($hora_fim);
			
			$sql_a2 		= "SELECT * FROM tb_agenda WHERE id_cliente = '$id_cliente' AND data='$data' AND status='ativo' ORDER BY data, hora_ini ASC";
			$resultado_a2	= mysql_query($sql_a2) or die(mysql_error());
			while($ln_a2 	= mysql_fetch_assoc($resultado_a2)){
				$hora_ini2 	= $ln_a2['hora_ini'];
				$hora_fim2	= $ln_a2['hora_fim'];
				
				$horat_ini2 	= strtotime($hora_ini2);
				$horat_fim2 	= strtotime($hora_fim2);
				
				if(($horat_ini >= $horat_ini2 and $horat_ini <= $horat_fim2) or ($hora_ini <= $hora_ini2 and $hora_fim > $hora_ini2)){
					$block=1;
				}
			}			
			
			if($id_cliente)
			
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
			
			$sql_crt 		= "SELECT * FROM tb_cartoes WHERE id='$forma_pg' ORDER BY id ASC";
			$resultado_crt  = mysql_query($sql_crt) or die(mysql_error());
			$ln_crt 		= mysql_fetch_assoc($resultado_crt);
			
			$lNcartao		= $ln_crt['numero'];
			$lNmcartao		= $ln_crt['nome_impresso'];
			$lMesVenc		= $ln_crt['mes_val'];
			$lAnoVenc		= $ln_crt['ano_val'];
			$lCodigoSeg		= $ln_crt['cod_seg'];
			$lTipo			= $ln_crt['tipo'];
			$bancodd		= $ln_crt['banco'];
			
			if($lTipo == 1){
				$n_tipo = 'CREDITO';
			}elseif($lTipo == 2){
				$n_tipo = 'DEBITO';
			}elseif($lTipo == 3){
				$n_tipo = 'DINHEIRO';
			}else{
				$n_tipo = '';
			}
			
			$sql_s 		= "SELECT * FROM tb_produto WHERE id = '$servico' ORDER BY id ASC";
			$resultado_s= mysql_query($sql_s) or die(mysql_error());
			$ln_s 		= mysql_fetch_assoc($resultado_s);
			
			$nome_servico = $ln_s['titulo'];
			
			$sql_cl 		= "SELECT * FROM tb_cliente WHERE id = '$id_cliente' ORDER BY id ASC";
			$resultado_cl	= mysql_query($sql_cl) or die(mysql_error());
			$ln_cl 			= mysql_fetch_assoc($resultado_cl);
			
			$nome_cliente = $ln_cl['nome'];
			
			$sql_c 		= "SELECT * FROM tb_colaborador WHERE id = '$id_colaborador' ORDER BY id ASC";
			$resultado_c= mysql_query($sql_c) or die(mysql_error());
			$ln_c 		= mysql_fetch_assoc($resultado_c);
			
			$nome_colaborador 	= $ln_c['nome'];
			$foto 				= $ln_c['foto'];
			
			$titulo_exp = explode(' ',ucfirst(mb_strtolower($nome_servico,'UTF-8')));
			if($titulo_exp[0] == 'Pacote'){
				$fonte = "font-family:'Conv_Angelface', sans-serif;font-size: 3em;!important";
			}else{
				$fonte = 'font-size: 2em;!important';
			}
			
			
			$dados.= '
			<a class="btn2 infoservico" style="text-decoration:none">
				<div id="produto_total">
					<div class="partes_categoria" align="center">
						<p style="'.$fonte.' font-size:30px;">'.ucfirst(mb_strtolower($nome_servico,'UTF-8')).'</p>
						<h4 align="center"><b>COD.: '.str_pad($id, 4, "0", STR_PAD_LEFT).'</b></h4>
						<br />
						<h4 class="list-group-item-heading">						    
						    <i class="fa fa-money" aria-hidden="true" style="color:#ec268f"></i> Valor: <b>R$'.number_format($valor, 2, ',', ' ').'</b>
							<br />	<br />						
							<i class="fa fa-user-circle" aria-hidden="true"></i> Cliente: '.$nome_cliente.
							'<br /><br />
							
							<a target="_blank" href="http://maps.google.com.br/maps?hl=pt-BR&amp;q='.$logradouro.','.$numero.','.$cidade.', '.$estado.'&amp;um=1&amp;ie=UTF-8&amp;sa=N&amp;tab=wl">
							<i class="fa fa-map-marker" aria-hidden="true"></i> '.$logradouro.', nº '.$numero.
							'<br />
							'.$nome_bairro.' - '.$cidade.' </a>
						</h4>
					</div>
					<br />
					<div class="partes_categoria" align="center">
						<h4 class="list-group-item-heading">
						 	<i class="fa fa-calendar-check-o" aria-hidden="true"></i> Data: '.databar($data).'<br /><br />
						 	<i class="fa fa-clock-o" aria-hidden="true"></i> Horario: de '.$hora_ini.' ate '.$hora_fim.'<br /><br />';
					
					if($situacao == 'ACEITO' || $situacao == 'PEDIDO'){
						$dados.= '<a class="btn_troca_cartao" alt="'.$id.'">';
					}else{
						$dados.= '<a>';
					} 
					
						$dados.= '<i class="fa fa-credit-card-alt" aria-hidden="true" style="color:#ec268f;"></i> ****'.substr($lNcartao, -4).' '.$n_tipo.'</a><br /><br />
							';
						if(!empty($id_colaborador)){
							if(empty($foto)){$foto = 'imagem_teste.gif';}
							//<img src="'.$url_img.'igestao/imagem_col/'.$foto.'">
							$url_img = 'http://igestaoweb.com.br/pinkmajesty/imagem_col/';
							$url_bsc = $url_img.''.$foto;
							$dados.= '
								<div class="imageservico" style="background-image:url('.$url_bsc.')"></div><br />
								<p align="center"><i class="fa fa-user-circle" aria-hidden="true"></i> Profissional: '.$nome_colaborador.'</p><br />
								
							';
						}
						$dados.= '
						</h4>
						';
						if($situacao =='CONCLUIDO'){
							$sql_ava 		= "SELECT * FROM tb_avaliacao WHERE id_agenda = '$id' ORDER BY id ASC";
							$resultado_ava	= mysql_query($sql_ava) or die(mysql_error());
							$linha_ava 		= mysql_num_rows($resultado_ava);
							$ln_ava 		= mysql_fetch_assoc($resultado_ava);
							
							$avaliacao	= $ln_ava['avaliacao'];
							$descricao	= $ln_ava['descricao'];
						?>
							<script>
								$(function () {
									$("#rateYo"+<?=$id?>).rateYo({
										rating: 
										<? if($linha_ava == 1){
											echo $avaliacao;
										 }else{?>
											3
										<? }?>,
										starWidth: "20px",
										spacing   : "5px",
										normalFill: "#C6C6C6",
										//halfStar: true,
										<? if($linha_ava == 1){?>
											readOnly: true,
										<? }?>
										/*multiColor: {
											"startColor": "#000000", //BLACK
											"endColor"  : "#ec268f"  //PINK
										}*/
										ratedFill:"#ec268f",
										fullStar: true
									});
								});
								$(document).on("click", "#rateYo"+<?=$id?>, function(evt){
									var avaliacao = $("#rateYo"+<?=$id?>).rateYo("option", "rating");
									$('#v_rate'+<?=$id?>).html(avaliacao);
								});
							</script>
						<?
							
							$dados.= '<div class="realizar_avaliacao'.$id.'" align="center">';	
							if($linha_ava == 1){
								$dados.= '
								<div class="avaliacao'.$id.'">
									<div class="rateYo" id="rateYo'.$id.'" alt="'.$id.'"></div>
									<div class="v_rate" id="v_rate'.$id.'" alt="'.$id.'"></div>
									<div align="center">
										'.$descricao.'
									</div>
								</div>
								';
							}else{
								$dados.= '<button class="btn_avaliar btn" alt="'.$id.'" data-uib="twitter%20bootstrap/button" data-ver="1">Avaliar</button>';
							}
							$dados.= '</div>';
							
							$dados.= '
							<div class="avaliacao'.$id.'" style="display:none;" align="center">
								<div class="rateYo" id="rateYo'.$id.'" alt="'.$id.'"></div>
								<div class="v_rate" id="v_rate'.$id.'" alt="'.$id.'"></div>
								<div align="center">
									<textarea class="desc_serv" id="desc_serv'.$id.'" alt="'.$id.'"></textarea>
								</div>
								<div align="center">
						 			<button class="btn_avaliacao btn" alt="'.$id.'" data-uib="twitter%20bootstrap/button" data-ver="1">Enviar Avalicação</button>
								</div>
							</div>
							';
						}
						if($situacao =='ACEITO' || $situacao == 'PEDIDO'){
							$dados.= '
							<div align="center">
								<button name="btn_troca_cartao" class="btn_troca_cartao btn" alt="'.$id.'" data-uib="twitter%20bootstrap/button" data-ver="1" style="display:none">Selecionar outro Cartão</button>
							</div>';
						}
					$dados.= '
					</div>
					<div style="clear:both;"></div>
				</div>
				<input type="hidden" id="idc'.$id.'" 		value="'.$id_cliente.'">
				<input type="hidden" id="id_end'.$id.'" 	value="'.$local.'">
				<input type="hidden" id="idi'.$id.'" 		value="'.$servico.'">
				<input type="hidden" id="total'.$id.'" 		value="'.$valor.'">
				<input type="hidden" id="lNcartao'.$id.'" 	value="'.$lNcartao.'">
				<input type="hidden" id="lNmcartao'.$id.'" 	value="'.$lNmcartao.'">
				<input type="hidden" id="lMesVenc'.$id.'" 	value="'.$lMesVenc.'">
				<input type="hidden" id="lAnoVenc'.$id.'" 	value="'.$lAnoVenc.'">
				<input type="hidden" id="lCodigoSeg'.$id.'" value="'.$lCodigoSeg.'">
				<input type="hidden" id="lTipo'.$id.'" 		value="'.$lTipo.'">
				<input type="hidden" id="Bancodd'.$id.'" 	value="'.$bancodd.'">
			</a>
			<br />
			';
			$x++;
		}
		
	}else{
		if($bsc == 3){
			$dados.= '<h3 align="center" style="margin-top:50%">Nenhum Pedido para mostrar =(</h3>';
		}
		if($bsc == 2){
			$dados.= '<h3 align="center" style="margin-top:50%">Nenhum Pedido Concluido !</h3>';
		}
		if($bsc == 1){
			$dados.= '<h3 align="center" style="margin-top:50%">Nenhum Pedido Agendado !</h3>';
		}	
	}
	$dados.= '
			</div>
		';
}
	echo $dados;

?>