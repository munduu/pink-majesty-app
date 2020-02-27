<?php
require_once('../Connections/localhost.php');

$estado = $_POST['estado'];
$cidade = $_POST['cidade'];

$sql2 = "SELECT * FROM tb_estados WHERE uf = '$estado'";
$qr2  = mysql_query($sql2) or die(mysql_error());
$ln2  = mysql_fetch_assoc($qr2);

$iduf = $ln2['iduf'];

//if(!$iduf){
	//$iduf = 31;	
//}

$sql = "SELECT * FROM tb_municipios WHERE iduf = '$iduf' ORDER BY nome";
$qr = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($qr) == 0){
   echo  '<option value="0">Não cidades nesse estado</option>';
   
}else{
	echo  '<option value="0">'.htmlentities('Selecione Cidade').'</option>';
   	while($ln = mysql_fetch_assoc($qr)){
		//if($ln['nome'] == $cidade){
			//$check = 'selected="selected"';
		//}else{
			$check = '';
		//}
    		echo '<option value="'.$ln['id'].'" '.$check.'>'.$ln['nome'].'</option>';
   	}
}

?>
