<?php
$id_cliente            = 1;
$hostname_localhostout = "localhost";
$database_localhostout = "mundoino_ski";
$username_localhostout = "mundoino_root";
$password_localhostout = "1n0v42325sql";
$localhostout = mysql_pconnect($hostname_localhostout, $username_localhostout, $password_localhostout) or trigger_error(mysql_error(),E_USER_ERROR); 

$hostout    = $hostname_localhostout;
$bancoout   = $database_localhostout; 
$usuarioout = $username_localhostout; 
$senhaout   = $password_localhostout; 
mysql_connect($hostout,$usuarioout,$senhaout);
mysql_select_db($bancoout);

?>