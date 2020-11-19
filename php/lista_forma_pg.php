<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));


if($token=='H424715433852'){

	$cliente    = $_REQUEST['user'];
	$servico    = anti_sql_injection(strip_tags(trim($_REQUEST['servico'])));
	$tipo_order = anti_sql_injection(strip_tags(trim($_REQUEST['tipo_order'])));
	
	if(empty($tipo_order) or $tipo_order==1){ 
		$tipo_order = 'principal'; 
	}else{
		$tipo_order = 'id'; 
	}
	
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
		
	$sql = "SELECT * FROM tb_cartoes WHERE id_cliente='$id_cliente' AND (tipo='1' or tipo='3') ORDER BY $tipo_order DESC";
	$resultado   = mysql_query($sql) or die(mysql_error());
	$linha       = mysql_num_rows($resultado);
		if($linha == 0){
	   		$dados = '<option value="0">Não há formas de pagamento</option>'; 
		}else{
			$x=1;
			while($ln = mysql_fetch_assoc($resultado)){
				$id	 		= $ln['id'];
				$numero 	= $ln['numero'];
				$tipo		= $ln['tipo'];
				$bancodd    = $ln['banco'];
				if($tipo == 1){
					$n_tipo = 'CREDITO ['.strtoupper($bancodd).']';
					$cc     = ' •••• '.substr($numero, -4);
					$value	= $ln['id_cartao'];
				} elseif ($tipo == 2){
					$n_tipo = 'DEBITO ONLINE';
					$cc     = $bancodd;
					$value	= $id;
				} else {
					$n_tipo = 'DINHEIRO * * * *';
					$cc     = '* * * * ';
					$value	= $id;
				}
				
				$dados.= '<option class="pg_'.$value.'" value="'.$value.'">'.$cc.' '.$n_tipo.'</option>';
				$x++;
			}
			$dados.= '<option class="pg_'.$id.'" disabled>Apenas Cartão Credito ou Dinheiro</option>';
		}
}
	echo $dados;

?>