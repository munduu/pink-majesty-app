<?php
require_once('../Connections/localhost.php');

$classe = $_POST['classe'];

$sql = "SELECT * FROM tb_atributo_dado WHERE id_atributo = '$classe' ORDER BY titulo";
$qr = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($qr) == 0){
   echo  '<option value="0">Não ha fluidos nessa classe</option>';
   
}else{
	echo  '<option value="0">'.htmlentities('Selecione Fluido').'</option>';
   while($ln = mysql_fetch_assoc($qr)){
      echo '<option value="'.$ln['id'].'">'.$ln['titulo'].'</option>';
   }
}

?>
