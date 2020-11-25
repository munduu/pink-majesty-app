<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$colaborador  = anti_sql_injection(strip_tags(trim($_REQUEST['user'])));

if($token=='H424715433852'){

	$sql 		= "SELECT * FROM tb_login ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		$tipo = $ln['tipo'];
		if($colaborador == $c_verif){
			$linha ++;
			$id_colaborador = $ln['id_colaborador'];
		}
	}
	
	$dados.="
<style>	
	.select-method + .select-method {
		margin-left: 25px;
	}
		
	.select-methods input:focus + label {
  		outline: 2px dotted #21b4d0;
	}
		
	.select-methods input:checked + label{
  		
	}
</style>";
	

//text-small

$dados.= '<table width="100%" class="panel table b-t ">';

for($dia=0; $dia<=7;$dia++){
	if($dia == 0){$dia_s = 'todos';$dia_completo = 'Todos';}
	if($dia == 1){$dia_s = 'dom';$dia_completo = 'Dom';}
	if($dia == 2){$dia_s = 'seg';$dia_completo = 'Seg';}
	if($dia == 3){$dia_s = 'ter';$dia_completo = 'Ter';}
	if($dia == 4){$dia_s = 'qua';$dia_completo = 'Qua';}
	if($dia == 5){$dia_s = 'qui';$dia_completo = 'Qui';}
	if($dia == 6){$dia_s = 'sex';$dia_completo = 'Sex';}
	if($dia == 7){$dia_s = 'sab';$dia_completo = 'Sab';}

	
	$dados.= '
		<tr>	
			<td align="left">
				<h4>'.$dia_completo.'</h4>
			</td>
	';
	
	$sql_hc = "SELECT * FROM tb_horario_colaborador WHERE id_colaborador = '$id_colaborador' AND dia = '$dia' ORDER BY dia";
	$resultado_hc 	= mysql_query($sql_hc) or die(mysql_error());
	$ln_hc = mysql_fetch_assoc($resultado_hc);
	$data_ini = $ln_hc['data_ini'];
	$data_fim = $ln_hc['data_fim'];
	if(empty($data_ini)){
		$data_ini = '--:--';
	}
	if(empty($data_fim)){
		$data_fim = '--:--';
	}
	if($dia == 0){
		$editar_b = 'Editar Todos';
	}else if($data_ini != '--:--' and $data_fim != '--:--'){
		$editar_b = 'Disponivel';
	}else{
		$editar_b = 'Indisponivel';
	}
	$dados.= '
			<td align="left">
				<h4 class="in_'.$dia_s.'">De <br />'.$data_ini.'</h4>
				<div class="data_i_'.$dia_s.'" alt="'.$data_ini.'"></div>
			</td>
			<td align="left">
				<h4 class="fm_'.$dia_s.'">Ate <br />'.$data_fim.'</h4>
				<div class="data_f_'.$dia_s.'" alt="'.$data_fim.'"></div>
			</td>
	';
	/*
	<div class="entrar">
    	<a href="#"><div class="buttonentrar">confirmar cadastro</a>
    </div>
	*/
	$dados.= '
			<td align="center">
				<h4 class="btn_'.$dia_s.'">
				<a class="alt_hora" alt="'.$dia_s.'" ><div class="buttonentrar">'.$editar_b.'</div></a>
				</h4>
			</td>
		</tr>
	';
}
$dados.= '</table>';
}
	echo $dados;

?>