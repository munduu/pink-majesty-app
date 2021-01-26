<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$servico_selec	= anti_sql_injection(strip_tags(trim($_REQUEST['servico'])));
$token  		= anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$hora   		= anti_sql_injection(strip_tags(trim($_REQUEST['hora'])));
$data   		= anti_sql_injection(strip_tags(trim($_REQUEST['data'])));

//echo $data2=date("H:i",  strtotime( "+20 minute", strtotime($hora) ));

$dia = date('w', strtotime($data))+1;
if($token=='H424715433852'){
		
	$sql_c = "SELECT * FROM tb_colaborador WHERE status='ATIVO' ORDER BY id ASC";
	$resultado_c   = mysql_query($sql_c) or die(mysql_error());
	while($ln_c = mysql_fetch_assoc($resultado_c)){
		$servicos = $ln_c['servico'];
		$servico = explode(",", $servicos);
		for($y=0;!empty($servico[$y]);$y++){
			if($servico_selec == $servico[$y]){
				$id_c = $ln_c['id'];
				if(!empty($colab)){
					$colab .= ',';
				}
				$colab .= $id_c;
			}
		}
	}
	if(empty($data)){
		$error = 6;
		$dados = 'Selecione a Data';
	}else{
		if(!empty($colab)){
			$sql = "SELECT * FROM tb_horario_colaborador WHERE dia='$dia' AND status='1' AND id_colaborador IN($colab) ORDER BY id ASC";
			$resultado   = mysql_query($sql) or die(mysql_error());
			$linha       = mysql_num_rows($resultado);
			if($linha > 0){
				$hora 		= strtotime($hora);
				while($ln = mysql_fetch_assoc($resultado)){
					$id_colaborador = $ln['id_colaborador'];
					$hoje = date("Y-m-d H:i:s");
					$sql2 ="SELECT * FROM `tb_horario_colaborador` WHERE `bloqueado_inicio`> '$hoje' AND `bloqueado_fim`< '$hoje' AND status='0' AND `id_colaborador`='$id_colaborador'";
					$resultado2   = mysql_query($sql2) or die(mysql_error());
					$linha2       = mysql_num_rows($resultado2);
					if($linha2 > 0){
						$error = 5;
						$dados = 'Hora Indisponivel';
					} else {
						$id			= $ln['id'];
						$data_ini	= $ln['data_ini'];
						$data_fim	= $ln['data_fim'];
						
						$hora_ini 	= strtotime($data_ini);
						$hora_fim 	= strtotime($data_fim);
						
						if($hora >= $hora_ini and $hora < $hora_fim){
							$error = 1;
							$dados .= 'Data Disponivel';
						}elseif(empty($hora) and $error != 1){
							$error = 6;
							$dados = 'Selecione a Hora';
						}elseif($error != 1){
							$error = 5;
							$dados = 'Hora Indisponivel';
						}
					}
				}
			}else{
				$error = 4;
				$dados = 'Erro: Nenhum horario disponivel neste Dia!';
			}
		}else{
			$error = 2;
			$dados = 'Erro: Nenhum horario disponivel!';
		}
	}
}else{
	$error = 3;
	$dados = 'Erro: Token inválido!';
}
	echo '{"erro":"'.$error.'","dados":"'.$dados.'"}';

?>