<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/valida-cpf.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){

	$user    	= anti_sql_injection(strip_tags(trim($_REQUEST["user"])));
	$servico    = anti_sql_injection(strip_tags(trim($_REQUEST["servico"])));
	$cod_cupom  = anti_sql_injection(strip_tags(trim($_REQUEST["cod_cupom"])));
	$cpf_cupom  = anti_sql_injection(strip_tags(trim($_REQUEST["cpf_cupom"])));
	
	$data_atual = date('Y/m/d');
	
	//if (valida_cpf($cpf_cupom)) {
	
		$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
		$resultado 	= mysql_query($sql) or die(mysql_error());
		while($ln = mysql_fetch_assoc($resultado)){
			$c_verif = md5($ln['email'].$ln['senha']);
			if($user == $c_verif){
				$linha ++;
				$id_cliente = $ln['id_cliente'];
			}
		}
		
		$sql_serv 		= "SELECT * FROM tb_produto WHERE id='$servico'";
		$resultado_serv = mysql_query($sql_serv) or die(mysql_error());
		$ln_serv 		= mysql_fetch_assoc($resultado_serv);
		$venda	 			= $ln_serv['venda'];
		
		if($cod_cupom == 'Dellas1vez'){
			$sql_l         	= "SELECT * FROM tb_cliente WHERE id='$id_cliente'";
			$resultado_l   	= mysql_query($sql_l) or die(mysql_error());
			$linha_l       	= mysql_num_rows($resultado_l);
			$ln_l          	= mysql_fetch_assoc($resultado_l);
			$desconto 	   	= $ln_l['desconto'];
			$v_desc		   	= '20';
		}else{
			$sql = "SELECT * FROM tb_cupom WHERE cod='$cod_cupom'";
			$resultado  	 = mysql_query($sql) or die(mysql_error());
			$linha       	= mysql_num_rows($resultado);
			$ln 			= mysql_fetch_assoc($resultado);
			$id	 			= $ln['id'];
			$v_desc 		= $ln['v_desc'];
			$n_utilizacoes 	= $ln['n_utilizacoes'];
			$venc 			= $ln['venc'];
		}
		
		$sql_ag 		= "SELECT * FROM tb_agenda WHERE cupom='$cod_cupom'";
		$resultado_ag  	= mysql_query($sql_ag) or die(mysql_error());
		$linha_ag 		= mysql_num_rows($resultado_ag);
		if($linha_ag == 0){//
			if(($linha != 0 and $n_utilizacoes > 0 and strtotime($data_atual) < strtotime($venc)) or empty($cod_cupom) or $desconto == 1){
				
				if(empty($cod_cupom)){
					$error = 2;
				}else{
					$error = 0;
					$valor   = $v_desc*$venda/100;
					$venda = $venda - $valor;
				}
			}else{
				$error = 1;
				if($cod_cupom == 'Dellas1vez' and $desconto == 0){
					$error = 3;
				}else{
					$error = 1;
				}
			}
		}else{
			$error = 3;
			//$dados = 'Erro: CPF Inválido!';
		}
	/*}else{
		$error = 5;
		$dados = 'Erro: CPF Inválido!';
	}*/
	echo '{"erro":"'.$error.'","valor":"'.number_format((double)$venda, 2,',','.').'", "valor_ant":"'.number_format((double)$ln_serv['venda'], 2,',','.').'", "desconto":"'.$v_desc.'"}';
}
	
?>