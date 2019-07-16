<?
function salvaLog($mensagem, $email) {
	$ip = $_SERVER['REMOTE_ADDR']; // Salva o IP do visitante
	$hora = date('Y-m-d H:i:s'); // Salva a data e hora atual (formato MySQL)
	$mensagem = mysql_escape_string($mensagem);
	$nome_user = $_SESSION['nomeu'];
	if (empty($nome_user)) { $nome_user = $email; } 
	$sql = "INSERT INTO `logs` VALUES (NULL, '".$hora."', '".$ip."', '".$mensagem."', '".$nome_user."')";
	if (mysql_query($sql)) {
		return true;
	} else {
		return false;
	}
}
?>