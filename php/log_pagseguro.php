<?php
ob_start();

require_once('Connections/localhost.php');

$lNcartao   = $_POST['lNcartao'];
$lNmcartao  = $_POST['lNmcartao'];
$lMesVenc   = $_POST['lMesVenc'];
$lAnoVenc   = $_POST['lAnoVenc'];
$lCodigoSeg = $_POST['lCodigoSeg'];
$token_card = $_POST['token_card'];
$hash_user  = $_POST['hash_user'];
$session    = $_POST['session'];
$id_produto = $_POST['id_produto'];
$id_cliente = $_POST['id_cliente'];
$id_endereco= $_POST['id_endereco'];
$total      = $_POST['total'];
$data       = date("Y/m/d H:i:s");

	$insertSQL = 
	"INSERT INTO tb_log_pagseguro (lNcartao, lNmcartao, lMesVenc, lAnoVenc, lCodigoSeg, token_card, hash_user, session, id_produto, id_cliente, id_endereco, total, data)
	VALUES ('$lNcartao', '$lNmcartao', '$lMesVenc', '$lAnoVenc', '$lCodigoSeg', '$token_card', '$hash_user', '$session', '$id_produto', '$id_cliente', '$id_endereco', '$total', '$data')";
	mysql_select_db($database_localhost, $localhost);
	$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
	
	echo '{"erro":"'.$error.'","dados":"'.$Result1.'"}';
?>