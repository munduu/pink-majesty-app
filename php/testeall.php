<?php

ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');

$message = "Importante - Faça a Atualização de seu aplicativo!";
$token_id_user = array();


$sql_e 		= "SELECT token_id FROM tb_login ORDER BY id";
$resultado_e= mysql_query($sql_e) or die(mysql_error());
while($ln_e = mysql_fetch_assoc($resultado_e)){

	if($ln_e['token_id']){
	$token_id_user[] = "'" . $ln_e['token_id'] . "'";}
	
}
	$token_id_user = implode(',',$token_id_user);
	echo sendMessage($message,$token_id_user);	
	echo $token_id_user."<br>";

?>