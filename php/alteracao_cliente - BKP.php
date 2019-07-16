<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$user   	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$tipo   	= anti_sql_injection(strip_tags(trim($_POST['tipo'])));
$token        = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
			$email = $ln['email'];
		}
	}
	
	$sql2 		= "SELECT * FROM tb_cliente WHERE id = '$id_cliente'";
	$resultado2 = mysql_query($sql2) or die(mysql_error());
	$ln2 		= mysql_fetch_assoc($resultado2);
	$nome 		= $ln2['nome'];
	$tel1 		= $ln2['tel1'];
	$tel2 		= $ln2['tel2'];
		
	if($linha > 0){
		if($tipo == 4){
			$sql = "SELECT * FROM tb_enderecos WHERE id_cliente='$id_cliente' ORDER BY principal DESC";
			$resultado   = mysql_query($sql) or die(mysql_error());
			$linha       = mysql_num_rows($resultado);
			$dados.='<h4 align="center">Selecione o endereço principal</h4>';
			if($linha == 0){
				$dados .= '<p class="list-group-item allow-badge widget" data-uib="twitter%20bootstrap/list_item" data-ver="1">Não há enderecos cadastrados</p>'; 
			}else{
				$x=1;	
				while($ln = mysql_fetch_assoc($resultado)){
					$id	 		= $ln['id'];
					$cep 		= $ln['cep'];
					$logradouro = $ln['logradouro'];
					$complemento= $ln['complemento'];
					$bairro 	= $ln['bairro'];
					$numero 	= $ln['numero'];
					$cidade 	= $ln['cidade'];
					
					$sql_m 		= "SELECT * FROM tb_municipios WHERE id='$cidade'";
					$resultado_m= mysql_query($sql_m) or die(mysql_error());
					$ln_m 		= mysql_fetch_assoc($resultado_m);
					//<div class="btn2" align="center" 	style="display:inline-block;width:24%;height:50%;" alt="principal">
					//									style="display:inline-block;width:90%;"
					/*
					<a 	
						class="list-group-item allow-badge widget" 
						data-uib="twitter%20bootstrap/list_item" data-ver="1" 
					></a>
					*/
					$dados.= 
					'
						<div class="alt_endereco_p" alt="'.$ln['id'].'" style="display:inline-block;width:88%;" > '
							.$logradouro.', nº '.$numero.', '.$complemento.' - '.$ln_m['nome'].
						'</div>
						<div class="del_endereco_p" alt="'.$ln['id'].'" style="display:inline-block;width:10%; text-align:center;">'
							.'<p align="right"><img src="images/delete.png" /></p>'.
						'</div>
					';
					$x++;
				}
			}
		}
		if($tipo == 3){
			$dados.='
			<h4 align="center">Troca de Email</h4>
			<div align="center" style="display:inline-block;width:20%;height:50%;">
				E-mail : 
			</div>
			<div align="center" style="display:inline-block;width:75%;height:50%;">
				<input class="form-control email_alterar" type="email" size="30" maxlength="50" value="'.$email.'">
			</div>
			<a class="list-group-item allow-badge widget alt_email" data-uib="twitter%20bootstrap/list_item" data-ver="1" align="center" alt="'.$ln['id'].'">
				Alterar
			</a>';
		}
		if($tipo == 2){
			$dados.='
			<h4 align="center">Troca de Telefones</h4>
			<div align="center" style="display:inline-block;width:20%;height:50%;">Telefone :</div>
			<div align="center" style="display:inline-block;width:75%;height:50%;">
				<input class="form-control tel1_alterar" type="text" size="30" maxlength="12" alt="telefone2" value="'.$tel1.'" onkeypress="mascara(this, "## ####-####")">
			</div>
			
			<div align="center" style="display:inline-block;width:20%;height:50%;">Celular :</div>
			<div align="center" style="display:inline-block;width:75%;height:50%;">
				<input class="form-control tel2_alterar" type="text" size="30" maxlength="13" alt="telefone" value="'.$tel2.'" onkeypress="mascara(this, "## #####-####")">
			</div>
			<div align="center" style="display:inline-block;width:100%;height:50%;">(apenas números com DDD)</div>
			
			<a class="list-group-item allow-badge widget alt_tel" data-uib="twitter%20bootstrap/list_item" data-ver="1" align="center" alt="'.$ln['id'].'">
				Alterar
			</a>';
		}
		if($tipo == 1){
			$dados.='
			<h4 align="center">Troca de Nome</h4>
			<div align="center" style="display:inline-block;width:20%;height:50%;">Nome :</div>
			<div align="center" style="display:inline-block;width:75%;height:50%;">
				<input class="form-control nome_alterar" type="text" size="50" maxlength="150" value="'.$nome.'">
			</div>
			<a class="list-group-item allow-badge widget alt_nome" data-uib="twitter%20bootstrap/list_item" data-ver="1" align="center" alt="'.$ln['id'].'">
				Alterar
			</a>';
		}
	}
}
echo $dados;
?>