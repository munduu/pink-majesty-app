<?php
require_once('../Connections/localhost.php');

$cliente = $_POST['cliente'];

$sql2 = "SELECT id FROM tb_cliente WHERE razao_social = '$cliente'";
$qr2 = mysql_query($sql2) or die(mysql_error());
$ln2 = mysql_fetch_assoc($qr2);
$id_cliente = $ln2['id'];

$sql = "SELECT * FROM tb_veiculo WHERE id_cliente = '$id_cliente' ORDER BY modelo";
$qr = mysql_query($sql) or die(mysql_error());

echo 'alert($sql)';
if(mysql_num_rows($qr) == 0){
   echo  '<option value="0">Nenhum Veículo Encontrado</option>';
   
}else{
  	 echo '<option value="0"> Escolha o Veículo </option>';	
 	 echo '<option value="0"> Nenhum dos veículos abaixo</option>';
   while($ln = mysql_fetch_assoc($qr)){
      echo '<option value="'.$ln['id'].'">'.$ln['modelo']." - ".$ln['marca']." (".$ln['placa'].")".'</option>';
   }
   	
}

?>


