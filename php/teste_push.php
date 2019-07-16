<?php

/*require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
require_once("phpmail/class.phpmailer.php");*/

//$autenticacao_google = "AIzaSyCylkqvDALJb6EwYMJkzU8ZlgotufO5tno";


//boris
//APA91bEdB9Qq7SQPMmhD6Fgdg5D2xKl7Wzj7P-7x-dPs91mPPXbF85MocqW3l8XmgRLwEs7LOFEWwh_ebc2n0QdXDXjdvGZEkddDmNJc_fdUO4db71gX9ghtDJSyULyZtmj1_iKjmodW
//otavio
//APA91bGPNObgPspXi_hLvETWGb9itCERt8xYaE74by1aPOm6H8ZVdHQNF29VbmKl1oW-_jQXIFK8ZoWxeWGi-n-l0ujUy07jmnyhdSqQehzW1ozrFL8-_qc

		$sql_c2 		= "SELECT id,token_id FROM tb_login WHERE tipo='Profissional' AND notif='1' ORDER BY id ASC";
		$resultado_c2	= mysql_query($sql_c2) or die(mysql_error());
		while($ln_c2 			= mysql_fetch_assoc($resultado_c2)){
			
			$id            = $ln_c2['id'];
			$token_id_user = $ln_c2['token_id'];
			
			$title    = "Pink Majesty";
			$message  = 'Oba...Novo pedido recebido !'; 
			sendMessage($message,$token_id_user);

		}
?>