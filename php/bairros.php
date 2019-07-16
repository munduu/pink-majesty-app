<?php
require_once('Connections/localhost.php');

function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
}

$cidade = $_REQUEST['cidade'];
$bairro = $_REQUEST['bairro'];

/*if(!is_numeric($cidade)){		
	$sql_e = "SELECT iduf FROM tb_municipios WHERE nome = '$cidade'";
	$qr_e  = mysql_query($sql_e) or die (mysql_error());
	$ln_e  = mysql_fetch_assoc($qr_e);
	$cidade    = $ln_e['id'];
}*/

if(is_numeric($cidade)){
$sql_m = "SELECT * FROM tb_municipios WHERE id = '$cidade' ORDER BY nome";
$qr_m  = mysql_query($sql_m) or die(mysql_error());
$ln_m  = mysql_fetch_assoc($qr_m);
$cidade = $ln_m['nome'];
}else{
	$cidade = $cidade;
}
$sql = "SELECT * FROM tb_bairro WHERE cidade = '$cidade' ORDER BY nome";
$qr = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($qr) == 0){
   echo  '<option value="0">Não foi encontrado bairros nesse estado'.$cidade.'</option>';
}else{
	echo  '<option value="0">'.htmlentities('Selecione Bairro').'</option>';
   while($ln = mysql_fetch_assoc($qr)){
		if(strtoupper(tirarAcentos($ln['nome'])) == strtoupper(tirarAcentos($bairro))){
			$check = 'selected="selected"';
		}else{
			$check = '';
		}
      	echo '<option value="'.$ln['id'].'" '.$check.'>'.strtoupper(tirarAcentos($ln['nome'])).'</option>';
   }
}

?>
