<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

			$token_id_user    = "7d38dfc6-e594-4ea9-a7fd-5bccc2d2515a";
			$message  		  = '=( Debito indisponível, tente usar Credito'; 
			sendMessage($message,$token_id_user);

?>