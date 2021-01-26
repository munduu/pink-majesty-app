<?php



require_once('Connections/localhost.php');

require_once('function/function.php');

require_once('function/log.php');



$voucher = anti_sql_injection(strip_tags(trim($_REQUEST["email"])));



		echo $insertSQL2 = "UPDATE tb_aluno SET status='ATIVO', ativo='ativo' WHERE email='$voucher'";

		mysql_select_db($database_localhost, $localhost);

		$Result1 = mysql_query($insertSQL2, $localhost) or die(mysql_error("erro!"));

		

		

		/*echo "<script language='JavaScript'>alert('Cadastro validado com sucesso!');</script>"; 

		echo "<script language='JavaScript'>location.href='https://sportbro.com.br/sportbro/index_form.php'</script>";*/

		

?>