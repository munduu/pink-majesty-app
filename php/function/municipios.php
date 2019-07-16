<?php
require_once('../Connections/localhost.php');

$estado = $_POST['estado'];

if(!is_numeric($estado)){
		
		$sql_e = "SELECT iduf FROM tb_estados WHERE uf = '$estado'";
		$qr_e  = mysql_query($sql_e) or die (mysql_error());
		$ln_e  = mysql_fetch_assoc($qr_e);
		$iduf    = $ln_e['iduf'];
	
	}

echo $sql = "SELECT * FROM tb_municipios WHERE iduf = '$iduf' ORDER BY nome";
$qr = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($qr) == 0){
   echo  '<option value="0">Não foi encontrado cidades nesse estado</option>';
   
}else{
	echo  '<option value="0">'.htmlentities('Selecione Cidade').'</option>';
   while($ln = mysql_fetch_assoc($qr)){
      echo '<option value="'.$ln['id'].'">'.$ln['nome'].'</option>';
   }
}

?>
