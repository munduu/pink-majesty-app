<?php
//error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
header('Access-Control-Allow-Origin: *');

$hostname_localhost = "localhost";

//LOCALHOST
$database_localhost = "igestao_pinkmajesty";
$username_localhost = "igestao_pinkmajesty";
$password_localhost = "mD2DqMCQU";

$url_img = "https://igestaoweb.com.br/pinkmajesty/";

//LOCALHOST
/*$database_localhost = "igestaow_dellas";
$username_localhost = "root";
$password_localhost = "vertrigo";*/

//SMTP E-MAIL
$f_email='pinkmajesty@igestaoweb.com.br';
$f_name ='Pink Majesty ';
$f_smtp ='mail.igestaoweb.com.br';
$f_senha='pink9s3rv3r';

$autenticacao_google = "AIzaSyCylkqvDALJb6EwYMJkzU8ZlgotufO5tno";



// UNIGESTOR PRODUÇÃO



/*$database_localhost = "igestaow_ateliec";

$username_localhost = "igestaow_ateliec";

$password_localhost = "atelie9s3rv3r";*/





// IGESTAOWEB TESTE

//$database_localhost = "unigesto_unigestor";

//$username_localhost = "igestaow_unigest";

//$password_localhost = "unitrac9s3rv3r";



$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error("errroooo"),E_USER_ERROR); 



$host    = $hostname_localhost;

$banco   = $database_localhost; 

$usuario = $username_localhost; 

$senha   = $password_localhost; 

mysql_connect($host,$usuario,$senha);

mysql_select_db($banco);



$sql_prog      = "SELECT * FROM tb_prog WHERE id='1'";

$result_prog   = mysql_query($sql_prog) or die("Erro no banco de dados!!!!!");

$ln_prog       = mysql_fetch_assoc($result_prog);



$prog_empresa       = $ln_prog["prog_empresa"];

$prog_nome          = $ln_prog["prog_nome"];

$prog_sigla         = $ln_prog["prog_sigla"];

$prog_versao        = $ln_prog["prog_versao"];

$prog_email         = $ln_prog["prog_email"];

$prog_tel           = $ln_prog["prog_tel"];

$prog_link          = $ln_prog["prog_link"];

$prog_creitos1      = $ln_prog["prog_creditos1"];

$prog_creitos2      = $ln_prog["prog_creditos"];



$print_result       = true; //true para imprimir aba, false para não imprimir

$save_backup        = true; //true para salvar, false para não salvar

$backup_file_name   = "igestao_unitracker_backup"; //Nome do arquivo, se $save_backup estiver ativado

$backup_file_format = "sql"; // formato da extensão (automatic_backup_140409.sql)

$db_backup          = $database_localhost; // nome do banco de dados para backup

$dir_backup         = "/home/mundoino/public_html/teste/unitracker/bkp/"; // endereço completo do diretório onde será salvo o backup

$dir2_backup        = "/teste/unitracker/bkp/"; // endereço do diretório onde será salvo o backup

//ftp

$dir3_backup        = "/public_html/bkp/"; // endereço completo do diretório onde será salvo o backup

$dados_ftp          = array("host" => "ftp.mundoinova.com.br","usuario" => "backup","senha" => "1n0v42325backup"); // host: endereço ftp; usuario e senhas do ftp



 //CODIGO DA EMPRESA

	$tipo_emp  = "1";  

	//PASTA

	$sigla     =  "unitracker/admin";	



?>
