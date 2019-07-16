<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$bsc  	= anti_sql_injection(strip_tags(trim($_REQUEST['bsc'])));
$datai 	= anti_sql_injection(strip_tags(trim($_REQUEST['data1'])));
$dataf 	= anti_sql_injection(strip_tags(trim($_REQUEST['data2'])));
$data_atual = date("Y-m-d");
if(empty($datai)){
	$datai =  date("Y-m-01");
}
if(empty($dataf)){
	$dataf = date("Y-m-31");
}
//$dados.= $datai.' - '.$dataf;
//$dados .= anti_sql_injection(strip_tags(trim($_REQUEST['data1'])));

$data1 = str_replace("-", "/", $datai);
$data2 = str_replace("-", "/", $dataf);
$data1 = databar($data1);
$data2 = databar($data2);



if($token=='H424715433852'){

	$user    = anti_sql_injection(strip_tags(trim($_REQUEST["user"])));
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Profissional' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_colaborador = $ln['id_colaborador'];
			
			$updateSQL = "
			UPDATE tb_login SET notif='0' WHERE id_colaborador = '$id_colaborador'";
			mysql_select_db($database_localhost, $localhost);
  			$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
    					
		}
	}
	/*if($bsc == 1){
		$busca = "(id_colaborador='$id_colaborador') AND (situacao='AGENDADO')";	
	}elseif($bsc == 2 or empty($bsc)){
		$busca = "(id_colaborador is NULL OR id_colaborador='0') AND (situacao='ACEITO' OR situacao='PEDIDO')";
	}elseif($bsc == 3){
		$busca = "(id_colaborador='$id_colaborador') AND (situacao='CONCLUIDO')";	
	}*/
	
	//filtar o serviço
	$sql_S 		= "SELECT servico FROM tb_colaborador WHERE id = '$id_colaborador' ";
	$resultado_S= mysql_query($sql_S) or die(mysql_error());
	$ln_S 		= mysql_fetch_assoc($resultado_S);			
	$servicosqueatendo 	= $ln_S['servico'];
	
	//fazer o ajuste da virgula ->  AND servico IN ($servicosqueatendo) 
	
	if($bsc == 1){
		$busca = "(id_colaborador='$id_colaborador') AND (situacao='AGENDADO') ";	
		$ordem = "ORDER BY id DESC";
	}elseif($bsc == 2 or empty($bsc)){
		$busca = "(((id_colaborador is NULL OR id_colaborador='0') AND situacao='PEDIDO') OR (situacao='ACEITO' and id_colaborador='$id_colaborador' ) ) ";
		$ordem = "ORDER BY situacao PEDIDO, ACEITO";
	}elseif($bsc == 3){
		$busca = "(id_colaborador='$id_colaborador') AND (situacao='CONCLUIDO')";	
		$ordem = "ORDER BY data_agend DESC";
	}
	if(!empty($data1) and !empty($data2) and $bsc != 2){
		$busca .= " AND data BETWEEN '".$data1."' AND '".$data2."'";
	}
		
        //$dados.= '';
		if($bsc == 3){
			$cont = 0;
			$sql2 = "SELECT * FROM tb_agenda WHERE $busca $ordem";
			$qr2  = mysql_query($sql2) or die (mysql_error());
			while($ln2 = mysql_fetch_assoc($qr2)){ 
						
				$id_agenda  	=  $ln2['id'];
				$valor_total	+= $ln2['valor']*70/100;				
			
				//$valor_total	        += $ln2['valor_semdesconto']*70/100;
						
				$sql_ava2 = "SELECT * FROM tb_avaliacao WHERE id_agenda = '$id_agenda'";
				$qr_ava2  = mysql_query($sql_ava2) or die (mysql_error());
				$linha_ava2 = mysql_num_rows($qr_ava2); 
				$ln_ava2 = mysql_fetch_assoc($qr_ava2); 
						
				$avaliacao_total  += $ln_ava2['avaliacao'];
				if(!empty($ln_ava2['avaliacao'])){
					$cont++;
				}
			}
			if(!empty($valor_total)){
				$total = 'Valor Atribuido ao Periodo: R$'.number_format($valor_total, 2, ',', ' ');
			}
			if($cont > 0){
				$avaliacao_total = $avaliacao_total/$cont;
			}
			if(!empty($avaliacao_total)){
			?>
			<script>
			$(function () {
				$("#rateYo_media").on("rateyo.init", function () { console.log("rateyo.init"); });
							
				$("#rateYo_media").rateYo({
					rating:<?=$avaliacao_total?>,
					starWidth: "30px",
					spacing   : "5px",
					normalFill: "#C6C6C6",
					readOnly: true,
					/*multiColor: {
						"startColor": "#FF0000", //RED
						"endColor"  : "#00FF00"  //GREEN
					}*/
					ratedFill:"#ec268f"
				});
			})
			</script>
			<?
			}
			/*
			$dados.= '
			<div class="col-xs-6 col-md-12" align="left">
				Data Inicio : 
				<input type="date" class="apg_campo data_busca1" placeholder="Data Inicio" id="data_busca1" value="'.$datai.'">
			</div>
			<div class="col-xs-6 col-md-12" align="left">
				Data Fim  : 
				<input type="date" class="apg_campo data_busca2" placeholder="Data Final" id="data_busca2" value="'.$dataf.'">
			</div>
			<div class="col-xs-12 col-md-12 entrar">
				<a class="busca-data"><div class="buttonentrar">Buscar</div></a>
			</div>
			<div class="col-xs-12 col-md-12"><br /></div>
			';								
			*/
			$dados.= ' 
			<div align="center">
				<div style="display:inline-block;width:15%;height:50%;"><b>MEDIA : </b></div>
				<div class="rateYo_media" id="rateYo_media" alt="" style="display:inline-block;width:60%;height:50%;"></div>			
				<div style="display:inline-block;width:10%;height:50%;">
					<b>'.$avaliacao_total.'</b>
				</div>
				<div>'.$total.'</div>
			</div>
			';
		}
		
		if($bsc == 1 or $bsc == 2){
			$dados .= '
			<div align="center">
				<div style="display:inline-block;width:15%;height:50%;"><b></b></div>
				<div alt="" style="display:inline-block;width:60%;height:50%;"></div>			
				<div style="display:inline-block;width:10%;height:50%;">
					<b></b>
				</div>
			</div>
			';
		}
	$sql_a 		= "SELECT * FROM tb_agenda WHERE $busca AND status='ativo' ORDER BY data, hora_ini ASC";
	$resultado_a= mysql_query($sql_a) or die(mysql_error());
	$linha_a    = mysql_num_rows($resultado_a);
	if($linha_a > 0){
		$x=0; $verif=0;
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
			$valor 			= $ln_a['valor'];
			$valor_s		= $ln_a['valor_semdesconto'];
			$forma_pg		= $ln_a['forma_pg'];			
			
			$horat_ini 	= strtotime($hora_ini);
			$horat_fim 	= strtotime($hora_fim);
			
			$sql_a2 		= "SELECT * FROM tb_agenda WHERE id_colaborador = '$id_colaborador' AND data='$data' AND status='ativo' ORDER BY data, hora_ini ASC";
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
			
			$sql_c2 		= "SELECT * FROM tb_colaborador WHERE status = 'ATIVO' ORDER BY id ASC";
			$resultado_c2	= mysql_query($sql_c2) or die(mysql_error());
			while($ln_c2 			= mysql_fetch_assoc($resultado_c2)){
				$areas 	= $ln_c2['area'];
				$area = explode(",", $areas);
				for($y=0; !empty($area[$y]); $y++){
					if($bairro == $area[$y]){
						$verif = 1;
					}
				}
			}
			//if($status_tempo == 1){$verif = 1;}
			$sql_c 		= "SELECT * FROM tb_colaborador WHERE id = '$id_colaborador' ORDER BY id ASC";
			$resultado_c= mysql_query($sql_c) or die(mysql_error());
			$ln_c 		= mysql_fetch_assoc($resultado_c);
			
			$areas 	= $ln_c['area'];
			$area = explode(",", $areas);
			for($y=0; !empty($area[$y]); $y++){
				if($bairro == $area[$y]){
					
					$titulo_exp = explode(' ',ucfirst(mb_strtolower($nome_servico,'UTF-8')));
					if($titulo_exp[0] == 'Pacote'){
						$fonte = "font-family:'Conv_Angelface', sans-serif;font-size: 3em;!important";
					}else{
						$fonte = 'font-size: 2em;!important';
					}
					
					$dados.= '
					<div class="list-group-item allow-badge widget btn2" id="'.$id.'" data-uib="twitter%20bootstrap/list_item" data-ver="1">
						<div id="produto_total">
							<div class="partes_categoria" align="center">
								<p style="'.$fonte.' font-size:30px">'.ucfirst(mb_strtolower($nome_servico,'UTF-8')).'</p>
								<h4 align="center"><b>COD.: '.str_pad($id, 4, "0", STR_PAD_LEFT).'</b></h4>
								<h4 class="list-group-item-heading">
									<i class="fa fa-user-circle" aria-hidden="true"></i> Cliente : '.$nome_cliente.
									'<br /><br />
									
									<a target="_blank" href="http://maps.google.com.br/maps?hl=pt-BR&amp;q='.$logradouro.','.$numero.','.$cidade.', '.$estado.'&amp;um=1&amp;ie=UTF-8&amp;sa=N&amp;tab=wl">
									
									<i class="fa fa-map-marker" aria-hidden="true"></i>																		
									'.$logradouro.', nº - '.$numero.
									'</a><br />
									'.$nome_bairro.' - '.$cidade.
									'<br />
									'.$complemento.' 
									
									
									
								</h4>
							</div>
							<br />
							<div class="partes_categoria" align="center">
								<h4 class="list-group-item-heading">
								 <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Data: '.databar($data).'<br /><br />
								 <i class="fa fa-clock-o" aria-hidden="true"></i> Horario: de '.$hora_ini.' ate '.$hora_fim.'
								</h4>
								
								<h4 class="list-group-item-heading" style="font-size:17px">
								<br />
								<i class="fa fa-money" aria-hidden="true" style="color:#ec268f"></i> Valor Total: <b>R$'.number_format($valor, 2, ',', ' ').'</b> 
								<br /><br />
								<i class="fa fa-money" aria-hidden="true" style="color:#ec268f"></i> Valor que você vai Receber: <b>R$'.number_format($valor*70/100, 2, ',', ' ').'</b>
								</h4>
								';
								if($bsc == 2 and $block != 1 and $situacao == 'PEDIDO'){
									$dados.= '
									<br>
									<div align="center">
								 	 <button class="btn_aceitar btn" alt="'.$id.'" data-uib="twitter%20bootstrap/button" data-ver="1"><h5>Aceitar</h5></button>
									</div>';
								}
							$dados.= '</div>';
							if($situacao == 'ACEITO'){
								$dados.= '
								<div class="partes_categoria" align="center">
									<h4 style="color:red"><b>Aguardando confirmação de pagamento</b></h4>
								</div>';
							}
							if($situacao == 'AGENDADO'){
								$dados.= '
								<div class="partes_categoria" align="center">
									<h4 style="color:green"><b>Agenda Confirmada</b></h4>
								</div>
								<div align="center">
								 	 <button class="btn_concluir btn" alt="'.$id.'" data-uib="twitter%20bootstrap/button" data-ver="1">Encerrar</button>
								</div>
								';
							}
							
							
							$dados.= '
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
								<input type="hidden" id="Bancodd'.$id.'" 	value="'.$bancodd.'">';
														
							if($situacao == 'CONCLUIDO'){
							$sql_ava = "SELECT * FROM tb_avaliacao WHERE id_agenda = '$id'";
							$qr_ava  = mysql_query($sql_ava) or die (mysql_error());
							$linha_ava = mysql_num_rows($qr_ava); 
							$ln_ava = mysql_fetch_assoc($qr_ava); 
							
							$avaliacao  = $ln_ava['avaliacao'];
							$descricao  = $ln_ava['descricao'];	
							if(!empty($avaliacao)){
							?>
			<script>
				$(function () {
					$("#rateYo_ag"+<?=$id?>).on("rateyo.init", function () { console.log("rateyo.init"); });
					
					$("#rateYo_ag"+<?=$id?>).rateYo({
						rating:<?=$avaliacao;?>,
						starWidth: "20px",
						spacing   : "5px",
						normalFill: "#C6C6C6",
						readOnly: true,
						/*multiColor: {
							"startColor": "#FF0000", //RED
							"endColor"  : "#00FF00"  //GREEN
						}*/
						ratedFill:"#ec268f"
					});
				})
			</script>
							<? 
							}
                            	$dados.= '
								<div align="center">
								<div class="rateYo_ag'.$id.'" id="rateYo_ag'.$id.'" alt="'.$id.'" style="display:inline-block; width:75%; height:50%;">'.$avaliacao.'</div>
								<div style="display:inline-block; width:10%; height:50%;">
									<b>'.$avaliacao.'</b>
                    			</div>
								</div>
								';
							}
							$dados.= '
							<div style="clear:both;"></div>
						</div>
					</div>
					';
					$x++;
				}
			}
			if(($verif != 1 or $status_tempo == 1) and $block != 1){
				$dados.= '
				<div class="list-group-item allow-badge widget btn2" id="'.$id.'" data-uib="twitter%20bootstrap/list_item" data-ver="1">
					<div id="produto_total">
						<div class="partes_categoria" align="center">
						<p style="font-size:30px">'.ucfirst(mb_strtolower($nome_servico,'UTF-8')).'</p>
						<h4 align="center"><b>COD.: '.str_pad($id, 4, "0", STR_PAD_LEFT).'</b></h4>
						<h4 class="list-group-item-heading"> 
							<i class="fa fa-user-circle" aria-hidden="true"></i> Cliente : '.$nome_cliente.
							'<br /><br />
							
							<a target="_blank" href="http://maps.google.com.br/maps?hl=pt-BR&amp;q='.$logradouro.','.$numero.','.$cidade.', '.$estado.'&amp;um=1&amp;ie=UTF-8&amp;sa=N&amp;tab=wl">
							
							<i class="fa fa-map-marker" aria-hidden="true"></i> '.$logradouro.', nº '.$numero.
							'<br />
							'.$nome_bairro.' - '.$cidade.
							'<br />
							'.$complemento.'
							
							</a>
							
						</h4>
					</div>
					<br />
					<div class="partes_categoria" align="center">
						<h4 class="list-group-item-heading">
						 	<i class="fa fa-calendar-check-o" aria-hidden="true"></i> Data: '.databar($data).'<br /><br />
						 	<i class="fa fa-clock-o" aria-hidden="true"></i> Horario: de '.$hora_ini.' ate '.$hora_fim.'
						</h4>
						
						<h4 class="list-group-item-heading">
							<br />
							<i class="fa fa-money" aria-hidden="true" style="color:#ec268f"></i> Valor Total: <b>R$'.number_format($valor, 2, ',', ' ').'</b> 
							<br />
							<i class="fa fa-money" aria-hidden="true" style="color:#ec268f"></i> Valor que você vai Receber: <b>R$'.number_format($valor*70/100, 2, ',', ' ').'</b>
						</h4>
						';
						if($bsc == 2 and $block != 1 and $situacao == 'PEDIDO'){
							$dados.= '
							<div align="center">
						 	 <button class="btn_aceitar btn" alt="'.$id.'" data-uib="twitter%20bootstrap/button" data-ver="1">Aceitar</button>
							</div>';
						}
					$dados.= '</div>';
					if($situacao == 'ACEITO'){
						$dados.= '
						<div class="partes_categoria" align="center">
							<h4 style="color:red; text-align:center;"><b>Aguardando confirmação de pagamento</b></h4>
						</div>';
					}
					if($situacao == 'AGENDADO'){
						$dados.= '
						<div class="partes_categoria" align="center">
							<h4 style="color:green; text-align:center;"><b>Agenda Confimada</b></h4>
						</div>
						<div align="center">
						 	 <button class="btn_concluir btn" alt="'.$id.'" data-uib="twitter%20bootstrap/button" data-ver="1">Encerrar</button>
						</div>
						';
					}
					if($situacao == 'CONCLUIDO'){
					$sql_ava = "SELECT * FROM tb_avaliacao WHERE id_agenda = '$id'";
					$qr_ava  = mysql_query($sql_ava) or die (mysql_error());
					$linha_ava = mysql_num_rows($qr_ava); 
					$ln_ava = mysql_fetch_assoc($qr_ava); 
						
					$avaliacao  = $ln_ava['avaliacao'];
					$descricao  = $ln_ava['descricao'];
					if(!empty($avaliacao)){	
					?>
			<script>
				$(function () {
					$("#rateYo_ag"+<?=$id?>).on("rateyo.init", function () { console.log("rateyo.init"); });
					
					$("#rateYo_ag"+<?=$id?>).rateYo({
						rating:<?=$avaliacao;?>,
						starWidth: "20px",
						spacing   : "5px",
						normalFill: "#C6C6C6",
						readOnly: true,
						/*multiColor: {
							"startColor": "#FF0000", //RED
							"endColor"  : "#00FF00"  //GREEN
						}*/
						ratedFill:"#ec268f"
					});
				})
			</script>
					<?
					}
                       	$dados.= '
						<div align="center">
							<div class="rateYo_ag" id="rateYo_ag'.$id.'" alt="'.$id.'" style="display:inline-block; width:75%; height:50%;"></div>
							<div style="display:inline-block; width:10%; height:50%;">
								<b>'.$avaliacao.'</b>
                   			</div>
						</div>
						';
					}
					$dados.= '
						<div style="clear:both;"></div>
					</div>
				</div>
				';
				$x++;
			}
			$verif = 0;$block = 0;
		}		
	}
	if($x == 0){
	$dados.= '
	<div align="center">
		<div>
			<b>Nenhum Pedido</b>
       	</div>
	</div>
	';
	}
}
	echo $dados;

?>