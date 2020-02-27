<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token    = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$id_end	  = anti_sql_injection(strip_tags(trim($_REQUEST['id_end'])));

if($token=='H424715433852'){
		
	$sql = "SELECT * FROM tb_enderecos WHERE id='$id_end'";
	$resultado   = mysql_query($sql) or die(mysql_error());
	$linha       = mysql_num_rows($resultado);
	$x=1;
	$ln = mysql_fetch_assoc($resultado);
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
		
		$dados.= '<a href="https://www.google.com.br/maps?hl=pt-BR&q='.$logradouro.','.$numero.',&um=1&ie=UTF-8&sa=N&tab=wl">'.$logradouro.', nº '.$numero.', '.$ln_b['nome'].' - '.$complemento.' - '.$ln_m['nome'].'</a>';
	
		
}
	echo $dados;

?>