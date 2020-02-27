<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token    = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));


if($token=='H424715433852'){

	$cliente = $_REQUEST['cliente'];
	$servico = anti_sql_injection(strip_tags(trim($_REQUEST['servico'])));
	$cidade  = anti_sql_injection(strip_tags(trim($_REQUEST['cidade'])));
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	//$linha    = mysql_num_rows($resultado);
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($cliente == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
		}
	}
	
	//$sqlc2  = "SELECT * FROM tb_municipios WHERE nome = 'Uberaba' ORDER BY nome";
	//$qrc2   = mysql_query($sqlc2) or die (mysql_error());
	//$lnc2 	= mysql_fetch_assoc($qrc2);
	//$id_cidade = $lnc2['id'];
		
	//$sql = "SELECT * FROM tb_enderecos WHERE id_cliente='$id_cliente' AND cidade='$id_cidade' ORDER BY principal DESC";
	$sql = "SELECT * FROM tb_enderecos WHERE id_cliente='$id_cliente'  ORDER BY principal DESC";

	$resultado   = mysql_query($sql) or die(mysql_error());
	$linha       = mysql_num_rows($resultado);
		if($linha == 0){
	   		$dados = '<option value="0">Não há endereço cadastrado</option>'; 
		}else{
			$x=1;
			while($ln = mysql_fetch_assoc($resultado)){
				$id	 		= $ln['id'];
				$cep 		= $ln['cep'];
				$logradouro = $ln['logradouro'];
				$complemento= $ln['complemento'];
				$bairro 	= $ln['bairro'];
				$numero 	= $ln['numero'];
				$cidade 	= $ln['cidade'];
				
				$sql_m 		= "SELECT * FROM tb_municipios WHERE id='$cidade'";
				$resultado_m= mysql_query($sql_m) or die(mysql_error());
				$ln_m 		= mysql_fetch_assoc($resultado_m);
				
				$sql_b 		= "SELECT * FROM tb_bairro WHERE id='$bairro'";
				$resultado_b= mysql_query($sql_b) or die(mysql_error());
				$ln_b 		= mysql_fetch_assoc($resultado_b);
				
				if(!empty($complemento)){
					$txt_complemento = ' - '.$complemento;
				}
				$dados.= '<option value="'.$ln['id'].'">'.$logradouro.', nº '.$numero.', '.$ln_b['nome'].$txt_complemento.' - '.$ln_m['nome'].'</option>';
				$x++;
			}
		}
}
	echo $dados;

?>