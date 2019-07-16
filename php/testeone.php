<?php

ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');

$message = "Teste do envio de Push";

$token_id_user = "13ae2eb0-de8e-4769-93c0-d53ff77b9cfa";

sendMessage($message,$token_id_user);

?>