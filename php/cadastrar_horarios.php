<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$user  	= anti_sql_injection(strip_tags(trim($_REQUEST['user'])));
$dia  	= anti_sql_injection(strip_tags(trim($_REQUEST['dia'])));
$in_h  	= anti_sql_injection(strip_tags(trim($_REQUEST['in_h'])));
$fm_h  	= anti_sql_injection(strip_tags(trim($_REQUEST['fm_h'])));

if($token=='H424715433852'){
	$sql 		= "SELECT * FROM tb_login ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		$tipo = $ln['tipo'];
		if($user == $c_verif){
			$linha ++;
			$id_colaborador = $ln['id_colaborador'];
		}
	}
	
	if($linha > 0){
		if($dia == 'dom'){$dia = 1;}
		if($dia == 'seg'){$dia = 2;}
		if($dia == 'ter'){$dia = 3;}
		if($dia == 'qua'){$dia = 4;}
		if($dia == 'qui'){$dia = 5;}
		if($dia == 'sex'){$dia = 6;}
		if($dia == 'sab'){$dia = 7;}
		
		if($dia == 'todos'){
			for($dia2=1; $dia2<=7;$dia2++){
				$sql_hor   		= "SELECT * FROM tb_horario_colaborador WHERE id_colaborador = '$id_colaborador' AND dia = '$dia2' ORDER BY id";
				$resultado_hor 	= mysql_query($sql_hor) or die(mysql_error());
				$linha_hor     	= mysql_num_rows($resultado_hor);
					
				if($linha_hor == 1){
					$updateSQL = "UPDATE tb_horario_colaborador SET data_ini='$in_h', data_fim='$fm_h' WHERE  id_colaborador = '$id_colaborador' AND dia = '$dia2'";
					mysql_select_db($database_localhost, $localhost);
					$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error("erro!"));
				}else{
					$insertSQL = "INSERT INTO tb_horario_colaborador(id_colaborador, dia, data_ini, data_fim) VALUES ('$id_colaborador', '$dia2', '$in_h', '$fm_h')";
					mysql_select_db($database_localhost, $localhost);
					$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
					$error = 2;
				}
			}
		}else{
			$sql_hor   		= "SELECT * FROM tb_horario_colaborador WHERE id_colaborador = '$id_colaborador' AND dia = '$dia' ORDER BY id";
			$resultado_hor 	= mysql_query($sql_hor) or die(mysql_error());
			$linha_hor     	= mysql_num_rows($resultado_hor);
			
			if($linha_hor == 1){
				$updateSQL = "UPDATE tb_horario_colaborador SET data_ini='$in_h', data_fim='$fm_h' WHERE  id_colaborador = '$id_colaborador' AND dia = '$dia'";
				mysql_select_db($database_localhost, $localhost);
				$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error("erro!"));
			}else{
				$insertSQL = "INSERT INTO tb_horario_colaborador(id_colaborador, dia, data_ini, data_fim) VALUES ('$id_colaborador', '$dia', '$in_h', '$fm_h')";
				mysql_select_db($database_localhost, $localhost);
				$Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
				$error = 2;
			}
		}
		$dados = "ENDEREÇO CADASTRADO COM SUCESSO!";
	}else{
		$error = 3;
		$dados = "FAÇA LOGIN PRIMEIRO!";
	}	
}else{
	$error = 4;
	$dados = 'Erro: Token inválido!';
}

echo '{"erro":"'.$error.'","dados":"'.$dados.'","linha":"'.$linha.'"}';

?>