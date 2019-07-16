<?php
require_once('../Connections/localhost.php');

$contratante = $_POST['contratante'];

$sql = "SELECT * FROM tb_vaso WHERE id_cliente = '$contratante' ORDER BY identificacao";
$qr = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($qr) == 0){
   echo  '<option value="0">Não ha fluidos nessa classe</option>';
   
}else{
	echo  '<option value="0">'.htmlentities('Selecione Vaso').'</option>';
   while($ln = mysql_fetch_assoc($qr)){
      echo '<option value="'.$ln['id'].'">'.$ln['identificacao'].'</option>';
   }
}

?>
