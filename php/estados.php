<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token    = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$estado   = anti_sql_injection(strip_tags(trim($_REQUEST['estado'])));

if($token=='H424715433852'){
		
	$sql 	= "SELECT * FROM tb_estados ORDER BY nome ASC";
    $qr 	= mysql_query($sql) or die(mysql_error());
	$linha 	= mysql_num_rows($qr);
		if($linha == 0){
	   		$dados = '<option value="0">Não há estados</option>'; 
		}else{
			$x=1;
			echo '<option value="0" selected="selected">SELECIONE UM ESTADO</option>';
			while($ln = mysql_fetch_assoc($qr)){
				//if($estado == $ln['uf']){
					//$selecionado = 'selected="selected"';
					echo '<option value="'.$ln['uf'].'" '.$selecionado.'>'.$ln['uf'].'</option>';
				//}else{
					//$selecionado = '';
				//}
				
				//$x++;
			}
		}
}
	echo $dados;

?>